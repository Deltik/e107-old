<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
if(!defined("e_THEME")){ exit; }
// [theme]

$themename = "Jayya";
$themeversion = "1.0";
$themeauthor = "e107";
$themedate = "22/12/04";
$themeinfo = "";
$xhtmlcompliant = TRUE;
$csscompliant = TRUE;

define("THEME_DISCLAIMER", "");
define("IMODE", "lite");

// [output js nav css in <head>]

function theme_head() {
	return "<link rel='stylesheet' href='".THEME."nav_menu.css' />\n";
}


// [dont render core style sheet link]

$no_core_css = TRUE;


// [layout]
// <img src='".THEME."images/logo.png' style='width: 170px; height: 71px; display: block;' alt='' />
$layout = "_default";

$HEADER = "<table class='top_section'>
<tr>
<td class='top_section_left' style='width: 190px; padding-left: 5px; padding-right: 5px'>
{LOGO}
</td>
<td class='top_section_mid'>
{BANNER}
</td>

<td class='top_section_right' style='padding: 0px; white-space: nowrap; width: 170px'>
{CUSTOM=search+default}
</td>
</tr>
</table>

<div>
{SITELINKS_ALT}
</div>

<table class='main_section'>
<tr>
<td class='left_menu'>
{SETSTYLE=leftmenu}
{MENU=1}
</td>
<td class='default_menu'>
{SETSTYLE=default}
{WMESSAGE}
";

$FOOTER = "<br />
</td>

<td class='right_menu'>
{SETSTYLE=rightmenu}
{MENU=2}
</td>
</tr>
</table>
<div style='text-align:center'>
<br />
{SITEDISCLAIMER}
<br /><br />
</div>
";


// [linkstyle]

define("PRELINK", "");
define("POSTLINK", "");
define("LINKSTART", "");
define("LINKEND", "");
define("LINKDISPLAY", 1);
define("LINKALIGN", "right");


// [newsstyle]

$NEWSSTYLE = "<div class='cap_border'><div class='main_caption'>
{STICKY_ICON}{NEWSTITLE}
</div></div>
<div class='menu_content'>
{NEWSBODY}
{EXTENDED}
<br /></div>
<div class='menu_content'>
<table class='news_info'>
<tr>
<td style='text-align: center; padding: 3px; padding-bottom: 0px; white-space: nowrap'>
<img src='".THEME."images/postedby_16.png' style='width: 16px; height: 16px' alt='' />
</td>
<td style='width: 100%; padding: 0px; padding-bottom: 0px; padding-left: 2px'>
Posted by 
{NEWSAUTHOR}
 on 
{NEWSDATE}

</td><td style='text-align: center; padding: 3px; padding-bottom: 0px; white-space: nowrap'>
<img src='".THEME."images/comments_16.png' style='width: 16px; height: 16px' alt='' />
</td>
<td style='padding: 0px; padding-bottom: 0px; padding-left: 2px; white-space: nowrap'>
{NEWSCOMMENTS}
</td><td style='text-align: center; padding: 3px; padding-bottom: 0px; padding-left: 7px; white-space: nowrap'>
{EMAILICON}
{PRINTICON}
</td></tr></table>
<br /></div>";

define("ICONMAIL", "email_16.png");
define("ICONPRINT", "print_16.png");
define("ICONSTYLE", "float: left; border:0");
define("COMMENTLINK", "Read/Post Comment: ");
define("COMMENTOFFSTRING", "Comments are turned off for this item");
define("PRE_EXTENDEDSTRING", "<br /><br />[ ");
define("EXTENDEDSTRING", "Read the rest...");
define("POST_EXTENDEDSTRING", " ]<br />");


//	[tablestyle]

function tablestyle($caption, $text, $mode){
	global $style;
	if ($caption == '') { $caption = '&nbsp;'; }
	$bodytable = (isset($mode['style']) && $mode['style'] == 'button_menu') ? 'menu_content_buttons' : 'menu_content';
	$bodybreak = (isset($mode['style']) && $mode['style'] == 'button_menu') ? '' : '<br />';
	$r_caption_bord_but = (isset($mode['style']) && $mode['style'] == 'button_menu') ? ' button_menu' : '';
	if ($style == "leftmenu") {
		echo "<div class='cap_border'><div class='left_caption'>".$caption."</div></div>";
		if ($text != "") {
			echo "<div class='menu_content'>
			".$text."<br /></div>";
		}
	}  else if ($style == "rightmenu") {
		echo "<div class='cap_border".$r_caption_bord_but."'>
		<div class='right_caption'>".$caption."</div>
		</div>";
		if ($text != "") {
			echo "<div class='".$bodytable."'>".$text.$bodybreak."</div>";
		}
	} else {
		echo "<div class='cap_border'>
		<div class='main_caption'>".$caption."</div>
		</div>";
		if ($text != "") {
			echo "<div class='menu_content'>".$text."<br /></div>";
		}
	}
}

$CHATBOXSTYLE = "
<img src='".e_IMAGE."admin_images/chatbox_16.png' alt='' style='width: 16px; height: 16px; vertical-align: bottom' />
<b>{USERNAME}</b><br />{TIMEDATE}<br />{MESSAGE}<br /><br />";

$COMMENTSTYLE = "
<table style='width: 100%;'>
<tr>
<td style='width: 10%;'>{USERNAME}<br />{TIMEDATE}<br />{AVATAR}<br />{REPLY}</td>
<td style='width: 90%; background-color: #f9f9fd; vertical-align: top; padding: 4px'>
{COMMENT}
</td>
</tr>
</table>
<br />
";

$POLLSTYLE = "<img src='".THEME."images/polls.png' style='width: 10px; height: 14px; vertical-align: bottom' /> {QUESTION}
<br /><br />
{OPTIONS=<img src='".THEME."images/bullet2.gif' style='width: 10px; height: 10px' /> OPTION<br />BAR<br /><span class='smalltext'>PERCENTAGE VOTES</span><br /><br />}
<div style='text-align:center' class='smalltext'>{AUTHOR}<br />{VOTE_TOTAL} {COMMENTS}
<br />
{OLDPOLLS}
</div>";

?>
