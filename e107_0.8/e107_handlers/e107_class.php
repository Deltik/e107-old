<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2010 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Main
 *
 * $URL$
 * $Id$
*/

if (!defined('e107_INIT')) { exit; }


/**
 *
 * @package     e107
 * @category	e107_handlers
 * @version     $Id$
 * @author      e107inc
 *
 *	e107_class - core class with many system-related methods
 */

class e107
{
	/**
	 * IPV6 string for localhost - as stored in DB
	 */
	const LOCALHOST_IP = '0000:0000:0000:0000:0000:ffff:7f00:0001';

	public $server_path;

	public $e107_dirs = array();

	/**
	 * @var array  SQL connection data
	 */
	protected $e107_config_mysql_info = array();

	public $http_path;
	public $https_path;
	public $base_path;
	public $file_path;
	public $relative_base_path;
	public $_ip_cache;
	public $_host_name_cache;

	public $site_theme; // class2 -> check valid theme
	public $http_theme_dir; // class2 -> check valid theme

	/**
	 * Contains reference to global $_E107 array
	 * Assignment is done inside prepare_request() method
	 *
	 * @var array
	 */
	protected $_E107 = array();

	/**
	 * @var string Current request type (http or https)
	 */
	protected $HTTP_SCHEME;

	/**
	 * Used for runtime caching of user extended struct
	 *
	 * @var array
	 * @see get_user_data()
	 */
	public $extended_struct;

	/**
	 * User login name
	 *
	 * @var string
	 * @see init_session()
	 */
	public $currentUser = '';

	/**
	 * Run once load core shortcodes
	 * while initialize SC parser
	 *
	 * @var boolean
	 */
	protected static $_sc_core_loaded = false;

	/**
	 * Singleton instance
	 * Allow class extends - override {@link getInstance()}
	 *
	 * @var e107
	 */
	protected static $_instance = null;

	/**
	 * e107 registry
	 *
	 * @var array
	 */
	private static $_registry = array();

	/**
	 * e107 core config object storage
	 *
	 * @var array
	 */
	protected static $_core_config_arr = array();

	/**
	 * e107 plugin config object storage
	 *
	 * @var array
	 */
	protected static $_plug_config_arr = array();

	/**
	 * Core handlers array
	 * For new/missing handler add
	 * 'class name' => 'path' pair
	 *
	 * Used to auto-load core/plugin handlers
	 * NOTE: aplhabetically sorted! (by class name)
	 *
	 * @see addHandler()
	 * @see setHandlerOverload()
	 * @see getSingleton()
	 * @see getObject()
	 * @var array
	 */
	protected static $_known_handlers = array(
		'ArrayData'						 => '{e_HANDLER}arraystorage_class.php',
		'DHTML_Calendar'				 => '{e_HANDLER}calendar/calendar_class.php',
		'UserHandler'					 => '{e_HANDLER}user_handler.php',
		'comment'						 => '{e_HANDLER}comment_class.php',
		'convert'						 => '{e_HANDLER}date_handler.php',
		'db'							 => '{e_HANDLER}mysql_class.php',
		'e107Email'						 => '{e_HANDLER}mail.php',
		'e107_event'					 => '{e_HANDLER}event_class.php',
		'e107_traffic'					 => '{e_HANDLER}traffic_class.php',
		'e107_user_extended'			 => '{e_HANDLER}user_extended_class.php',
		'e107plugin'					 => '{e_HANDLER}plugin_class.php',
		'eURL'							 => '{e_HANDLER}e107Url.php',
		'e_core_session'				 => '{e_HANDLER}session_handler.php',
		'e_admin_controller'			 => '{e_HANDLER}admin_ui.php',
		'e_admin_controller_ui'			 => '{e_HANDLER}admin_ui.php',
		'e_admin_dispatcher'			 => '{e_HANDLER}admin_ui.php',
		'e_admin_form_ui'				 => '{e_HANDLER}admin_ui.php',
		'e_admin_icons'					 => '{e_HANDLER}admin_ui.php',
		'e_admin_log'					 => '{e_HANDLER}admin_log_class.php',
		'e_admin_model'					 => '{e_HANDLER}model_class.php',
		'e_admin_request'				 => '{e_HANDLER}admin_ui.php',
		'e_admin_response'				 => '{e_HANDLER}admin_ui.php',
		'e_admin_ui'					 => '{e_HANDLER}admin_ui.php',
		'e_file'						 => '{e_HANDLER}file_class.php',
		'e_form'						 => '{e_HANDLER}form_handler.php',
		'e_jshelper'					 => '{e_HANDLER}js_helper.php',
		'e_media'						 => '{e_HANDLER}media_class.php',
		'e_menu'						 => '{e_HANDLER}menu_class.php',
		'e_model'						 => '{e_HANDLER}model_class.php',
		'e_news_item'					 => '{e_HANDLER}news_class.php',
		'e_news_tree'					 => '{e_HANDLER}news_class.php',
		'e_object'						 => '{e_HANDLER}model_class.php',
		'e_online'						 => '{e_HANDLER}online_class.php',
		'e_parse'						 => '{e_HANDLER}e_parse_class.php',
		'e_parse_shortcode'				 => '{e_HANDLER}shortcode_handler.php',
		'e_ranks'						 => '{e_HANDLER}e_ranks_class.php',
		'e_shortcode'					 => '{e_HANDLER}shortcode_handler.php',
		'e_upgrade'						 => '{e_HANDLER}e_upgrade_class.php',
		'e_user_model'					 => '{e_HANDLER}user_model.php',
		'e_user'					 	 => '{e_HANDLER}user_model.php',
		'e_system_user'					 => '{e_HANDLER}user_model.php',
		'e_user_extended_structure_tree' => '{e_HANDLER}user_model.php',
		'e_userperms'					 => '{e_HANDLER}user_handler.php',
		'e_validator'					 => '{e_HANDLER}validator_class.php',
		'e_vars'						 => '{e_HANDLER}model_class.php',
		'ecache'						 => '{e_HANDLER}cache_handler.php',
		'news'							 => '{e_HANDLER}news_class.php',
		'notify'						 => '{e_HANDLER}notify_class.php',
		'override'						 => '{e_HANDLER}override_class.php',
		'redirection'					 => '{e_HANDLER}redirection_class.php',
		'sitelinks'						 => '{e_HANDLER}sitelinks_class.php',
		'themeHandler'					 => '{e_HANDLER}theme_handler.php',
		'user_class'					 => '{e_HANDLER}userclass_class.php',
		'userlogin'					 	 => '{e_HANDLER}login.php',
		'xmlClass'						 => '{e_HANDLER}xml_class.php',
		'language'						 => '{e_HANDLER}language_class.php'
	);

	/**
	 * Overload core handlers array
	 * Format: 'core_class' => array('overload_class', 'overload_path');
	 *
	 * NOTE: to overload core singleton objects, you have to add record to
	 * $_overload_handlers before the first singleton call.
	 *
	 * Example:
	 * <code> array('e_form' => array('plugin_myplugin_form_handler' => '{e_PLUGIN}myplugin/includes/form/handler.php'));</code>
	 *
	 * Used to auto-load core handlers
	 *
	 * @var array
	 */
	protected static $_overload_handlers = array();


	/**
	 * Constructor
	 *
	 * Use {@link getInstance()}, direct instantiating
	 * is not possible for signleton objects
	 *
	 * @return void
	 */
	protected function __construct()
	{
	}

	/**
	 * Cloning is not allowed
	 *
	 */
	private function __clone()
	{
	}

	/**
	 * Get singleton instance (php4 no more supported)
	 *
	 * @return e107
	 */
	public static function getInstance()
	{
		if(null == self::$_instance)
		{
		    self::$_instance = new self();
		}
	  	return self::$_instance;
	}

	/**
	 * Initialize environment path constants
	 * Public proxy to the protected method {@link _init()}
	 *
	 * @return e107
	 */
	public function initCore($e107_paths, $e107_root_path, $e107_config_mysql_info, $e107_config_override = array())
	{
		return $this->_init($e107_paths, $e107_root_path, $e107_config_mysql_info, $e107_config_override);
	}

	/**
	 * Initialize environment path constants while installing e107
	 *
	 * @return e107
	 */
	public function initInstall($e107_paths, $e107_root_path, $e107_config_override = array())
	{
		// Do some security checks/cleanup, prepare the environment
		$this->prepare_request();

		// folder info
		//$this->e107_dirs = $e107_paths;
		$this->setDirs($e107_paths, $e107_config_override);

		// build all paths
		$this->set_paths();
		$this->file_path = $this->fix_windows_paths($e107_root_path)."/";

		// set base path, SSL is auto-detected
		$this->set_base_path();

		// cleanup QUERY_STRING and friends, set  related constants
		$this->set_request();

		// set some core URLs (e_LOGIN/SIGNUP)
		$this->set_urls();

		return $this;
	}

	/**
	 * Resolve paths, will run only once
	 *
	 * @return e107
	 */
	protected function _init($e107_paths, $e107_root_path, $e107_config_mysql_info, $e107_config_override = array())
	{

		if(empty($this->e107_dirs))
		{
			// Do some security checks/cleanup, prepare the environment
			$this->prepare_request();

			// Set default folder (and override paths) if missing from e107_config.php
			$this->setDirs($e107_paths, $e107_config_override);

			// mysql connection info
			$this->e107_config_mysql_info = $e107_config_mysql_info;

			// various constants - MAGIC_QUOTES_GPC, MPREFIX, ...
			$this->set_constants();

			// build all paths
			$this->set_paths();
			$this->file_path = $this->fix_windows_paths($e107_root_path)."/";

			// set base path, SSL is auto-detected
			$this->set_base_path();

			// cleanup QUERY_STRING and friends, set  related constants
			$this->set_request();

			// set some core URLs (e_LOGIN/SIGNUP)
			$this->set_urls();
		}

		return $this;
	}

	/**
	 * Set system folders and override paths
	 * $e107_paths is the 'compact' version of e107_config folder vars ($ADMIN_DIRECTORY, $IMAGES_DIRECTORY, etc)
	 * $e107_config_override is the new override method - it can do it for all server and http paths via
	 * the newly introduced $E107_CONFIG array.
	 *
	 * Overriding just replace _DIRECTORY with _SERVER or _HTTP:
	 * - override server path example:
	 * <code>$E107_CONFIG['SYSTEM_SERVER'] = '/home/user/system/';</code>
	 *
	 * - override http path example:
	 * <code>$E107_CONFIG['MEDIA_VIDEOS_HTTP'] = 'http://static.mydomain.com/videos/';</code>
	 *
	 * @param array $e107_dirs Override folder instructions (*_DIRECTORY vars - e107_config.php)
	 * @param array $e107_config_override Override path insructions ($E107_CONFIG array - e107_config.php)
	 * @return e107
	 */
	public function setDirs($e107_dirs, $e107_config_override = array())
	{
		$override = array_merge((array) $e107_dirs, (array) $e107_config_override);
		// override all
		$this->e107_dirs = array_merge($this->defaultDirs($override), $override);
		return $this;
	}

