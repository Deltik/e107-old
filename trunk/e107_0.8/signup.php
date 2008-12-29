<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * User signup
 *
 * $Source: /cvs_backup/e107_0.8/signup.php,v $
 * $Revision: 1.30 $
 * $Date: 2008-12-29 09:31:36 $
 * $Author: e107steved $
 *
*/


require_once("class2.php");
$qs = explode(".", e_QUERY);
if($qs[0] != 'activate')
{   // multi-language fix.
	include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_signup.php");
//	include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_usersettings.php");		Shouldn't need this now
}
include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/lan_user.php');		// Generic user-related language defines

define('SIGNUP_DEBUG', FALSE);

include_once(e_HANDLER.'user_extended_class.php');
$ue = new e107_user_extended;
require_once(e_HANDLER.'calendar/calendar_class.php');
$cal = new DHTML_Calendar(true);
require_once(e_HANDLER.'validator_class.php');
require_once(e_HANDLER.'user_handler.php');
$userMethods = new UserHandler;
$userMethods->deleteExpired();				// Delete time-expired partial registrations

if (is_readable(THEME."signup_template.php")) 
{
	require_once(THEME."signup_template.php");
} 
else 
{
	require_once(e_THEME."templates/signup_template.php");
}

include_once(e_FILE."shortcode/batch/signup_shortcodes.php");

$signup_imagecode = ($pref['signcode'] && extension_loaded("gd"));
$text = '';
$extraErrors = array();
$error = FALSE;


//-------------------------------
// Resend Activation Email 
//-------------------------------
if(e_QUERY == "resend" && !USER && ($pref['user_reg_veri'] == 1))
{
	include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE);
	require_once(HEADERF);

	$clean_email = $tp -> toDB($_POST['resend_email']);
    if(!check_email($clean_email))
	{
		$clean_email = "xxx";
	}

    $new_email = $tp -> toDB(varset($_POST['resend_newemail'], ''));
    if(!check_email($new_email ))
	{
    	$new_email = FALSE;
	}

	if($_POST['submit_resend'])
	{	// Action user's submitted information
		// 'resend_email' - user name or email address actually used to sign up
		// 'resend_newemail' - corrected email address
		// 'resend_password' - password (required if changing email address)

		if($_POST['resend_email'] && !$new_email && $clean_email && $sql->db_Select_gen("SELECT * FROM #user WHERE user_ban=0 AND user_sess='' AND (`user_loginname`= '".$clean_email."' OR `user_name` = '".$clean_email."' OR `user_email` = '".$clean_email."' ) "))
		{	// Account already activated
			$ns -> tablerender(LAN_SIGNUP_40,LAN_SIGNUP_41."<br />");
			require_once(FOOTERF);
			exit;
		}


		// Start by looking up the user
		if(!$sql->db_Select("user", "*", "(`user_loginname` = '".$clean_email."' OR `user_name` = '".$clean_email."' OR `user_email` = '".$clean_email."' ) AND `user_ban`=".USER_REGISTERED_NOT_VALIDATED." AND `user_sess` !='' LIMIT 1"))
		{
			require_once(e_HANDLER."message_handler.php");
			message_handler("ALERT",LAN_SIGNUP_64.': '.$clean_email); // email (or other info) not valid.
			exit;
		}
		$row = $sql -> db_Fetch();
		// We should have a user record here
	
		if(trim($_POST['resend_password']) !="" && $new_email)
		{  // Need to change the email address - check password to make sure
			if ($userMethods->CheckPassword($_POST['resend_password'], $row['user_loginname'], $row['user_password']) === TRUE)
			{
				if($sql->db_Update("user", "user_email='".$new_email."' WHERE user_id = '".$row['user_id']."' LIMIT 1 "))
				{
					$row['user_email'] = $new_email;
				}
			}
			else
			{
				require_once(e_HANDLER."message_handler.php");
				message_handler("ALERT",LAN_SIGNUP_52); // Incorrect Password.
				exit;
			}
		}
	
		// Now send the email - got some valid info
		$row['user_password'] = 'xxxxxxx';		// Don't know the real one
		$eml = render_email($row);
		$mailheader_e107id = $row['user_id'];
		require_once(e_HANDLER."mail.php");

		$do_log['signup_action'] = LAN_SIGNUP_63;

		if(!sendemail($row['user_email'], $eml['subject'], $eml['message'], $row['user_name'], "", "", $eml['attachments'], $eml['cc'], $eml['bcc'], $returnpath, $returnreceipt,$eml['inline-images']))
		{
			$ns -> tablerender(LAN_ERROR,LAN_SIGNUP_42);
			$do_log['signup_result'] = LAN_SIGNUP_62;
		}
		else
		{
			$ns -> tablerender(LAN_SIGNUP_43,LAN_SIGNUP_44." ".$row['user_email']." - ".LAN_SIGNUP_45."<br /><br />");
			$do_log['signup_result'] = LAN_SIGNUP_61;
		}
		// Now log this (log will ignore if its disabled)
		$admin_log->user_audit(USER_AUDIT_PW_RES,$do_log,$row['user_id'],$row['user_name']);
		require_once(FOOTERF);
		exit;
	}
	elseif(!$_POST['submit_resend'])
	{	// Display form to get info from user
		$text .= "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?resend' name='resend_form'>
		<table style='".USER_WIDTH."' class='fborder'>
		<tr>
			<td class='forumheader3' style='text-align:right'>".LAN_SIGNUP_48."</td>
        <td class='forumheader3'>
		<input type='text' name='resend_email' class='tbox' size='50' style='max-width:80%' value='' maxlength='80' />
		</td>
		</tr>

		<tr>
			<td class='forumheader3' colspan='2'>".LAN_SIGNUP_49."</td>
		</tr>
		<tr>
			<td class='forumheader3' style='text-align:right;width:30%'>".LAN_SIGNUP_50."</td>
			<td class='forumheader3'><input type='text' name='resend_newemail' class='tbox' size='50' style='max-width:80%' value='' maxlength='80' />
		</tr>
		<tr>
			<td class='forumheader3' style='text-align:right'>".LAN_SIGNUP_51."</td>
			<td class='forumheader3'><input type='text' name='resend_password' class='tbox' size='50' style='max-width:80%' value='' maxlength='80' />

		</td>
		</tr>

		";

		$text .="<tr style='vertical-align:top'>
		<td colspan='2' style='text-align:center' class='forumheader'>";
		$text .= "<input class='button' type='submit' name='submit_resend' value=\"".LAN_SIGNUP_47."\" />";  // resend activation email.
		$text .= "</td>
		</tr>
		</table>
		</form>
		</div>";

		$ns -> tablerender(LAN_SIGNUP_47, $text);
		require_once(FOOTERF);
		exit;
	}
    exit;
}

