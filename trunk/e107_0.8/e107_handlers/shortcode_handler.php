<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2010 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Shortcode handler
 *
 * $URL$
 * $Id$
 */

if (!defined('e107_INIT'))
{
	exit;
}

/**
 *
 * @package     e107
 * @subpackage	e107_handlers
 * @version     $Id$
 * @author      e107inc
 *
 * e_parse_shortcode - shortcode parser/manager, former e_shortcode
 * e_shortcode - abstract batch class
 */

/**
 * FIXME: to be removed
 */
function register_shortcode($classFunc, $codes, $path = '', $force = false)
{
	return e107::getScParser()->registerShortcode($classFunc, $codes, $path, $force);
}

/**
 * FIXME: to be removed
 */
function setScVar($className, $scVarName, $value)
{
	return e107::getScParser()->setScVar($className, $scVarName, $value);
}

/**
 * FIXME: to be removed (once event calendar changed)
 */
function callScFunc($className, $scFuncName, $param = '')
{
	return e107::getScParser()->callScFunc($className, $scFuncName, $param);
}

/**
 * FIXME: to be removed
 */
function initShortcodeClass($class, $force = false, $eVars = null)
{
	return e107::getScParser()->initShortcodeClass($class, $eVars, $force);
}

class e_parse_shortcode
{
	protected $scList = array(); // The actual code - added by parsing files or when plugin codes encountered. Array key is the shortcode name.
	protected $parseSCFiles; // True if individual shortcode files are to be used
	protected $addedCodes = NULL; 		// Pointer to a class or array to be used on a single call
	protected $registered_codes = array(); // Shortcodes added by plugins TODO make it private
	protected $scClasses = array(); // Batch shortcode classes - TODO make it private
	protected $scOverride = array(); // Array of codes found in override/ dir
	/**
	 * @var e_vars
	 */
	protected $eVars = null;

	function __construct()
	{
		$this->parseSCFiles = true; // Default probably never used, but make sure its defined.
		
		$this->loadOverrideShortcodes();
		$this->loadThemeShortcodes();
		$this->loadPluginShortcodes();
		$this->loadPluginSCFiles();
		$this->loadCoreShortcodes();

	}

	/**
	 * Register shortcode
	 * $classFunc could be function name, class name or object
	 * $code could be 'true' when class name/object is passed to automate the
	 * registration of shortcode methods
	 *
	 * @param mixed $classFunc
	 * @param mixed $codes
	 * @param string $path
	 * @param boolean $force override
	 * @return e_parse_shortcode
	 */
	function registerShortcode($classFunc, $codes, $path = '', $force = false)
	{
		//If codes is set to true, let's go get a list of shortcode methods
		if ($codes === true)
		{
			$codes = array();
			$tmp = get_class_methods($classFunc);
			foreach ($tmp as $c)
			{
				if (strpos($c, 'sc_') === 0)
				{
					$codes[] = substr($c, 3);
				}
			}
			unset($tmp);
		}

		//Register object feature
		$classObj = null;
		if (is_object($classFunc))
		{
			$classObj = $classFunc;
			$classFunc = get_class($classObj);

		}

		//We only register these shortcodes if they have not already been registered in some manner
		//ie theme or other plugin .sc files
		if (is_array($codes))
		{
			foreach ($codes as $code)
			{
				$code = strtoupper($code);
				if ((!$this->isRegistered($code) || $force == true) && !$this->isOverride($code))
				{
					$this->registered_codes[$code] = array('type' => 'class', 'path' => $path, 'class' => $classFunc);
				}
			}

			//register object if required
			if (null !== $classObj && (!$this->isScClass($classFunc) || $force == true))
			{
				$this->scClasses[$classFunc] = $classObj;
			}
		}
		else
		{
			$codes = strtoupper($codes);
			if ((!$this->isRegistered($code) || $force == true) && !$this->isOverride($code))
			{
				$this->registered_codes[$codes] = array('type' => 'func', 'path' => $path, 'function' => $classFunc);
			}
		}
		return $this;
	}

