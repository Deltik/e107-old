<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2010 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Administration - Site Preferences
 *
 * $URL$
 * $Revision$
 * $Id$
 * $Author$
 */
require_once ("../class2.php");

if(isset($_POST['newver']))
{
	header("location:http://e107.org/index.php");
	exit();
}

if(! getperms("1"))
{
	header("location:".e_BASE."index.php");
	exit();
}

include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/admin/lan_'.e_PAGE);
$e_sub_cat = 'prefs';
require_once (e_ADMIN."auth.php");

$e_userclass = e107::getUserClass(); 
require_once (e_HANDLER."user_extended_class.php");
$ue = new e107_user_extended();
$core_pref = e107::getConfig();

if(!$core_pref->get('timezone'))
{
	$core_pref->set('timezone', 'GMT');
}

$frm = e107::getForm(false, true); //enable inner tabindex counter
$emessage = e107::getMessage();
$tp = e107::getParser();

/*	RESET DISPLAY NAMES	*/
if(isset($_POST['submit_resetdisplaynames']))
{
	e107::getDb()->db_Update('user', 'user_name=user_loginname');
	$emessage->add(PRFLAN_157);
}

//echo '<pre>';
//var_dump($core_pref->getPref());
//echo '</pre>';
/*	UPDATE PREFERENCES */
if(isset($_POST['updateprefs']))
{
	unset($_POST['updateprefs'], $_POST['sitelanguage']);

	$_POST['cookie_name'] = str_replace(array(" ", "."), "_", $_POST['cookie_name']);
	$_POST['cookie_name'] = preg_replace("#[^a-zA-Z0-9_]#", "", $_POST['cookie_name']);

	$_POST['siteurl'] = trim($_POST['siteurl']) ? trim($_POST['siteurl']) : SITEURL;
	$_POST['siteurl'] = substr($_POST['siteurl'], - 1) == "/" ? $_POST['siteurl'] : $_POST['siteurl']."/";

	// If email verification or Email/Password Login Method - email address is required!
	if (($_POST['user_reg_veri'] == 1 || $_POST['allowEmailLogin'] == 1) && $_POST['disable_emailcheck'])
	{
		$_POST['disable_emailcheck'] = 0;
		$emessage->add(PRFLAN_211, E_MESSAGE_ERROR);
    }

	// Table of range checking values - min and max for numerics. Only do the important ones
	$pref_limits = array('loginname_maxlength' => array('min' => 10, 'max' => 100, 'default' => 30),
					'displayname_maxlength' => array('min' => 5, 'max' => 100, 'default' => 15),
					'antiflood_timeout' => array('min' => 3, 'max' => 300, 'default' => 10),
					'signup_pass_len' => array('min' => 2, 'max' => 100, 'default' => 4)
					);

	$pref['post_html'] = intval($_POST['post_html']);			// This ensures the setting is reflected in set text
	
	$_POST['membersonly_exceptions'] = explode("\n",$_POST['membersonly_exceptions']);

	// FIXME - automate - pref model & validation handler
	$prefChanges = array();
	$sessionRegenerate = false;
	foreach($_POST as $key => $value)
	{
		if(isset($pref_limits[$key]))
		{ // Its a numeric value to check
			if(is_numeric($value))
			{
				if($value < $pref_limits[$key]['min'])
				{
					$value = $pref_limits[$key]['min'];
					$emessage->addWarning(str_replace(array('--FIELD--','--VALUE--'),array($key,$value),PRFLAN_213));
				}
				if($value > $pref_limits[$key]['max'])
				{
					$value = $pref_limits[$key]['max'];
					$emessage->addWarning(str_replace(array('--FIELD--','--VALUE--'),array($key,$value),PRFLAN_212));
				}
			}
			else
			{
				$value = $pref_limits[$key]['default'];
			}
			$newValue = $value;
		}
		elseif('cookie_name' == $key && $core_pref->get($key) != $value)
		{
			// special case
			if(!preg_match('/^[\w\-]+$/', $value))
			{
				$newValue = e_COOKIE;
				$emessage->addWarning(PRFLAN_219);
			}
			else 
			{
				$newValue = $value;
				$sessionRegenerate = true;
			}
		}
		else
		{
			$newValue = $tp->toDB($value);
		}
		$core_pref->update($key, $newValue);
		/*if($newValue != $core_pref->get($key))
		{ // Changed value
			$core_pref->set($key, $newValue);
			$prefChanges[$key] = $newValue;
		}*/
	}
	$core_pref->save(false);
	// special case, do session cleanup, logout, redirect to login screen
	if($sessionRegenerate)
	{
		// reset cookie
		cookie($core_pref->get('cookie_name'), $_COOKIE[e_COOKIE], (time() + 3600 * 24 * 30), e_HTTP, e107::getLanguage()->getCookieDomain());
		cookie(e_COOKIE, null, null);
		
		// regenerate session
		$s = $_SESSION;
		e107::getSession()->destroy();
		$session = new e_core_session(array('name' => $core_pref->get('cookie_name')));
		$_SESSION = $s;
	}
}