// ------------------------------------------------------------------

if(!$_POST)
{

	$error = "";
	$text = " ";
	$password1 = "";
	$password2 = "";
	$email = "";				// Used in shortcodes
	$loginname = "";
	$realname = "";
	$image = "";
	$avatar_upload = "";
	$photo_upload = "";
	$_POST['ue'] = "";
	$signature = "";
}

if(ADMIN && (e_QUERY == "preview" || e_QUERY == "test"  || e_QUERY == "preview.aftersignup"))
{
	include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE);
	if(e_QUERY == "preview.aftersignup")
	{
		require_once(HEADERF);
		if(trim($pref['signup_text_after']))
		{
			$text = $tp->toHTML($pref['signup_text_after'], TRUE, 'parse_sc,defs')."<br />";
		}
		else
		{
			if ($pref['user_reg_veri'] == 2)
			{
				$text = LAN_SIGNUP_37;
			}
			else
			{
				$text = LAN_SIGNUP_72;
			}
		}
		$ns->tablerender(LAN_SIGNUP_73, $text);
		require_once(FOOTERF);
		exit;
	}

	$temp = array();
	$eml = render_email($temp,TRUE);		// It ignores the data, anyway
	echo $eml['preview'];

	if(e_QUERY == "test")
	{
		require_once(e_HANDLER."mail.php");
		$message = $eml['message'];
		$subj = $eml['subject'];
		$inline = $eml['inline-images'];
		$Cc = $eml['cc'];
		$Bcc = $eml['bcc'];
		$attachments = $eml['attachments'];

		if(!sendemail(USEREMAIL, $subj, $message, USERNAME, "", "", $attachments, $Cc, $Bcc, $returnpath, $returnreceipt,$inline))
		{
			echo "<br /><br /><br /><br >&nbsp;&nbsp;>> ".LAN_SIGNUP_42; // there was a problem.
		}
		else
		{
			echo "<br /><br />&nbsp;&nbsp;>> ".LAN_SIGNUP_43." [ ".USEREMAIL." ] - ".LAN_SIGNUP_45;
		}
	}
	exit;
}