	/**
	 * Add value to already registered SC object
	 *
	 * @param string $className
	 * @param string $scVarName
	 * @param mixed $value
	 * @return e_parse_shortcode
	 */
	public function setScVar($className, $scVarName, $value)
	{
		if ($this->isScClass($className))
		{
			// new way - batch should extend e_shortcode class
			if (method_exists($this->scClasses[$className], 'setScVar'))
			{
				$this->scClasses[$className]->setScVar($scVarName, $value);
			}
			else // Old - DEPRECATED

			{
				$this->scClasses[$className]->$scVarName = $value;
			}
		}
		return $this;
	}

	/**
	 * Call function on an already registered SC object
	 *
	 * @param string $className
	 * @param string $scFuncName
	 * @param mixed $param - passed to function
	 *
	 * @return mixed|boolean - NULL if class/method doesn't exist; otherwise whatever the function returns.
	 */
	public function callScFunc($className, $scFuncName, $param = '')
	{
		if ($this->isScClass($className))
		{
			return method_exists($this->scClasses[$className], $scFuncName) ? call_user_func(array($this->scClasses[$className], $scFuncName), $param) : null;
		}
		return null;
	}

	/**
	 * same as e_parse_shortcode::callScFunc(), but passes the last argument (array)
	 * to the called method as multiple arguments
	 *
	 * @param string $className
	 * @param string $scFuncName
	 * @param array $param - arguments passed to function
	 *
	 * @return mixed|boolean - NULL if class/method doesn't exist; otherwise whatever the function returns.
	 */
	protected function callScFuncA($className, $scFuncName, $args = array())
	{
		if ($this->isScClass($className))
		{
			// avoid warnings
			return method_exists($this->scClasses[$className], $scFuncName) ? call_user_func_array(array($this->scClasses[$className], $scFuncName), $args) : null;
		}
		return null;
	}

	/**
	 * Create shortcode object - don't forget you still can use e_shortcode.php
	 *
	 * @param string $class
	 * @param boolean $force
	 * @return e_shortcode
	 */
	public function initShortcodeClass($class, $force = false)
	{
		if (class_exists($class, false) && ($force || !$this->isScClass($class)))
		{
			$this->scClasses[$class] = new $class();
			return $this->scClasses[$class];
		}
		return null;
	}

	/*public function getScObject($className)
	{
		if (isset($this->scClasses[$className]))
		{
			return $this->scClasses[$className];
		}
		// TODO - throw exception?
		return null;
	}*/

	/**
	 * Get registered SC object
	 * Normally you would use the proxy of this method - e107::getScBatch()
	 * DRAFT!
	 *
	 * <code><?php
	 * // simple use
	 * e107::getScParser()->getScObject('news_shortcodes'); // For Globally Registered shortcodes, including plugins using e_shortcode.php
	 *
	 * // plugin override - e107_plugins/myplug/shortcodes/batch/news_shortcodes.php -> class plugin_myplug_news_shortcodes
	 * e107::getScParser()->getScObject('news_shortcodes', 'myplug', true);
	 *
	 * // more complex plugin override
	 * // e107_plugins/myplug/shortcodes/batch/news2_shortcodes.php -> class plugin_myplug_news2_shortcodes
	 * e107::getScParser()->getScObject('news_shortcodes', 'myplug', 'news2_shortcodes');
	 * </code>
	 * @param string $className
	 * @param string $plugName if true className is used., if string, string value is used.  
	 * @param string $overrideClass if true, $className is used
	 * @return e_shortcode
	 */
	public function getScObject($className, $pluginName = null, $overrideClass = null)
	{
		if(trim($className)==""){ return; }
				
		$_class_fname = $className;
			
		// plugin override
		if($overrideClass)
		{
			if(true === $overrideClass)
			{
				$overrideClass = $className;
			}
			// e.g. class plugin_myplug_news_shortcodes
			$_class_fname = $overrideClass;
			$className = 'plugin_'.$pluginName.'_'.str_replace('/', '_', $overrideClass);
		}
		elseif(is_string($pluginName))
		{
			$className = 'plugin_'.$pluginName.'_'.str_replace('/', '_', $className);
		}
		elseif($pluginName === TRUE)
		{
			$pluginName = str_replace("_shortcodes","",$className);		
		}
	
		if ($this->isScClass($className)) // Includes global Shortcode Classes. ie. e_shortcode.php 
		{
			return $this->scClasses[$className];
		}

		$path = ($pluginName ? e_PLUGIN.$pluginName.'/shortcodes/batch/' : e_CORE.'shortcodes/batch/').$_class_fname.'.php';
		
		
		if (is_readable($path))
		{
			require_once($path);
			if (class_exists($className, false)) // don't allow __autoload()
			{
				// register instance directly to allow override
				// $this->scClasses[$className] = new $className(); // located inside registerClassMethods()
				$this->registerClassMethods($className, $path);
				return $this->scClasses[$className];
			}
			elseif(E107_DBG_BBSC || E107_DBG_SC)
			{
				echo "Couldn't Find Class '".$className."' in <b>".$path."</b>";
			}
		}
		elseif(E107_DBG_BBSC || E107_DBG_SC)
		{
			echo "Couldn't Load: <b>".$path."</b>";
		}

		// TODO - throw exception?
		return null;
	}

