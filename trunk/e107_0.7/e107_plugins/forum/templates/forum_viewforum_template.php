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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/forum/templates/forum_viewforum_template.php,v $
|     $Revision: 1.7 $
|     $Date: 2005-06-03 18:24:47 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
if (!$FORUM_VIEW_START)
{
$FORUM_VIEW_START = "

	<div style='text-align:center'>
	<div class='spacer'>
	<table style='width:95%' class='fborder' >
	<tr>
		<td  colspan='2' class='fcaption'>{BREADCRUMB}</td>
	</tr>
	{SUBFORUMS}
	<tr>
		<td style='width:80%; vertical-align:middle; text-align: left;'>&nbsp;<span class='mediumtext'>{FORUMTITLE}</span><br />{THREADPAGES}</td>
		<td style='width:20%; text-align:right'>
		{NEWTHREADBUTTON}
		</td>
	</tr>
	<tr>
		<td colspan='2'>

	<table style='width:100%' class='fborder'>
	<tr>
	<td style='width:3%' class='fcaption'>&nbsp;</td>
	<td style='width:47%' class='fcaption'>{THREADTITLE}</td>
	<td style='width:20%; text-align:center' class='fcaption'>{STARTERTITLE}</td>
	<td style='width:5%; text-align:center' class='fcaption'>{REPLYTITLE}</td>
	<td style='width:5%; text-align:center' class='fcaption'>{VIEWTITLE}</td>
	<td style='width:20%; text-align:center' class='fcaption'>{LASTPOSTITLE}</td>
	</tr>";
}

if (!$FORUM_VIEW_FORUM) {
	global $sc_style;
	$sc_style['THREADTYLE']['post'] = "<br />";
	$FORUM_VIEW_FORUM = "
		<tr>
		<td style='vertical-align:middle; text-align:center; width:3%' class='forumheader3'>{ICON}</td>
		<td style='vertical-align:middle; text-align:left; width:47%'  class='forumheader3'>

		<table style='width:100%'>
		<tr>
		<td style='width:90%'><span class='mediumtext'><b>{THREADTYPE}{THREADNAME}</b></span> <span class='smalltext'>{PAGES}</span></td>
		<td style='width:10%; white-space:nowrap;'>{ADMIN_ICONS}</td>
		</tr>
		</table>

		</td>

		<td style='vertical-align:center; text-align:center; width:20%' class='forumheader3'>{POSTER}<br />{THREADDATE}</td>
		<td style='vertical-align:center; text-align:center; width:5%' class='forumheader3'>{REPLIES}</td>
		<td style='vertical-align:center; text-align:center; width:5%' class='forumheader3'>{VIEWS}</td>
		<td style='vertical-align:center; text-align:center; width:20%' class='forumheader3'>{LASTPOST}</td>
		</tr>";
}

if (!$FORUM_VIEW_END) {
	$FORUM_VIEW_END = "
		</table>
		<table style='width:100%'>
		<tr>
		<td style='width:80%'><span class='mediumtext'>{THREADPAGES}</span>
		{FORUMJUMP}
		</td>
		<td style='width:20%; text-align:right'>
		{NEWTHREADBUTTON}
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
        </div>
		<div class='spacer'>
		<table class='fborder' style='width:95%'>
		<tr>
		<td style='vertical-align:center; width:50%' class='forumheader3'><span class='smalltext'>{MODERATORS}</span></td>
		<td style='vertical-align:center; width:50%' class='forumheader3'><span class='smalltext'>{BROWSERS}</span></td>
		</tr>
		</table>
		</div>
		<div class='spacer'>
		<table class='fborder' style='width:95%'>
		<tr>
		<td style='vertical-align:center; width:50%' class='forumheader3'>{ICONKEY}</td>
		<td style='vertical-align:center; text-align:center; width:50%' class='forumheader3'><span class='smallblacktext'>{PERMS}</span><br /><br />{SEARCH}
		</td>
		</tr>
		</table>
		</div>
		</div>
		<div style='text-align:center;'>
		<a href='".e_PLUGIN."rss_menu/rss.php?11.1.".e_QUERY."'><img src='".e_PLUGIN."rss_menu/images/rss1.png' alt='".LAN_431."' style='vertical-align: middle; border: 0;' /></a> 
		<a href='".e_PLUGIN."rss_menu/rss.php?11.2.".e_QUERY."'><img src='".e_PLUGIN."rss_menu/images/rss2.png' alt='".LAN_432."' style='vertical-align: middle; border: 0;' /></a> 
		<a href='".e_PLUGIN."rss_menu/rss.php?11.3.".e_QUERY."'><img src='".e_PLUGIN."rss_menu/images/rss3.png' alt='".LAN_433."' style='vertical-align: middle; border: 0;' /></a>
		</div>
		<div class='nforumdisclaimer' style='text-align:center'>Powered by <b>e107 Forum System</b></div>
";
}

if (!$FORUM_VIEW_SUB_START)
 {
	$FORUM_VIEW_SUB_START = "
	<tr>
	<td colspan='2'>
		<br />
		<div>
		<table style='width:100%'>
		<tr>
			<td class='fcaption' style='width: 50%'>".FORLAN_20."</td>
			<td class='fcaption' style='width: 10%'>".FORLAN_21."</td>
			<td class='fcaption' style='width: 10%'>".LAN_55."</td>
			<td class='fcaption' style='width: 30%'>".FORLAN_22."</td>
		</tr>
	";
}

if (!$FORUM_VIEW_SUB) {
	$FORUM_VIEW_SUB = "
	<tr>
		<td class='forumheader3' style='text-align:left'><b>{SUB_FORUMTITLE}</b><br />{SUB_DESCRIPTION}</td>
		<td class='forumheader3' style='text-align:center'>{SUB_THREADS}</td>
		<td class='forumheader3' style='text-align:center'>{SUB_REPLIES}</td>
		<td class='forumheader3' style='text-align:center'>{SUB_LASTPOST}</td>
	</tr>
	";
}

if (!$FORUM_VIEW_SUB_END) {
	$FORUM_VIEW_SUB_END = "
	</table><br /><br />
	</div>
	</td>
	</tr>
	";
}

?>