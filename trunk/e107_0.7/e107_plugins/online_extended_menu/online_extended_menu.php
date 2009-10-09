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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/online_extended_menu/online_extended_menu.php,v $
|     $Revision: 1.20 $
|     $Date: 2007-10-07 05:40:50 $
|     $Author: streaky $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

if(!defined("e_TRACKING_DISABLED") && (isset($pref['track_online']) && $pref['track_online'])) {
	$text = ONLINE_EL1.GUESTS_ONLINE.", ";
	$text .= ONLINE_EL2.MEMBERS_ONLINE." ...<br />";

	if (MEMBERS_ONLINE) {
		global $listuserson, $ADMIN_DIRECTORY;
		foreach($listuserson as $uinfo => $pinfo) {


			list($oid, $oname) = explode(".", $uinfo, 2);
			$online_location_page = substr(strrchr($pinfo, "/"), 1);
			if ($pinfo == "log.php" || $pinfo == "error.php") {
				$online_location_page = "news.php";
				$pinfo = "news.php";
			}
			if ($online_location_page == "request.php") {
				$pinfo = "download.php";
			}
			if (strstr($online_location_page, "forum")) {
				$pinfo = e_PLUGIN."forum/forum.php";
				$online_location_page = "forum.php";
			}
			if (strstr($online_location_page, "content")) {
				$pinfo = "content.php";
				$online_location_page = "content.php";
			}
			if (strstr($online_location_page, "comment")) {
				$pinfo = "comment.php";
				$online_location_page = "comment.php";
			}
			$text .= "<img src='".e_IMAGE_ABS."admin_images/users_16.png' alt='' style='vertical-align:middle' /> <a href='".e_BASE."user.php?id.$oid'>$oname</a> ".ONLINE_EL7;
			(!strstr($pinfo, $ADMIN_DIRECTORY) ? $text .= " <a href='{$pinfo}'>$online_location_page</a><br />" : $text .= " $online_location_page<br />");
		}
	}

	if ((MEMBERS_ONLINE + GUESTS_ONLINE) > ($menu_pref['most_members_online'] + $menu_pref['most_guests_online'])) {
		$menu_pref['most_members_online'] = MEMBERS_ONLINE;
		$menu_pref['most_guests_online'] = GUESTS_ONLINE;
		$menu_pref['most_online_datestamp'] = time();
		$tmp = addslashes(serialize($menu_pref));
		$sql->db_Update("core", "e107_value='$tmp' WHERE e107_name='menu_pref' ");
	}
    global $gen;
	if (!is_object($gen)) {
		$gen = new convert;
	}

	$datestamp = $gen->convert_date($menu_pref['most_online_datestamp'], "short");

	$text .= "<br />".ONLINE_EL8." ".($menu_pref['most_members_online'] + $menu_pref['most_guests_online'])."<br />(".ONLINE_EL2.$menu_pref['most_members_online'].", ".ONLINE_EL1.$menu_pref['most_guests_online'].") ".ONLINE_EL9." ".$datestamp."<br />";

 	$total_members = $sql->db_Count("user","(*)","where user_ban = 0");
	if ($total_members > 1) {
		$newest_member = $sql->db_Select("user", "user_id, user_name", "user_ban = 0 ORDER BY user_join DESC LIMIT 1");
		$row = $sql->db_Fetch();
		extract($row);
		$text .= "<br />".ONLINE_EL5.": ".$total_members."<br />".ONLINE_EL6.": <a href='".e_BASE."user.php?id.".$user_id."'>".$user_name."</a>";
	}
} elseif(ADMIN) {
	global $tp;
	$text = $tp->toHtml(ONLINE_TRACKING_MESSAGE,TRUE);
}


$ns->tablerender(ONLINE_EL4, $text, 'online_extended');

?>