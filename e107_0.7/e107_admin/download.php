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
|     $Source: /cvs_backup/e107_0.7/e107_admin/download.php,v $
|     $Revision: 1.67 $
|     $Date: 2005-10-17 23:18:13 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if (!getperms("R")) {
	header("location:".e_BASE."index.php");
	exit;
}

require_once(e_HANDLER."calendar/calendar_class.php");
$cal = new DHTML_Calendar(true);
function headerjs()
{
	global $cal;
	return $cal->load_files();
}

$e_sub_cat = 'download';

require_once(e_HANDLER."form_handler.php");
require_once(e_HANDLER."userclass_class.php");
require_once(e_HANDLER."file_class.php");

$fl = new e_file;

// -------- Presets. ------------
require_once(e_HANDLER."preset_class.php");
$pst = new e_preset;
$pst->form = array("myform","dlform"); // form id of the form that will have it's values saved.
$pst->page = array("download.php?create","download.php?cat"); // display preset options on which page(s).
$pst->id = array("admin_downloads","admin_dl_cat");
// -------------------------------

$download = new download;
require_once("auth.php");
$pst->save_preset();  // unique name(s) for the presets - comma separated.

 /*
One form example (no arrays needed)
$pst->form = "myform"; // form id of the form that will have it's values saved.
$pst->page = "download.php?create"; // display preset options on which page.
$pst->save_preset("admin_downloads");  // unique name for the preset
*/

$rs = new form;
if (e_QUERY) {
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	$from = ($tmp[3] ? $tmp[3] : 0);
	unset($tmp);
}

if(isset($_POST['delete']))
{
	$tmp = array_keys($_POST['delete']);
	list($delete, $del_id) = explode("_", $tmp[0]);
	unset($_POST['searchquery']);
}

$from = ($from ? $from : 0);
$amount = 50;


if($file_array = $fl->get_files(e_DOWNLOAD, "","standard",2)){
		sort($file_array);
}

if ($sql->db_Select("rbinary")){
	while ($row = $sql->db_Fetch())	{
		extract($row);
		$file_array[] = "Binary ".$binary_id."/".$binary_name;
	}
}

$reject = array('$.','$..','/','CVS','thumbs.db','*._$', 'index', 'null*');

if($image_array = $fl->get_files(e_FILE."downloadimages/", "",$reject,1)){
	sort($image_array);
}

if($thumb_array = $fl->get_files(e_FILE."downloadthumbs/", "",$reject,1)){
	sort($thumb_array);
}


if (isset($_POST['add_category'])) {
	$download->create_category($sub_action, $id);
}

if (isset($_POST['submit_download'])) {
	$download->submit_download($sub_action, $id);
	$action = "main";
	unset($sub_action, $id);
}


if(isset($_POST['update_catorder'])){
 	foreach($_POST['catorder'] as $key=>$order){
		if($_POST['catorder'][$key]){
			$sql -> db_Update("download_category", "download_category_order='$order' WHERE download_category_id='$key' ");
	  	}
	}
   	$ns->tablerender("", "<div style='text-align:center'><b>".LAN_UPDATED."</b></div>");
}

if (isset($_POST['updateoptions']))
{
	$pref['download_php'] = $_POST['download_php'];
	$pref['download_view'] = $_POST['download_view'];
	$pref['download_sort'] = $_POST['download_sort'];
	$pref['download_order'] = $_POST['download_order'];
	$pref['agree_flag'] = $_POST['agree_flag'];
	$pref['agree_text'] = $tp->toDB($_POST['agree_text']);
	$pref['download_denied'] = $tp->toDB($_POST['download_denied']);
	save_prefs();
	$message = DOWLAN_65;
}

if(isset($_POST['addlimit']))
{
	if($sql->db_Select('generic','gen_id',"gen_type = 'download_limit' AND gen_datestamp = {$_POST['newlimit_class']}"))
	{
		$message = DOWLAN_116;
	}
	else
	{
		if($sql->db_Insert('generic',"0, 'download_limit', '{$_POST['newlimit_class']}', '{$_POST['new_bw_num']}', '{$_POST['new_bw_days']}', '{$_POST['new_count_num']}', '{$_POST['new_count_days']}'"))
		{
			$message = DOWLAN_117;
		}
		else
		{
			$message = DOWLAN_118;
		}
	}
}

if(isset($_POST['updatelimits']))
{

	if($pref['download_limits'] != $_POST['download_limits'])
	{
		$pref['download_limits'] = ($_POST['download_limits'] == 'on') ? 1 : 0;
		save_prefs();
		$message .= DOWLAN_126."<br />";
	}
	foreach(array_keys($_POST['count_num']) as $id)
	{
		if($_POST['count_num'][$id] == "" && $_POST['count_days'][$id] == "" && $_POST['bw_num'][$id] == "" && $_POST['bw_days'][$id] == "")
		{
			//All entries empty - Remove record
			if($sql->db_Delete('generic',"gen_id = {$id}"))
			{
				$message .= $id." - ".DOWLAN_119."<br />";
			}
			else
			{
				$message .= $id." - ".DOWLAN_120."<br />";
			}
		}
		else
		{
			$sql->db_Update('generic',"gen_user_id = '{$_POST['bw_num'][$id]}', gen_ip = '{$_POST['bw_days'][$id]}', gen_intdata = '{$_POST['count_num'][$id]}', gen_chardata = '{$_POST['count_days'][$id]}' WHERE gen_id = {$id}");
			$message .= $id." - ".DOWLAN_121."<br />";
		}
	}
}

if(isset($_POST['submit_mirror']))
{
	$download->submit_mirror($sub_action, $id);
}

if($action == "mirror")
{
	$download -> show_existing_mirrors();
}


if ($action == "dlm")
{
	$action = "create";
	$id = $sub_action;
	$sub_action = "dlm";
}

if ($action == "create") {
	$download->create_download($sub_action, $id);
}

if ($delete == 'category') {
	if ($sql->db_Delete("download_category", "download_category_id='$del_id' ")) {
		$sql->db_Delete("download_category", "download_category_parent='{$del_id}' ");
		$download->show_message(DOWLAN_49." #".$del_id." ".DOWLAN_36);
	}
}

if ($action == "cat") {
	$download->show_categories($sub_action, $id);
}

if ($delete == 'main') {
	if ($sql->db_Delete("download", "download_id='$del_id' ")) {
		$download->show_message(DOWLAN_35." #".$del_id." ".DOWLAN_36);
	}
	unset($sub_action, $id);
}


if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

if (!e_QUERY || $action == "main") {
	$download->show_existing_items($action, $sub_action, $id, $from, $amount);
}