if ($pref['membersonly_enabled'])
{
	$HEADER = "<div style='text-align:center; width:100%;margin-left:auto;margin-right:auto;text-align:center'><div style='width:70%;text-align:center;margin-left:auto;margin-right:auto'><br />";
	if (file_exists(THEME."images/login_logo.png"))
	{
		$HEADER .= "<img src='".THEME."images/login_logo.png' alt='' />\n";
	}
	else
	{
		$HEADER .= "<img src='".e_IMAGE."logo.png' alt='' />\n";
	}
	$HEADER .= "<br />";
	$FOOTER = "</div></div>";
}

if($signup_imagecode)
{
	require_once(e_HANDLER."secure_img_handler.php");
	$sec_img = new secure_image;
}

if($pref['user_reg'] == 0)
{
	header("location: ".e_HTTP."index.php");
	exit;
}

if(USER)
{
	header("location: ".e_HTTP."index.php");
	exit;
}

//----------------------------------------
// After clicking the activation link 
//----------------------------------------
if (e_QUERY)
{
	$qs = explode(".", e_QUERY);
	if ($qs[0] == "activate" && (count($qs) == 3 || count($qs) == 4) && $qs[2])
	{
        // return the message in the correct language.
		if(isset($qs[3]) && strlen($qs[3]) == 2 )
		{
			require_once(e_HANDLER."language_class.php");
			$lng = new language;
			$the_language = $lng->convert($qs[3]);
			if(is_readable(e_LANGUAGEDIR.$the_language."/lan_signup.php"))
			{
				include(e_LANGUAGEDIR.$the_language."/lan_signup.php");
			}
			else
			{
				require_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_signup.php");
 			}
		}
		else
		{
            include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_signup.php");
		}


		$e107cache->clear("online_menu_totals");
		if ($sql->db_Select("user", "*", "user_sess='".$tp -> toDB($qs[2], true)."' "))
		{
			if ($row = $sql->db_Fetch())
			{
				// Set initial classes, and any which the user can opt to join
				$init_classes = '';
				if ($pref['init_class_stage'] == '2')
				{
					$init_classes = explode(',',varset($pref['initial_user_classes'],''));
					if ($init_classes)
					{	// Update the user classes
						$row['user_class'] = $tp->toDB(implode(',',array_unique(array_merge($init_classes, explode(',',$row['user_class'])))));
						$init_classes = ", user_class='".$row['user_class']."' ";
					}
				}
				$sql->db_Update("user", "user_ban='0', user_sess=''{$init_classes} WHERE user_sess='".$tp -> toDB($qs[2], true)."' ");

				// Log to user audit log if enabled
				$admin_log->user_audit(USER_AUDIT_EMAILACK,$row);
				
				$e_event->trigger("userveri", $row);
				require_once(HEADERF);
				$text = LAN_SIGNUP_74." <a href='index.php'>".LAN_SIGNUP_22."</a> ".LAN_SIGNUP_23."<br />".LAN_SIGNUP_24." ".SITENAME;
				$ns->tablerender(LAN_SIGNUP_75, $text);
				require_once(FOOTERF);
				exit;
			}
		}
		else
		{	// Invalid activation code
			header("location: ".e_BASE."index.php");
			exit;
		}
	}
}