	/**
	 * Get default e107 folders, root folders can be overridden by passed override array
	 *
	 * @param array $override_root
	 * @param boolean $return_root
	 * @return array
	 */
	public function defaultDirs($override_root = array(), $return_root = false)
	{
		$ret = array_merge(array(
			'ADMIN_DIRECTORY' 		=> 'e107_admin/',
			'IMAGES_DIRECTORY' 		=> 'e107_images/',
			'THEMES_DIRECTORY' 		=> 'e107_themes/',
			'PLUGINS_DIRECTORY' 	=> 'e107_plugins/',
			'FILES_DIRECTORY' 		=> 'e107_files/', // DEPRECATED!!!
			'HANDLERS_DIRECTORY' 	=> 'e107_handlers/',
			'LANGUAGES_DIRECTORY' 	=> 'e107_languages/',
			'DOCS_DIRECTORY' 		=> 'e107_docs/',
			'MEDIA_DIRECTORY' 		=> 'e107_media/',
			'SYSTEM_DIRECTORY' 		=> 'e107_system/',
			'CORE_DIRECTORY' 		=> 'e107_core/',
			'WEB_DIRECTORY' 		=> 'e107_web/',
		), (array) $override_root);

		if($return_root) return $ret;

		$ret['HELP_DIRECTORY'] 				= $ret['DOCS_DIRECTORY'].'help/';

		$ret['MEDIA_IMAGES_DIRECTORY'] 		= $ret['MEDIA_DIRECTORY'].'images/';
		$ret['MEDIA_ICONS_DIRECTORY'] 		= $ret['MEDIA_DIRECTORY'].'icons/';
		$ret['MEDIA_AVATARS_DIRECTORY'] 	= $ret['MEDIA_DIRECTORY'].'avatars/';
		$ret['MEDIA_VIDEOS_DIRECTORY'] 		= $ret['MEDIA_DIRECTORY'].'videos/';
		$ret['MEDIA_FILES_DIRECTORY'] 		= $ret['MEDIA_DIRECTORY'].'files/';
		$ret['MEDIA_UPLOAD_DIRECTORY'] 		= $ret['MEDIA_DIRECTORY'].'temp/';

		$ret['WEB_JS_DIRECTORY'] 			= $ret['WEB_DIRECTORY'].'js/';
		$ret['WEB_CSS_DIRECTORY'] 			= $ret['WEB_DIRECTORY'].'css/';
		$ret['WEB_IMAGES_DIRECTORY'] 		= $ret['WEB_DIRECTORY'].'images/';
		$ret['WEB_PACKS_DIRECTORY'] 		= $ret['WEB_DIRECTORY'].'packages/';

		$ret['DOWNLOADS_DIRECTORY']			= $ret['MEDIA_FILES_DIRECTORY'];
		$ret['UPLOADS_DIRECTORY'] 			= $ret['MEDIA_UPLOAD_DIRECTORY'];

		$ret['CACHE_DIRECTORY'] 			= $ret['SYSTEM_DIRECTORY'].'cache/';
		$ret['CACHE_CONTENT_DIRECTORY'] 	= $ret['CACHE_DIRECTORY'].'content/';
		$ret['CACHE_IMAGE_DIRECTORY'] 		= $ret['CACHE_DIRECTORY'].'images/';
		$ret['CACHE_DB_DIRECTORY'] 			= $ret['CACHE_DIRECTORY'].'db/';

		$ret['LOGS_DIRECTORY'] 				= $ret['SYSTEM_DIRECTORY'].'logs/';

		return $ret;
	}

	/**
	 * Set mysql data
	 *
	 * @return e107
	 */
	public function initInstallSql($e107_config_mysql_info)
	{
		// mysql connection info
		$this->e107_config_mysql_info = $e107_config_mysql_info;

		// various constants - MAGIC_QUOTES_GPC, MPREFIX, ...
		$this->set_constants();

		return $this;
	}

	/**
	 * Get data from the registry
	 * Returns $default if data not found
	 * Replacement of cachevar()
	 *
	 * @param string $id
	 * @return mixed
	 */
	public static function getRegistry($id, $default = null)
	{
		if(isset(self::$_registry[$id]))
		{
			return self::$_registry[$id];
		}

		return $default;
	}

	/**
	 * Add data to the registry - replacement of getcachedvars().
	 * $id is path-like unique id bind to the passed data.
	 * If $data argument is null, $id will be removed from the registry.
	 * When removing objects from the registry, __destruct() method will be auto-executed
	 * if available
	 *
	 * Naming standards (namespaces):
	 * 'area/area_id/storage_type'<br>
	 * where <br>
	 * - area = 'core'|'plugin'|'external' (everything else)
	 * - area_id = core handler id|plugin name (depends on area)
	 * - (optional) storage_type = current data storage stack
	 *
	 * Examples:
	 * - 'core/e107/' - reserved for this class
	 * - 'core/e107/singleton/' - singleton objects repo {@link getSingleton()}
	 *
	 * @param string $id
	 * @param mixed|null $data
	 * @return void
	 */
	public static function setRegistry($id, $data = null, $allow_override = true)
	{
		if(null === $data)
		{
			if(is_object(self::$_registry[$id]) && method_exists(self::$_registry[$id], '__destruct'))
			{
				self::$_registry[$id]->__destruct();
			}
			unset(self::$_registry[$id]);
			return;
		}

		if(!$allow_override && null !== self::getRegistry($id))
		{
			return;
		}

		self::$_registry[$id] = $data;
	}

	/**
	 * Get folder name (e107_config)
	 * Replaces all $(*)_DIRECTORY globals
	 * Example: <code>$e107->getFolder('images')</code>;
	 *
	 * @param string $for
	 * @return string
	 */
	function getFolder($for)
	{
		$key = strtoupper($for).'_DIRECTORY';
		$self = self::getInstance();
		return (isset($self->e107_dirs[$key]) ? $self->e107_dirs[$key] : '');
	}

	/**
	 * Get value from $_E107 config array
	 * Note: will always return false if called before prepare_request() method!
	 *
	 * @param string $key
	 * @return boolean
	 */
	public static function getE107($key)
	{
		$self = self::getInstance();
		return (isset($self->_E107[$key]) && $self->_E107[$key] ? true : false);
	}

	/**
	 * Convenient proxy to $_E107 getter - check if
	 * the system is currently running in cli mode
	 * Note: will always return false if called before prepare_request() method!
	 *
	 * @return boolean
	 */
	public static function isCli()
	{
		return self::getE107('cli');
	}

	/**
	 * Get mysql config var (e107_config.php)
	 * Replaces all $mySQL(*) globals
	 * Example: <code>$e107->getMySQLConfig('prefix');</code>
	 *
	 * @param string $for prefix|server|user|password|defaultdb
	 * @return string
	 */
	function getMySQLConfig($for)
	{
		$key = 'mySQL'.$for;
		return (isset($this->e107_config_mysql_info[$key]) ? $this->e107_config_mysql_info[$key] : '');
	}

	/**
	 * Get known handler path
	 *
	 * @param string $class_name
	 * @param boolean $parse_path [optional] parse path shortcodes
	 * @return string|null
	 */
	public static function getHandlerPath($class_name, $parse_path = true)
	{
		$ret = isset(self::$_known_handlers[$class_name]) ? self::$_known_handlers[$class_name] : null;
		if($parse_path && $ret)
		{
			$ret = self::getParser()->replaceConstants($ret);
		}

		return $ret;
	}

	/**
	 * Add handler to $_known_handlers array on runtime
	 * If class name is array, method will add it (recursion) and ignore $path argument
	 *
	 * @param array|string $class_name
	 * @param string $path [optional]
	 * @return void
	 */
	public static function addHandler($class_name, $path = '')
	{
		if(is_array($class_name))
		{
			foreach ($class_name as $cname => $path)
			{
				self::addHandler($cname, $path);
			}
			return;
		}
		if(!self::isHandler($class_name))
		{
			self::$_known_handlers[$class_name] = $path;
		}
	}

	/**
	 * Check handler presence
	 *
	 * @param string $class_name
	 * @return boolean
	 */
	public static function isHandler($class_name)
	{
		return isset(self::$_known_handlers[$class_name]);
	}

	/**
	 * Get overlod class and path (if any)
	 *
	 * @param object $class_name
	 * @param object $default_handler [optional] return data from $_known_handlers if no overload data available
	 * @param object $parse_path [optional] parse path shortcodes
	 * @return array
	 */
	public static function getHandlerOverload($class_name, $default_handler = true, $parse_path = true)
	{
		$ret = (isset(self::$_overload_handlers[$class_name]) ? self::$_overload_handlers[$class_name] : ($default_handler ? array($class_name, self::getHandlerPath($class_name, false)) : array()));
		if ($parse_path && isset($ret[1]))
		{
			$ret[1] = self::getParser()->replaceConstants($ret[1]);
		}

		return $ret;
	}

	/**
	 * Overload present handler.
	 * If class name is array, method will add it (recursion) and
	 * ignore $overload_class_name and  $overload_path arguments
	 *
	 * @param string $class_name
	 * @param string $overload_name [optional]
	 * @param string $overload_path [optional]
	 * @return void
	 */
	public static function setHandlerOverload($class_name, $overload_class_name = '', $overload_path = '')
	{
		if(is_array($class_name))
		{
			foreach ($class_name as $cname => $overload_array)
			{
				self::setHandlerOverload($cname, $overload_array[0], $overload_array[1]);
			}
			return;
		}
		if(self::isHandler($class_name) && !self::isHandlerOverloadable($class_name))
		{
			self::$_overload_handlers[$class_name] = array($overload_class_name, $overload_path);
		}
	}

	/**
	 * Check if handler is already overloaded
	 *
	 * @param string $class_name
	 * @return boolean
	 */
	public static function isHandlerOverloadable($class_name)
	{
		return isset(self::$_overload_handlers[$class_name]);
	}

	/**
	 * Retrieve singleton object
	 *
	 * @param string $class_name
	 * @param string|boolean $path optional script path
	 * @param string $regpath additional registry path
	 * @return Object
	 */
	public static function getSingleton($class_name, $path = true, $regpath = '')
	{

		$id = 'core/e107/singleton/'.$class_name.$regpath;

		//singleton object found - overload not possible
		if(self::getRegistry($id))
		{

			return self::getRegistry($id);
		}

		//auto detection + overload check
		if(is_bool($path))
		{
			//overload allowed
			if(true === $path && self::isHandlerOverloadable($class_name))
			{
				$tmp = self::getHandlerOverload($class_name);
				$class_name = $tmp[0];
				$path = $tmp[1];
			}
			//overload not allowed
			else
			{
				$path = self::getHandlerPath($class_name);
			}
		}

		if($path && is_string($path) && !class_exists($class_name, false))
		{
			e107_require_once($path); //no existence/security checks here!
			//e107_require_once() is available without class2.php. - see core_functions.php
		}
		if(class_exists($class_name, false))
		{
			e107::setRegistry($id, new $class_name());
		}

		return self::getRegistry($id);
	}

	/**
	 * Retrieve object
	 * Prepare for __autoload
	 *
	 * @param string $class_name
	 * @param mxed $arguments
	 * @param string|boolean $path optional script path
	 * @return object|null
	 */
	public static function getObject($class_name, $arguments = null, $path = true)
	{
		if(true === $path)
		{
			if(isset(self::$_known_handlers[$class_name]))
			{
				$path = self::getParser()->replaceConstants(self::$_known_handlers[$class_name]);
			}
		}

		//auto detection + overload check
		if(is_bool($path))
		{
			//overload allowed
			if(true === $path && self::isHandlerOverloadable($class_name))
			{
				$tmp = self::getHandlerOverload($class_name);
				$class_name = $tmp[0];
				$path = $tmp[1];
			}
			//overload not allowed
			else
			{
				$path = self::getHandlerPath($class_name);
			}
		}

		if($path && is_string($path) && !class_exists($class_name, false))
		{
			e107_require_once($path); //no existence/security checks here!
		}

		if(class_exists($class_name, false))
		{
			if(null !== $arguments) return  new $class_name($arguments);
			return new $class_name();
		}

		trigger_error("Class {$class_name} not found!", E_USER_ERROR);
		return null;
	}

	/**
	 * Retrieve core config handlers.
	 * List of allowed $name values (aliases) could be found
	 * in {@link e_core_pref} class
	 *
	 * @param string $name core|core_backup|emote|menu|search|notify|ipool
	 * @return e_core_pref
	 */
	public static function getConfig($name = 'core', $load = true)
	{
		if(!isset(self::$_core_config_arr[$name]))
		{
			e107_require_once(e_HANDLER.'pref_class.php');
			self::$_core_config_arr[$name] = new e_core_pref($name, $load);
		}

		return self::$_core_config_arr[$name];
	}

	/**
	 * Retrieve core config handler preference value or the core preference array
	 * Shorthand of  self::getConfig()->get()
	 *
	 * @see e_core_pref::get()
	 * @param string $pref_name
	 * @param mixed $default default value if preference is not found
	 * @return mixed
	 */
	public static function getPref($pref_name = '', $default = null)
	{
		return empty($pref_name) ? self::getConfig()->getPref() : self::getConfig()->get($pref_name, $default);
	}

	/**
	 * Advanced version of self::getPref(). $pref_name is parsed,
	 * so that $pref_name = 'x/y/z' will search for value pref_data[x][y][z]
	 * Shorthand of  self::getConfig()->getPref()
	 *
	 * @see e_core_pref::getPref()
	 * @param string $pref_name
	 * @param mixed $default default value if preference is not found
	 * @return mixed
	 */
	public static function findPref($pref_name, $default = null, $index = null)
	{
		return self::getConfig()->getPref($pref_name, $default, $index);
	}

