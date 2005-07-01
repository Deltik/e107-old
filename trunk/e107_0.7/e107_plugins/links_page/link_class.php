<?php
/*
+ ----------------------------------------------------------------------------+
|    e107 website system
|
|    �Steve Dunstan 2001-2002
|    http://e107.org
|    jalist@e107.org
|
|    Released   under the   terms and   conditions of the
|    GNU    General Public  License (http://gnu.org).
|
|    $Source: /cvs_backup/e107_0.7/e107_plugins/links_page/link_class.php,v $
|    $Revision: 1.5 $
|    $Date: 2005-07-01 08:03:58 $
|    $Author: lisa_ $
+----------------------------------------------------------------------------+
*/


class linkclass {

	function LinkPageDefaultPrefs(){

		$linkspage_pref['link_page_categories'] = "0";
		$linkspage_pref['link_submit'] = "0";
		$linkspage_pref['link_submit_class'] = "0";
		$linkspage_pref['link_open_all'] = "5";			//use individual link open type setting
		$linkspage_pref["link_nextprev"] = "1";
		$linkspage_pref["link_nextprev_number"] = "20";
		$linkspage_pref['link_cat_icon'] = "1";
		$linkspage_pref['link_cat_desc'] = "1";
		$linkspage_pref['link_cat_amount'] = "1";
		$linkspage_pref['link_cat_total'] = "1";
		$linkspage_pref['link_cat_icon_empty'] = "0";
		$linkspage_pref['link_cat_sort'] = "link_category_name";
		$linkspage_pref['link_cat_order'] = "ASC";
		$linkspage_pref['link_cat_resize_value'] = "50";
		$linkspage_pref['link_icon'] = "1";
		$linkspage_pref['link_referal'] = "1";
		$linkspage_pref['link_url'] = "0";
		$linkspage_pref['link_desc'] = "1";
		$linkspage_pref['link_rating'] = "0";
		$linkspage_pref['link_icon_empty'] = "0";
		$linkspage_pref['link_sortorder'] = "0";
		$linkspage_pref['link_sort'] = "link_order";
		$linkspage_pref['link_order'] = "ASC";
		$linkspage_pref['link_resize_value'] = "100";
		$linkspage_pref['link_pagenumber'] = "20";
		$linkspage_pref['link_manager'] = "0";
		$linkspage_pref['link_manager_class'] = "0";
		$linkspage_pref['link_directpost'] = "0";
		$linkspage_pref['link_directdelete'] = "0";

		return $linkspage_pref;
	}

	function getLinksPagePref(){
		global $sql, $eArrayStorage;

		$num_rows = $sql -> db_Select("core", "*", "e107_name='links_page' ");
		if ($num_rows == 0) {
			$linkspage_pref = $this->LinkPageDefaultPrefs();
			$tmp = $eArrayStorage->WriteArray($linkspage_pref);
			$sql -> db_Insert("core", "'links_page', '{$tmp}' ");
			$sql -> db_Select("core", "*", "e107_name='links_page' ");
		}
		$row = $sql -> db_Fetch();
		$linkspage_pref = $eArrayStorage->ReadArray($row['e107_value']);

		return $linkspage_pref;
	}

	function UpdateLinksPagePref($_POST){
		global $sql, $eArrayStorage, $tp;

		$num_rows = $sql -> db_Select("core", "*", "e107_name='links_page' ");
		if ($num_rows == 0) {
			$sql -> db_Insert("core", "'links_page', '{$tmp}' ");
		}else{
			$row = $sql -> db_Fetch();

			//assign new preferences
			foreach($_POST as $k => $v){
				if(preg_match("#^link_#",$k)){
					$linkspage_pref[$k] = $tp->toDB($v, true);
				}
			}

			//create new array of preferences
			$tmp = $eArrayStorage->WriteArray($linkspage_pref);

			$sql -> db_Update("core", "e107_value = '{$tmp}' WHERE e107_name = 'links_page' ");
		}
		return $linkspage_pref;
	}

	function showLinkSort(){
		global $rs, $ns, $link_sort, $link_order;
	
		$sotext = "
		".$rs -> form_open("post", e_SELF."?".e_QUERY, "linksort")."
			".LAN_LINKS_15." 
			".$rs -> form_select_open("link_sort")."
			".$rs -> form_option(LAN_LINKS_4, ($link_sort == "link_name" ? "1" : "0"), "link_name", "")."
			".$rs -> form_option(LAN_LINKS_5, ($link_sort == "link_url" ? "1" : "0"), "link_url", "")."
			".$rs -> form_option(LAN_LINKS_6, ($link_sort == "link_order" ? "1" : "0"), "link_order", "")."
			".$rs -> form_option(LAN_LINKS_7, ($link_sort == "link_refer" ? "1" : "0"), "link_refer", "")."
			".$rs -> form_select_close()."
			".LAN_LINKS_6." 
			".$rs -> form_select_open("link_order")."
			".$rs -> form_option(LAN_LINKS_8, ($link_order == "ASC" ? "1" : "0"), "ASC", "")."
			".$rs -> form_option(LAN_LINKS_9, ($link_order == "DESC" ? "1" : "0"), "DESC", "")."
			".$rs -> form_select_close()."
			<input class='button' style='width:25px' type='submit' name='submit' value='go'>
		".$rs -> form_close();

		return $sotext;
	}

