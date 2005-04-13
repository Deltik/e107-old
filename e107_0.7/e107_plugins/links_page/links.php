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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/links_page/links.php,v $
|     $Revision: 1.8 $
|     $Date: 2005-04-13 17:00:07 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
require_once('../../class2.php');
require_once(HEADERF);
if (file_exists(e_PLUGIN."links_page/languages/".e_LANGUAGE."_links.php")) {
	include_once(e_PLUGIN."links_page/languages/".e_LANGUAGE."_links.php");
} else {
	include_once(e_PLUGIN."links_page/languages/English_links.php");
}
	
if (IsSet($_POST['add_link']) && check_class($pref['link_submit_class'])) {
	if ($_POST['link_name'] && $_POST['link_url'] && $_POST['link_description']) {
		$link_name = $tp->toDB($_POST['link_name']);
		$link_url = $tp->toDB($_POST['link_url']);
		$link_description = $tp->toDB($_POST['link_description']);
		$link_button = $tp->toDB($_POST['link_button']);
		$username = (defined('USERNAME')) ? USERNAME : LAN_LINKS_3;
		$submitted_link = $_POST['cat_name']."^".$link_name."^".$link_url."^".$link_description."^".$link_button."^".$username;
		$sql->db_Insert("tmp", "'submitted_link', '".time()."', '$submitted_link' ");
		$ns->tablerender(LAN_99, "<div style='text-align:center'>".LAN_100."</div>");
		$edata_ls = array("link_name" => $link_name, "link_url" => $link_url, "link_description" => $link_description, "link_button" => $link_button, "username" => $username, "submitted_link" => $submitted_link);
		$e_event->trigger("linksub", $edata_ls);
	} else {
		message_handler("ALERT", 5);
	}
}
	
if (e_QUERY == "submit" && check_class($pref['link_submit_class'])) {
	if (!$LINK_SUBMIT_TABLE) {
		if (file_exists(THEME."links_template.php")) {
			require_once(THEME."links_template.php");
		} else {
			require_once(e_BASE.$THEMES_DIRECTORY."templates/links_template.php");
		}
	}
	 
	$link_submit_table_string .= parse_link_submit_table();
	 
	$link_submit_table_start = preg_replace("/\{(.*?)\}/e", '$\1', $LINK_SUBMIT_TABLE_START);
	$link_submit_table_end = preg_replace("/\{(.*?)\}/e", '$\1', $LINK_SUBMIT_TABLE_END);
	$text .= $link_submit_table_start.$link_submit_table_string.$link_submit_table_end;
	 
	$ns->tablerender(LAN_92, $text);
	require_once(FOOTERF);
	exit;
}
	