if (plugInstalled('alt_auth'))
{
	$authlist[] = "e107";
	$handle = opendir(e_PLUGIN."alt_auth");
	while($file = readdir($handle))
	{
		if(preg_match("/^(.*)_auth\.php/", $file, $match))
		{
			$authlist[] = $match[1];
		}
	}
}

/*
if(isset($message))
{
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

if(e_QUERY == "u")
{
	$ns->tablerender("", "<div style='text-align:center'><b>".PRFLAN_106."</b></div>");
}
*/
$handle = opendir(e_ADMIN.'includes/');
while($file = readdir($handle))
{
	if($file != "." && $file != "..")
	{
		$file = str_replace(".php", "", $file);
		$adminlist[] = $file;
	}
}
closedir($handle);

$pref['membersonly_exceptions'] = implode("\n",$pref['membersonly_exceptions']);

$text = "
<div id='core-prefs'>
	<form class='admin-menu' method='post' action='".e_SELF."'>
		<fieldset class='e-hideme' id='core-prefs-main'>
			<legend>".PRFLAN_1."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_2."</td>
						<td class='control'>
							".$frm->text('sitename', $pref['sitename'], 100)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_3."</td>
						<td class='control'>
							".$frm->text('siteurl', $pref['siteurl'], 150)."
							".($pref['siteurl'] == SITEURL ? "" : "<div class='smalltext'>( ".PRFLAN_159.": <strong>".SITEURL."</strong> )</div>")."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_134."</td>
						<td class='control'>
							".$frm->radio('redirectsiteurl', 1, $pref['redirectsiteurl']).$frm->label(LAN_ENABLED, 'redirectsiteurl', 1)."&nbsp;&nbsp;
							".$frm->radio('redirectsiteurl', 0, !$pref['redirectsiteurl']).$frm->label(LAN_DISABLED, 'redirectsiteurl', 0)."
							<div class='field-help'>".PRFLAN_135."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_4."</td>
						<td class='control'>
";

$parms = "name=sitebutton";
$parms .= "&path=".e_THEME.$pref['sitetheme']."/images/|".e_IMAGE;
$parms .= "&filter=0";
$parms .= "&fullpath=1";
$parms .= "&default=".urlencode($pref['sitebutton']);
//$parms .= "&width=128px";
//$parms .= "&height=128px";
$parms .= "&multiple=FALSE";
$parms .= "&label=-- No Image --";
$parms .= "&subdirs=1";
$parms .= "&tabindex=".$frm->getNext();


$text .= "<div class='field-section'>".$tp->parseTemplate("{IMAGESELECTOR={$parms}&scaction=select}")."</div>";
// $text .= "<div class='field-section'>".$frm->imagepicker('sitebutton',$pref['sitebutton'],'-- No Image --')."</div>";

//TODO make the preview update when image-picker is used.
$text .= "<div class='field-spacer'>".$tp->parseTemplate("{IMAGESELECTOR={$parms}&scaction=preview}")."</div>";

$text .= "
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_214."</td>
						<td class='control'>".$frm->imagepicker('sitelogo',$pref['sitelogo'],'_common')."</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_5."</td>
						<td class='control'>
							".$frm->textarea('sitetag', $pref['sitetag'], 3, 59)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_6."</td>
						<td class='control'>
							".$frm->textarea('sitedescription', $pref['sitedescription'], 6, 59)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_7."</td>
						<td class='control'>
							".$frm->text('siteadmin', SITEADMIN, 100)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_8."</td>
						<td class='control'>
							".$frm->text('siteadminemail', SITEADMINEMAIL, 100)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_174."</td>
						<td class='control'>
							".$frm->text('replyto_name', $pref['replyto_name'], 100)."
							<div class='smalltext field-help'>".PRFLAN_175."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_176."</td>
						<td class='control'>
							".$frm->text('replyto_email', $pref['replyto_email'], 100)."
							<div class='smalltext field-help'>".PRFLAN_177."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_162."</td>
						<td class='control'>
							".$frm->textarea('sitecontactinfo', $pref['sitecontactinfo'], 6, 59)."
							<div class='smalltext field-help'>".PRFLAN_163."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_168."</td>
						<td class='control'>
							".$e_userclass->uc_dropdown('sitecontacts', $pref['sitecontacts'], 'nobody,main,admin,classes', "tabindex='".$frm->getNext()."'")."
							<div class='smalltext field-help'>".PRFLAN_169."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_164."</td>
						<td class='control'>
							".$frm->radio('contact_emailcopy', 1, $pref['contact_emailcopy'])."
							".$frm->label(LAN_ENABLED, 'contact_emailcopy', 1)."&nbsp;&nbsp;
							".$frm->radio('contact_emailcopy', 0, !$pref['contact_emailcopy'])."
							".$frm->label(LAN_DISABLED, 'contact_emailcopy', 0)."
							<div class='smalltext field-help'>".PRFLAN_165."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_9."</td>
						<td class='control'>
							".$frm->textarea('sitedisclaimer', str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $pref['sitedisclaimer']), 6, 59)."
						</td>
					</tr>
				</tbody>
			</table>
			".pref_submit('main')."
		</fieldset>
