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
|     $Revision: 1.25 $
|     $Date: 2005-06-04 21:28:14 $
|     $Author: streaky $
+----------------------------------------------------------------------------+
*/

class e107{

	var $server_path;
	var $e107_dirs;
	var $http_path;
	var $file_path;
	var $relative_base_path;

	function e107($e107_paths, $class2_file){
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		if(defined("COMPRESS_OUTPUT") && COMPRESS_OUTPUT === true) {
			ob_start ("ob_gzhandler");
		}
		$this->e107_dirs = $e107_paths;
		$this->set_e107_dirs($class2_file);
	}

	function set_e107_dirs($class2_file){
		$this->fix_missing_doc_root();

		$_SERVER['DOCUMENT_ROOT'] = $this->fix_windows_paths(realpath($_SERVER['DOCUMENT_ROOT']));

		$e107_root_folder = realpath(dirname($class2_file));
		$e107_root_folder = $this->fix_windows_paths($e107_root_folder);

		// define server path in e107_config.php if you have problems, just leave it if everything works.
		if(defined("SERVER_PATH")){
			$server_path = SERVER_PATH;
		} else {
			$server_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $e107_root_folder)."/";
		}

		$relative_path = substr_replace(dirname($_SERVER['PHP_SELF']), "", 0, strlen($server_path));

		$link_prefix = '';
		$prefix_array = explode("/", $relative_path."/");

		foreach ($prefix_array as $val) {
			if(trim($val) != ""){
				$link_prefix .= "../";
			}
		}

		$this->relative_base_path = $link_prefix;

		if ($_SERVER['SERVER_PORT'] != 80) {
			$url_port = ":{$_SERVER['SERVER_PORT']}";
		} else {
			$url_port = "";
		}

		$this->server_path = $server_path;

		$this->http_path = "http://{$_SERVER['HTTP_HOST']}{$url_port}{$this->server_path}";
		$this->https_path = "https://{$_SERVER['HTTP_HOST']}{$url_port}{$this->server_path}";
		$this->file_path = $e107_root_folder;

		define("e_HTTP", $this->server_path);
	}

	function fix_windows_paths($path) {
		$fixed_path = str_replace(array('\\\\', '\\'), array('/', '/'), $path);
		$fixed_path = (substr($fixed_path, 1, 2) == ":/" ? substr($fixed_path, 2) : $fixed_path);
		return $fixed_path;
	}

	function fix_missing_doc_root() {
		if($_SERVER['DOCUMENT_ROOT'] == '') {
			$_SERVER['PATH_INFO'] = $this->fix_windows_paths($_SERVER['PATH_INFO']);
			$_SERVER['PATH_TRANSLATED'] = $this->fix_windows_paths($_SERVER['PATH_TRANSLATED']);
			$_SERVER['DOCUMENT_ROOT'] = str_replace($_SERVER['PATH_INFO'], '', $_SERVER['PATH_TRANSLATED']);
		}
	}

	function http_abs_location($dir_type = false, $extended = false, $secure = false) {
		global $pref;
		if ($pref['ssl_enabled']) {
			$secure = true;
		}
		$site_uri = ($secure ? $this->https_path : $this->http_path);
		return "{$site_uri}".($dir_type ? $this->e107_dirs[$dir_type] : "").($extended ? $extended : "");
	}

	function ban() {
		global $sql;
		$ip = $this->getip();
		$wildcard = substr($ip, 0, strrpos($ip, ".")).".*";

		$tmp = gethostbyaddr(getenv('REMOTE_ADDR'));
		preg_match("/[\w]+\.[\w]+$/si", $tmp, $match);
		$bhost = $match[0];

		if ($ip != '127.0.0.1') {
			if ($sql->db_Select("banlist", "*", "banlist_ip='".$_SERVER['REMOTE_ADDR']."' OR banlist_ip='".USEREMAIL."' OR banlist_ip='$ip' OR banlist_ip='$wildcard' OR banlist_ip='$bhost'")) {
				// enter a message here if you want some text displayed to banned users ...
				exit;
			}
		}
	}

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
					$ip=preg_replace($ip2, $ip, $ip3[1]);
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

	function get_memory_usage(){
		global $dbg;
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
}

?>