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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/lastseen/lastseen_menu.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-06-28 20:13:14 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/

if (file_exists(e_PLUGIN."lastseen/languages/".e_LANGUAGE.".php"))
{
	include_once(e_PLUGIN."lastseen/languages/".e_LANGUAGE.".php");
}
else
{
	include_once(e_PLUGIN."lastseen/languages/English.php");
}

$sql -> db_Select("user", "user_id, user_name, user_currentvisit", "ORDER BY user_currentvisit DESC LIMIT 0,10", "nowhere");
$userArray = $sql -> db_getList();

$gen = new convert;
$text = "<ul style='margin-left:15px; margin-top:0px; padding-left:0px;'>";
foreach($userArray as $user)
{
	extract($user);
	$lastseen = $gen -> computeLapse($user_currentvisit, false, false, true, 'short')." ".LANDT_AGO;
	$text .= "<li style='list-style-type: square;'><a href='".e_BASE."user.php?id.$user_id'>".$user_name."</a><br /> [ ".$lastseen." ]</li>";
}
$text .= "</ul>";

$ns->tablerender(LSP_LAN_1, $text);
?>