	/**
	 * Register any shortcode from the override/shortcodes/ directory
	 *
	 * @return e_parse_shortcode
	 */
	protected function loadOverrideShortcodes()
	{
		if (e107::getPref('sc_override'))
		{
			$tmp = explode(',', e107::getPref('sc_override'));
			foreach ($tmp as $code)
			{
				$code = strtoupper(trim($code));
				$this->registered_codes[$code]['type'] = 'override';
				$this->scOverride[] = $code;
			}
		}
		return $this;
	}

	/**
	 * Register any shortcodes that were registered by the theme
	 * $register_sc[] = 'MY_THEME_CODE'
	 *
	 * @return e_parse_shortcode
	 */
	public function loadThemeShortcodes()
	{
		global $register_sc;

		if (isset($register_sc) && is_array($register_sc))
		{
			foreach ($register_sc as $code)
			{
				if (!$this->isRegistered($code))
				{
					$code = strtoupper($code);
					$this->registered_codes[$code]['type'] = 'theme';
				}
			}
		}
		return $this;
	}

	/**
	 * Register all .sc files found in plugin directories (via pref)
	 *
	 * @return e_parse_shortcode
	 */
	protected function loadPluginSCFiles()
	{
		$pref = e107::getPref('shortcode_list');

		if ($pref)
		{
			foreach ($pref as $path => $namearray)
			{
				foreach ($namearray as $code => $uclass)
				{
					if ($code == 'shortcode_config')
					{
						include_once(e_PLUGIN.$path.'/shortcode_config.php');
					}
					else
					{
						$code = strtoupper($code);
						if (!$this->isRegistered($code))
						{
							$this->registered_codes[$code]['type'] = 'plugin';
							$this->registered_codes[$code]['path'] = $path;
							$this->registered_codes[$code]['perms'] = $uclass; // XXX how we get this?
						}
					}
				}
			}
		}
		return $this;
	}

	/**
	 * Register Plugin Shortcode Batch files (e_shortcode.php) for use site-wide.
	 * Equivalent to multiple .sc files in the plugin's folder.
	 *
	 * @return e_parse_shortcode
	 */
	protected function loadPluginShortcodes()
	{
		$pref = e107::getPref('e_shortcode_list');

		if (!$pref)
		{
			return $this;
		}

		foreach ($pref as $key => $val)
		{
			$path = e_PLUGIN.$key.'/e_shortcode.php';
			$classFunc = $key.'_shortcodes';
			if (!include_once($path))
			{
				continue;
			}

			$this->registerClassMethods($classFunc, $path, false);

			
		}
		return $this;
	}