	function show_message($message) {
		global $ns;
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	function uploadLinkIcon($_POST){
		global $ns, $pref;
		$pref['upload_storagetype'] = "1";
		require_once(e_HANDLER."upload_handler.php");
		$pathicon = e_PLUGIN."links_page/link_images/";
		$uploaded = file_upload($pathicon);

		$icon = "";
		if($uploaded) {
			$icon = $uploaded[0]['name'];
			if($_POST['link_resize_value']){
				require_once(e_HANDLER."resize_handler.php");
				resize_image($pathicon.$icon, $pathicon.$icon, $_POST['link_resize_value'], "nocopy");
			}
		}
		$msg = "<div style='font-weight:bold; text-align:center;'>".($icon ? LCLAN_ADMIN_7 : LCLAN_ADMIN_8)."</div>";
		$this -> show_message($msg);
	}

	function uploadCatLinkIcon($_POST){
		global $ns, $pref;
		$pref['upload_storagetype'] = "1";
		require_once(e_HANDLER."upload_handler.php");
		$pathicon = e_PLUGIN."links_page/cat_images/";
		$uploaded = file_upload($pathicon);

		$icon = "";
		if($uploaded) {
			$icon = $uploaded[0]['name'];
			if($_POST['link_cat_resize_value']){
				require_once(e_HANDLER."resize_handler.php");
				resize_image($pathicon.$icon, $pathicon.$icon, $_POST['link_cat_resize_value'], "nocopy");
			}
		}
		$msg = "<div style='font-weight:bold; text-align:center;'>".($icon ? LCLAN_ADMIN_7 : LCLAN_ADMIN_8)."</div>";
		$this -> show_message($msg);
	}

	function dbCategoryCreate($_POST) {
		global $sql;
		$_POST['link_category_name']	= $tp->toDB($_POST['link_category_name'], "admin");
		$link_t							= $sql->db_Count("links_page_cat", "(*)");
		$sql->db_Insert("links_page_cat", " '0', '".$_POST['link_category_name']."', '".$_POST['link_category_description']."', '".$_POST['link_category_icon']."', '".($link_t+1)."', '".$_POST['link_category_class']."', '".time()."' ");
		$this->show_message(LCLAN_ADMIN_4);
	}

	function dbCategoryUpdate($_POST) {
		global $sql;
		$_POST['category_name'] = $tp->toDB($_POST['category_name'], "admin");
		$time = ($_POST['update_datestamp'] ? time() : ($_POST['link_category_datestamp'] != "0" ? $_POST['link_category_datestamp'] : time()) );
		$sql->db_Update("links_page_cat", "link_category_name ='".$_POST['link_category_name']."', link_category_description='".$_POST['link_category_description']."', link_category_icon='".$_POST['link_category_icon']."', link_category_order='".$_POST['link_category_order']."', link_category_class='".$_POST['link_category_class']."', link_category_datestamp='".$time."'	WHERE link_category_id='".$_POST['link_category_id']."'");
		$this->show_message(LCLAN_ADMIN_5);
	}

	function dbOrderUpdate($order) {
		global $sql;
		foreach ($order as $order_id) {
			$tmp = explode(".", $order_id);
		$sql->db_Update("links_page", "link_order=".$tmp[1]." WHERE link_id=".$tmp[0]);
		}
		$this->show_message(LCLAN_ADMIN_9);
	}

	function dbOrderCatUpdate($order) {
		global $sql;
		foreach ($order as $order_id) {
			$tmp = explode(".", $order_id);
			$sql->db_Update("links_page_cat", "link_category_order=".$tmp[1]." WHERE link_category_id=".$tmp[0]);
		}
		$this->show_message(LCLAN_ADMIN_9);
	}

	function dbOrderUpdateInc($inc) {
		global $sql;
		$tmp = explode(".", $inc);
		$linkid = $tmp[0];
		$link_order = $tmp[1];
		$location = $tmp[2];
		if(isset($location)){
			$sql->db_Update("links_page", "link_order=link_order+1 WHERE link_order='".($link_order-1)."' AND link_category='$location'");
			$sql->db_Update("links_page", "link_order=link_order-1 WHERE link_id='$linkid' AND link_category='$location'");
		}else{
			$sql->db_Update("links_page_cat", "link_category_order=link_category_order+1 WHERE link_category_order='".($link_order-1)."' ");
			$sql->db_Update("links_page_cat", "link_category_order=link_category_order-1 WHERE link_category_id='$linkid' ");
		}
	}

	function dbOrderUpdateDec($dec) {
		global $sql;
		$tmp = explode(".", $dec);
		$linkid = $tmp[0];
		$link_order = $tmp[1];
		$location = $tmp[2];
		if(isset($location)){
			$sql->db_Update("links_page", "link_order=link_order-1 WHERE link_order='".($link_order+1)."' AND link_category='$location'");
			$sql->db_Update("links_page", "link_order=link_order+1 WHERE link_id='$linkid' AND link_category='$location'");
		}else{
			$sql->db_Update("links_page_cat", "link_category_order=link_category_order-1 WHERE link_category_order='".($link_order+1)."' ");
			$sql->db_Update("links_page_cat", "link_category_order=link_category_order+1 WHERE link_category_id='$linkid' ");
		}
	}

	function dbLinkCreate($mode='') {
		global $ns, $tp, $qs, $sql, $e107cache, $e_event;

		$link_name			= $tp->toDB($_POST['link_name'], TRUE);
		$link_url			= $tp->toDB($_POST['link_url'], TRUE);
		$link_description	= $tp->toDB($_POST['link_description'], TRUE);
		$link_button		= $tp->toDB($_POST['link_button'], TRUE);
		
		if(isset($mode) && $mode == "submit"){
			if ($_POST['link_name'] && $_POST['link_url'] && $_POST['link_description']) {
				$username			= (defined('USERNAME')) ? USERNAME : LAN_LINKS_3;
				$submitted_link		= $_POST['cat_name']."^".$link_name."^".$link_url."^".$link_description."^".$link_button."^".$username;
				$sql->db_Insert("tmp", "'submitted_link', '".time()."', '$submitted_link' ");
				$ns->tablerender(LAN_LINKS_28, "<div style='text-align:center'>".LAN_LINKS_29."</div>");
				$edata_ls = array("link_name" => $link_name, "link_url" => $link_url, "link_description" => $link_description, "link_button" => $link_button, "username" => $username, "submitted_link" => $submitted_link);
				$e_event->trigger("linksub", $edata_ls);
				} else {
				message_handler("ALERT", 5);
			}
		}else{
			$link_t	= $sql->db_Count("links_page", "(*)", "WHERE link_category='".$_POST['cat_id']."'");
			$time	= ($_POST['update_datestamp'] ? time() : ($_POST['link_datestamp'] != "0" ? $_POST['link_datestamp'] : time()) );

			if (is_numeric($qs[2]) && $qs[1] != "sn") {
				if($qs[1] == "manage"){
					$link_author = USERID;
					$link_class = ($linkspage_pref['link_directpost'] ? $_POST['link_class'] : "255");
				}else{
					$link_author = ($_POST['link_author'] ? $_POST['link_author'] : USERID);
					$link_class = $_POST['link_class'];
				}
				$sql->db_Update("links_page", "link_name='$link_name', link_url='$link_url', link_description='$link_description', link_button= '$link_button',   link_category='".$_POST['cat_id']."', link_open='".$_POST['linkopentype']."', link_class='".$link_class."', link_datestamp='".$time."', link_author='".$link_author."' WHERE link_id='$qs[2]'");
				$e107cache->clear("sitelinks");
				$this->show_message(LCLAN_ADMIN_3);
			} else {
				$sql->db_Insert("links_page", "0, '$link_name', '$link_url', '$link_description', '$link_button', '".$_POST['cat_id']."', '".($link_t+1)."', '0', '".$_POST['linkopentype']."', '".$_POST['link_class']."', '".time()."', '".USERID."' ");
				$e107cache->clear("sitelinks");
				$this->show_message(LCLAN_ADMIN_2);
			}
			if (is_numeric($qs[2]) && $qs[1] == "sn") {
				$sql->db_Delete("tmp", "tmp_time='$qs[2]' ");
			}
		}
	}

	function show_link_create() {
		global $sql, $rs, $qs, $ns, $fl;

		$row['link_category']		= "";
		$row['link_name']			= "";
		$row['link_url']			= "";
		$row['link_description']	= "";
		$row['link_button']			= "";
		$row['link_open']			= "";
		$row['link_class']			= "";
		$link_resize_value			= (isset($linkspage_pref['link_resize_value']) && $linkspage_pref['link_resize_value'] ? $linkspage_pref['link_resize_value'] : "100");

		if ($qs[1] == 'edit' && !$_POST['submit']) {
			if ($sql->db_Select("links_page", "*", "link_id='$qs[2]' ")) {
				$row = $sql->db_Fetch();
			}
		}

		if ($qs[1] == 'sn') {
			if ($sql->db_Select("tmp", "*", "tmp_time='$qs[2]'")) {
				$row = $sql->db_Fetch();
				$submitted					= explode("^", $row['tmp_info']);
				$row['link_category']		= $submitted[0];
				$row['link_name']			= $submitted[1];
				$row['link_url']			= $submitted[2];
				$row['link_description']	= $submitted[3]."\n[i]".LCLAN_ITEM_1." ".$submitted[5]."[/i]";
				$row['link_button']			= $submitted[4];
			}
		}

		if(isset($_POST['uploadlinkicon'])){
			$row['link_category']		= $_POST['cat_id'];
			$row['link_name']			= $_POST['link_name'];
			$row['link_url']			= $_POST['link_url'];
			$row['link_description']	= $_POST['link_description'];
			$row['link_button']			= $_POST['link_button'];
			$row['link_open']			= $_POST['linkopentype'];
			$row['link_class']			= $_POST['link_class'];
			$link_resize_value			= (isset($_POST['link_resize_value']) && $_POST['link_resize_value'] ? $_POST['link_resize_value'] : $link_resize_value);
		}
		$width = (e_PAGE == "admin_linkspage_config.php" ? ADMIN_WIDTH : "width:100%;");
		$text = "
		<div style='text-align:center'>
		".$rs -> form_open("post", e_SELF."?".e_QUERY, "linkform", "", "enctype='multipart/form-data'", "")."
		<table style='".$width."' class='fborder' cellspacing='0' cellpadding='0'>
		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_ITEM_2."</td>
		<td style='width:70%' class='forumheader3'>";

		if (!$link_cats = $sql->db_Select("links_page_cat")) {
			$text .= LCLAN_ITEM_3."<br />";
		} else {
			$text .= $rs -> form_select_open("cat_id");
			while (list($cat_id, $cat_name, $cat_description) = $sql->db_Fetch()) {
				if ( (isset($link_category) && $row['link_category'] == $cat_id) || (isset($row['linkid']) && $cat_id == $row['linkid'] && $action == "add") ) {
					$text .= $rs -> form_option($cat_name, "1", $cat_id, "");
				} else {
					$text .= $rs -> form_option($cat_name, "0", $cat_id, "");
				}
			}
			$text .= $rs -> form_select_close();
		}
		$text .= "
		</td>
		</tr>
		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_ITEM_4."</td>
		<td style='width:70%' class='forumheader3'>
			".$rs -> form_text("link_name", 60, $row['link_name'], 100)."
		</td>
		</tr>
		<tr>
		<td style='width:30%' class='forumheader3'>".LCLAN_ITEM_5."</td>
		<td style='width:70%' class='forumheader3'>
			".$rs -> form_text("link_url", 60, $row['link_url'], 200)."
		</td>
		</tr>
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_ITEM_6."</td>
		<td style='width:70%' class='forumheader3'>
			".$rs -> form_textarea("link_description", '59', '3', $row['link_description'], "", "", "", "", "")."
		</td>
		</tr>
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_ITEM_7."</td>
		<td style='width:70%' class='forumheader3'>";
			if(!FILE_UPLOADS){
				$text .= "<b>".LCLAN_ITEM_9."</b>";
			}else{
				if(!is_writable(e_PLUGIN."links_page/link_images/")){
					$text .= "<b>".LCLAN_ITEM_10." ".e_PLUGIN."links_page/link_images/ ".LCLAN_ITEM_11."</b><br />";
				}
				$text .= "
				<input class='tbox' type='file' name='file_userfile[]'  size='58' /><br />
				".LCLAN_ITEM_8." ".$rs -> form_text("link_resize_value", 3, $link_resize_value, 3)."&nbsp;".LCLAN_ITEM_12."
				".$rs -> form_button("submit", "uploadlinkicon", LCLAN_ITEM_13, "", "", "");
			}
		$text .= "
		</td>
		</tr>";

		$rejectlist = array('$.','$..','/','CVS','thumbs.db','Thumbs.db','*._$', 'index', 'null*', 'blank*');
		$iconpath = e_PLUGIN."links_page/link_images/";
		$iconlist = $fl->get_files($iconpath,"",$rejectlist);

		$text .= "
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_ITEM_14."</td>
		<td style='width:70%; vertical-align:top;' class='forumheader3'>
			<div id='linkbut' style='display:; vertical-align:top;'><table style='text-align:left; width:100%;'><tr><td style='width:20%; padding-right:10px;'>";
			$selectjs	= "size='9'";
			$text		.= $rs -> form_select_open("link_button", $selectjs);
			$js			= " onclick = \"insertext('','link_button',''); document.getElementById('iconview').src='".$iconpath."blank.gif';\" ";
			$text		.= $rs -> form_option(LCLAN_ITEM_34, "1", "", $js );
			foreach($iconlist as $icon){
				$js		= " onclick = \"insertext('".$icon['fname']."','link_button',''); document.getElementById('iconview').src='".$icon['path'].$icon['fname']."';\" ";
				$text	.= $rs -> form_option($icon['fname'], ($icon['fname'] == $row['link_button'] ? "1" : "0"), $icon['fname'], $js );
			}
			$text .= $rs -> form_select_close();
			$text .= "</td><td><img id='iconview' src='".$iconpath."blank.gif' style='width:".$link_resize_value."px; border:0;' /></td></tr></table>";
			$text .= "</div>
		</td>
		</tr>";

		//0=same window, 1=_blank, 2=_parent, 3=_top, 4=miniwindow
		$text .= "
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_ITEM_16."</td>
		<td style='width:70%' class='forumheader3'>
			".$rs -> form_select_open("linkopentype")."
			".$rs -> form_option(LCLAN_ITEM_17, ($row['link_open'] == "0" ? "1" : "0"), "0", "")."
			".$rs -> form_option(LCLAN_ITEM_18, ($row['link_open'] == "1" ? "1" : "0"), "1", "")."
			".$rs -> form_option(LCLAN_ITEM_19, ($row['link_open'] == "4" ? "1" : "0"), "4", "")."
			".$rs -> form_select_close()."
		</td>
		</tr>
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_ITEM_20."</td>
		<td style='width:70%' class='forumheader3'>
			".r_userclass("link_class", $row['link_class'], "off", "public,guest,nobody,member,admin,classes")."
		</td>
		</tr>
		<tr style='vertical-align:top'>
		<td colspan='2' style='text-align:center' class='forumheader'>";
		if ($qs[2] && $qs[1] == "edit") {
			$text .= $rs -> form_hidden("link_datestamp", $row['link_datestamp']);
			$text .= $rs -> form_checkbox("update_datestamp", 1, 0)." ".LCLAN_ITEM_21."<br /><br />";
			$text .= $rs -> form_button("submit", "add_link", LCLAN_ITEM_22, "", "", "").$rs -> form_hidden("link_id", $row['link_id']);
		} else {
			$text .= $rs -> form_button("submit", "add_link", LCLAN_ITEM_23, "", "", "");
		}
		$text .= "</td>
		</tr>
		</table>
		".$rs -> form_close()."
		</div>";
		$ns->tablerender(LCLAN_ITEM_24, $text);
	}

	function show_links() {
		global $sql, $qs, $rs, $ns, $tp;

		if ($sql->db_Select("links_page_cat", "link_category_name", "link_category_id='".$qs[2]."' " )) {
			$row = $sql->db_Fetch();
			$caption = LCLAN_ITEM_2." ".$row['link_category_name'];
		}

		if (!$link_total = $sql->db_Select("links_page", "*", "link_category=".$qs[2]." ORDER BY link_order, link_id ASC")) {
			js_location(e_SELF."?link");
		}else{
			$text = $rs->form_open("post", e_SELF.(e_QUERY ? "?".e_QUERY : ""), "myform_{$row['link_id']}", "", "");
			$text .= "<div style='text-align:center'>
			<table class='fborder' style='".ADMIN_WIDTH."'>
			<tr>
			<td class='fcaption' style='width:5%'>".LCLAN_ITEM_25."</td>
			<td class='fcaption' style='width:65%'>".LCLAN_ITEM_26."</td>
			<td class='fcaption' style='width:10%'>".LCLAN_ITEM_27."</td>
			<td class='fcaption' style='width:10%'>".LCLAN_ITEM_28."</td>
			<td class='fcaption' style='width:10%'>".LCLAN_ITEM_29."</td>
			</tr>";
			while ($row = $sql->db_Fetch()) {
				$linkid = $row['link_id'];
				$img = "";
				if ($row['link_button']) {
					if (strpos($row['link_button'], "http://") !== FALSE) {
						$img = "<img style='border:0;' src='".$row['link_button']."' alt='".$LINK_CAT_NAME."' />";
					} else {
						if(strstr($row['link_button'], "/")){
							$img = "<img style='border:0;' src='".e_BASE.$row['link_button']."' alt='".$LINK_CAT_NAME."' />";
						}else{
							$img = "<img style='border:0' src='".e_PLUGIN."links_page/link_images/".$row['link_button']."' alt='".$LINK_CAT_NAME."' />";
						}
					}
				}
				if($row['link_order'] == "1"){
					$up = "&nbsp;&nbsp;&nbsp;";
				}else{
					$up = "<input type='image' src='".LINK_ICON_ORDER_UP_BASE."' value='".$linkid.".".$row['link_order'].".".$row['link_category']."' name='inc' />";
				}
				if($row['link_order'] == $link_total){
					$down = "&nbsp;&nbsp;&nbsp;";
				}else{
					$down = "<input type='image' src='".LINK_ICON_ORDER_DOWN_BASE."' value='".$linkid.".".$row['link_order'].".".$row['link_category']."' name='dec' />";
				}
				$text .= "
				<tr>
				<td class='forumheader3' style='width:5%; text-align: center; vertical-align: middle'>".$img."</td>
				<td style='width:65%' class='forumheader3'>
					<a href='".e_PLUGIN."links_page/links.php?".$row['link_id']."' rel='external'>".LINK_ICON_LINK."</a> ".$row['link_name']."
				</td>
				<td style='width:10%; text-align:center; white-space: nowrap' class='forumheader3'>
					<a href='".e_SELF."?link.edit.".$linkid."' title='".LCLAN_ITEM_31."'>".LINK_ICON_EDIT."</a>
					<input type='image' title='delete' name='delete[main_{$linkid}]' alt='".LCLAN_ITEM_32."' src='".LINK_ICON_DELETE_BASE."' onclick=\"return jsconfirm('".$tp->toJS(LCLAN_ITEM_33." [ ".$row['link_name']." ]")."')\" />
				</td>
				<td style='width:10%; text-align:center; white-space: nowrap' class='forumheader3'>
					".$up."
					".$down."
				</td>
				<td style='width:10%; text-align:center' class='forumheader3'>
					<select name='link_order[]' class='tbox'>";
					//".$rs -> form_select_open("link_order[]");
					for($a = 1; $a <= $link_total; $a++) {
						$text .= $rs -> form_option($a, ($row['link_order'] == $a ? "1" : "0"), $linkid.".".$a, "");
					}
					$text .= $rs -> form_select_close()."
				</td>
				</tr>";
			}
			$text .= "
			<tr>
			<td class='forumheader' colspan='4'>&nbsp;</td>
			<td class='forumheader' style='width:5%; text-align:center'>
			".$rs->form_button("submit", "update_order", LCLAN_ITEM_30)."
			</td>
			</tr>
			</table></div>
			".$rs->form_close();
		}
		$ns->tablerender($caption, $text);
	}

	function show_cat_create() {
		global $qs, $sql, $rs, $ns, $tp, $fl;

		$row['link_category_name']			= "";
		$row['link_category_description']	= "";
		$row['link_category_icon']			= "";
		$link_cat_resize_value				= (isset($linkspage_pref['link_cat_resize_value']) && $linkspage_pref['link_cat_resize_value'] ? $linkspage_pref['link_cat_resize_value'] : "50");

		if(isset($_POST['uploadcatlinkicon'])){
			$row['link_category_name']			= $_POST['link_category_name'];
			$row['link_category_description']	= $_POST['link_category_description'];
			$row['link_category_icon']			= $_POST['link_category_icon'];
			$link_cat_resize_value				= (isset($_POST['link_cat_resize_value']) && $_POST['link_cat_resize_value'] ? $_POST['link_cat_resize_value'] : $link_cat_resize_value);
		}
		if ($qs[1] == "edit") {
			if ($sql->db_Select("links_page_cat", "*", "link_category_id='$qs[2]' ")) {
				$row = $sql->db_Fetch();
			}
		}
		if(isset($_POST['category_clear'])){
			$row['link_category_name']			= "";
			$row['link_category_description']	= "";
			$row['link_category_icon']			= "";
		}
		$rejectlist = array('$.','$..','/','CVS','thumbs.db','Thumbs.db','*._$', 'index', 'null*');
		$iconlist = $fl->get_files(e_PLUGIN."links_page/cat_images/","",$rejectlist);

		$text = "<div style='text-align:center'>
		".$rs->form_open("post", e_SELF.(e_QUERY ? "?".e_QUERY : ""), "linkform", "", "enctype='multipart/form-data'", "")."
		<table class='fborder' style='".ADMIN_WIDTH."'>
		<tr>
		<td class='forumheader3' style='width:30%'>".LCLAN_CAT_13."</td>
		<td class='forumheader3' style='width:70%'>".$rs->form_text("link_category_name", 50, $row['link_category_name'], 200)."</td>
		</tr>
		<tr>
		<td class='forumheader3' style='width:30%; vertical-align:top;'>".LCLAN_CAT_14."</td>
		<td class='forumheader3' style='width:70%'>".$rs->form_text("link_category_description", 60, $row['link_category_description'], 200)."</td>
		</tr>
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_CAT_15."</td>
		<td style='width:70%' class='forumheader3'>";
			if(!FILE_UPLOADS){
				$text .= "<b>".LCLAN_CAT_17."</b>";
			}else{
				if(!is_writable(e_PLUGIN."links_page/cat_images/")){
					$text .= "<b>".LCLAN_CAT_18." ".e_PLUGIN."links_page/cat_images/ ".LCLAN_CAT_19."</b><br />";
				}
				$text .= "
				<input class='tbox' type='file' name='file_userfile[]'  size='58' /><br />
				".LCLAN_CAT_16." ".$rs -> form_text("link_cat_resize_value", 3, $link_cat_resize_value, 3)."&nbsp;".LCLAN_CAT_20."
				".$rs -> form_button("submit", "uploadcatlinkicon", LCLAN_CAT_21, "", "", "");
			}
		$text .= "
		</td>
		</tr>
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_CAT_22."</td>
		<td style='width:70%' class='forumheader3'>
			".$rs -> form_text("link_category_icon", 60, $row['link_category_icon'], 100)."
			".$rs -> form_button("button", '', LCLAN_CAT_23, "onclick=\"expandit('catico')\"")."
			<div id='catico' style='{head}; display:none'>";
			foreach($iconlist as $icon){
				$text .= "<a href=\"javascript:insertext('".$icon['fname']."','link_category_icon','catico')\"><img src='".$icon['path'].$icon['fname']."' style='border:0' alt='' /></a> ";
			}
			$text .= "</div>
		</td>
		</tr>
		<tr>
		<td style='width:30%; vertical-align:top;' class='forumheader3'>".LCLAN_CAT_24."</td>
		<td style='width:70%' class='forumheader3'>
			".r_userclass("link_category_class", $row['link_category_class'], "off", "public,guest,nobody,member,admin,classes")."
		</td>
		</tr>
		<tr><td colspan='2' style='text-align:center' class='forumheader'>";
		if (is_numeric($qs[2])) {
			$text .= $rs -> form_hidden("link_category_order", $row['link_category_order']);
			$text .= $rs -> form_hidden("link_category_datestamp", $row['link_category_datestamp']);
			$text .= $rs -> form_checkbox("update_datestamp", 1, 0)." ".LCLAN_CAT_25."<br /><br />";
			$text .= $rs -> form_button("submit", "update_category", LCLAN_CAT_26, "", "", "");
			$text .= $rs -> form_button("submit", "category_clear", LCLAN_CAT_27). $rs->form_hidden("link_category_id", $qs[2]);

		} else {
			$text .= $rs -> form_button("submit", "create_category", LCLAN_CAT_28, "", "", "");
		}
		$text .= "</td></tr></table>
		".$rs->form_close()."
		</div>";

		$ns->tablerender(LCLAN_CAT_29, $text);
		unset($row['link_category_name'], $row['link_category_description'], $row['link_category_icon']);
	}