	/**
	 * Retrieve plugin config handlers.
	 * Multiple plugin preference DB rows are supported
	 * Class overload is supported.
	 * Examples:
	 * - <code>e107::getPluginConfig('myplug');</code>
	 * 	 will search for e107_plugins/myplug/e_pref/myplug_pref.php which
	 * 	 should contain class 'e_plugin_myplug_pref' class (child of e_plugin_pref)
	 * - <code>e107::getPluginConfig('myplug', 'row2');</code>
	 * 	 will search for e107_plugins/myplug/e_pref/myplug_row2_pref.php which
	 * 	 should contain class 'e_plugin_myplug_row2_pref' class (child of e_plugin_pref)
	 *
	 * @param string $plug_name
	 * @param string $multi_row
	 * @param boolean $load load from DB on startup
	 * @return e_plugin_pref
	 */
	public static function getPlugConfig($plug_name, $multi_row = '', $load = true)
	{
		if(!isset(self::$_plug_config_arr[$plug_name.$multi_row]))
		{
			e107_require_once(e_HANDLER.'pref_class.php');
			$override_id = $plug_name.($multi_row ? "_{$multi_row}" : '');

			//check (once) for custom plugin pref handler
			if(is_readable(e_PLUGIN.$plug_name.'/e_pref/'.$override_id.'_pref.php'))
			{
				require_once(e_PLUGIN.$plug_name.'/e_pref/'.$override_id.'_pref.php');
				$class_name = 'e_plugin_'.$override_id.'_pref';

				//PHPVER: string parameter for is_subclass_of require PHP 5.0.3+
				if(class_exists($class_name, false) && is_subclass_of('e_plugin_pref', $class_name)) //or e_pref ?
				{
					self::$_plug_config_arr[$plug_name.$multi_row] = new $class_name($load);
					return self::$_plug_config_arr[$plug_name.$multi_row];
				}
			}

			self::$_plug_config_arr[$plug_name.$multi_row] = new e_plugin_pref($plug_name, $multi_row, $load);
		}

		return self::$_plug_config_arr[$plug_name.$multi_row];
	}

	/**
	 * Retrieve plugin preference value.
	 * Shorthand of  self::getPluginConfig()->get()
	 * NOTE: Multiple plugin preference DB rows are NOT supported
	 * This will only look for your default plugin config (empty $milti_row)
	 *
	 * @see e_plugin_pref::get()
	 * @param string $plug_name
	 * @param string $pref_name
	 * @param mixed $default default value if preference is not found
	 * @return mixed
	 */
	public static function getPlugPref($plug_name, $pref_name = '', $default = null)
	{
		return  empty($pref_name) ? self::getPlugConfig($plug_name)->getPref() : self::getPlugConfig($plug_name)->get($pref_name, $default);
	}

	/**
	 * Advanced version of self::getPlugPref(). $pref_name is parsed,
	 * so that $pref_name = 'x/y/z' will search for value pref_data[x][y][z]
	 * Shorthand of  self::getPluginConfig()->getPref()
	 *
	 * @see e_core_pref::getPref()
	 * @param string $pref_name
	 * @param mixed $default default value if preference is not found
	 * @return mixed
	 */
	public static function findPlugPref($plug_name, $pref_name, $default = null, $index = null)
	{
		return self::getPlugConfig($plug_name)->getPref($pref_name, $default, $index);
	}

	/**
	 * Get current theme preference. $pref_name is parsed,
	 * so that $pref_name = 'x/y/z' will search for value pref_data[x][y][z]
	 * Shorthand of  self::getConfig()->getPref('current_theme/sitetheme_pref/pref_name')
	 *
	 * @see e_core_pref::getPref()
	 * @param string $pref_name
	 * @param mixed $default default value if preference is not found
	 * @return mixed
	 */
	public static function getThemePref($pref_name = '', $default = null, $index = null)
	{
		return e107::getConfig()->getPref('sitetheme_pref/'.$pref_name, $default, $index);
	}

	/**
	 * Retrieve text parser singleton object
	 *
	 * @return e_parse
	 */
	public static function getParser()
	{
		return self::getSingleton('e_parse', e_HANDLER.'e_parse_class.php'); //WARNING - don't change this - inifinite loop!!!
	}

	/**
	 * Retrieve sc parser singleton object
	 *
	 * @return e_parse_shortcode
	 */
	public static function getScParser()
	{
		return self::getSingleton('e_parse_shortcode', true);
	}

	/**
	 * Retrieve registered sc object (batch) by class name
	 * Note - '_shortcodes' part of the class/override is added by the method
	 * Override is possible only if class is not already instantiated by shortcode parser
	 *
	 * <code><?php
	 * // core news shortcodes
	 * e107::getScObject('news');
	 * // object of plugin_myplugin_my_shortcodes class -> myplugin/core/shortcodes/batch/my_shortcodes.php
	 * e107::getScObject('my', 'myplugin');
	 * // news override - plugin_myplugin_news_shortcodes extends news_shortcodes -> myplugin/core/shortcodes/batch/news_shortcodes.php
	 * e107::getScObject('news', 'myplugin', true);
	 * // news override - plugin_myplugin_mynews_shortcodes extends news_shortcodes -> myplugin/core/shortcodes/batch/mynews_shortcodes.php
	 * e107::getScObject('news', 'myplugin', 'mynews');
	 * </code>
	 *
	 * @param string $className
	 * @param string $pluginName
	 * @param string|true $overrideClass
	 * @return e_shortcode
	 */
	public static function getScBatch($className, $pluginName = null, $overrideClass = null)
	{
		if(is_string($overrideClass)) $overrideClass .= '_shortcodes';
		return self::getScParser()->getScObject($className.'_shortcodes', $pluginName, $overrideClass);
	}

	/**
	 * Retrieve DB singleton object based on the
	 * $instance_id
	 *
	 * @param string $instance_id
	 * @return e_db_mysql
	 */
	public static function getDb($instance_id = '')
	{
		return self::getSingleton('db', true, $instance_id);
	}

	/**
	 * Retrieve event singleton object
	 *
	 * @return ecache
	 */
	public static function getCache()
	{
		return self::getSingleton('ecache', true);
	}

	/**
	 * Retrieve user-session singleton object
	 *
	 * @return UserHandler
	 */
	public static function getUserSession()
	{
		return self::getSingleton('UserHandler', true);
	}

	/**
	 * Retrieve core session singleton object(s)
	 *
	 * @return e_core_session
	 */
	public static function getSession($namespace = null)
	{
		$id = 'core/e107/session/'.(null === $namespace ? 'e107' : $namespace);
		if(self::getRegistry($id))
		{
			return self::getRegistry($id);
		}
		$session = self::getObject('e_core_session', array('namespace' => $namespace), true);
		self::setRegistry($id, $session);
		return $session;
	}

	/**
	 * Retrieve redirection singleton object
	 *
	 * @return redirection
	 */
	public static function getRedirect()
	{
		return self::getSingleton('redirection', true);
	}

	/**
	 * Retrieve sitelinks singleton object
	 *
	 * @return user_class
	 */
	public static function getSitelinks()
	{
		return self::getSingleton('sitelinks', true);
	}


	/**
	 * Retrieve render singleton object
	 *
	 * @return e107table
	 */
	public static function getRender()
	{
		return self::getSingleton('e107table');
	}

	/**
	 * Retrieve e107Email singleton object
	 *
	 * @return e107Email
	 */
	public static function getEmail()
	{
		return self::getSingleton('e107Email', true);
	}

	/**
	 * Retrieve event singleton object
	 *
	 * @return e107_event
	 */
	public static function getEvent()
	{
		return self::getSingleton('e107_event', true);
	}

	/**
	 * Retrieve array storage singleton object
	 *
	 * @return ArrayData
	 */
	public static function getArrayStorage()
	{
		return self::getSingleton('ArrayData', true);
	}

	/**
	 * Retrieve menu handler singleton object
	 *
	 * @return e_menu
	 */
	public static function getMenu()
	{
		return self::getSingleton('e_menu', true);
	}

	/**
	 * Retrieve URL singleton object
	 *
	 * @return eURL
	 */
	public static function getUrl()
	{
		return self::getSingleton('eURL', true);
	}

	/**
	 * Retrieve file handler singleton or new fresh object
	 *
	 * @param boolean $singleton default true
	 * @return e_file
	 */
	public static function getFile($singleton = true)
	{
		if($singleton)
		{
			return self::getSingleton('e_file', true);
		}
		return self::getObject('e_file', null, true);
	}

	/**
	 * Retrieve form handler singleton or new fresh object
	 *
	 * @param boolean $singleton default false
	 * @param boolean $tabindex passed to e_form when initialized as an object (not singleton)
	 * @return e_form
	 */
	public static function getForm($singleton = false, $tabindex = false)
	{
		if($singleton)
		{
			return self::getSingleton('e_form', true);
		}
		return self::getObject('e_form', $tabindex, true);
	}

	/**
	 * Retrieve admin log singleton object
	 *
	 * @return e_admin_log
	 */
	public static function getAdminLog()
	{
		return self::getSingleton('e_admin_log', true);
	}

	/**
	 * Retrieve date handler singleton object
	 *
	 * @return convert
	 */
	public static function getDateConvert()
	{
		return self::getSingleton('convert', true);
	}

	/**
	 * Retrieve notify handler singleton object
	 *
	 * @return notify
	 */
	public static function getNotify()
	{
		return self::getSingleton('notify', true);
	}

	/**
	 * Retrieve Language handler singleton object
	 *
	 * @return language
	 */
	public static function getLanguage()
	{
		return self::getSingleton('language', true);
	}

	/**
	 * Retrieve Xml handler singleton or new instance object
	 * @param mixed $singleton false - new instance, true - singleton from default registry location, 'string' - registry path
	 * @return xmlClass
	 */
	public static function getXml($singleton = true)
	{
		if($singleton)
		{
			return self::getSingleton('xmlClass', true, (true === $singleton ? '' : $singleton));
		}
		return self::getObject('xmlClass', null, true);
	}

	/**
	 * Retrieve userclass singleton object
	 *
	 * @return user_class
	 */
	public static function getUserClass()
	{
		return self::getSingleton('user_class', true);
	}

	/**
	 * Retrieve user model object.
	 *
	 * @param integer $user_id target user
	 * @param boolean $checkIfCurrent if tru user_id will be compared to current user, if there is a match
	 * 	current user object will be returned
	 * @return e_system_user
	 */
	public static function getSystemUser($user_id, $checkIfCurrent = true)
	{
		if($checkIfCurrent && $user_id && $user_id === self::getUser()->getId())
		{
			return self::getUser();
		}
		$user = self::getRegistry('core/e107/user/'.$user_id);
		if(null === $user)
		{
			$user = self::getObject('e_system_user');
			if($user_id) $user->load($user_id); // self registered on load
		}
		return $user;
	}

	/**
	 * Retrieve current user model object.
	 *
	 * @return e_user
	 */
	public static function getUser()
	{
		$user = self::getRegistry('core/e107/current_user');
		if(null === $user)
		{
			$user = self::getObject('e_user');
			self::setRegistry('core/e107/current_user', $user);
		}
		return $user;
	}

	/**
	 * Retrieve user model object.
	 *
	 * @param integer $user_id target user
	 * @return e_current_user
	 */
	public static function getUserStructure()
	{
		return self::getSingleton('e_user_extended_structure_tree', true);
	}

	/**
	 * Retrieve User Extended handler singleton object
	 * @return e107_user_extended
	 */
	public static function getUserExt()
	{
		return self::getSingleton('e107_user_extended', true);
	}

	/**
	 * Retrieve User Perms (admin perms) handler singleton object
	 * @return comment
	 */
	public static function getUserPerms()
	{
		return self::getSingleton('e_userperms', true);
	}

	/**
	 * Retrieve online users handler singleton object
	 * @return e_ranks
	 */
	public static function getRank()
	{
		return self::getSingleton('e_ranks', true);
	}

	/**
	 * Retrieve online users handler singleton object
	 * @return e_online
	 */
	public static function getOnline()
	{
		return self::getSingleton('e_online', true);
	}

	/**
	 * Retrieve comments handler singleton object
	 * @return comment
	 */
	public static function getComment()
	{
		return self::getSingleton('comment', true);
	}