	/**
	 * Common Auto-Register function for class methods.
	 * @return e_parse_shortcode
	 */
	protected function registerClassMethods($class, $path, $force = false)
	{
		$tmp = get_class_methods($class);
		$className = is_object($class) ? get_class($class) : $class;

		foreach ($tmp as $c)
		{
			if (strpos($c, 'sc_') === 0)
			{
				$sc_func = substr($c, 3);
				$code = strtoupper($sc_func);
				if ($force || !$this->isRegistered($code))
				{
					$this->registered_codes[$code] = array('type' => 'class', 'path' => $path, 'class' => $className);
					
					if (class_exists($className, false))
					{
						$this->scClasses[$className] = new $className(); // Required. Test with e107::getScBatch($className)				
					}
				}
			}
		}
		

		return $this;
	}

	/**
	 * Register Core Shortcode Batches.
	 * FIXME - make it smarter - currently loaded all the time (even on front-end)
	 *
	 * @return e_parse_shortcode
	 */
	function loadCoreShortcodes()
	{
		$coreBatchList = array('admin_shortcodes');

		foreach ($coreBatchList as $cb)
		{
			$path = e_CORE.'shortcodes/batch/'.$cb.".php";
			if (include_once($path))
			{
				$this->registerClassMethods($cb, $path);
			}
		}
		return $this;
	}

	function isRegistered($code)
	{
		return in_array($code, $this->registered_codes);
	}

	public function resetScClass($className, $object)
	{
		if(null === $object)
		{
			unset($this->scClasses[$className]);
		}
		elseif ($this->isScClass($className))
		{
			$this->scClasses[$className] = $object;
		}
		return $this;
	}

	function isScClass($className)
	{
		return isset($this->scClasses[$className]);
	}

	function isOverride($code)
	{
		return in_array($code, $this->scOverride);
	}




	/**
	 *	Parse the shortcodes in some text
	 *
	 *	@param string $text - the text containing the shortcodes
	 *	@param boolean $useSCFiles - if TRUE, all currently registered shortcodes can be used.
	 *								- if FALSE, only those passed are used.
	 *	@param array|object|null $extraCodes - if passed, defines additional shortcodes:
	 *			- if an object or an array, the shortcodes defined by the class of the object are available for this parsing only.
	 *	@param array|null $eVars - if defined, details values to be substituted for shortcodes. Array key (lower case) is shortcode name (upper case)
	 *
	 *	@return string with shortcodes substituted
	 */
	function parseCodes($text, $useSCFiles = true, $extraCodes = null, $eVars = null)
	{
		$saveParseSCFiles = $this->parseSCFiles; // In case of nested call
		$this->parseSCFiles = $useSCFiles;
		$saveVars = $this->eVars; // In case of nested call
		$saveCodes = $this->addedCodes;
		$this->eVars = $eVars;
		$this->addedCodes = NULL;

		//object support
		if (is_object($extraCodes))
		{
			$this->addedCodes = &$extraCodes;
			/*
			$classname = get_class($extraCodes);

			//register once
			if (!$this->isScClass($classname))
			{
				$this->registerShortcode($extraCodes, true);		// Register class if not already registered
			}

			//always overwrite object
			$this->scClasses[$classname] = $extraCodes;
			*/

			// auto-register eVars if possible - call it manually?
			// $this->callScFunc($classname, 'setParserVars', $this->eVars);
		}
		elseif (is_array($extraCodes))
		{
			$this->addedCodes = &$extraCodes;
			/*
			foreach ($extraCodes as $sc => $code)
			{
				$this->scList[$sc] = $code;
			}
			*/
		}
		$ret = preg_replace_callback('#\{(\S[^\x02]*?\S)\}#', array(&$this, 'doCode'), $text);
		$this->parseSCFiles = $saveParseSCFiles; // Restore previous value
		$this->addedCodes = $saveCodes;
		$this->eVars = $saveVars; // restore eVars
		return $ret;
	}


