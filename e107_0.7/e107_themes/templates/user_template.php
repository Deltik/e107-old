<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL$
|     $Revision$
|     $Id$
|     $Author$
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }
if (!defined("USER_WIDTH")){ define("USER_WIDTH","width:95%"); }

global $user_shortcodes, $pref, $user;
//Set this to TRUE if you would like any extended user field that is empty to NOT be shown on the profile page
define("HIDE_EMPTY_FIELDS", FALSE);

$EXTENDED_CATEGORY_START = "<tr><td colspan='2' class='forumheader' style='text-align:left'>{EXTENDED_NAME}</td></tr>";

$EXTENDED_CATEGORY_TABLE = "
	<tr>
		<td style='width:40%' class='forumheader3'>
			{EXTENDED_ICON}&nbsp;
			{EXTENDED_NAME}
		</td>
		<td style='width:60%' class='forumheader3'>{EXTENDED_VALUE}</td>
	</tr>
	";

$EXTENDED_CATEGORY_END = "";


$USER_SHORT_TEMPLATE_START = "
	<div style='text-align:center'>".LAN_138." {TOTAL_USERS}
	<br />
	<br />
	{USER_FORM_START}
	<p>".LAN_419.": {USER_FORM_RECORDS} ".LAN_139." {USER_FORM_ORDER}
	{USER_FORM_SUBMIT}
	</p>
	{USER_FORM_END}
	</div>
	<br />
	<br />
	<table style='".USER_WIDTH."' class='fborder'>
	<tr>
	<td class='fcaption' style='width:2%'>&nbsp;</td>
	<td class='fcaption' style='width:20%'>".LAN_142."</td>
	<td class='fcaption' style='width:20%'>".LAN_112."</td>
	<td class='fcaption' style='width:20%'>".LAN_145."</td>
	</tr>
";
$USER_SHORT_TEMPLATE_END = "
</table>
";

$USER_SHORT_TEMPLATE = "
<tr>
	<td class='forumheader3' style='width:2%'>{USER_ICON_LINK}</td>
	<td class='forumheader' style='width:20%'>{USER_ID}: {USER_NAME_LINK}</td>
	<td class='forumheader3' style='width:20%'>{USER_EMAIL}</td>
	<td class='forumheader3' style='width:20%'>{USER_JOIN}</td>
</tr>
";

$sc_style['USER_SIGNATURE']['pre'] = "<tr><td colspan='2' class='forumheader3' style='text-align:left'>";
$sc_style['USER_SIGNATURE']['post'] = "</td></tr>";

$sc_style['USER_COMMENTS_LINK']['pre'] = "<tr><td colspan='2' class='forumheader3' style='text-align:left'>";
$sc_style['USER_COMMENTS_LINK']['post'] = "</td></tr>";

$sc_style['USER_FORUM_LINK']['pre'] = "<tr><td colspan='2' class='forumheader3' style='text-align:left'>";
$sc_style['USER_FORUM_LINK']['post'] = "</td></tr>";

$sc_style['USER_UPDATE_LINK']['pre'] = "<tr><td colspan='2' class='forumheader3' style='text-align:center'>";
$sc_style['USER_UPDATE_LINK']['post'] = "</td></tr>";

$sc_style['USER_RATING']['pre'] = "<tr><td colspan='2' style='width:100%' class='forumheader3'><span style='float:left'>".LAN_406."</span><span style='float:right;'>";
$sc_style['USER_RATING']['post'] = "</span></td></tr>";

$sc_style['USER_LOGINNAME']['pre'] = " : ";

if(isset($pref['photo_upload']) && $pref['photo_upload'])
{
	$user_picture =  "{USER_PICTURE}";
	$colspan = " colspan='2'";
	$main_colspan = "";
}
else
{
	$user_picture =  "";
	$colspan = "";
	$main_colspan = " colspan = '2' ";
}