	/**
	 * Retrieve Media handler singleton object
	 * @return e_media
	 */
	public static function getMedia()
	{
		return self::getSingleton('e_media', true);
	}

	/**
	 * Retrieve message handler singleton
	 * @return eMessage
	 */
	public static function getMessage()
	{
		static $included = false;
		if(!$included)
		{
			e107_require_once(e_HANDLER.'message_handler.php');
			$included = true;
		}
		return eMessage::getInstance();
	}

	/**
	 * Retrieve JS Manager singleton object
	 *
	 * @return e_jsmanager
	 */
	public static function getJs()
	{
		static $included = false;
		if(!$included)
		{
			e107_require_once(e_HANDLER.'js_manager.php');
			$included = true;
		}
		return e_jsmanager::getInstance();
	}

	/**
	 * Retrieve JS Helper object
	 *
	 * @param boolean|string $singleton if true return singleton, if string return singleton object, use string as namespace, default false
	 * @return e_jshelper
	 */
	public static function getJshelper($singleton = false)
	{
		if($singleton)
		{
			return self::getSingleton('e_jshelper', true, (true === $singleton ? '' : $singleton));
		}
		return self::getObject('e_jshelper', null, true);
	}

	/**
	 * Retrieve admin dispatcher instance.
	 * It's instance is self registered (for now, this could change in the future) on initialization (__construct())
	 *
	 * @see e_admin_dispatcher
	 * @return e_admin_dispatcher
	 */
	public static function getAdminUI()
	{
		return self::getRegistry('admin/ui/dispatcher');
	}



	/**
	 * Retrieves config() from addons such as e_url.php, e_cron.php, e_sitelink.php
	 * @param string $addonName eg. e_cron, e_url
	 * @param string $className [optional] (if different from addonName)
	 * @return none
	 */
	public function getAddonConfig($addonName, $className = '')
	{
		$new_addon = array();

		$filename = $addonName; // e.g. 'e_cron';
		if(!$className)
		{
			$className = substr($filename, 2); // remove 'e_'
		}

		$elist = self::getPref($filename.'_list');
		if($elist)
		{
			foreach(array_keys($elist) as $key)
			{
				if(is_readable(e_PLUGIN.$key.'/'.$filename.'.php'))
				{
					include_once(e_PLUGIN.$key.'/'.$filename.'.php');

					$class_name = $key.'_'.$className;
					$array = self::callMethod($class_name, 'config');

					if($array)
					{
						$new_addon[$key] = $array;
					}

				}
			}
		}

		return $new_addon;
	}

	/**
	 * Safe way to call user methods.
	 * @param string $class_name
	 * @param string $method_name
	 * @return boolean FALSE
	 */
	public function callMethod($class_name, $method_name)
	{
		$mes = e107::getMessage();

		if(class_exists($class_name))
		{
			$obj = new $class_name;
			if(method_exists($obj, $method_name))
			{
				$mes->debug('Executing <strong>'.$class_name.' :: '.$method_name.'()</strong>');
				return call_user_func(array($obj, $method_name));
			}
			else
			{
				$mes->debug('Function <strong>'.$class_name.' :: '.$method_name.'()</strong> NOT found.');
			}
		}
		return FALSE;
	}

	/**
	 * Get theme name or path.
	 *
	 * @param mixed $for true (default) - auto-detect (current), admin - admin theme, front - site theme
	 * @param string $path default empty string (return name only), 'abs' - absolute url path, 'rel' - relative server path
	 * @return string
	 */
	public static function getThemeInfo($for = true, $path = '')
	{
		global $user_pref; // FIXME - user model, kill user_pref global

		if(true === $for)
		{
			$for = e_ADMIN_AREA ? 'admin' : 'front';
		}
		switch($for )
		{
			case 'admin':
				$for = e107::getPref('admintheme');
			break;

			case 'front':
				$for = isset($user_pref['sitetheme']) ? $user_pref['sitetheme'] : e107::getPref('sitetheme');
			break;
		}
		if(!$path) return $for;

		switch($path)
		{
			case 'abs':
				$path = e_THEME_ABS.$for.'/';
			break;

			case 'rel':
			default:
				$path = e_THEME.$for.'/';
			break;
		}
		return $path;
	}

	/**
	 * Retrieve core template path
	 * Example: <code>echo e107::coreTemplatePath('admin_icons');</code>
	 *
	 * @see getThemeInfo()
	 * @param string $id part of the path/file name without _template.php part
	 * @param boolean $override default true
	 * @return string relative path
	 */
	public static function coreTemplatePath($id, $override = true)
	{
		$id = str_replace('..', '', $id); //simple security, '/' is allowed
		$override_path = $override ? self::getThemeInfo($override, 'rel').'templates/'.$id.'_template.php' : null;
		$default_path = e_THEME.'templates/'.$id.'_template.php';

		return ($override_path && is_readable($override_path) ? $override_path : $default_path);
	}

	/**
	 * Retrieve plugin template path
	 * Override path could be forced to front- or back-end via
	 * the $override parameter e.g. <code> e107::templatePath(plug_name, 'my', 'front')</code>
	 * Example:
	 * <code>
	 * echo e107::templatePath(plug_name, 'my');
	 * // result is something like:
	 * // e107_themes/current_theme/templates/plug_name/my_template.php
	 * // or if not found
	 * // e107_plugins/plug_name/templates/my_template.php
	 * </code>
	 *
	 * @see getThemeInfo()
	 * @param string $plug_name plugin name
	 * @param string $id part of the path/file name without _template.php part
	 * @param boolean|string $override default true
	 * @return string relative path
	 */
	public static function templatePath($plug_name, $id, $override = true)
	{
		$id = str_replace('..', '', $id); //simple security, '/' is allowed
		$plug_name = preg_replace('#[^a-z0-9_]#i', '', $plug_name); // only latin allowed, so \w not a solution since PHP5.3
		$override_path = $override ? self::getThemeInfo($override, 'rel').'templates/'.$plug_name.'/'.$id.'_template.php' : null;
		$default_path = e_PLUGIN.$plug_name.'/templates/'.$id.'_template.php';

		return ($override_path && is_readable($override_path) ? $override_path : $default_path);
	}

	/**
	 * Get core template. Use this method for templates, which are following the
	 * new template standards:
	 * - template variables naming conventions
	 * - one array variable per template only
	 * - theme override is made now by current_theme/templates/ folder
	 *
	 * <br><br>Results are cached (depending on $id and $override so it's safe to use
	 * this method e.g. in loop for retrieving a template string. If template (or template key) is not
	 * found, <b>NULL</b> is returned.<br><br>
	 *
	 * Example usage: <code>e107::getCoreTemplate('user', 'short_start');</code>
	 * Will search for:
	 * - e107_themes/current_frontend_theme/templates/user_template.php (if $override is true)
	 * - e107_themes/templates/user_template.php (if override not found or $override is false)
	 * - $USER_TEMPLATE array which contains all user templates
	 * - $USER_TEMPLATE['short_start'] (if key is null, $USER_TEMPLATE will be returned)
	 *
	 * @param string $id - file prefix, e.g. user for user_template.php
	 * @param string|null $key
	 * @param boolean $override see {@link getThemeInfo()}
	 * @param boolean $merge merge theme with core templates, default is false
	 * @param boolean $info retrieve template info only
	 * @return string|array
	 */
	public static function getCoreTemplate($id, $key = null, $override = true, $merge = false, $info = false)
	{
		$reg_path = 'core/e107/templates/'.$id.($override ? '/ext' : '');
		$path = self::coreTemplatePath($id, $override);
		$id = str_replace('/', '_', $id);
		$ret = self::_getTemplate($id, $key, $reg_path, $path, $info);
		if(!$merge || !$override || !is_array($ret))
		{
			return $ret;
		}

		// merge
		$reg_path = 'core/e107/templates/'.$id;
		$path = self::coreTemplatePath($id, false);
		$id = str_replace('/', '_', $id);
		$ret_core = self::_getTemplate($id, $key, $reg_path, $path, $info);

		return (is_array($ret_core) ? array_merge($ret_core, $ret) : $ret);
	}

	/**
	 * Get plugin template. Use this method for plugin templates, which are following the
	 * new template standards:
	 * - template variables naming conventions
	 * - one array variable per template only
	 * - theme override is made now by current_theme/templates/plugin_name/ folder
	 *
	 * <br><br>Results are cached (depending on $id and $override so it's safe to use
	 * this method e.g. in loop for retrieving a template string. If template (or template key) is not
	 * found, <b>NULL</b> is returned.<br><br>
	 *
	 * Example usage: <code>e107::getTemplate('user', 'short_start');</code>
	 * Will search for:
	 * - e107_themes/current_frontend_theme/templates/user_template.php (if $override is true)
	 * - e107_themes/templates/user_template.php (if override not found or $override is false)
	 * - $USER_TEMPLATE array which contains all user templates
	 * - $USER_TEMPLATE['short_start'] (if key is null, $USER_TEMPLATE will be returned)
	 *
	 * @param string $plug_name if null getCoreTemplate method will be called
	 * @param string $id - file prefix, e.g. calendar for calendar_template.php
	 * @param string|null $key
	 * @param boolean $override see {@link getThemeInfo()}
	 * @param boolean $merge merge theme with plugin templates, default is false
	 * @param boolean $info retrieve template info only
	 * @return string|array
	 */
	public static function getTemplate($plug_name, $id, $key = null, $override = true, $merge = false, $info = false)
	{
		if(null === $plug_name)
		{
			return self::getCoreTemplate($id, $key, $override, $merge, $info);
		}
		$reg_path = 'plugin/'.$plug_name.'/templates/'.$id.($override ? '/ext' : '');
		$path = self::templatePath($plug_name, $id, $override);
		$id = str_replace('/', '_', $id);
		$ret = self::_getTemplate($id, $key, $reg_path, $path, $info);
		if(!$merge || !$override || !is_array($ret))
		{
			return $ret;
		}

		// merge
		$reg_path = 'plugin/'.$plug_name.'/templates/'.$id;
		$path = self::templatePath($plug_name, $id, false);
		$id = str_replace('/', '_', $id);
		$ret_plug = self::_getTemplate($id, $key, $reg_path, $path, $info);

		return (is_array($ret_plug) ? array_merge($ret_plug, $ret) : $ret);
	}

	/**
	 * Get Template Info array.
	 * Note: Available only after getTemplate()/getCoreTemplate() call
	 *
	 * @param string $plug_name if null - search for core template
	 * @param string $id
	 * @param string $key
	 * @param boolean $override
	 * @param boolean $merge
	 * @return array
	 */
	public function getTemplateInfo($plug_name = null, $id, $key = null, $override = true, $merge = false)
	{
		if($plug_name)
		{
			$ret = self::getTemplate($plug_name, $id, null, $override, $merge, true);
		}
		else
		{
			$ret = self::getCoreTemplate($id, null, $override, $merge, true);
		}
		if($key && isset($ret[$key]) && is_array($ret[$key]))
		{
			return $ret[$key];
		}
		return $ret;
	}

	/**
	 * Return a list of available template IDs for a plugin(eg. $MYTEMPLATE['my_id'] -> array('id' => 'My Id'))
	 * @param string $plugin_name
	 * @param string $template_id [optional] if different from $plugin_name;
	 * @param mixed $where true - current theme, 'admin' - admin theme, 'front' (default)  - front theme
	 * @param boolean $merge merge theme with core/plugin layouts, default is false
	 * @param boolean $allinfo reutrn nimerical array of templates and all available template information
	 * @return array
	 */
	public static function getLayouts($plugin_name, $template_id = '', $where = 'front', $filter_mask = '', $merge = false, $allinfo = true)
	{
		if(!$plugin_name) // Core template
		{
			$tmp = self::getCoreTemplate($template_id, null, $where, $merge);
			$tmp_info = self::getTemplateInfo(null, $template_id, null, $where, $merge);
		}
		else // Plugin template
		{
			$id = (!$template_id) ? $plugin_name : $template_id;
			$tmp = self::getTemplate($plugin_name, $id, null, $where, $merge);
			$tmp_info = self::getTemplateInfo($plugin_name, $id, null, $where, $merge);
		}

		$templates = array();
		if(!$filter_mask)
		{
			$filter_mask = array();
		}
		elseif(!is_array($filter_mask))
		{
			$filter_mask = array($filter_mask);
		}
		foreach($tmp as $key => $val)
		{
			$match = true;
			if($filter_mask)
			{
				$match = false;
				foreach ($filter_mask as $mask)
				{
					if(preg_match($mask, $key)) //e.g. retrieve only keys starting with 'layout_'
					{
						$match = true;
						break;
					}
				}
				if(!$match) continue;
			}
			if(isset($tmp_info[$key]))
			{
				$templates[$key] = defset($tmp_info[$key]['title'], $tmp_info[$key]['title']);
				continue;
			}
			$templates[$key] = implode(' ', array_map('ucfirst', explode('_', $key))); //TODO add LANS?
		}
		return ($allinfo ? array($templates, $tmp_info) : $templates);
	}

