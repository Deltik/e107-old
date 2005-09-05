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
|     $Source: /cvs_backup/e107_0.7/e107_admin/users.php,v $
|     $Revision: 1.59 $
|     $Date: 2005-09-05 01:10:53 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");

if (!getperms("4")) {
	header("location:".e_BASE."index.php");
	 exit;
}

if (isset($_POST['useraction']) && $_POST['useraction'] == 'userinfo') {
	header('location:'.e_ADMIN."userinfo.php?{$_POST['userip']}");
	exit;
}

if (isset($_POST['useraction']) && $_POST['useraction'] == 'usersettings') {
	header('location:'.e_BASE."usersettings.php?{$_POST['userid']}");
	exit;
}

if (isset($_POST['useraction']) && $_POST['useraction'] == 'userclass') {
	header('location:'.e_ADMIN."userclass.php?{$_POST['userid']}");
	exit;
}

$e_sub_cat = 'users';
$user = new users;
require_once("auth.php");

require_once(e_HANDLER."form_handler.php");

$rs = new form;

if (e_QUERY) {
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	$from = ($tmp[3] ? $tmp[3] : 0);
	unset($tmp);
}

$from = (isset($from)) ? $from : 0;
$amount = 30;
// ------- Resend Email. --------------
if (isset($_POST['resend_mail'])) {
	$tid = $_POST['resend_id'];
	$key = $_POST['resend_key'];
	$name = $_POST['resend_name'];
	define("RETURNADDRESS", (substr(SITEURL, -1) == "/" ? SITEURL."signup.php?activate.".$tid.".".$key : SITEURL."/signup.php?activate.".$tid.".".$key));

	$message = USRLAN_114." ".$_POST['resend_name']."\n\n".USRLAN_122." ".SITENAME."\n".USRLAN_123."\n\n".USRLAN_124."...\n\n";
	$message .= RETURNADDRESS . "\n\n".USRLAN_115."\n\n ".USRLAN_125." ".SITENAME."\n".SITEURL;

	require_once(e_HANDLER."mail.php");
	if(sendemail($_POST['resend_email'], USRLAN_113." ".SITENAME, $message)){
	//  echo str_replace("\n","<br>",$message);
		$user->show_message("Email Re-sent to: ".$name);
	}else{
    	$user->show_message("Failed to Re-sent to: ".$name);
	}
	unset($tid);
}
// ------- Test Email. --------------
if (isset($_POST['test_mail'])) {
	require_once(e_HANDLER."mail.php");
	$text = validatemail($_POST['test_email']);
	$caption = $_POST['test_email']." - ";
	$caption .= ($text[0] == TRUE)?"Successful":
	 "Error";
	$ns->tablerender($caption, $text[1]);
	unset($id, $action, $sub_cation);
}
// ------- Update Options. --------------
if (isset($_POST['update_options'])) {
	$pref['avatar_upload'] = (FILE_UPLOADS ? $_POST['avatar_upload'] : 0);
	$pref['im_width'] = $_POST['im_width'];
	$pref['im_height'] = $_POST['im_height'];
	$pref['photo_upload'] = (FILE_UPLOADS ? $_POST['photo_upload'] : 0);
	$pref['del_unv'] = $_POST['del_unv'];
	$pref['profile_rate'] = $_POST['profile_rate'];
	$pref['profile_comments'] = $_POST['profile_comments'];
	$pref['track_online'] = $_POST['track_online'];
	$pref['force_userupdate'] = $_POST['force_userupdate'];
	save_prefs();
	$user->show_message(USRLAN_1);
}
// ------- Prune Users. --------------
if (isset($_POST['prune'])) {
	$e107cache->clear("online_menu_totals");
	$sql2 = new db;
	$text = USRLAN_56." ";
	if ($sql->db_Select("user", "user_id, user_name", "user_ban=2")) {
		while ($row = $sql->db_Fetch()) {
			extract($row);
			$text .= $user_name." ";
			$sql2->db_Delete("user", "user_id='$user_id' ");
		}
	}
	$ns->tablerender(USRLAN_57, "<div style='text-align:center'><b>".$text."</b></div>");
	unset($text);
}
// ------- Quick Add User --------------
if (isset($_POST['adduser'])) {
	$e107cache->clear("online_menu_totals");
	if (!$_POST['ac'] == md5(ADMINPWCHANGE)) {
		exit;
	}

	require_once(e_HANDLER."message_handler.php");
	if (strstr($_POST['name'], "#") || strstr($_POST['name'], "=")) {
		message_handler("P_ALERT", USRLAN_92);
		$error = TRUE;
	}
	$_POST['name'] = trim(chop(str_replace("&nbsp;", "", $_POST['name'])));
	if ($_POST['name'] == "Anonymous") {
		message_handler("P_ALERT", USRLAN_65);
		$error = TRUE;
	}
	if ($sql->db_Select("user", "*", "user_name='".$_POST['name']."' ")) {
		message_handler("P_ALERT", USRLAN_66);
		$error = TRUE;
	}
	if ($_POST['password1'] != $_POST['password2']) {
		message_handler("P_ALERT", USRLAN_67);
		$error = TRUE;
	}

	if ($_POST['name'] == "" || $_POST['password1'] == "" || $_POST['password2'] = "") {
		message_handler("P_ALERT", USRLAN_68);
		$error = TRUE;
	}
	if (!preg_match('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i', $_POST['email'])) {
		message_handler("P_ALERT", USRLAN_69);
		$error = TRUE;
	}
	if (!$error) {
		if ($sql->db_Select("user", "*", "user_email='".$_POST['email']."' AND user_ban='1' ")) {
			exit;
		}
		if ($sql->db_Select("banlist", "*", "banlist_ip='".$_POST['email']."'")) {
			exit;
		}

		$username = strip_tags($_POST['name']);
		$loginname = strip_tags($_POST['loginname']);
		$ip = $e107->getip();
//		extract($_POST);
//		for($a = 0; $a <= (count($_POST['userclass'])-1); $a++) {
//			$svar .= $userclass[$a].".";
//		}
		$svar = implode(",", $_POST['userclass']);
		$sql->db_Insert("user", "0, '$username', '$loginname',  '', '".md5($_POST['password1'])."', '$key', '".$_POST['email']."', '".$_POST['signature']."', '".$_POST['image']."', '".$_POST['timezone']."', '1', '".time()."', '0', '".$time."', '0', '0', '0', '0', '".$ip."', '0', '0', '', '', '', '0', '".$_POST['realname']."', '".$svar."', '', '', '', '' ");
		$user->show_message(USRLAN_70);
	}
}

