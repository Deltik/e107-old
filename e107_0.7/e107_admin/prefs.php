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
|     $Source: /cvs_backup/e107_0.7/e107_admin/prefs.php,v $
|     $Revision: 1.17 $
|     $Date: 2005-01-27 19:52:24 $
|     $Author: streaky $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
	
if (isset($_POST['newver'])) {
	header("location:http://e107.org/index.php");
	exit;
}
	
if (!getperms("1")) {
	header("location:".e_BASE."index.php");
	 exit;
}
$e_sub_cat = 'prefs';
if (!$pref['timezone']) {
	$pref['timezone'] = "GMT";
}
	
require_once(e_HANDLER."form_handler.php");
$rs = new form;
	
$signup_title = array(CUSTSIG_2, CUSTSIG_3, "ICQ", "Aim", "MSN", CUSTSIG_4, CUSTSIG_5, CUSTSIG_6, CUSTSIG_7, CUSTSIG_8, CUSTSIG_17);
$signup_name = array("real", "url", "icq", "aim", "msn", "dob", "loc", "sig", "avt", "zone", "usrclass");
	
$aj = new textparse;
if (isset($_POST['updateprefs'])) {
	$pref['sitename'] = $aj->formtpa($_POST['sitename']);
	$pref['siteurl'] = $aj->formtpa($_POST['siteurl']);
	$pref['sitebutton'] = $aj->formtpa($_POST['sitebutton']);
	$pref['sitetag'] = $aj->formtpa($_POST['sitetag']);
	$pref['sitedescription'] = $aj->formtpa($_POST['sitedescription']);
	$pref['siteadmin'] = $aj->formtpa($_POST['siteadmin']);
	$pref['siteadminemail'] = $aj->formtpa($_POST['siteadminemail']);
	$pref['sitetheme'] = ($_POST['sitetheme'] && $_POST['sitetheme'] != "/" ? $_POST['sitetheme'] : "leap of faith");
	$pref['admintheme'] = ($_POST['admintheme'] && $_POST['admintheme'] != "/" ? $_POST['admintheme'] : "leap of faith");
	$pref['sitedisclaimer'] = $aj->formtpa($_POST['sitedisclaimer']);
	 
	$pref['anon_post'] = $_POST['anon_post'];
	$pref['user_reg'] = $_POST['user_reg'];
	$pref['profanity_filter'] = $_POST['profanity_filter'];
	$pref['profanity_replace'] = $aj->formtpa($_POST['profanity_replace']);
	$pref['profanity_words'] = $aj->formtpa($_POST['profanity_words']);
	$pref['use_coppa'] = $_POST['use_coppa'];
	$pref['shortdate'] = $aj->formtpa($_POST['shortdate']);
	$pref['longdate'] = $aj->formtpa($_POST['longdate']);
	$pref['forumdate'] = $aj->formtpa($_POST['forumdate']);
	//$pref['sitelanguage'] = $_POST['sitelanguage'];
	$pref['time_offset'] = $_POST['time_offset'];
	$pref['user_reg_veri'] = $_POST['user_reg_veri'];
	$pref['user_reg_secureveri'] = $_POST['user_reg_secureveri'];
	$pref['user_tracking'] = $_POST['user_tracking'];
	$pref['cookie_name'] = ereg_replace("[^[:alnum:]]", "", $_POST['cookie_name']);
	$pref['auth_method'] = $_POST['auth_method'];
	$pref['displaythemeinfo'] = $_POST['displaythemeinfo'];
	$pref['displayrendertime'] = $_POST['displayrendertime'];
	$pref['displaysql'] = $_POST['displaysql'];
	$pref['image_preload'] = $_POST['image_preload'];
	$pref['timezone'] = $_POST['timezone'];
	$pref['adminstyle'] = $_POST['adminstyle'];
	$pref['membersonly_enabled'] = $_POST['membersonly_enabled'];
	$pref['ssl_enabled'] = $_POST['ssl_enabled'];
	$pref['search_restrict'] = $_POST['search_restrict'];
	$pref['nested_comments'] = $_POST['nested_comments'];
	$pref['antiflood1'] = $_POST['antiflood1'];
	$pref['antiflood_timeout'] = $_POST['antiflood_timeout'];
	$pref['autoban'] = $_POST['autoban'];
	$pref['admin_alerts_ok'] = $_POST['admin_alerts_ok'];
	$pref['admin_alerts_uniquemenu'] = $_POST['admin_alerts_uniquemenu'];
	 
	// Signup. ====================================================
	 
	$pref['signup_pass_len'] = $_POST['signup_pass_len'];
	 
	 
	// Create prefs to custom fields.
	if ($sql->db_Select("core", " e107_value", " e107_name='user_entended'")) {
		$row = $sql->db_Fetch();
		$user_entended = unserialize($row[0]);
		$c = 0;
		$user_pref = unserialize($user_prefs);
		while (list($key, $u_entended) = each($user_entended)) {
			if ($u_entended) {
				$signup_ext = "signup_ext".$key;
				$pref[$signup_ext] = $_POST[$signup_ext];
				$signup_ext_req = "signup_ext_req".$key;
				$pref[$signup_ext_req] = $_POST[$signup_ext_req];
			}
		}
	}
	 
	$signup_options = "";
	for ($i = 0; $i < count($signup_title); $i++) {
		$valuesignup = $signup_name[$i];
		$signup_options .= $_POST[$valuesignup];
		$signup_options .= $i < (count($signup_title)-1)?".":
		"";
	}
	$pref['signup_options'] = $signup_options;
	 
	// =========================
	 
	$pref['signcode'] = $_POST['signcode'];
	$pref['logcode'] = $_POST['logcode'];
	$pref['htmlarea'] = $_POST['htmlarea'];
	 
	$e107cache->clear();
	 
	save_prefs();
	 
	 
	header("location:".e_ADMIN."prefs.php");
	echo "<script type='text/javascript'>document.location.href='prefs.php'</script>\n";
	exit;
}
	
