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
|     $Source: /cvs_backup/e107_0.7/e107_admin/mailout.php,v $
|     $Revision: 1.24 $
|     $Date: 2005-04-30 22:48:26 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

require_once("../class2.php");
$e_sub_cat = 'mail';
$e_wysiwyg = "email_body";
set_time_limit(180);

require_once(e_ADMIN."auth.php");
if (!getperms("W")) {
	header("location:".e_BASE."index.php");
	 exit;
}
require_once(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_users.php");
require_once(e_HANDLER."userclass_class.php");

if ($pref['htmlarea']) {
	require_once(e_HANDLER."htmlarea/htmlarea.inc.php");
	htmlarea('email_body');
}


if (isset($_POST['testemail'])) {
	require_once(e_HANDLER."mail.php");
	if (!sendemail(SITEADMINEMAIL, PRFLAN_66." ".SITENAME, PRFLAN_67)) {
		$message = ($pref['smtp_enable'] ? PRFLAN_75 : PRFLAN_68);
	} else {
		$message = PRFLAN_69;
	}
}

if (isset($_POST['submit'])) {

	if ($_POST['email_to'] == "all" || $_POST['email_to'] == "unverified" || $_POST['email_to'] == "admin") {

		switch ($_POST['email_to']) {
			case "admin":
				$insert = "user_admin='1' ";
			break;
			case "unverified":
				$insert = "user_ban='2' ";
			break;
			case "all":
				$insert = "user_ban='0' ";
			break;
		}


		// send to all.
		$sql->db_Select("user", "user_id,user_name, user_email, user_class, user_sess", " $insert ORDER BY user_name");
		$c = 0;
		while ($row = $sql->db_Fetch()) {
			extract($row);
			$recipient_name[$c] = $user_name;
			$recipient[$c] = $user_email;
			$recipient_key[$c] = $user_sess;
			$recipient_id[$c] = $user_id;
			$c++;
		}
	} else {
		// send to a user-class.

		$sql->db_Select("user", "user_id,user_name, user_email, user_class", "ORDER BY user_name", "no-where");
		$c = 0;
		while ($row = $sql->db_Fetch()) {
			extract($row);
			if (check_class($_POST['email_to'], $user_class)) {
				$recipient_name[$c] = $user_name;
				$recipient[$c] = $user_email;
				$recipient_key[$c] = $user_sess;
				$recipient_id[$c] = $user_id;
				$c++;
			}

		}
		// end while

	}

	// ===== phpmailer version.

	require(e_HANDLER."phpmailer/class.phpmailer.php");

	$mail = new PHPMailer();

	$mail->From = ($_POST['email_from_email'])? $_POST['email_from_email']:	$pref['siteadminemail'];
	$mail->FromName = ($_POST['email_from_name'])? $_POST['email_from_name']: $pref['siteadmin'];
	//  $mail->Host     = "smtp1.site.com;smtp2.site.com";
	if ($pref['smtp_enable']) {
		$mail->Mailer = "smtp";
		$mail->SMTPKeepAlive = TRUE;
		$mail->SMTPAuth = TRUE;
		$mail->Username = $pref['smtp_username'];
		$mail->Password = $pref['smtp_password'];
		$mail->Host = $pref['smtp_server'];
	} else {
		$mail->Mailer = "mail";
	}

	$mail->AddCC = ($_POST['email_cc']);
	$mail->WordWrap = 50;
	$mail->Charset = CHARSET;
	$mail->Subject = $_POST['email_subject'];
	$mail->IsHTML(true);

	$attach = chop($_POST['email_attachment']);

	$attach_link = e_DOWNLOAD.$attach;
	echo $attach_link;
	
	if ($attach != "" && !$mail->AddAttachment($attach_link, $attach))
	{
		$mss = "There is a problem with the attachment<br />$attach_link";
		$ns->tablerender("Error", $mss);
		require_once(e_ADMIN."footer.php");
		exit;
	}
	// ============================  Render Results and Mailit =========

	$text = "<div style='overflow:auto;height:300px; ".ADMIN_WIDTH."'>";
	$text .= "<table class='fborder' style='width:100%'>";
	$text .= "<tr><td class='fcaption'>Username</td><td class='fcaption'>Email</td><td class='fcaption'>Status</td></tr>";
	$message_subject = stripslashes($_POST['email_subject']);
	$message_body = stripslashes($_POST['email_body']);
	$message_body = eregi_replace('src="', 'src="'.SITEURL, $message_body);

	if (isset($_POST['use_theme'])) {
		$theme = $THEMES_DIRECTORY.$pref['sitetheme']."/";
		$mail_style = "<link rel=\"stylesheet\" href=\"".SITEURL.$theme."style.css\" type=\"text/css\" />";
		$mail_style .= "<div style='text-align:center; width:100%'>";
		$mail_style .= "<div style='width:90%;text-align:center;padding-top:10px'>";
		$mail_style .= "<div class='fcaption' style='text-align:center'><b>$message_subject</b></div>";
		$mail_style .= "<div class='forumheader3' style='text-align:left;'>";
		$message_body = $mail_style.$message_body."<br><br><br></div></div>";
	}


	$sent_no = 0;

	for ($i = 0; $i < count($recipient); $i++) {

		// --- start loop ----

		$text .= "<tr>";
		$text .= "<td class='forumheader3' style='width:40%'>".$recipient_name[$i]."</td>";
		$text .= "<td class='forumheader3' style='width:40%'>".$recipient[$i]."</td>";

		$mes_body = str_replace("{USERNAME}", $recipient_name[$i], $message_body);
		$mes_body = str_replace("{USERID}", $recipient_id[$i], $mes_body);

		$activator = (substr(SITEURL, -1) == "/" ? SITEURL."signup.php?activate.".$recipient_id[$i].".".$recipient_key[$i] : SITEURL."/signup.php?activate.".$recipient_id[$i].".".$recipient_key[$i]);
		if($recipient_key[$i]){
			$mes_body = str_replace("{SIGNUP_LINK}", "<a href='$activator'>$activator</a>", $mes_body);
		}else{
			$mes_body = str_replace("{SIGNUP_LINK}", "", $mes_body);
		}

		$mes_body = str_replace("\n", "<br>", $mes_body);

		$mail->Body = $tp->toHTML($mes_body);
		$mail->AltBody = strip_tags(str_replace("<br>", "\n", $mes_body));
		$mail->AddAddress($recipient[$i], $recipient_name[$i]);



		if ($mail->Send()) {
			$stat = "<span style='color:green'>Sent</span>";
			$sent_no ++;
		} else {
			$stat = "<span style='color:red'>Error</span>";
		}
		$text .= "<td class='forumheader3'>&nbsp;&nbsp; $stat </td></tr>";



		$mail->ClearAddresses();
		if ($pref['smtp_enable']) {
			$mail->SmtpClose();
		}


		// ---- end loop. ---

	};

	$mail->ClearAttachments();


	$text .= "</table></div>";
	$rec_text = $c > 1 ? "recipients":
	"recipient";

	if ($c == 0 ) {
		$text = "<div style='text-align:center'>No Recipients Found</div>";
	}

	$ns->tablerender("Emailing $c $rec_text", $text);
	require_once(e_ADMIN."footer.php");
	exit;
}
//. Update Preferences.

if (isset($_POST['updateprefs'])) {

	$pref['smtp_enable'] = $_POST['smtp_enable'];
	$pref['smtp_server'] = $tp->toDB($_POST['smtp_server']);
	$pref['smtp_username'] = $tp->toDB($_POST['smtp_username']);
	$pref['smtp_password'] = $tp->toDB($_POST['smtp_password']);

	save_prefs();
	$message = LAN_SETSAVED;
}


if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

// Display Mailout Form.



$text = "";
$text .= ($pref['smtp_enable'] == 0)? "<div style='text-align:center'>".MAILAN_14."<br /><br /></div>":
"";
$text .= "<div style='text-align:center'>
	<form method='post' action='".e_SELF."' id='linkform'>
	<table style='".ADMIN_WIDTH."' class='fborder'>
	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_01.": </td>
	<td style='width:70%' class='forumheader3'>
	<input type='text' name='email_from_name' class='tbox' style='width:80%' value='$email_from_name' />
	</td>
	</tr>

	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_02.": </td>
	<td style='width:70%' class='forumheader3'>
	<input type='text' name='email_from_email' class='tbox' style='width:80%' value='$email_from_email' />
	</td>
	</tr>

	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_03.": </td>
	<td style='width:70%' class='forumheader3'>
	".userclasses("email_to", $email_to)."</td>
	</tr>";

$text .= "

	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_04.": </td>
	<td style='width:70%' class='forumheader3'>
	<input type='text' name='email_cc' class='tbox' style='width:80%' value='$email_cc' />

	</td>
	</tr>


	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_05.": </td>
	<td style='width:70%' class='forumheader3'>
	<input type='text' name='email_bcc' class='tbox' style='width:80%' value='$email_bcc' />

	</td>
	</tr>

	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_06.": </td>
	<td style='width:70%' class='forumheader3'>
	<input type='text' name='email_subject' class='tbox' style='width:80%' value='$email_subject' />

	</td>
	</tr>";


// Attachment.

$text .= "<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_07.": </td>
	<td style='width:70%' class='forumheader3'>";
$text .= "<select class='tbox' name='email_attachment' >
	<option></option>";
$sql->db_Select("download", "download_url,download_name", "download_id !='' ORDER BY download_name");
while ($row = $sql->db_Fetch()) {
	extract($row);
	$selected = ($_POST['email_attachment'] == $download_url) ? "selected='selected'" :
	 "";
	$text .= "<option value=\"$download_url \" $selected>$download_name</option>";
}
$text .= " </select>";

$text .= "</td>
	</tr>";


$text .= "
	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_09.": </td>
	<td style='width:70%' class='forumheader3'>
	<input type='checkbox' name='use_theme' value='1' />
	</td>
	</tr>



	<tr>
	<td colspan='2' style='width:30%' class='forumheader3'>

	<textarea rows='10' cols='20' id='email_body' name='email_body'  class='tbox' style='width:100%;height:200px' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>
	$email_body
	</textarea>
	</td>
	</tr>

	<tr>
	<td style='width:30%' class='forumheader3'>".MAILAN_11.": </td>
	<td style='width:70%' class='forumheader3'>";

if($pref['wysiwyg']){
	$text .="<input type='button' class='button' name='usrname' value='username' onclick=\"tinyMCE.selectedInstance.execCommand('mceInsertContent',0,'{USERNAME}')\" />
	<input type='button' class='button' name='usrlink' value='signup link' onclick=\"tinyMCE.selectedInstance.execCommand('mceInsertContent',0,'{SIGNUP_LINK}')\" />
	<input type='button' class='button' name='usrid' value='user id' onclick=\"tinyMCE.selectedInstance.execCommand('mceInsertContent',0,'{USERID}')\" />";
}else{
    $text .="<input type='button' class='button' name='usrname' value='username' onclick=\"addtext('{USERNAME}')\" />
	<input type='button' class='button' name='usrlink' value='signup link' onclick=\"addtext('{SIGNUP_LINK}')\" />
	<input type='button' class='button' name='usrid' value='user id' onclick=\"addtext('{USERID}')\" />";
}


	$text .="</td>
	</tr>";



$text .= "<tr style='vertical-align:top'>
	<td colspan='2' style='text-align:center' class='forumheader'>";
$text .= "<input class='button' type='submit' name='submit' value='".MAILAN_08."' />";
$text .= "</td>
	</tr>
	</table>
	</form>
	</div>";

$ns->tablerender(MAILAN_15, $text);




$text = "
	<form method='post' action='".e_SELF."' id='mailsettingsform'>
	<div id='mail' style='text-align:center;'>
	<table style='".ADMIN_WIDTH."' class='fborder'>
	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_63."<br /><span class='smalltext'>".PRFLAN_64."</span></td>
	<td style='width:50%; text-align:right' class='forumheader3'><input class='button' type='submit' name='testemail' value='".PRFLAN_65." ".SITEADMINEMAIL."' />
	</td>
	</tr>

	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_70."<br /><span class='smalltext'>".PRFLAN_71."</span></td>
	<td style='width:50%; text-align:right' class='forumheader3'>". ($pref['smtp_enable'] ? "<input type='checkbox' name='smtp_enable' value='1' checked='checked' />" : "<input type='checkbox' name='smtp_enable' value='1' />")." </td>
	</tr>


	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_72.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='smtp_server' size='30' value='".$pref['smtp_server']."' maxlength='50' />
	</td>
	</tr>

	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_73.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='text' name='smtp_username' size='30' value='".$pref['smtp_username']."' maxlength='50' />
	</td>
	</tr>

	<tr>
	<td style='width:50%' class='forumheader3'>".PRFLAN_74.": </td>
	<td style='width:50%; text-align:right' class='forumheader3'>
	<input class='tbox' type='password' name='smtp_password' size='30' value='".$pref['smtp_password']."' maxlength='50' />
	</td>
	</tr>

	<tr>
	<td style='text-align:center' colspan='2' class='forumheader'>
	<input class='button' type='submit' name='updateprefs' value='".PRFLAN_52."' />
	</td>
	</tr>





	</table></div></form>";

$text .= "";
$caption = LAN_PREFS;
$ns->tablerender($caption, $text);



require_once(e_ADMIN."footer.php");






function userclasses($name) {
	global $sql;
	$text .= "<select style='width:80%' class='tbox' name='$name' >
		<option value='all'>".MAILAN_12."</option>
		<option value='unverified'>".MAILAN_13."</option>
		<option value='admin'>Admins</option>";
	$sql->db_Select("userclass_classes");
	while ($row = $sql->db_Fetch()) {
		extract($row);
		$public = ($userclass_editclass == 0)? "(".MAILAN_10.")" :
		 "";
		$text .= "<option value=\"$userclass_id\" $selected>Userclass - $userclass_name  $public</option>";
	}
	$text .= " </select>";

	return $text;
}


function show_options($action) {
	// ##### Display options ---------------------------------------------------------------------------------------------------------
	/*   if($action==""){$action="main";}
	// ##### Display options ---------------------------------------------------------------------------------------------------------
	$var['main']['text']= "Mails Prefs";//USRLAN_71;
	$var['main']['link']= "mailout.php";

	$var['create']['text']="Mail-Out";USRLAN_72;
	$var['create']['link']="mailout.php?mailout";

	$var['prune']['text']=USRLAN_73;
	$var['prune']['link']="users.php?prune";

	$var['extended']['text']=USRLAN_74;
	$var['extended']['link']="users.php?extended";

	$var['options']['text']=USRLAN_75;
	$var['options']['link']="users.php?options";

	$var['mailing']['text']= USRLAN_121;
	$var['mailing']['link']="mailout.php";*/
	//   show_admin_menu(USRLAN_76,$action,$var);
}

/*function mailout_adminmenu(){
global $user;
global $action;
$action = "mailing";
show_options($action);
}*/


?>