	/**
	 *		Callback looks up and substitutes a shortcode
	 */
	function doCode($matches)
	{
		global $pref, $e107cache, $menu_pref, $sc_style, $parm, $sql;

		if ($this->eVars)
		{
			if ($this->eVars->isVar($matches[1]))
			{
				return $this->eVars->$matches[1];
			}
		}
		if (strpos($matches[1], E_NL) !== false)
		{
			return $matches[0];
		}

		if (strpos($matches[1], '='))
		{
			list($code, $parm) = explode('=', $matches[1], 2);
		}
		else
		{
			$code = $matches[1];
			$parm = '';
		}
		//look for the $sc_mode
		if (strpos($code, '|'))
		{
			list($code, $sc_mode) = explode("|", $code, 2);
			$code = trim($code);
			$sc_mode = trim($sc_mode);
		}
		else
		{
			$sc_mode = '';
		}
		$parm = trim($parm);
		$parm = str_replace(array('[[', ']]'), array('{', '}'), $parm);

		if (E107_DBG_BBSC || E107_DBG_SC || E107_DBG_TIMEDETAILS)
		{
			global $db_debug;
			$sql->db_Mark_Time("SC $code");
			$db_debug->logCode(2, $code, $parm, "");
		}

		if (E107_DBG_SC)
		{
			echo "<strong>";
			echo '{';
			echo $code;
			echo($parm) ? '='.htmlentities($parm) : "";
			echo '}';
			echo "</strong>";
			//	trigger_error('starting shortcode {'.$code.'}', E_USER_ERROR);    // no longer useful - use ?[debug=bbsc]
			}

		$scCode = '';
		$scFile = '';
		$ret = '';
		$_method = 'sc_'.strtolower($code);
		if (is_object($this->addedCodes) && method_exists($this->addedCodes, $_method))
		{
			//It is class-based batch shortcode.  Class already loaded; call the method
			$ret = $this->addedCodes->$_method($parm, $sc_mode);
		}
		elseif (is_array($this->addedCodes) && array_key_exists($code, $this->addedCodes))
		{
			// Its array-based shortcode. Load the code for evaluation later.
			$scCode = $this->addedCodes[$code];
		}
		// Check to see if we've already loaded the .sc file contents
		elseif (array_key_exists($code, $this->scList))
		{
			$scCode = $this->scList[$code];
		}
		else
		{
			//.sc file not yet loaded, or shortcode is new function type
			if ($this->parseSCFiles == true)
			{
				if (array_key_exists($code, $this->registered_codes))
				{
					//shortcode is registered, let's proceed.
					if (isset($this->registered_codes[$code]['perms']))
					{
						if (!check_class($this->registered_codes[$code]['perms']))
						{
							return '';
						}
					}

					switch ($this->registered_codes[$code]['type'])
					{
						case 'class':
							//It is batch shortcode.  Load the class and call the method
							$_class = $this->registered_codes[$code]['class'];
							$_method = 'sc_'.strtolower($code);
							if (!$this->isScClass($_class))
							{
								if (!class_exists($_class) && $this->registered_codes[$code]['path'])
								{
									include_once($this->registered_codes[$code]['path']);
								}
								$this->initShortcodeClass($_class, false);
								if(!$this->isScClass($_class))
								{
									return '';
								}

								// egister passed eVars object on init - call it manually?
								// $this->callScFunc($_class, 'setVars', $this->var);
							}

							// FIXME - register passed eVars object - BAD solution - called on EVERY sc method call
							// XXX - removal candidate - I really think it should be done manually (outside the parser)
							// via e107::getScBatch(name)->setParserVars($eVars);
							// $this->callScFunc($_class, 'setParserVars', $this->eVars);

							$ret = $this->callScFuncA($_class, $_method, array($parm, $sc_mode));
							/*if (method_exists($this->scClasses[$_class], $_method))
							{
								$ret = $this->scClasses[$_class]->$_method($parm, $sc_mode);
							}
							else
							{
								echo $_class.'::'.$_method.' NOT FOUND!<br />';
							}*/

							break;

						case 'func':
							//It is a function, so include the file and call the function
							$_function = $this->registered_codes[$code]['function'];
							if ($this->registered_codes[$code]['path'])
							{
								include_once($this->registered_codes[$code]['path'].strtolower($code).'.php');

							}
							if (function_exists($_function))
							{
								$ret = call_user_func($_function, $parm, $sc_mode);
							}
							break;

						case 'plugin':
							$scFile = e_PLUGIN.strtolower($this->registered_codes[$code]['path']).'/'.strtolower($code).'.sc';
							break;

						case 'override':
							$scFile = e_CORE.'override/shortcodes/'.strtolower($code).'.sc';
							break;

						case 'theme':
							$scFile = THEME.strtolower($code).'.sc';
							break;

					}
				}
				else
				{
					// Code is not registered, let's look for .sc or .php file
					// .php file takes precedence over .sc file
					if (is_readable(e_CORE.'shortcodes/single/'.strtolower($code).'.php'))
					{
						$_function = strtolower($code).'_shortcode';
						$_class = strtolower($code);

						include_once(e_CORE.'shortcodes/single/'.strtolower($code).'.php');

						if (class_exists($_class, false)) // prevent __autoload - performance

						{
							$ret = call_user_func(array($_class, $_function), $parm);
						}
						elseif (function_exists($_function))
						{
							$ret = call_user_func($_function, $parm);
						}
					}
					else
					{
						$scFile = e_CORE.'shortcodes/single/'.strtolower($code).'.sc';
					}
				}
				if ($scFile && file_exists($scFile))
				{
					$scCode = file_get_contents($scFile);
					$this->scList[$code] = $scCode;
				}
			}

			if (!isset($scCode))
			{
				if (E107_DBG_BBSC)
				{
					trigger_error('shortcode not found:{'.$code.'}', E_USER_ERROR);
				}
				return $matches[0];
			}

			if (E107_DBG_SC && $scFile)
			{
				//	echo (isset($scFile)) ? "<br />sc_file= ".str_replace(e_CORE.'shortcodes/single/', '', $scFile).'<br />' : '';
				//	echo "<br />sc= <b>$code</b>";
			}
		}

		if ($scCode)
		{
			$ret = eval($scCode);
		}

		if (isset($ret) && ($ret != '' || is_numeric($ret)))
		{
			//if $sc_mode exists, we need it to parse $sc_style
			if ($sc_mode)
			{
				$code = $code.'|'.$sc_mode;
			}
			if (isset($sc_style) && is_array($sc_style) && array_key_exists($code, $sc_style))
			{
				if (isset($sc_style[$code]['pre']))
				{
					$ret = $sc_style[$code]['pre'].$ret;
				}
				if (isset($sc_style[$code]['post']))
				{
					$ret = $ret.$sc_style[$code]['post'];
				}
			}
		}
		if (E107_DBG_SC || E107_DBG_TIMEDETAILS)
		{
			$sql->db_Mark_Time("(After SC {$code})");
		}
		return isset($ret) ? $ret : '';
	}

