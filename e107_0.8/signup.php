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
|     $Source: /cvs_backup/e107_0.8/signup.php,v $
|     $Revision: 1.5 $
|     $Date: 2007-01-07 15:59:41 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

require_once("class2.php");
$qs = explode(".", e_QUERY);
if($qs[0] != "activate"){   // multi-language fix.
	e107_include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_signup.php");
	e107_include_once(e_LANGUAGEDIR."English/lan_signup.php");
	e107_include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_usersettings.php");
}

include_once(e_HANDLER."user_extended_class.php");
$usere = new e107_user_extended;
require_once(e_HANDLER."calendar/calendar_class.php");
$cal = new DHTML_Calendar(true);

if (is_readable(THEME."signup_template.php")) {
	require_once(THEME."signup_template.php");
} else {
	require_once(e_THEME."templates/signup_template.php");
}

include_once(e_FILE."shortcode/batch/signup_shortcodes.php");

$signup_imagecode = ($pref['signcode'] && extension_loaded("gd"));

// Resend Activation Email ------------------------------------------->
if(e_QUERY == "resend" && !USER && ($pref['user_reg_veri'] == 1))
{
	e107_include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE);
	e107_include_once(e_LANGUAGEDIR."English/lan_".e_PAGE);
	require_once(HEADERF);

    if(!$clean_email = check_email($tp -> toDB($_POST['resend_email'])))
	{
		$clean_email = "xxx";
	}

    if(!$new_email = check_email($tp -> toDB($_POST['resend_newemail'])))
	{
    	$new_email = FALSE;
	}

	if($_POST['submit_resend'])
	{
		if($_POST['resend_email'] && !$new_email && $sql->db_Select_gen("SELECT * FROM #user WHERE user_ban=0 AND user_sess='' AND (user_loginname= \"".$tp->toDB($_POST['resend_email'])."\" OR user_name = \"".$tp->toDB($_POST['resend_email'])."\" OR user_email = \"".$clean_email."\" ) "))
		{
			$ns -> tablerender(LAN_SIGNUP_40,LAN_SIGNUP_41."<br />");
			require_once(FOOTERF);
			exit;
		}

		if(trim($_POST['resend_password']) !="" && $new_email)
		{
        	if($sql->db_Select("user", "user_id", "user_password = \"".md5($_POST['resend_password'])."\" AND user_ban=2 AND user_sess !='' LIMIT 1"))
			{
				$row = $sql -> db_Fetch();
            	if($sql->db_Update("user", "user_email='".$new_email."' WHERE user_id = '".$row['user_id']."' LIMIT 1 "))
				{
                	$clean_email = $new_email;
				}
			}
			else
			{
			   	require_once(e_HANDLER."message_handler.php");
			   	message_handler("ALERT",LAN_SIGNUP_52); // Incorrect Password.
			}
		}


		if($sql->db_Select("user", "*", "(user_loginname = \"".$tp->toDB($_POST['resend_email'])."\" OR user_name = \"".$tp->toDB($_POST['resend_email'])."\" OR user_email = \"".$clean_email."\" ) AND user_ban=2 AND user_sess !='' LIMIT 1"))
		{
			$row = $sql -> db_Fetch();

			$_POST['password1'] = "xxxxxxxxx";
			$_POST['loginname'] = $row['user_loginname'];
			$_POST['name'] = $row['user_name'];
			$nid = $row['user_id'];
			$u_key = $row['user_sess'];

			$eml = render_email();
        	$mailheader_e107id = $nid;
			require_once(e_HANDLER."mail.php");

/*
            echo "Sending to: ".$row['user_email'];
            require_once(FOOTERF);
            exit;
*/

            if(!sendemail($row['user_email'], $eml['subject'], $eml['message'], $row['user_name'], "", "", $eml['attachments'], $eml['cc'], $eml['bcc'], $returnpath, $returnreceipt,$eml['inline-images']))
            {
                $ns -> tablerender(LAN_ERROR,LAN_SIGNUP_42);
                require_once(FOOTERF);
                exit;
            }
            else
            {
                $ns -> tablerender(LAN_SIGNUP_43,LAN_SIGNUP_44." ".$row['user_email']." - ".LAN_SIGNUP_45."<br /><br />");
                require_once(FOOTERF);
                exit;
            }
         }

		require_once(e_HANDLER."message_handler.php");
		message_handler("ALERT",LAN_106); // email not valid.
		exit;
	}
	elseif(!$_POST['submit_resend'])
	{

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

if(!$_POST)   // Notice Removal.
{

	$error = "";
	$text = " ";
	$password1 = "";
	$password2 = "";
	$email = "";
	$loginname = "";
	$realname = "";
	$user_timezone = "";
	$image = "";
	$avatar_upload = "";
	$photo_upload = "";
	$_POST['ue'] = "";
	$signature = "";
}

if(ADMIN && (e_QUERY == "preview" || e_QUERY == "test"  || e_QUERY == "preview.aftersignup"))
{
	e107_include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_".e_PAGE);
	e107_include_once(e_LANGUAGEDIR."English/lan_".e_PAGE);
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
				$text = LAN_405;
			}
		}
		$ns->tablerender(LAN_406, $text);
		require_once(FOOTERF);
		exit;
	}

	$eml = render_email(TRUE);
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

