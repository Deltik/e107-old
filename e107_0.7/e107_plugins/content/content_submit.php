<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /admin/review.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
|		$Source: /cvs_backup/e107_0.7/e107_plugins/content/content_submit.php,v $
|		$Revision: 1.6 $
|		$Date: 2005-05-03 15:02:39 $
|		$Author: lisa_ $
+---------------------------------------------------------------+
*/

require_once("../../class2.php");
require_once(e_HANDLER."form_handler.php");
require_once(e_HANDLER."userclass_class.php");
$rs = new form;
require_once(e_PLUGIN."content/handlers/content_class.php");
$aa = new content;
require_once(e_PLUGIN."content/handlers/content_db_class.php");
$adb = new contentdb;
require_once(e_PLUGIN."content/handlers/content_form_class.php");
$aform = new contentform;
global $tp;

$eplug_css = "../../".$PLUGINS_DIRECTORY."content/content_css.css";

$lan_file = e_PLUGIN.'content/languages/'.e_LANGUAGE.'/lan_content.php';
include_once(file_exists($lan_file) ? $lan_file : e_PLUGIN.'content/languages/English/lan_content.php');

if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	if(!empty($tmp[0])) { $type = $tmp[0]; }
	if(!empty($tmp[1])) { $type_id = $tmp[1]; }
	if(!empty($tmp[2])) { $action = $tmp[2]; }
	if(!empty($tmp[3])) { $sub_action = $tmp[3]; }
	if(!empty($tmp[4])) { $id = $tmp[4]; }
	if(!empty($tmp[5])) { $id2 = $tmp[5]; }
	unset($tmp);
}
if(!isset($type)){
	define("e_PAGETITLE", CONTENT_ADMIN_SUBMIT_LAN_6);
}elseif($type == "type"){
	define("e_PAGETITLE", CONTENT_ADMIN_SUBMIT_LAN_7);
}

require_once(HEADERF);

if(IsSet($_POST['create_content'])){
        if($_POST['content_text'] && $_POST['content_heading'] && $_POST['parent'] != "none" && $_POST['content_author_name'] != "" && $_POST['content_author_email'] != ""){
				$adb -> dbContentCreate("submit");
        }else{
                $message = CONTENT_ADMIN_SUBMIT_LAN_4;
				$content_heading = $_POST['content_heading'];
				$content_subheading = $_POST['content_subheading'];
				$content_summary = $_POST['content_summary'];
				$content_text = $_POST['content_text'];
				$content_icon = $_POST['content_icon'];
				$content_file = $_POST['content_file'];
				$content_comment = $_POST['content_comment'];
				$content_rate = $_POST['content_rate'];
				$content_pe = $_POST['content_pe'];
				$content_class = $_POST['content_class'];
				$ne_day = $_POST['ne_day'];
				$ne_month = $_POST['ne_month'];
				$ne_year = $_POST['ne_year'];
				$end_day = $_POST['end_day'];
				$end_month = $_POST['end_month'];
				$end_year = $_POST['end_year'];
				$custom["content_custom_score"] = $_POST['content_score'];
				$custom["content_custom_meta"] = $_POST['content_meta'];
				for($i=0;$i<$content_pref["content_submit_custom_number_{$type_id}"];$i++){
					$keystring = $_POST["content_custom_key_{$i}"];
					$custom["content_custom_{$keystring}"] = $_POST["content_custom_value_{$i}"];
				}
        }
}

if($type == "s"){
		$message = CONTENT_ADMIN_SUBMIT_LAN_2."<br /><br />".CONTENT_ADMIN_SUBMIT_LAN_5;
		$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
		require_once(FOOTERF);
		exit;
}
if($type == "d"){
		$message = CONTENT_ADMIN_SUBMIT_LAN_3."<br /><br />".CONTENT_ADMIN_SUBMIT_LAN_5;
		$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
		require_once(FOOTERF);
		exit;
}

