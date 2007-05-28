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
|     $Source: /cvs_backup/e107_0.8/e107_handlers/user_select_class.php,v $
|     $Revision: 1.3 $
|     $Date: 2007-05-28 11:13:20 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined("e_THEME")) {
	require_once('../class2.php');
	include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_user_select.php");
	$us = new user_select;
	$us -> popup();
}

include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_user_select.php");

class user_select {

	function user_list($class, $form_name) {
		global $pref, $sql, $tp;
		if($class === FALSE) { $class = e_UC_MEMBER;}
		switch ($class)
		{
			case e_UC_ADMIN:
				$where = "user_admin = 1";
				break;

			case e_UC_MEMBER:
				$where = "1";
				break;
				
			case e_UC_NOBODY:
				return "";
				break;
				
			default:
				$where = "user_class REGEXP '(^|,)(".$tp -> toDB($class, true).")(,|$)'";
				break;
		}
				
		$text = "<select class='tbox' id='user' name='user' onchange=\"uc_switch('class')\">";
		$text .= "<option value=''>".US_LAN_1."</option>";
		$sql -> db_Select("user", "user_name", $where." ORDER BY user_name");
		while ($row = $sql -> db_Fetch()) {
			$text .= "<option value='".$row['user_name']."'>".$row['user_name']."</option>";
		}
		$text .= "</select>";
		return $text;
	}
	
	function class_list($class, $form_name) {
		global $pref, $sql;
		$text = "<select class='tbox' id='class' name='class' onchange=\"uc_switch('user')\">";
		$text .= "<option value=''>".US_LAN_2."</option>";
		if (ADMINPERMS == '0' && $class == e_UC_MEMBER) {
			$text .= "<option value='all'>".US_LAN_3."</option>";
		}
		if ($class == e_UC_MEMBER) {
			$sql -> db_Select("userclass_classes", "userclass_id, userclass_name", "ORDER BY userclass_name", "nowhere");
			while ($row = $sql -> db_Fetch()) {
				if (check_class($row['userclass_id']) || ADMINPERMS == '0') {
					$text .= "<option value='".$row['userclass_id'].":".$row['userclass_name']."'>".$row['userclass_name']."</option>";
				}
			}
		} else {
			$sql -> db_Select("userclass_classes", "userclass_id, userclass_name", "userclass_id='".intval($class)."' ORDER BY userclass_name");
			while ($row = $sql -> db_Fetch()) {
				$text .= "<option value='".$row['userclass_id'].":".$row['userclass_name']."'>".$row['userclass_name']."</option>";
			}
		}
		return $text;
	}
	
	function select_form($type, $user_form, $user_value = '', $class_form = false, $class_value = '', $class = false) {
		global $tp, $imode;
		$text .= "<script type='text/javascript'>
		<!--
		function uc_switch(uctype) {
			document.getElementById(uctype).value = '';
		}
		//-->
		</script>";

		list($form_type, $form_id) = explode(".", $user_form);
		if($form_id == "") { $form_id = $form_type; }

		if ($type == 'list') {
			$text .= $this -> user_list($class, 'user');
		} 
		else if ($type == 'popup')
		{
			if($form_type == 'textarea')
			{
				$text .= "<textarea class='tbox' name='".$form_id."' id='".$form_id."' cols='50' rows='4'>{$user_value}</textarea>&nbsp;";
			}
			else
			{
				$text .= "<input class='tbox' type='text' name='".$form_id."' id='".$form_id."' size='25' maxlength='30' value='".$tp -> post_toForm($user_value)."'>&nbsp;";
			}
			$text .= "<img src='".e_IMAGE_ABS."packs/".$imode."/generic/user_select.png' 
			style='width: 16px; height: 16px; vertical-align: top' alt='".US_LAN_4."...' 
			title='".US_LAN_4."...' onclick=\"window.open('".e_HANDLER_ABS."user_select_class.php?".$user_form."','user_search', 'toolbar=no,location=no,status=yes,scrollbars=yes,resizable=yes,width=300,height=200,left=100,top=100'); return false;\" />";
		}
		
		if ($class !== false) {
			if (($class < e_UC_NOBODY && USERCLASS) || ADMINPERMS == '0') {
				$text .= ' '.$this -> class_list($class, 'class');
			}
		}
		
		return $text;
	}
	
