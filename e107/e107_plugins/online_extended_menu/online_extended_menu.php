<?php

	$text = ONLINE_EL1.GUESTS_ONLINE.", ";
//	if($pref['user_reg'] == 1){
		$text .= ONLINE_EL2.MEMBERS_ONLINE." ...<br />";
//	}
	if(MEMBERS_ONLINE){
		global $listuserson, $ADMIN_DIRECTORY;
		foreach($listuserson as $uinfo => $pinfo){
			
	
			list($oid,$oname) = explode(".",$uinfo,2);
			$online_location_page = substr(strrchr($pinfo, "/"), 1);
			if($pinfo == "log.php" || $pinfo == "error.php"){ $online_location_page = "news.php"; $pinfo = "news.php"; }
			if($online_location_page == "request.php"){ $pinfo = "download.php"; }
			if(strstr($online_location_page, "forum")){ $pinfo = "forum.php"; $online_location_page = "forum.php"; }
			if(strstr($online_location_page, "content")){ $pinfo = "content.php"; $online_location_page = "content.php"; }
			if(strstr($online_location_page, "comment")){ $pinfo = "comment.php"; $online_location_page = "comment.php"; }
			$text .= "<img src='".e_PLUGIN."online_extended_menu/images/user.png' alt='' style='vertical-align:middle' /> <a href='".e_BASE."user.php?id.$oid'>$oname</a> ".ONLINE_EL7;
      (!strstr($pinfo,$ADMIN_DIRECTORY) ? $text .= " <a href='{$pinfo}'>$online_location_page</a><br />" : $text .= " $online_location_page<br />");
	   }
	}
	
	if((MEMBERS_ONLINE + GUESTS_ONLINE) > ($menu_pref['most_members_online'] + $menu_pref['most_guests_online'])){
		$menu_pref['most_members_online'] = MEMBERS_ONLINE;
		$menu_pref['most_guests_online'] = GUESTS_ONLINE;
		$menu_pref['most_online_datestamp'] = time();
		$tmp = addslashes(serialize($menu_pref));
		$sql -> db_Update("core", "e107_value='$tmp' WHERE e107_name='menu_pref' ");
	}

	if(!is_object($gen)){
		$gen = new convert;
	}

	$datestamp = $gen->convert_date($menu_pref['most_online_datestamp'], "short");
	
	$text .= "<br />".ONLINE_EL8.": ".($menu_pref['most_members_online'] + $menu_pref['most_guests_online'])."<br />(".strtolower(ONLINE_EL2).$menu_pref['most_members_online'].", ".strtolower(ONLINE_EL1).$menu_pref['most_guests_online'].") ".ONLINE_EL9." ".$datestamp."<br />";

	$total_members = $sql -> db_Count("user");

	if($total_members > 1){
		$newest_member = $sql -> db_Select("user", "user_id, user_name", "user_ban='0' ORDER BY user_join DESC LIMIT 0,1");
		$row = $sql -> db_Fetch(); extract($row);
		$text .= "<br />".ONLINE_EL5.": ".$total_members."<br />".ONLINE_EL6.": <a href='".e_BASE."user.php?id.".$user_id."'>".$user_name."</a>";
	}

	$ns -> tablerender(ONLINE_EL4, $text);

?>