$sc_style['USER_SENDPM']['pre'] = "<tr><td colspan='2' style='width:100%' class='forumheader3'><span style='float:left'>";
$sc_style['USER_SENDPM']['post'] = "</span><span style='float:right;'>".LAN_425."</span></td></tr>";

// Determine which other bits are installed; let photo span those rows (can't do signature - will vary with user)
$span = 4;
if ($tp->parseTemplate("{USER_SENDPM}", FALSE, $user_shortcodes)) $span++;
$span = " rowspan='".$span."' ";

$sc_style['USER_PICTURE']['pre']="<td {$span} class='forumheader3' style='width:20%; vertical-align:middle; text-align:center'>";
$sc_style['USER_PICTURE']['post']="</td>";

$USER_FULL_TEMPLATE = "
<div style='text-align:center'>
<table style='".USER_WIDTH."' class='fborder'>
<tr>
	<td colspan='2' class='fcaption' style='text-align:center'>".LAN_142." {USER_ID} : {USER_NAME}{USER_LOGINNAME}</td>
</tr>
<tr>
	{$user_picture}
	<td {$main_colspan} class='forumheader3' style='width:100%'>
		<span style='float:left'>{USER_REALNAME_ICON} ".LAN_308."</span>
		<span style='float:right; text-align:right'>{USER_REALNAME}</span>
	</td>
</tr>

<tr>
	<td  {$main_colspan} style='width:100%' class='forumheader3'>
		<span style='float:left'>{USER_EMAIL_ICON} ".LAN_112."</span>
		<span style='float:right; text-align:right'>{USER_EMAIL_LINK}</span>
	</td>
</tr>

<tr>
	<td  {$main_colspan} style='width:100%' class='forumheader3'>
		<span style='float:left'>".LAN_406.":</span>
		<span style='float:right; text-align:right'>{USER_LEVEL}</span>
	</td>
</tr>

<tr>
	<td  {$main_colspan} style='width:100%' class='forumheader3'>
		<span style='float:left'>".LAN_404.":&nbsp;&nbsp;</span>
		<span style='float:right; text-align:right'>{USER_LASTVISIT}<br />{USER_LASTVISIT_LAPSE}</span>
	</td>
</tr>
{USER_SENDPM}
{USER_RATING}
{USER_SIGNATURE}
{USER_EXTENDED_ALL}
<tr>
	<td colspan='2' class='forumheader'>".LAN_403."</td>
</tr>

<tr>
	<td style='width:30%' class='forumheader3'>".LAN_145."</td>
	<td style='width:70%' class='forumheader3'>{USER_JOIN}<br />{USER_DAYSREGGED}</td>
</tr>

<tr>
	<td style='width:30%' class='forumheader3'>".LAN_147."</td>
	<td style='width:70%' class='forumheader3'>{USER_CHATPOSTS} ( {USER_CHATPER}% )</td>
</tr>

<tr>
	<td style='width:30%' class='forumheader3'>".LAN_148."</td>
	<td style='width:70%' class='forumheader3'>{USER_COMMENTPOSTS} ( {USER_COMMENTPER}% )</td>
</tr>
{USER_COMMENTS_LINK}

<tr>
	<td style='width:30%' class='forumheader3'>".LAN_149."</td>
	<td style='width:70%' class='forumheader3'>{USER_FORUMPOSTS} ( {USER_FORUMPER}% )</td>
</tr>
{USER_FORUM_LINK}
<tr>
	<td style='width:30%' class='forumheader3'>".LAN_146."</td>
	<td style='width:70%' class='forumheader3'>{USER_VISITS}</td>
</tr>
{USER_UPDATE_LINK}
<tr>
	<td colspan='2' class='forumheader3' style='text-align:center'>
		<table style='width:95%'>
			<tr>
				<td style='width:50%'>{USER_JUMP_LINK=prev}</td>
				<td style='width:50%; text-align:right'>{USER_JUMP_LINK=next}</td>
			</tr>
		</table>
	</td>
</tr>
</table></div>
{PROFILE_COMMENTS}
{PROFILE_COMMENT_FORM}
";
?>