if ($action == "opt") {
	global $pref, $ns;
	$agree_flag = $pref['agree_flag'];
	$agree_text = $pref['agree_text'];
	$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?".e_QUERY."'>\n
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
		<td style='width:70%' class='forumheader3'>".DOWLAN_69."</td>
		<td class='forumheader3' style='width:30%;text-align:left'>";
	$c = $pref['download_php'] ? " checked = 'checked' " : "";
	$text .= "<input type='checkbox' name='download_php' value='1' {$c} /> <span class='smalltext'>".DOWLAN_70."</span></td>
		</tr>
		<tr>
		<td style='width:70%' class='forumheader3'>
		".DOWLAN_55."
		</td>
		<td class='forumheader3' style='width:30%;text-align:left'>
		<select name='download_view' class='tbox'>". ($pref['download_view'] == 5 ? "<option selected='selected'>5</option>" : "<option>5</option>"). ($pref['download_view'] == 10 ? "<option selected='selected'>10</option>" : "<option>10</option>"). ($pref['download_view'] == 15 ? "<option selected='selected'>15</option>" : "<option>15</option>"). ($pref['download_view'] == 20 ? "<option selected='selected'>20</option>" : "<option>20</option>"). ($pref['download_view'] == 50 ? "<option selected='selected'>50</option>" : "<option>50</option>")."
		</select>
		</td>
		</tr>

		<tr><td style='width:70%' class='forumheader3'>
		".DOWLAN_56."
		</td>
		<td class='forumheader3' style='width:30%;text-align:left'>

		<select name='download_order' class='tbox'>";
		$order_options = array("download_id"=>"Id No.","download_datestamp"=>LAN_DATE,"download_requested"=>ADLAN_24,"download_name"=>DOWLAN_59,"download_author"=>DOWLAN_60);
		foreach($order_options as $value=>$label){
			$select = ($pref['download_order'] == $value) ? "selected='selected'" : "";
			$text .= "<option value='$value' $select >$label</option>\n";
		}

		$text .= "</select>
		</td>
		</tr>
		<tr><td style='width:70%' class='forumheader3'>
		".LAN_ORDER."
		</td>
		<td class='forumheader3' style='width:30%;text-align:left'>
		<select name='download_sort' class='tbox'>". ($pref['download_sort'] == "ASC" ? "<option value='ASC' selected='selected'>".DOWLAN_62."</option>" : "<option value='ASC'>".DOWLAN_62."</option>"). ($pref['download_sort'] == "DESC" ? "<option value='DESC' selected='selected'>".DOWLAN_63."</option>" : "<option value='DESC'>".DOWLAN_63."</option>")."
		</select>
		</td>
		</tr>

		<tr>
		<td style='width:70%' class='forumheader3'>".DOWLAN_100."</td>
		<td class='forumheader3' style='width:30%;text-align:left'>". ($agree_flag ? "<input type='checkbox' name='agree_flag' value='1' checked='checked' />" : "<input type='checkbox' name='agree_flag' value='1' />")."</td>
		</tr>

		<tr><td style='width:70%' class='forumheader3'>
		".DOWLAN_101."
		</td>
		<td class='forumheader3' style='width:30%;text-align:left'>
		<textarea class='tbox' name='agree_text' cols='59' rows='3'>$agree_text</textarea>
		</td>
		</tr>

		<tr><td style='width:70%' class='forumheader3'>
		".DOWLAN_146."
		</td>
		<td class='forumheader3' style='width:30%;text-align:left'>
		<textarea class='tbox' name='download_denied' cols='59' rows='3'>".$pref['download_denied']."</textarea>
		</td>
		</tr>

		<tr style='vertical-align:top'>
		<td colspan='2'  style='text-align:center' class='forumheader'>
		<input class='button' type='submit' name='updateoptions' value='".DOWLAN_64."' />
		</td>
		</tr>

		</table>
		</form>
		</div>";
	$ns->tablerender(DOWLAN_54, $text);
}

if($action == 'limits')
{
	if($sql->db_Select('userclass_classes','userclass_id, userclass_name'))
	{
		$classList = $sql->db_getList();
	}
	if($sql->db_Select("generic", "gen_id as limit_id, gen_datestamp as limit_classnum, gen_user_id as limit_bw_num, gen_ip as limit_bw_days, gen_intdata as limit_count_num, gen_chardata as limit_count_days", "gen_type = 'download_limit'"))
	{
		while($row = $sql->db_Fetch())
		{
			$limitList[$row['limit_classnum']] = $row;
		}
	}
	$txt = "
		<form method='post' action='".e_SELF."?".e_QUERY."'>
		<table style='width:100%'>
		<tr>
			<td colspan='4' class='forumheader3' style='text-align:left'>
		";
		if($pref['download_limits'] == 1)
		{
			$chk = "checked = 'checked'";
		}
		else
		{
			$chk = "";
		}

		$txt .= "
			<input type='checkbox' name='download_limits' {$chk} /> ".DOWLAN_125."
			</td>
		</tr>
		<tr>
			<td class='fcaption'>".DOWLAN_67."</td>
			<td class='fcaption'>".DOWLAN_113."</td>
			<td class='fcaption'>".DOWLAN_107."</td>
			<td class='fcaption'>".DOWLAN_108."</td>
		</tr>
	";

	foreach($limitList as $row)
	{
		$txt .= "
		<tr>
		<td class='forumheader3'>".$row['limit_id']."</td>
		<td class='forumheader3'>".r_userclass_name($row['limit_classnum'])."</td>
		<td class='forumheader'>
			<input type='text' class='tbox' size='5' name='count_num[{$row['limit_id']}]' value='{$row['limit_count_num']}' /> ".DOWLAN_109."
			<input type='text' class='tbox' size='5' name='count_days[{$row['limit_id']}]' value='{$row['limit_count_days']}' /> ".DOWLAN_110."
		</td>
		<td class='forumheader'>
			<input type='text' class='tbox' size='5' name='bw_num[{$row['limit_id']}]' value='{$row['limit_bw_num']}' /> ".DOWLAN_111." ".DOWLAN_109."
			<input type='text' class='tbox' size='5' name='bw_days[{$row['limit_id']}]' value='{$row['limit_bw_days']}' /> ".DOWLAN_110."
		</td>
		</tr>
		";
	}

	$txt .= "
	<tr>
	<td colspan='4' style='text-align:center'>
	<input type='submit' class='button' name='updatelimits' value='".DOWLAN_115."' />
	</td>
	</tr>
	<tr>
	<td colspan='4'><br /><br /></td>
	</tr>
	<tr>
	<td colspan='2' class='forumheader3'>".r_userclass("newlimit_class", 0, "off", "guest, member, admin, classes, language")."</td>
	<td class='forumheader'>
		<input type='text' class='tbox' size='5' name='new_count_num' value='' /> ".DOWLAN_109."
		<input type='text' class='tbox' size='5' name='new_count_days' value='' /> ".DOWLAN_110."
	</td>
	<td class='forumheader'>
		<input type='text' class='tbox' size='5' name='new_bw_num' value='' /> ".DOWLAN_111." ".DOWLAN_109."
		<input type='text' class='tbox' size='5' name='new_bw_days' value='' /> ".DOWLAN_110."
	</td>
	</tr>
	<tr>
	<td colspan='4' style='text-align:center'>
	<input type='submit' class='button' name='addlimit' value='".DOWLAN_114."' />
	</td>
	</tr>
	";

	$txt .= "</table></form>";

	$ns->tablerender(DOWLAN_112, $txt);
	require_once(e_ADMIN.'footer.php');
	exit;
}

//$download->show_options($action);

require_once("footer.php");
exit;

class download {

