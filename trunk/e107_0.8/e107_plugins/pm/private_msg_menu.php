<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *	PM plugin - menu display
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/pm/private_msg_menu.php,v $
 * $Revision: 1.12 $
 * $Date: 2009-12-17 22:47:20 $
 * $Author: e107steved $
 */


/**
 *	e107 Private messenger plugin
 *
 *	@package	e107_plugins
 *	@subpackage	pm
 *	@version 	$Id: private_msg_menu.php,v 1.12 2009-12-17 22:47:20 e107steved Exp $;
 */

if (!defined('e107_INIT')) { exit; }
if (!e107::isInstalled('pm')) { return ''; }
global $sysprefs, $pm_prefs;
if(!isset($pm_prefs['perpage']))
{
	$pm_prefs = $sysprefs->getArray('pm_prefs');
}
require_once(e_PLUGIN.'pm/pm_func.php');
e107::getScParser();
require_once(e_PLUGIN.'pm/pm_shortcodes.php');
setScVar('pm_handler_shortcodes','pmPrefs', $pm_prefs);
$pmManager = new pmbox_manager($pm_prefs);
setScVar('pm_handler_shortcodes','pmManager', &$pmManager);

define('PM_INBOX_ICON', "<img src='".e_PLUGIN_ABS."pm/images/mail_get.png' class='icon S16' alt='".LAN_PM_25."' title='".LAN_PM_25."' />");
define('PM_OUTBOX_ICON', "<img src='".e_PLUGIN_ABS."pm/images/mail_send.png' class='icon S16' alt='".LAN_PM_26."' title='".LAN_PM_26."' />");
define('PM_SEND_LINK', LAN_PM_35);
define('NEWPM_ANIMATION', "<img src='".e_PLUGIN_ABS."pm/images/newpm.gif' alt='' />");


$sc_style['PM_SEND_PM_LINK']['pre'] = "<br /><br />[ ";
$sc_style['PM_SEND_PM_LINK']['post'] = " ]";

$sc_style['PM_INBOX_FILLED']['pre'] = "[";
$sc_style['PM_INBOX_FILLED']['post'] = "%]";

$sc_style['PM_OUTBOX_FILLED']['pre'] = "[";
$sc_style['PM_OUTBOX_FILLED']['post'] = "%]";

$sc_style['PM_NEWPM_ANIMATE']['pre'] = "<a href='".e_PLUGIN_ABS."pm/pm.php?inbox'>";
$sc_style['PM_NEWPM_ANIMATE']['post'] = "</a>";

$sc_style['PM_BLOCKED_SENDERS_MANAGE']['pre'] = "<br />[ <a href='".e_PLUGIN_ABS."pm/pm.php?blocked'>";
$sc_style['PM_BLOCKED_SENDERS_MANAGE']['post'] = '</a> ]';


if(!isset($pm_menu_template))
{
	$pm_menu_template = "
	<a href='{URL=pm|main|f=box&box=inbox}'>".PM_INBOX_ICON."</a>
	<a href='{URL=pm|main|f=box&box=inbox}'>".LAN_PM_25."</a>
	{PM_NEWPM_ANIMATE}
	<br />
	{PM_INBOX_TOTAL} ".LAN_PM_36.", {PM_INBOX_UNREAD} ".LAN_PM_37." {PM_INBOX_FILLED}
	<br />
	<a href='{URL=pm|main|f=box&box=outbox}'>".PM_OUTBOX_ICON."</a>
	<a href='{URL=pm|main|f=box&box=outbox}'>".LAN_PM_26."</a><br />
	{PM_OUTBOX_TOTAL} ".LAN_PM_36.", {PM_OUTBOX_UNREAD} ".LAN_PM_37." {PM_OUTBOX_FILLED}
	{PM_SEND_PM_LINK}
	{PM_BLOCKED_SENDERS_MANAGE}
	";
}


if(check_class($pm_prefs['pm_class']))
{
	$tp = e107::getParser();
	$pm_inbox = $pmManager->pm_getInfo('inbox');
	$txt = $tp->parseTemplate($pm_menu_template, TRUE);
	if($pm_inbox['inbox']['new'] > 0 && $pm_prefs['popup'] && strpos(e_SELF, 'pm.php') === FALSE && $_COOKIE['pm-alert'] != 'ON')
	{
		$txt .= pm_show_popup($pm_inbox, $pm_prefs);
	}
	$ns->tablerender(LAN_PM, $txt, 'pm');
}


function pm_show_popup($pm_inbox, $pm_prefs)
{
	$alertdelay = intval($pm_prefs['popup_delay']);
	if($alertdelay == 0) { $alertdalay = 60; }
	setcookie('pm-alert', 'ON', time()+$alertdelay);
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
	$popuptext = str_replace("\n", '', $popuptext);
	$popuptext = str_replace("\t", '', $popuptext);
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