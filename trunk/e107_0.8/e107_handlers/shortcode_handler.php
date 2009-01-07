<?php

/*
+ ----------------------------------------------------------------------------+
| e107 website system
|
| �Steve Dunstan 2001-2002
| http://e107.org
| jalist@e107.org
|
| Released under the terms and conditions of the
| GNU General Public License (http://gnu.org).
|
| $Source: /cvs_backup/e107_0.8/e107_handlers/shortcode_handler.php,v $
| $Revision: 1.15 $
| $Date: 2009-01-07 19:57:09 $
| $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

function register_shortcode($class, $codes, $path='', $force=false)
{
	global $e_shortcodes;
	if(!is_array($codes))
	{
		$codes = array($codes);
	}
	foreach($codes as $code)
	{
		$code = strtoupper($code);
		if(!array_key_exists($code, $e_shortcodes) || $force == true)
		{
			$e_shortcodes[$code] = array('path' => $path, 'class' => $class, 'function' => $function);
		}
	}
}

class e_shortcode
{
	var $scList;					// The actual code - added by parsing files or when plugin codes encountered. Array key is the shortcode name.
	var $parseSCFiles;				// True if individual shortcode files are to be used
	var $addedCodes;				// Apparently not used
	var $registered_codes;			// Shortcodes added by plugins
	var $scClasses;						//Batch shortcode classes

	function e_shortcode()
	{
		global $pref, $register_sc;

		$this->parseSCFiles = TRUE;			// Default probably never used, but make sure its defined.

		$this->registered_codes = array();	// Think this used to access an undefined variable
		if(varset($pref['shortcode_list'],'') != '')
		{
			foreach($pref['shortcode_list'] as $path=>$namearray)
			{
				foreach($namearray as $code=>$uclass)
				{
					if($code == 'shortcode_config')
					{
						include_once(e_PLUGIN.$path.'/shortcode_config.php');
					}
					else
					{
						$code = strtoupper($code);
						$this->registered_codes[$code]['type'] = 'plugin';
						$this->registered_codes[$code]['path'] = $path;
						$this->registered_codes[$code]['perms'] = $uclass;			// Add this in
					}
				}
			}
		}

		if(isset($register_sc) && is_array($register_sc))
		{
			foreach($register_sc as $code)
			{
				$this->registered_codes[$code]['type'] = 'theme';
			}
		}
	}


	function parseCodes($text, $useSCFiles = true, $extraCodes = '')
	{
		$saveParseSCFiles = $this->parseSCFiles;		// In case of nested call
		$this->parseSCFiles = $useSCFiles;
		if(is_array($extraCodes))
		{
			foreach($extraCodes as $sc => $code)
			{
				$this->scList[$sc] = $code;
			}
		}
		$ret = preg_replace_callback('#\{(\S[^\x02]*?\S)\}#', array(&$this, 'doCode'), $text);
		$this->parseSCFiles = $saveParseSCFiles;		// Restore previous value
		return $ret;
	}

	function doCode($matches)
	{
		global $pref, $e107cache, $menu_pref, $sc_style, $parm, $sql, $e_shortcodes;

		if(strpos($matches[1], E_NL) !== false)
		{
			return $matches[0];
		}

		if (strpos($matches[1], '='))
		{
			list($code, $parm) = explode("=", $matches[1], 2);
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

		if (E107_DBG_BBSC)
		{
			global $db_debug;
			$sql->db_Mark_Time("SC $code");
			$db_debug->logCode(2, $code, $parm, "");
		}

		/* Check for shortcode registered with $e_shortcodes */
		if (is_array($e_shortcodes) && (array_key_exists($code, $e_shortcodes)))
		{
			$_class = $e_shortcodes[$code]['class'];
			$_method = 'get_'.strtolower($code);
			if(!isset($this->scClasses[$_class]))
			{
				if(!class_exists($_class) && $e_shortcodes[$code]['path'])
				{
					include_once($e_shortcodes[$code]['path'].$_class.'.php');
				}
				$this->scClasses[$_class] = new $_class;
			}
			if(is_callable(array($_class, $_method)))
			{
				$ret = $this->scClasses[$_class]->$_method($parm);
			}
		}
		else
		{
			if (is_array($this->scList) && array_key_exists($code, $this->scList))
			{
				$shortcode = $this->scList[$code];
			}
			else
			{
				if ($this->parseSCFiles == TRUE)
				{
					if (is_array($this -> registered_codes) && array_key_exists($code, $this->registered_codes))
					{
						if($this->registered_codes[$code]['type'] == 'plugin')
						{
				if (check_class($this->registered_codes[$code]['perms']))
				{	// Use the plugin 'override' shortcode
							$scFile = e_PLUGIN.strtolower($this->registered_codes[$code]['path']).'/'.strtolower($code).'.sc';
						}
						else
				{	// Look for a core shortcode
				  $scFile = e_FILE."shortcode/".strtolower($code).".sc";
				}
			  }
			  else
						{
							$scFile = THEME.strtolower($code).'.sc';
						}
					}
					else
					{
						$scFile = e_FILE."shortcode/".strtolower($code).".sc";
					}
			if (file_exists($scFile))
					{
						$shortcode = file_get_contents($scFile);
						$this->scList[$code] = $shortcode;
					}
				}
			}

			if (!isset($shortcode))
			{
				if(E107_DBG_BBSC) trigger_error("shortcode not found:{".$code."}", E_USER_ERROR);
				return $matches[0];
			}

			if(E107_DBG_SC)
			{
				echo (isset($scFile)) ? "<br />sc_file= ".str_replace(e_FILE."shortcode/","",$scFile)."<br />" : "";
				echo "<br />sc= <b>$code</b>";
			}

			if(E107_DBG_BBSC)
			{
				trigger_error("starting shortcode {".$code."}", E_USER_ERROR);
			}
			$ret = eval($shortcode);
		}

		if($ret != '' || is_numeric($ret))
		{
			//if $sc_mode exists, we need it to parse $sc_style
			if($sc_mode)
			{
				$code = $code."|".$sc_mode;
			}
			if(isset($sc_style) && is_array($sc_style) && array_key_exists($code,$sc_style))
			{
				if(isset($sc_style[$code]['pre']))
				{
					$ret = $sc_style[$code]['pre'].$ret;
				}
				if(isset($sc_style[$code]['post']))
				{
					$ret = $ret.$sc_style[$code]['post'];
				}
			}
		}
		if (E107_DBG_SC)
		{
			$sql->db_Mark_Time("(SC $code Done)");
		}
		return $ret;
	}

	function parse_scbatch($fname, $type = 'file')
	{
		global $e107cache, $eArrayStorage;
		$cur_shortcodes = array();
		if($type == 'file')
		{
			$batch_cachefile = "nomd5_scbatch_".md5($fname);
			//			$cache_filename = $e107cache->cache_fname("nomd5_{$batchfile_md5}");
			$sc_cache = $e107cache->retrieve_sys($batch_cachefile);
			if(!$sc_cache)
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

		if($sc_batch)
		{
			$cur_sc = '';
			foreach($sc_batch as $line)
			{
				if (trim($line) == 'SC_END')
				{
					$cur_sc = '';
				}
				if ($cur_sc)
				{
					$cur_shortcodes[$cur_sc] .= $line;
				}
				if (preg_match("#^SC_BEGIN (\w*).*#", $line, $matches))
				{
					$cur_sc = $matches[1];
					$cur_shortcodes[$cur_sc] = varset($cur_shortcodes[$cur_sc],'');
				}
			}
			if($type == 'file')
			{
				$sc_cache = $eArrayStorage->WriteArray($cur_shortcodes, false);
				$e107cache->set_sys($batch_cachefile, $sc_cache);
			}
		}

		foreach(array_keys($cur_shortcodes) as $cur_sc)
		{
			if (is_array($this -> registered_codes) && array_key_exists($cur_sc, $this -> registered_codes))
			{
				if ($this -> registered_codes[$cur_sc]['type'] == 'plugin')
				{
					$scFile = e_PLUGIN.strtolower($this -> registered_codes[$cur_sc]['path']).'/'.strtolower($cur_sc).'.sc';
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

?>