//----------------------------------------
// 		Initial signup (registration)
//----------------------------------------
if (isset($_POST['register']))
{
	$_POST['user_xup'] = trim(varset($_POST['user_xup'],''));
	$readXUP = varsettrue($pref['xup_enabled']) && varsettrue($_POST['user_xup']);
	$e107cache->clear("online_menu_totals");
	require_once(e_HANDLER."message_handler.php");
	if (isset($_POST['rand_num']) && $signup_imagecode && !$readXUP )
	{
		if (!$sec_img->verify_code($_POST['rand_num'], $_POST['code_verify']))
		{
			$extraErrors[] = LAN_SIGNUP_3."\\n";
			$error = TRUE;
		}
	}

	if($invalid = $e_event->trigger("usersup_veri", $_POST))
	{
    	$extraErrors[] = $invalid."\\n";
        $error = TRUE;
	}

	if (!$error && $readXUP)
	{
		require_once(e_HANDLER."xml_class.php");
		$xml = new parseXml;
		if(!$rawData = $xml -> getRemoteXmlFile($_POST['user_xup']))
		{
			$extraErrors[] = LAN_SIGNUP_68."\\n";
			$error = TRUE;
		}
		else
		{
			preg_match_all("#\<meta name=\"(.*?)\" content=\"(.*?)\" \/\>#si", $rawData, $match);
			$count = 0;
			foreach($match[1] as $value)
			{
				$xup[$value] = $match[2][$count];
				$count++;
			}
	
			$_POST['name'] = $xup['NICKNAME'];
			$_POST['email'] = $xup['EMAIL'];
			$_POST['email_confirm'] = $xup['EMAIL'];
			$_POST['signature'] = $xup['SIG'];
			$_POST['hideemail'] = $xup['EMAILHIDE'];
			$_POST['realname'] = $xup['FN'];
			$_POST['image'] = $xup['AV'];
	
			$_POST['ue']['user_timezone'] = $xup['TZ'];
			$_POST['ue']['user_homepage'] = $xup['URL'];
			$_POST['ue']['user_icq'] = $xup['ICQ'];
			$_POST['ue']['user_aim'] = $xup['AIM'];
			$_POST['ue']['user_msn'] = $xup['MSN'];
			$_POST['ue']['user_yahoo'] = $xup['YAHOO'];
			$_POST['ue']['user_location'] = $xup['GEO'];
			$_POST['ue']['user_birthday'] = $xup['BDAY'];
	
			unset($xup);
			if($_POST['loginnamexup']) $_POST['loginname'] = $_POST['loginnamexup'];
			if($_POST['password1xup']) $_POST['password1'] = $_POST['password1xup'];
			if($_POST['password2xup']) $_POST['password2'] = $_POST['password2xup'];
		}
	}

	if (!$error)
	{
		if (varsettrue($pref['predefinedLoginName']))
		{
		  $_POST['loginname'] = $userMethods->generateUserLogin($pref['predefinedLoginName']);
		}
	
		// Use LoginName for DisplayName if restricted
		if (!check_class($pref['displayname_class'],e_UC_PUBLIC.','.e_UC_MEMBER))
		{
			$_POST['username'] = $_POST['loginname'];
		}
	
		// Now validate everything
		$allData = validatorClass::validateFields($_POST,$userMethods->userVettingInfo, TRUE);		// Do basic validation
		validatorClass::checkMandatory('user_name,user_loginname', $allData);						// Check for missing fields (email done in userValidation() )
		validatorClass::dbValidateArray($allData, $userMethods->userVettingInfo, 'user', 0);		// Do basic DB-related checks
		$userMethods->userValidation($allData);														// Do user-specific DB checks
		if (!isset($allData['errors']['user_password']))
		{	// No errors in password - keep it outside the main data array
			$savePassword = $allData['validate']['user_password'];
			unset($allData['validate']['user_password']);						// Delete the password value in the output array
		}
		unset($_POST['password1']);					// Restrict the scope of this
		unset($_POST['password2']);
	
		$allData['user_ip'] = $e107->getip();
	
	
		// check for multiple signups from the same IP address.
		if($ipcount = $sql->db_Select('user', '*', "user_ip='".$allData['user_ip']."' and user_ban !='2' "))
		{
			if($ipcount >= $pref['signup_maxip'] && trim($pref['signup_maxip']) != "")
			{
				$allData['errors']['user_email'] = ERR_GENERIC;
				$allData['errortext']['user_email'] =  LAN_SIGNUP_71;
			}
		}
	
		// Email address confirmation.
		if (!isset($allData['errors']['user_email']))
		{	// Obviously nothing wrong with the email address so far (or maybe its not required)
			if ($_POST['email'] != $_POST['email_confirm'])
			{
				$allData['errors']['user_email'] = ERR_GENERIC;
				$allData['errortext']['user_email'] =  LAN_SIGNUP_38;
				unset($allData['validate']['user_email']);
			}
		}
	
	
		// Verify Custom Signup options if selected - need specific loop since the need for them is configuration-dependent
		$signup_option_title = array(LAN_USER_63, LAN_USER_71, LAN_USER_72, LAN_USER_73, LAN_USER_74);
		$signup_option_names = array('realname', 'signature', 'image', 'class', 'customtitle');
	
		foreach($signup_option_names as $key => $value)
		{
			if ($pref['signup_option_'.$value] == 2 && !isset($alldata['validate']['user_'.$value]) && !isset($alldata['errors']['user_'.$value]))
			{
				$alldata['errors']['user_'.$value] = ERR_GENERIC;
				$alldata['errortext']['user_'.$value] = str_replace('--SOMETHING--',$signup_option_title[$key],LAN_USER_75);
			}
		}
	
	
		// Validate Extended User Fields.
		$eufVals = array();
		if (isset($_POST['ue']))
		{
			$eufVals = $ue->userExtendedValidateAll($_POST['ue'], varset($_POST['hide'],array()));		// Validate the extended user fields
		}


		// Determine whether we have an error
		$error = ((isset($allData['errors']) && count($allData['errors'])) || (isset($eufVals['errors']) && count($eufVals['errors'])) || count($extraErrors));

		// All validated here - handle any errors
		if ($error)
		{
			require_once(e_HANDLER."message_handler.php");
			$temp = array();
			if (count($extraErrors))
			{
				$temp[] = implode('<br />', $extraErrors);
			}
			if (count($allData['errors']))
			{
				$temp[] = validatorClass::makeErrorList($allData,'USER_ERR_','%n - %x - %t: %v', '<br />', $userMethods->userVettingInfo);
			}
			if (varsettrue($eufData['errors']))
			{
				$temp[] = validatorClass::makeErrorList($eufData,'USER_ERR_','%n - %x - %t: %v', '<br />', $userMethods->userVettingInfo);
			}
			message_handler('P_ALERT', implode('<br />', $temp));
		}
	}		// End of data validation



	// ========== End of verification.. ==============
	// If no errors, we can enter the new member in the DB
	// At this point we have two data arrays:
	//		$allData['validate'] - the 'core' user data
	//		$eufVals['validate'] - any extended user fields

	if (!$error)
	{
		$error_message = '';
		$fp = new floodprotect;
		if ($fp->flood("user", "user_join") == FALSE)
		{
			header("location:".e_BASE."index.php");
			exit;
		}

		if ($_POST['email'] && $sql->db_Select("user", "*", "user_email='".$_POST['email']."' AND user_ban='".USER_BANNED."'")) 
		{
			exit;
		}


		$u_key = md5(uniqid(rand(), 1));					// Key for signup completion
		$allData['validate']['user_sess'] = $u_key;			// Validation key

		// Work out all user classes
		$intClasses = array();
		if (isset($pref['initial_user_classes'])) { $initClasses = explode(',',$pref['initial_user_classes']); }	 // Any initial user classes to be set at some stage
		$classList = array();
		if (isset($allData['validate']['user_class'])) { $classList = explode(',',$allData['validate']['user_class']); }	// Classes entered by user during signup
		if (varsettrue($pref['user_new_period']))
		{
			$classList[] = e_UC_NEWUSER;		// Probationary user class
		}
		if (!$pref['user_reg_veri'] || ($pref['init_class_stage'] == '1'))
		{	// Set initial classes if no verification required, or if selected to add them now
			$classList = array_merge($classList, $initClasses);
		}
		$classList = array_unique($classList);
		if (count($classList))
		{
			$allData['validate']['user_class'] = implode(',',$classList);
		}

		if ($pref['user_reg_veri'])
		{
			$allData['validate']['user_ban'] = USER_REGISTERED_NOT_VALIDATED;
		}
		else
		{
			$allData['validate']['user_ban'] = USER_VALIDATED;
		}
		// Work out data to be written to user audit trail
		$signup_data = array('user_name', 'user_loginname', 'user_email', 'user_ip');
		foreach (array() as $f)
		{
			$signup_data[$f] = $allData['validate'][$f];		// Just copy across selected fields
		}

		$allData['validate']['user_password'] = $userMethods->HashPassword($savePassword,$allData['validate']['user_loginname']);
		if (varsettrue($pref['allowEmailLogin']))
		{  // Need to create separate password for email login
			$allData['validate']['user_prefs'] = serialize(array('email_password' => $userMethods->HashPassword($savePassword, $allData['validate']['user_email'])));
		}

		$allData['validate']['user_join'] = time();

		// Actually write data to DB
		$nid = $sql->db_Insert("user", $allData['validate']);
		if (isset($eufVals['validate']) && count($eufVals['validate']))
		{
			$sql->db_Select_gen("INSERT INTO `#user_extended` (user_extended_id) values ('{$nid}')");
			$sql->db_UpdateArray("user_extended", $eufVals['validate']." WHERE `user_extended_id` = ".intval($nid));
		}
		if (SIGNUP_DEBUG) $admin_log->e_log_event(10,debug_backtrace(),"DEBUG","Signup new user",array_merge($allData['validate'],$eufVals) ,FALSE,LOG_TO_ROLLING);

		// Log to user audit log if enabled
		$signup_data['user_id'] = $nid;
		$signup_data['signup_key'] = $u_key;
		$signup_data['user_realname'] = $tp -> toDB($_POST['realname']);

		$admin_log->user_audit(USER_AUDIT_SIGNUP,$signup_data);

		if (!$nid)
		{
			require_once(HEADERF);
			$ns->tablerender("", LAN_SIGNUP_36);
			require_once(FOOTERF);
		}

		$adviseLoginName = '';
		if (varsettrue($pref['predefinedLoginName']))
		{
			$adviseLoginName = LAN_SIGNUP_65.': '.$allData['validate']['user_loginname'].'<br />'.LAN_SIGNUP_66.'<br />';
		}


		if ($pref['user_reg_veri'])
		{	// Verification required (may be by email or by admin)

			// ========== Send Email =========>
			if (($pref['user_reg_veri'] != 2) && $allData['validate']['user_email'])		// Don't send if email address blank - means that its not compulsory
			{
				$allData['validate']['user_id'] = $nid;					// User ID
				$allData['validate']['user_password'] = $savePassword;	// Might need to send plaintext password in the email
                $eml = render_email($allData['validate']);
				$mailheader_e107id = $eml['userid'];
				require_once(e_HANDLER."mail.php");


				if(!sendemail($_POST['email'], $eml['subject'], $eml['message'], "", "", "", $eml['attachments'], $eml['cc'], $eml['bcc'], "", "", $eml['inline-images']))
				{
					$error_message = LAN_SIGNUP_42; // There was a problem, the registration mail was not sent, please contact the website administrator.
				}
			}
			$e_event->trigger("usersup", $_POST);  // send everything in the template, including extended fields.

			require_once(HEADERF);
			if (isset($pref['signup_text_after']) && (strlen($pref['signup_text_after']) > 2))
			{
				$text = $tp->toHTML(str_replace('{NEWLOGINNAME}', $allData['validate']['user_loginname'], $pref['signup_text_after']), TRUE, 'parse_sc,defs')."<br />";
			}
			else
			{
				if ($pref['user_reg_veri'] == 2)
				{
					$text = LAN_SIGNUP_37.'<br /><br />'.$adviseLoginName;
				}
				else
				{
					$text = LAN_SIGNUP_72.'<br /><br />'.$adviseLoginName;
				}
			}
			if ($error_message)
			{
				$text = "<br /><b>".$error_message."</b><br />";		// Just display error message
			}
			$ns->tablerender(LAN_SIGNUP_73, $text);
			require_once(FOOTERF);
			exit;
		}
		else
		{	// User can be signed up immediately
			require_once(HEADERF);

			if(!$sql -> db_Select("user", "user_id", "user_loginname='".$allData['validate']['user_loginname']."' AND user_password='".$allData['validate']['user_password']."'"))
			{	// Error looking up newly created user
				$ns->tablerender("", LAN_SIGNUP_36);
				require_once(FOOTERF);
				exit;
			}

			$e_event->trigger("usersup", $_POST);  // send everything in the template, including extended fields.

			if (isset($pref['signup_text_after']) && (strlen($pref['signup_text_after']) > 2))
			{
				$text = $tp->toHTML(str_replace('{NEWLOGINNAME}', $loginname, $pref['signup_text_after']), TRUE, 'parse_sc,defs')."<br />";
			}
			else
			{
				$text = LAN_SIGNUP_76."&nbsp;".SITENAME.", ".LAN_SIGNUP_12."<br /><br />".LAN_SIGNUP_13;
			}
			$ns->tablerender(LAN_SIGNUP_8,$text);
			require_once(FOOTERF);
			exit;
		}
	}		// End - if (!$error)
	else
	{	// 'Recirculate' selected values so they are retained on the form when an error occurs
		foreach (array('user_class') as $a)
		{
			$signupData[$a] = $tp->toForm(varset($allData['validate'][$a],''));
		}
	}
}