$sql->db_Select("plugin", "*", "plugin_installflag='1' ");
while ($row = $sql->db_Fetch()) {
	extract($row);
	if (preg_match("/^auth_(.*)/", $plugin_path, $match)) {
		$authlist[] = $match[1];
	}
}
if ($authlist) {
	$authlist[] = "e107";
	$auth_dropdown = "\n<tr>
		<td style='width:50%' class='forumheader3'>".PRFLAN_56.": </td>
		<td style='width:50%; text-align:right;' class='forumheader3'>";
	$auth_dropdown .= "<select class='tbox' name='auth_method'>\n";
	foreach($authlist as $a) {
		$s = ($pref['auth_method'] == $a) ? " selected='selected'>" :
		 "";
		$auth_dropdown .= "<option {$s}>".$a."</option>\n";
	}
	$auth_dropdown .= "</select>\n";
	$auth_dropdown .= "</td></tr>";
} else {
	$auth_dropdown = "<input type='hidden' name='auth_method' value='' />";
	$pref['auth_method'] = "";
}
	
	
//added prefs since v2.0 ...
$anon_post = $pref['anon_post'];
$user_reg = $pref['user_reg'];
$profanity_filter = $pref['profanity_filter'];
$profanity_replace = $pref['profanity_replace'];
$profanity_words = $pref['profanity_words'];
$search_restrict = $pref['search_restrict'];
$use_coppa = $pref['use_coppa'];
$shortdate = $pref['shortdate'];
$longdate = $pref['longdate'];
$forumdate = $pref['forumdate'];
$sitelocale = $pref['sitelocale'];
$time_offset = $pref['time_offset'];
$user_reg_veri = $pref['user_reg_veri'];
$user_reg_secureveri = $pref['user_reg_secureveri'];
$user_tracking = $pref['user_tracking'];
$antiflood1 = $pref['antiflood1'];
$antiflood_timeout = $pref['antiflood_timeout'];
$autoban = $pref['autoban'];
	
require_once("auth.php");
	
if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}
	
$handle = opendir(e_THEME);
while ($file = readdir($handle)) {
	if ($file != "." && $file != ".." && $file != "templates" && $file != "/") {
		if (is_readable(e_THEME.$file."/theme.php") && is_readable(e_THEME.$file."/style.css")) {
			$dirlist[] = $file;
		}
	}
}
closedir($handle);
	
$handle = opendir(e_ADMIN.'includes/');
while ($file = readdir($handle)) {
	if ($file != "." && $file != "..") {
		$file = str_replace(".php", "", $file);
		$adminlist[] = $file;
	}
}
closedir($handle);
	