// ------- Ban User. --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == "ban")
{
  //	$sub_action = $_POST['userid'];
	$sql->db_Select("user", "*", "user_id='".$_POST['userid']."'");
	$row = $sql->db_Fetch();
//	 extract($row);
	if ($row['user_perms'] == "0")
	{
		$user->show_message(USRLAN_7);
	}
	else
	{
		if($sql->db_Update("user", "user_ban='1' WHERE user_id='".$_POST['userid']."' "))
		{
			$user->show_message(USRLAN_8);
		}
		if(trim($row['user_ip']) == "")
		{
			$user->show_message(USRLAN_129);
		}
		else
		{
			if($sql->db_Count("user", "(*)", "WHERE user_ip = '{$row['user_ip']}'") > 1)
			{
				$user->show_message(str_replace("{IP}", $row['user_ip'], USRLAN_130));
			}
			else
			{
				$sql -> db_Insert("banlist", "'".$row['user_ip']."', '".USERID."', '".$row['user_name']."' ");
				$user->show_message(str_replace("{IP}", $row['user_ip'], USRLAN_131));
			}
		}
	}
	$action = "main";
	if(!$sub_action){$sub_action = "user_id"; }
}
// ------- Unban User --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == "unban") {
	$sql->db_Select("user", "*", "user_id='".$_POST['userid']."'");
	$row = $sql->db_Fetch();
	extract($row);
	$sql->db_Update("user", "user_ban='0' WHERE user_id='".$_POST['userid']."' ");
	$sql -> db_Delete("banlist", " banlist_ip='$user_ip' ");
	$user->show_message(USRLAN_9);
	$action = "main";
	if(!$sub_action){$sub_action = "user_id"; }
}

