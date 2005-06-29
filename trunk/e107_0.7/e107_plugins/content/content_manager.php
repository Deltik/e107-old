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
|		$Source: /cvs_backup/e107_0.7/e107_plugins/content/content_manager.php,v $
|		$Revision: 1.14 $
|		$Date: 2005-06-29 16:38:23 $
|		$Author: lisa_ $
+---------------------------------------------------------------+
*/

require_once("../../class2.php");

$plugindir = e_PLUGIN."content/";
require_once($plugindir."content_shortcodes.php");

global $tp;
require_once(e_HANDLER."userclass_class.php");
require_once(e_HANDLER."form_handler.php");
$rs = new form;
e107_require_once(e_HANDLER.'arraystorage_class.php');
$eArrayStorage = new ArrayData();
require_once(e_HANDLER."file_class.php");
$fl = new e_file;
require_once($plugindir."handlers/content_class.php");
$aa = new content;
require_once($plugindir."handlers/content_db_class.php");
$adb = new contentdb;
require_once($plugindir."handlers/content_form_class.php");
$aform = new contentform;

$lan_file = $plugindir.'languages/'.e_LANGUAGE.'/lan_content_admin.php';
include_once(file_exists($lan_file) ? $lan_file : $plugindir.'languages/English/lan_content_admin.php');

$lan_file = $plugindir.'languages/'.e_LANGUAGE.'/lan_content.php';
include_once(file_exists($lan_file) ? $lan_file : $plugindir.'languages/English/lan_content.php');

$deltest = array_flip($_POST);

if(e_QUERY){
	$qs = explode(".", e_QUERY);
}

// define e_pagetitle
$aa -> setPageTitle();

if(preg_match("#(.*?)_delete_(\d+)#",$deltest[$tp->toJS("delete")],$matches)){
	$delete = $matches[1];
	$del_id = $matches[2];
}

// ##### DB ---------------------------------------------------------------------------------------

require_once(HEADERF);

//include js
function headerjs(){
	global $tp, $plugindir, $qs;

	if( ($qs[0] == "content" && $qs[1] == "create" && is_numeric($qs[2])) || ($qs[0] == "content" && $qs[1] == "edit" && is_numeric($qs[2])) ){
		$e_wysiwyg			= "content_text";
		if($pref['wysiwyg']){
			$pref['allow_html']	= "1";
			require_once(e_HANDLER."tiny_mce/wysiwyg.php");
			echo wysiwyg($e_wysiwyg);
		}
	}
	echo "<script type='text/javascript' src='".e_FILE."popup.js'></script>\n";
}


if(isset($_POST['create_content'])){
	if($_POST['content_text'] && $_POST['content_heading'] && $_POST['parent'] != "none"){
		$adb -> dbContentCreate("contentmanager");
	}else{
		$message = CONTENT_ADMIN_ITEM_LAN_0;
	}
}

if(isset($_POST['update_content'])){
	if($_POST['content_text'] && $_POST['content_heading'] && $_POST['parent'] != "none"){
		$adb -> dbContentUpdate("contentmanager");
	}else{
		$message = CONTENT_ADMIN_ITEM_LAN_0;
	}
}

if($delete == 'content' && is_numeric($del_id)){
	if($sql -> db_Delete($plugintable, "content_id='$del_id' ")){
		$message = CONTENT_ADMIN_ITEM_LAN_3;
		$e107cache->clear("content");
	}
}

if(isset($message)){
	$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

if(!e_QUERY){
	if(USERID){
		$aform -> show_contentmanager("edit", USERID, USERNAME);
		require_once(FOOTERF);
		exit;
	}else{
		header("location:".$plugindir."content.php"); exit;
	}
}else{

	if($qs[0] == "c"){
		$message = CONTENT_ADMIN_ITEM_LAN_1."<br /><br />".CONTENT_ADMIN_ITEM_LAN_55;
		$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
		require_once(FOOTERF);
		exit;

	}elseif($qs[0] == "u"){
		$message = CONTENT_ADMIN_ITEM_LAN_2."<br /><br />".CONTENT_ADMIN_ITEM_LAN_55;
		$ns -> tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
		require_once(FOOTERF);
		exit;

	//show list of items in this category
	}elseif($qs[0] == "content" && is_numeric($qs[1])){
		$aform -> show_manage_content("contentmanager", USERID, USERNAME);

	//create new item
	}elseif($qs[0] == "content" && $qs[1] == "create" && is_numeric($qs[2])){
		$aform -> show_create_content("contentmanager", USERID, USERNAME);

	//edit item
	}elseif($qs[0] == "content" && $qs[1] == "edit" && is_numeric($qs[2])){
		$aform -> show_create_content("contentmanager", USERID, USERNAME);

	}else{
		header("location:".e_SELF); exit;
	}
}


require_once(FOOTERF);



?>