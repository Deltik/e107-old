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
|     $Source: /cvs_backup/e107_0.7/e107_admin/content.php,v $
|     $Revision: 1.9 $
|     $Date: 2005-02-24 10:48:26 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");

if (!getperms("J") && !getperms("K") && !getperms("L")) {
	header("location:".e_HTTP."index.php");
	exit;
}
$e_sub_cat = 'content';
$e_wysiwyg = "data";

require_once("auth.php");
require_once(e_HANDLER."userclass_class.php");
require_once(e_HANDLER."form_handler.php");


$rs = new form;

if (e_QUERY) {
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}

foreach($_POST as $k => $v) {
	if (preg_match("#^main_delete_(\d*)$#", $k, $matches) && $_POST[$k] == $tp->toJS(CNTLAN_7)) {
		$delete_content = $matches[1];
	}
}

$aj = new textparse;

If(isset($_POST['submit'])) {
	if ($_POST['data'] != "") {
		$content_subheading = $aj->formtpa($_POST['content_subheading'], "admin");
		$content_heading = $aj->formtpa($_POST['content_heading'], "admin");
		$content_content = $aj->formtpa($_POST['data'], "admin");
		$sql->db_Insert("content", "0, '".$content_heading."', '".$content_subheading."', '$content_content', '".$_POST['auto_line_breaks']."', '".time()."', '".ADMINID."', '".$_POST['content_comment']."', '', '1', 0, ".$_POST['add_icons'].",  {$_POST['c_class']}");
		if ($_POST['content_heading']) {
			$sql->db_Select("content", "*", "ORDER BY content_datestamp DESC LIMIT 0,1 ", $mode = "no_where");
			list($content_id, $content_heading) = $sql->db_Fetch();
			$sql->db_Insert("links", "0, '".$content_heading."', 'content.php?content.$content_id', '', '', '1', '0', '0', '0', {$_POST['c_class']} ");
			$e107cache->clear("sitelinks");
			$message = CNTLAN_24;
		} else {
			$sql->db_Select("content", "*", "ORDER BY content_datestamp DESC LIMIT 0,1 ", $mode = "no_where");
			list($content_id, $content_heading) = $sql->db_Fetch();
			$message = CNTLAN_23." - 'article.php?".$content_id.".255'.";
		}
		$e107cache->clear("content");
		unset($content_heading, $content_subheading, $content_content, $content_parent);
	} else {
		$message = CNTLAN_1;
	}
}

if (isset($_POST['update'])) {
	$content_subheading = $aj->formtpa($_POST['content_subheading'], "admin");
	$content_heading = $aj->formtpa($_POST['content_heading'], "admin");
	$content_content = $aj->formtpa($_POST['data'], "admin");
	$sql->db_Update("content", " content_heading='$content_heading', content_subheading='$content_subheading', content_content='$content_content', content_parent='".$_POST['auto_line_breaks']."',  content_comment='".$_POST['content_comment']."', content_type='1', content_class='{$_POST['c_class']}', content_pe_icon='".$_POST['add_icons']."' WHERE content_id='".$_POST['content_id']."'");
	$sql->db_Update("links", "link_class='".$_POST['c_class']."' WHERE link_name='$content_heading' ");
	unset($content_heading, $content_subheading, $content_content, $content_parent);
	$message = CNTLAN_2;
	$e107cache->clear("content");
	$e107cache->clear("sitelinks");
}

if ($delete_content) {
	$sql = new db;
	$sql->db_Select("content", "*", "content_id=$delete_content");
	$row = $sql->db_Fetch();
	 extract($row);
	$sql->db_Delete("links", "link_name='".$content_heading."' ");
	$sql->db_Delete("content", "content_id=$delete_content");
	$message = CNTLAN_20;
	unset($content_heading, $content_subheading, $content_content);
	$e107cache->clear("content");
	$e107cache->clear("sitelinks");
}

if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$text = "<div style='text-align:center'><div style='padding : 1px; ".ADMIN_WIDTH."; height : 100px; overflow : auto; margin-left: auto; margin-right: auto;'>\n";
if (!$content_total = $sql->db_Select("content", "*", "content_type='254' OR content_type='255' OR content_type='1' ORDER BY content_datestamp DESC")) {
	$text .= "<div style='text-align:center'>".CNTLAN_4."</div>";
} else {
	$text .= "
		<form method='post' action='".e_SELF."' >
		<table class='fborder' style='width:99%'>
		<tr>
		<td style='width:5%' class='fcaption'>&nbsp;</td>
		<td style='width:65%' class='fcaption'>".CNTLAN_25."</td>
		<td style='width:30%' class='fcaption'>".CNTLAN_26."</td>
		</tr>";
	while ($row = $sql->db_Fetch()) {
		extract($row);
		$content_title = ($content_heading) ? $content_heading :
		 $content_subheading;
		$text .= "<tr><td style='width:5%; text-align:center' class='forumheader3'>$content_id</td>
			<td style='width:65%' class='forumheader3'>$content_title</td>
			<td style='width:30%; text-align:center' class='forumheader3'>
			".$rs->form_button("button", "main_edit_{$content_id}", CNTLAN_6, "onclick=\"document.location='".e_SELF."?edit.$content_id'\"")."
			".$rs->form_button("submit", "main_delete_{$content_id}", CNTLAN_7," onclick=\"return jsconfirm('".$tp->toJS(CNTLAN_27)." [ID: $content_id ]')\"")."

			</td>\n</tr>";
	}
	$text .= "</table>\n</form>";
}
$text .= "<br /></div></div>";