if (e_QUERY == "" && $pref['linkpage_categories'])
{
	if (!$LINK_MAIN_TABLE) {
		if (file_exists(THEME."links_template.php")) {
			require_once(THEME."links_template.php");
		} else {
			require_once(e_PLUGIN."links_page/links_template.php");
		}
	}
	$caption = LAN_61;
	$total_links = $sql->db_Count("links_page", "(*)");
	$category_total = $sql->db_Select("links_page_cat", "*");
	 
	while ($row = $sql->db_Fetch())
	{
		extract($row);
		$link_main_table_string .= parse_link_main_table($row);
	}
	$link_main_table_start = preg_replace("/\{(.*?)\}/e", '$\1', $LINK_MAIN_TABLE_START);
	 
	$LINK_MAIN_TOTAL = LAN_102." ".($total_links == 1 ? LAN_103 : LAN_104)." ".$total_links." ".($total_links == 1 ? LAN_65 : LAN_66)." ".LAN_105." ".$category_total." ".($category_total == 1 ? LAN_63 : LAN_62);
	$LINK_MAIN_SHOWALL = "<a href='".e_PLUGIN."links_page/links.php?cat.all'>".LAN_67."</a>";
	 
	$link_main_table_end = preg_replace("/\{(.*?)\}/e", '$\1', $LINK_MAIN_TABLE_END);
	 
	$text .= $link_main_table_start.$link_main_table_string.$link_main_table_end;
	 
	$ns->tablerender($caption, $text);
} else {
	$id = e_QUERY;
	$qs = explode(".", e_QUERY);
	if ($qs[0] == "cat") {
		$category = $qs[1];
		unset($id);
	}
	else if ($qs[1] == "cat") {
		$category = $qs[2];
		$id = $qs[0];
	}
	 
	if (isset($id)) {
		$id = $qs[0];
		if ($id) {
			$sql->db_Update("links_page", "link_refer=link_refer+1 WHERE link_id='$id' ");
			$sql->db_Select("links_page", "*", "link_id='$id AND link_class!=255' ");
			$row = $sql->db_Fetch();
			 extract($row);
			header("location:".$link_url);
		}
		$sql->db_Select("links_page_cat", "*");
	}
	 
	if ($category) {
		if ($category == "all") {
			$sql->db_Select("links_page_cat", "*");
		} else {
			$sql->db_Select("links_page_cat", "*", "link_category_id='$category'");
		}
	}
	 
	if (!$LINK_CAT_TABLE) {
		if (file_exists(THEME."links_template.php")) {
			require_once(THEME."links_template.php");
		} else {
			require_once(e_PLUGIN."links_page/links_template.php");
		}
	}
	 
	$sql2 = new db;
	while (list($link_category_id, $link_category_name, $link_category_description) = $sql->db_Fetch()) {
		if ($link_total = $sql2->db_Select("links_page", "*", "link_category ='$link_category_id' ORDER BY link_order ")) {
			unset($text, $link_cat_table_string);
			$link_activ = 0;
			while ($row = $sql2->db_Fetch()) {
				extract($row);
				if (!$link_class || check_class($link_class)) {
					$link_activ++;
					 
					// Caption
					$caption = LAN_86." ".$link_category_name;
					if ($link_category_description != "") {
						$caption .= " <i>[".$link_category_description."]</i>";
					}
					// Number of links displayed
					$caption .= " (<b title='".(ADMIN ? LAN_Links_2 : LAN_Links_1)."' >".$link_activ."</b>".(ADMIN ? "/<b title='".(ADMIN ? LAN_Links_1 : "" )."' >".$link_total."</b>" :
					"").") ";
					$link_cat_table_string .= parse_link_cat_table($row);
				}
			}
			if ($pref['link_submit'] && check_class($pref['link_submit_class'])) {
				$LINK_CAT_SUBMIT = "<a href='".e_SELF."?submit'>".LAN_101."</a>";
			}
			 
			if ($link_activ > 0) {
				$link_cat_table_start = preg_replace("/\{(.*?)\}/e", '$\1', $LINK_CAT_TABLE_START);
				$link_cat_table_end = preg_replace("/\{(.*?)\}/e", '$\1', $LINK_CAT_TABLE_END);
				$text .= $link_cat_table_start.$link_cat_table_string.$link_cat_table_end;
				$ns->tablerender($caption, $text);
			}
			$link_activ = 0;
			$display_links = TRUE;
		}
	}
	if (!$display_links) {
		$ns->tablerender("Links", "<div style='text-align: center'>".LAN_107."</div>");
	}
}
	
require_once(FOOTERF);
	
function parse_link_main_table($row) {
	global $LINK_MAIN_TABLE, $sql;
	extract($row);
	 
	$sql2 = new db;
	$total_links_cat = $sql2->db_Count("links_page", "(*)", " WHERE link_category=$link_category_id ");
	if ($link_category_icon) {
		$LINK_MAIN_ICON = (strstr($link_category_icon, "/") ? "<img src='".e_BASE.$link_category_icon."' alt='' style='vertical-align:middle' /></a>" : "<img src='".e_PLUGIN."links_page/cat_images/".$link_category_icon."' alt='' style='vertical-align:middle' /></a>");
	} else {
		$LINK_MAIN_ICON = "<img src='".THEME."images/".(defined("BULLET") ? BULLET : "bullet2.gif")."' alt='' style='vertical-align:middle;' />";
	}
	$LINK_MAIN_HEADING = (!$total_links_cat ? $link_category_name : "<a href='links.php?cat.".$link_category_id."'>".$link_category_name."</a>");
	$LINK_MAIN_DESC = $link_category_description;
	$LINK_MAIN_NUMBER = $total_links_cat." ".($total_links_cat == 1 ? LAN_65 : LAN_66)." ".LAN_64;
	 
	return(preg_replace("/\{(.*?)\}/e", '$\1', $LINK_MAIN_TABLE));
}
	