$text = "<script type=\"text/javascript\">
	<!--
	var hideid=\"main\";
	function showhideit(showid){
	if (hideid!=showid){
	show=document.getElementById(showid).style;
	hide=document.getElementById(hideid).style;
	show.display=\"\";
	hide.display=\"none\";
	hideid = showid;
	}
	}
	//-->
	</script>
	<div style='text-align:center'>
	<div style='text-align:center; ".ADMIN_WIDTH."; margin-left: auto; margin-right: auto'>
	<form method='post' action='".e_SELF."'>
	<div id='main' style='text-align:center'>
	<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_1."</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_2.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class=\"tbox\" type=\"text\" name=\"sitename\" size=\"50\" value=\"".SITENAME."\" maxlength=\"100\" />
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_3.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='siteurl' size='50' value='".SITEURL."' maxlength='150' />
	</td>
	</tr>
	 
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_4.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='sitebutton' size='50' value='".SITEBUTTON."' maxlength='150' />
	</td>
	</tr>
	<tr>
	 
	<td style='width:50%' class='forumheader3'>".PRFLAN_5.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<textarea class='tbox' name='sitetag' cols='59' rows='3'>".SITETAG."</textarea>
	</td>
	</tr>
	<tr>
	 
	<td style='width:50%' class='forumheader3'>".PRFLAN_6.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<textarea class='tbox' name='sitedescription' cols='59' rows='3'>".SITEDESCRIPTION."</textarea>
	</td>
	</tr>
	<tr>
	 
	<td style='width:50%' class='forumheader3'>".PRFLAN_7.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='siteadmin' size='50' value='".SITEADMIN."' maxlength='100' />
	</td>
	</tr>
	<tr>
	 
	<td style='width:50%' class='forumheader3'>".PRFLAN_8.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='siteadminemail' size='50' value='".SITEADMINEMAIL."' maxlength='100' />
	</td>
	</tr>
	<tr>
	 
	<td style='width:50%' class='forumheader3'>".PRFLAN_9.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<textarea class='tbox' name='sitedisclaimer' cols='59' rows='3'>".SITEDISCLAIMER."</textarea>
	</td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table>
	</div>
	 
	<div id='theme' style='display:none; text-align:center'>
	<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_10."</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_11.":<br /><span class='smalltext'>".PRFLAN_85."</span></td>
	<td style='width:50%; text-align:right' class='forumheader3'><a href='".e_ADMIN."theme_prev.php'>".PRFLAN_12."</a>
	<select name='sitetheme' class='tbox'>\n";
$counter = 0;
while (isset($dirlist[$counter])) {
	if (!strstr($dirlist[$counter], 'admin_')) {
		$text .= ($dirlist[$counter] == $pref['sitetheme'] ? "<option selected='selected'>".$dirlist[$counter]."</option>\n" : "<option>".$dirlist[$counter]."</option>\n");
	}
	$counter++;
}
$text .= "</select>
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_100."</td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['image_preload'] ? "<input type='checkbox' name='image_preload' value='1' checked='checked' />" : "<input type='checkbox' name='image_preload' value='1' />")."
	</td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table>
	</div>
	 
	<div id='display' style='display:none; text-align:center'>
	<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_13."</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_14." </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['displaythemeinfo'] ? "<input type='checkbox' name='displaythemeinfo' value='1' checked='checked' />" : "<input type='checkbox' name='displaythemeinfo' value='1' />")."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_15." </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['displayrendertime'] ? "<input type='checkbox' name='displayrendertime' value='1' checked='checked' />" : "<input type='checkbox' name='displayrendertime' value='1' />")."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_16." </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['displaysql'] ? "<input type='checkbox' name='displaysql' value='1' checked='checked' />" : "<input type='checkbox' name='displaysql' value='1' />")."
	</td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table></div>";
	
// Admin Display Areas. .
	
$text .= "<div id='admindisp' style='display:none; text-align:center'>
	<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_77."</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_54.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<select name='admintheme' class='tbox'>\n";
$counter = 0;
while (isset($dirlist[$counter])) {
	$text .= ($dirlist[$counter] == $pref['admintheme'] ? "<option selected='selected'>".$dirlist[$counter]."</option>\n" : "<option>".$dirlist[$counter]."</option>\n");
	$counter++;
}
$text .= "</select>
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_57.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<select name='adminstyle' class='tbox'>\n";
$counter = 0;
while (isset($adminlist[$counter])) {
	$text .= ($adminlist[$counter] == $pref['adminstyle'] ? "<option selected='selected'>".$adminlist[$counter]."</option>\n" : "<option>".$adminlist[$counter]."</option>\n");
	$counter++;
}
$text .= "</select>
	</td>
	</tr>";
	
	
$text .= "<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_79.":</td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['htmlarea'] ? "<input type='checkbox' name='htmlarea' value='1'  checked='checked' />" : "<input type='checkbox' name='htmlarea' value='1' />")."
	</td>
	</tr>";
	
