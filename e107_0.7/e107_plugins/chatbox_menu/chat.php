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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/chatbox_menu/chat.php,v $
|     $Revision: 1.5 $
|     $Date: 2005-06-15 15:11:47 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
require_once("../../class2.php");
if (file_exists(e_PLUGIN."chatbox_menu/languages/".e_LANGUAGE."/".e_LANGUAGE.".php")) {
	include_once(e_PLUGIN."chatbox_menu/languages/".e_LANGUAGE."/".e_LANGUAGE.".php");
} else {
	include_once(e_PLUGIN."chatbox_menu/languages/English/English.php");
}
require_once(HEADERF);


$sql->db_Select("menus", "*", "menu_name='chatbox_menu'");
$row = $sql->db_Fetch();

if (!check_class($row['menu_class'])) {
	$ns->tablerender(LAN_14, "<div style='text-align:center'>".LAN_15."</div>");
	require_once(FOOTERF);
	exit;
}

if (strstr(e_QUERY, "fs")) {
	$cgtm = str_replace(".fs", "", e_QUERY);
	$fs = TRUE;
}

if (e_QUERY ? $from = e_QUERY : $from = 0);
if (!$view) {
	$view = 30;
}

$chat_total = $sql->db_Count("chatbox");

if ($fs) {
	$page_count = 0;
	$row_count = 0;
	$sql->db_Select("chatbox", "*", "cb_blocked=0 ORDER BY cb_datestamp DESC");
	while ($row = $sql -> db_Fetch()) {
		if ($row['cb_id'] == $cgtm) {
			$from = $page_count;
			break;
		}
		$row_count++;
		if ($row_count == 30) {
			$row_count = 0;
			$page_count += 30;
		}
	}
}

$sql->db_Select("chatbox", "*", "cb_blocked=0 ORDER BY cb_datestamp DESC LIMIT $from, ".$view);
	
$obj2 = new convert;

while ($row = $sql->db_Fetch()) {
	$CHAT_TABLE_DATESTAMP = $obj2->convert_date($row['cb_datestamp'], "long");
	$CHAT_TABLE_NICK = eregi_replace("[0-9]+\.", "", $row['cb_nick']);
	$cb_message = ($row['cb_blocked'] ? LAN_16 : $tp->toHTML($row['cb_message']));
	if (!eregi("<a href|<img|&#", $cb_message)) {
		$cb_message = preg_replace("/([^\s]{100})/", "$1\n", $cb_message);
	}
	$CHAT_TABLE_MESSAGE = $cb_message;
	$CHAT_TABLE_FLAG = ($flag ? "forumheader3" : "forumheader4");

	if (!$CHAT_TABLE) {
		if (file_exists(THEME."chat_template.php")) {
			require_once(THEME."chat_template.php");
		} else {
			require_once(e_BASE.$THEMES_DIRECTORY."templates/chat_template.php");
		}
	}
	$textstring .= preg_replace("/\{(.*?)\}/e", '$\1', $CHAT_TABLE);
	$flag = (!$flag ? TRUE : FALSE);
}

$textstart = preg_replace("/\{(.*?)\}/e", '$\1', $CHAT_TABLE_START);
$textend = preg_replace("/\{(.*?)\}/e", '$\1', $CHAT_TABLE_END);
$text = $textstart.$textstring.$textend;

$ns->tablerender(LAN_11, $text);


require_once(e_HANDLER."np_class.php");
$ix = new nextprev("chat.php", $from, 30, $chat_total, LAN_12);


require_once(FOOTERF);

?>