";

$text .= "
		<fieldset class='e-hideme' id='core-prefs-display'>
			<legend>".PRFLAN_13."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_14." </td>
						<td class='control'>
							".$frm->radio_switch('displaythemeinfo', $pref['displaythemeinfo'])."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_15." </td>
						<td class='control'>
							".$frm->radio_switch('displayrendertime', $pref['displayrendertime'])."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_16." </td>
						<td class='control'>
							".$frm->radio_switch('displaysql', $pref['displaysql'])."
						</td>
					</tr>
	";
if(function_exists("memory_get_usage"))
{
	$text .= "
					<tr>
						<td class='label'>".PRFLAN_137." </td>
						<td class='control'>
							".$frm->radio_switch('display_memory_usage', $pref['display_memory_usage'])."
						</td>
					</tr>
	";
}
$text .= "
				</tbody>
			</table>
			".pref_submit('display')."
		</fieldset>
";

// Admin Display Areas
$text .= "
		<fieldset class='e-hideme' id='core-prefs-admindisp'>
			<legend>".PRFLAN_77."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_95."</td>
						<td class='control'>
							".$frm->radio_switch('admin_alerts_ok', $pref['admin_alerts_ok'])."
							<div class='smalltext field-help'>".PRFLAN_96."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_97."</td>
						<td class='control'>
							".$frm->radio_switch('admin_alerts_uniquemenu', $pref['admin_alerts_uniquemenu'])."
							<div class='smalltext field-help'>".PRFLAN_98."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_199."</td>
						<td class='control'>
							".$frm->radio_switch('admin_slidedown_subs', $pref['admin_slidedown_subs'])."
							<div class='smalltext field-help'>".PRFLAN_200."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_204."</td>
						<td class='control'>
							".$frm->radio_switch('admin_separate_plugins', $pref['admin_separate_plugins'])."
							<div class='smalltext field-help'>".PRFLAN_205."</div>
						</td>
					</tr>
				</tbody>
			</table>
			".pref_submit('admindisp')."
		</fieldset>

	";

// Date options.
$ga = new convert();
$date1 = $ga->convert_date(time(), "short");
$date2 = $ga->convert_date(time(), "long");
$date3 = $ga->convert_date(time(), "forum");

$text .= "
		<fieldset class='e-hideme' id='core-prefs-date'>
			<legend>".PRFLAN_21."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_22.": </td>
						<td class='control'>
							".$frm->text('shortdate', $pref['shortdate'], 50)."
							<div class='field-help'>".PRFLAN_83.": {$date1}</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_23.": </td>
						<td class='control'>
							".$frm->text('longdate', $pref['longdate'], 50)."
							<div class='field-help'>".PRFLAN_83.": {$date2}</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_24."</td>
						<td class='control'>
							".$frm->text('forumdate', $pref['forumdate'], 50)."
							<div class='field-help'>".PRFLAN_83.": {$date3}</div>
							<div class='field-help'>".PRFLAN_25." <a href='http://www.php.net/manual/en/function.strftime.php' rel='external'>".PRFLAN_93."</a></div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_26."</td>
						<td class='control'>
							".$frm->select_open('time_offset', 'class=tbox select time-offset');//use form handler because of the tabindex
$toffset = array("-12", "-11", "-10", "-9", "-8", "-7", "-6", "-5", "-4", "-3", "-2", "-1", "0", "+1", "+2", "+3", "+4", "+5", "+6", "+7", "+8", "+9", "+10", "+11", "+12", "+13", "+14", "+15", "+16");
if(! isset($pref['time_offset']))
{
	$pref['time_offset'] = "0";
}
foreach($toffset as $o)
{
	$text .= "
								".$frm->option($o, $o, ($o == $pref['time_offset']))."
	";
}
$text .= "
							</select>
							<div class='smalltext field-help'>".PRFLAN_27."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_56.": </td>
						<td class='control'>
							".$frm->text('timezone', $pref['timezone'], 50)."
						</td>
					</tr>
				</tbody>
			</table>
			".pref_submit('date')."
		</fieldset>
";