$text .= "<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_95."</td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['admin_alerts_ok'] ? "<input type='checkbox' name='admin_alerts_ok' value='1'  checked='checked' />" : "<input type='checkbox' name='admin_alerts_ok' value='1' />")."
	<br />".PRFLAN_96."
	</td>
	</tr>";
	
$text .= "<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_97."</td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['admin_alerts_uniquemenu'] ? "<input type='checkbox' name='admin_alerts_uniquemenu' value='1'  checked='checked' />" : "<input type='checkbox' name='admin_alerts_uniquemenu' value='1' />")."
	<br />".PRFLAN_98."
	</td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table></div>";
	
// Date options.
$text .= "<div id='date' style='display:none; text-align:center'>
	<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_21."</td>
	</tr>
	 
	<tr>";
	
$ga = new convert;
$date1 = $ga->convert_date(time(), "short");
$date2 = $ga->convert_date(time(), "long");
$date3 = $ga->convert_date(time(), "forum");
	
	
$text .= "<td style='width:50%' class='forumheader3'>".PRFLAN_22.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='shortdate' size='40' value='$shortdate' maxlength='50' />
	<br />".PRFLAN_83.": $date1
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_23.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='longdate' size='40' value='$longdate' maxlength='50' />
	<br />".PRFLAN_83.": $date2
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_24.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='forumdate' size='40' value='$forumdate' maxlength='50' />
	<br />".PRFLAN_83.": $date3
	</td>
	</tr>
	 
	<tr>
	<td colspan='2' style='text-align:center' class='forumheader3'>
	(".PRFLAN_25." <a href='http://www.php.net/manual/en/function.strftime.php' rel='external'>".PRFLAN_93."</a>)
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_26.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<select name='time_offset' class='tbox'>\n";
$toffset = array("-12", "-11", "-10", "-9", "-8", "-7", "-6", "-5", "-4", "-3", "-2", "-1", "0", "+1", "+2", "+3", "+4", "+5", "+6", "+7", "+8", "+9", "+10", "+11", "+12", "+13", "+14", "+15", "+16");
$counter = 0;
while (isset($toffset[$counter])) {
	if ($toffset[$counter] == $pref['time_offset']) {
		$text .= "<option selected='selected'>".$toffset[$counter]."</option>\n";
	} else {
		$text .= "<option>".$toffset[$counter]."</option>\n";
	}
	$counter++;
}
$text .= "</select>
	</td></tr>
	 
	<tr>
	<td colspan='2' style='text-align:center' class='forumheader3'>
	(".PRFLAN_27.")
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_56.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='timezone' size='20' value='".$pref['timezone']."' maxlength='50' />
	</td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table></div>";
	
// =========== Registration Preferences. ==================
	
$text .= "<div id='registration' style='display:none; text-align:center'><table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_28."</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_29.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($user_reg ? "<input type='checkbox' name='user_reg' value='1'  checked='checked' />" : "<input type='checkbox' name='user_reg' value='1' />")."
	(".PRFLAN_30.")
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_31.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($user_reg_veri ? "<input type='checkbox' name='user_reg_veri' value='1'  checked='checked' />" : "<input type='checkbox' name='user_reg_veri' value='1' />")."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_32.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($anon_post ? "<input type='checkbox' name='anon_post' value='1'  checked='checked' />" : "<input type='checkbox' name='anon_post' value='1' />")."
	(".PRFLAN_33.")
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_45.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>";
if ($use_coppa == 1) {
	$text .= "<input type='checkbox' name='use_coppa' value='1'  checked='checked' />";
} else {
	$text .= "<input type='checkbox' name='use_coppa' value='1' />";
}
	
	
$text .= "(".PRFLAN_46." <a href='http://www.cdt.org/legislation/105th/privacy/coppa.html'>".PRFLAN_94."</a>)
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_58.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['membersonly_enabled'] ? "<input type='checkbox' name='membersonly_enabled' value='1'  checked='checked' />" : "<input type='checkbox' name='membersonly_enabled' value='1' />")."
	(".PRFLAN_59.")
	</td>
	</tr>
	 
	 
	";
	
$text .= "
	<tr>
	<td style='width:50%' class='forumheader3'>".CUSTSIG_16."</td>
	<td class='forumheader3' style='width:50%;text-align:right' >
	<input type='text' class='tbox' size='3' name='signup_pass_len' value='".$pref['signup_pass_len']."' />
	(".PRFLAN_78.") </td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table></div>";
	
	
