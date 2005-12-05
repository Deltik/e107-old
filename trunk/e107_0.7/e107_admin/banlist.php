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
|     $Source: /cvs_backup/e107_0.7/e107_admin/banlist.php,v $
|     $Revision: 1.16 $
|     $Date: 2005-12-05 19:28:57 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if (!getperms("4")) {
	header("location:".e_BASE."index.php");
	exit;
}
$e_sub_cat = 'banlist';
require_once("auth.php");
require_once(e_HANDLER."form_handler.php");
$rs = new form;

if (e_QUERY) {
	$tmp = explode("-", e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}

$_POST['ban_ip'] = trim($_POST['ban_ip']);

if (isset($_POST['add_ban']) && $_POST['ban_ip'] != "" && strpos($_POST['ban_ip'], ' ') === false) {
	$_POST['ban_reason'] = $tp->toDB($_POST['ban_reason']);
	admin_update($sql -> db_Insert("banlist", "'".$_POST['ban_ip']."', '".ADMINID."', '".$_POST['ban_reason']."'"), 'insert');
	unset($ban_ip);
}

if (isset($_POST['update_ban']) && $_POST['ban_ip'] != "" && strpos($_POST['ban_ip'], ' ') === false) {
	$_POST['ban_reason'] = $tp->toDB($_POST['ban_reason']);
	admin_update($sql -> db_Update("banlist", "banlist_ip='".$_POST['ban_ip']."', banlist_admin=".ADMINID.", banlist_reason='".$_POST['ban_reason']."' WHERE banlist_ip='".$_POST['old_ip']."'"));
	unset($ban_ip);
}

if ($action == "remove" && isset($_POST['ban_secure'])) {
	admin_update($sql -> db_Delete("banlist", "banlist_ip='$sub_action'"), 'delete');
}

if ($action == "edit") {
	$sql->db_Select("banlist", "*", "banlist_ip='$sub_action'");
	$row = $sql->db_Fetch();
	extract($row);
} else {
	unset($banlist_ip, $banlist_reason);
	if (e_QUERY && strpos($_SERVER["HTTP_REFERER"], "userinfo")) {
		$banlist_ip = $action;
	}
}


$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."'>
	<table style='".ADMIN_WIDTH."' class='fborder'>

	<tr>
	<td style='width:30%' class='forumheader3'>".BANLAN_5.": </td>
	<td style='width:70%' class='forumheader3'>
	<input class='tbox' type='text' name='ban_ip' size='40' value='".$banlist_ip."' maxlength='200' />
	</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".BANLAN_7.": </td>
	<td style='width:80%' class='forumheader3'>
	<textarea class='tbox' name='ban_reason' cols='50' rows='4'>$banlist_reason</textarea>
	</td>
	</tr>

	<tr style='vertical-align:top'>
	<td colspan='2' style='text-align:center' class='forumheader'>".
($action == "edit" ? "<input type='hidden' name='old_ip' value='$banlist_ip' /><input class='button' type='submit' name='update_ban' value='".LAN_UPDATE."' />" : "<input class='button' type='submit' name='add_ban' value='".BANLAN_8."' />")."

	</td>
	</tr>
	</table>
	</form>
	</div>";

$ns->tablerender(BANLAN_9, $text);

if ($action != "edit") {
	$text = $rs->form_open("post", e_SELF, "ban_form")."<div style='text-align:center'>".$rs->form_hidden("ban_secure", "1");
	if (!$ban_total = $sql->db_Select("banlist","*","ORDER BY banlist_ip","nowhere")) {
		$text .= "<div style='text-align:center'>".BANLAN_2."</div>";
	} else {
		$text .= "<table class='fborder' style='".ADMIN_WIDTH."'>
			<tr>
			<td style='width:70%' class='fcaption'>".BANLAN_10."</td>
			<td style='width:30%' class='fcaption'>".LAN_OPTIONS."</td>
			</tr>";
		$count = 0;
		while ($row = $sql->db_Fetch()) {
			extract($row);
			$banlist_reason = str_replace("LAN_LOGIN_18", BANLAN_11, $banlist_reason);
			$text .= "<tr><td style='width:70%' class='forumheader3'>$banlist_ip<br />".BANLAN_7.": $banlist_reason</td>
				<td style='width:30%; text-align:center' class='forumheader3'>".$rs->form_button("submit", "main_edit_$count", LAN_EDIT, "onclick=\"document.getElementById('ban_form').action='".e_SELF."?edit-$banlist_ip'\"").$rs->form_button("submit", "main_delete_$count", BANLAN_4, "onclick=\"document.getElementById('ban_form').action='".e_SELF."?remove-$banlist_ip'\"")."</td>\n</tr>";
			$count++;
		}
		$text .= "</table>\n";
	}
	$text .= "</div>".$rs->form_close();
	$ns->tablerender(BANLAN_3, $text);
}

require_once("footer.php");
?>