	function show_categories($mode) {
		global $sql, $rs, $ns, $tp, $fl;

		if ($category_total = $sql->db_Select("links_page_cat", "*", "ORDER BY link_category_order", "mode=no_where")) {
			$text = "
			<div style='text-align: center'>
			".$rs->form_open("post", e_SELF.(e_QUERY ? "?".e_QUERY : ""), "", "", "")."
			<table class='fborder' style='".ADMIN_WIDTH."'>
			<tr>
			<td style='width:5%' class='fcaption'>".LCLAN_CAT_1."</td>
			<td class='fcaption'>".LCLAN_CAT_2."</td>
			<td style='width:10%' class='fcaption'>".LCLAN_CAT_3."</td>";
			if($mode == "cat"){
				$text .= "
				<td class='fcaption' style='width:10%'>".LCLAN_CAT_4."</td>
				<td class='fcaption' style='width:10%'>".LCLAN_CAT_5."</td>";
			}
			$text .= "
			</tr>";
			while ($row = $sql->db_Fetch()) {
				$linkcatid = $row['link_category_id'];
				if ($row['link_category_icon']) {
					$img = (strstr($row['link_category_icon'], "/") ? "<img src='".e_BASE.$row['link_category_icon']."' alt='' style='vertical-align:middle' />" : "<img src='".e_PLUGIN."links_page/cat_images/".$row['link_category_icon']."' alt='' style='vertical-align:middle' />");
				} else {
					$img = "&nbsp;";
				}
				$text .= "
				<tr>
				<td style='width:5%; text-align:center' class='forumheader3'>".$img."</td>
				<td class='forumheader3'>
					<a href='".e_PLUGIN."links_page/links.php?cat.".$linkcatid."' rel='external'>".LINK_ICON_LINK."</a>
					".$row['link_category_name']."<br /><span class='smalltext'>".$row['link_category_description']."</span>
				</td>";
				if($mode == "cat"){
					if($row['link_category_order'] == "1"){
						$up = "&nbsp;&nbsp;&nbsp;";
					}else{
						$up = "<input type='image' src='".LINK_ICON_ORDER_UP_BASE."' value='".$linkcatid.".".$row['link_category_order']."' name='inc' />";
					}
					if($row['link_category_order'] == $category_total){
						$down = "&nbsp;&nbsp;&nbsp;";
					}else{
						$down = "<input type='image' src='".LINK_ICON_ORDER_DOWN_BASE."' value='".$linkcatid.".".$row['link_category_order']."' name='dec' />";
					}
					$text .= "
					<td style='width:10%; text-align:center; white-space: nowrap' class='forumheader3'>
						<a href='".e_SELF."?cat.edit.".$linkcatid."' title='".LCLAN_CAT_6."'>".LINK_ICON_EDIT."</a>
						<input type='image' title='delete' name='delete[category_{$linkcatid}]' alt='".LCLAN_CAT_7."' src='".LINK_ICON_DELETE_BASE."' onclick=\"return jsconfirm('".$tp->toJS(LCLAN_CAT_8." [ ".$row['link_category_name']." ]")."')\"/>
					</td>
					<td style='width:10%; text-align:center; white-space: nowrap' class='forumheader3'>
						".$up."
						".$down."
					</td>
					<td style='width:10%; text-align:center' class='forumheader3'>
						<select name='link_category_order[]' class='tbox'>";
						for($a = 1; $a <= $category_total; $a++) {
							$text .= $rs -> form_option($a, ($row['link_category_order'] == $a ? "1" : "0"), $linkcatid.".".$a, "");
						}
						$text .= $rs -> form_select_close()."
					</td>";
				}else{
					$text .= "<td style='width:10%; text-align:center; white-space: nowrap' class='forumheader3'>
					<a href='".e_SELF."?link.view.".$linkcatid."' title='".LCLAN_CAT_9."'>".LINK_ICON_EDIT."</a></td>";
				}
				$text .= "
				</tr>\n";
			}
			if($mode == "cat"){
				$text .= "
				<tr>
				<td class='forumheader' colspan='4'>&nbsp;</td>
				<td class='forumheader' style='width:5%; text-align:center'>
				".$rs->form_button("submit", "update_category_order", LCLAN_CAT_10)."
				</td>
				</tr>";
			}
			$text .= "
			</table>
			".$rs->form_close()."
			</div>";
		} else {
			$text = "<div style='text-align:center'>".LCLAN_CAT_11."</div>";
		}
		$ns->tablerender(LCLAN_CAT_12, $text);
		unset($row['link_category_name'], $row['link_category_description'], $row['link_category_icon']);
	}