// Signup options ===========================.
	
$text .= "<div id='signup' style='display:none; text-align:center'><table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_19."</td>
	</tr>
	 
	<tr >
	<td class=\"forumheader\">".CUSTSIG_13."</td>
	<td class=\"forumheader\">".CUSTSIG_14."</td>
	</tr>";
$signupval = explode(".", $pref['signup_options']);
for ($i = 0; $i < count($signup_title); $i++) {
	$text .= "
		<tr>
		<td style='width:50%' class='forumheader3'>".$signup_title[$i]."</td>
		<td style='width:50%;text-align:center' class='forumheader3' >". ($signupval[$i] == "0" || $$signup_name[$i] == "" ? "<input type='radio' name='".$signup_name[$i]."' value='0' checked='checked' /> ".CUSTSIG_12 : "<input type='radio' name='".$signup_name[$i]."' value='0' /> ".CUSTSIG_12)."&nbsp;&nbsp;". ($signupval[$i] == "1" ? "<input type='radio' name='".$signup_name[$i]."' value='1' checked='checked' /> ".CUSTSIG_14 : "<input type='radio' name='".$signup_name[$i]."' value='1' /> ".CUSTSIG_14)."&nbsp;&nbsp;". ($signupval[$i] == "2" ? "<input type='radio' name='".$signup_name[$i]."' value='2' checked='checked' /> ".CUSTSIG_15 : "<input type='radio' name='".$signup_name[$i]."' value='2' /> ".CUSTSIG_15)."&nbsp;&nbsp;
		 
		</td></tr>";
}
	
// Custom Fields.
	
if ($sql->db_Select("core", " e107_value", " e107_name='user_entended'")) {
	$row = $sql->db_Fetch();
	$user_entended = unserialize($row[0]);
	$c = 0;
	$user_pref = unserialize($user_prefs);
	while (list($key, $u_entended) = each($user_entended)) {
		if ($u_entended) {
			$ut = explode("|", $u_entended);
			$u_name = ($ut[0] != "") ? str_replace("_", " ", $ut[0]):
			 $u_entended;
			$u_type = $ut[1];
			$u_value = $ut[2];
			$signup_ext = "signup_ext";
			$text .= "
				<tr>
				<td style='width:50%' class='forumheader3'>".$u_name." <span class='smalltext'>(custom field)</span></td>
				<td style='width:50%;text-align:right' class='forumheader3' >". ($pref['signup_ext'.$key] == "0" || $pref['signup_ext'.$key] == "" ? "<input type='radio' name='signup_ext".$key."' value='0' checked='checked' /> ".CUSTSIG_12 : "<input type='radio' name='signup_ext".$key."' value='0' /> ".CUSTSIG_12)."&nbsp;&nbsp;". ($pref['signup_ext'.$key] == "1" ? "<input type='radio' name='signup_ext".$key."' value='1' checked='checked' /> ".CUSTSIG_14 : "<input type='radio' name='signup_ext".$key."' value='1' /> ".CUSTSIG_14)."&nbsp;&nbsp;". ($pref['signup_ext'.$key] == "2" ? "<input type='radio' name='signup_ext".$key."' value='2' checked='checked' /> ".CUSTSIG_15 : "<input type='radio' name='signup_ext".$key."' value='2' /> ".CUSTSIG_15)."&nbsp;&nbsp;". "</td>
				</tr>";
		}
	}
}
	
	
$text .= pref_submit();
	
	
$text .= "</table></div>";
	
// Security Options. .
	
$text .= "<div id='security' style='display:none; text-align:center'>
	<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_47."</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_60."<br /><span class='smalltext'>".PRFLAN_61."</span> </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['ssl_enabled'] ? "<input type='checkbox' name='ssl_enabled' value='1'  checked='checked' />" : "<input type='checkbox' name='ssl_enabled' value='1' />")."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_76.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['signcode'] ? "<input type='checkbox' name='signcode' value='1'  checked='checked' />" : "<input type='checkbox' name='signcode' value='1' />")."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_81.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['logcode'] ? "<input type='checkbox' name='logcode' value='1'  checked='checked' />" : "<input type='checkbox' name='logcode' value='1' />")."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_92.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($user_reg_secureveri ? "<input type='checkbox' name='user_reg_secureveri' value='1'  checked='checked' />" : "<input type='checkbox' name='user_reg_secureveri' value='1' />")."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_48.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($user_tracking == "cookie" ? "<input type='radio' name='user_tracking' value='cookie' checked='checked' /> ".PRFLAN_49 : "<input type='radio' name='user_tracking' value='cookie' /> ".PRFLAN_49). ($user_tracking == "session" ? "<input type='radio' name='user_tracking' value='session' checked='checked' /> ".PRFLAN_50 : "<input type='radio' name='user_tracking' value='session' /> ".PRFLAN_50)."
	<br />
	".PRFLAN_55.": <input class='tbox' type='text' name='cookie_name' size='20' value='".$pref['cookie_name']."' maxlength='20' />
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_40.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>";
if ($profanity_filter == 1) {
	$text .= "<input type='checkbox' name='profanity_filter' value='1'  checked='checked' />";
} else {
	$text .= "<input type='checkbox' name='profanity_filter' value='1' />";
}
	