// =========== Registration Preferences. ==================
$text .= "
		<fieldset class='e-hideme' id='core-prefs-registration'>
			<legend>".PRFLAN_28."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_29."</td>
						<td class='control'>
							".$frm->radio_switch('user_reg', $pref['user_reg'])."
							<div class='smalltext field-help'>".PRFLAN_30."</div>
						</td>
					</tr>


					<tr>
						<td class='label'>".PRFLAN_154."</td>
						<td class='control'>
							".$frm->select_open('user_reg_veri');
                            $veri_list = array(PRFLAN_152,PRFLAN_31,PRFLAN_153);

							foreach($veri_list as $v => $v_title)
							{
								$text .= $frm->option($v_title, $v, ($pref['user_reg_veri'] == $v));
							}

					$text .= "
							</select>
							<div class='field-help'>".PRFLAN_154a."</div>
						</td>
					</tr>
                    <tr>
						<td class='label'>".PRFLAN_184."</td>
						<td class='control'>".$frm->select_open('allowEmailLogin');
                        $login_list = array(PRFLAN_201,PRFLAN_202,PRFLAN_203);
                        foreach($login_list as $l => $l_title)
						{
							$text .= $frm->option($l_title, $l, ($pref['allowEmailLogin'] == $l));
						}

					$text .= "
							</select></td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_160."</td>
						<td class='control'>
							".$frm->radio_switch('signup_remote_emailcheck', $pref['signup_remote_emailcheck'])."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_167."</td>
						<td class='control'>
							".$frm->radio_switch('disable_emailcheck', $pref['disable_emailcheck'])."
						</td>
					</tr>

					<tr>
						<td class='label'>".PRFLAN_45."</td>
						<td class='control'>
							".$frm->radio_switch('use_coppa', $pref['use_coppa'])."
							<div class='field-help'>".PRFLAN_46." <a href='http://www.ftc.gov/privacy/coppafaqs.shtm'>".PRFLAN_94."</a></div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_58."</td>
						<td class='control'>
							".$frm->radio_switch('membersonly_enabled', $pref['membersonly_enabled'])."
							<div class='field-help'>".PRFLAN_59."</div>
						</td>
					</tr>
                    <tr>
						<td class='label'>".PRFLAN_206."</td>
						<td class='control'>
							".$frm->textarea('membersonly_exceptions', $pref['membersonly_exceptions'], 3, 1)."
							<div class='field-help'>".PRFLAN_207."</div>
						</td>
					</tr>
               		<tr>
						<td class='label'>".PRFLAN_197.": </td>
						<td class='control'>
							".$frm->radio_switch('autologinpostsignup', $pref['autologinpostsignup'])."
							<div class='smalltext field-help'>".PRFLAN_198."</div>
						</td>
					</tr>

					<tr>
						<td class='label'>".CUSTSIG_16."</td>
						<td class='control'>
							".$frm->text('signup_pass_len', $pref['signup_pass_len'], 2)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_136."</td>
						<td class='control'>
							".$frm->text('signup_maxip', $pref['signup_maxip'], 3)."
							<div class='field-help'>".PRFLAN_78."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".CUSTSIG_18."</td>
						<td class='control'>
							".$frm->textarea('signup_disallow_text', $pref['signup_disallow_text'], 3, 1)."
							<div class='field-help'>".CUSTSIG_19."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_155.":</td>
						<td class='control'>
							<div class='field-spacer'>".$e_userclass->uc_dropdown('displayname_class', $pref['displayname_class'], 'nobody,member,admin,classes', "tabindex='".$frm->getNext()."'")."</div>
							".$frm->admin_button('submit_resetdisplaynames', PRFLAN_156)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_192.":</td>
						<td class='control'>
							".$frm->text('predefinedLoginName', $pref['predefinedLoginName'], 50)."
							<div class='field-help'>".PRFLAN_193."</div>
							<div class='field-help'>".PRFLAN_194."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_158.":</td>
						<td class='control'>
							".$frm->text('displayname_maxlength', $pref['displayname_maxlength'], 3)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_172.":</td>
						<td class='control'>
							".$frm->text('loginname_maxlength', $pref['loginname_maxlength'], 3)."
						</td>
					</tr>
				</tbody>
			</table>
			".pref_submit('registration')."
		</fieldset>

	";

// Signup options ===========================.


$text .= "
		<fieldset class='e-hideme' id='core-prefs-signup'>
			<legend>".PRFLAN_19."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_126."</td>
						<td class='control'>
							".$frm->textarea('signup_text', $pref['signup_text'], 3, 1)."
						</td>
					</tr>

					<tr>
						<td class='label'>".PRFLAN_140."</td>
						<td class='control'>
							".$frm->textarea('signup_text_after', $pref['signup_text_after'], 3, 1)."
						</td>
					</tr>
";

/*
					<!--
					<tr>
						<td class='label'>".CUSTSIG_13."</td>
						<td class='control'>".CUSTSIG_14."</td>
					</tr>
					-->
*/
$signup_option_title = array(CUSTSIG_2, CUSTSIG_6, CUSTSIG_7, CUSTSIG_17, CUSTSIG_20);
$signup_option_names = array("signup_option_realname", "signup_option_signature", "signup_option_image", "signup_option_class", 'signup_option_customtitle');