	function real_name($_id) {
		global $sql;
		$sql -> db_Select("user", "user_name", "user_id='".intval($_id)."' ");
		if ($row = $sql -> db_Fetch()) {
			return $row['user_name'];
		}
	}
	
	function popup() {
		global $ns, $tp;
		list($elementType, $elementID) = explode(".", e_QUERY);
		if($elementType == 'textarea')
		{
			$job = "
			curval = parent.opener.document.getElementById('{$elementID}').value;
			lastchr = curval.substring(curval.length-1, curval.length);
			if(lastchr != '\\n' && curval.length > 0)
			{
				curval = curval+'\\n';
			}
			parent.opener.document.getElementById('{$elementID}').value = curval+d+'\\n';";
		}
		else
		{
			if($elementID == "")
			{
				$elementID = $elementType;
			}
			$job = "parent.opener.document.getElementById('{$elementID}').value = d;";
		}
		
		// send the charset to the browser - overrides spurious server settings with the lan pack settings.
		header("Content-type: text/html; charset=".CHARSET, true);
		echo (defined("STANDARDS_MODE") ? "" : "<?xml version='1.0' encoding='".CHARSET."' "."?".">\n")."<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
		echo "<html xmlns='http://www.w3.org/1999/xhtml'".(defined("TEXTDIRECTION") ? " dir='".TEXTDIRECTION."'" : "").(defined("CORE_LC") ? " xml:lang=\"".CORE_LC."\"" : "").">
		<head>
		<title>".SITENAME."</title>\n";

		
		echo "<link rel=stylesheet href='".THEME_ABS."style.css'>
		<script language='JavaScript' type='text/javascript'>
		<!--
		function SelectUser() {
		var d = window.document.results.usersel.value;
		{$job}
		this.close();
		}
		//-->
		</script>
		</head>
		<body>
		";

		$text = "<form action='".e_SELF."?".e_QUERY."' method='POST'>
			<table style='width:100%' class='fborder'>
			<tr>
			<td class='forumheader3' style='text-align: center'><input type='text' name='srch' class='tbox' value='".$tp -> post_toForm($_POST['srch'])."' size='40'>
			<input class='button' type='submit' name='dosrch' class='tbox' value='".US_LAN_6."' /></td>
			</tr>
			</table>
			</form>
			";

		if ($_POST['dosrch']) {
			$userlist = $this -> findusers($_POST['srch']);
			if($userlist == FALSE)
			{
				$fcount= 0;
			}
			else
			{
				$fcount = count($userlist);
			}
			$text .= "<br /><form name='results' action='".e_SELF."?".e_QUERY."' method='POST'>
			<table style='width:100%' class='fborder'>
			<tr><td class='fcaption'>{$fcount} ".US_LAN_5."</td></tr>
			<tr>
			<td class='forumheader3'>
			<select class='tbox' name='usersel' width='60' ondblclick='SelectUser()'>
			";
			foreach($userlist as $u) {
				$text .= "<option value='{$u}'>{$u}";
			}
			$text .= "
			</select>
			<input type='button' class='button' value='".US_LAN_1."' onClick='SelectUser()' />
			</td>

			</tr>
			</table>
			</form>
			";
		}
		$ns -> tablerender(US_LAN_4, $text);
		echo "\n</body>\n</html>";
	}

	function findusers($s) {
		global $sql, $tp;
		if ($sql->db_Select("user", "*", "user_name LIKE '%".$tp -> toDB($s)."%' ")) {
			while ($row = $sql -> db_Fetch()) {
				$ret[strtolower($row['user_name'])] = $row['user_name'];
			}
			ksort($ret);
		} else {
			$ret = FALSE;
		}
		return $ret;
	}

}

?>