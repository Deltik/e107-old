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
|     $Source: /cvs_backup/e107_0.7/class2.php,v $
|     $Revision: 1.60 $
|     $Date: 2005-01-28 03:03:39 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

$eTimingStart = microtime(); // Honest global beginning point for processing time

//unset any globals created by register_globals being turned ON
while (list($global) = each($GLOBALS)) {
	if (!preg_match('/^(_POST|_GET|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*|_REQUEST|eTimingStart)$/', $global)) {
		unset($$global);
	}
}
unset($global);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

//ob_start ("ob_gzhandler"); // Uncomment to enable gz output compression.

ob_start ();
$start_ob_level = ob_get_level();
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (!$mySQLserver) {
	@include("e107_config.php");
	$a = 0;
	$p = "";
	while (!$mySQLserver && $a < 5) {
		$a++;
		$p .= "../";
		@include($p."e107_config.php");
	}
	if (!defined("e_HTTP")) {
		header("Location:install.php");
		exit;
	}
}

$link_prefix = "";
$url_prefix = substr($_SERVER['PHP_SELF'], strlen(e_HTTP), strrpos($_SERVER['PHP_SELF'], "/")+1-strlen(e_HTTP));
$tmp = explode("?", $url_prefix);
$num_levels = substr_count($tmp[0], "/");
for($i = 1; $i <= $num_levels; $i++) {
	$link_prefix .= "../";
}
if (strstr($_SERVER['QUERY_STRING'], "'") || strstr($_SERVER['QUERY_STRING'], ";") ) {
	die("Access denied.");
}

if (preg_match("/\[(.*?)\].*?/i", $_SERVER['QUERY_STRING'], $matches)) {
	define("e_MENU", $matches[1]);
	define("e_QUERY", str_replace($matches[0], "", eregi_replace("&|/?".session_name().".*", "", $_SERVER['QUERY_STRING'])));
} else {
	define("e_QUERY", eregi_replace("&|/?".session_name().".*", "", $_SERVER['QUERY_STRING']));
}


$_SERVER['QUERY_STRING'] = e_QUERY;
define('e_BASE', $link_prefix);
define("e_ADMIN", e_BASE.$ADMIN_DIRECTORY);
define("e_IMAGE", e_BASE.$IMAGES_DIRECTORY);
define("e_THEME", e_BASE.$THEMES_DIRECTORY);
define("e_PLUGIN", e_BASE.$PLUGINS_DIRECTORY);
define("e_FILE", e_BASE.$FILES_DIRECTORY);
define("e_HANDLER", e_BASE.$HANDLERS_DIRECTORY);
define("e_LANGUAGEDIR", e_BASE.$LANGUAGES_DIRECTORY);

define("e_DOCS", e_BASE.$HELP_DIRECTORY);
define("e_DOCROOT", $_SERVER['DOCUMENT_ROOT']."/");
define("e_UC_PUBLIC", 0);
define("e_UC_READONLY", 251);
define("e_UC_GUEST", 252);
define("e_UC_MEMBER", 253);
define("e_UC_ADMIN", 254);
define("e_UC_NOBODY", 255);
define("ADMINDIR", $ADMIN_DIRECTORY);

//
// Some bits for different debug levels (all intended for ADMIN only)
// 8 bits for each set of details
// 1...128 = basics
// 256...32768 = gory details
// Thus:
// 255 = all basics
// 65535 = all basic and all gory details

if (strstr(e_MENU, "debug")) {
	require_once(e_HANDLER.'debug_handler.php');
	$e107_debug = new e107_debug;
	$db_debug = new e107_db_debug;
	$e107_debug->set_error_reporting();
	$e107_debug_level = $e107_debug->debug_level;
	define('E107_DEBUG_LEVEL', $e107_debug_level);
} else {
	define('E107_DEBUG_LEVEL', 0);
}


// Basic levels
define('E107_DBG_BASIC', (E107_DEBUG_LEVEL & 1));
// basics: worst php errors, sql errors, etc
define('E107_DBG_SQLQUERIES', (E107_DEBUG_LEVEL & 2));
// display all sql queries
define('E107_DBG_TRAFFIC', (E107_DEBUG_LEVEL & 4));
// display traffic counters
define('E107_DBG_FILLIN8', (E107_DEBUG_LEVEL & 8));
// fill in what it is
define('E107_DBG_FILLIN16', (E107_DEBUG_LEVEL & 16));
// fill in what it is
define('E107_DBG_FILLIN32', (E107_DEBUG_LEVEL & 32));
// fill in what it is
define('E107_DBG_FILLIN64', (E107_DEBUG_LEVEL & 64));
// fill in what it is
define('E107_DBG_FILLIN128', (E107_DEBUG_LEVEL & 128));
// fill in what it is