	function parse_scbatch($fname, $type = 'file')
	{
		global $e107cache, $eArrayStorage;
		$cur_shortcodes = array();
		if ($type == 'file')
		{
			$batch_cachefile = 'nomd5_scbatch_'.md5($fname);
			//			$cache_filename = $e107cache->cache_fname("nomd5_{$batchfile_md5}");
			$sc_cache = $e107cache->retrieve_sys($batch_cachefile);
			if (!$sc_cache)
			{
				$sc_batch = file($fname);
			}
			else
			{
				$cur_shortcodes = $eArrayStorage->ReadArray($sc_cache);
				$sc_batch = "";
			}
		}
		else
		{
			$sc_batch = $fname;
		}

		if ($sc_batch)
		{
			$cur_sc = '';
			foreach ($sc_batch as $line)
			{
				if (trim($line) == 'SC_END')
				{
					$cur_sc = '';
				}
				if ($cur_sc)
				{
					$cur_shortcodes[$cur_sc] .= $line;
				}
				if (preg_match('#^SC_BEGIN (\w*).*#', $line, $matches))
				{
					$cur_sc = $matches[1];
					$cur_shortcodes[$cur_sc] = varset($cur_shortcodes[$cur_sc], '');
				}
			}
			if ($type == 'file')
			{
				$sc_cache = $eArrayStorage->WriteArray($cur_shortcodes, false);
				$e107cache->set_sys($batch_cachefile, $sc_cache);
			}
		}

		foreach (array_keys($cur_shortcodes) as $cur_sc)
		{
			if (array_key_exists($cur_sc, $this->registered_codes))
			{
				if ($this->registered_codes[$cur_sc]['type'] == 'plugin')
				{
					$scFile = e_PLUGIN.strtolower($this->registered_codes[$cur_sc]['path']).'/'.strtolower($cur_sc).'.sc';
				}
				else
				{
					$scFile = THEME.strtolower($cur_sc).'.sc';
				}
				if (is_readable($scFile))
				{
					$cur_shortcodes[$cur_sc] = file_get_contents($scFile);
				}
			}
		}
		return $cur_shortcodes;
	}
}