// Disable the signup form - if either there was an error, or starting from scratch
require_once(HEADERF);

$qs = ($error ? "stage" : e_QUERY);
if ($pref['use_coppa'] == 1 && strpos($qs, "stage") === FALSE)
{
	$text = $tp->parseTemplate($COPPA_TEMPLATE, TRUE, $signup_shortcodes);
	$ns->tablerender(LAN_SIGNUP_78, $text);
	require_once(FOOTERF);
	exit;
}


if ($qs == 'stage1' && $pref['use_coppa'] == 1)
{
	if(isset($_POST['newver']))
	{
		if(!varsettrue($_POST['coppa']))
		{
			$text = $tp->parseTemplate($COPPA_FAIL);
			$ns->tablerender(LAN_SIGNUP_78, $text);
			require_once(FOOTERF);
			exit;
		}
	}
	else
	{
  		header('Location: '.e_BASE.'signup.php');
		exit;
	}
}

require_once(e_HANDLER."form_handler.php");
$rs = new form;


$text = $tp->parseTemplate($SIGNUP_BEGIN.$SIGNUP_BODY.$SIGNUP_END, TRUE, $signup_shortcodes);
$ns->tablerender(LAN_SIGNUP_79, $text);
require_once(FOOTERF);
exit;



//----------------------------------
// Function returns an image if a field is required.
function req($field)
{
	return ($field == 2 ? REQUIRED_FIELD_MARKER : "");
}
//----------------------------------