// After clicking the activation link -------------------------
if (e_QUERY)
{
	$qs = explode(".", e_QUERY);
	if ($qs[0] == "activate" && (count($qs) == 3 || count($qs) == 4) && $qs[2])
	{
        // return the message in the correct language.
		if($qs[3] && strlen($qs[3]) == 2 )
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
				$sql->db_Update("user", "user_ban='0', user_sess='' WHERE user_sess='".$tp -> toDB($qs[2], true)."' ");
				$e_event->trigger("userveri", $row);
				require_once(HEADERF);
				$text = LAN_401." <a href='index.php'>".LAN_SIGNUP_22."</a> ".LAN_SIGNUP_23."<br />".LAN_SIGNUP_24." ".SITENAME;
				$ns->tablerender(LAN_402, $text);
				require_once(FOOTERF);
				exit;
			}
		}
		else
		{
			header("location: ".e_BASE."index.php");
			exit;
		}
	}
}


if (isset($_POST['register']))
{
	$e107cache->clear("online_menu_totals");
	$error_message = "";
	require_once(e_HANDLER."message_handler.php");
	if ($signup_imagecode && !isset($_POST['xupexist']))
	{
		if (!$sec_img->verify_code($_POST['rand_num'], $_POST['code_verify']))
		{
			$error_message .= LAN_SIGNUP_3."\\n";
			$error = TRUE;
		}
	}

	if($_POST['xupexist'])
	{
		require_once(e_HANDLER."xml_class.php");
		$xml = new parseXml;
		if(!$rawData = $xml -> getRemoteXmlFile($_POST['xupexist']))
		{
			echo "Error: Unable to open remote XUP file";
		}
		preg_match_all("#\<meta name=\"(.*?)\" content=\"(.*?)\" \/\>#si", $rawData, $match);
		$count = 0;
		foreach($match[1] as $value)
		{
			$xup[$value] = $match[2][$count];
			$count++;
		}

		$_POST['name'] = $xup['NICKNAME'];
		$_POST['email'] = $xup['EMAIL'];
		$_POST['signature'] = $xup['SIG'];
		$_POST['hideemail'] = $xup['EMAILHIDE'];
		$_POST['timezone'] = $xup['TZ'];
		$_POST['realname'] = $xup['FN'];
		$_POST['image'] = $xup['AV'];

		$_POST['ue']['user_homepage'] = $xup['URL'];
		$_POST['ue']['user_icq'] = $xup['ICQ'];
		$_POST['ue']['user_aim'] = $xup['AIM'];
		$_POST['ue']['user_msn'] = $xup['MSN'];
		$_POST['ue']['user_yahoo'] = $xup['YAHOO'];
		$_POST['ue']['user_location'] = $xup['GEO'];
		$_POST['ue']['user_birthday'] = $xup['BDAY'];

		unset($xup);
	}
	if($_POST['loginnamexup']) $_POST['loginname'] = $_POST['loginnamexup'];
	if($_POST['password1xup']) $_POST['password1'] = $_POST['password1xup'];
	if($_POST['password2xup']) $_POST['password2'] = $_POST['password2xup'];

	if (strstr($_POST['loginname'], "#") || strstr($_POST['loginname'], "=") || strstr($_POST['loginname'], "\\") || strstr($_POST['loginname'], "'") || strstr($_POST['loginname'], '"'))
	{
		$error_message .= LAN_409."\\n";
		$error = TRUE;
	}

	$_POST['loginname'] = trim(preg_replace("/&nbsp;|\#|\=/", "", $_POST['loginname']));
	if ($_POST['loginname'] == "Anonymous")
	{
		$error_message .= LAN_103."\\n";
		$error = TRUE;
	}

	// Use LoginName for DisplayName if restricted   **** MOVED FORWARD ****
	if (!check_class($pref['displayname_class']))
	{
		$_POST['name'] = $_POST['loginname'];
	}

	// Impose a minimum length on display name
	$_POST['name'] = trim($_POST['name']);
	if (strlen($_POST['name']) < 2)
	{
	  $error_message .= LAN_SIGNUP_56."\\n";
	  $error = TRUE;
	}
	
	// Check for disallowed names.
	if(isset($pref['signup_disallow_text']))
	{
		$tmp = explode(",", $pref['signup_disallow_text']);
		foreach($tmp as $disallow)
		{
			if( strstr($_POST['name'], $disallow) || strstr($_POST['loginname'], $disallow) ){
				$error_message .= LAN_103."\\n";
				$error = TRUE;
			}
		}
	}

	// Check if form maxlength has been bypassed
	if ( strlen($_POST['name']) > 30 || strlen($_POST['loginname']) > 30)
	{
		exit;
	}

	// Check if display name exceeds maximum allowed length
	if (isset($pref['displayname_maxlength']) && (strlen($_POST['name']) > $pref['displayname_maxlength']))
	{
	  $error_message .= LAN_SIGNUP_55."\\n";
	  $error = TRUE;
	}
	
	// Display Name exists.
	if ($sql->db_Select("user", "*", "user_name='".$tp -> toDB($_POST['name'])."'"))
	{
		$error_message .= LAN_411.": ".$tp -> toDB($_POST['name'])."\\n";
		$error = TRUE;
	}
	// Login Name exists
	if ($sql->db_Select("user", "*", "user_loginname='".$tp -> toDB($_POST['loginname'])."' "))
	{
		$error_message .= LAN_104.": ".$tp -> toDB($_POST['loginname'])."\\n";
		$error = TRUE;
	}

	// check for multiple signups from the same IP address.
	if($ipcount = $sql->db_Select("user", "*", "user_ip='".$e107->getip()."' and user_ban !='2' "))
	{
		if($ipcount >= $pref['signup_maxip'] && trim($pref['signup_maxip']) != "")
		{
			$error_message .= LAN_202."\\n";
			$error = TRUE;
		}
	}

	// Check password fields are matching.
	if ($_POST['password1'] != $_POST['password2'])
	{
		$error_message .= LAN_105."\\n";
		$error = TRUE;
		$password1 = "";
		$password2 = "";
	}

	// Email address confirmation.
	if ($_POST['email'] != $_POST['email_confirm'])
	{
		$error_message .= LAN_SIGNUP_38."\\n";
		$error = TRUE;
		$email = "";
		$email_confirm = "";
	}

	// Password length check.
	if (trim(strlen($_POST['password1'])) < $pref['signup_pass_len'])
	{
		$error_message .= LAN_SIGNUP_4.$pref['signup_pass_len'].LAN_SIGNUP_5."\\n";
		$error = TRUE;
		$password1 = "";
		$password2 = "";
	}

	// Check for emtpy fields
	if (trim($_POST['name']) == "" || trim($_POST['loginname']) == "" || trim($_POST['password1']) == "" || trim($_POST['password2']) == "")
	{
		$error_message .= LAN_185."\\n";
		$error = TRUE;
	}

	// ========== Verify Custom Signup options if selected ========================

	$signup_option_title = array(LAN_308, LAN_120, LAN_121, LAN_122, LAN_SIGNUP_28);
	$signup_option_names = array("realname", "signature", "image", "timezone", "class");

	foreach($signup_option_names as $key => $value)
	{
		if ($pref['signup_option_'.$value] == 2 && !$_POST[$value])
		{
			$error_message .= LAN_SIGNUP_6.$signup_option_title[$key].LAN_SIGNUP_7."\\n";
			$error = TRUE;
		}
	}

	// Check for Duplicate Email address.
	if ($sql->db_Select("user", "user_email, user_ban, user_sess", "user_email='".$tp -> toDB($_POST['email'])."' "))
	{
        $chk = $sql -> db_Fetch();
		if($chk['user_ban']== 2 && $chk['user_sess']){
		// duplicate because unactivated
			$error = TRUE;
        	header("Location: ".e_BASE."signup.php?resend");
			exit;
		}else{
			$error_message .= LAN_408."\\n";
			$error = TRUE;
		}
	}

	// Extended Field validation
	$extList = $usere->user_extended_get_fieldList();

	foreach($extList as $ext)
	{
		if(isset($_POST['ue']['user_'.$ext['user_extended_struct_name']]))
		{

			$newval = trim($_POST['ue']['user_'.$ext['user_extended_struct_name']]);
			if($ext['user_extended_struct_required'] == 1 && $newval == "" )
			{
				$_ftext = (defined($ext['user_extended_struct_text']) ? constant($ext['user_extended_struct_text']) : $ext['user_extended_struct_text']);
				$error_message .= LAN_SIGNUP_6.$_ftext.LAN_SIGNUP_7."\\n";
				$error = TRUE;
			}
			$parms = explode("^,^", $ext['user_extended_struct_parms']);
			$regex = (isset($parms[1]) ? $tp->toText($parms[1]) : "");
			$regexfail = (isset($parms[2]) ? trim($tp->toText($parms[2])) : "");

			if($regexfail == "")
			{
				$regexfail = $ext['user_extended_struct_name']." ".LAN_SIGNUP_53;
			}

			if(defined($regexfail)) {$regexfail = constant($regexfail);}

			if($regex != "" && $newval != "")
			{
				if(!preg_match($regex, $newval))
				{
					$error_message .= $regexfail."\\n";
					$error = TRUE;
				}
			}
		}
	}

	// Email syntax validation.
	if (!check_email($_POST['email']))
	{
		message_handler("P_ALERT", LAN_106);
		$error_message .= LAN_106."\\n";
		$error = TRUE;
	}

	// Check Email against banlist.
	$wc = $tp -> toDB("*".trim(substr($_POST['email'], strpos($_POST['email'], "@"))));
	if ($sql->db_Select("banlist", "*", "banlist_ip='".$tp -> toDB($_POST['email'])."' OR banlist_ip='{$wc}'"))
	{
		$brow = $sql -> db_Fetch();
		$error = TRUE;
		if($brow['banlist_reason'])
		{
			$repl = array("\n","\r","<br />");
			$error_message = str_replace($repl,"\\n",$tp->toHTML($brow['banlist_reason'],"","nobreak, defs"))."\\n";
			$email = "";
		}
		else
		{
			exit;
		}
	}

	// Check email address on remote server (if enabled).
	if (varsettrue($pref['signup_remote_emailcheck']) && $error != TRUE)
	{
		require_once(e_HANDLER."mail_validation_class.php");
		list($adminuser,$adminhost) = split ("@", SITEADMINEMAIL);
		$validator = new email_validation_class;
		$validator->localuser= $adminuser;
		$validator->localhost= $adminhost;
		$validator->timeout=3;
		//	$validator->debug=1;
		//	$validator->html_debug=1;
		if($validator->ValidateEmailBox(trim($_POST['email'])) != 1)
		{
			$error_message .= LAN_106."\\n";
			$error = TRUE;
			$email = "";
			$email_confirm = "";
		}

	}

	if($error_message)
	{
		message_handler("P_ALERT", $error_message);
	}

	// ========== End of verification.. ====================================================

	if (!$error)
	{
		$fp = new floodprotect;
		if ($fp->flood("user", "user_join") == FALSE)
		{
			header("location:".e_BASE."index.php");
			exit;
		}

		if ($sql->db_Select("user", "*", "user_email='".$tp -> toDB($_POST['email'])."' AND user_ban='1'")) {
			exit;
		}

		$username = $tp -> toDB(strip_tags($_POST['name']));
		$loginname = $tp -> toDB(strip_tags($_POST['loginname']));
		$time = time();
		$ip = $e107->getip();

		$ue_fields = "";
		foreach($_POST['ue'] as $key => $val)
		{
			$key = $tp->toDB($key);
			$val = $tp->toDB($val);
			$ue_fields .= ($ue_fields) ? ", " : "";
			$ue_fields .= $key."='".$val."'";
		}

		$u_key = md5(uniqid(rand(), 1));
		$nid = $sql->db_Insert("user", "0, '{$username}', '{$loginname}', '', '".md5($_POST['password1'])."', '{$u_key}', '".$tp -> toDB($_POST['email'])."', '".$tp -> toDB($_POST['signature'])."', '".$tp -> toDB($_POST['image'])."', '".$tp -> toDB($_POST['timezone'])."', '".$tp -> toDB($_POST['hideemail'])."', '".$time."', '0', '".$time."', '0', '0', '0', '0', '".$ip."', '2', '0', '', '', '0', '0', '".$tp -> toDB($_POST['realname'])."', '', '', '', '0', '".$tp -> toDB($_POST['xupexist'])."' ");
		if(!$nid)
		{
			require_once(HEADERF);
			$ns->tablerender("", LAN_SIGNUP_36);
			require_once(FOOTERF);
		}

		if ($pref['user_reg_veri'])
		{
			// ==== Update Userclass =======>

			if ($_POST['class'])
			{
				unset($insert_class);
				sort($_POST['class']);
				$insert_class = implode(",",$_POST['class']);
				$sql->db_Update("user", "user_class='".$tp -> toDB($insert_class)."' WHERE user_id='".$nid."' ");
			}

			// ========= save extended fields into db table. =====

			if($ue_fields)
			{
				$sql->db_Select_gen("INSERT INTO #user_extended (user_extended_id) values ('{$nid}')");
				$sql->db_Update("user_extended", $ue_fields." WHERE user_extended_id = '{$nid}'");
			}

			// ========== Send Email =========>

			if ($pref['user_reg_veri'] != 2)
			{
                $eml = render_email();
				$mailheader_e107id = $eml['userid'];
				require_once(e_HANDLER."mail.php");


				if(!sendemail($_POST['email'], $eml['subject'], $eml['message'], "", "", "", $eml['attachments'], $eml['cc'], $eml['bcc'], "", "", $eml['inline-images']))
				{
					$error_message = LAN_SIGNUP_42; // There was a problem, the registration mail was not sent, please contact the website administrator.
				}
			}

            $_POST['ip'] = $ip;
			$e_event->trigger("usersup", $_POST);  // send everything in the template, including extended fields.

			require_once(HEADERF);
			if($pref['signup_text_after'])
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
					$text = LAN_405;
				}
			}
			if(isset($error_message))
			{
				$text .= "<br /><b>".$error_message."</b><br />";
			}
			$ns->tablerender(LAN_406, $text);
			require_once(FOOTERF);
			exit;
		}
		else
		{
			require_once(HEADERF);

			if(!$sql -> db_Select("user", "user_id", "user_name='{$username}' AND user_password='".md5($_POST['password1'])."'"))
			{
				$ns->tablerender("", LAN_SIGNUP_36);
				require_once(FOOTERF);
				exit;
			}
			$sql->db_Update("user", "user_ban = '0' WHERE user_id = '{$nid}'");

			// ==== Update Userclass =======
			if ($_POST['class'])
			{
				unset($insert_class);
				sort($_POST['class']);
				$insert_class = implode(",",$_POST['class']);
				$sql->db_Update("user", "user_class='".$tp -> toDB($insert_class)."' WHERE user_id='".$nid."' ");
			}
			// ======== save extended fields to DB table.

			if($ue_fields)
			{
				$sql->db_Select_gen("INSERT INTO #user_extended (user_extended_id) values ('{$nid}')");
				$sql->db_Update("user_extended", $ue_fields." WHERE user_extended_id = '{$nid}'");
			}

			// ==========================================================
            $_POST['ip'] = $ip;
			$e_event->trigger("usersup", $_POST);  // send everything in the template, including extended fields.

			if($pref['signup_text_after'])
			{
				$text = $tp->toHTML($pref['signup_text_after'], TRUE, 'parse_sc,defs')."<br />";
			}
			else
			{
				$text = LAN_107."&nbsp;".SITENAME.", ".LAN_SIGNUP_12."<br /><br />".LAN_SIGNUP_13;
			}
			$ns->tablerender(LAN_SIGNUP_8,$text);
			require_once(FOOTERF);
			exit;
		}
	}

}
require_once(HEADERF);