	function show_existing_items($action, $sub_action, $id, $from, $amount) {
		global $sql, $rs, $ns, $tp, $mySQLdefaultdb,$pref;
		$text = "<div style='text-align:center'><div style='padding : 1px; ".ADMIN_WIDTH."; margin-left: auto; margin-right: auto;'>";
        $sortorder = ($pref['download_order']) ? $pref['download_order'] : "download_datestamp";
		if(isset($_POST['searchdisp'])){
			$pref['admin_download_disp'] = implode("|",$_POST['searchdisp']);
			save_prefs();
		}

		if(!$pref['admin_download_disp']){
			$search_display = array("download_name","download_class");
		}else{
            $search_display = explode("|",$pref['admin_download_disp']);
		}

         $query = "SELECT d.*, dc.* FROM #download AS d	LEFT JOIN #download_category AS dc ON dc. download_category_id  = d.download_category";

		if (isset($_POST['searchquery']) && $_POST['searchquery'] != "") {
			$query .= " WHERE  download_url REGEXP('".$_POST['searchquery']."') OR download_author REGEXP('".$_POST['searchquery']."') OR download_description  REGEXP('".$_POST['searchquery']."') ";
            foreach($search_display as $disp){
		  		$query .= " OR $disp REGEXP('".$_POST['searchquery']."') ";
			}
            $query .= " ORDER BY ".$sortorder." DESC";
		} else {
			$query .= " ORDER BY ".($sub_action ? $sub_action : $sortorder)." ".($id ? $id : "DESC")."  LIMIT $from, $amount";
		}

      	if ($sql->db_Select_gen($query)) {
			$text .= $rs->form_open("post", e_SELF."?".e_QUERY, "myform")."
				<table class='fborder' style='width:99%'>
				<tr>
				<td style='width:5%' class='fcaption'>ID</td>
				";

// Search Display Column header.----------
			foreach($search_display as $disp){
				if($disp == "download_name"){
					$text .= "<td class='fcaption'><a href='".e_SELF."?main.download_name.".($id == "desc" ? "asc" : "desc").".$from'>".DOWLAN_27."</a></td>";
				}else{
					$repl = array("download_","_");
					$text .= "<td class='fcaption'><a href='".e_SELF."?main.$disp.".($id == "desc" ? "asc" : "desc").".$from'>".ucwords(str_replace($repl," ",$disp))."</a></td>";
				}
			}

// ------------------------------
			$text .="
				<td style='width:10%' class='fcaption'>".LAN_OPTIONS."</td>
				</tr>";
			while ($row = $sql->db_Fetch()) {
				extract($row);
				$text .= "<tr>
					<td style='width:5%;vertical-align:top' class='forumheader3'>$download_id</td>";

// Display Chosen options -------------------------------------

		foreach($search_display as $disp){
			$text .= "<td class='forumheader3' style='vertical-align:top'>";

        	if($disp == "download_name"){
        		$text .= "<a href='".e_BASE."download.php?view.$download_id'>$download_name</a>";
			}elseif($disp == "download_category"){
				$text .= $row['download_category_name']."&nbsp;";
        	}elseif($disp == "download_datestamp"){
				$text .= ($row[$disp]) ? strftime($pref['shortdate'],$row[$disp])."&nbsp;" : "&nbsp";
			}elseif($disp == "download_class" || $disp == "download_visible"){
				$text .= r_userclass_name($row[$disp])."&nbsp;";
        	}elseif($disp == "download_filesize"){
				$text .= ($row[$disp]) ? round(($row[$disp] / 1000))." Kb&nbsp;" : "&nbsp";
			}elseif($disp == "download_thumb"){
				$text .= ($row[$disp]) ? "<img src='".e_FILE."downloadthumbs/".$row[$disp]."' alt='' />" : "";
        	}elseif($disp == "download_image"){
				$text .= "<a rel='external' href='".e_FILE."downloadimages/".$row[$disp]."' >".$row[$disp]."</a>&nbsp;";
			}elseif($disp == "download_description"){
				$text .= $tp->toHTML($row[$disp],TRUE)."&nbsp;";
			}else{
				$text .= $row[$disp]."&nbsp;";
        	}

			$text .= "</td>";
		}
// -------------------------------------------------------------

			$text .= "
					<td style='width:20%;vertical-align:top; text-align:center' class='forumheader3'>
					<a href='".e_SELF."?create.edit.{$download_id}'>".ADMIN_EDIT_ICON."</a>
					<input type='image' title='".LAN_DELETE."' name='delete[main_{$download_id}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_33." [ID: $download_id ]")."') \" />
					</td>
					</tr>";
			}
			$text .= "</table></form>";
		} else {
			$text .= "<div style='text-align:center'>".DOWLAN_6."</div>";
		}
		$text .= "</div>";

// Next-Previous. ==========================

		$downloads = $sql->db_Count("download");
		if ($downloads > $amount && !$_POST['searchquery']) {
			$parms = "{$downloads},{$amount},{$from},".e_SELF."?".(e_QUERY ? "$action.$sub_action.$id." : "main.download_id.desc.")."[FROM]";
			$text .= "<br />".LAN_GOPAGE." ".$tp->parseTemplate("{NEXTPREV={$parms}}");
		}


// Search  & display options etc. =========================.

		$text .= "<br /><form method='post' action='".e_SELF."'>\n<p>\n<input class='tbox' type='text' name='searchquery' size='20' value='' maxlength='50' />\n<input class='button' type='submit' name='searchsubmit' value='".DOWLAN_51."' />\n</p>";

		$text .= "<div style='cursor:pointer' onclick=\"expandit('sdisp')\">".LAN_DISPLAYOPT."</div>";
		$text .= "<div id='sdisp' style='padding-top:4px;display:none;text-align:center;margin-left:auto;margin-right:auto'>
		<table class='forumheader3' style='width:95%'><tr>";
		$fields = mysql_list_fields($mySQLdefaultdb, MPREFIX."download");
		$columns = mysql_num_fields($fields);
		for ($i = 0; $i < $columns; $i++) {
			$fname[] = mysql_field_name($fields, $i);
		}
        $m = 0;
		$replacechar = array("download_","_");
		foreach($fname as $fcol){
        $checked = (in_array($fcol,$search_display)) ? "checked='checked'" : "";
			$text .= "<td style='text-align:left; padding:0px'>";
			$text .= "<input type='checkbox' name='searchdisp[]' value='".$fcol."' $checked />".str_replace($replacechar," ",$fcol) . "</td>\n";
			$m++;
			if($m == 5){
				$text .= "</tr><tr>";
				$m = 0;
			 }
        }

		$text .= "</table></div>
		</form>\n
		</div>";
// ======================