if(IsSet($message)){
        $ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

if(!isset($type)){
		if(!$sql -> db_Select($plugintable, "content_id, content_heading, content_subheading, content_icon, content_pref as prefvalue", "content_parent = '0' AND content_class IN (".USERCLASS_LIST.") ORDER BY content_heading")){
			$text .= "<div style='text-align:center;'>".CONTENT_ADMIN_SUBMIT_LAN_0."</div>";
		}else{
			if(!$CONTENT_SUBMIT_TYPE_TABLE){
				require_once(e_PLUGIN."content/templates/content_submit_type_template.php");
			}
			$sql2 = "";
			$content_submit_table_string = "";
			$count = "0";
			while($row = $sql -> db_Fetch()){
				if(!is_object($sql2)){ $sql2 = new db; }

				$content_pref = unserialize(stripslashes($row['prefvalue']));
				$content_pref["content_cat_icon_path_large_{$content_id}"] = ($content_pref["content_cat_icon_path_large_{$row['content_id']}"] ? $content_pref["content_cat_icon_path_large_{$content_id}"] : "{e_PLUGIN}content/images/cat/48/" );
				$content_pref["content_cat_icon_path_small_{$content_id}"] = ($content_pref["content_cat_icon_path_small_{$row['content_id']}"] ? $content_pref["content_cat_icon_path_small_{$content_id}"] : "{e_PLUGIN}content/images/cat/16/" );
				$content_cat_icon_path_large = $aa -> parseContentPathVars($content_pref["content_cat_icon_path_large_{$row['content_id']}"]);
				$content_cat_icon_path_small = $aa -> parseContentPathVars($content_pref["content_cat_icon_path_small_{$row['content_id']}"]);
				$content_icon_path = $aa -> parseContentPathVars($content_pref["content_icon_path_{$row['content_id']}"]);

				if($content_pref["content_submit_{$row['content_id']}"] && check_class($content_pref["content_submit_class_{$row['content_id']}"])){
					$CONTENT_SUBMIT_TYPE_TABLE_HEADING = "<a href='".e_SELF."?type.".$row['content_id']."'>".$row['content_heading']."</a>";
					$CONTENT_SUBMIT_TYPE_TABLE_SUBHEADING = ($row['content_subheading'] ? $row['content_subheading'] : "");
					$CONTENT_SUBMIT_TYPE_TABLE_ICON = $aa -> getIcon("catlarge", $row['content_icon'], $content_cat_icon_path_large, "type.".$row['content_id'], "", $content_pref["content_blank_caticon_{$row['content_id']}"]);
					$content_submit_type_table_string .= preg_replace("/\{(.*?)\}/e", '$\1', $CONTENT_SUBMIT_TYPE_TABLE);
					$count = $count + 1;
				}
			}
			if($count == "0"){
				$text .= "<div style='text-align:center;'>".CONTENT_ADMIN_SUBMIT_LAN_0."</div>";
			}else{
				$content_submit_type_table_start = preg_replace("/\{(.*?)\}/e", '$\1', $CONTENT_SUBMIT_TYPE_TABLE_START);
				$content_submit_type_table_end = preg_replace("/\{(.*?)\}/e", '$\1', $CONTENT_SUBMIT_TYPE_TABLE_END);
				$text = $content_submit_type_table_start.$content_submit_type_table_string.$content_submit_type_table_end;
			}
		}
		$caption = CONTENT_ADMIN_SUBMIT_LAN_1;
		$ns -> tablerender($caption, $text);
}

if($type=="type" && is_numeric($type_id) && !isset($action)){

		//check if valid categories exist for this main parent
		$sql2 = new db;
		$contenttotal = $sql2 -> db_Count($plugintable, "(*)", "WHERE (content_parent = '0' AND content_id = '".$type_id."' || LEFT(content_parent,".(strlen($type_id)+2).") = '0.".$type_id."') AND content_refer != 'sa' ".$datequery." AND content_class IN (".USERCLASS_LIST.")" );
		if($contenttotal == "0"){
			header("location:".e_SELF); exit;
		}else{
			$aform -> show_content_create("submit");
		}
}

require_once(FOOTERF);

?>