class e_shortcode
{
	/**
	 * Stores passed to shortcode handler simple parser object
	 * @var e_vars
	 */
	protected $var = null; // value returned by each shortcode. 

	/**
	 * Storage for shortcode values
	 * @var e_vars
	 */
	protected $scVars = null;

	public function __construct()
	{
		$this->scVars = new e_vars();
	}

	/**
	 * Set external simple parser object
	 *
	 * @param e_vars $eVars
	 * @return e_shortcode
	 */
	public function setParserVars($eVars)
	{
		$this->var = $eVars;
		return $this;
	}

	/**
	 * Get external simple parser object
	 *
	 * @return e_vars
	 */
	public function getParserVars()
	{
		if(null === $this->var) $this->var = new e_vars();
		return $this->var;
	}

	/**
	 * Add shortcode value
	 * <code>e107::getScBatch('class_name')->setScVar('some_property', $some_value);</code>
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return e_shortcode
	 */
	public function setScVar($name, $value)
	{
		$this->scVars->$name = $value;
		return $this;
	}
	
	/**
	 * Add shortcode values
	 * <code>e107::getScBatch('class_name')->addScVars(array('some_property', $some_value));</code>
	 *
	 * @param array $vars
	 * @return e_shortcode
	 */
	public function addScVars($vars)
	{
		$this->scVars->addVars($vars);
		return $this;
	}

	/**
	 * Retrieve shortcode value
	 * <code>$some_value = e107::getScBatch('class_name')->getScVar('some_property');</code>
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getScVar($name)
	{
		return $this->scVars->$name;
	}
	
	/**
	 * Retrieve all shortcode values
	 * <code>$some_value = e107::getScBatch('class_name')->getScVars();</code>
	 *
	 * @return mixed
	 */
	public function getScVars()
	{
		return $this->scVars->getVars();
	}

	/**
	 * Check if shortcode variable is set
	 * <code>if(e107::getScBatch('class_name')->issetScVar('some_property'))
	 * {
	 * 		//do something
	 * }</code>
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function issetScVar($name)
	{
		return isset($this->scVars->$name);
	}

	/**
	 * Unset shortcode value
	 * <code>e107::getScBatch('class_name')->unsetScVar('some_property');</code>
	 *
	 * @param string $name
	 * @return e_shortcode
	 */
	public function unsetScVar($name)
	{
		$this->scVars->$name = null;
		unset($this->scVars->$name);
		return $this;
	}
	
	/**
	 * Empty scvar object data
	 * @return e_shortcode
	 */
	public function emptyScVars()
	{
		$this->scVars->emptyVars();
		return $this;
	}

	/**
	 * Magic setter - bind to eVars object
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->setScVar($name, $value);
	}

	/**
	 * Magic getter - bind to eVars object
	 *
	 * @param string $name
	 * @return mixed value or null if key not found
	 */
	public function __get($name)
	{
		return $this->getScVar($name);
	}

	/**
	 * Magic method - bind to eVars object
	 * NOTE: works on PHP 5.1.0+
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function __isset($name)
	{
		return $this->issetScVar($name);
	}

	/**
	 * Magic method - bind to eVars object
	 * NOTE: works on PHP 5.1.0+
	 *
	 * @param string $name
	 */
	public function __unset($name)
	{
		$this->unsetScVar($name);
	}
}