	/**
	 * More abstsract template loader, used
	 * internal in {@link getTemplate()} and {@link getCoreTemplate()} methods
	 * If $info is set to true, only template informational array will be returned
	 *
	 * @param string $id
	 * @param string|null $key
	 * @param string $reg_path
	 * @param string $path
	 * @param boolean $info
	 * @return string|array
	 */
	public static function _getTemplate($id, $key, $reg_path, $path, $info = false)
	{
		$regPath = $reg_path;
		$var = strtoupper($id).'_TEMPLATE';
		$regPathInfo = $reg_path.'/info';
		$var_info = strtoupper($id).'_INFO';

		if(null === self::getRegistry($regPath))
		{
			(deftrue('E107_DEBUG_LEVEL') ? include_once($path) : @include_once($path));
			self::setRegistry($regPath, (isset($$var) ? $$var : array()));
		}
		if(null === self::getRegistry($regPathInfo))
		{
			self::setRegistry($regPathInfo, (isset($$var_info) && is_array($$var_info) ? $$var_info : array()));
		}

		$ret = (!$info ? self::getRegistry($regPath) : self::getRegistry($regPathInfo));
		if(!$key)
		{
			return $ret;
		}
		return ($ret && is_array($ret) && isset($ret[$key]) ? $ret[$key] : '');
	}

	/**
	 * Load language file, replacement of include_lan()
	 *
	 * @param string $path
	 * @param boolean $force
	 * @return string
	 */
	public static function includeLan($path, $force = false)
	{
		if (!is_readable($path))
		{
			if (self::getPref('noLanguageSubs') || (e_LANGUAGE == 'English'))
			{
				return FALSE;
			}
			$path = str_replace(e_LANGUAGE, 'English', $path);
		}
		$ret = ($force) ? include($path) : include_once($path);
		return (isset($ret)) ? $ret : "";
	}

	/**
	 * Simplify importing of core Language files.
	 * All inputs are sanitized.
	 * Core Exceptions as e_LANGUAGE.'.php' and e_LANGUAGE.'_custom.php' are manually loaded. (see class2.php)
	 *
	 * Examples:
	 * <code><?php
	 * 	// import defeinitions from /e107_languages/[CurrentLanguage]/lan_comment.php</code>
	 * 	e107::coreLan('comment');
	 *
	 * 	// import defeinitions from /e107_languages/[CurrentLanguage]/admin/lan_banlist.php
	 * 	e107::coreLan('banlist', true);
	 * </code>
	 *
	 * @param string $fname filename without the extension part (e.g. 'comment')
	 * @param boolean $admin true if it's an administration language file
	 * @return void
	 */
	public static function coreLan($fname, $admin = false)
	{
		$cstring  = 'corelan/'.e_LANGUAGE.'_'.$fname.($admin ? '_admin' : '_front');
		if(e107::getRegistry($cstring)) return;

		$fname = ($admin ? 'admin/' : '').'lan_'.preg_replace('/[^\w]/', '', $fname).'.php';
		$path = e_LANGUAGEDIR.e_LANGUAGE.'/'.$fname;

		e107::setRegistry($cstring, true);
		self::includeLan($path, false);
	}

	/**
	 * Simplify importing of plugin Language files (following e107 plugin structure standards).
	 * All inputs are sanitized.
	 *
	 * Examples:
	 * <code><?php
	 * 	// import defeinitions from /e107_plugins/forum/languages/[CurrentLanguage]/lan_forum.php</code>
	 * 	e107::plugLan('forum', 'lan_forum');
	 *
	 * 	// import defeinitions from /e107_plugins/featurebox/languages/[CurrentLanguage]_admin_featurebox.php</code>
	 * 	e107::plugLan('featurebox', 'admin_featurebox', true);
	 *
	 * 	// import defeinitions from /e107_plugins/myplug/languages/[CurrentLanguage].php
	 * 	e107::plugLan('myplug');
	 *
	 * 	// import defeinitions from /e107_plugins/myplug/languages/[CurrentLanguage].php
	 * 	e107::plugLan('myplug', 'admin/common');
	 * </code>
	 *
	 * @param string $plugin plugin name
	 * @param string $fname filename without the extension part (e.g. 'common')
	 * @param boolean $flat false (default, preferred) Language folder structure; true - prepend Language to file name
	 * @return void
	 */
	public static function plugLan($plugin, $fname = '', $flat = false)
	{
		$cstring  = 'pluglan/'.e_LANGUAGE.'_'.$plugin.'_'.$fname.($flat ? '_1' : '_0');
		if(e107::getRegistry($cstring)) return;

		$plugin = preg_replace('/[^\w]/', '', $plugin);

		if($fname) $fname = e_LANGUAGE.($flat ? '_' : '/').preg_replace('#[^\w/]#', '', $fname);
		else $fname = e_LANGUAGE;

		$path = e_PLUGIN.$plugin.'/languages/'.$fname.'.php';

		e107::setRegistry($cstring, true);
		self::includeLan($path, false);
	}

	/**
	 * Routine looks in standard paths for language files associated with a plugin or
	 * theme - primarily for core routines, which won't know for sure where the author has put them.
	 * $unitName is the name (directory path) of the plugin or theme
	 * $type determines what is to be loaded:
	 * - 'runtime' - the standard runtime language file for a plugin
	 * - 'admin' - the standard admin language file for a plugin
	 * - 'theme' - the standard language file for a plugin (these are usually pretty small, so one is enough)
	 * Otherwise, $type is treated as part of a filename within the plugin's language directory,
	 * prefixed with the current language.
	 * Returns FALSE on failure (not found).
	 * Returns the include_once error return if there is one
	 * Otherwise returns an empty string.
	 * Note - if the code knows precisely where the language file is located, use {@link getLan()}
	 * $pref['noLanguageSubs'] can be set TRUE to prevent searching for the English files if
	 * the files for the current site language don't exist.
	 *
	 * @param string $unitName
	 * @param string $type predefined types are runtime|admin|theme
	 * @return boolean|string
	 */
	public static function loadLanFiles($unitName, $type='runtime')
	{
		global $pref;
		switch ($type)
		{
			case 'runtime' :
				$searchPath[1] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'_'.$unitName.'.php';
				$searchPath[2] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'/'.$unitName.'.php';
				$searchPath[3] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'.php'; // menu language file.
				break;
			case 'admin' :
				$searchPath[1] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'_admin_'.$unitName.'.php';
				$searchPath[2] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'/'.'admin_'.$unitName.'.php';
				$searchPath[3] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'/admin/'.e_LANGUAGE.'.php';
				break;
			case 'theme' :
				$searchPath[1] = e_THEME.$unitName.'/languages/'.e_LANGUAGE.'_'.$unitName.'.php';
				$searchPath[2] = e_THEME.$unitName.'/languages/'.e_LANGUAGE.'/'.$unitName.'.php';
				break;
			default :
				$searchPath[1] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'_'.$type.'.php';
				$searchPath[2] = e_PLUGIN.$unitName.'/languages/'.e_LANGUAGE.'/'.$type.'.php';
		}
		foreach ($searchPath as $s)			// Look for files in current language first - should usually be found
		{
			if (is_readable($s))
			{
				$ret = include_once($s);
				return (isset($ret)) ? $ret : "";
			}
		}
		if (varsettrue($pref['noLanguageSubs']) || (e_LANGUAGE == 'English'))
		{
			return FALSE;		// No point looking for the English files twice
		}

		foreach ($searchPath as $s)			// Now look for the English files
		{
			$s = str_replace(e_LANGUAGE, 'English', $s);
			if (is_readable($s))
			{
				$ret = include_once($s);
				return (isset($ret)) ? $ret : "";
			}
		}
		return FALSE;		// Nothing found
	}

	/**
	 * Prepare e107 environment
	 * This is done before e107_dirs initilization and [TODO] config include
	 * @param bool $checkS basic security check (0.7 like), will be extended in the future
	 * @return e107
	 */
	public function prepare_request($checkS = true)
	{

		// Block common bad agents / queries / php issues.
		array_walk($_SERVER,  array('self', 'filter_request'), '_SERVER');
		if (isset($_GET)) array_walk($_GET,     array('self', 'filter_request'), '_GET');
		if (isset($_POST)) array_walk($_POST,    array('self', 'filter_request'), '_POST');
		if (isset($_COOKIE)) array_walk($_COOKIE,  array('self', 'filter_request'), '_COOKIE');
		if (isset($_REQUEST)) array_walk($_REQUEST, array('self', 'filter_request'), '_REQUEST');

		// TODO - better ajax detection method (headers when possible)
		define('e_AJAX_REQUEST', isset($_REQUEST['ajax_used']));
		unset($_REQUEST['ajax_used']); // removed because it's auto-appended from JS (AJAX), could break something...

		//$GLOBALS['_E107'] - minimal mode - here because of the e_AJAX_REQUEST
		if(isset($GLOBALS['_E107']['minimal']) || e_AJAX_REQUEST)
		{
			$_e107vars = array('forceuserupdate', 'online', 'theme', 'menus', 'prunetmp');

			// lame but quick - allow online when ajax request only, additonal checks are made in e_online class
			if(e_AJAX_REQUEST && !isset($GLOBALS['_E107']['online']) && !isset($GLOBALS['_E107']['minimal'])) unset($_e107vars[1]);

			foreach($_e107vars as $v)
			{
				$noname = 'no_'.$v;
				if(!isset($GLOBALS['_E107'][$v]))
				{
					$GLOBALS['_E107'][$noname] = 1;
				}
				unset($GLOBALS['_E107'][$v]);
			}
		}

		// we can now start use $e107->_E107
		if(isset($GLOBALS['_E107']) && is_array($GLOBALS['_E107'])) $this->_E107 = & $GLOBALS['_E107'];

		// remove ajax_used=1 from query string to avoid SELF problems, ajax should always be detected via e_AJAX_REQUEST constant
		$_SERVER['QUERY_STRING'] = trim(str_replace(array('ajax_used=1', '&&'), array('', '&'), $_SERVER['QUERY_STRING']), '&');

		/* PathInfo doesn't break anything, URLs should be always absolute. Disabling the below forever.
		// e107 uses relative url's, which are broken by "pretty" URL's. So for now we don't support / after .php
		if(($pos = strpos($_SERVER['PHP_SELF'], '.php/')) !== false) // redirect bad URLs to the correct one.
		{
			$new_url = substr($_SERVER['PHP_SELF'], 0, $pos+4);
			$new_loc = ($_SERVER['QUERY_STRING']) ? $new_url.'?'.$_SERVER['QUERY_STRING'] : $new_url;
			header('Location: '.$new_loc);
			exit();
		}
		*/

		// If url contains a .php in it, PHP_SELF is set wrong (imho), affecting all paths.  We need to 'fix' it if it does.
		$_SERVER['PHP_SELF'] = (($pos = stripos($_SERVER['PHP_SELF'], '.php')) !== false ? substr($_SERVER['PHP_SELF'], 0, $pos+4) : $_SERVER['PHP_SELF']);

		// setup some php options
		e107::ini_set('magic_quotes_runtime',     0);
		e107::ini_set('magic_quotes_sybase',      0);
		e107::ini_set('arg_separator.output',     '&amp;');
		e107::ini_set('session.use_only_cookies', 1);
		e107::ini_set('session.use_trans_sid',    0);

		//  Ensure thet '.' is the first part of the include path
		$inc_path = explode(PATH_SEPARATOR, ini_get('include_path'));
		if($inc_path[0] != '.')
		{
			array_unshift($inc_path, '.');
			$inc_path = implode(PATH_SEPARATOR, $inc_path);
			e107::ini_set('include_path', $inc_path);
		}
		unset($inc_path);

		return $this;
	}

