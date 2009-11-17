<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/pm/private_msg_menu.php,v $
 * $Revision: 1.8 $
 * $Date: 2009-11-17 13:48:45 $
 * $Author: marj_nl_fr $
 */

if (!defined('e107_INIT')) { exit; }
global $sysprefs, $pref, $pm_prefs;
if(!isset($pm_prefs['perpage']))
{
	$pm_prefs = $sysprefs->getArray("pm_prefs");
}
require_once(e_PLUGIN."pm/pm_func.php");
pm_getInfo('clear');

define("PM_INBOX_ICON", "<img src='".e_PLUGIN_ABS."pm/images/mail_get.png' class='icon S16' alt='".LAN_PM_25."' title='".LAN_PM_25."' />");
define("PM_OUTBOX_ICON", "<img src='".e_PLUGIN_ABS."pm/images/mail_send.png' class='icon S16' alt='".LAN_PM_26."' title='".LAN_PM_26."' />");
define("PM_SEND_LINK", LAN_PM_35);
define("NEWPM_ANIMATION", "<img src='".e_PLUGIN_ABS."pm/images/newpm.gif' alt='' />");


$sc_style['SEND_PM_LINK']['pre'] = "<br /><br />[ ";
$sc_style['SEND_PM_LINK']['post'] = " ]";

$sc_style['INBOX_FILLED']['pre'] = "[";
$sc_style['INBOX_FILLED']['post'] = "%]";

$sc_style['OUTBOX_FILLED']['pre'] = "[";
$sc_style['OUTBOX_FILLED']['post'] = "%]";

$sc_style['NEWPM_ANIMATE']['pre'] = "<a href='".e_PLUGIN_ABS."pm/pm.php?inbox'>";
$sc_style['NEWPM_ANIMATE']['post'] = "</a>";


if(!isset($pm_menu_template))
{
	$pm_menu_template = "
	<a href='{URL=pm|main|f=box&box=inbox}'>".PM_INBOX_ICON."</a>
	<a href='{URL=pm|main|f=box&box=inbox}'>".LAN_PM_25."</a>
	{NEWPM_ANIMATE}
	<br />
	{INBOX_TOTAL} ".LAN_PM_36.", {INBOX_UNREAD} ".LAN_PM_37." {INBOX_FILLED}
	<br />
	<a href='{URL=pm|main|f=box&box=outbox}'>".PM_OUTBOX_ICON."</a>
	<a href='{URL=pm|main|f=box&box=outbox}'>".LAN_PM_26."</a><br />
	{OUTBOX_TOTAL} ".LAN_PM_36.", {OUTBOX_UNREAD} ".LAN_PM_37." {OUTBOX_FILLED}
	{SEND_PM_LINK}
	";
}


if(check_class($pm_prefs['pm_class']))
{
	global $tp, $pm_inbox;
	$pm_inbox = pm_getInfo('inbox');
	require_once(e_PLUGIN."pm/pm_shortcodes.php");
	$txt = $tp->parseTemplate($pm_menu_template, TRUE, $pm_shortcodes);
	if($pm_inbox['inbox']['new'] > 0 && $pm_prefs['popup'] && strpos(e_SELF, "pm.php") === FALSE && $_COOKIE["pm-alert"] != "ON")
	{
		$txt .= pm_show_popup();
	}
	$ns->tablerender(LAN_PM, $txt, 'pm');
}

function pm_show_popup()
{
	global $pm_inbox, $pm_prefs;
	$alertdelay = intval($pm_prefs['popup_delay']);
	if($alertdelay == 0) { $alertdalay = 60; }
	setcookie("pm-alert", "ON", time()+$alertdelay);
	$popuptext = "
	<html>
		<head>
			<title>".$pm_inbox['inbox']['new']." ".LAN_PM_109."</title>
			<link rel=stylesheet href=" . THEME . "style.css>
		</head>
		<body style=\'padding-left:2px;padding-right:2px; padding:2px; padding-bottom:2px; margin:0px; text-align:center\' marginheight=\'0\' marginleft=\'0\' topmargin=\'0\' leftmargin=\'0\'>
		<table style=\'width:100%; text-align:center; height:99%; padding-bottom:2px\' class=\'bodytable\'>
			<tr>
				<td width=100% >
					<center><b>--- ".LAN_PM." ---</b><br />".$pm_inbox['inbox']['new']." ".LAN_PM_109."<br />".$pm_inbox['inbox']['unread']." ".LAN_PM_37."<br /><br />
					<form>
						<input class=\'button\' type=\'submit\' onclick=\'self.close();\' value = \'".LAN_PM_110."\' />
					</form>
					</center>
				</td>
			</tr>
		</table>
		</body>
	</html> ";
	$popuptext = str_replace("\n", "", $popuptext);
	$popuptext = str_replace("\t", "", $popuptext);
	$text .= "
	<script type='text/javascript'>
	winl=(screen.width-200)/2;
	wint = (screen.height-100)/2;
	winProp = 'width=200,height=100,left='+winl+',top='+wint+',scrollbars=no';
	window.open('javascript:document.write(\"".$popuptext."\");', 'pm_popup', winProp);
	</script >";
	return $text;
}
?>