// ------- Resend Email. --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == 'resend') {
	$qry = (e_QUERY) ? "?".e_QUERY : "";
	if ($sql->db_Select("user", "*", "user_id='".$_POST['userid']."' ")) {
		$resend = $sql->db_Fetch();
		$text .= "<form method='post' action='".e_SELF.$qry."'><div style='text-align:center'>\n";
		$text .= USRLAN_116." <b>".$resend['user_name']."</b><br /><br />

			<input type='hidden' name='resend_id' value='".$_POST['userid']."' />\n
			<input type='hidden' name='resend_name' value='".$resend['user_name']."' />\n
			<input type='hidden' name='resend_key' value='".$resend['user_sess']."' />\n
			<input type='hidden' name='resend_email' value='".$resend['user_email']."' />\n
			<input class='button' type='submit' name='resend_mail' value='".USRLAN_112."' />\n</div></form>\n";
		$caption = USRLAN_112;
		$ns->tablerender($caption, $text);
		require_once("footer.php");
		exit;
	}
}
// ------- TEst Email. --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == 'test') {
	$qry = (e_QUERY) ? "?".e_QUERY : "";
	if ($sql->db_Select("user", "*", "user_id='".$_POST['userid']."' ")) {
		$test = $sql->db_Fetch();
		$text .= "<form method='post' action='".e_SELF.$qry."'><div style='text-align:center'>\n";
		$text .= USRLAN_117." <br /><b>".$test['user_email']."</b><br /><br />
			<input type='hidden' name='test_email' value='".$test['user_email']."' />\n
			<input class='button' type='submit' name='test_mail' value='".USRLAN_118."' />\n</div></form>\n";
		$caption = USRLAN_118;
		$ns->tablerender($caption, $text);
		require_once("footer.php");
		exit;
	}
}
// ------- Delete User --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == 'deluser') {
	if ($_POST['confirm']) {
		if ($sql->db_Delete("user", "user_id='".$_POST['userid']."' AND user_perms != '0'")) {
			$user->show_message(USRLAN_10);
		}
		if(!$sub_action){ $sub_action = "user_id"; }
		if(!$id){ $id = "DESC"; }

	} else {
		if ($sql->db_Select("user", "*", "user_id='".$_POST['userid']."' ")) {
			$row = $sql->db_Fetch();
			$qry = (e_QUERY) ? "?".e_QUERY : "";
			$text .= "<form method='post' action='".e_SELF.$qry."'><div style='text-align:center'>\n";
			$text .= "<div>
				<input type='hidden' name='useraction' value='deluser' />
				<input type='hidden' name='userid' value='{$row['user_id']}' /></div>". USRLAN_13."
				<br /><br /><span class='indent'>#{$row['user_id']} : {$row['user_name']}</span>
				<br /><br />
				<input type='submit' class='button' name='confirm' value='".USRLAN_17."' />
				&nbsp;&nbsp;
				<input type='button' class='button' name='cancel' value='".LAN_CANCEL."' onclick=\"location.href='".e_SELF.$qry."' \"/>
				</div>
				</form>
				";
			$ns->tablerender(USRLAN_16, $text);
			require_once("footer.php");
			exit;
		}
	}
}
// ------- Make Admin.. --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == "admin") {
	$sql->db_Select("user", "*", "user_id='".$_POST['userid']."'");
	$row = $sql->db_Fetch();
	 extract($row);
	$sql->db_Update("user", "user_admin='1' WHERE user_id='".$_POST['userid']."' ");
	$user->show_message($user_name." ".USRLAN_3." <a href='".e_ADMIN."administrator.php?edit.$user_id'>".USRLAN_4."</a>");
	$action = "main";
	if(!$sub_action){ $sub_action = "user_id"; }
	if(!$id){ $id = "DESC"; }
}
// ------- Remove Admin --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == "unadmin") {
	$sql->db_Select("user", "*", "user_id='".$_POST['userid']."'");
	$row = $sql->db_Fetch();
	 extract($row);
	if ($user_perms == "0") {
		$user->show_message(USRLAN_5);
	} else {
		$sql->db_Update("user", "user_admin='0', user_perms='' WHERE user_id='".$_POST['userid']."'");
		$user->show_message($user_name." ".USRLAN_6);
	$action = "main";
	if(!$sub_action){ $sub_action = "user_id"; }
	if(!$id){ $id = "DESC"; }
	}
}
// ------- Approve User. --------------
if (isset($_POST['useraction']) && $_POST['useraction'] == "verify") {
	if ($sql->db_Update("user", "user_ban='0' WHERE user_id='".$_POST['userid']."' ")) {
		$user->show_message(USRLAN_86);
	if(!$action){ $action = "main"; }
	if(!$sub_action){ $sub_action = "user_id"; }
	if(!$id){ $id = "DESC"; }
	}
}

if (isset($action) && $action == "uset") {
	$user->show_message(USRLAN_87);
	$action = "main";
}