	function show_submitted() {
		global $sql, $rs, $qs, $ns, $tp;

		$text = "<div style='padding : 1px; ".ADMIN_WIDTH."; height : 200px; overflow : auto; margin-left: auto; margin-right: auto;'>\n";
		if (!$submitted_total = $sql->db_Select("tmp", "*", "tmp_ip='submitted_link' ")) {
			$text .= "<div style='text-align:center'>".LCLAN_SL_2."</div>";
		}else{
			$text .= "
			".$rs->form_open("post", e_SELF."?sn", "submitted_links")."
			<table class='fborder' style='width:99%'>
			<tr>
			<td style='width:60%' class='fcaption'>".LCLAN_SL_3."</td>
			<td style='width:30%' class='fcaption'>".LCLAN_SL_4."</td>
			<td style='width:10%; white-space:nowrap; text-align:center' class='fcaption'>".LCLAN_SL_5."</td>
			</tr>";
			while ($row = $sql->db_Fetch()) {
				$tmp_time = $row['tmp_time'];
				$submitted = explode("^", $row['tmp_info']);
				if (!strstr($submitted[2], "http")) {
					$submitted[2] = "http://".$submitted[2];
				}
				$text .= "<tr>
				<td style='width:60%' class='forumheader3'><a href='".$submitted[2]."' rel='external'>".$submitted[2]."</a></td>
				<td style='width:30%' class='forumheader3'>".$submitted[5]."</td>
				<td style='width:10%; white-space:nowrap; text-align:center; vertical-align:top' class='forumheader3'>
					<a href='".e_SELF."?link.sn.".$tmp_time."' title='".LCLAN_SL_6."'>".LINK_ICON_EDIT."</a>
					<input type='image' title='delete' name='delete[sn_{$tmp_time}]' alt='".LCLAN_SL_7."' src='".LINK_ICON_DELETE_BASE."' onclick=\"return jsconfirm('".$tp->toJS(LCLAN_SL_8." [ ".$tmp_time." ]")."')\" />
				</td>
				</tr>\n";
			}
			$text .= "</table>".$rs->form_close();
		}
		$text .= "</div>";
		$ns->tablerender(LCLAN_SL_1, $text);
	}