foreach($signup_option_names as $key => $value)
{
	$text .= "
					<tr>
						<td class='label'>".$signup_option_title[$key]."</td>
						<td class='control'>
							".$frm->radio($value, 0, !$pref[$value]).$frm->label(CUSTSIG_12, $value, 0)."&nbsp;&nbsp;
							".$frm->radio($value, 1, ($pref[$value] == 1)).$frm->label(CUSTSIG_14, $value, 1)."&nbsp;&nbsp;
							".$frm->radio($value, 2, ($pref[$value] == 2)).$frm->label(CUSTSIG_15, $value, 2)."
						</td>
					</tr>
	";
}

$text .= "
				</tbody>
			</table>
			".pref_submit('signup')."
		</fieldset>
";

// Custom Fields.


/* text render options */

if(!isset($pref['post_html']))
{
	$pref['post_html'] = '250';
	save_prefs();
}

$text .= "
		<fieldset class='e-hideme' id='core-prefs-textpost'>
			<legend>".PRFLAN_101."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_127.":</td>
						<td class='control'>
							".$frm->radio_switch('make_clickable', $pref['make_clickable'])."
							<div class='smalltext field-help'>".PRFLAN_128."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_102."?:</td>
						<td class='control'>
							".$frm->radio_switch('link_replace', $pref['link_replace'])."
							<div class='smalltext field-help'>".PRFLAN_103."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_145."?:</td>
						<td class='control'>
							".$frm->radio_switch('links_new_window', $pref['links_new_window'])."
							<div class='smalltext field-help'>".PRFLAN_146."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_104.":</td>
						<td class='control'>
							".$frm->text('link_text', $pref['link_text'], 200)."
							<div class='smalltext field-help'>".PRFLAN_105."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_107.":</td>
						<td class='control'>
							".$frm->text('email_text', $tp->post_toForm($pref['email_text']), 200)."
							<div class='smalltext field-help'>".PRFLAN_108."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_109.":</td>
						<td class='control'>
							".$frm->text('main_wordwrap', $pref['main_wordwrap'], 3)."
							<div class='smalltext field-help'>".PRFLAN_110."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_111.":</td>
						<td class='control'>
							".$frm->text('menu_wordwrap', $pref['menu_wordwrap'], 3)."
							<div class='smalltext field-help'>".PRFLAN_110."</div>
						</td>
					</tr>

					<tr>
						<td class='label'>".PRFLAN_116.":</td>
						<td class='control'>
							".$e_userclass->uc_dropdown('post_html', $pref['post_html'], 'nobody,public,member,admin,main,classes', "tabindex='".$frm->getNext()."'")."
							<div class='smalltext field-help'>".PRFLAN_117."</div>
						</td>
					</tr>

					<tr>
						<td class='label'>".PRFLAN_215.":</td>
						<td class='control'>
							".r_userclass('post_script',$pref['post_script'],'off','nobody,member,admin,main,classes')."
							<div class='smalltext field-help'>".PRFLAN_216."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_217.":</td>
						<td class='control'>
							".$frm->radio_switch('filter_script', varset($pref['filter_script'], 1))."
							<div class='smalltext field-help'>".PRFLAN_218."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_220.":</td>
						<td class='control'>
							".$frm->radio_switch('html_abuse', varset($pref['html_abuse'], 1))."
							<div class='smalltext field-help'>".PRFLAN_221."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_122.":</td>
						<td class='control'>
							".$frm->radio_switch('wysiwyg', $pref['wysiwyg'])."
							<div class='smalltext field-help'>".PRFLAN_123."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_124.":</td>
						<td class='control'>
							".$frm->radio_switch('old_np', $pref['old_np'])."
							<div class='smalltext field-help'>".PRFLAN_125."</div>
						</td>
					</tr>
					
";

if(file_exists(e_PLUGIN."geshi/geshi.php"))
{
	$text .= "
					<tr>
						<td class='label'>".PRFLAN_118."?:</div></td>
						<td class='control'>
							".$frm->radio_switch('useGeshi', $pref['useGeshi'])."
							<div class='smalltext field-help'>".PRFLAN_119."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_120."?:</td>
						<td class='control'>
							".$frm->text('defaultLanGeshi', ($pref['defaultLanGeshi'] ? $pref['defaultLanGeshi'] : "php"), 20)."
							<div class='smalltext field-help'>".PRFLAN_121."</div>
						</td>
					</tr>
	";
}
$text .= "
				</tbody>
			</table>
			".pref_submit('textpost')."
		</fieldset>
";

function multi_radio($name, $textsVals, $currentval = '')
{
	$ret = '';
	$gap = '';
	foreach($textsVals as $v => $t)
	{
		$sel = ($v == $currentval) ? " checked='checked'" : "";
		$ret .= $gap."<input type='radio' name='{$name}' value='{$v}'{$sel} /> ".$t."";
		$gap = "&nbsp;&nbsp;";
	}
	return $ret;
}

// Security Options. .
$hasGD = extension_loaded("gd");

