<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /e107_admin/header.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
|   $Source: /cvs_backup/e107_0.7/e107_admin/header.php,v $
|   $Revision: 1.30 $
|   $Date: 2005-04-04 09:23:00 $
|   $Author: sweetas $
+---------------------------------------------------------------+
*/
if (!defined('e_HTTP')) {
	exit;
}
require_once(e_ADMIN.'ad_links.php');
echo defined('STANDARDS_MODE') ? "" :
 "<?xml version='1.0' encoding='".CHARSET."' ?>";
if (file_exists(e_LANGUAGEDIR.e_LANGUAGE.'/admin/lan_header.php')) {
	@include_once(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_header.php");
} else {
	@include_once(e_LANGUAGEDIR."English/admin/lan_header.php");
}
if (file_exists(e_LANGUAGEDIR.e_LANGUAGE.'/admin/lan_footer.php')) {
	@include_once(e_LANGUAGEDIR.e_LANGUAGE.'/admin/lan_footer.php');
} else {
	@include_once(e_LANGUAGEDIR.'English/admin/lan_footer.php');
}
if (!defined('ADMIN_WIDTH')) {
	define('ADMIN_WIDTH', 'width: 95%');
}
if (file_exists(THEME.'admin_template.php')) {
  	require_once(THEME.'admin_template.php');
} else {
  	require_once(e_BASE.$THEMES_DIRECTORY.'templates/admin_template.php');
}

if (!defined('ADMIN_EDIT_ICON'))
{
	define("ADMIN_EDIT_ICON", "<img src='".e_IMAGE."admin_images/edit_16.png' alt='' title='".LAN_EDIT."' style='border:0px; height:16px; width:16px' />");
	define("ADMIN_EDIT_ICON_PATH", e_IMAGE."admin_images/edit_16.png");
}

if (!defined('ADMIN_DELETE_ICON'))
{
	define("ADMIN_DELETE_ICON", "<img src='".e_IMAGE."admin_images/delete_16.png' alt='' title='".LAN_DELETE."' style='border:0px; height:16px; width:16px' />");
	define("ADMIN_DELETE_ICON_PATH", e_IMAGE."admin_images/delete_16.png");
}

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
	<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
	<title>".SITENAME." : ".LAN_head_4."</title>\n";
echo "<meta http-equiv='content-type' content='text/html; charset=".CHARSET."' />
	<meta http-equiv='content-style-type' content='text/css' />\n";
if (strpos(e_SELF.'?'.e_QUERY, 'menus.php?configure') === FALSE && $pref['admincss'] && file_exists(THEME.$pref['admincss'])) {
	echo "<link rel='stylesheet' href='".THEME.$pref['admincss']."' />\n";
} else {
	echo "<link rel='stylesheet' href='".THEME."style.css' />\n";
}

if (!$no_core_css) {
	echo "<link rel='stylesheet' href='".e_FILE."e107.css' type='text/css' />\n";
}
if (function_exists('theme_head')) {
   	echo theme_head();
}
if (strpos(e_SELF.'?'.e_QUERY, 'menus.php?configure') === FALSE) {
	echo "<script type='text/javascript' src='".e_FILE."e107.js'></script>\n";
}
if (file_exists(THEME."theme.js")) {
	echo "<script type='text/javascript' src='".THEME."theme.js'></script>\n";
}
if (filesize(e_FILE.'user.js')) {
	echo "<script type='text/javascript' src='".e_FILE."user.js'></script>\n";
}
if (function_exists("headerjs")) {
	echo headerjs();
}
if (isset($htmlarea_js) && $htmlarea_js) {
	echo $htmlarea_js;
}
echo "<script type='text/javascript'>
		function savepreset(ps,pid){
			document.getElementById(ps).action='".e_SELF."?savepreset.'+pid;
			document.getElementById(ps).submit();
		}
	</script> ";
if (isset($eplug_js) && $eplug_js) {
	echo "<script type='text/javascript' src='{$eplug_js}'></script>\n";
}
if (isset($eplug_css) && $eplug_css) {
	echo "\n<link rel='stylesheet' href='{$eplug_css}' type='text/css' />\n";
}
if((ADMIN || $pref['allow_html']) && $pref['wysiwyg'] && $e_wysiwyg == TRUE){
  	require_once(e_HANDLER."tiny_mce/wysiwyg.php");
	echo wysiwyg($e_wysiwyg);
}
echo "</head>
	<body>";

$ns = new e107table;
$e107_var = array();

if (!function_exists('show_admin_menu')) {
	function show_admin_menu($title, $page, $e107_vars, $js = FALSE) {
		global $ns;
		$text = "<div style='text-align:center; width:100%'><table class='fborder' style='width:98%;'>\n";
		foreach (array_keys($e107_vars) as $act) {
			$pre = "";
			$post = "";

		  	if ($page == $act || (str_replace("?","",e_PAGE.e_QUERY) == str_replace("?","",$act))) {
				$pre = "<b>&laquo;&nbsp;";
				$post = "&nbsp;&raquo;</b>";
		 	}
			$t = str_replace(" ", "&nbsp;", $e107_vars[$act]['text']);
			if (!$e107_vars[$act]['perm'] || getperms($e107_vars[$act]['perm'])) {
				$on_click = $js ? "onclick=\"showhideit('".$act."');\"" : "onclick=\"location.href='{$e107_vars[$act]['link']}'\" ";
				$text .= "<tr><td class='button'><div style='width:100%; text-align:center'><a style='cursor:hand; cursor:pointer; text-decoration:none;' ".$on_click." >{$pre}{$t}{$post}</a></div></td></tr>\n";
			}
		}
		$text .= "</table></div>\n";
		if ($title == "") {
			return $text;
		}
		$ns->tablerender($title, $text);
	}
}

if (!function_exists("parse_admin")) {
	function parse_admin($ADMINLAYOUT) {
		global $tp;
		$adtmp = explode("\n", $ADMINLAYOUT);
		for ($a = 0; $a < count($adtmp); $a++) {
			if (preg_match("/{.+?}/", $adtmp[$a])) {
				echo $tp->parseTemplate($adtmp[$a]);
			} else {
				echo $adtmp[$a];
			}
		}
	}
}

if (strpos(e_SELF.'?'.e_QUERY, 'menus.php?configure') === FALSE) {
	parse_admin($ADMIN_HEADER);
}

function get_admin_treemenu($title, $page, $e107_vars, $sortlist = FALSE) {
	global $ns;
	if ($sortlist == TRUE) {
		$temp = $e107_vars;
		unset($e107_vars);
		foreach (array_keys($temp) as $key) {
			$func_list[] = $temp[$key]['text'];
		}

		usort($func_list, 'strcoll');

		foreach ($func_list as $func_text) {
			foreach (array_keys($temp) as $key) {
				if ($temp[$key]['text'] == $func_text) {
					$e107_vars[] = $temp[$key];
				}
			}
		}
	}

	$idtitle = "yop_".str_replace(" ", "", $title);
	$text = "<div style='text-align:center; width:100%'><table class='fborder' style='width:100%;'>";
	$text .= "<tr><td class='button'><a style='text-align:center; cursor:hand; cursor:pointer; text-decoration:none;' onclick=\"expandit('{$idtitle}');\" >{$title}</a></td></tr>";
	$text .= "<tr id=\"{$idtitle}\" style=\"display: none;\" ><td class='forumheader3' style='text-align:left;'>";
	foreach (array_keys($e107_vars) as $act) {
		$pre = "";
		$post = "";
		if ($page == $act) {
			$pre = "<b> &laquo; ";
			$post = " &raquo; </b>";
		}
		if (!$e107_vars[$act]['perm'] || getperms($e107_vars[$act]['perm'])) {
			$text .= "{$pre}<a style='text-decoration:none;' href='{$e107_vars[$act]['link']}'>{$e107_vars[$act]['text']}</a>{$post}<br />";
		}
	}

	$text .= "</td></tr>";
	$text .= "</table></div>";
	return $text;
}
?>