function parse_link_submit_table() {
	global $LINK_SUBMIT_TABLE;
	$sql = new db;
	if ($link_cats = $sql->db_Select("links_page_cat")) {
		$LINK_SUBMIT_CAT = "<select name='cat_name' class='tbox'>";
		while (list($cat_id, $cat_name, $cat_description) = $sql->db_Fetch()) {
			if ($cat_name != "Main") {
				$LINK_SUBMIT_CAT .= "<option value='$cat_id'>".$cat_name."</option>\n";
			}
		}
		$LINK_SUBMIT_CAT .= "</select>";
	}
	return(preg_replace("/\{(.*?)\}/e", '$\1', $LINK_SUBMIT_TABLE));
}
	
function parse_link_cat_table($row) {
	global $LINK_CAT_TABLE, $sql, $pref, $tp, $category;
	extract($row);
	 
	// Body
	if (isset($category)) {
		if ($qs[0] == "cat") {
			$link_append = "<a href='".e_SELF."?".$link_id.".cat.{$category}'>";
		} else {
			switch ($link_open) {
				case 1:
				$link_append = "<a href='".e_SELF."?".$link_id.".cat.{$category}' rel='external'>";
				break;
				case 2:
				$link_append = "<a href='".e_SELF."?".$link_id.".cat.{$category}'>";
				break;
				case 3:
				$link_append = "<a href='".e_SELF."?".$link_id.".cat.{$category}'>";
				break;
				case 4:
				$link_append = "<a href=\"javascript:open_window('".e_SELF."?".$link_id.".cat.{$category}')\">";
				break;
				default:
				$link_append = "<a href='".e_SELF."?".$link_id.".cat.{$category}'>";
			}
		}
	} else {
		switch ($link_open) {
			case 1:
			$link_append = "<a href='".e_SELF."?".$link_id."' rel='external'>";
			break;
			case 2:
			$link_append = "<a href='".e_SELF."?".$link_id."'>";
			break;
			case 3:
			$link_append = "<a href='".e_SELF."?".$link_id."'>";
			break;
			case 4:
			$link_append = "<a href=\"javascript:open_window('".e_SELF."?".$link_id."')\">";
			break;
			default:
			$link_append = "<a href='".e_SELF."?".$link_id."'>";
		}
	}
	 
	if ($link_button) {
		if (strpos($link_button, "http://") !== FALSE) {
			$LINK_CAT_BUTTON = $link_append."\n<img style='border:0;' src='".$link_button."' alt='".$link_name."' /></a>";
		} else {
			$LINK_CAT_BUTTON = (strstr($link_button, "/") ? $link_append."\n<img style='border:0;' src='".e_BASE.$link_button."' alt='".$link_name."' /></a>" : $link_append."\n<img style='border:0' src='".e_PLUGIN."links_page/link_images/".$link_button."' alt='".$link_name."' /></a>");
		}
	} else {
		$LINK_CAT_BUTTON = $link_append."\n<img style='border:0; width: 88px; height: 31px;' src='".e_PLUGIN."links_page/images/generic.png' alt='".$link_name."' /></a>";
	}
	$LINK_CAT_APPEND = $link_append;
	$LINK_CAT_NAME = $link_name;
	$LINK_CAT_URL = $link_url;
	$LINK_CAT_DESC = $tp->toHTML($link_description, TRUE);
	$LINK_CAT_REFER = LAN_88." ".$link_refer;
	 
	return(preg_replace("/\{(.*?)\}/e", '$\1', $LINK_CAT_TABLE));
}
?>