$qs = ($error ? "stage" : e_QUERY);
if ($pref['use_coppa'] == 1 && strpos($qs, "stage") === FALSE)
{
	$text = $tp->parseTemplate($COPPA_TEMPLATE, TRUE, $signup_shortcodes);
	$ns->tablerender(LAN_110, $text);
	require_once(FOOTERF);
	exit;
}

if (!$website)
{
	$website = "http://";
}

if (strpos(LAN_109, "stage") === FALSE)
{
	if (isset($_POST['newver']))
	{
		if (!$_POST['coppa'])
		{
			$text = $tp->parseTemplate($COPPA_FAIL);
			$ns->tablerender(LAN_110, $text);
			require_once(FOOTERF);
			exit;
		}
	}
}

require_once(e_HANDLER."form_handler.php");
$rs = new form;


$text = $tp->parseTemplate($SIGNUP_BEGIN.$SIGNUP_BODY.$SIGNUP_END, TRUE, $signup_shortcodes);
$ns->tablerender(LAN_123, $text);
require_once(FOOTERF);
exit;

// Default Signup Form ----->

$ns->tablerender(LAN_123, $text);

require_once(FOOTERF);

//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

function req($field)
{
	return ($field == 2 ? REQUIRED_FIELD_MARKER : "");
}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

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


