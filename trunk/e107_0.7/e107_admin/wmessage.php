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
|     $Source: /cvs_backup/e107_0.7/e107_admin/wmessage.php,v $
|     $Revision: 1.10 $
|     $Date: 2005-01-31 05:53:28 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
require_once(e_HANDLER.'e_parse_class.php');
$aj = new e_parse;
if (!getperms("M")) {
	header("location:".e_BASE."index.php");
	 exit;
}
$e_sub_cat = 'wmessage';
require_once("auth.php");
require_once(e_HANDLER.'form_handler.php');
require_once(e_HANDLER.'userclass_class.php');
require_once(e_HANDLER."ren_help.php");

$rs = new form;

if (e_QUERY) {
	$tmp = explode('.', e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}

if (isset($_POST['wm_update'])) {
	$wm_text = $aj->toDB($_POST['wm_text']);
	$message = ($sql->db_Update("wmessage", "wm_text ='$wm_text', wm_active='".$_POST['wm_active']."' WHERE wm_id='".$_POST['wm_id']."' ")) ? LAN_UPDATED : LAN_UPDATED_FAILED;
}

if (isset($_POST['wm_insert'])) {
	$wmtext = $aj->toDB($_POST['wm_text']);
	$message = ($sql->db_Insert("wmessage", "0, '$wmtext', '".$_POST['wm_active']."' ")) ? LAN_CREATED :  LAN_CREATED_FAILED ;
}

if (isset($_POST['updateoptions'])) {
	$pref['wm_enclose'] = $_POST['wm_enclose'];
	$pref['wmessage_sc'] = $_POST['wmessage_sc'];
	save_prefs();
	$message = LAN_SETSAVED;
}

$deltest = array_flip($_POST);
if (preg_match("#(.*?)_delete_(\d+)#", $deltest[$aj->toJS(LAN_DELETE)], $matches)) {
	$delete = $matches[1];
	$del_id = $matches[2];
}

if ($delete && $del_id) {
	$message = ($sql->db_Delete("wmessage", "wm_id='".$del_id."'  ")) ? LAN_DELETED : LAN_DELETED_FAILED ;
}


if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}




// Show Existing -------
if ($action == "main" || $action == "") {
	if ($wm_total = $sql->db_Select("wmessage", "*", "ORDER BY wm_id, wm_id ASC", "nowhere")) {
		$text = $rs->form_open("post", e_SELF, "myform_{$link_id}", "", "");
		$text .= "<div style='text-align:center'>
			<table class='fborder' style='".ADMIN_WIDTH."'>
			<tr>
			<td class='fcaption' style='width:5%'>ID</td>
			<td class='fcaption' style='width:70%'>".WMLAN_02."</td>
			<td class='fcaption' style='width:20%'>".WMLAN_03."</td>
			<td class='fcaption' style='width:15%'>".LAN_OPTIONS."</td>
			</tr>";
		while ($row = $sql->db_Fetch()) {
			extract($row);
			$text .= "<tr><td class='forumheader3' style='width:5%; text-align: center; vertical-align: middle'>";
			//   $text .= $wm_id ? "<img src='".e_IMAGE."link_icons/".$link_button."' alt='' /> ":"";
			$text .= $wm_id;
			$text .= "</td><td style='width:70%' class='forumheader3'>".$wm_text."</td>";
			$text .= "</td><td style='width:70%' class='forumheader3'>".r_userclass_name($wm_active)."</td>";

			$text .= "</td><td style='width:15%; text-align:center; white-space: nowrap' class='forumheader3'>";
			$text .= $rs->form_button("button", "main_edit_{$wm_id}", LAN_EDIT, "onclick=\"document.location='".e_SELF."?create.edit.$wm_id'\"");
			$text .= $rs->form_button("submit", "main_delete_".$wm_id, LAN_DELETE, "onclick=\"return confirm_('".$wm_id."')\"");
			$text .= "</td>";
			$text .= "</tr>";
		}


		$text .= "</table></div>";
		$text .= $rs->form_close();
	} else {
		$text .= "<div style='text-align:center'>".LCLAN_61."</div>";
	}
	$ns->tablerender(WMLAN_00, $text);


}


