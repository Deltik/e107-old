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
|     $Source: /cvs_backup/e107_0.7/e107_admin/custommenu.php,v $
|     $Revision: 1.21 $
|     $Date: 2005-04-02 18:29:13 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

require_once("../class2.php");
if (!getperms("2")){
	header("location:".e_BASE."index.php");
	exit;
}

$e_sub_cat = 'custom';
$e_wysiwyg = "menu_text";

require_once("auth.php");
require_once(e_HANDLER."ren_help.php");
require_once(e_HANDLER."userclass_class.php");
$custpage_lang = ($sql->mySQLlanguage) ? $sql->mySQLlanguage : $pref['sitelanguage'];
unset($message);

// Create link
if (isset($_POST['mainmenu_link']) && $_POST['type_content'] == 2 && !$_POST['preview'])
{
	if (!$sql->db_Select("links", "link_id", "link_name='".$_POST['menu_name']."'"))
	{
		$menu_name = $tp -> toDB($_POST['menu_name']);
		$menu_link = $PLUGINS_DIRECTORY."custompages/".$_POST['menu_name'].".php";
		$sql->db_Insert("links", "0, '$menu_name', '$menu_link', '', '', 1, 0, 0, 0, ".$_POST['link_class']);
		$message = CUSLAN_28."<br /><br />";
	} else {
		$message = CUSLAN_27."<br /><br />";
	}
}

if ((isset($_POST['add_menu']) || isset($_POST['update_menu'])) && $_POST['type_content'] == 1){

	if (!$_POST['menu_name'] || !$_POST['menu_caption'] || !$_POST['menu_text']){
		$message .= CUSLAN_1;
	} else {
		if($pref['wysiwyg']){
			$_POST['menu_text'] = str_replace($IMAGES_DIRECTORY,"../../".$IMAGES_DIRECTORY,$_POST['menu_text']);
		}

		$data = chr(60)."?php\n". chr(47)."*\n+---------------------------------------------------------------+\n|        e107 website system\n|        ".e_PLUGIN.$_POST['menu_name']."/custom_".$_POST['menu_name'].".php\n|\n|        �Steve Dunstan 2001-2006\n|        http://e107.org\n|        jalist@e107.org\n|\n|        Released under the terms and conditions of the\n|        GNU General Public License (http://gnu.org).\n+---------------------------------------------------------------+\n\nThis file has been generated by ".e_ADMIN."custommenu.php.\n\n*". chr(47)."\n\n". chr(36)."caption = <<<CAPTION\n".$_POST['menu_caption']."\nCAPTION;\n". chr(36)."text = <<<MENU\n".$_POST['menu_text']."\nMENU;\n".

		"global ".chr(36)."tp;\n". chr(36)."caption = ".chr(36)."tp->toHTML(".chr(36)."caption, TRUE);\n". chr(36)."text = ".chr(36)."tp->toHTML(".chr(36)."text, TRUE,\"parse_sc\");\n". chr(36)."ns->tablerender(".chr(36)."caption, ".chr(36)."text);\n?".chr(62);
		$data = chr(60)."?php\n". chr(47)."*\n+---------------------------------------------------------------+\n|        e107 website system\n|        ".e_PLUGIN.$_POST['menu_name']."/custom_".$_POST['menu_name'].".php\n|\n|        �Steve Dunstan 2001-2002\n|        http://e107.org\n|        jalist@e107.org\n|\n|        Released under the terms and conditions of the\n|        GNU General Public License (http://gnu.org).\n+---------------------------------------------------------------+\n\nThis file has been generated by ".e_ADMIN."custommenu.php.\n\n*". chr(47)."\n\n".
		chr(36)."caption = <<<CAPTION\n".$_POST['menu_caption']."\nCAPTION;\n".
		chr(36)."content['$custpage_lang'] = <<<MENU\n".$_POST['menu_text']."\nMENU;\n".
		"global ".chr(36)."tp;\n". chr(36)."caption = ".chr(36)."tp->toHTML(".chr(36)."caption, TRUE);\n".
        chr(36)."lang = (USERLAN ? USERLAN : ".chr(36)."pref['sitelanguage']);\n".
		chr(36)."text = ".chr(36)."tp->toHTML(".chr(36)."content[".chr(36)."lang], TRUE, 'parse_sc');\n".
		chr(36)."ns->tablerender(".chr(36)."caption, ".chr(36)."text);\n
		?".chr(62);

		$_POST['menu_caption'] = $tp->toHTML($_POST['menu_caption']);
		$_POST['menu_text'] = $tp->toHTML($_POST['menu_text']);

		$fp = @fopen(e_PLUGIN."custom/".$_POST['menu_name'].".php", "w");
		if (!@fwrite($fp, $data)) {
			$message .= CUSLAN_2.e_PLUGIN.CUSLAN_3;
		} else {
			fclose($fp);
			$message .= (isset($_POST['update_menu']) ? CUSLAN_4 : CUSLAN_5);
			unset($_POST['menu_name'], $_POST['menu_caption'], $_POST['menu_text'], $_POST['edit'], $_POST['type_content']);
		}
	}
}

