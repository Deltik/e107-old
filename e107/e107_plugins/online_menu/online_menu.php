<?php
$text = ONLINE_L1.GUESTS_ONLINE."<br />";
//if($pref['user_reg'] == 1){
	$text .= ONLINE_L2.MEMBERS_ONLINE.(MEMBERS_ONLINE ? ", ": "").MEMBER_LIST."<br />";
//}
$text .= ONLINE_L3.ON_PAGE;
$caption = (file_exists(THEME."images/online_menu.png") ? "<img src='".THEME."images/online_menu.png' alt='' /> ".ONLINE_L4 : ONLINE_L4);

$total_members = $sql -> db_Count("user");

if($total_members > 1){
	$newest_member = $sql -> db_Select("user", "user_id, user_name", "ORDER BY user_join DESC LIMIT 0,1", "no_where");
	$row = $sql -> db_Fetch(); extract($row);
	$text .= "<br />".ONLINE_L5.": ".$total_members.", ".ONLINE_L6.": <a href='".e_BASE."user.php?id.$user_id'>$user_name</a>";
}

$ns -> tablerender($caption, $text);
?>