if (isset($action) && $action == "cu") {
	$user->show_message(USRLAN_88);
	$action = "main";
  //	$sub_action = "user_id";
}

/*
echo "action= ".$action."<br />";
echo "subaction= ".$sub_action."<br />";
echo "id= ".$id."<br />";
echo "from= ".$from."<br />";
echo "amount= ".$amount."<br />";
*/

if (!e_QUERY || ($action == "main")) {
	$user->show_existing_users($action, $sub_action, $id, $from, $amount);
}

if (isset($action) && $action == "options") {
	$user->show_prefs();
}

if (isset($action) && $action == "prune") {
	$user->show_prune();
}

if (isset($action) && $action == "create") {
	$user->add_user();
}

require_once("footer.php");

class users{

	function show_existing_users($action, $sub_action, $id, $from, $amount) {
		// ##### Display scrolling list of existing news items ---------------------------------------------------------------------------------------------------------

		global $sql, $rs, $ns, $tp, $mySQLdefaultdb,$pref;
		// save the display choices.
		if(isset($_POST['searchdisp'])){
			$pref['admin_user_disp'] = implode("|",$_POST['searchdisp']);
			save_prefs();
		}

		if(!$pref['admin_user_disp']){
			$search_display = array("user_name","user_class");
		}else{
			$search_display = explode("|",$pref['admin_user_disp']);
		}

		if ($sql->db_Select("userclass_classes")) {
			while ($row = $sql->db_Fetch()) {
				extract($row);
				$class[$userclass_id] = $userclass_name;
			}
		}

		$text = "<div style='text-align:center'><div style='padding : 1px; ".ADMIN_WIDTH."; margin-left: auto; margin-right: auto;'>";

		if (isset($_POST['searchquery']) && $_POST['searchquery'] != "") {
			$_POST['searchquery'] = trim($_POST['searchquery']);
            $query = "WHERE ".
			$query .= (strpos($_POST['searchquery'], "@") !== FALSE) ? "user_email REGEXP('".$_POST['searchquery']."') OR ": "";
			$query .= (strpos($_POST['searchquery'], ".") !== FALSE) ? "user_ip REGEXP('".$_POST['searchquery']."') OR ": "";
            foreach($search_display as $disp){
            	$query .= "$disp REGEXP('".$_POST['searchquery']."') OR ";
			}
			$query .= "user_login REGEXP('".$_POST['searchquery']."') OR ";
			$query .= "user_name REGEXP('".$_POST['searchquery']."') ORDER BY user_id";
		} else {
			$query = "ORDER BY ".($sub_action ? $sub_action : "user_id")." ".($id ? $id : "DESC")."  LIMIT $from, $amount";
		}

        //  $user_total = db_Count($table, $fields = '(*)',
        $qry_insert = "SELECT u.*, ue.* FROM #user AS u	LEFT JOIN #user_extended AS ue ON ue.user_extended_id = u.user_id ";

		if ($user_total = $sql->db_Select_gen($qry_insert. $query)) {
			$text .= "<table class='fborder' style='width: 99%'>
				<tr>
				<td style='width:5%' class='fcaption'><a href='".e_SELF."?main.user_id.".($id == "desc" ? "asc" : "desc").".$from'>ID</a></td>
				<td style='width:10%' class='fcaption'><a href='".e_SELF."?main.user_ban.".($id == "desc" ? "asc" : "desc").".$from'>".USRLAN_79."</a></td>";


// Search Display Column header.
	$display_lan = array('user_id' => DUSRLAN_1, 
	'user_id' => DUSRLAN_1, 
	'user_name' => DUSRLAN_2, 
	'user_loginname' => DUSRLAN_3, 
	'user_customtitle' => DUSRLAN_4, 
	'user_password' => DUSRLAN_5, 
	'user_sess' => DUSRLAN_6, 
	'user_email' => DUSRLAN_7, 
	'user_homepage' => DUSRLAN_8, 
	'user_icq' => DUSRLAN_9, 
	'user_aim' => DUSRLAN_10, 
	'user_msn' => DUSRLAN_11, 
	'user_location' => DUSRLAN_12, 
	'user_birthday' => DUSRLAN_13, 
	'user_signature' => DUSRLAN_14, 
	'user_image' => DUSRLAN_15, 
	'user_timezone' => DUSRLAN_16, 
	'user_hideemail' => DUSRLAN_17, 
	'user_join' => DUSRLAN_18, 
	'user_lastvisit' => DUSRLAN_19, 
	'user_currentvisit' => DUSRLAN_20, 
	'user_lastpost' => DUSRLAN_21, 
	'user_chats' => DUSRLAN_22, 
	'user_comments' => DUSRLAN_23, 
	'user_forums' => DUSRLAN_24, 
	'user_ip' => DUSRLAN_25, 
	'user_ban' => DUSRLAN_26, 
	'user_prefs' => DUSRLAN_27, 
	'user_new' => DUSRLAN_28, 
	'user_viewed' => DUSRLAN_29, 
	'user_visits' => DUSRLAN_30, 
	'user_admin' => DUSRLAN_31, 
	'user_login' => DUSRLAN_32, 
	'user_class' => DUSRLAN_33, 
	'user_perms' => DUSRLAN_34, 
	'user_realm' => DUSRLAN_35, 
	'user_pwchange' => DUSRLAN_36,
	'user_xup' => DUSRLAN_37
	);

			foreach($search_display as $disp){
				if (isset($display_lan[$disp])) {
					$text .= "<td style='width:15%' class='fcaption'><a href='".e_SELF."?main.$disp.".($id == "desc" ? "asc" : "desc").".$from'>".$display_lan[$disp]."</a></td>";
				} else {
					$text .= "<td style='width:15%' class='fcaption'><a href='".e_SELF."?main.$disp.".($id == "desc" ? "asc" : "desc").".$from'>".ucwords(str_replace("_"," ",$disp))."</a></td>";
				}
			}

// ------------------------------

			$text .= " 	<td style='width:30%' class='fcaption'>".LAN_OPTIONS."</td>
				</tr>";

			while ($row = $sql->db_Fetch()) {
				extract($row);
				$text .= "<tr>
					<td style='width:5%; text-align:center' class='forumheader3'>$user_id</td>
					<td style='width:10%' class='forumheader3'>";

				if ($user_perms == "0") {
					$text .= "<div class='fcaption' style='padding-left:3px;padding-right:3px;text-align:center;white-space:nowrap'>".LAN_MAINADMIN."</div>";
				}
				else if($user_admin) {
					$text .= "<a href='".e_SELF."?main.user_admin.".($id == "desc" ? "asc" : "desc")."'><div class='fcaption' style='padding-left:3px;padding-right:3px;;text-align:center'>".LAN_ADMIN."</div></a>";
				}
				else if($user_ban == 1) {
					$text .= "<a href='".e_SELF."?main.user_ban.".($id == "desc" ? "asc" : "desc")."'><div class='fcaption' style='padding-left:3px;padding-right:3px;text-align:center;white-space:nowrap'>".LAN_BANNED."</div></a>";
				}
				else if($user_ban == 2) {
					$text .= "<div class='fcaption' style='padding-left:3px;padding-right:3px;text-align:center;white-space:nowrap' >".LAN_NOTVERIFIED."</div>";
				} else {
					$text .= "&nbsp;";
				}

				$text .= "</td>";



 // Display Chosen options -------------------------------------

	$datefields = array("user_lastpost","user_lastvisit","user_join","user_currentvisit");

	foreach($search_display as $disp)
	{
		$text .= "<td style='white-space:nowrap' class='forumheader3'>";
		if($disp == "user_class")
		{
			if ($user_class)
			{
				$tmp = explode(",", $user_class);
				while (list($key, $class_id) = each($tmp))
				{
					$text .= ($class[$class_id] ? $class[$class_id]."<br />\n" : "");
				}
			}
			else
			{
				$text .= "&nbsp;";
			}
		}
		elseif(in_array($disp,$datefields))
		{
			$text .= ($row[$disp]) ? strftime($pref['shortdate'],$row[$disp])."&nbsp;" : "&nbsp";
		}
		elseif($disp == "user_name")
		{
			$text .= "<a href='".e_BASE."user.php?id.{$row['user_id']}'>{$row['user_name']}</a>";
		}
		else
		{
			$text .= $row[$disp]."&nbsp;";
		}
		if(isset($prev[$disp]) && $row[$disp] == $prev[$disp] && $prev[$disp] != "")
		{ // show matches
			$text .= " <b>*</b>";
		}

		$text .= "</td>";
		$prev[$disp] = $row[$disp];
	}
// -------------------------------------------------------------
				$qry = (e_QUERY) ?  "?".e_QUERY : "";
				$text .= "
					<td style='width:30%;text-align:center' class='forumheader3'>
					<form method='post' action='".e_SELF.$qry."'>
					<div>

					<input type='hidden' name='userid' value='{$user_id}' />
					<input type='hidden' name='userip' value='{$user_ip}' />
					<select name='useraction' onchange='this.form.submit()' class='tbox' style='width:75%'>
					<option selected='selected' value=''></option>";

				if ($user_perms != "0") {
					$text .= "<option value='userinfo'>".USRLAN_80."</option>
						<option value='usersettings'>".LAN_EDIT."</option>";

					if ($user_ban == 1) {
						$text .= "<option value='unban'>".USRLAN_33."</option>";
					}
					else if($user_ban == 2) {
						$text .= "<option value='ban'>".USRLAN_30."</option>
							<option value='verify'>".USRLAN_32."</option>
							<option value='resend'>".USRLAN_112."</option>
							<option value='test'>".USRLAN_118."</option>";
					} else {
						$text .= "<option value='ban'>".USRLAN_30."</option>";
					}

					if (!$user_admin && !$user_ban && $user_ban != 2) {
						$text .= "<option value='admin'>".USRLAN_35."</option>";
					}
					else if ($user_admin && $user_perms != "0") {
						$text .= "<option value='unadmin'>".USRLAN_34."</option>";
					}

				}
				if ($user_perms == "0" && !getperms("0")) {
					$text .= "";
				} elseif($user_id != USERID || getperms("0") ) {
					$text .= "<option value='userclass'>".USRLAN_36."</option>";
				}

				if ($user_perms != "0") {
					$text .= "<option value='deluser'>".LAN_DELETE."</option>";
				}
				$text .= "</select></div>";
				$text .= "</form></td></tr>";
			}
			$text .= "</table>";
		}
		$text .= "</div>";
		$users = $sql->db_Count("user");

		if ($users > $amount && !$_POST['searchquery']) {
			$parms = "{$users},{$amount},{$from},".e_SELF."?".(e_QUERY ? "$action.$sub_action.$id." : "main.user_id.desc.")."[FROM]";
			$text .= "<br />".LAN_GOPAGE." ".$tp->parseTemplate("{NEXTPREV={$parms}}");
		}

// Search - display options etc. .

		$text .= "<br /><form method='post' action='".e_SELF."?".e_QUERY."'>\n";
		$text .= "<p>\n<input class='tbox' type='text' name='searchquery' size='20' value='' maxlength='50' />\n
		<input class='button' type='submit' name='searchsubmit' value='".USRLAN_90."' />\n
		<br /><br /></p>\n";

		$text .= "<div style='cursor:pointer' onclick=\"expandit('sdisp')\">".LAN_DISPLAYOPT."</div>";
		$text .= "<div  id='sdisp' style='padding-top:4px;display:none;text-align:center;margin-left:auto;margin-right:auto'>
		<table class='forumheader3' style='width:95%'><tr>";
		$fields = mysql_list_fields($mySQLdefaultdb, MPREFIX."user");
		$columns = mysql_num_fields($fields);
		for ($i = 0; $i < $columns; $i++) {
			$fname[] = mysql_field_name($fields, $i);
		}

		// include extended fields in the list.
        $sql -> db_Select("user_extended_struct");
            while($row = $sql-> db_Fetch()){
            $fname[] = "user_".$row['user_extended_struct_name'];
		}
        $m = 0;
		foreach($fname as $fcol){
        $checked = (in_array($fcol,$search_display)) ? "checked='checked'" : "";
			$text .= "<td style='text-align:left; padding:0px'>";
			$text .= "<input type='checkbox' name='searchdisp[]' value='".$fcol."' $checked />".str_replace("user_","",$fcol) . "</td>\n";
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
		$caption = USRLAN_77 ."&nbsp;&nbsp;   (total: $user_total)";
		$ns->tablerender($caption, $text);

	}

	function show_options($action) {
		// ##### Display options ---------------------------------------------------------------------------------------------------------
		if ($action == "") {
			$action = "main";
		}
		// ##### Display options ---------------------------------------------------------------------------------------------------------
		$var['main']['text'] = USRLAN_71;
		$var['main']['link'] = e_SELF;

		$var['create']['text'] = USRLAN_72;
		$var['create']['link'] = e_SELF."?create";

		$var['prune']['text'] = USRLAN_73;
		$var['prune']['link'] = e_SELF."?prune";

		$var['options']['text'] = LAN_OPTIONS;
		$var['options']['link'] = e_SELF."?options";

		//  $var['mailing']['text']= USRLAN_121;
		//   $var['mailing']['link']="mailout.php";
		show_admin_menu(USRLAN_76, $action, $var);
	}

	function show_prefs() {
		global $ns, $pref;
		$text = "<div style='text-align:center'>
			<form method='post' action='".e_SELF."?".e_QUERY."'>
			<table style='".ADMIN_WIDTH."' class='fborder'>

			<tr>
			<td style='width:50%' class='forumheader3'>".USRLAN_44.":</td>
			<td style='width:50%' class='forumheader3'>". ($pref['avatar_upload'] ? "<input name='avatar_upload' type='radio' value='1' checked='checked' />".LAN_YES."&nbsp;&nbsp;<input name='avatar_upload' type='radio' value='0' />".LAN_NO : "<input name='avatar_upload' type='radio' value='1' />".LAN_YES."&nbsp;&nbsp;<input name='avatar_upload' type='radio' value='0' checked='checked' />".LAN_NO). (!FILE_UPLOADS ? " <span class='smalltext'>(".USRLAN_58.")</span>" : "")."
			</td>
			</tr>

			<tr>
			<td style='width:50%' class='forumheader3'>".USRLAN_53.":</td>
			<td style='width:50%' class='forumheader3'>". ($pref['photo_upload'] ? "<input name='photo_upload' type='radio' value='1' checked='checked' />".LAN_YES."&nbsp;&nbsp;<input name='photo_upload' type='radio' value='0' />".LAN_NO : "<input name='photo_upload' type='radio' value='1' />".LAN_YES."&nbsp;&nbsp;<input name='photo_upload' type='radio' value='0' checked='checked' />".LAN_NO). (!FILE_UPLOADS ? " <span class='smalltext'>(".USRLAN_58.")</span>" : "")."
			</td>
			</tr>

			<tr>
			<td style='width:50%' class='forumheader3'>".USRLAN_47.":</td>
			<td style='width:50%' class='forumheader3'>
			<input class='tbox' type='text' name='im_width' size='10' value='".$pref['im_width']."' maxlength='5' /> (".USRLAN_48.")
			</td></tr>

			<tr>
			<td style='width:50%' class='forumheader3'>".USRLAN_49.":</td>
			<td style='width:50%' class='forumheader3'>
			<input class='tbox' type='text' name='im_height' size='10' value='".$pref['im_height']."' maxlength='5' /> (".USRLAN_50.")
			</td></tr>

			<tr>
			<td style='width:50%' class='forumheader3'>".USRLAN_126.":</td>
			<td style='width:50%;vertical-align:top' class='forumheader3'>". ($pref['profile_rate'] ? "<input name='profile_rate' type='radio' value='1' checked='checked' />".LAN_YES."&nbsp;&nbsp;<input name='profile_rate' type='radio' value='0' />".LAN_NO : "<input name='profile_rate' type='radio' value='1' />".LAN_YES."&nbsp;&nbsp;<input name='profile_rate' type='radio' value='0' checked='checked' />".LAN_NO)."
			</td>
			</tr>

			<tr>
			<td style='width:50%' class='forumheader3'>".USRLAN_127.":</td>
			<td style='width:50%;vertical-align:top' class='forumheader3'>". ($pref['profile_comments'] ? "<input name='profile_comments' type='radio' value='1' checked='checked' />".LAN_YES."&nbsp;&nbsp;<input name='profile_comments' type='radio' value='0' />".LAN_NO : "<input name='profile_comments' type='radio' value='1' />".LAN_YES."&nbsp;&nbsp;<input name='profile_comments' type='radio' value='0' checked='checked' />".LAN_NO)."
			</td>
			</tr>

			<tr>
			<td style='width:50%;vertical-align:top' class='forumheader3'>".USRLAN_133.":<br /><span class='smalltext'>".USRLAN_134."</span></td>
			<td style='width:50%;vertical-align:top' class='forumheader3'>". ($pref['force_userupdate'] ? "<input name='force_userupdate' type='radio' value='1' checked='checked' />".LAN_YES."&nbsp;&nbsp;<input name='force_userupdate' type='radio' value='0' />".LAN_NO : "<input name='force_userupdate' type='radio' value='1' />".LAN_YES."&nbsp;&nbsp;<input name='force_userupdate' type='radio' value='0' checked='checked' />".LAN_NO)."
			</td>
			</tr>


			<tr>
			<td style='width:50%;vertical-align:top' class='forumheader3'>".USRLAN_93."<br /><span class='smalltext'>".USRLAN_94."</span></td>
			<td style='width:50%' class='forumheader3'>
			<input class='tbox' type='text' name='del_unv' size='10' value='".$pref['del_unv']."' maxlength='5' /> ".USRLAN_95."
			</td></tr>

			<tr>
			<td style='width:50%' class='forumheader3'>".USRLAN_130."<br /><span class='smalltext'>".USRLAN_131."</span></td>
			<td style='width:50%; class='forumheader3'>&nbsp;
			<input type='checkbox' name='track_online' value='1'".($pref['track_online'] ? " checked='checked'" : "")." /> ".USRLAN_132."&nbsp;&nbsp;
			</td>
			</tr>

			<tr>
			<td colspan='2' style='text-align:center' class='forumheader'>
			<input class='button' type='submit' name='update_options' value='".USRLAN_51."' />
			</td></tr>

			</table></form></div>";
		$ns->tablerender(USRLAN_52, $text);
	}

	function show_message($message) {
		global $ns;
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	function show_prune() {
		global $ns, $sql;

		$unactive = $sql->db_Select("user", "*", "user_ban=2");
		$text = "<div style='text-align:center'>".USRLAN_84." ".$unactive." ".USRLAN_85."<br /><br />
			<form method='post' action='".e_SELF."'>
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td class='forumheader3' style='text-align:center'>
			<input class='button' type='submit' name='prune' value='".USRLAN_54."' />
			</td>
			</tr>
			</table>
			</form>
			</div>";
		$ns->tablerender(USRLAN_55, $text);
	}

	function add_user() {
		global $rs, $ns;
		$text = "<div style='text-align:center'>". $rs->form_open("post", e_SELF, "adduserform")."
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td style='width:30%' class='forumheader3'>".USRLAN_61."</td>
			<td style='width:70%' class='forumheader3'>
			".$rs->form_text("name", 40, "", 30)."
			</td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".USRLAN_128."</td>
			<td style='width:70%' class='forumheader3'>
			".$rs->form_text("loginname", 40, "", 30)."
			</td>
			</tr>

			<tr>
			<td style='width:30%' class='forumheader3'>".USRLAN_62."</td>
			<td style='width:70%' class='forumheader3'>
			".$rs->form_password("password1", 40, "", 20)."
			</td>
			</tr>
			<tr>
			<td style='width:30%' class='forumheader3'>".USRLAN_63."</td>
			<td style='width:70%' class='forumheader3'>
			".$rs->form_password("password2", 40, "", 20)."
			</td>
			</tr>
			<tr>
			<td style='width:30%' class='forumheader3'>".USRLAN_64."</td>
			<td style='width:70%' class='forumheader3'>
			".$rs->form_text("email", 60, "", 100)."
			</td>
			</tr>";


		if (!is_object($sql)) $sql = new db;
		if ($sql->db_Select("userclass_classes")) {
			$text .= "<tr style='vertical-align:top'>
				<td colspan='2' style='text-align:center' class='forumheader'>
				".USRLAN_120."
				</td>
				</tr>";
			$c = 0;
			while ($row = $sql->db_Fetch()) {
				$class[$c][0] = $row['userclass_id'];
				$class[$c][1] = $row['userclass_name'];
				$class[$c][2] = $row['userclass_description'];
				$c++;
			}
			for($a = 0; $a <= (count($class)-1); $a++) {
				$text .= "<tr><td style='width:30%' class='forumheader'>
					<input type='checkbox' name='userclass[]' value='".$class[$a][0]."' />".$class[$a][1]."
					</td><td style='width:70%' class='forumheader3'> ".$class[$a][2]."</td></tr>";
			}
		}
		$text .= "
			<tr style='vertical-align:top'>
			<td colspan='2' style='text-align:center' class='forumheader'>
			<input class='button' type='submit' name='adduser' value='".USRLAN_60."' />
			<input type='hidden' name='ac' value='".md5(ADMINPWCHANGE)."' />
			</td>
			</tr>
			</table>
			</form>
			</div>
			";

		$ns->tablerender(USRLAN_59, $text);
	}

}
function users_adminmenu() {
	global $user;
	global $action;
	$user->show_options($action);
}
?>