$text .= "
		<fieldset class='e-hideme' id='core-prefs-security'>
			<legend>".PRFLAN_47."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_60."</td>

						<td class='control'>
							".$frm->radio_switch('ssl_enabled', $pref['ssl_enabled'])."
							<div class='field-help'>".PRFLAN_61."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_76.": </td>
						<td class='control'>
";

if($hasGD)
{
	$text .= $frm->radio_switch('signcode', $pref['signcode']);
}
else
{
	$text .= PRFLAN_133;
}
$text .= "
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_81.": </td>
						<td class='control'>
";

if($hasGD)
{
	$text .= $frm->radio_switch('logcode', $pref['logcode']);
}
else
{
	$text .= PRFLAN_133;
}
$text .= "
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_138.": </td>
						<td class='control'>
";
if($hasGD)
{
	$text .= $frm->radio_switch('fpwcode', $pref['fpwcode']);
}
else
{
	$text .= PRFLAN_133;
}

$text .= "
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_92.": </td>
						<td class='control'>
							".$frm->radio_switch('user_reg_secureveri', $pref['user_reg_secureveri'])."
						</td>
					</tr>

					<tr>
						<td class='label'>".PRFLAN_129.":</td>
						<td class='control'>
							".$frm->radio_switch('disallowMultiLogin', $pref['disallowMultiLogin'], LAN_YES, LAN_NO)."
							<div class='smalltext field-help'>".PRFLAN_130."</div>
						</td>
					</tr>

					<tr>
						<td class='label'>".PRFLAN_48.":</td>
						<td class='control'>
							<div class='field-spacer'>".$frm->radio_multi('user_tracking', array('cookie' => PRFLAN_49, 'session' => PRFLAN_50), $pref['user_tracking'])."</div>
							".PRFLAN_55.": <br />".$frm->text('cookie_name', $pref['cookie_name'], 20)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_188.":</td>
						<td class='control'>
							".$frm->radio_switch('passwordEncoding', varset($pref['passwordEncoding'], 0), PRFLAN_190, PRFLAN_189)."
							<div class='smalltext field-help'>".PRFLAN_191."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_178."</td>
						<td class='control'>
							".$frm->select_open('password_CHAP');
//TODO - user tracking session name - visible only if Cookie is enabled (JS)
$CHAP_list[0] = PRFLAN_180;
$CHAP_list[1] = PRFLAN_181;
$CHAP_list[2] = PRFLAN_182;

foreach($CHAP_list as $ab => $ab_title)
{
	$text .= "
								".$frm->option($ab_title, $ab, ($pref['password_CHAP'] == $ab))."
	";
}

$text .= "
							</select>
							<div class='smalltext field-help'>".PRFLAN_183."<br />".PRFLAN_179."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_40."</td>
						<td class='control'>
							".$frm->radio_switch('profanity_filter', $pref['profanity_filter'])."
							<div class='smalltext field-help'>".PRFLAN_41."</div>
						</td>
					</tr>

					<tr>
						<td class='label'>".PRFLAN_42.":</td>
						<td class='control'>
							".$frm->text('profanity_replace', $pref['profanity_replace'], 20)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_43.":</td>
						<td class='control'>
							".$frm->textarea('profanity_words', $pref['profanity_words'], 2, 59)."
							<div class='field-help'>".PRFLAN_44."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_35.":</td>
						<td class='control'>
							".$frm->radio_switch('antiflood1', $pref['antiflood1'])."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_36.":</td>
						<td class='control'>
							".$frm->text('antiflood_timeout', $pref['antiflood_timeout'], 3)."
							<div class='smalltext field-help'>".PRFLAN_38."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_37."</td>
						<td class='control'>
							".$frm->select_open('autoban');

$autoban_list = array(
	PRFLAN_113,
	PRFLAN_144,
	PRFLAN_142,
	PRFLAN_143
);

foreach($autoban_list as $ab => $ab_title)
{
	$sel = ($pref['autoban'] == $ab) ? "selected='selected'" : "";
	$text .= "
								".$frm->option($ab_title, $ab, ($pref['autoban'] == $ab))."
	";
}

$text .= "
							</select>
							<div class='field-help'>".PRFLAN_91."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_139.":</td>
						<td class='control'>
							".$frm->radio_switch('adminpwordchange', $pref['adminpwordchange'])."
						</td>
					</tr>
				</tbody>
			</table>
			".pref_submit('security')."
		</fieldset>
";