	/**
	 * Filter User Input - used by array_walk in prepare_request method above.
	 * @param string $input array value
	 * @param string $key	array key
	 * @param string $type	array type _SESSION, _GET etc.
	 * @return
	 */
	public static function filter_request($input,$key,$type)
	{
		if (is_array($input))
		{
			return array_walk($input, array('self', 'filter_request'), $type);
		}

		if($type == "_SERVER")
		{
			if(($key == "QUERY_STRING") && strpos(strtolower($input),"=http")!==FALSE)
			{
				exit();
			}

			if(($key == "HTTP_USER_AGENT") && strpos($input,"libwww-perl")!==FALSE)
			{
				exit();
			}
		}

		if(strpos(str_replace('.', '', $input), '22250738585072011') !== FALSE) // php-bug 53632
		{
			exit();
		}
	}



	/**
	 * Set base system path
	 * @return e107
	 */
	public function set_base_path($force = null)
	{
		$ssl_enabled = (null !== $force) ? $force : $this->isSecure();//(self::getPref('ssl_enabled') == 1);
		$this->base_path = $ssl_enabled ?  $this->https_path : $this->http_path;
		return $this;
	}

	/**
	 * Set various system environment constants
	 * @return e107
	 */
	public function set_constants()
	{
		define('MAGIC_QUOTES_GPC', (ini_get('magic_quotes_gpc') ? true : false));

		define('MPREFIX', $this->getMySQLConfig('prefix')); // mysql prefix

		define('CHARSET', 'utf-8'); // set CHARSET for backward compatibility

		// Define the domain name and subdomain name.
		if($_SERVER['HTTP_HOST'] && is_numeric(str_replace(".","",$_SERVER['HTTP_HOST'])))
		{
			$srvtmp = '';  // Host is an IP address.
		}
		else
		{
			$srvtmp = explode('.',str_replace('www.', '', $_SERVER['HTTP_HOST']));
		}

		define('e_SUBDOMAIN', (count($srvtmp)>2 && $srvtmp[2] ? $srvtmp[0] : false)); // needs to be available to e107_config.

		if(e_SUBDOMAIN)
		{
		   	unset($srvtmp[0]);
		}

		define('e_DOMAIN',(count($srvtmp) > 1 ? (implode('.', $srvtmp)) : false)); // if it's an IP it must be set to false.

		define('e_UC_PUBLIC', 0);
		define('e_UC_MAINADMIN', 250);
		define('e_UC_READONLY', 251);
		define('e_UC_GUEST', 252);
		define('e_UC_MEMBER', 253);
		define('e_UC_ADMIN', 254);
		define('e_UC_NOBODY', 255);

		return $this;
	}

	/**
	 * Relaitve server path - set_path() helper
	 * @param string $dir
	 * @return string
	 */
	public function get_override_rel($dir)
	{
		if(isset($this->e107_dirs[$dir.'_SERVER']))
		{
			return $this->e107_dirs[$dir.'_SERVER'];
		}
		return e_BASE.$this->e107_dirs[$dir.'_DIRECTORY'];
	}

	/**
	 * Absolute HTTP path - set_path() helper
	 * @param string $dir
	 * @return string
	 */
	public function get_override_http($dir)
	{
		if(isset($this->e107_dirs[$dir.'_HTTP']))
		{
			return $this->e107_dirs[$dir.'_HTTP'];
		}
		return e_HTTP.$this->e107_dirs[$dir.'_DIRECTORY'];
	}