$text .= "(".PRFLAN_41.")
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_42.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='profanity_replace' size='30' value='$profanity_replace' maxlength='20' />
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_43.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<textarea class='tbox' name='profanity_words' cols='59' rows='2'>".$profanity_words."</textarea>
	<br />".PRFLAN_44."
	</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_82.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>";
if ($search_restrict == 1) {
	$text .= "<input type='checkbox' name='search_restrict' value='1'  checked='checked' />";
} else {
	$text .= "<input type='checkbox' name='search_restrict' value='1' />";
}
$text .= "</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_35.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>";
if ($antiflood1 == 1) {
	$text .= "<input type='checkbox' name='antiflood1' value='1'  checked='checked' />";
} else {
	$text .= "<input type='checkbox' name='antiflood1' value='1' />";
}
$text .= "</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_36.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='antiflood_timeout' size='3' value='$antiflood_timeout' maxlength='3' />
	<br />
	<b class=\"smalltext\" >".PRFLAN_38."</b>
	</td>
	</tr>
	 
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_37.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>";
if ($autoban == 1) {
	$text .= "<input type='checkbox' name='autoban' value='1'  checked='checked' />";
} else {
	$text .= "<input type='checkbox' name='autoban' value='1' />";
}
$text .= "<br />
	<b class=\"smalltext\" >".PRFLAN_91."</b></td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table></div>";
	
$text .= "<div id='comments' style='display:none; text-align:center'>
	<table style='width:100%' class='fborder'>
	<tr>
	<td class='fcaption' title='".PRFLAN_80."' style='cursor:pointer; cursor:hand; text-align:left;' colspan='2'>".PRFLAN_87."</td>
	</tr>
	 
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_88.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['nested_comments'] ? "<input type='checkbox' name='nested_comments' value='1'  checked='checked' />" : "<input type='checkbox' name='nested_comments' value='1' />"). "</td>
	</tr>";
	
$text .= pref_submit();
	
$text .= "</table></div>";
	
$text .= "</form></div></div>";
	
$ns->tablerender(PRFLAN_53, $text);
	
require_once("footer.php");
	
function pref_submit() {
	$text = "<tr>
		<td colspan='2' style='text-align:center' class='forumheader'>";
	 
	// ML
	/* if(e_MLANG == 1){
	//$text .="<input class='fcaption' type='submit' name='updateprefs' value='".PRFLAN_52."' />
	$but_typ = array(""); // empty = submit
	$but_nam = array("updateprefs"); // empty = autobutX with X autoincrement
	$but_val = array("updateprefs"); // empty = Submit
	$but_class = array("caption"); // empty = button
	$butjs = array(""); // empty = ""
	$buttitle = array(""); // empty = ""
	$text .= e107ml_adpanel(1,$but_typ,$but_nam,$but_val,$but_class,$butjs,$buttitle);
	}else{*/
	$text .= "<input class='button' type='submit' name='updateprefs' value='".PRFLAN_52."' />";
	// }
	$text .= "</td>
		</tr>";
	 
	// END ML
	return $text;
}
	
function prefs_adminmenu() {
	$var['main']['text'] = PRFLAN_1;
	$var['theme']['text'] = PRFLAN_10;
	$var['display']['text'] = PRFLAN_13;
	$var['admindisp']['text'] = PRFLAN_77;
	$var['date']['text'] = PRFLAN_21;
	$var['registration']['text'] = PRFLAN_28;
	$var['signup']['text'] = PRFLAN_19;
	$var['security']['text'] = PRFLAN_47;
	$var['comments']['text'] = PRFLAN_87;
	show_admin_menu(PRFLAN_99, $action, $var, TRUE);
}
?>