		$ns->tablerender(DOWLAN_7, $text);
	}

	function show_options($action) {

		if ($action == "") {
			$action = "main";
		}
		$var['main']['text'] = DOWLAN_29;
		$var['main']['link'] = e_SELF;

		$var['create']['text'] = DOWLAN_30;
		$var['create']['link'] = e_SELF."?create";

		$var['cat']['text'] = DOWLAN_31;
		$var['cat']['link'] = e_SELF."?cat";
		$var['cat']['perm'] = "Q";

		$var['opt']['text'] = LAN_OPTIONS;
		$var['opt']['link'] = e_SELF."?opt";

		$var['limits']['text'] = DOWLAN_112;
		$var['limits']['link'] = e_SELF."?limits";

		$var['mirror']['text'] = DOWLAN_128;
		$var['mirror']['link'] = e_SELF."?mirror";

		show_admin_menu(DOWLAN_32, $action, $var);

	}

	function create_download($sub_action, $id) {
		global $cal, $sql, $rs, $ns, $file_array, $image_array, $thumb_array,$pst;
		$download_status[0] = DOWLAN_122;
		$download_status[1] = DOWLAN_123;
		$download_status[2] = DOWLAN_124;
		$preset = $pst->read_preset("admin_downloads");  // read preset values into array
		extract($preset);

		if (!$sql->db_Select("download_category")) {
			$ns->tablerender(ADLAN_24, "<div style='text-align:center'>".DOWLAN_5."</div>");
			return;
		}
		$download_active = 1;
		if ($sub_action == "edit" && !$_POST['submit']) {
			if ($sql->db_Select("download", "*", "download_id='$id' ")) {
				$row = $sql->db_Fetch();
				extract($row);

				if($download_mirror)
				{

					$mirrorTArray = explode(chr(1), $download_mirror);
					$mirrorArray = array();

					$count=0;
					foreach($mirrorTArray as $mirror)
					{
						if($mirror)
						{
							list($mid, $murl) = explode(",", $mirror);
							$mirrorArray[$count]['id'] = $mid;
							$mirrorArray[$count]['url'] = $murl;
							$count++;
						}
					}
				}
			}
		}

		if ($sub_action == "dlm" && !$_POST['submit']) {
			if ($sql->db_Select("upload", "*", "upload_id='$id' ")) {
				$row = $sql->db_Fetch();
				extract($row);
				$download_category = $upload_category;
				$download_name = $upload_name.($upload_version ? " v" . $upload_version : "");
				$download_url = $upload_file;
				$download_author_email = $upload_email;
				$download_author_website = $upload_website;
				$download_description = $upload_description;
				$download_image = $upload_ss;
				$download_filesize = $upload_filesize;
				$image_array[] = $upload_ss;
				$download_author = substr($upload_poster, (strpos($upload_poster, ".")+1));
			}
		}


		$text = "
			<div style='text-align:center'>
			<form method='post' action='".e_SELF."?".e_QUERY."' id='myform'>
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_11."</td>
			<td style='width:80%' class='forumheader3'>";

		$sql->db_Select("download_category", "*", "download_category_parent !=0");
		$text .= "<select name='download_category' class='tbox'>\n";
		while ($row = $sql->db_Fetch()) {
			extract($row);
			if ($download_category_id == $download_category) {
				$text .= "<option value='$download_category_id' selected='selected'>".$download_category_name."</option>\n";
			} else {
				$text .= "<option value='$download_category_id'>".$download_category_name."</option>\n";
			}
		}
		$text .= "</select>
			</td>
			</tr>

			<tr>
			<td style='width:20%; vertical-align:top' class='forumheader3'><span style='text-decoration:underline'>".DOWLAN_12."</span>:</td>
			<td style='width:80%' class='forumheader3'>
			<input class='tbox' type='text' name='download_name' size='60' value='$download_name' maxlength='200' />
			</td>
			</tr>

			<tr>
			<td style='width:20%; vertical-align:top' class='forumheader3'><span style='text-decoration:underline;cursor:help' title='".DOWLAN_127."' >".DOWLAN_13."</span>:</td>
			<td style='width:80%' class='forumheader3'><div style='padding-bottom:5px'>".DOWLAN_131."&nbsp;&nbsp;

		   <select name='download_url' class='tbox'>
			<option></option>
			";

		$counter = 0;
		while (isset($file_array[$counter])) {
			$fpath = str_replace(e_DOWNLOAD,"",$file_array[$counter]['path']).$file_array[$counter]['fname'];
			if (stristr($fpath, $download_url) !== FALSE) {
				$selected = " selected='selected'";
				$found = 1;
			} else {
				$selected = "";
			}

			$text .= "<option value='".$fpath."' $selected>".$fpath."</option>\n";
			$counter++;
		}

		if (preg_match("/http:|ftp:/", $download_url)) {
			$download_url_external = $download_url;
		}

		$etext = " - (".DOWLAN_68.")";
		if (file_exists(e_FILE."public/".$download_url)) {
			$etext = "";
		}

		if (!$found && $download_url) {
			$text .= "<option value='".$download_url."' selected='selected'>".$download_url.$etext."</option>\n";
		}

		$text .= "</select></div>
            <span style='padding-top:6px;cursor:pointer;text-decoration:underline' onclick='expandit(this)' title='".DOWLAN_14."'>".DOWLAN_149."</span>
			<div id='use_ext' style='padding-top:6px;display:none'>
           URL:&nbsp;

			<input class='tbox' type='text' name='download_url_external' size='40' value='$download_url_external' maxlength='150' />
			&nbsp;&nbsp;&nbsp;".DOWLAN_66."
			<input class='tbox' type='text' name='download_filesize_external' size='8' value='$download_filesize' maxlength='10' />
           </div>

			</td>
			</tr>

			<tr>
			<td style='width:20%' class='forumheader3'><span title='".DOWLAN_129."' style='cursor:help'>".DOWLAN_128."</span>:</td>
			<td style='width:80%' class='forumheader3'>";

		if(!$sql -> db_Select("download_mirror"))
		{
			$text .= DOWLAN_144."</tr></tr>";
		}
		else
		{

			$text .= DOWLAN_132."<br />
			<div id='mirrorsection'>";

			$mirrorList = $sql -> db_getList();

			$m_count = (count($mirrorArray) ? count($mirrorArray) : 1);

			for($count = 1; $count <= $m_count; $count++){

				$opt = ($count==1) ? "id='mirror'" : "";
				$text .="<span $opt>
				<select name='download_mirror_name[]' class='tbox'>
					<option></option>";

				foreach($mirrorList as $mirror)	{
					extract($mirror);
					$text .= "<option value='$mirror_id'".($mirror_id == $mirrorArray[($count-1)]['id'] ? " selected='selected'" : "").">$mirror_name</option>\n";
				}

				$text .= "</select>
				<input  class='tbox' type='text' name='download_mirror[]' style='width: 75%;' value=\"".$mirrorArray[($count-1)]['url']."\" maxlength='200' />";
				$text .= "</span><br />";
			}

			$text .="</div><input class='button' type='button' name='addoption' value='".DOWLAN_130."' onclick=\"duplicateHTML('mirror','mirrorsection')\" /><br />

			</td>
			</tr>

			<tr>
			<td style='width:20%' class='forumheader3' ><span style='cursor:help' title='if using mirrors, select how they will be displayed'>Mirror display type:</span></td>
			<td style='width:80%' class='forumheader3'>

			<input type='radio' name='download_mirror_type' value='1'".($download_mirror_type ? " checked='checked'" : "")." /> show mirror list, allow user to choose mirror<br />
			<input type='radio' name='download_mirror_type' value='0'".(!$download_mirror_type ? " checked='checked'" : "")." /> use random mirror - no user choice
			</td>
			</tr>";
		}



			$text .= "<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_15.":</td>
			<td style='width:80%' class='forumheader3'>
			<input class='tbox' type='text' name='download_author' size='60' value='$download_author' maxlength='100' />
			</td>
			</tr>

			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_16.":</td>
			<td style='width:80%' class='forumheader3'>
			<input class='tbox' type='text' name='download_author_email' size='60' value='$download_author_email' maxlength='100' />
			</td>
			</tr>

			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_17.":</td>
			<td style='width:80%' class='forumheader3'>
			<input class='tbox' type='text' name='download_author_website' size='60' value='$download_author_website' maxlength='100' />
			</td>
			</tr>

			<tr>
			<td style='width:20%' class='forumheader3'><span style='text-decoration:underline'>".DOWLAN_18."</span>: </td>
			<td style='width:80%' class='forumheader3'>
			<textarea class='tbox' name='download_description' cols='50' rows='5' style='width:90%'>$download_description</textarea>
			</td>
			</tr>

			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_19.":</td>
			<td style='width:80%' class='forumheader3'>
			<select name='download_image' class='tbox'>
			<option value=''></option>";

			foreach($image_array as $img){
				$fpath = str_replace(e_FILE."downloadimages/","",$img['path'].$img['fname']);
            	$sel = ($download_image == $fpath) ? " selected='selected'" : "";
            	$text .= "<option value='".$fpath."' $sel>".$fpath."</option>\n";
			}

		$text .= "
			</select>
			</td>
			</tr>

			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_20.":</td>
			<td style='width:80%' class='forumheader3'>
			<select name='download_thumb' class='tbox'>
			<option></option>
			";

			foreach($thumb_array as $thm){
				$tpath = str_replace(e_FILE."downloadthumbs/","",$thm['path'].$thm['fname']);
            	$sel = ($download_thumb == $tpath) ? " selected='selected'" : "";
            	$text .= "<option value='".$tpath."' $sel>".$tpath."</option>\n";
			}

		$text .= "</select>
			</td>
			</tr>


		<tr>
		<td style='width:20%' class='forumheader3'>".LAN_DATESTAMP.":</td>
		<td style='width:80%' class='forumheader3'>
		";
        if(!$download_datestamp){
        	$download_datestamp = time();
	   	}
		$cal_options['firstDay'] = 0;
		$cal_options['showsTime'] = false;
		$cal_options['showOthers'] = false;
		$cal_options['weekNumbers'] = false;
		$cal_options['ifFormat'] = "%d/%m/%Y %H:%M:%S";
		$cal_options['timeFormat'] = "24";
		$cal_attrib['class'] = "tbox";
		$cal_attrib['size'] = "12";
		$cal_attrib['name'] = "download_datestamp";
		$cal_attrib['value'] = date("d/m/Y H:i:s", $download_datestamp);
		$text .= $cal->make_input_field($cal_options, $cal_attrib);

		$update_checked = ($_POST['update_datestamp']) ? "checked='checked'" : "";
		$text .= "&nbsp;&nbsp;<span><input type='checkbox' value='1' name='update_datestamp' $update_checked />".DOWLAN_148."
		</span>
		</td>
		</tr>



			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_21.":</td>
			<td style='width:80%' class='forumheader3'>
			<select name='download_active' class='tbox'>
			";

			foreach($download_status as $key => $val){
				$sel = ($download_active == $key) ? " selected = 'selected' " : "";
            	$text .= "<option value='{$key}' {$sel}>{$val}</option>\n";
			}
			$text .= "</select>";

		$text .= "</td>
			</tr>


			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_102.":</td>
			<td style='width:80%' class='forumheader3'>";


		if ($download_comment == "0") {
			$text .= LAN_YES.": <input type='radio' name='download_comment' value='1' />
				".LAN_NO.": <input type='radio' name='download_comment' value='0' checked='checked' />";
		} else {
			$text .= LAN_YES.": <input type='radio' name='download_comment' value='1' checked='checked' />
				".LAN_NO.": <input type='radio' name='download_comment' value='0' />";
		}

		$text .= "</td>
			</tr>";


		$text .= "
			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_145.":</td>
			<td style='width:80%' class='forumheader3'>".r_userclass('download_visible', $download_visible, 'off', 'public, nobody, member, admin, classes, language')."</td>
			</tr>


			<tr>
			<td style='width:20%' class='forumheader3'>".DOWLAN_106.":</td>
			<td style='width:80%' class='forumheader3'>".r_userclass('download_class', $download_class, 'off', 'public, nobody, member, admin, classes, language')."</td>
			</tr>
			";

		if ($sub_action == "dlm") {
			$text .= "<tr>
				<td style='width:30%' class='forumheader3'>".DOWLAN_103.":<br /></td>
				<td style='width:70%' class='forumheader3'>
				<input type='checkbox' name='remove_upload' value='1' />
				<input type='hidden' name='remove_id' value='$id' />
				</td></tr>
				";
		}

		$text .= "
			<tr style='vertical-align:top'>
			<td colspan='2' style='text-align:center' class='forumheader'>";


		if ($id && $sub_action == "edit") {
			$text .= "<input class='button' type='submit' name='submit_download' value='".DOWLAN_24."' /> ";
		} else {
			$text .= "<input class='button' type='submit' name='submit_download' value='".DOWLAN_25."' />";
		}

		$text .= "</td>
			</tr>
			</table>
			</form>
			</div>";
		$ns->tablerender(ADLAN_24, $text);
	}

	function show_message($message) {
		global $ns;
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	function submit_download($sub_action, $id) {
		global $tp, $sql, $DOWNLOADS_DIRECTORY, $e_event;

		if($sub_action == 'edit')
		{
			if($_POST['download_url_external'] == '')
			{
				$_POST['download_filesize_external'] = FALSE;
			}
		}

		if ($_POST['download_url_external'] && $_POST['download_url'] == '')
		{
			$durl = $_POST['download_url_external'];
			$filesize = $_POST['download_filesize_external'];
		}
		else
		{
			$durl = $_POST['download_url'];
			if($_POST['download_filesize_external'])
			{
				$filesize = $_POST['download_filesize_external'];
			}
			else
			{
				if (strpos($DOWNLOADS_DIRECTORY, "/") === 0 || strpos($DOWNLOADS_DIRECTORY, ":") >= 1)
				{
					$filesize = filesize($DOWNLOADS_DIRECTORY.$durl);
				}
				else
				{
					$filesize = filesize(e_BASE.$DOWNLOADS_DIRECTORY.$durl);
				}
			}
		}

		if (!$filesize)
		{
			if($sql->db_Select("upload", "upload_filesize", "upload_file='$durl'"))
			{
				$row = $sql->db_Fetch();
				$filesize = $row['upload_filesize'];
			}
		}

		$_POST['download_description'] = $tp->toDB($_POST['download_description']);
		$_POST['download_name'] = $tp->toDB($_POST['download_name']);
		$_POST['download_author'] = $tp->toDB($_POST['download_author']);

		if (preg_match("#(.*?)/(.*?)/(.*?) (.*?):(.*?):(.*?)$#", $_POST['download_datestamp'], $matches)){
			$_POST['download_datestamp'] = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
		}else{
           $_POST['download_datestamp'] = time();
		}

		if($_POST['update_datestamp']){	$_POST['download_datestamp'] = time();	}

		$mirrorStr = "";
		$mirrorReq = "";


	//	echo "<b>Debug</b> ".count($_POST['download_mirror_name'])." <br />"; EXIT;

		if($_POST['download_mirror_name'][0])
		{
			$mirrors = count($_POST['download_mirror_name']);
			for($a=0; $a<$mirrors; $a++)
			{
				$mirror_id = $_POST['download_mirror_name'][$a];
				$mirror_url = $_POST['download_mirror'][$a];
				$mirrorStr .= $mirror_id.",".$mirror_url.",0".chr(1);
			}
		}

		if ($id)
		{

			if($sql->db_Update("download", "download_name='".$_POST['download_name']."', download_url='".$durl."', download_author='".$_POST['download_author']."', download_author_email='".$_POST['download_author_email']."', download_author_website='".$_POST['download_author_website']."', download_description='".$_POST['download_description']."', download_filesize='".$filesize."', download_category='".$_POST['download_category']."', download_active='".$_POST['download_active']."', download_datestamp='".$_POST['download_datestamp']."', download_thumb='".$_POST['download_thumb']."', download_image='".$_POST['download_image']."', download_comment='".$_POST['download_comment']."', download_class = '{$_POST['download_class']}', download_mirror='$mirrorStr', download_mirror_type='".$_POST['download_mirror_type']."' , download_visible='".$_POST['download_visible']."' WHERE download_id=$id")){
				$this->show_message(DOWLAN_2);
			}else{
                $this->show_message(LAN_UPDATED_FAILED);
			}
		} else {

			if ($download_id = $sql->db_Insert("download", "0, '".$_POST['download_name']."', '".$durl."', '".$_POST['download_author']."', '".$_POST['download_author_email']."', '".$_POST['download_author_website']."', '".$_POST['download_description']."', '".$filesize."', '0', '".$_POST['download_category']."', '".$_POST['download_active']."', '".$_POST['download_datestamp']."', '".$_POST['download_thumb']."', '".$_POST['download_image']."', '".$_POST['download_comment']."', '{$_POST['download_class']}', '$mirrorStr', '".$_POST['download_mirror_type']."', '".$_POST['download_visible']."' ")) {

				$dlinfo = array("download_id" => $download_id, "download_name" => $_POST['download_name'], "download_url" => $durl, "download_author" => $_POST['download_author'], "download_author_email" => $_POST['download_author_email'], "download_author_website" => $_POST['download_author_website'], "download_description" => $_POST['download_description'], "download_filesize" => $filesize, "download_category" => $_POST['download_category'], "download_active" => $_POST['download_active'], "download_datestamp" => $time, "download_thumb" => $_POST['download_thumb'], "download_image" => $_POST['download_image'], "download_comment" => $_POST['download_comment'] );
				$e_event->trigger("dlpost", $dlinfo);

				if ($_POST['remove_upload']) {
					$sql->db_Update("upload", "upload_active='1' WHERE upload_id='".$_POST['remove_id']."'");
					$mes = "<br />".$_POST['download_name']." ".DOWLAN_104;
					$mes .= "<br /><br /><a href='".e_ADMIN."upload.php'>".DOWLAN_105."</a>";
				}
				$this->show_message(DOWLAN_1.$mes);
			}
		}
	}

	function show_categories($sub_action, $id) {
		global $sql, $rs, $ns, $sql2, $sql3, $tp, $pst;

		if (!is_object($sql2)) {
			$sql2 = new db;
		}
		if (!is_object($sql3)) {
			$sql3 = new db;
		}
		if (!is_object($sql4)) {
			$sql4 = new db;
		}
		$text = $rs->form_open("post", e_SELF."?".e_QUERY, "myform");
		$text .= "<div style='padding : 1px; ".ADMIN_WIDTH."; height : 200px; overflow : auto; margin-left: auto; margin-right: auto;'>";
		if ($download_total = $sql->db_Select("download_category", "*", "download_category_parent=0 ORDER BY download_category_order")) {

			$text .= "
			<table class='fborder' style='width:99%'>
				<tr>
				<td style='width:5%; text-align:center' class='fcaption'>&nbsp;</td>
				<td style='width:70%; text-align:center' class='fcaption'>".DOWLAN_11."</td>
				<td style='width:5%; text-align:center' class='fcaption'>".DOWLAN_52."</td>
				<td style='width:5%; text-align:center' class='fcaption'>".LAN_ORDER."</td>
				<td style='width:20%; text-align:center' class='fcaption'>".LAN_OPTIONS."</td>
				</tr>";

			while ($row = $sql->db_Fetch()) {
				extract($row);

				if(strstr($download_category_icon, chr(1)))
				{
					list($download_category_icon, $download_category_icon_empty) = explode(chr(1), $download_category_icon);
				}

				$text .= "<tr>
					<td style='width:5%; text-align:center' class='forumheader'>".($download_category_icon ? "<img src='".e_IMAGE."icons/$download_category_icon' style='vertical-align:middle; border:0' alt='' />" : "&nbsp;")."</td>
					<td colspan='2' style='width:70%' class='forumheader'><b>$download_category_name</b></td>
					<td class='forumheader3'>
					 <input class='tbox' type='text' name='catorder[$download_category_id]' value='$download_category_order' size='3' />
					</td>
					<td style='text-align:center' class='forumheader'>
					<a href='".e_SELF."?cat.edit.{$download_category_id}'>".ADMIN_EDIT_ICON."</a>
					<input type='image' title='".LAN_DELETE."' name='delete[category_{$download_category_id}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_34." [ID: $download_category_id ]")."') \"/>
					</td>
					</tr>";

				$parent_id = $download_category_id;
				if ($sql2->db_Select("download_category", "*", "download_category_parent=$parent_id ORDER BY download_category_order")) {
					while ($row = $sql2->db_Fetch()) {
						extract($row);
							if(strstr($download_category_icon, chr(1)))
							{
								list($download_category_icon, $download_category_icon_empty) = explode(chr(1), $download_category_icon);
							}
						$files = $sql4->db_Count("download", "(*)", "WHERE download_category='".$download_category_id."' ");
						$text .= "<tr>
							<td style='width:5%; text-align:center' class='forumheader3'>".($download_category_icon ? "<img src='".e_IMAGE."icons/$download_category_icon' style='vertical-align:middle; border:0' alt='' />" : "&nbsp;")."</td>
							<td style='width:70%' class='forumheader3'>$download_category_name<br /><span class='smalltext'>$download_category_description</span></td>
							<td style='width:5%; text-align:center' class='forumheader3'>$files</td>
							<td class='forumheader3'>
                            <input class='tbox' type='text' name='catorder[$download_category_id]' value='$download_category_order' size='3' />
							</td>
							<td style='width:20%; text-align:center' class='forumheader3'>
							<a href='".e_SELF."?cat.edit.{$download_category_id}'>".ADMIN_EDIT_ICON."</a>
							<input type='image' title='".LAN_DELETE."' name='delete[category_{$download_category_id}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_34." [ID: $download_category_id ]")."') \"/>
							</td>
							</tr>";


						$sub_parent_id = $download_category_id;
						if ($sql3->db_Select("download_category", "*", "download_category_parent=$sub_parent_id ORDER BY download_category_order")) {
							while ($row = $sql3->db_Fetch()) {
								extract($row);
								if(strstr($download_category_icon, chr(1)))
								{
									list($download_category_icon, $download_category_icon_empty) = explode(chr(1), $download_category_icon);
								}
								$files = $sql4->db_Count("download", "(*)", "WHERE download_category='".$download_category_id."' ");
								$text .= "<tr>
									<td style='width:5%; text-align:center' class='forumheader3'>".($download_category_icon ? "<img src='".e_IMAGE."icons/$download_category_icon' style='vertical-align:middle; border:0' alt='' />" : "&nbsp;")."</td>
									<td style='width:70%' class='forumheader3'>&nbsp;&nbsp;&nbsp;&nbsp;".DOWLAN_53.": $download_category_name<br />&nbsp;&nbsp;&nbsp;&nbsp;<span class='smalltext'>$download_category_description</span></td>
									<td style='width:5%; text-align:center' class='forumheader3'>$files</td>
									<td>
									</td>
									<td style='width:20%; text-align:center' class='forumheader3'>
									<a href='".e_SELF."?cat.edit.{$download_category_id}'>".ADMIN_EDIT_ICON."</a>
									<input type='image' title='".LAN_DELETE."' name='delete[category_{$download_category_id}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".$tp->toJS(DOWLAN_34." [ID: $download_category_id ]")."') \"/>
									</td>
									</tr>";
							}
						}
					}
				}
			}
			$text .= "</table></div>";
			$text .= "<div style='text-align:center'>
				<input class='button' type='submit' name='update_catorder' value='".LAN_UPDATE."' />
				</div>";
		} else {
			$text .= "<div style='text-align:center'>".DOWLAN_38."</div>";
		}
		$text .= "</form>";
		$ns->tablerender(DOWLAN_37, $text);

		unset($download_category_id, $download_category_name, $download_category_description, $download_category_parent, $download_category_icon, $download_category_class);

		$handle = opendir(e_IMAGE."icons");
		while ($file = readdir($handle)) {
			if ($file != "." && $file != ".." && $file != "/" && $file != "CVS") {
				$iconlist[] = $file;
			}
		}
		closedir($handle);

		if ($sub_action == "edit" && !$_POST['add_category']) {
			if ($sql->db_Select("download_category", "*", "download_category_id=$id")) {
				$row = $sql->db_Fetch();
				 extract($row);
				$main_category_parent = $download_category_parent;
				if(strstr($download_category_icon, chr(1)))
				{
					list($download_category_icon, $download_category_icon_empty) = explode(chr(1), $download_category_icon);
				}
				else
				{
					$download_category_icon_empty = "";
				}
			}
		}

		$preset = $pst->read_preset("admin_dl_cat");  // read preset values into array
		extract($preset);

		$frm_action = (isset($_POST['add_category'])) ? e_SELF."?cat" : e_SELF."?".e_QUERY;
		$text = "<div style='text-align:center'>
			<form method='post' action='{$frm_action}' id='dlform'>
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td style='width:30%' class='forumheader3'>".DOWLAN_37.": </td>
			<td style='width:70%' class='forumheader3'>";

		if (!$download_cats = $sql->db_Select("download_category")) {
			$text .= "
				<select name='download_category_parent' class='tbox'>
				<option>".DOWLAN_40."</option>
				</select>\n";
		} else {
			$text .= "
				<select name='download_category_parent' class='tbox'>
				<option>".DOWLAN_40."</option>";

			while (list($cat_id, $cat_name, $null, $null, $cat_parent) = $sql->db_Fetch()) {
				$sql2->db_Select("download_category", "download_category_parent", "download_category_id='$cat_parent'", TRUE);
				$row = $sql2->db_Fetch();
				 extract($row);
				if (!$download_category_parent || !$cat_parent) {
					$text .= ($main_category_parent == $cat_id ? "<option value='$cat_id' selected='selected'>".$cat_name."</option>" : "<option value='$cat_id'>".$cat_name."</option>");
				}
			}
			$text .= "</select>";
		}
		$text .= "</td></tr><tr>
			<td style='width:30%' class='forumheader3'>".DOWLAN_12.": </td>
			<td style='width:70%' class='forumheader3'>
			<input class='tbox' type='text' name='download_category_name' size='40' value='$download_category_name' maxlength='100' />
			</td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".DOWLAN_18.": </td>
			<td style='width:70%' class='forumheader3'>
			<textarea class='tbox' name='download_category_description' cols='59' rows='3'>$download_category_description</textarea>
			</td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".DOWLAN_41.": </td>
			<td style='width:70%' class='forumheader3'>
			<input class='tbox' type='text' id='download_category_icon' name='download_category_icon' size='60' value='$download_category_icon' maxlength='100' />

			<br />
			<input class='button' type ='button' style='cursor:hand' size='30' value='".DOWLAN_42."' onclick='expandit(this)' />
			<div id='cat_icn' style='display:none;{head}' >";

		while (list($key, $icon) = each($iconlist)) {
			$text .= "<a href=\"javascript:insertext('$icon','download_category_icon','cat_icn')\"><img src='".e_IMAGE."icons/".$icon."' style='border:0' alt='' /></a> ";
		}

		reset($iconlist);

		$text .= "
			</div></td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".DOWLAN_147.": </td>
			<td style='width:70%' class='forumheader3'>
			<input class='tbox' type='text' id='download_category_icon_empty' name='download_category_icon_empty' size='60' value='$download_category_icon_empty' maxlength='100' />

			<br />
			<input class='button' type ='button' style='cursor:hand' size='30' value='".DOWLAN_42."' onclick='expandit(this)' />
			<div id='cat_icn_empty' style='display:none;{head}' >";

		while (list($key, $icon) = each($iconlist)) {
			$text .= "<a href=\"javascript:insertext('$icon','download_category_icon_empty','cat_icn_empty')\"><img src='".e_IMAGE."icons/".$icon."' style='border:0' alt='' /></a> ";
		}

		$text .= "
			</div></td>
			</tr>



			<tr>
			<td style='width:30%' class='forumheader3'>".DOWLAN_43.":<br /><span class='smalltext'>(".DOWLAN_44.")</span></td>
			<td style='width:70%' class='forumheader3'>".r_userclass("download_category_class", $download_category_class, 'off', 'public, nobody, member, admin, classes, language')."

			</td></tr>";


		$text .= "
			<tr style='vertical-align:top'>
			<td colspan='2' style='text-align:center' class='forumheader'>";
		if ($id && $sub_action == "edit" && !isset($_POST['add_category'])) {
			$text .= "<input class='button' type='submit' name='add_category' value='".DOWLAN_46."' /> ";
		} else {
			$text .= "<input class='button' type='submit' name='add_category' value='".DOWLAN_45."' />";
		}
		$text .= "</td>
			</tr>
			</table>
			</form>
			</div>";
		$ns->tablerender(DOWLAN_39, $text);
	}

	function create_category($sub_action, $id) {
		global $sql, $tp;
		$download_category_name = $tp->toDB($_POST['download_category_name']);
		$download_category_description = $tp->toDB($_POST['download_category_description']);
	  	$download_category_icon = $tp->toDB($_POST['download_category_icon']);

		if(isset($_POST['download_category_icon_empty']) && $_POST['download_category_icon_empty'] != ""){
			$download_category_icon .= trim(chr(1).$tp->toDB($_POST['download_category_icon_empty']));
		}

		if ($id) {
			$sql->db_Update("download_category", "download_category_name='$download_category_name', download_category_description='$download_category_description', download_category_icon ='$download_category_icon', download_category_parent= '".$_POST['download_category_parent']."', download_category_class='".$_POST['download_category_class']."' WHERE download_category_id='$id'");
			$this->show_message(DOWLAN_48);
		} else {
			$sql->db_Insert("download_category", "0, '$download_category_name', '$download_category_description', '$download_category_icon', '".$_POST['download_category_parent']."', '".$_POST['download_category_class']."', 0 ");
			$this->show_message(DOWLAN_47);
		}
		if ($sub_action == "sn") {
			$sql->db_Delete("tmp", "tmp_time='$id' ");
		}
	}



	function show_existing_mirrors()
	{

		global $sql, $ns, $tp, $sub_action, $id, $delete, $del_id;

		if($delete == "mirror")
		{
			$sql -> db_Delete("download_mirror", "mirror_id=".$del_id);
			$this->show_message(DOWLAN_135);
		}


		if(!$sql -> db_Select("download_mirror"))
		{
			$text = "<div style='text-align:center;'>No mirrors defined yet</div>";
		}
		else
		{

			$text = "<div style='text-align:center'>
			<form method='post' action='".e_SELF."?".e_QUERY."'>
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td style='width: 10%; text-align: center;' class='forumheader'>ID</td>
			<td style='width: 30%;' class='forumheader'>".DOWLAN_12."</td>
			<td style='width: 30%;' class='forumheader'>".DOWLAN_136."</td>
			<td style='width: 30%; text-align: center;' class='forumheader'>".LAN_OPTIONS."</td>
			</tr>
			";

			$mirrorList = $sql -> db_getList();

			foreach($mirrorList as $mirror)
			{
				extract($mirror);
				$text .= "

				<tr>
				<td style='width: 10%; text-align: center;' class='forumheader3'>$mirror_id</td>
				<td style='width: 30%;' class='forumheader3'>".$tp -> toHTML($mirror_name)."</td>
				<td style='width: 30%;' class='forumheader3'>".($mirror_image ? "<img src='".e_FILE."downloadimages/".$mirror_image."' alt='' />" : "None")."</td>
				<td style='width: 30%; text-align: center;' class='forumheader3'>
				<a href='".e_SELF."?mirror.edit.{$mirror_id}'>".ADMIN_EDIT_ICON."</a>
				<input type='image' title='".LAN_DELETE."' name='delete[mirror_{$mirror_id}]' src='".ADMIN_DELETE_ICON_PATH."' onclick=\"return jsconfirm('".DOWLAN_137." [ID: $mirror_id ]')\"/>
				</td>
				</tr>
				";
			}
			$text .= "</table></form></div>";

		}

		$ns -> tablerender(DOWLAN_138, $text);

		require_once(e_HANDLER."file_class.php");
		$fl = new e_file;
		$rejecthumb = array('$.','$..','/','CVS','thumbs.db','*._$',"thumb_", 'index', 'null*');
		$imagelist = $fl->get_files(e_FILE."downloadimages/","",$rejecthumb);

		if($sub_action == "edit" && !defined("SUBMITTED"))
		{
			$sql -> db_Select("download_mirror", "*", "mirror_id=".$id);
			$row = $sql -> db_Fetch();
			extract($mirror);
			$edit = TRUE;
		}
		else
		{
			unset($mirror_name, $mirror_url, $mirror_image, $mirror_location, $mirror_description);
			$edit = FALSE;
		}



		$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?".e_QUERY."' id='dataform'>\n
		<table style='".ADMIN_WIDTH."' class='fborder'>

		<tr>
		<td style='width: 30%;' class='forumheader3'>".DOWLAN_12."</td>
		<td style='width: 70%;' class='forumheader3'>
		<input class='tbox' type='text' name='mirror_name' size='60' value='$mirror_name' maxlength='200' />
		</td>
		</tr>

		<tr>
		<td style='width: 30%;' class='forumheader3'>".DOWLAN_139."</td>
		<td style='width: 70%;' class='forumheader3'>
		<input class='tbox' type='text' name='mirror_url' size='70' value='$mirror_url' maxlength='200' />
		</td>
		</tr>

		<tr>
		<td style='width: 30%;' class='forumheader3'>".DOWLAN_136."</td>
		<td style='width: 70%;' class='forumheader3'>
		<input class='tbox' type='text' id='mirror_image' name='mirror_image' size='60' value='$mirror_image' maxlength='200' />


		<br /><input class='button' type ='button' style='cursor:hand' size='30' value='".DOWLAN_42."' onclick='expandit(this)' />
		<div id='imagefile' style='display:none;{head}'>";

		$text .= DOWLAN_140."<br /><br />";

		foreach($imagelist as $file){
			$text .= "<a href=\"javascript:insertext('".$file['fname']."','mirror_image','imagefile')\"><img src='".e_FILE."downloadimages/".$file['fname']."' alt='' /></a> ";
		}

		$text .= "</div>
		</td>
		</tr>

		<tr>
		<td style='width: 30%;' class='forumheader3'>".DOWLAN_141."</td>
		<td style='width: 70%;' class='forumheader3'>
		<input class='tbox' type='text' name='mirror_location' size='60' value='$mirror_location' maxlength='200' />
		</td>
		</tr>

		<tr>
		<td style='width: 30%;' class='forumheader3'>".DOWLAN_18."</td>
		<td style='width: 70%;' class='forumheader3'>
		<textarea class='tbox' name=' mirror_description' cols='70' rows='6'>$mirror_description</textarea>
		</td>
		</tr>

		<tr>
		<td colspan='2' class='forumheader' style='text-align:center;'>
		".($edit ? "<input class='button' type='submit' name='submit_mirror' value='".DOWLAN_142."' /><input type='hidden' name='id' value='$mirror_id' />" : "<input class='button' type='submit' name='submit_mirror' value='".DOWLAN_143."' />")."
		</td>
		</tr>

		</table>
		</form>
		</div>";

		$caption = ($edit ? DOWLAN_142 : DOWLAN_143);

		$ns -> tablerender($caption, $text);
	}

	function submit_mirror()
	{
		global $tp, $sql;
		define("SUBMITTED", TRUE);
		if(isset($_POST['mirror_name']) && isset($_POST['mirror_url']))
		{
			$name = $tp -> toDB($_POST['mirror_name']);
			$url = $tp -> toDB($_POST['mirror_url']);
			$location = $tp -> toDB($_POST['mirror_location']);

			$description = $tp -> toDB($_POST['mirror_description']);

			if (isset($_POST['id'])){
				$message = ($sql -> db_Update("download_mirror", "mirror_name='$name', mirror_url='$url', mirror_image='".$_POST['mirror_image']."', mirror_location='$location', mirror_description='$description' WHERE mirror_id=".$_POST['id'])) ? DOWLAN_133 : LAN_UPDATED_FAILED;
				$this->show_message($message);
			} else {
				$message = ($sql -> db_Insert("download_mirror", "0, '$name', '$url', '".$_POST['mirror_image']."', '$location', '$description', 0")) ? DOWLAN_134 : LAN_CREATED_FAILED;
				$this->show_message($message);
			}
		}
	}

}


function download_adminmenu($parms) {
	global $download;
	global $action;
	$download->show_options($action);
}




?>