// Gory detail levels
define('E107_DBG_TIMEDETAILS', (E107_DEBUG_LEVEL & 256)); // detailed time profile
define('E107_DBG_SQLDETAILS', (E107_DEBUG_LEVEL & 512));
// detailed sql analysis
define('E107_DBG_FILLIN1024', (E107_DEBUG_LEVEL & 1024));
// fill in what it is
define('E107_DBG_FILLIN2048', (E107_DEBUG_LEVEL & 2048));
// fill in what it is
//...
define('E107_DBG_ALLERRORS', (E107_DEBUG_LEVEL & 32768));
// show ALL errors//...

// e107_config.php upgrade check
// =====================
if (!$ADMIN_DIRECTORY && !$DOWNLOADS_DIRECTORY) {
	message_handler("CRITICAL_ERROR", 8, ": generic, ", "e107_config.php");
	exit;
}

if (!e107_include(e_HANDLER."errorhandler_class.php")) {
	echo "<div style='text-align:center; font: 12px Verdana, Tahoma'>Path error</div>";
	exit;
}
if (!$e107_debug) {
	set_error_handler("error_handler");
}
if (!$mySQLuser) {
	header("location:install.php");
	exit;
}
define("MPREFIX", $mySQLprefix);

e107_require_once(e_HANDLER."mysql_class.php");
e107_require_once(e_HANDLER.'e_parse_class.php');
e107_require_once(e_HANDLER.'traffic_class.php');

$eTraffic = new e107_traffic; // Traffic counts can begin now
$eTraffic->Calibrate($eTraffic);
$tp = new e_parse;
$sql = new db;
$sql->db_SetErrorReporting(FALSE);
$merror = $sql->db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);

if ($merror == "e1") {
	message_handler("CRITICAL_ERROR", 6, ": generic, ", "class2.php");
	exit;
}
else if($merror == "e2") {
	message_handler("CRITICAL_ERROR", 7, ": generic, ", "class2.php");
	exit;
}

/* New compatabilty mode.
At a later date add a check to load e107 compat mode by $pref
PHP Compatabilty should *always* be on. */

e107_require_once(e_HANDLER."PHP_Compat_handler.php");
e107_require_once(e_HANDLER."e107_Compat_handler.php");

// New parser code #########
//$parsethis = array();
//if ($sql->db_Select("parser", "parser_pluginname,parser_regexp", "")) {
// while ($row = $sql->db_Fetch('nostrip')) {
//  $parsethis[$row['parser_regexp']] = $row['parser_pluginname'];
// }
//}
// End parser code #########

global $sysprefs;
e107_require_once(e_HANDLER."pref_class.php");
$sysprefs = new prefs;
$tmp = $sysprefs->get('pref');
$pref = unserialize($tmp);

if (!is_array($pref)) {
	$pref = $sysprefs->getArray('pref');
	if (!is_array($pref)) {
		($sql->db_Select("core", "*", "e107_name='pref' ") ? message_handler("CRITICAL_ERROR", 1, __LINE__, __FILE__) : message_handler("CRITICAL_ERROR", 2, __LINE__, __FILE__));
		if ($sql->db_Select("core", "*", "e107_name='pref_backup' ")) {
			$row = $sql->db_Fetch();
			extract($row);
			$sysprefs->set($e107_value, 'pref', 'core');
			message_handler("CRITICAL_ERROR", 3, __LINE__, __FILE__);
		} else {
			message_handler("CRITICAL_ERROR", 4, __LINE__, __FILE__);
			exit;
		}
	}
}

if (!$pref['cookie_name']) {
	$pref['cookie_name'] = "e107cookie";
}
//if($pref['user_tracking'] == "session"){ @require_once(e_HANDLER."session_handler.php"); }        // if your server session handling is misconfigured uncomment this line and comment the next to use custom session handler
if ($pref['user_tracking'] == "session") {
	session_start();
}

$pref['htmlarea'] = false;