	/**
	 * Set all environment vars and constants
	 * FIXME - remove globals
	 * @return e107
	 */
	public function set_paths()
	{
		// ssl_enabled pref not needed anymore, scheme is auto-detected
		$this->HTTP_SCHEME = 'http';
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
		{
			$this->HTTP_SCHEME =  'https';
		}

		$path = ""; $i = 0;

		// FIXME - Again, what if someone moves handlers under the webroot?
		if(!self::isCli())
		{
			while (!file_exists("{$path}class2.php"))
			{
				$path .= "../";
				$i++;
			}
		}
		if($_SERVER['PHP_SELF'] == "") { $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME']; }

		$http_path = dirname($_SERVER['PHP_SELF']);
		$http_path = explode("/", $http_path);
		$http_path = array_reverse($http_path);
		$j = 0;
		while ($j < $i)
		{
			unset($http_path[$j]);
			$j++;
		}
		$http_path = array_reverse($http_path);
		$this->server_path = implode("/", $http_path)."/";
		$this->server_path = $this->fix_windows_paths($this->server_path);

		if ($this->server_path == "//")
		{
			$this->server_path = "/";
		}

		// Absolute file-path of directory containing class2.php
		//	define("e_ROOT", realpath(dirname(__FILE__)."/../")."/");

		// TODO - We need new way to do this, this file could be located under the web root!
		$e_ROOT = realpath(dirname(__FILE__)."/../"); // Works in Windows, fails on Linux.
		if ((substr($e_ROOT,-1) != '/') && (substr($e_ROOT,-1) != '\\'))
		{
			$e_ROOT .= '/';
		}
		define('e_ROOT', $e_ROOT);			// Specified format gives trailing slash already (at least on Windows)

		$this->relative_base_path = (!self::isCli()) ? $path : e_ROOT;
		$this->http_path = "http://{$_SERVER['HTTP_HOST']}{$this->server_path}";
		$this->https_path = "https://{$_SERVER['HTTP_HOST']}{$this->server_path}";
		$this->file_path = $path;

		if(!defined('e_HTTP') || !defined('e_ADMIN') )
		{
			define('e_HTTP', $this->server_path);			// Directory of site root relative to HTML base directory
		  	define('e_BASE', $this->relative_base_path);

			// Base dir of web stuff in server terms. e_ROOT should always end with e_HTTP, even if e_HTTP = '/'
			define('SERVERBASE', substr(e_ROOT, 0, -strlen(e_HTTP) + 1));

			if(isset($_SERVER['DOCUMENT_ROOT']))
			{
			  	define('e_DOCROOT', $_SERVER['DOCUMENT_ROOT']."/");
			}
			else
			{
			  	define('e_DOCROOT', false);
			}

			//BC temporary fixes
			if (!isset($this->e107_dirs['UPLOADS_SERVER']) && $this->e107_dirs['UPLOADS_DIRECTORY']{0} == "/")
			{
				$this->e107_dirs['UPLOADS_SERVER'] = $this->e107_dirs['UPLOADS_DIRECTORY'];
			}
			if (!isset($this->e107_dirs['DOWNLOADS_SERVER']) && $this->e107_dirs['DOWNLOADS_DIRECTORY']{0} == "/")
			{
				$this->e107_dirs['DOWNLOADS_SERVER'] = $this->e107_dirs['DOWNLOADS_DIRECTORY'];
			}

			//
			// HTTP relative paths
			//
			define('e_ADMIN', $this->get_override_rel('ADMIN'));
			define('e_IMAGE', $this->get_override_rel('IMAGES'));
			define('e_THEME', $this->get_override_rel('THEMES'));
			define('e_PLUGIN', $this->get_override_rel('PLUGINS'));
			define('e_FILE', $this->get_override_rel('FILES'));
			define('e_HANDLER', $this->get_override_rel('HANDLERS'));
			define('e_LANGUAGEDIR', $this->get_override_rel('LANGUAGES'));

			define('e_DOCS', $this->get_override_rel('HELP')); // WILL CHANGE SOON - $this->_get_override_rel('DOCS')
			define('e_HELP', $this->get_override_rel('HELP'));

			define('e_MEDIA', $this->get_override_rel('MEDIA'));
			define('e_MEDIA_FILE', $this->get_override_rel('MEDIA_FILES'));
			define('e_MEDIA_VIDEO', $this->get_override_rel('MEDIA_VIDEOS'));
			define('e_MEDIA_IMAGE', $this->get_override_rel('MEDIA_IMAGES'));
			define('e_MEDIA_ICON', $this->get_override_rel('MEDIA_ICONS'));
			define('e_MEDIA_AVATAR', $this->get_override_rel('MEDIA_AVATARS'));

			define('e_DOWNLOAD', $this->get_override_rel('DOWNLOADS'));
			define('e_UPLOAD', $this->get_override_rel('UPLOADS'));

			define('e_CORE', $this->get_override_rel('CORE'));
			define('e_SYSTEM', $this->get_override_rel('SYSTEM'));

			define('e_WEB', $this->get_override_rel('WEB'));
			define('e_WEB_JS', $this->get_override_rel('WEB_JS'));
			define('e_WEB_CSS', $this->get_override_rel('WEB_CSS'));
			define('e_WEB_IMAGE', $this->get_override_rel('WEB_IMAGES'));
			define('e_WEB_PACK', $this->get_override_rel('WEB_PACKS'));

			define('e_CACHE', $this->get_override_rel('CACHE'));
			define('e_CACHE_CONTENT', $this->get_override_rel('CACHE_CONTENT'));
			define('e_CACHE_IMAGE', $this->get_override_rel('CACHE_IMAGE'));
			define('e_CACHE_DB', $this->get_override_rel('CACHE_DB'));

			define('e_LOG', $this->get_override_rel('LOGS'));

			//
			// HTTP absolute paths
			//
			define("e_ADMIN_ABS", $this->get_override_http('ADMIN'));
			define("e_IMAGE_ABS", $this->get_override_http('IMAGES'));
			define("e_THEME_ABS", $this->get_override_http('THEMES'));
			define("e_PLUGIN_ABS", $this->get_override_http('PLUGINS'));
			define("e_FILE_ABS", $this->get_override_http('FILES')); // Deprecated!
			define("e_DOCS_ABS", $this->get_override_http('DOCS'));
			define("e_HELP_ABS", $this->get_override_http('HELP'));

			// DEPRECATED - not a legal http query now!
			//define("e_HANDLER_ABS", $this->get_override_http('HANDLERS'));
			//define("e_LANGUAGEDIR_ABS", $this->get_override_http('LANGUAGES'));
			//define("e_LOG_ABS", $this->get_override_http('LOGS'));

			define("e_MEDIA_ABS", $this->get_override_http('MEDIA'));
			define('e_MEDIA_FILE_ABS', $this->get_override_http('MEDIA_FILES'));
			define('e_MEDIA_VIDEO_ABS', $this->get_override_http('MEDIA_VIDEOS'));
			define('e_MEDIA_IMAGE_ABS', $this->get_override_http('MEDIA_IMAGES'));
			define('e_MEDIA_ICON_ABS', $this->get_override_http('MEDIA_ICONS'));
			define('e_MEDIA_AVATAR_ABS', $this->get_override_http('MEDIA_AVATARS'));

			// XXX DISCUSSS - e_JS_ABS, e_CSS_ABS etc is not following the naming standards but they're more usable.
			// Example: e_JS_ABS vs e_WEB_JS_ABS
			define('e_WEB_ABS', $this->get_override_http('WEB'));
			define('e_JS_ABS', $this->get_override_http('WEB_JS'));
			define('e_CSS_ABS', $this->get_override_http('WEB_CSS'));
			define('e_PACK_ABS', $this->get_override_http('WEB_PACKS'));
			define('e_WEB_IMAGE_ABS', $this->get_override_http('WEB_IMAGES'));

		}
		return $this;
	}

	/**
	 * Fix Windows server path
	 *
	 * @param string $path resolved server path
	 * @return string fixed path
	 */
	function fix_windows_paths($path)
	{
		$fixed_path = str_replace(array('\\\\', '\\'), array('/', '/'), $path);
		$fixed_path = (substr($fixed_path, 1, 2) == ":/" ? substr($fixed_path, 2) : $fixed_path);
		return $fixed_path;
	}

	/**
	 * Define e_PAGE, e_SELF, e_ADMIN_AREA and USER_AREA;
	 * The following files are assumed to use admin theme:
	 * 1. Any file in the admin directory (check for non-plugin added to avoid mismatches)
	 * 2. any plugin file starting with 'admin_'
	 * 3. any plugin file in a folder called admin/
	 * 4. any file that specifies $eplug_admin = TRUE; or ADMIN_AREA = TRUE;
	 * NOTE: USER_AREA = true; will force e_ADMIN_AREA to FALSE
	 *
	 * @param boolean $no_cbrace remove curly brackets from the url
	 * @return e107
	 */
	public function set_urls($no_cbrace = true)
	{
		//global $PLUGINS_DIRECTORY,$ADMIN_DIRECTORY, $eplug_admin;
		$PLUGINS_DIRECTORY = $this->getFolder('plugins');
		$ADMIN_DIRECTORY = $this->getFolder('admin');

		// Outdated
		/*$requestQry = '';
		$requestUrl = $_SERVER['REQUEST_URI'];
		if(strpos($_SERVER['REQUEST_URI'], '?') !== FALSE)
			list($requestUrl, $requestQry) = explode("?", $_SERVER['REQUEST_URI'], 2); */

		$eplug_admin = vartrue($GLOBALS['eplug_admin'], false);

		$page = substr(strrchr($_SERVER['PHP_SELF'], '/'), 1);
		define('e_PAGE', $page);

		// Leave e_SELF BC, use e_REQUEST_SELF instead
		/*// moved after page check - e_PAGE is important for BC
		if($requestUrl && $requestUrl != $_SERVER['PHP_SELF'])
		{
			$_SERVER['PHP_SELF'] = $requestUrl;
		}*/

		$eSelf = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME'];
		define('e_SELF', $this->HTTP_SCHEME.'://'.$_SERVER['HTTP_HOST'].$eSelf);

		// START New - request uri/url detection, XSS protection
		$requestUri = $requestUrl = '';
		if (isset($_SERVER['HTTP_X_REWRITE_URL']))
		{
			// check this first so IIS will catch
			$requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
			$requestUrl = $this->HTTP_SCHEME.'://'.$_SERVER['HTTP_HOST'].$requestUri;
			// fix request uri
			$_SERVER['REQUEST_URI'] = $requestUri;
		}
		elseif (isset($_SERVER['REQUEST_URI']))
		{
			$requestUri = $_SERVER['REQUEST_URI'];
			$requestUrl = $this->HTTP_SCHEME.'://'.$_SERVER['HTTP_HOST'].$requestUri;
		}
		else
		{
			// go back to e_SELF
			$requestUri = $eSelf;
			$requestUrl = e_SELF;
			if (e_QUERY)
			{
				$requestUri .= '?'.e_QUERY;
				$requestUrl .= '?'.e_QUERY;
			}
		}
		// FIXME - basic security - add url sanitize method to e_parse
		$check = rawurldecode($requestUri); // urlencoded by default
		// a bit aggressive XSS protection... convert to e.g. htmlentities if you are not a bad guy
		$checkregx = $no_cbrace ? '[<>\{\}]' : '[<>]';
		if(preg_match('/'.$checkregx.'/', $check))
		{
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		// e_MENU fix
		if(e_MENU)
		{
			str_replace('['.e_MENU.']', '', $requestUri);
			str_replace('['.e_MENU.']', '', $requestUrl);
		}

		// the last anti-XSS measure, XHTML compliant URL to be used in forms instead e_SELF
		define('e_REQUEST_URL', str_replace(array("'", '"'), array('%27', '%22'), $requestUrl)); // full request url string (including domain)
		define('e_REQUEST_SELF', array_shift(explode('?', e_REQUEST_URL))); // full URL without the QUERY string
		define('e_REQUEST_URI', str_replace(array("'", '"'), array('%27', '%22'), $requestUri)); // absolute http path + query string
		define('e_REQUEST_HTTP', array_shift(explode('?', e_REQUEST_URI))); // SELF URL without the QUERY string and leading domain part
		unset($requestUrl, $requestUri);
		// END request uri/url detection, XSS protection

		// e_SELF has the full HTML path
		$inAdminDir = FALSE;
		$isPluginDir = strpos(e_SELF,'/'.$PLUGINS_DIRECTORY) !== FALSE;		// True if we're in a plugin
		$e107Path = str_replace($this->base_path, '', e_SELF);				// Knock off the initial bits

		if	(
			 (!$isPluginDir && strpos($e107Path, $ADMIN_DIRECTORY) === 0 ) 									// Core admin directory
			  || ($isPluginDir && (strpos(e_PAGE,'admin_') === 0 || strpos($e107Path, 'admin/') !== FALSE)) // Plugin admin file or directory
			  || (varsettrue($eplug_admin) || defsettrue('ADMIN_AREA'))		// Admin forced
			)
		{
			$inAdminDir = TRUE;
		}
		if ($isPluginDir)
		{
			$temp = substr($e107Path, strpos($e107Path, '/') +1);
			$plugDir = substr($temp, 0, strpos($temp, '/'));
			define('e_CURRENT_PLUGIN', $plugDir);
			define('e_PLUGIN_DIR', e_PLUGIN.e_CURRENT_PLUGIN.'/');
			define('e_PLUGIN_DIR_ABS', e_PLUGIN_ABS.e_CURRENT_PLUGIN.'/');
		}
		else
		{
			define('e_CURRENT_PLUGIN', '');
			define('e_PLUGIN_DIR', '');
			define('e_PLUGIN_DIR_ABS', '');
		}

		// This should avoid further checks - NOTE: used in js_manager.php
		define('e_ADMIN_AREA', ($inAdminDir  && !deftrue('USER_AREA'))); //Force USER_AREA added

		define('ADMINDIR', $ADMIN_DIRECTORY);

		define('SITEURLBASE', $this->HTTP_SCHEME.'://'.$_SERVER['HTTP_HOST']);
		define('SITEURL', SITEURLBASE.e_HTTP);

		// login/signup
		define('e_SIGNUP', SITEURL.(file_exists(e_BASE.'customsignup.php') ? 'customsignup.php' : 'signup.php'));
		define('e_LOGIN', SITEURL.(file_exists(e_BASE.'customlogin.php') ? 'customlogin.php' : 'login.php'));

		return $this;
	}

	/**
	 * Set request related constants
	 * @param boolean $no_cbrace remove curly brackets from the url
	 * @return e107
	 */
	public function set_request($no_cbrace = true)
	{

		$inArray = array("'", ';', '/**/', '/UNION/', '/SELECT/', 'AS ');
		if (strpos($_SERVER['PHP_SELF'], 'trackback') === false)
		{
			foreach($inArray as $res)
			{
				if(stristr($_SERVER['QUERY_STRING'], $res))
				 {
					die('Access denied.');
				}
			}
		}

		if (strpos($_SERVER['QUERY_STRING'], ']') && preg_match('#\[(.*?)](.*)#', $_SERVER['QUERY_STRING'], $matches))
		{
			define('e_MENU', $matches[1]);
			$e_QUERY = $matches[2];
		}
		else
		{
			define('e_MENU', '');
			$e_QUERY = $_SERVER['QUERY_STRING'];
		}

		if ($no_cbrace)	$e_QUERY = str_replace(array('{', '}', '%7B', '%7b', '%7D', '%7d'), '', rawurldecode($e_QUERY));
		$e_QUERY = str_replace("&","&amp;", self::getParser()->post_toForm($e_QUERY));
		define('e_QUERY', $e_QUERY);

		define('e_TBQS', $_SERVER['QUERY_STRING']);
		$_SERVER['QUERY_STRING'] = e_QUERY;

	}

	/**
	 * Check if current request is secure (https)
	 * @return boolean TRUE if https, FALSE if http
	 */
	public function isSecure()
	{
		return ($this->HTTP_SCHEME === 'https');
	}

	/**
	 * Check if current user is banned
	 *
	 * Generates the queries to interrogate the ban list, then calls $this->check_ban().
	 * If the user is banned, $check_ban() never returns - so a return from this routine indicates a non-banned user.
	 *
	 * @return void
	 */
	public function ban()
	{
		global $sql, $pref;
		$ban_count = $sql->db_Count('banlist');
		if($ban_count)
		{
			$vals = array();
			$ip = $this->getip(); // This will be in normalised IPV6 form
			if($ip!='x.x.x.x')
			{
				$vals[] = $ip; // Always look for exact match
				if(strpos($ip, '0000:0000:0000:0000:0000:ffff:')===0)
				{ // It's an IPV4 address
					$vals[] = substr($ip, 0, -2).'*';
					$vals[] = substr($ip, 0, -4).'*';
					$vals[] = substr($ip, 0, -7).'*'; // Knock off colon as well here
				}
				else
				{ // Its an IPV6 address - ban in blocks of 16 bits
					$vals[] = substr($ip, 0, -4).'*';
					$vals[] = substr($ip, 0, -9).'*';
					$vals[] = substr($ip, 0, -14).'*';
				}
			}
			if(varsettrue($pref['enable_rdns']))
			{
				$tmp = array_reverse(explode('.', $this->get_host_name(getenv('REMOTE_ADDR'))));
				$line = '';
				//		  $vals[] = $addr;
				foreach($tmp as $e)
				{
					$line = '.'.$e.$line;
					$vals[] = '*'.$line;
				}
			}
			if((defined('USEREMAIL')&&USEREMAIL))
			{
				$vals[] = USEREMAIL;
			}
			if(($ip!='127.0.0.1')&&count($vals))
			{
				$match = "`banlist_ip`='".implode("' OR `banlist_ip`='", $vals)."'";
				$this->check_ban($match);
			}
		}
	}

	/**
	 * Check the banlist table. $query is used to determine the match.
	 * If $do_return, will always return with ban status - TRUE for OK, FALSE for banned.
	 * If return permitted, will never display a message for a banned user; otherwise will display any message then exit
	 * XXX - clean up
	 *
	 * @param string $query
	 * @param boolean $show_error
	 * @param boolean $do_return
	 * @return boolean
	 */
	public function check_ban($query, $show_error = TRUE, $do_return = FALSE)
	{
		global $sql, $tp, $pref, $admin_log;
		//$admin_log->e_log_event(4,__FILE__."|".__FUNCTION__."@".__LINE__,"DBG","Check for Ban",$query,FALSE,LOG_TO_ROLLING);
		if($sql->db_Select('banlist', '*', $query.' ORDER BY `banlist_bantype` DESC'))
		{
			// Any whitelist entries will be first - so we can answer based on the first DB record read
			define('BAN_TYPE_WHITELIST', 100); // Entry for whitelist
			$row = $sql->db_Fetch();
			if($row['banlist_bantype']>=BAN_TYPE_WHITELIST)
			{
				//$admin_log->e_log_event(4,__FILE__."|".__FUNCTION__."@".__LINE__,"DBG","Whitelist hit",$query,FALSE,LOG_TO_ROLLING);
				return TRUE;
			}
			// Found banlist entry in table here
			if(($row['banlist_banexpires']>0)&&($row['banlist_banexpires']<time()))
			{ // Ban has expired - delete from DB
				$sql->db_Delete('banlist', $query);
				return TRUE;
			}
			if(varsettrue($pref['ban_retrigger'])&&varsettrue($pref['ban_durations'][$row['banlist_bantype']]))
			{ // May need to retrigger ban period
				$sql->db_Update('banlist', "`banlist_banexpires`=".intval(time()+($pref['ban_durations'][$row['banlist_bantype']]*60*60)), "WHERE `banlist_ip`='{$row['banlist_ip']}'");
				//$admin_log->e_log_event(4,__FILE__."|".__FUNCTION__."@".__LINE__,"DBG","Retrigger Ban",$row['banlist_ip'],FALSE,LOG_TO_ROLLING);
			}
			//$admin_log->e_log_event(4,__FILE__."|".__FUNCTION__."@".__LINE__,"DBG","Active Ban",$query,FALSE,LOG_TO_ROLLING);
			if($show_error)
				header("HTTP/1.1 403 Forbidden", true);
			if(isset($pref['ban_messages']))
			{ // May want to display a message
				// Ban still current here
				if($do_return)
					return FALSE;
				echo $tp->toHTML(varsettrue($pref['ban_messages'][$row['banlist_bantype']])); // Show message if one set
			}
			$admin_log->e_log_event(4, __FILE__."|".__FUNCTION__."@".__LINE__, 'BAN_03', 'LAN_AUDIT_LOG_003', $query, FALSE, LOG_TO_ROLLING);
			exit();
		}
		//$admin_log->e_log_event(4,__FILE__."|".__FUNCTION__."@".__LINE__,"DBG","No ban found",$query,FALSE,LOG_TO_ROLLING);
		return TRUE; // Email address OK
	}


	/**
	 * Add an entry to the banlist. $bantype = 1 for manual, 2 for flooding, 4 for multiple logins
	 * Returns TRUE if ban accepted.
	 * Returns FALSE if ban not accepted (i.e. because on whitelist, or invalid IP specified)
	 * FIXME - remove $admin_log global, add admin_log method getter instead
	 *
	 * @param string $bantype
	 * @param string $ban_message
	 * @param string $ban_ip
	 * @param integer $ban_user
	 * @param string $ban_notes
	 *
	 * @return boolean check result
	 */
	public function add_ban($bantype, $ban_message = '', $ban_ip = '', $ban_user = 0, $ban_notes = '')
	{
		global $sql, $pref, $e107, $admin_log;

		if(!$ban_message)
		{
			$ban_message = 'No explanation given';
		}
		if(!$ban_ip)
		{
			$ban_ip = $this->getip();
		}
		$ban_ip = preg_replace('/[^\w@\.]*/', '', urldecode($ban_ip)); // Make sure no special characters
		if(!$ban_ip)
		{
			return FALSE;
		}
		// See if the address is in the whitelist
		if($sql->db_Select('banlist', '*', '`banlist_bantype` >= '.BAN_TYPE_WHITELIST))
		{ // Got a whitelist entry for this
			$admin_log->e_log_event(4, __FILE__."|".__FUNCTION__."@".__LINE__, "BANLIST_11", 'LAN_AL_BANLIST_11', $ban_ip, FALSE, LOG_TO_ROLLING);
			return FALSE;
		}
		if(varsettrue($pref['enable_rdns_on_ban']))
		{
			$ban_message .= 'Host: '.$e107->get_host_name($ban_ip);
		}
		// Add using an array - handles DB changes better
		$sql->db_Insert('banlist', array('banlist_ip' => $ban_ip , 'banlist_bantype' => $bantype , 'banlist_datestamp' => time() , 'banlist_banexpires' => (varsettrue($pref['ban_durations'][$bantype]) ? time()+($pref['ban_durations'][$bantype]*60*60) : 0) , 'banlist_admin' => $ban_user , 'banlist_reason' => $ban_message , 'banlist_notes' => $ban_notes));
		return TRUE;
	}

	/**
	 * Get the current user's IP address
	 * returns the address in internal 'normalised' IPV6 format - so most code should continue to work provided the DB Field is big enougn
	 *
	 * @return string
	 */
	public function getip()
	{
		if(!$this->_ip_cache)
		{
			$ip=$_SERVER['REMOTE_ADDR'];
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{
				if (preg_match('/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/', getenv('HTTP_X_FORWARDED_FOR'), $ip3))
				{
					$ip2 = array('#^0\..*#',
							'#^127\..*#', 							// Local loopbacks
							'#^192\.168\..*#', 						// RFC1918 - Private Network
							'#^172\.(?:1[6789]|2\d|3[01])\..*#', 	// RFC1918 - Private network
							'#^10\..*#', 							// RFC1918 - Private Network
							'#^169\.254\..*#', 						// RFC3330 - Link-local, auto-DHCP
							'#^2(?:2[456789]|[345][0-9])\..*#'		// Single check for Class D and Class E
							);
					$ip = preg_replace($ip2, $ip3[1], $ip);
				}
			}
			if ($ip == "")
			{
				$ip = "x.x.x.x";
			}
			$this->_ip_cache = $this->ipEncode($ip); // Normalise for storage
		}
		return $this->_ip_cache;
	}

	/**
	 * Encode an IP address to internal representation. Returns string if successful; FALSE on error
	 * Default separates fields with ':'; set $div='' to produce a 32-char packed hex string
	 *
	 * @param string $ip
	 * @param string $div divider
	 * @return string encoded IP
	 */
	public function ipEncode($ip, $div = ':')
	{
		$ret = '';
		$divider = '';
		if(strpos($ip, ':')!==FALSE)
		{ // Its IPV6 (could have an IP4 'tail')
			if(strpos($ip, '.')!==FALSE)
			{ // IPV4 'tail' to deal with
				$temp = strrpos($ip, ':')+1;
				$ipa = explode('.', substr($ip, $temp));
				$ip = substr($ip, 0, $temp).sprintf('%02x%02x:%02x%02x', $ipa[0], $ipa[1], $ipa[2], $ipa[3]);
			}
			// Now 'normalise' the address
			$temp = explode(':', $ip);
			$s = 8-count($temp); // One element will of course be the blank
			foreach($temp as $f)
			{
				if($f=='')
				{
					$ret .= $divider.'0000'; // Always put in one set of zeros for the blank
					$divider = $div;
					if($s>0)
					{
						$ret .= str_repeat($div.'0000', $s);
						$s = 0;
					}
				}
				else
				{
					$ret .= $divider.sprintf('%04x', hexdec($f));
					$divider = $div;
				}
			}
			return $ret;
		}
		if(strpos($ip, '.')!==FALSE)
		{ // Its IPV4
			$ipa = explode('.', $ip);
			$temp = sprintf('%02x%02x%s%02x%02x', $ipa[0], $ipa[1], $div, $ipa[2], $ipa[3]);
			return str_repeat('0000'.$div, 5).'ffff'.$div.$temp;
		}
		return FALSE; // Unknown
	}

	/**
	 * Takes an encoded IP address - returns a displayable one
	 * Set $IP4Legacy TRUE to display 'old' (IPv4) addresses in the familiar dotted format,
	 * FALSE to display in standard IPV6 format
	 * Should handle most things that can be thrown at it.
	 *
	 * @param string $ip encoded IP
	 * @param boolean $IP4Legacy
	 * @return string decoded IP
	 */
	public function ipDecode($ip, $IP4Legacy = TRUE)
	{
		if (strstr($ip,'.'))
		{
			if ($IP4Legacy) return $ip;			// Assume its unencoded IPV4
			$ipa = explode('.', $ip);
			$ip = '0:0:0:0:0:ffff:'.sprintf('%02x%02x:%02x%02x', $ipa[0], $ipa[1], $ipa[2], $ipa[3]);
		}
		if (strstr($ip,'::')) return $ip;			// Assume its a compressed IPV6 address already
		if ((strlen($ip) == 8) && !strstr($ip,':'))
		{	// Assume a 'legacy' IPV4 encoding
			$ip = '0:0:0:0:0:ffff:'.implode(':',str_split($ip,4));		// Turn it into standard IPV6
		}
		elseif ((strlen($ip) == 32) && !strstr($ip,':'))
		{  // Assume a compressed hex IPV6
			$ip = implode(':',str_split($ip,4));
		}
		if (!strstr($ip,':')) return FALSE;			// Return on problem - no ':'!
		$temp = explode(':',$ip);
		$z = 0;		// State of the 'zero manager' - 0 = not started, 1 = running, 2 = done
		$ret = '';
		$zc = 0;			// Count zero fields (not always required)
		foreach ($temp as $t)
		{
			$v = hexdec($t);
			if (($v != 0) || ($z == 2))
			{
				if ($z == 1)
				{ // Just finished a run of zeros
					$z++;
					$ret .= ':';
				}
				if ($ret) $ret .= ':';
				$ret .= sprintf('%x',$v);				// Drop leading zeros
			}
			else
			{  // Zero field
				$z = 1;
				$zc++;
			}
		}
		if ($z == 1)
		{  // Need to add trailing zeros, or double colon
			if ($zc > 1) $ret .= '::'; else $ret .= ':0';
		}
		if ($IP4Legacy && (substr($ret,0,7) == '::ffff:'))
		{
			$temp = explode(':',substr($ret,7));		// Should give us two 16-bit hex values
			$z = array();
			foreach ($temp as $t)
			{
				$zc = hexdec($t);
				$z[] = intval($zc / 256);		// intval needed to avoid small rounding error
				$z[] = $zc % 256;
			}
			$ret = implode('.',$z);
		}

		return $ret;
	}

	/**
	 * Given a string which may be IP address, email address etc, tries to work out what it is
	 *
	 * @param string $string
	 * @return string ip|email|url|ftp|unknown
	 */
	public function whatIsThis($string)
	{
		if (strstr($string,'@')) return 'email';		// Email address
		if (strstr($string,'http://')) return 'url';
		if (strstr($string,'ftp://')) return 'ftp';
		$string = strtolower($string);
		if (str_replace(' ', '', strtr($string,'0123456789abcdef.:*', '                   ')) == '')	// Delete all characters found in ipv4 or ipv6 addresses, plus wildcards
		{
			return 'ip';
		}
		return 'unknown';
	}

	/**
	 * Retrieve & cache host name
	 *
	 * @param string $ip_address
	 * @return string host name
	 */
	public function get_host_name($ip_address)
	{
		if(!$this->_host_name_cache[$ip_address])
		{
			$this->_host_name_cache[$ip_address] = gethostbyaddr($ip_address);
		}
		return $this->_host_name_cache[$ip_address];
	}

	/**
	 * Return a memory value formatted helpfully
	 * $dp overrides the number of decimal places displayed - realistically, only 0..3 are sensible
	 * FIXME e107->parseMemorySize() START
	 * - maybe we are in need of General Helper handler, this + the above ban/ip related methods
	 * are not fitting e107 class logic anymore
	 * - change access to public static - more useful
	 * - out of (integer) range case?
	 * 32 bit systems range: -2147483648 to 2147483647
	 * 64 bit systems range: -9223372036854775808 9223372036854775807
	 * {@link http://www.php.net/intval}
	 * FIXME e107->parseMemorySize() END
	 *
	 * @param integer $size
	 * @param integer $dp
	 * @return string formatted size
	 */
	public function parseMemorySize($size, $dp = 2)
	{
		if (!$size) { $size = 0; }
		if ($size < 4096)
		{	// Fairly arbitrary limit below which we always return number of bytes
			return number_format($size, 0).CORE_LAN_B;
		}

		$size = $size / 1024;
		$memunit = CORE_LAN_KB;

		if ($size > 1024)
		{ /* 1.002 mb, etc */
			$size = $size / 1024;
			$memunit = CORE_LAN_MB;
		}
		if ($size > 1024)
		{ /* show in GB if >1GB */
			$size = $size / 1024;
			$memunit = CORE_LAN_GB;
		}
		if ($size > 1024)
		{ /* show in TB if >1TB */
			$size = $size / 1024;
			$memunit = CORE_LAN_TB;
		}
		return (number_format($size, $dp).$memunit);
}


	/**
	 * Get the current memory usage of the code
	 * If $separator argument is null, raw data (array) will be returned
	 *
	 * @param null|string $separator
	 * @return string|array memory usage
	 */
	public function get_memory_usage($separator = '/')
	{
		$ret = array();
		if(function_exists("memory_get_usage"))
		{
	      $ret[] = $this->parseMemorySize(memory_get_usage());
		  // With PHP>=5.2.0, can show peak usage as well
	      if (function_exists("memory_get_peak_usage")) $ret[] = $this->parseMemorySize(memory_get_peak_usage(TRUE));
		}
		else
		{
		  $ret[] = 'Unknown';
		}

		return (null !== $separator ? implode($separator, $ret) : $ret);
	}

	/**
	 * Check if plugin is installed
	 * @param string $plugname
	 * @return boolean
	 */
	public static function isInstalled($plugname)
	{
		// Could add more checks here later if appropriate
		return self::getConfig()->isData('plug_installed/'.$plugname);
	}

	/**
	 * Safe way to set ini var
	 * @param string $var
	 * @param string $value
	 * @return TBD
	 */
	public static function ini_set($var, $value)
	{
		if (function_exists('ini_set'))
		{
			return ini_set($var, $value);
		}
		return false;
	}

	public function __get($name)
	{
		switch ($name)
		{
			case 'tp':
				$ret = e107::getParser();
			break;

			case 'sql':
				$ret = e107::getDb();
			break;

			case 'ecache':
				$ret = e107::getCache();
			break;

			case 'arrayStorage':
				$ret = e107::getArrayStorage();
			break;

			case 'e_event':
				$ret = e107::getEvent();
			break;

			case 'ns':
				$ret = e107::getRender();
			break;

			case 'url':
				$ret = e107::getUrl();
			break;

			case 'admin_log':
				$ret = e107::getAdminLog();
			break;

			case 'override':
				$ret = e107::getSingleton('override', e_HANDLER.'override_class.php');
			break;

			case 'notify':
				$ret = e107::getNotify();
			break;

			case 'e_online':
				$ret = e107::getOnline();
			break;

			case 'user_class':
				$ret = e107::getUserClass();
			break;

			default:
				trigger_error('$e107->$'.$name.' not defined', E_USER_WARNING);
				return null;
			break;
		}

		$this->{$name} = $ret;
		return $ret;
	}
}
?>