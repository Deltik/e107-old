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
|     $Source: /cvs_backup/e107_0.7/e107_admin/search.php,v $
|     $Revision: 1.8 $
|     $Date: 2005-03-20 15:00:28 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/

require_once("../class2.php");
if (!getperms("X")) {
	header("location:".e_BASE."index.php");
	exit;
}
$e_sub_cat = 'search';
require_once("auth.php");
$search_prefs = $sysprefs -> getArray('search_prefs');

$search_handlers['news'] = SEALAN_5;
$search_handlers['comments'] = SEALAN_6;
$search_handlers['users'] = SEALAN_7;
$search_handlers['downloads'] = SEALAN_8;

if (isset($_POST['updatesettings'])) {
	$pref['search_restrict'] = $_POST['search_restrict'];
	save_prefs();
	$search_prefs['search_chars'] = $_POST['search_chars'];
	$search_prefs['search_sort'] = $_POST['search_sort'];
	$search_prefs['search_res'] = $_POST['search_res'];
	$search_prefs['relevance'] = $_POST['relevance'];
	$search_prefs['user_select'] = $_POST['user_select'];
	$search_prefs['time_restrict'] = $_POST['time_restrict'];
	$search_prefs['time_secs'] = $_POST['time_secs'] > 300 ? 300 : $_POST['time_secs'];
	foreach($search_handlers as $s_key => $s_value) {
		$search_prefs['core_handlers'][$s_key] = $_POST['core_handlers'][$s_key];
	}

	foreach ($search_prefs['plug_handlers'] as $plug_dir => $active) {
		$search_prefs['plug_handlers'][$plug_dir] = $_POST['plug_handlers'][$plug_dir];
	}

	$search_prefs['google'] = $_POST['google'];
	
	foreach ($search_prefs['comments_handlers'] as $key => $value) {
		$search_prefs['comments_handlers'][$key]['active'] = $_POST['comments_handlers'][$key];
	}

	$tmp = addslashes(serialize($search_prefs));
	$sql->db_Update("core", "e107_value='".$tmp."' WHERE e107_name='search_prefs' ");
	$message = TRUE;
}

require_once(e_HANDLER."form_handler.php");
$rs = new form;

if (isset($message)) {
		$ns->tablerender("", "<div style='text-align:center'><b>".LAN_UPDATED."</b></div>");
}

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."'>
<table style='".ADMIN_WIDTH."' class='fborder'>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_15.": </td>
<td style='width:50%' class='forumheader3'>
<input type='radio' name='search_restrict' value='1'".($pref['search_restrict'] ? " checked='checked'" : "")." /> ".SEALAN_16."&nbsp;&nbsp;
<input type='radio' name='search_restrict' value='0'".(!$pref['search_restrict'] ? " checked='checked'" : "")." /> ".SEALAN_17."
</td>
</tr>";
	
$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_2."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>".$rs -> form_text("search_chars", 4, $search_prefs['search_chars'], 4)."</td>
</tr>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_9."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>".$rs -> form_text("search_res", 4, $search_prefs['search_res'], 4)."</td>
</tr>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_3."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>".$rs -> form_radio('search_sort', 'php', ($search_prefs['search_sort'] == 'php' ? 1 : 0))."PHP".$rs -> form_radio('search_sort', 'mysql', ($search_prefs['search_sort'] == 'mysql' ? 1 : 0))."MySql</td>
</tr>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_10."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>".$rs -> form_checkbox('relevance', '1', $search_prefs['relevance'])."</td>
</tr>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_11."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>".$rs -> form_checkbox('user_select', '1', $search_prefs['user_select'])."</td>
</tr>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_12."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>".$rs -> form_checkbox('time_restrict', '1', $search_prefs['time_restrict'])." ".SEALAN_13." ".$rs -> form_text("time_secs", 3, $search_prefs['time_secs'], 3)." ".SEALAN_14."</td>
</tr>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_4."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>";

foreach($search_handlers as $key => $value) {
	$sel = (isset($search_prefs['core_handlers'][$key]) && $search_prefs['core_handlers'][$key]) ? " checked='checked'" : "";
	$text .= "<span style='white-space:nowrap'><input type='checkbox' name='core_handlers[".$key."]' ".$sel." />".$value."</span>\n";
}

foreach ($search_prefs['plug_handlers'] as $plug_dir => $active) {
	require_once(e_PLUGIN.$plug_dir."/e_search.php");
	$sel = $active ? " checked='checked'" : "";
	$text .= "<span style='white-space:nowrap'><input type='checkbox' name='plug_handlers[".$plug_dir."]' ".$sel." />".$search_info[0]['qtype']."</span>\n";
	unset($search_info);
}

$sel = (isset($search_prefs['google']) && $search_prefs['google']) ? " checked='checked'" : "";
$text .= "<input id='google' type='checkbox' name='google' ".$sel." />Google";

$text .= "</td>
</tr>";

$text .= "<tr>
<td style='width:50%; white-space:nowrap' class='forumheader3'>".SEALAN_18."</td>
<td style='width:50%;' colspan='2' class='forumheader3'>";

foreach ($search_prefs['comments_handlers'] as $key => $value) {
	$path = ($value['dir'] == 'core') ? e_HANDLER.'search/'.$value['handler'] : e_PLUGIN.$value['dir'].'/comments_search.php';
	require_once($path);
	$sel = $value['active'] ? " checked='checked'" : "";
	$text .= "<span style='white-space:nowrap'><input type='checkbox' name='comments_handlers[".$key."]' ".$sel." />".$comments_title."</span>\n";
	unset($comments_title);
}

$text .= "</td>
</tr>";

$text .= "<tr>
<td colspan='2' style='text-align:center' class='forumheader'>".$rs -> form_button("submit", "updatesettings", LAN_UPDATE)."</td>
</tr>";

$text .= "</table>
</form>
</div>";
	
$ns -> tablerender(SEALAN_1, $text);

require_once("footer.php");

?>