$text .= "
		<fieldset class='e-hideme' id='core-prefs-comments'>
			<legend>".PRFLAN_87."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
             		<tr>
						<td class='label'>".PRFLAN_32."</td>
						<td class='control'>
							".$frm->radio_switch('anon_post', $pref['anon_post'], LAN_YES, LAN_NO)."
							<div class='field-help'>".PRFLAN_33."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_89.": </td>
						<td class='control'>
							".$frm->radio_switch('comments_icon', $pref['comments_icon'], LAN_YES, LAN_NO)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_88.": </td>
						<td class='control'>
							".$frm->radio_switch('nested_comments', $pref['nested_comments'], LAN_YES, LAN_NO)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_90.": </td>
						<td class='control'>
							".$frm->radio_switch('allowCommentEdit', $pref['allowCommentEdit'], LAN_YES, LAN_NO)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_161.": </td>
						<td class='control'>
							".$frm->radio_switch('comments_disabled', $pref['comments_disabled'], LAN_YES, LAN_NO)."
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_166.": </td>
						<td class='control'>
							".$frm->radio_switch('comments_emoticons', $pref['comments_emoticons'], LAN_YES, LAN_NO)."
						</td>
					</tr>

				</tbody>
			</table>

			<legend>".PRFLAN_209."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>

					<tr>
						<td class='label'>".PRFLAN_208.":</td>
						<td class='control'>
							".$e_userclass->uc_dropdown('email_item_class',varset($pref['email_item_class'],e_UC_MEMBER),'nobody,admin,main,public,member,classes', "tabindex='".$frm->getNext()."'")."
						</td>
					</tr>

				</tbody>
			</table>
			".pref_submit('comments')."
		</fieldset>
	";
	