$ns->tablerender(CNTLAN_5, $text);

unset($content_heading, $content_subheading, $content_content, $content_parent, $content_comment);

if ($action == "edit") {
	if ($sql->db_Select("content", "*", "content_id=$sub_action")) {
		$row = $sql->db_Fetch();
		 extract($row);
	}
} else {
	$content_comment = TRUE;
}

$article_total = $sql->db_Select("content", "*", "content_type='254' OR content_type='255' OR content_type='1' ");

$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."' id='dataform'>
	<table style='".ADMIN_WIDTH."' class='fborder'>

	<tr>
	<td style='width:20%; vertical-align:top' class='forumheader3'>".CNTLAN_10.":</td>
	<td style='width:80%' class='forumheader3'>
	<input class='tbox' type='text' name='content_heading' size='60' value='$content_heading' maxlength='100' />

	</td>
	</tr>
	<tr>
	<td style='width:20%' class='forumheader3'>".CNTLAN_11.":</td>
	<td style='width:80%' class='forumheader3'>
	<input class='tbox' type='text' name='content_subheading' size='60' value='$content_subheading' maxlength='100' />
	</td>
	</tr>
	<tr>
	<td colspan='2' class='forumheader3'><span style='text-decoration: underline'>".CNTLAN_12.": </span></td></tr>
	<tr><td colspan='2' class='forumheader3'>";

	$insertjs = (!$pref['wysiwyg'])? "onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'": "";
	$text .= "<textarea class='tbox' id='data' name='data' style='width:100%' rows='30' cols='60' $insertjs >$content_content</textarea>";

if (!$pref['wysiwyg']) {
	$text .= "
		<br />
		<input class='helpbox' type='text' name='helpb' size='100' />
		<br />";
	require_once(e_HANDLER."ren_help.php");
	$text .= ren_help();
}
$text .= "
	</td>
	</tr>

	<tr>
	<td style='width:20%' class='forumheader3'>".CNTLAN_21."?:</td>
	<td style='width:80%' class='forumheader3'>";

if ($content_parent) {
	$text .= CNTLAN_14.": <input type='radio' name='auto_line_breaks' value='0' />
		".CNTLAN_15.": <input type='radio' name='auto_line_breaks' value='1' checked='checked' />";
} else {
	$text .= CNTLAN_14.": <input type='radio' name='auto_line_breaks' value='0' checked='checked' />
		".CNTLAN_15.": <input type='radio' name='auto_line_breaks' value='1' />";
}
$text .= "<span class='smalltext'>".CNTLAN_22."</span>
	</td></tr>
	<tr>
	<td style='width:20%' class='forumheader3'>".CNTLAN_13."?:</td>
	<td style='width:80%' class='forumheader3'>";


if (!$content_comment) {
	$text .= CNTLAN_14.": <input type='radio' name='content_comment' value='1' />
		".CNTLAN_15.": <input type='radio' name='content_comment' value='0' checked='checked' />";
} else {
	$text .= CNTLAN_14.": <input type='radio' name='content_comment' value='1' checked='checked' />
		".CNTLAN_15.": <input type='radio' name='content_comment' value='0' />";
}


$text .= "
	</td></tr>


	<tr>
	<td class='forumheader3'>".CNTLAN_28.":&nbsp;&nbsp;</td><td class='forumheader3'>". ($content_pe_icon ? CNTLAN_29.": <input type='radio' name='add_icons' value='1' checked='checked' />".CNTLAN_30.": <input type='radio' name='add_icons' value='0' />" : CNTLAN_29.": <input type='radio' name='add_icons' value='1' />".CNTLAN_30.": <input type='radio' name='add_icons' value='0' checked='checked' />")."
	</td>
	</tr>

	";

$text .= "
	<tr>
	<td style='width:20%' class='forumheader3'>".CNTLAN_19.":</td>
	<td style='width:80%' class='forumheader3'>".r_userclass("c_class", $content_class)."
	</td>
	</tr>
	<tr style='vertical-align:top'>
	<td colspan='2'  style='text-align:center' class='forumheader'>";


if ($action == "edit") {
	$text .= "<input class='button' type='submit' name='update' value='".CNTLAN_16."' />
		<input type='hidden' name='content_id' value='$content_id' />";
} else {
	$text .= "<input class='button' type='submit' name='submit' value='".CNTLAN_17."' />";
}

$text .= "</td>
	</tr>
	</table>
	</form>
	</div>";


$ns->tablerender(CNTLAN_18, $text);

echo "<script type=\"text/javascript\">
	function confirm_(content_id){
	return  confirm(\"".$tp->toJS(CNTLAN_27)."\");
	}
	</script>";

require_once("footer.php");
?>