function headerjs()
{
	$script_txt = "
	<script type=\"text/javascript\">
	function addtext3(sc){
		document.getElementById('signupform').image.value = sc;
	}

	function addsig(sc){
		document.getElementById('signupform').signature.value += sc;
	}
	function help(help){
		document.getElementById('signupform').helpb.value = help;
	}
	</script>\n";

	global $cal;
	$script_txt .= $cal->load_files();
	return $script_txt;
}


// Create the email to send. $userInfo is the array of DB variables
function render_email($userInfo, $preview = FALSE)
{
	// 1 = Body
	// 2 = Subject

	global $pref,$SIGNUPEMAIL_LINKSTYLE,$SIGNUPEMAIL_SUBJECT,$SIGNUPEMAIL_TEMPLATE;

	if($preview == TRUE)
	{
		$userInfo['user_password'] = "test-password";
		$userInfo['user_loginname'] = "test-loginname";
		$userInfo['user_name'] = "test-username";
		$userInfo['user_website'] = "www.test-site.com";		// This may not be defined
		$userInfo['user_id'] = 0;
		$userInfo['user_sess'] = "1234567890ABCDEFGHIJKLMNOP";
	}

	define("RETURNADDRESS", (substr(SITEURL, -1) == "/" ? SITEURL."signup.php?activate.".$userInfo['user_id'].".".$userInfo['user_sess'] : SITEURL."/signup.php?activate.".$userInfo['user_id'].".".$userInfo['user_sess'].".".e_LAN));
	$pass_show = ($pref['user_reg_secureveri'])? "*******" : $userInfo['user_password'];

	if (file_exists(THEME."email_template.php"))
	{
		require_once(THEME."email_template.php");
	}
	else
	{
		require_once(e_THEME."templates/email_template.php");
	}

	$inline_images = explode(",",$SIGNUPEMAIL_IMAGES);
	if($SIGNUPEMAIL_BACKGROUNDIMAGE)
	{
		$inline_images[] = $SIGNUPEMAIL_BACKGROUNDIMAGE;
	}

	$ret['userid'] = $userInfo['user_id'];
	$ret['cc'] = $SIGNUPEMAIL_CC;
	$ret['bcc'] = $SIGNUPEMAIL_BCC;
	$ret['attachments'] = $SIGNUPEMAIL_ATTACHMENTS;
	$ret['inline-images'] = implode(",",$inline_images);

	$style = ($SIGNUPEMAIL_LINKSTYLE) ? "style='$SIGNUPEMAIL_LINKSTYLE'" : "";

	$search[0] = "{LOGINNAME}";
	$replace[0] = $userInfo['user_loginname'];

	$search[1] = "{PASSWORD}";
	$replace[1] = $pass_show;

	$search[2] = "{ACTIVATION_LINK}";
	$replace[2] = "<a href='".RETURNADDRESS."' {$style}>".RETURNADDRESS."</a>";

	$search[3] = "{SITENAME}";
	$replace[3] = SITENAME;

	$search[4] = "{SITEURL}";
	$replace[4] = "<a href='".SITEURL."' {$style}>".SITEURL."</a>";

	$search[5] = "{USERNAME}";
	$replace[5] = $userInfo['user_name'];

	$search[6] = "{USERURL}";
	$replace[6] = varsettrue($userInfo['user_website']) ? $userInfo['user_website'] : "";

	$cnt=1;

	foreach($inline_images as $img)
	{
		if(is_readable($inline_images[$cnt-1]))
		{
			$cid_search[] = "{IMAGE".$cnt."}";
			$cid_replace[] = "<img alt=\"".SITENAME."\" src='cid:".md5($inline_images[$cnt-1])."' />\n";
			$path_search[] = "{IMAGE".$cnt."}";
			$path_replace[] = "<img alt=\"".SITENAME."\" src=\"".$inline_images[$cnt-1]."\" />\n";
		}
		$cnt++;
	}

	$subject = str_replace($search,$replace,$SIGNUPEMAIL_SUBJECT);
	$ret['subject'] =  $subject;

	$HEAD = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
	$HEAD .= "<html xmlns='http://www.w3.org/1999/xhtml' >\n";
	$HEAD .= "<head><meta http-equiv='content-type' content='text/html; charset=utf-8' />\n";
	$HEAD .= ($SIGNUPEMAIL_USETHEME == 1) ? "<link rel=\"stylesheet\" href=\"".SITEURL.THEME."style.css\" type=\"text/css\" />\n" : "";
    $HEAD .= ($preview) ? "<title>".LAN_SIGNUP_58."</title>\n" : "";
	if($SIGNUPEMAIL_USETHEME == 2)
	{
		$CSS = file_get_contents(THEME."style.css");
		$HEAD .= "<style>\n".$CSS."\n</style>";
	}

	$HEAD .= "</head>\n";
	if($SIGNUPEMAIL_BACKGROUNDIMAGE)
	{
		$HEAD .= "<body background=\"cid:".md5($SIGNUPEMAIL_BACKGROUNDIMAGE)."\" >\n";
	}
	else
	{
		$HEAD .= "<body>\n";
	}
	$FOOT = "\n</body>\n</html>\n";

	$SIGNUPEMAIL_TEMPLATE = $HEAD.$SIGNUPEMAIL_TEMPLATE.$FOOT;
	$message = str_replace($search,$replace,$SIGNUPEMAIL_TEMPLATE);

	$ret['message'] = str_replace($cid_search,$cid_replace,$message);
	$ret['preview'] = str_replace($path_search,$path_replace,$message);

	return $ret;
}
?>