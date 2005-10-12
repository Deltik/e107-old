<?php

/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_handlers/e107_class.php,v $
|     $Revision: 1.44 $
|     $Date: 2005-10-12 03:29:34 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

/**
 * Core e107 class
 *
 */
class e107{

	var $server_path;
	var $e107_dirs;
	var $http_path;
	var $file_path;
	var $relative_base_path;
	var $_ip_cache;
	var $_host_name_cache;

	/**
	 * e107 class constructor
	 *
	 * @param array $e107_paths
	 * @param string $e107_root_path
	 * @return e107
	 */
	function e107($e107_paths, $e107_root_path){
		if(defined("COMPRESS_OUTPUT") && COMPRESS_OUTPUT === true) {
			ob_start ("ob_gzhandler");
		}
		$this->e107_dirs = $e107_paths;
		$this->set_paths();
		$this->file_path = $this->fix_windows_paths($e107_root_path)."/";
	}

	function set_paths(){
		global $DOWNLOADS_DIRECTORY, $ADMIN_DIRECTORY, $IMAGES_DIRECTORY, $THEMES_DIRECTORY, $PLUGINS_DIRECTORY,
		$FILES_DIRECTORY, $HANDLERS_DIRECTORY, $LANGUAGES_DIRECTORY, $HELP_DIRECTORY;
		$path = ""; $i = 0;
		while (!file_exists("{$path}class2.php")) {
			$path .= "../";
			$i++;
		}
		if($_SERVER['PHP_SELF'] == "") { $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME']; }

		$http_path = dirname($_SERVER['PHP_SELF']);
		$http_path = explode("/", $http_path);
		$http_path = array_reverse($http_path);
		$j = 0;
		while ($j < $i) {
			unset($http_path[$j]);
			$j++;
		}
		$http_path = array_reverse($http_path);
		$this->server_path = implode("/", $http_path)."/";
		$this->server_path = $this->fix_windows_paths($this->server_path);

		if ($this->server_path == "//") {
			$this->server_path = "/";
		}
		$this->relative_base_path = $path;
		$this->http_path = "http://{$_SERVER['HTTP_HOST']}{$this->server_path}";
		$this->https_path = "https://{$_SERVER['HTTP_HOST']}{$this->server_path}";
		$this->file_path = $path;

		define("e_HTTP", $this->server_path);
		define("e_BASE", $this->relative_base_path);
		define("e_ADMIN", e_BASE.$ADMIN_DIRECTORY);
		define("e_IMAGE", e_BASE.$IMAGES_DIRECTORY);
		define("e_THEME", e_BASE.$THEMES_DIRECTORY);
		define("e_PLUGIN", e_BASE.$PLUGINS_DIRECTORY);
		define("e_FILE", e_BASE.$FILES_DIRECTORY);
		define("e_HANDLER", e_BASE.$HANDLERS_DIRECTORY);
		define("e_LANGUAGEDIR", e_BASE.$LANGUAGES_DIRECTORY);

		define("e_ADMIN_ABS", e_HTTP.$ADMIN_DIRECTORY);
		define("e_IMAGE_ABS", e_HTTP.$IMAGES_DIRECTORY);
		define("e_THEME_ABS", e_HTTP.$THEMES_DIRECTORY);
		define("e_PLUGIN_ABS", e_HTTP.$PLUGINS_DIRECTORY);
		define("e_FILE_ABS", e_HTTP.$FILES_DIRECTORY);
		define("e_HANDLER_ABS", e_HTTP.$HANDLERS_DIRECTORY);
		define("e_LANGUAGEDIR_ABS", e_HTTP.$LANGUAGES_DIRECTORY);

		define("e_DOCS", e_BASE.$HELP_DIRECTORY);
		define("e_DOCROOT", $_SERVER['DOCUMENT_ROOT']."/");

		define("e_DOCS_ABS", e_HTTP.$HELP_DIRECTORY);

		if ($DOWNLOADS_DIRECTORY{0} == "/") {
			define("e_DOWNLOAD", $DOWNLOADS_DIRECTORY);
		} else {
			define("e_DOWNLOAD", e_BASE.$DOWNLOADS_DIRECTORY);
		}
	}

	function fix_windows_paths($path) {
		$fixed_path = str_replace(array('\\\\', '\\'), array('/', '/'), $path);
		$fixed_path = (substr($fixed_path, 1, 2) == ":/" ? substr($fixed_path, 2) : $fixed_path);
		return $fixed_path;
	}

	/**
	 * Check if current user is banned
	 *
	 */
	function ban() {
		global $sql, $e107;
		$ban_count = $sql->db_Count("banlist");
		if($ban_count){
			$ip = $this->getip();
			$tmp = explode(".",$ip);
			$wildcard =  $tmp[0].".".$tmp[1].".".$tmp[2].".*";
			$wildcard2 = $tmp[0].".".$tmp[1].".*.*";

			$tmp = $e107->get_host_name(getenv('REMOTE_ADDR'));

			preg_match("/[\w]+\.[\w]+$/si", $tmp, $match);
			$bhost = (isset($match[0]) ? " OR banlist_ip='{$match[0]}'" : "");

			if ($ip != '127.0.0.1') {
				if ($sql->db_Select("banlist", "*", "banlist_ip='".$_SERVER['REMOTE_ADDR']."' OR banlist_ip='".USEREMAIL."' OR banlist_ip='{$ip}' OR banlist_ip='{$wildcard}' OR banlist_ip='{$wildcard2}' {$bhost}")) {
					// enter a message here if you want some text displayed to banned users ...
					exit();
				}
			}
		}
	}

	/**
	 * Get the current user's IP address
	 *
	 * @return string
	 */
	function getip() {
		if(!$this->_ip_cache){
			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$ip=$_SERVER['REMOTE_ADDR'];
				if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip3)) {
					$ip2 = array(
					'/^0\./',
					'/^127\.0\.0\.1/',
					'/^192\.168\..*/',
					'/^172\.16\..*/',
					'/^10..*/',
					'/^224..*/',
					'/^240..*/'
					);
					$ip = preg_replace($ip2, $ip, $ip3[1]);
				}
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			if ($ip == "") {
				$ip = "x.x.x.x";
			}
			$this->_ip_cache = $ip;
		}
		return $this->_ip_cache;
	}

	function get_host_name($ip_address) {
		if(!$this->_host_name_cache[$ip_address]) {
			$this->_host_name_cache[$ip_address] = gethostbyaddr($ip_address);
		}
		return $this->_host_name_cache[$ip_address];
	}

	/**
	 * Get the current memory usage of the code
	 *
	 * @return string memory usage
	 */
	function get_memory_usage(){
		if(function_exists("memory_get_usage")){
			$memusage = memory_get_usage();
			$memunit = 'b';
			if ($memusage > 1024){
				$memusage = $memusage / 1024;
				$memunit = 'kb';
			}
			if ($memusage > 1024){
				$memusage = $memusage / 1024;
				$memunit = 'mb';
			}
			if ($memusage > 1024){
				$memusage = $memusage / 1024;
				$memunit = 'gb';
			}
			return (number_format($memusage, 0).$memunit);
		} else {
			return ('Unknown');
		}
	}
	
	/**
	 * Get the user data from user and user_extended tables
	 *
	 * @return array
	 */
	function get_user_data($uid, $extra = "", $force_join = TRUE)
	{
		global $pref, $sql, $e107cache;
		if($force_join == TRUE || array_key_exists('ue_upgrade', $pref) || array_key_exists('signup_maxip'))
		{
			$qry = "
			SELECT u.*, ue.* FROM #user AS u
			LEFT JOIN #user_extended AS ue ON ue.user_extended_id = u.user_id
			WHERE u.user_id='{$uid}' {$extra}
			";
		}
		else
		{
			$qry = "SELECT * FROM #user AS u WHERE u.user_id='{$uid}' {$extra}";
		}
		if ($sql->db_Select_gen($qry))
		{
			$var = $sql->db_Fetch();
			$extended_struct = getcachedvars("extended_struct");
			if(!$extended_struct)
			{
				unset($extended_struct);
				$qry = "SHOW COLUMNS FROM #user_extended ";
				if($sql->db_Select_gen($qry))
				{
					while($row = $sql->db_Fetch())
					{
						if($row['Default'] != "")
						{
							$extended_struct[] = $row;
						}
					}
					cachevars("extended_struct", $extended_struct);
				}
			}
			
			foreach($extended_struct as $row)
			{
				if($row['Default'] != "" && ($var[$row['Field']] == NULL || $var[$row['Field']] == "" ))
				{
					$var[$row['Field']] = $row['Default'];
				}
			}
			return $var;
		}
		return FALSE;
	}
}
?>