// Javascript Control
//TODO LANS
$text .= "
			<fieldset class='e-hideme' id='core-prefs-javascript'>
			<legend>Javascript Frameworks (for testing purposes only)</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>";
	
		$js_options = array(
			'auto'	=> 'Auto (on-demand)', 	// load based on dependency
			'admin'	=> 'Admin Area', 		// Always load in admin
			'front'	=> 'Front-End', 		// Always load in front-end
			'all'	=> "Both",				// Always load in admin and front-end
			'none'	=> 'Disabled' 			// disabled
		);	
	
		
		//TODO FIXME - remove JS framework dependency from front-end and backend. 
		// ie. no JS errors when prototype.js is completely disabled. 
		// no JS error with only 'e107 Core Minimum' is enabled. 
		// e107 Core Minimum should function independently of framework. 
		// ie. e107 Core Minimum: JS similar to e107 v1.0 should be loaded "e_js.php" (no framwork dependency) 
		// with basic functions like SyncWithServerTime() and expandit(), externalLinks() etc. 
		
		//TODO Move paths into js_manager and store only keys like 'prototype-local', 'core-minimum' in prefs. 
		/*		
		$js_types = array(
		  array('name'=> 'Prototype (local)',				'path'=> 'prototype/prototype.js') ,
		  array('name'=> 'Scriptaculous (local)',			'path'=> 'scriptaculous/scriptaculous.js'),
		  array('name'=> 'Scriptaculous-effects (local)',	'path'=> 'scriptaculous/effects.js'),
		  array('name'=> 'Prototype (CDN)',					'path'=> 'https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js'),
		  array('name'=> 'Scriptaculous + Effects (CDN)',	'path'=> 'https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js?load=effects'),
		  array('name'=> 'jQuery (CDN)',					'path'=> 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'),
		  array('name'=> 'jQuery UI (CDN)',					'path'=> 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js'),
		  array('name'=> 'e107 Core Minimum',				'path'=> 'e107.js'),
		  array('name'=> 'e107 Plugin Scripts (TO-DO)',	'path'=> ''), // all js that has been added by Plugins. 
		  array('name'=> 'e107 Theme Scripts (TO-DO)',	'path'=> ''), // all js that has been added by Themes. 
		);	
		*/
		
		// e107 minimum loaded by default once dependency is removed. 
		
		$js_types = array(
		  array('id'	=> 'prototype',		'name'=> 'Prototype (local)'),
		  array('id'	=> 'prototype-cdn', 'name'=> 'Prototype (CDN)'),		
		  array('id'	=> 'jquery', 		'name'=> 'jQuery (CDN)')	
 		);	
		
		
				
		
				
		foreach($js_types as $arr)
		{
			// $k = $arr['path'];
			$k = $arr['id'];
			$name = $arr['name'];
			$text .= "<tr>
				<td>".$name."</td>
				<td>".$frm->radio_multi("e_jslib_core[{$k}]",$js_options,$pref['e_jslib_core'][$k])."</td>
				</tr>";
		}
								
		$text .= "
					</tbody>
			</table>
			<table class='adminform' style='margin-top: 20px'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>Disable scripts consolidation</td>
						<td class='control'>
							".$frm->radio_switch('e_jslib_nocombine', $pref['e_jslib_nocombine'], LAN_YES, LAN_NO)."
							<div class='smalltext field-help'>If disabled, scripts will be loaded in one consolidated file</div>
						</td>
					</tr>
					<tr>
						<td class='label'>Enable consolidated scripts zlib compression:</td>
						<td class='control'>
							".$frm->radio_switch('e_jslib_gzip', $pref['e_jslib_gzip'], LAN_YES, LAN_NO)."
							<div class='smalltext field-help'>Used only when script consolidation is enabled</div>
						</td>
					</tr>
					<tr>
						<td class='label'>Disable consolidated scripts server cache:</td>
						<td class='control'>
							".$frm->radio_switch('e_jslib_nocache', $pref['e_jslib_nocache'], LAN_YES, LAN_NO)."
							<div class='smalltext field-help'>Used only when script consolidation is enabled</div>
						</td>
					</tr>
					<tr>
						<td class='label'>Disable consolidated scripts browser cache:</td>
						<td class='control'>
							".$frm->radio_switch('e_jslib_nobcache', $pref['e_jslib_nobcache'], LAN_YES, LAN_NO)."
							<div class='smalltext field-help'>Used only when script consolidation is enabled</div>
						</td>
					</tr>
		";	
					
		$text .= "
					</tbody></table>
			".pref_submit('javascript')."
					</fieldset>
					
					";
					
					
		e107::js('inline',"			
						\$\$('#e-jslib-nocombine', '#e-jslib-nocombine-1').invoke('observe', 'change', function(event) {
							var element = event.findElement('input'), check = !parseInt(element.value);
							eHandleJsForm(check);
							
						});
						
						var eHandleJsForm = function(enable) {
							var collection = \$w('e-jslib-gzip e-jslib-nocache e-jslib-nobcache');
							collection.each(function(id) {
								var method = enable ? 'enable' : 'disable';
								\$\$('#' + id, '#' + id + '-1').invoke(method);
							});
						};
						
						eHandleJsForm(".($pref['e_jslib_nocombine'] ? 'false' : 'true').");
					
					","prototype");
	
	

//Advanced Features
$text .= "
		<fieldset class='e-hideme' id='core-prefs-advanced'>
			<legend>".PRFLAN_149."</legend>
			<table class='adminform'>
				<colgroup>
					<col class='col-label' />
					<col class='col-control' />
				</colgroup>
				<tbody>
					<tr>
						<td class='label'>".PRFLAN_147.":</td>
						<td class='control'>
							".$frm->radio_switch('developer', $pref['developer'])."
							<div class='smalltext field-help'>".PRFLAN_148."</div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_196."</td>
						<td class='control'>
						".$frm->radio_switch('log_page_accesses', $pref['log_page_accesses'])."
						<div class='field-help'>".PRFLAN_196a." <strong>".e_LOG_ABS."</strong></div>
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_17."</td>
						<td class='control'>
							".$frm->radio_switch('compress_output', $pref['compress_output'])."
						</td>
					</tr>
";

$auth_dropdown = '';
if($authlist)
{
	$auth_dropdown = "\n".$frm->select_open('auth_method')."\n";
	foreach($authlist as $a)
	{
		$auth_dropdown .= $frm->option($a, $a, ($pref['auth_method'] == $a))."\n";
	}
	$auth_dropdown .= "</select>\n";
}
else
{
	$auth_dropdown = "<input type='hidden' name='auth_method' value='' />".PRFLAN_151;
	$pref['auth_method'] = "";
}

$text .= "
					<tr>
						<td class='label'>".PRFLAN_150."</td>
						<td class='control'>
							{$auth_dropdown}
						</td>
					</tr>
					<tr>
						<td class='label'>".PRFLAN_173."</td>
						<td class='control'>
							".$frm->radio_switch('check_updates', $pref['check_updates'])."
						</td>
					</tr>
				</tbody>
			</table>
			".pref_submit('advanced')."
		</fieldset>
	";

// END Advanced Features


$text .= "
	</form>
</div>
";

$e107->ns->tablerender(PRFLAN_53, $emessage->render().$text);

require_once(e_ADMIN."footer.php");

function pref_submit($post_id = '')
{
	global $frm;
	if($post_id) $post_id = '-'.$post_id;
	$text = "
		<div class='buttons-bar center'>";

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
	$text .= $frm->admin_button('updateprefs', PRFLAN_52, 'update', '', "id=updateprefs{$post_id}");
	// }
	$text .= "\n</div>";

	// END ML
	return $text;
}

function prefs_adminmenu()
{
	$var['core-prefs-main']['text'] = PRFLAN_1;
	$var['core-prefs-display']['text'] = PRFLAN_13;
	$var['core-prefs-admindisp']['text'] = PRFLAN_77;
	$var['core-prefs-date']['text'] = PRFLAN_21;
	$var['core-prefs-registration']['text'] = PRFLAN_28;
	$var['core-prefs-signup']['text'] = PRFLAN_19;
	$var['core-prefs-textpost']['text'] = PRFLAN_101;
	$var['core-prefs-security']['text'] = PRFLAN_47;
	$var['core-prefs-comments']['text'] = PRFLAN_210;
	$var['core-prefs-javascript']['text'] = "Javascript Framework"; // TODO LAN
	$var['core-prefs-advanced']['text'] = PRFLAN_149;
	e_admin_menu(LAN_OPTIONS.'--id--prev_nav', 'core-prefs-main', $var);
}