	function show_pref_options() {
		global $linkspage_pref, $ns, $rs, $pref;

		$TOPIC_ROW = "
		<tr>
			<td class='forumheader3' style='width:25%; white-space:nowrap; vertical-align:top;'>{TOPIC_TOPIC}</td>
			<td class='forumheader3' style='vertical-align:top;'>{TOPIC_FIELD}</td>
		</tr>";

		$TOPIC_TITLE_ROW = "<tr><td colspan='2' class='fcaption'>{TOPIC_CAPTION}</td></tr>";
		$TOPIC_ROW_SPACER = "<tr><td style='height:20px;' colspan='2'></td></tr>";

		$text = "
		<div style='text-align:center'>
		".$rs -> form_open("post", e_SELF."?".e_QUERY, "", "", "", "")."
		<table style='".ADMIN_WIDTH."' class='fborder'>";

		$TOPIC_CAPTION = LCLAN_OPT_1;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_TITLE_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_7;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_page_categories", "1", ($linkspage_pref['link_page_categories'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_page_categories", "0", ($linkspage_pref['link_page_categories'] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_8;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_submit", "1", ($linkspage_pref['link_submit'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_submit", "0", ($linkspage_pref['link_submit'] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_9;
		$TOPIC_FIELD = r_userclass("link_submit_class", $linkspage_pref['link_submit_class']);
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		//link_nextprev
		$TOPIC_TOPIC = LCLAN_OPT_10;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_nextprev", "1", ($linkspage_pref["link_nextprev"] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_nextprev", "0", ($linkspage_pref["link_nextprev"] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		//link_nextprev_number
		$TOPIC_TOPIC = LCLAN_OPT_11;
		$TOPIC_FIELD = $rs -> form_select_open("link_nextprev_number");
		for($i=2;$i<52;$i++){
			$TOPIC_FIELD .= $rs -> form_option($i, ($linkspage_pref["link_nextprev_number"] == $i ? "1" : "0"), $i);
			$i++;
		}
		$TOPIC_FIELD .= $rs -> form_select_close();
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$text .= $TOPIC_ROW_SPACER;

		$TOPIC_CAPTION = LCLAN_OPT_52;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_TITLE_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_54;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_manager", "1", ($linkspage_pref['link_manager'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_manager", "0", ($linkspage_pref['link_manager'] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);
		
		$TOPIC_TOPIC = LCLAN_OPT_46;
		$TOPIC_FIELD = r_userclass("link_manager_class", $linkspage_pref['link_manager_class'])."<br />".LCLAN_OPT_47;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_48;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_directpost", "1", ($linkspage_pref['link_directpost'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_directpost", "0", ($linkspage_pref['link_directpost'] ? "0" : "1"), "", "").LCLAN_OPT_4."<br />".LCLAN_OPT_49;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_50;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_directdelete", "1", ($linkspage_pref['link_directdelete'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_directdelete", "0", ($linkspage_pref['link_directdelete'] ? "0" : "1"), "", "").LCLAN_OPT_4."<br />".LCLAN_OPT_51;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$text .= $TOPIC_ROW_SPACER;

		$TOPIC_CAPTION = LCLAN_OPT_12;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_TITLE_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_13;
		$TOPIC_FIELD = "<table style='width:100%;' cellpadding='0' cellspacing='0'><tr><td style='white-space:nowrap; width:20%;'>
		".$rs -> form_checkbox("link_cat_icon", 1, ($linkspage_pref['link_cat_icon'] ? "1" : "0"))." ".LCLAN_OPT_14."<br />
		".$rs -> form_checkbox("link_cat_desc", 1, ($linkspage_pref['link_cat_desc'] ? "1" : "0"))." ".LCLAN_OPT_15."<br />
		".$rs -> form_checkbox("link_cat_amount", 1, ($linkspage_pref['link_cat_amount'] ? "1" : "0"))." ".LCLAN_OPT_16."<br />
		</td><td style='white-space:nowrap;'>
		".$rs -> form_checkbox("link_cat_total", 1, ($linkspage_pref['link_cat_total'] ? "1" : "0"))." ".LCLAN_OPT_19."<br />
		".$rs -> form_checkbox("link_cat_toprefer", 1, ($linkspage_pref['link_cat_toprefer'] ? "1" : "0"))." ".LCLAN_OPT_20."<br />
		".$rs -> form_checkbox("link_cat_toprated", 1, ($linkspage_pref['link_cat_toprated'] ? "1" : "0"))." ".LCLAN_OPT_21."<br />
		</td></tr></table>";
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_22;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_cat_icon_empty", "1", ($linkspage_pref['link_cat_icon_empty'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_cat_icon_empty", "0", ($linkspage_pref['link_cat_icon_empty'] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_23;
		$TOPIC_FIELD = "
		".$rs -> form_select_open("link_cat_sort")."
		".$rs -> form_option(LCLAN_OPT_40, ($linkspage_pref['link_cat_sort'] == "link_category_name" ? "1" : "0"), "link_category_name", "")."
		".$rs -> form_option(LCLAN_OPT_41, ($linkspage_pref['link_cat_sort'] == "link_category_id" ? "1" : "0"), "link_category_id", "")."
		".$rs -> form_select_close();
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_24;
		$TOPIC_FIELD = "
		".$rs -> form_select_open("link_cat_order")."
		".$rs -> form_option(LCLAN_OPT_30, ($linkspage_pref['link_cat_order'] == "ASC" ? "1" : "0"), "ASC", "")."
		".$rs -> form_option(LCLAN_OPT_31, ($linkspage_pref['link_cat_order'] == "DESC" ? "1" : "0"), "DESC", "")."
		".$rs -> form_select_close();
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_25;
		$TOPIC_FIELD = $rs -> form_text("link_cat_resize_value", "3", $linkspage_pref['link_cat_resize_value'], "3", "tbox", "", "", "")." ".LCLAN_OPT_5;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$text .= $TOPIC_ROW_SPACER;

		$TOPIC_CAPTION = LCLAN_OPT_26;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_TITLE_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_13;
		$TOPIC_FIELD = "<table style='width:100%;' cellpadding='0' cellspacing='0'><tr><td style='white-space:nowrap; width:20%;'>
		".$rs -> form_checkbox(LCLAN_OPT_38, 1, ($linkspage_pref['link_icon'] ? "1" : "0"))." ".LCLAN_OPT_14."<br />
		".$rs -> form_checkbox(LCLAN_OPT_37, 1, ($linkspage_pref['link_referal'] ? "1" : "0"))." ".LCLAN_OPT_17."<br />
		</td><td style='white-space:nowrap;'>
		".$rs -> form_checkbox(LCLAN_OPT_35, 1, ($linkspage_pref['link_url'] ? "1" : "0"))." ".LCLAN_OPT_18."<br />
		".$rs -> form_checkbox(LCLAN_OPT_39, 1, ($linkspage_pref['link_desc'] ? "1" : "0"))." ".LCLAN_OPT_15."<br />
		</td></tr></table>";
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_27;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_rating", "1", ($linkspage_pref['link_rating'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_rating", "0", ($linkspage_pref['link_rating'] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_28;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_icon_empty", "1", ($linkspage_pref['link_icon_empty'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_icon_empty", "0", ($linkspage_pref['link_icon_empty'] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_29;
		$TOPIC_FIELD = "
		".$rs -> form_radio("link_sortorder", "1", ($linkspage_pref['link_sortorder'] ? "1" : "0"), "", "").LCLAN_OPT_3."
		".$rs -> form_radio("link_sortorder", "0", ($linkspage_pref['link_sortorder'] ? "0" : "1"), "", "").LCLAN_OPT_4;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_23;
		$TOPIC_FIELD = "
		".$rs -> form_select_open("link_sort")."
		".$rs -> form_option(LCLAN_OPT_34, ($linkspage_pref['link_sort'] == "link_name" ? "1" : "0"), "link_name", "")."
		".$rs -> form_option(LCLAN_OPT_35, ($linkspage_pref['link_sort'] == "link_url" ? "1" : "0"), "link_url", "")."
		".$rs -> form_option(LCLAN_OPT_36, ($linkspage_pref['link_sort'] == "link_order" ? "1" : "0"), "link_order", "")."
		".$rs -> form_option(LCLAN_OPT_37, ($linkspage_pref['link_sort'] == "link_refer" ? "1" : "0"), "link_refer", "")."
		".$rs -> form_option(LCLAN_OPT_53, ($linkspage_pref['link_sort'] == "link_datestamp" ? "1" : "0"), "link_datestamp", "")."
		".$rs -> form_select_close();
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_24;
		$TOPIC_FIELD = "
		".$rs -> form_select_open("link_order")."
		".$rs -> form_option(LCLAN_OPT_30, ($linkspage_pref['link_order'] == "ASC" ? "1" : "0"), "ASC", "")."
		".$rs -> form_option(LCLAN_OPT_31, ($linkspage_pref['link_order'] == "DESC" ? "1" : "0"), "DESC", "")."
		".$rs -> form_select_close();
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		//0=same window, 1=_blank, 2=_parent, 3=_top, 4=miniwindow
		$TOPIC_TOPIC = LCLAN_OPT_32;
		$TOPIC_FIELD = "
		".$rs -> form_select_open("link_open_all")."
		".$rs -> form_option(LCLAN_OPT_42, ($linkspage_pref['link_open_all'] == "5" ? "1" : "0"), "5", "")."
		".$rs -> form_option(LCLAN_OPT_43, ($linkspage_pref['link_open_all'] == "0" ? "1" : "0"), "0", "")."
		".$rs -> form_option(LCLAN_OPT_44, ($linkspage_pref['link_open_all'] == "1" ? "1" : "0"), "1", "")."
		".$rs -> form_option(LCLAN_OPT_45, ($linkspage_pref['link_open_all'] == "4" ? "1" : "0"), "4", "")."
		".$rs -> form_select_close();
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$TOPIC_TOPIC = LCLAN_OPT_33;
		$TOPIC_FIELD = $rs -> form_text("link_resize_value", "3", $linkspage_pref['link_resize_value'], "3", "tbox", "", "", "")." ".LCLAN_OPT_5;
		$text .= preg_replace("/\{(.*?)\}/e", '$\1', $TOPIC_ROW);

		$text .= $TOPIC_ROW_SPACER;

		$text .= "
		<tr style='vertical-align:top'>
		<td colspan='2' style='text-align:center' class='forumheader'>
		".$rs->form_button("submit", "updateoptions", LCLAN_ADMIN_1)."
		</td>
		</tr>
		</table>
		".$rs->form_close()."
		</div>";
		$ns->tablerender(LCLAN_OPT_2, $text);
	}
}

?>