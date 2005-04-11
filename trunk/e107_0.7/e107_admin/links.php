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
|     $Source: /cvs_backup/e107_0.7/e107_admin/links.php,v $
|     $Revision: 1.32 $
|     $Date: 2005-04-11 23:13:42 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

require_once('../class2.php');
if (!is_object($tp)) $tp = new e_parse;
if (!getperms('I')) {
	header('location:'.e_BASE.'index.php');
}
$e_sub_cat = 'links';
// ----- Presets.----------
require_once(e_HANDLER."preset_class.php");
$pst = new e_preset;
$pst->form = "linkform";
$pst->page = "links.php?create";
$pst->id = "admin_links";
require_once('auth.php');
// --------------------
$pst->save_preset();


require_once(e_HANDLER.'userclass_class.php');
require_once(e_HANDLER.'form_handler.php');


$rs = new form;
$linkpost = new links;

$deltest = array_flip($_POST);

if (e_QUERY) {
	$tmp = explode('.', e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}
if (preg_match("#(.*?)_delete_(\d+)#", $deltest[$tp->toJS(LAN_DELETE)], $matches)) {
	$delete = $matches[1];
	$del_id = $matches[2];
}

if (IsSet($_POST['inc'])) {
	$qs = explode(".", $_POST['inc']);
	$linkid = $qs[0];
	$link_order = $qs[1];
	$sql->db_Update("links", "link_order=link_order+1 WHERE link_order='".($link_order-1)."'");
	$sql->db_Update("links", "link_order=link_order-1 WHERE link_id='".$linkid."'");
}

if (IsSet($_POST['dec'])) {
	$qs = explode(".", $_POST['dec']);
	$linkid = $qs[0];
	$link_order = $qs[1];
	$sql->db_Update("links", "link_order=link_order-1 WHERE link_order='".($link_order+1)."'");
	$sql->db_Update("links", "link_order=link_order+1 WHERE link_id='".$linkid."'");
}

if (isset($_POST['update'])) {
	foreach ($_POST['link_order'] as $loid) {
		$tmp = explode(".", $loid);
		$sql->db_Update("links", "link_order=".$tmp[1]." WHERE link_id=".$tmp[0]);
	}
	foreach ($_POST['link_class'] as $lckey => $lcid) {
		$sql->db_Update("links", "link_class=".$lcid." WHERE link_id=".$lckey);
	}
	$e107cache->clear("sitelinks");
	$linkpost->show_message(LAN_UPDATED);
}

if (isset($_POST['updateoptions'])) {
	$pref['linkpage_screentip'] = $_POST['linkpage_screentip'];
	$pref['sitelinks_expandsub'] = $_POST['sitelinks_expandsub'];
	save_prefs();
	$linkpost->show_message(LCLAN_1);
}

if ($delete == 'main') {
	$sql->db_Select("links", "link_order", "link_id='".$del_id."'");
	$row = $sql->db_Fetch();
	$sql2 = new db;
	$sql->db_Select("links", "link_id", "link_order>'".$row['link_order']."'");
	while ($row = $sql->db_Fetch()) {
		$sql2->db_Update("links", "link_order=link_order-1 WHERE link_id='".$row['link_id']."'");
	}
	if ($sql->db_Delete("links", "link_id='".$del_id."'")) {
		$e107cache->clear("sitelinks");
		$linkpost->show_message(LCLAN_53." #".$del_id." ".LCLAN_54);
	}
}

if (isset($_POST['add_link'])) {
	$linkpost->submit_link($sub_action, $id);
	unset($id);
}

if ($action == 'create') {
	$linkpost->create_link($sub_action, $id);
}

if (!e_QUERY || $action == 'main') {
	$linkpost->show_existing_items();
}

if ($action == 'opt') {
	$linkpost->show_pref_options();
}

require_once('footer.php');
exit;

// End ---------------------------------------------------------------------------------------------------------------------------------------------------------------------

class links {
	function show_existing_items() {
		global $sql, $rs, $ns, $tp;
		if ($link_total = $sql->db_Select("links", "*", "ORDER BY link_order, link_id ASC", "nowhere")) {
			$text = $rs->form_open("post", e_SELF, "myform_{$link_id}", "", "");
			$text .= "<div style='text-align:center'>
				<table class='fborder' style='".ADMIN_WIDTH."'>
				<tr>
				<td class='fcaption' style='width:5%'>".LCLAN_89."</td>
				<td class='fcaption' style='width:60%'>".LCLAN_90."</td>
				<td class='fcaption' style='width:15%'>".LAN_OPTIONS."</td>
				<td class='fcaption' style='width:10%'>".LCLAN_95."</td>
				<td class='fcaption' style='width:5%'>".LCLAN_91."</td>
				<td class='fcaption' style='width:5%'>".LAN_ORDER."</td>
				</tr>";
			while ($row = $sql->db_Fetch()) {
				extract($row);
				$text .= "<tr><td class='forumheader3' style='width:5%; text-align: center; vertical-align: middle' title='".$link_description."'>";
				$text .= $link_button ? "<img src='".e_IMAGE."icons/".$link_button."' alt='' /> ":
				"";
				$text .= "</td><td style='width:60%' class='forumheader3' title='".$link_description."'>".$link_name."</td>";
				$text .= "<td style='width:15%; text-align:center; white-space: nowrap' class='forumheader3'>";
				$text .= $rs->form_button("button", "main_edit_{$link_id}", LAN_EDIT, "onclick=\"document.location='".e_SELF."?create.edit.$link_id'\"");
				$text .= $rs->form_button("submit", "main_delete_".$link_id, LAN_DELETE, "onclick=\"return jsconfirm('".$tp->toJS(LCLAN_58." [ $link_name ]")."')\"");
				$text .= "</td>";
				$text .= "<td style='width:10%; text-align:center' class='forumheader3'>".r_userclass("link_class[".$link_id."]", $link_class, "off", "public,guest,nobody,member,admin,classes")."</td>";
				$text .= "<td style='width:5%; text-align:center; white-space: nowrap' class='forumheader3'>";
				$text .= "<input type='image' src='".e_IMAGE."admin_images/up.png' title='".LCLAN_30."' value='".$link_id.".".$link_order."' name='inc' />";
				$text .= "<input type='image' src='".e_IMAGE."admin_images/down.png' title='".LCLAN_31."' value='".$link_id.".".$link_order."' name='dec' />";
				$text .= "</td>";
				$text .= "<td style='width:5%; text-align:center' class='forumheader3'>";
				$text .= "<select name='link_order[]' class='tbox'>";
				for($a = 1; $a <= $link_total; $a++) {
					$text .= ($row['link_order'] == $a) ? "<option value='".$row['link_id'].".".$a."' selected='selected'>".$a."</option>" :
					 "<option value='".$row['link_id'].".".$a."'>".$a."</option>";
				}
				$text .= "</select>";
				$text .= "</td>";
				$text .= "</tr>";
			}
			$text .= "<tr>
				<td class='forumheader' colspan='6' style='text-align:center'><input class='button' type='submit' name='update' value='".LAN_UPDATE."' /></td>
				</tr>";
			$text .= "</table></div>";
			$text .= $rs->form_close();
		} else {
			$text .= "<div style='text-align:center'>".LCLAN_61."</div>";
		}
		$ns->tablerender(LCLAN_8, $text);
	}

	function show_message($message) {
		global $ns;
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	function create_link($sub_action, $id) {
		global $sql, $rs, $ns, $pst;

		$preset = $pst->read_preset("admin_links");
		extract($preset);

		if ($sub_action == "edit" && !$_POST['submit']) {
			if ($sql->db_Select("links", "*", "link_id='$id' ")) {
				$row = $sql->db_Fetch();
				extract($row);
			}
		}

		$handle = opendir(e_IMAGE."icons");
		while ($file = readdir($handle)) {
			if ($file != "." && $file != ".." && $file != "/" && $file != "CVS") {
				$iconlist[] = $file;
			}
		}
		closedir($handle);

		$text = "<div style='text-align:center'>
			<form method='post' action='".e_SELF."?".e_QUERY."' id='linkform'>
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td style='width:30%' class='forumheader3'>".LCLAN_15.": </td>
			<td style='width:70%' class='forumheader3'>
			<input class='tbox' type='text' name='link_name' size='60' value='$link_name' maxlength='100' />
			</td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".LCLAN_16.": </td>
			<td style='width:70%' class='forumheader3'>
			<input class='tbox' type='text' name='link_url' size='60' value='$link_url' maxlength='200' />
			</td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".LCLAN_17.": </td>
			<td style='width:70%' class='forumheader3'>
			<textarea class='tbox' name='link_description' cols='59' rows='3'>$link_description</textarea>
			</td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".LCLAN_18.": </td>
			<td style='width:70%' class='forumheader3'>
			<input class='tbox' type='text' name='link_button' size='42' value='$link_button' maxlength='100' />

					<input class='button' type ='button' style='cursor:hand' size='30' value='".LCLAN_39."' onclick='expandit(this)' />
			<div id='linkicn' style='display:none;{head}'>";

		while (list($key, $icon) = each($iconlist)) {
			$text .= "<a href=\"javascript:insertext('$icon','link_button','linkicn')\"><img src='".e_IMAGE."icons/".$icon."' style='border:0' alt='' /></a> ";
		}

		// 0 = same window
		// 1 = _blank
		// 2 = _parent
		// 3 = _top
		// 4 = miniwindow


		$text .= "</div></td>
			</tr>
			<tr>
			<td style='width:30%' class='forumheader3'>".LCLAN_19.": </td>
			<td style='width:70%' class='forumheader3'>
			<select name='linkopentype' class='tbox'>". ($link_open == 0 ? "<option value='0' selected='selected'>".LCLAN_20."</option>" : "<option value='0'>".LCLAN_20."</option>"). ($link_open == 1 ? "<option value='1' selected='selected'>".LCLAN_23."</option>" : "<option value='1'>".LCLAN_23."</option>"). ($link_open == 4 ? "<option value='4' selected='selected'>".LCLAN_24."</option>" : "<option value='4'>".LCLAN_24."</option>")."
			</select>
			</td>
			</tr>
            <tr>
			<td style='width:30%' class='forumheader3'>".LCLAN_12.": </td>
			<td style='width:70%' class='forumheader3'>
			<select name='linkrender' class='tbox'>";
			$rentype = array("","Main","Alt","Alt", "Alt");
            for ($i=1; $i<count($rentype); $i++) {
			$sel = ($link_category == $i) ? "selected='selected'" : "";
            $text .="<option value='$i' $sel>$i - ".$rentype[$i]."</option>";
            };

            $text .="</select><span class='smalltext'> Shown in your theme as {SITELINKS=flat:[rendertype number]}</span>
			</td>
			</tr>
			<tr>
			<td style='width:30%' class='forumheader3'>".LCLAN_25.":<br /><span class='smalltext'>(".LCLAN_26.")</span></td>
			<td style='width:70%' class='forumheader3'>".r_userclass("link_class", $link_class, "off", "public,guest,nobody,member,admin,classes")."
			</td></tr>

			<tr style='vertical-align:top'>
			<td colspan='2' style='text-align:center' class='forumheader'>";
		if ($id && $sub_action == "edit") {
			$text .= "<input class='button' type='submit' name='add_link' value='".LCLAN_27."' />\n<input type='hidden' name='link_id' value='$link_id'>";
		} else {
			$text .= "<input class='button' type='submit' name='add_link' value='".LCLAN_28."' />";
		}
		$text .= "</td>
			</tr>
			</table>
			</form>
			</div>";
		$ns->tablerender(LCLAN_29, $text);
	}

	function submit_link($sub_action, $id) {
		global $sql, $e107cache, $tp;
		if(!is_object($tp)) {
			$tp=new e_parse;
		}
		$link_name = $tp->toDB($_POST['link_name']);
		$link_url = $tp->toDB($_POST['link_url']);
		$link_description = $tp->toDB($_POST['link_description']);
		$link_button = $tp->toDB($_POST['link_button']);

		$link_t = $sql->db_Count("links", "(*)");

		if ($id) {
			$sql->db_Update("links", "link_name='$link_name', link_url='$link_url', link_description='$link_description', link_button= '$link_button', link_category='".$_POST['linkrender']."', link_open='".$_POST['linkopentype']."', link_class='".$_POST['link_class']."' WHERE link_id='$id'");
			$e107cache->clear("sitelinks");
			$this->show_message(LCLAN_3);
		} else {
			$sql->db_Insert("links", "0, '$link_name', '$link_url', '$link_description', '$link_button', '".$_POST['linkrender']."', '".($link_t+1)."', '0', '".$_POST['linkopentype']."', '".$_POST['link_class']."'");
			$e107cache->clear("sitelinks");
			$this->show_message(LCLAN_2);
		}
	}

	function show_pref_options() {
		global $pref, $ns;
		$text = "<div style='text-align:center'>
			<form method='post' action='".e_SELF."?".e_QUERY."'>\n
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td style='width:70%' class='forumheader3'>
			".LCLAN_78."<br />
			<span class='smalltext'>".LCLAN_79."</span>
			</td>
			<td class='forumheader3' style='width:30%;text-align:center'>". ($pref['linkpage_screentip'] ? "<input type='checkbox' name='linkpage_screentip' value='1' checked='checked' />" : "<input type='checkbox' name='linkpage_screentip' value='1' />")."
			</td>
			</tr>

            <tr>
			<td style='width:70%' class='forumheader3'>
			".LCLAN_80."<br />
			<span class='smalltext'>".LCLAN_81."</span>
			</td>
			<td class='forumheader3' style='width:30%;text-align:center'>". ($pref['sitelinks_expandsub'] ? "<input type='checkbox' name='sitelinks_expandsub' value='1' checked='checked' />" : "<input type='checkbox' name='sitelinks_expandsub' value='1' />")."
			</td>
			</tr>



			<tr style='vertical-align:top'>
			<td colspan='2' style='text-align:center' class='forumheader'>
			<input class='button' type='submit' name='updateoptions' value='".LAN_UPDATE."' />
			</td>
			</tr>

			</table>
			</form>
			</div>";
		$ns->tablerender(LCLAN_88, $text);
	}
}

function links_adminmenu() {
	global $action;
	if ($action == "") {
		$action = "main";
	}
	$var['main']['text'] = LCLAN_62;
	$var['main']['link'] = e_SELF;

	$var['create']['text'] = LCLAN_63;
	$var['create']['link'] = e_SELF."?create";

	$var['opt']['text'] = LAN_OPTIONS;
	$var['opt']['link'] = e_SELF."?opt";

	$var['sub']['text'] = LCLAN_83;
	$var['sub']['link'] = "submenusgen.php";

	show_admin_menu(LCLAN_68, $action, $var);
}

?>