// Create and Edit
if ($action == "create" || $action == "edit") {
	$sql->db_Select("wmessage", "*", "wm_id = $id");
	$row = $sql->db_Fetch();
	 extract($row);

	$text = "
		<div style='text-align:center'>
		<form method='post' action='".e_SELF."'  id='wmform'>
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>";

	$text .= "

		<td style='width:20%' class='forumheader3'>".WMLAN_04."</td>
		<td style='width:60%' class='forumheader3'>
		<textarea class='tbox' name='wm_text' cols='70' rows='10' style='width:90%'>".$wm_text."</textarea>
		<br />
		<input class='helpbox' type='text' name='helpguest' size='100' />
		<br />
		".ren_help(1, "addtext1", "help1")."
		</td>

		</tr>";

	$text .= "<tr><td class='forumheader3'>".WMLAN_03."</td>
		<td class='forumheader3'>".r_userclass("wm_active", $wm_active, "off", "public,guest,nobody,member,admin,classes")."</td></tr>";

	$text .= "
		<tr style='vertical-align:top'>

		<td colspan='2' class='forumheader' style='text-align:center'>";

	$text .= ($sub_action == "edit") ? "<input class='button' type='submit' name='wm_update' value='".LAN_UPDATE."' />" :
	 "<input class='button' type='submit' name='wm_insert' value='".LAN_CREATE."' />" ;
	$text .= "<input type='hidden' name='wm_id' value='".$id."' />";
	$text .= "</td>
		</tr>
		</table>
		</form>
		</div>";
	$ns->tablerender(WMLAN_01, $text);
}


if ($action == "opt") {
	global $pref, $ns;
	$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?".e_QUERY."'>\n
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>

		<td style='width:70%' class='forumheader3'>
		".WMLAN_05."<br />
		<span class='smalltext'>".WMLAN_06."</span>
		</td>
		<td class='forumheader3' style='width:30%;text-align:center'>". ($pref['wm_enclose'] ? "<input type='checkbox' name='wm_enclose' value='1' checked='checked' />" : "<input type='checkbox' name='wm_enclose' value='1' />")."
		</td>
		</tr>
		<tr>

		<td style='width:70%' class='forumheader3'>
		".WMLAN_07."
		</td>
		<td class='forumheader3' style='width:30%;text-align:center'>". ($pref['wmessage_sc'] ? "<input type='checkbox' name='wmessage_sc' value='1' checked='checked' />" : "<input type='checkbox' name='wmessage_sc' value='1' />")."
		</td>
		</tr>

		<tr style='vertical-align:top'>
		<td colspan='2'  style='text-align:center' class='forumheader'>
		<input class='button' type='submit' name='updateoptions' value='".LAN_SAVE."' />
		</td>
		</tr>

		</table>
		</form>
		</div>";

	$ns->tablerender(WMLAN_00.": ".LAN_PREFS, $text);


}

function wmessage_adminmenu() {
	global $action;
	if ($action == "") {
		$action = "main";
	}
	$var['main']['text'] = WMLAN_00;
	$var['main']['link'] = e_SELF;
	$var['create']['text'] = WMLAN_01;
	$var['create']['link'] = e_SELF."?create";
	$var['opt']['text'] = WMLAN_05;
	$var['opt']['link'] = e_SELF."?opt";

	show_admin_menu(LAN_OPTIONS, $action, $var);
}

function headerjs() {
	global $aj;

	$headerj = "<script type='text/javascript'>
		function addtext1(sc){
		document.getElementById('wm_text').value += sc;
		}

		function fclear(){
		document.newspostform.message.value = '';
		}
		function help1(help){
		document.getElementById('wmform').helpguest.value = help;
		}

		function confirm_(link_id){
		return confirm('".$aj->toJS(LAN_CONFIRMDEL." id:")." ' + link_id);
		}
		</script>";

	return $headerj;
}


require_once("footer.php");
?>