function render_email($preview = FALSE)
{
	// 1 = Body
	// 2 = Subject

	global $pref,$nid,$u_key,$_POST,$SIGNUPEMAIL_LINKSTYLE,$SIGNUPEMAIL_SUBJECT,$SIGNUPEMAIL_TEMPLATE;

	if($preview == TRUE)
	{
		$_POST['password1'] = "test-password";
		$_POST['loginname'] = "test-loginname";
		$_POST['name'] = "test-username";
		$_POST['website'] = "www.test-site.com";
		$nid = 0;
		$u_key = "1234567890ABCDEFGHIJKLMNOP";
	}

	define("RETURNADDRESS", (substr(SITEURL, -1) == "/" ? SITEURL."signup.php?activate.".$nid.".".$u_key : SITEURL."/signup.php?activate.".$nid.".".$u_key.".".e_LAN));
	$pass_show = ($pref['user_reg_secureveri'])? "*******" : $_POST['password1'];

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

	$ret['userid'] = $nid;
	$ret['cc'] = $SIGNUPEMAIL_CC;
	$ret['bcc'] = $SIGNUPEMAIL_BCC;
	$ret['attachments'] = $SIGNUPEMAIL_ATTACHMENTS;
	$ret['inline-images'] = implode(",",$inline_images);

	$style = ($SIGNUPEMAIL_LINKSTYLE) ? "style='$SIGNUPEMAIL_LINKSTYLE'" : "";

	$search[0] = "{LOGINNAME}";
	$replace[0] = $_POST['loginname'];

	$search[1] = "{PASSWORD}";
	$replace[1] = $pass_show;

	$search[2] = "{ACTIVATION_LINK}";
	$replace[2] = "<a href='".RETURNADDRESS."' $style>".RETURNADDRESS."</a>";

	$search[3] = "{SITENAME}";
	$replace[3] = SITENAME;

	$search[4] = "{SITEURL}";
	$replace[4] = "<a href='".SITEURL."' $style>".SITEURL."</a>";

	$search[5] = "{USERNAME}";
	$replace[5] = $_POST['name'];

	$search[6] = "{USERURL}";
	$replace[6] = ($_POST['website']) ? $_POST['website'] : "";

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
    $HEAD .= ($preview) ? "<title>Signup Preview</title>\n" : "";
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