if ((isset($_POST['add_menu']) || isset($_POST['update_menu'])) && $_POST['type_content'] == 2){
	if (!$_POST['menu_name'] || !$_POST['menu_caption'] || !$_POST['menu_text']) {
		$message .= CUSLAN_1;
	} else {
		$_POST['menu_text'] = str_replace($IMAGES_DIRECTORY,"../../".$IMAGES_DIRECTORY,$_POST['menu_text']);
		$data =
chr(60)."?php\n". chr(47)."*\n+---------------------------------------------------------------+\n|        e107 website system\n|        ".e_PLUGIN."/custompages/".$_POST['menu_name'].".php\n|\n|        �e107 Dev Team (Lolo Irie) 2004\n|        http://e107.org\n|        \n|\n|        Released under the terms and conditions of the\n|        GNU General Public License (http://gnu.org).\n+---------------------------------------------------------------+\n\nThis file has been generated by ".e_ADMIN."custommenu.php.\n\n*". chr(47)."\n
require_once(\"../../class2.php\");
require_once(HEADERF);\n
if(!check_class(".$_POST['link_class'].")){
exit;
}\n"
.chr(36)."caption['".$custpage_lang."'] = <<<CAPTION\n".$_POST['menu_caption']."\nCAPTION;\n".
chr(36)."content['".$custpage_lang."'] = <<<TEXT\n".$_POST['menu_text']."\nTEXT;\n\n".
"global ".chr(36)."tp;\n".
chr(36)."lang = (USERLAN ? USERLAN : ".chr(36)."pref['sitelanguage']);\n".
chr(36)."caption = ".chr(36)."tp->toHTML(".chr(36)."caption[".chr(36)."lang], TRUE, 'parse_sc');\n".
chr(36)."text = ".chr(36)."tp->toHTML(".chr(36)."content[".chr(36)."lang], TRUE, 'parse_sc');\n".
chr(36)."ns->tablerender(".chr(36)."caption, ".chr(36)."text);
require_once(FOOTERF);
?".chr(62);

		$_POST['menu_caption'] = $tp->toHTML($_POST['menu_caption']);
		$_POST['menu_text'] = $tp->toHTML($_POST['menu_text']);

		$fp = @fopen(e_PLUGIN."custompages/".$_POST['menu_name'].".php", "w");
		if (!@fwrite($fp, $data)){
			$message .= CUSLAN_20.e_PLUGIN.CUSLAN_21;
		} else {
			fclose($fp);
			$message .= (isset($_POST['update_menu']) ? CUSLAN_4 : CUSLAN_24." ".SITEURL.$PLUGINS_DIRECTORY."custompages/".$_POST['menu_name'].".php");
			unset($_POST['menu_name'], $_POST['menu_caption'], $_POST['menu_text'], $_POST['edit'], $_POST['type_content']);
		}
	}
}

if (isset($_POST['preview'])){
	$menu_caption = $tp->post_toHTML($_POST['menu_caption']);
	$_POST['menu_text'] = str_replace($IMAGES_DIRECTORY,"../".$IMAGES_DIRECTORY,$_POST['menu_text']);
	$menu_text = $tp->toHTML($tp->post_toHTML($_POST['menu_text'], FALSE), TRUE, 'parse_sc');
	echo "
		<div style='text-align:center'>
		<table style='width:200px'>
		<tr>
		<td>";
	$ns->tablerender($menu_caption, $menu_text);
	echo "</td></tr></table></div><br /><br />";
	$_POST['menu_caption'] = $tp->post_toForm($_POST['menu_caption']);
	$_POST['menu_text'] = $tp->post_toForm($_POST['menu_text']);
	$_POST['menu_text'] = str_replace("../".$IMAGES_DIRECTORY,$IMAGES_DIRECTORY,$_POST['menu_text']);

	$_POST['menu_text'] = str_replace("<br />", "\n", $_POST['menu_text']);

 // Read Menu Information.
} elseif (isset($_POST['edit'])){
	$menu = e_PLUGIN."custom/".$_POST['existing'];

	/*
	if ($fp = @fopen($menu, "r")) {
		$buffer = str_replace("\n", "", fread($fp, filesize($menu)));
		fclose($fp);
	*/


	if($fileArray = file($menu)){

		$data = implode("\n", $fileArray);

		preg_match("/<MENU(.*?)MENU;/si", $data, $match);
		$_POST['menu_text'] = $tp -> toFORM($match[1]);

		preg_match("/<CAPTION(.*?)CAPTION;/si", $data, $match);
		$_POST['menu_caption'] = $tp -> toFORM($match[1]);
		if($pref['wysiwyg']){
			$_POST['menu_text'] = str_replace("../../".$IMAGES_DIRECTORY,SITEURL.$IMAGES_DIRECTORY,$_POST['menu_text']);
		}
		$_POST['menu_text'] = str_replace(array("\n", "<br />"), "", $_POST['menu_text']);
		$_POST['menu_name'] = eregi_replace(e_PLUGIN."custom/|.php", "", $menu);
		$_POST['type_content'] = 1;
	} else {
		$message .= CUSLAN_6." '".$_POST['existing']."' ".CUSLAN_7;
	}

 // Read Page Information.

} elseif (isset($_POST['edit2'])){
	$page = e_PLUGIN."custompages/".$_POST['existingpages'];
    if ($fileArray = file($page)){
		$data = implode("\n", $fileArray);
        preg_match("/<TEXT(.*?)TEXT;/si", $data, $match);
		$_POST['menu_text'] = $tp -> toFORM($match[1]);

		preg_match("/<CAPTION(.*?)CAPTION;/si", $data, $match);
		$_POST['menu_caption'] = $tp -> toFORM($match[1]);

		if ($pref['wysiwyg']){
			$_POST['menu_text'] = str_replace("../../".$IMAGES_DIRECTORY,SITEURL.$IMAGES_DIRECTORY,$_POST['menu_text']);
		}
		$_POST['menu_text'] = str_replace("<br />", "", $_POST['menu_text']);
		$_POST['menu_name'] = eregi_replace(e_PLUGIN."custompages/|.php", "", $menu);
		$_POST['type_content'] = 2;
		if (preg_match("#check_class\((.*?)\)#", $buffer, $result2)){
			$linkclass= $result2[1];
		}
//	print_r($result2);
} else {
		$message .= CUSLAN_6." '".$_POST['existing']."' ".CUSLAN_7;
	}
}

if (isset($message)){
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

require_once(e_HANDLER.'file_class.php');
$fi = new e_file;

$text = "
	<div style='text-align:center'>
	<form method='post' action='".e_SELF."' id='dataform'>
	<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
			<td colspan='2' class='fcaption'>".CUSLAN_18."</td></tr>
		<tr>
			<td style='text-align:center' colspan='2' class='forumheader'>
			<span class='defaulttext'>".CUSLAN_8.":</span>
	";

$menu_files = $fi->get_files(e_PLUGIN."custom",'.*php$');

if (count($menu_files)){
	$text .= "<select name='existing' class='tbox'>";
	foreach($menu_files as $f) {
		$text .= "<option>".$f['fname']."</option>";
	}
	$text .= "</select>\n<input class='button' type='submit' name='edit' value='".LAN_EDIT."' /> ";
} else {
	$text .= "<span class='defaulttext'>".CUSLAN_10."</span>";
}

$text .= "
	</td></tr>
	<tr><td style='text-align:center' colspan='2' class='forumheader'>
	<span class='defaulttext'>".CUSLAN_19.":</span>
	";

$page_files = $fi->get_files(e_PLUGIN."custompages",'.*php$');

if (count($page_files)){
	$text .= "<select name='existingpages' class='tbox'>";
	foreach($page_files as $f) {
		$text .= "<option>".$f['fname']."</option>";
	}
	$text .= "</select>\n<input class='button' type='submit' name='edit2' value='".CUSLAN_9."' /> ";
} else {
	$text .= "<span class='defaulttext'>".CUSLAN_10."</span>";
}

$_POST['menu_text'] = str_replace("&nbsp;", "&#38;nbsp;", $_POST['menu_text']);

$text .= "
	</td>
	</tr>";

$text .= "<tr>
	<td style='width:30%' class='forumheader3'>".CUSLAN_11.": </td>
	<td style='width:70%' class='forumheader3'>
	<input class='tbox' type='text' size='25' maxlength='25' name='menu_name' value='".$_POST['menu_name']."' />
	</td>
	</tr>

	<tr>
	<td style='width:30%' class='forumheader3'>".CUSLAN_12.": </td>
	<td style='width:70%' class='forumheader3'>
	<input class='tbox' type='text' size='60' maxlength='250' name='menu_caption' value='".$_POST['menu_caption']."' />
	</td>
	</tr>

	<tr>
	<td style='width:30%' class='forumheader3'>".CUSLAN_13.": </td>
	<td style='width:70%' class='forumheader3'>
	<textarea class='tbox' id='menu_text' name='menu_text' cols='59' rows='15' style='width:95%' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$_POST['menu_text']."</textarea>
	</td>
	</tr>";

if (!$pref['wysiwyg']) {
	$text .= "
		<tr>
		<td class='forumheader3'>&nbsp;</td>
		<td class='forumheader3'>
		<input id='helpb' class='helpbox' type='text' name='helpb' size='100' />
		<br />". display_help()."</td>
		</tr>";
}

$text .= "<tr>
	<td style='width:30%' class='forumheader3'>".CUSLAN_29.": </td>
	<td style='width:70%' class='forumheader3'>
	<input type='radio' name='type_content' value='1' ".($_POST['type_content'] == 1 ? "checked" : "" )." /> ".CUSLAN_22."
	<input type='radio' name='type_content' value='2' ".($_POST['type_content'] == 2 ? "checked" : "" )." /> ".CUSLAN_23."
	</td>
	</tr>";

$text .= "<tr>
	<td style='width:30%' class='forumheader3'>".CUSLAN_25.": </td>
	<td style='width:70%' class='forumheader3'>";
$checked = ($_POST['mainmenu_link'] == 1) ? "checked='checked'" : "";

$text .="<input type='checkbox' name='mainmenu_link' value='1' $checked /> ".LAN_YES.
	"&nbsp;".CUSLAN_31." ".r_userclass("link_class", $linkclass)."
	<br /><a href=\"".e_ADMIN."links.php\" >".CUSLAN_30."</a></b></td>
	</tr>";

$text .= "
	<tr style='vertical-align:top'>
	<td colspan='2'  style='text-align:center' class='forumheader'>";
if (isset($_POST['preview'])) {
	$text .= "<input class='button' type='submit' name='preview' value='".CUSLAN_14."' /> ";
	if (isset($_POST['edit'])) {
		$text .= "<input type='hidden' name='edit' value='".$_POST['edit']."'>";
	}
} else {
	$text .= "<input class='button' type='submit' name='preview' value='".CUSLAN_15."' /> ";
	if (isset($_POST['edit'])) {
		$text .= "<input type='hidden' name='edit' value='".$_POST['edit']."'>";
	}
}

if (isset($_POST['edit']) || isset($_POST['edit2'])) {
	$text .= "<input class='button' type='submit' name='update_menu' value='".CUSLAN_16."' />";
} else {
	$text .= "<input class='button' type='submit' name='add_menu' value='".CUSLAN_17."' />";
}
$text .= "
	</td>
	</tr>
	</table>
	</form>
	</div>";

$ns->tablerender(CUSLAN_18, $text);

require_once("footer.php");
?>