define("e_SELF", ($pref['ssl_enabled'] ? "https://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']) : "http://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME'])));

$menu_pref = $sysprefs->getArray('menu_pref');

// Cameron's Mult-lang switch. ==================

if (isset($_POST['setlanguage'])) {
	$sql->mySQLlanguage = $_POST['sitelanguage'];

	if ($pref['user_tracking'] == "session") {
		$_SESSION['e107language_'.$pref['cookie_name']] = $_POST['sitelanguage'];
	} else {
		setcookie('e107language_'.$pref['cookie_name'], $_POST['sitelanguage'], time()+86400) ;
		$_COOKIE['e107language_'.$pref['cookie_name']] = $_POST['sitelanguage'];
		if (!eregi(e_ADMIN, e_SELF)) {
			Header("Location:".e_SELF);
		}
	}
}
if ($pref['multilanguage']) {
	if ($pref['user_tracking'] == "session") {
		$user_language = $_SESSION['e107language_'.$pref['cookie_name']];
		$sql->mySQLlanguage = ($user_language) ? $user_language :
		"";
	} else {
		$user_language = $_COOKIE['e107language_'.$pref['cookie_name']];
		$sql->mySQLlanguage = ($user_language) ? $user_language :
		"";
	}
}
// =====================

$page = substr(strrchr($_SERVER['PHP_SELF'], "/"), 1);
define("e_PAGE", $page);

if (isset($pref['frontpage']) && $pref['frontpage_type'] == "splash") {
	$ip = getip();
	if (!$sql->db_Count("online", "(*)", "WHERE online_ip='{$ip}' ")) {
		online();
		if (is_numeric($pref['frontpage'])) {
			header("location:".e_BASE."article.php?".$pref['frontpage'].".255");
			exit;
		}
		else if(eregi("http", $pref['frontpage'])) {
			header("location: ".$pref['frontpage']);
			exit;
		} else {
			header("location: ".e_BASE.$pref['frontpage'].".php");
			exit;
		}
	}
}

e107_require_once(e_HANDLER."cache_handler.php");
$e107cache = new ecache;


if ($pref['del_unv']) {
	$threshold = (time() - ($pref['del_unv'] * 60));
	$sql->db_Delete("user", "user_ban = 2 AND user_join<'$threshold' ");
}

e107_require_once(e_HANDLER."override_class.php");
$override = new override;

e107_require_once(e_HANDLER."event_class.php");
$e_event = new e107_event;

if ($pref['modules']) {
	$mods = explode(",", $pref['modules']);
	foreach($mods as $mod) {
		if (file_exists(e_PLUGIN."{$mod}/module.php")) {
			require_once(e_PLUGIN."{$mod}/module.php");
		}
	}
}


//###########  Module redifinable functions ###############
if (!function_exists('checkvalidtheme')) {
	function checkvalidtheme($theme_check) {
		// arg1 = theme to check
		global $ADMIN_DIRECTORY;
		if (@fopen(e_THEME.$theme_check."/theme.php", r)) {
			define("THEME", e_THEME.$theme_check."/");
		} else {
			e107_require_once(e_HANDLER."debug_handler.php");
			e107_require_once(e_HANDLER."textparse/basic.php");
			$etp = new e107_basicparse;
			$e107tmp_theme = search_validtheme();
			define("THEME", e_THEME.$e107tmp_theme."/");
			if (ADMIN && !strstr(e_SELF, $ADMIN_DIRECTORY)) {
				echo '<script>alert("'.$etp->unentity(CORE_LAN1).'")</script>';
			}
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
if (!class_exists('convert')) {
	class convert {
		function convert_date($datestamp, $mode = "long") {
			/*
			# Date convert
			# - parameter #1:  string $datestamp, unix stamp
			# - parameter #2:  string $mode, date format, default long
			# - return         parsed text
			# - scope          public
			*/
			global $pref;

			$datestamp += (TIMEOFFSET * 3600);
			if ($mode == "long") {
				return strftime($pref['longdate'], $datestamp);
			}
			else if($mode == "short") {
				return strftime($pref['shortdate'], $datestamp);
			} else {
				return strftime($pref['forumdate'], $datestamp);
			}
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
if (!class_exists('e107_table')) {
	class e107table {
		function tablerender($caption, $text, $mode = "default", $return = FALSE) {
			/*
			# Render style table
			# - parameter #1:                string $caption, caption text
			# - parameter #2:                string $text, body text
			# - return                                null
			# - scope                                        public
			*/
			global $override;
			if ($override_tablerender = $override->override_check('tablerender')) {
				$result = call_user_func($override_tablerender, $caption, $text, $mode, $return);
				if ($result == "return") {
					return;
				}
				extract($result);
			}
			if ($return) {
				ob_start();
				tablestyle($caption, $text, $mode);
				$ret = ob_get_contents();
				ob_end_clean();
				return $ret;
			} else {
				tablestyle($caption, $text, $mode);
			}
		}
	}
}
//#############################################################

$ns = new e107table;
init_session();
online();


$fp = ($pref['frontpage'] ? $pref['frontpage'].".php" : "news.php index.php");
define("e_SIGNUP", (file_exists(e_BASE."customsignup.php") ? e_BASE."customsignup.php" : e_BASE."signup.php"));
define("e_LOGIN", (file_exists(e_BASE."customlogin.php") ? e_BASE."customlogin.php" : e_BASE."login.php"));

if ($pref['membersonly_enabled'] && !USER && e_PAGE != e_SIGNUP && e_PAGE != "index.php" && e_PAGE != "fpw.php" && e_PAGE != e_LOGIN && !strstr(e_PAGE, "admin") && e_PAGE != 'membersonly.php') {
	header("location: ".e_BASE."membersonly.php");
	exit;
}

$sql->db_Delete("tmp", "tmp_time < '".(time()-300)."' AND tmp_ip!='data' AND tmp_ip!='adminlog' AND tmp_ip!='submitted_link' AND tmp_ip!='reported_post' AND tmp_ip!='var_store' ");

$language = ($pref['sitelanguage'] ? $pref['sitelanguage'] : "English");
define("MAGIC_QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));
define("e_LAN", $language);
define("USERLAN", ($user_language && (strpos(e_SELF, $PLUGINS_DIRECTORY) !== FALSE || (strpos(e_SELF, $ADMIN_DIRECTORY) === FALSE && file_exists(e_LANGUAGEDIR.$user_language."/lan_".e_PAGE)) || (strpos(e_SELF, $ADMIN_DIRECTORY) !== FALSE && file_exists(e_LANGUAGEDIR.$user_language."/admin/lan_".e_PAGE))) ? $user_language : FALSE));
define("e_LANGUAGE", (!USERLAN || !defined("USERLAN") ? $language : USERLAN));
e107_include(e_LANGUAGEDIR.e_LANGUAGE."/".e_LANGUAGE.".php");
foreach($pref as $key => $prefvalue) {
	$pref[$key] = $tp->toFORM($prefvalue);
}
define("e_LANIMAGE", e_BASE.$IMAGES_DIRECTORY."lan_images/".e_LANGUAGE."/");
define("SITENAME", $pref['sitename']);
define("SITEURL", (substr($pref['siteurl'], -1) == "/" ? $pref['siteurl'] : $pref['siteurl']."/"));
define("SITEBUTTON", $pref['sitebutton']);
define("SITETAG", $pref['sitetag']);
define("SITEDESCRIPTION", $pref['sitedescription']);
define("SITEADMIN", $pref['siteadmin']);
define("SITEADMINEMAIL", $pref['siteadminemail']);
define("SITEDISCLAIMER", $pref['sitedisclaimer']);

if ($pref['maintainance_flag'] && ADMIN == FALSE && !eregi("admin", e_SELF)) {
	e107_include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_sitedown.php");
	e107_include_once(e_LANGUAGEDIR."English/lan_sitedown.php");
	e107_require_once(e_BASE."sitedown.php");
	exit;
}

if (strstr(e_SELF, $ADMIN_DIRECTORY) || strstr(e_SELF, "admin.php")) {
	e107_include_once(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_".e_PAGE);
	e107_include_once(e_LANGUAGEDIR."English/admin/lan_".e_PAGE);
}
else if (!strstr(e_SELF, $PLUGINS_DIRECTORY)) {
	e107_include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE);
	e107_include_once(e_LANGUAGEDIR."English/lan_".e_PAGE);
}

if (isset($_POST['userlogin'])) {
	e107_require_once(e_HANDLER."login.php");
	$usr = new userlogin($_POST['username'], $_POST['userpass'], $_POST['autologin']);
}

if (e_QUERY == 'logout') {
	$ip = getip();
	$udata = (USER === TRUE) ? USERID.".".USERNAME :
	"0";
	$sql->db_Update("online", "online_user_id = '0', online_pagecount=online_pagecount+1 WHERE online_user_id = '{$udata}' LIMIT 1");
	if ($pref['user_tracking'] == "session") {
		session_destroy();
		$_SESSION[$pref['cookie_name']] = "";
	}
	cookie($pref['cookie_name'], "", (time()-2592000));
	$e_event->trigger("logout");
	echo "<script type='text/javascript'>document.location.href='".e_BASE."index.php'</script>\n";
	exit;
}
ban();

define("TIMEOFFSET", $pref['time_offset']);

if ($sql->db_Select('menus', '*', 'menu_location > 0 ORDER BY menu_order')) {
	while ($row = $sql->db_Fetch()) {
		$eMenuList[$row['menu_location']][] = $row;
		$eMenuActive[] = $row['menu_name'];
	}
}

if ($sql->db_Select('links', '*', 'link_category = 1 ORDER BY link_order ASC')) {
	while ($row = $sql->db_Fetch()) {
		if (check_class($row['link_class'])) {
			if (substr($row['link_name'], 0, 8) == 'submenu.') {
				$tmp = explode('.', $row['link_name'], 3);
				$eLinkList[$tmp[1]][] = $row;
			} else {
				$eLinkList['head_menu'][] = $row;
			}
		}
	}
}

//echo '<pre>';
//print_r($eLinkList);
//echo '</pre>';

if ((strstr(e_SELF, $ADMIN_DIRECTORY) || strstr(e_SELF, "admin") ) && $pref['admintheme'] && !$_POST['sitetheme']) {
	if (strstr(e_SELF, "menus.php")) {
		checkvalidtheme($pref['sitetheme']);
	}
	else if(strstr(e_SELF, "newspost.php")) {
		define("MAINTHEME", e_THEME.$pref['sitetheme']."/");
		checkvalidtheme($pref['admintheme']);
	} else {
		checkvalidtheme($pref['admintheme']);
	}
} else {
	if (USERTHEME != FALSE && USERTHEME != "USERTHEME") {
		checkvalidtheme(USERTHEME);
	} else {
		checkvalidtheme($pref['sitetheme']);
	}
}
if (strstr(e_SELF, $ADMIN_DIRECTORY)) {
	if (file_exists(THEME.'admin_theme.php')) {
		require_once(THEME.'admin_theme.php');
	} else {
		require_once(THEME."theme.php");
	}
} else {
	require_once(THEME."theme.php");
}
if ($pref['anon_post'] ? define("ANON", TRUE) : define("ANON", FALSE));
if (Empty($pref['newsposts']) ? define("ITEMVIEW", 15) : define("ITEMVIEW", $pref['newsposts']));

if ($pref['antiflood1'] == 1) {
	define('FLOODPROTECT', TRUE);
	define('FLOODTIMEOUT', $pref['antiflood_timeout']);
}

define ("HEADERF", e_THEME."templates/header".$layout.".php");
define ("FOOTERF", e_THEME."templates/footer".$layout.".php");
if (!file_exists(HEADERF)) {
	message_handler("CRITICAL_ERROR", "Unable to find file: ".HEADERF, __LINE__-2, __FILE__);
}
if (!file_exists(FOOTERF)) {
	message_handler("CRITICAL_ERROR", "Unable to find file: ".FOOTERF, __LINE__-2, __FILE__);
}

define("LOGINMESSAGE", "");
define("OPEN_BASEDIR", (ini_get('open_basedir') ? TRUE : FALSE));
define("SAFE_MODE", (ini_get('safe_mode') ? TRUE : FALSE));
define("FILE_UPLOADS", (ini_get('file_uploads') ? TRUE : FALSE));
define("INIT", TRUE);
define("e_REFERER_SELF", ($_SERVER["HTTP_REFERER"] == e_SELF));

//@require_once(e_HANDLER."IPB_int.php");
//@require_once(e_HANDLER."debug_handler.php");

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function check_email($var) {
	return (preg_match('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i', $var)) ? $var :
	FALSE;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function check_class($var, $userclass = USERCLASS, $debug = FALSE) {
	if (preg_match ("/^([0-9]+)$/", $var)) {
		if ($var == e_UC_MEMBER && USER == TRUE) {
			return TRUE;
		}
		if ($var == e_UC_GUEST && USER == FALSE) {
			return TRUE;
		}
		if ($var == e_UC_PUBLIC) {
			return TRUE;
		}
		if ($var == e_UC_NOBODY) {
			return FALSE;
		}
		if ($var == e_UC_ADMIN && ADMIN) {
			return TRUE;
		}
		if ($var == e_UC_READONLY) {
			return TRUE;
		}
	}
	if ($debug) {
		echo "USERCLASS: ".$userclass.", \$var = $var : ";
	}
	if (!defined("USERCLASS") || $userclass == "") {
		if ($debug) {
			echo "FALSE<br />";
		}
		return FALSE;
	}
	// user has classes set - continue
	if (preg_match ("/^([0-9]+)$/", $var)) {
		$tmp = explode(',', $userclass);
		if (is_numeric(array_search($var, $tmp))) {
			if ($debug) {
				echo "TRUE<br />";
			}
			return TRUE;
		}
	} else {
		// var is name of class ...
		$sql = new db;
		if ($sql->db_Select("userclass_classes", "*", "userclass_name='$var' ")) {
			$row = $sql->db_Fetch();
			$tmp = explode(',', $userclass);
			if (is_numeric(array_search($row['userclass_id'], $tmp))) {
				if ($debug) {
					echo "TRUE<br />";
				}
				return TRUE;
			}
		}
	}
	if ($debug) {
		echo "NOTNUM! FALSE<br />";
	}
	return FALSE;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function getperms($arg, $ap = ADMINPERMS) {
	global $PLUGINS_DIRECTORY;
	if ($ap == "0") {
		return TRUE;
	}
	if ($ap == "") {
		return FALSE;
	}
	$ap = '.'.$ap;
	if ($arg == 'P' && preg_match("#(.*?)/".$PLUGINS_DIRECTORY."(.*?)/(.*?)#", e_SELF, $matches) ) {
		$psql = new db;
		if ($psql->db_Select('plugin', 'plugin_id', "plugin_path = '".$matches[2]."' ")) {
			$row = $psql->db_Fetch();
			$arg = 'P'.$row[0];
		}
	}
	if (preg_match("#\.".$arg."\.#", $ap)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function save_prefs($table = "core", $uid = USERID, $row_val = "") {
	global $pref, $user_pref, $tp;
	$sql = new db;
	if ($table == "core") {
		foreach($pref as $key => $prefvalue) {
			$pref[$key] = $tp->toDB($prefvalue);
		}
		$tmp = addslashes(serialize($pref));
		if ($row_val == "") {
			$sql->db_Update("core", "e107_value='$tmp' WHERE e107_name='pref'");
		} else {
			if ($sql->db_Select("core", "e107_name", "e107_name='".$row_val."'")) {
				$sql->db_Update("core", "e107_value='$tmp' WHERE e107_name='".$row_val."'");
			} else {
				$sql->db_Insert("core", "'".$row_val."', '$tmp'");
			}
		}
	} else {
		foreach($user_pref as $key => $prefvalue) {
			$user_pref[$key] = $tp->toDB($prefvalue);
		}
		$tmp = addslashes(serialize($user_pref));
		$sql->db_Update("user", "user_prefs='$tmp' WHERE user_id=$uid");
		return $tmp;
	}
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function online() {
	$page = (strstr(e_SELF, "forum_")) ? e_SELF.".".e_QUERY :
	e_SELF;
	$page = (strstr(e_SELF, "comment")) ? e_SELF.".".e_QUERY :
	$page;
	$page = (strstr(e_SELF, "content")) ? e_SELF.".".e_QUERY :
	$page;
	$online_timeout = 300;
	$online_warncount = 90;
	$online_bancount = 100;
	global $sql, $pref;
	global $listuserson;
	$ip = getip();
	$udata = (USER === TRUE) ? USERID.".".USERNAME :
	"0";

	if (USER) {
		// Find record that matches IP or visitor, or matches user info
		if ($sql->db_Select("online", "*", "(online_ip='{$ip}' AND online_user_id = '0') OR online_user_id = '{$udata}'")) {
			$row = $sql->db_Fetch();
			extract($row);
			if ($online_user_id == $udata) //Matching user record
			{
				if ($online_timestamp < (time() - $online_timeout)) //It has been at least 'timeout' seconds since this user has connected
				{
					//Update user record with timestamp, current IP, current page and set pagecount to 1
					$query = "online_timestamp='".time()."', online_ip='{$ip}', online_location='$page', online_pagecount=1 WHERE online_user_id='{$online_user_id}' LIMIT 1";
				} else {
					if (!ADMIN) {
						$online_pagecount++;
					}
					//Update user record with current IP, current page and increment pagecount
					$query = "online_ip='{$ip}', online_location='$page', online_pagecount={$online_pagecount} WHERE online_user_id='{$online_user_id}' LIMIT 1";
				}
			} else {
				//Found matching visitor record (ip only) for this user
				if ($online_timestamp < (time() - $online_timeout)) //It has been at least 'timeout' seconds since this user has connected
				{
					//Update record with timestamp, current IP, current page and set pagecount to 1
					$query = "online_timestamp='".time()."', online_user_id='{$udata}', online_location='$page', online_pagecount=1 WHERE online_ip='{$ip}' AND online_user_id='0' LIMIT 1";
				} else {
					if (!ADMIN) {
						$online_pagecount++;
					}
					//Update record with current IP, current page and increment pagecount
					$query = "online_user_id='{$udata}', online_location='$page', online_pagecount={$online_pagecount} WHERE online_ip='{$ip}' AND online_user_id='0' LIMIT 1";
				}
			}
			$sql->db_Update("online", $query);
		} else {
			$sql->db_Insert("online", " '".time()."', 'null', '".$udata."', '".$ip."', '".$page."', 1");
		}
	} else {
		//Current page request is from a visitor
		if ($sql->db_Select("online", "*", "online_ip='{$ip}' AND online_user_id = '0'")) {
			$row = $sql->db_Fetch();
			extract($row);
			if ($online_timestamp < (time() - $online_timeout)) //It has been at least 'timeout' seconds since this ip has connected
			{
				//Update record with timestamp, current page, and set pagecount to 1
				$query = "online_timestamp='".time()."', online_location='$page', online_pagecount=1 WHERE online_ip='{$ip}' AND online_user_id='0' LIMIT 1";
			} else {
				//Update record with current page and increment pagecount
				$online_pagecount++;
				//   echo "here {$online_pagecount}";
				$query = "online_location='$page', online_pagecount={$online_pagecount} WHERE online_ip='{$ip}' AND online_user_id='0' LIMIT 1";
			}
			$sql->db_Update("online", $query);
		} else {
			$sql->db_Insert("online", " '".time()."', 'null', '0', '{$ip}', '{$page}', 1");
		}
	}

	if (ADMIN || $pref['autoban'] != 1) {
		$online_pagecount = 1;
	}
	if ($online_pagecount > $online_bancount && $online_ip != "127.0.0.1") {
		$sql->db_Insert("banlist", "'$ip', '0', 'Hit count exceeded ($online_pagecount requests within allotted time)' ");
		$e_event->trigger("flood", $ip);
		exit;
	}
	if ($online_pagecount >= $online_warncount && $online_ip != "127.0.0.1") {
		echo "<div style='text-align:center; font: 11px verdana, tahoma, arial, helvetica, sans-serif;'><b>Warning!</b><br /><br />The flood protection on this site has been activated and you are warned that if you carry on requesting pages you could be banned.<br /></div>";
		exit;
	}

	$sql->db_Delete("online", "online_timestamp<".(time() - $online_timeout));
	$total_online = $sql->db_Count("online");
	if ($members_online = $sql->db_Select("online", "*", "online_user_id != '0' ")) {
		$listuserson = array();
		while ($row = $sql->db_Fetch()) {
			extract($row);
			list($oid, $oname) = explode(".", $online_user_id, 2);
			$member_list .= "<a href='".e_BASE."user.php?id.$oid'>$oname</a> ";
			$listuserson[$online_user_id] = $online_location;
		}
	}
	define("TOTAL_ONLINE", $total_online);
	define("MEMBERS_ONLINE", $members_online);
	define("GUESTS_ONLINE", $total_online - $members_online);
	define("ON_PAGE", $sql->db_Count("online", "(*)", "WHERE online_location='$page' "));
	define("MEMBER_LIST", $member_list);
}

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function cachevars($id, $var) {
	global $cachevar;
	$cachevar[$id] = $var;
}
function getcachedvars($id) {
	global $cachevar;
	return ($cachevar[$id] ? $cachevar[$id] : FALSE);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function getip() {
	/*
	# Get IP address
	#
	# - parameters                none
	# - return                                valid IP address
	# - scope                                        public
	*/
	if (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = $_SERVER['REMOTE_ADDR'];
		if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip3)) {
			$ip2 = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
			$ip = preg_replace($ip2, $ip, $ip3[1]);
		}
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if ($ip == "") {
		$ip = "x.x.x.x";
	}
	return $ip;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class floodprotect {
	function flood($table, $orderfield) {
		/*
		# Test for possible flood
		#
		# - parameter #1                string $table, table being affected
		# - parameter #2                string $orderfield, date entry in respective table
		# - return                                boolean
		# - scope                                        public
		*/
		$sql = new db;
		if (FLOODPROTECT == TRUE) {
			$sql->db_Select($table, "*", "ORDER BY ".$orderfield." DESC LIMIT 1", "no_where");
			$row = $sql->db_Fetch();
			return ($row[$orderfield] > (time() - FLOODTIMEOUT) ? FALSE : TRUE);
		} else {
			return TRUE;
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function init_session() {
	/*
	# Validate user
	#
	# - parameters none
	# - return boolean
	# - scope public
	*/
	global $sql, $pref, $user_pref, $tp;

	if (!$_COOKIE[$pref['cookie_name']] && !$_SESSION[$pref['cookie_name']]) {
		define("USER", FALSE);
		define("USERTHEME", FALSE);
		define("ADMIN", FALSE);
		define("GUEST", TRUE);
		define('USERCLASS', '');
		define('USEREMAIL', '');

	} else {
		list($uid, $upw) = ($_COOKIE[$pref['cookie_name']] ? explode(".", $_COOKIE[$pref['cookie_name']]) : explode(".", $_SESSION[$pref['cookie_name']]));
		if (empty($uid) || empty($upw)) // corrupt cookie?
		{
			cookie($pref['cookie_name'], "", (time()-2592000));
			$_SESSION[$pref['cookie_name']] = "";
			session_destroy();
			define("ADMIN", FALSE);
			define("USER", FALSE);
			define("USERCLASS", "");
			define("LOGINMESSAGE", "Corrupted cookie detected - logged out.<br /><br />");
			return(FALSE);
		}
		if ($sql->db_Select("user", "*", "user_id='$uid' AND md5(user_password)='$upw'")) {
			$result = $sql->db_Fetch();
			extract($result);
			define("USERID", $user_id);
			define("USERNAME", $user_name);
			define("USERURL", $user_homepage);
			define("USEREMAIL", $user_email);
			define("USER", TRUE);
			define("USERCLASS", $user_class);
			define("USERREALM", $user_realm);
			define("USERVIEWED", $user_viewed);
			define("USERIMAGE", $user_image);
			define("USERSESS", $user_sess);
			if ($user_currentvisit + 3600 < time()) {
				$user_lastvisit = $user_currentvisit;
				$user_currentvisit = time();
				$sql->db_Update("user", "user_visits=user_visits+1, user_lastvisit='$user_lastvisit', user_currentvisit='$user_currentvisit', user_viewed='' WHERE user_name='".USERNAME."' ");
			}
			define("USERLV", $user_lastvisit);
			if ($user_ban == 1) {
				exit;
			}
			$user_pref = unserialize($user_prefs);
			if (isset($_POST['settheme'])) {
				$user_pref['sitetheme'] = ($pref['sitetheme'] == $_POST['sitetheme'] ? "" : $_POST['sitetheme']);
				save_prefs($user);
			}


			define("USERTHEME", ($user_pref['sitetheme'] && file_exists(e_THEME.$user_pref['sitetheme']."/theme.php") ? $user_pref['sitetheme'] : FALSE));
			global $ADMIN_DIRECTORY, $PLUGINS_DIRECTORY;

			if ($user_admin) {
				define("ADMIN", TRUE);
				define("ADMINID", $user_id);
				define("ADMINNAME", $user_name);
				define("ADMINPERMS", $user_perms);
				define("ADMINEMAIL", $user_email);
				define("ADMINPWCHANGE", $user_pwchange);
			} else {
				define("ADMIN", FALSE);
			}
		} else {
			define("USER", FALSE);
			define("USERTHEME", FALSE);
			define("ADMIN", FALSE);
			define("CORRUPT_COOKIE", TRUE);
			define("USERCLASS", "");
		}
	}
	define('USERCLASS_LIST', class_list());
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function ban() {
	$sql = new db;
	$ip = getip();
	$wildcard = substr($ip, 0, strrpos($ip, ".")).".*";
	if($ip != '127.0.0.1'){
		if ($sql->db_Select("banlist", "*", "banlist_ip='".$_SERVER['REMOTE_ADDR']."' OR banlist_ip='".USEREMAIL."' OR banlist_ip='$ip' OR banlist_ip='$wildcard'")) {
			// enter a message here if you want some text displayed to banned users ...
			exit;
		}
	}
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function cookie($name, $value, $expire, $path = "/", $domain = "", $secure = 0) {
	setcookie($name, $value, $expire, $path, $domain, $secure);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function message_handler($mode, $message, $line = 0, $file = "") {
	e107_require_once(e_HANDLER."message_handler.php");
	show_emessage($mode, $message, $line, $file);
}
// -----------------------------------------------------------------------------
function table_exists($check) {
	if (!$GLOBALS['mySQLtablelist']) {
		$tablist = mysql_list_tables($GLOBALS['mySQLdefaultdb']);
		while (list($temp) = mysql_fetch_array($tablist)) {
			$GLOBALS['mySQLtablelist'][] = $temp;

		}
	}

	$mltable = MPREFIX.strtolower($check);
	foreach($GLOBALS['mySQLtablelist'] as $lang) {
		if (eregi($mltable, $lang)) {
			return TRUE;
		}
	}
}

function class_list($uid = '') {
	$clist = array();
	if ($uid == '') {
		if (USER === TRUE) {
			$clist = explode(',', USERCLASS);
			$clist[] = e_UC_MEMBER;
			if (ADMIN === TRUE) {
				$clist[] = e_UC_ADMIN;
			}
		} else {
			$clist[] = e_UC_GUEST;
		}
		$clist[] = e_UC_READONLY;
		$clist[] = e_UC_PUBLIC;
		return implode(',', $clist);
	}
}

// ---------------------------------------------------------------------------
function e107_include($fname) {
	global $e107_debug;
	return ($e107_debug ? include($fname) : @include($fname));
}
function e107_include_once($fname) {
	global $e107_debug;
	return ($e107_debug ? include_once($fname) : @include_once($fname));
}

function e107_require_once($fname) {
	global $e107_debug;
	return ($e107_debug ? require_once($fname) : @require_once($fname));
}
function e107_require($fname) {
	global $e107_debug;
	return ($e107_debug ? require($fname) : @require($fname));
}

?>