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
|     $Source: /cvs_backup/e107_0.7/e107_themes/templates/header_default.php,v $
|     $Revision: 1.12 $
|     $Date: 2005-01-15 01:26:39 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
if (!function_exists("parseheader")) {
	function parseheader($LAYOUT){
		global $tp;
		$tmp = explode("\n", $LAYOUT);
		for ($c=0; $c < count($tmp); $c++) {
			if (preg_match("/{.+?}/", $tmp[$c])) {
				echo $tp -> parseTemplate($tmp[$c]);
			} else {
				echo $tmp[$c];
			}
		}
	}
}
$sql->db_Mark_Time('(Header Top)');
$aj = new textparse;
echo (defined("STANDARDS_MODE") ? "" : "<?xml version='1.0' encoding='".CHARSET."' ?>")."<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>".SITENAME.(defined("e_PAGETITLE") ? ": ".e_PAGETITLE : (defined("PAGE_NAME") ? ": ".PAGE_NAME : ""))."</title>\n";
echo "<meta http-equiv='content-type' content='text/html; charset=".CHARSET."' />
<meta http-equiv='content-style-type' content='text/css' />\n";
echo "<link rel='alternate' type='application/rss+xml' title='".SITENAME." RSS' href='".e_FILE."backend/news.xml' />\n";
echo "<link rel='stylesheet' href='".THEME."style.css' type='text/css' />\n";
if (file_exists(THEME.'e107.css')) {
	echo "<link rel='stylesheet' href='".THEME."e107.css' type='text/css' />\n";
} else if (file_exists(e_FILE.'e107.css')) {
	echo "<link rel='stylesheet' href='".e_FILE."e107.css' type='text/css' />\n";
}
if(function_exists('theme_head')){
	echo theme_head();
}
if (file_exists(e_BASE."favicon.ico")) { echo "\n<link rel=\"shortcut icon\" href=\"favicon.ico\" />\n"; }
if ($eplug_css) { echo "\n<link rel='stylesheet' href='{$eplug_css}' type='text/css' />\n"; }
echo $pref['meta_tag'] ? $aj -> formtparev($pref['meta_tag'])."\n" : "";

echo "<script type='text/javascript' src='".e_FILE."e107.js'></script>\n";
if (file_exists(THEME.'theme.js')) { echo "<script type='text/javascript' src='".THEME."theme.js'></script>\n"; }
if (file_exists(e_FILE.'user.js')) { echo "<script type='text/javascript' src='".e_FILE."user.js'></script>\n"; }
if ($eplug_js) { echo "<script type='text/javascript' src='".$eplug_js."'></script>\n"; }
if ($htmlarea_js) { echo $htmlarea_js; }
if (function_exists('headerjs')){echo headerjs();  }

echo "<script type='text/javascript'>\n";
echo "<!--\n";
if ($pref['log_activate']) {
	echo "document.write( '<link rel=\"stylesheet\" type=\"text/css\" href=\"".e_PLUGIN."log/log.php?referer=' + ref + '&color=' + colord + '&eself=' + eself + '&res=' + res + '\">' );\n";
}
//echo "var ejs_listpics = new Array();";

$fader_onload = ($sql -> db_Select("menus", "*", "menu_name='fader_menu' AND menu_location!='0' ")) ? 'changecontent();' : '';
$links_onload = 'externalLinks();';
$body_onload = ($fader_onload != '' || $links_onload != '') ? " onload='".$links_onload." ".$fader_onload."'" : "";
$ejs_listpics = '';

$handle=opendir(THEME.'images');
while ($file = readdir($handle)) {
	if (!strstr($file, "._") && strstr($file,".") && $file != "." && $file != ".." && $file != "Thumbs.db" && $file != ".DS_Store") {
		$ejs_listpics .= $file.",";
	}
}
$ejs_listpics = substr($ejs_listpics, 0, -1);
closedir($handle);

echo "ejs_preload('".THEME."images/','".$ejs_listpics."');\n// -->\n</script>
</head>
<body".$body_onload.">";
//echo "XX - ".$e107_popup;
// require $e107_popup =1; to use it as header for popup without menus
if ($e107_popup != 1) {
	if ($pref['no_rightclick']) {
		echo "<script language='javascript'>\n";
		echo "<!--\n";
		echo "var message=\"Not Allowed\";\n";
		echo "function click(e) {\n";
		echo "	if (document.all) {\n";
		echo "		if (event.button==2||event.button==3) {\n";
		echo "			alert(message);\n";
		echo "			return false;\n";
		echo "		}\n";
		echo "	}\n";
		echo "	if (document.layers) {\n";
		echo "		if (e.which == 3) {\n";
		echo "			alert(message);\n";
		echo "			return false;\n";
		echo "		}\n";
		echo "	}\n";
		echo "}\n";
		echo "if (document.layers) {\n";
		echo "	document.captureevents(event.mousedown);\n";
		echo "}\n";
		echo "document.onmousedown=click;\n";
		echo "// -->\n";
		echo "</script>\n";
	}

        if (is_array($CUSTOMPAGES)) {
                foreach ($CUSTOMPAGES as $cust_key => $cust_value) {
                        $custompage[$cust_key] = explode(' ', $cust_value);
                }
        } else {
                $custompage['no_array'] = explode(' ', $CUSTOMPAGES);
        }

        if (e_PAGE == 'news.php' && $NEWSHEADER) {
                parseheader($NEWSHEADER);
        } else {
                foreach ($custompage as $key_extract => $cust_extract) {
                        foreach ($cust_extract as $key => $kpage) {
                                if ($kpage && strstr(e_SELF, $kpage)) {
                                        $ph = TRUE;
                                        if ($key_extract=='no_array') {
                                                $cust_header = $CUSTOMHEADER ? $CUSTOMHEADER : $HEADER;
                                                $cust_footer = $CUSTOMFOOTER ? $CUSTOMFOOTER : $FOOTER;
                                        } else {
                                                $cust_header = $CUSTOMHEADER[$key_extract] ? $CUSTOMHEADER[$key_extract] : $HEADER;
                                                $cust_footer = $CUSTOMFOOTER[$key_extract] ? $CUSTOMFOOTER[$key_extract] : $FOOTER;
                                        }
                                        break;
                                }
                        }
                }
                parseheader(($ph ? $cust_header : $HEADER));
        }
        $sql->db_Mark_Time("Main Page Body");
        unset($text);
}
?>