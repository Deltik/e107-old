<?php
/*
+ ----------------------------------------------------------------------------+
e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/index.php,v $
|     $Revision: 1.6 $
|     $Date: 2005-01-31 23:01:50 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
	
require_once("class2.php");
if ($pref['frontpage'] == 'links') {
	$pref['frontpage'] = $PLUGINS_DIRECTORY."links_page/links.php";
	save_prefs();
} else if ($pref['frontpage'] == 'forum') {
	$pref['frontpage'] = $PLUGINS_DIRECTORY."forum/forum.php";
	save_prefs();
} else if (is_numeric($pref['frontpage'])) {
	$pref['frontpage'] = "content.php?content.".$pref['frontpage'];
	save_prefs();
} else if (strpos($pref['frontpage'], ".")===FALSE) {
	if (!preg_match("#/$#",$pref['frontpage'])) {
		$pref['frontpage'] = $pref['frontpage'].'.php';
		save_prefs();
	}
}

if ($pref['membersonly_enabled'] && !USER)
{
	require_once(e_LOGIN);
	exit;
}
else if (!$pref['frontpage'] || $pref['frontpage_type'] == "splash")
{
	require_once(e_BASE."news.php");
	exit;
}
else if(strpos($pref['frontpage'], "http")!==FALSE)
{
	if (strpos($pref['frontpage'], ".php")!==FALSE) {
		require_once($pref['frontpage']);
		exit;
	} else {
		header("location: ".$pref['frontpage']);
		exit;
	}
}
else
{
	if (strpos($pref['frontpage'], ".php")!==FALSE) {
		require_once(e_BASE.$pref['frontpage']);
		exit;
	} else {
		header("location: ".e_BASE.$pref['frontpage']);
		exit;
	}
}

?>