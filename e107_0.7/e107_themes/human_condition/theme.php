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
|     $Source: /cvs_backup/e107_0.7/e107_themes/human_condition/theme.php,v $
|     $Revision: 1.10 $
|     $Date: 2005-06-23 11:24:38 $
|     $Author: lisa_ $
+----------------------------------------------------------------------------+
*/
if(!defined("e_THEME")){ exit; }

// [multilanguage]
@include_once(e_THEME."human_condition/languages/".e_LANGUAGE.".php");
@include_once(e_THEME."human_condition/languages/English.php");

// [theme]
$themename = "human condition";
$themeversion = "1.0";
$themeauthor = "Steve Dunstan [jalist]";
$themeemail = "jalist@e107.org";
$themewebsite = "http://e107.org";
$themedate = "19/01/2005";
$themeinfo = "based on the Wordpress theme, <a href='http://wordpress.org'>http://wordpress.org</a>";
define("STANDARDS_MODE", TRUE);
$xhtmlcompliant = TRUE;
$csscompliant = TRUE;
define("IMODE", "lite");
define("THEME_DISCLAIMER", "<br /><br /><i>".LAN_THEME_1."</i>");

// [layout]

$layout = "_default";

$HEADER = "
<div id='rap'>
<h1 id='header'><a href='#' title='e107 v0.7'>".$pref['sitename']."</a></h1>
<div class='post'>
{SETSTYLE=post}
";


$FOOTER = "
{SETSTYLE=default}
</div>
<div id='menu'>
{SITELINKS}
{MENU=1}
<br /><hr /><span class='smalltext'>{SITEDISCLAIMER}<br />{THEME_DISCLAIMER}</span>
</div>
</div>

";

define("TP_commenticon", "<img src='".THEME."images/comment.png' alt='' style='vertical-align:middle;' />");

$NEWSSTYLE = "
<div class='textstyle4'>{STICKY_ICON}<b>{NEWSTITLE}</b></div>
<div class='postinfo'>{NEWSCATEGORY}: {NEWSAUTHOR} @ {NEWSDATE}</div>
<div class='textstyle3'>{NEWSBODY}</div>
<div class='postinfo'>".TP_commenticon." {NEWSCOMMENTS}{TRACKBACK}</div>\n<br />\n";
define("TRACKBACKSTRING", LAN_THEME_5);
define("TRACKBACKBEFORESTRING", " | ");


define("DATEHEADERCLASS", "button");
//	define("DATEHEADERCLASS", "nextprev");	// uncomment this line for a different style of news date header

define("ICONSTYLE", "float: left; border:0");
define("COMMENTLINK", LAN_THEME_3);
define("COMMENTOFFSTRING", LAN_THEME_2);

define("PRE_EXTENDEDSTRING", "<br /><br />[ ");
define("EXTENDEDSTRING", LAN_THEME_4);
define("POST_EXTENDEDSTRING", " ]<br />");


// [linkstyle]

define(PRELINK, "");
define(POSTLINK, "");
define(LINKSTART, "<span><img src='".THEME."images/bullet2.gif' alt='bullet' /> ");
define(LINKSTART_HILITE, "<span style='font-weight:bold'><img src='".THEME."images/bullet2.png' alt='bullet' /> ");
define(LINKEND, "</span><br />");
define(LINKDISPLAY, 2);                        // 1 - along top, 2 - in left or right column
define(LINKALIGN, "left");


//        [tablestyle]

function tablestyle($caption, $text, $mode="")
{
	global $style;

	if($style == "post")
	{

		if(!$caption)
		{
			echo "<div class='spacer'>$text</div>\n";
		}
		else if(!$text)
		{
			echo "<div class='spacer'><div class='date'>$caption</div></div>\n";
		}
		else
		{
			echo "<div class='spacer'><div class='date'>$caption</div>\n$text\n</div>\n";
		}
	}
	else
	{
		if(!$caption)
		{
			echo "<div class='spacer'>$text</div>\n";
		}

		else if(!$text)
		{
			echo "<div class='spacer'>$caption</div>\n";
		}
		else
		{
			echo "<div class='spacer'><div class='menubox'><b>$caption</b><br />$text</div></div>";
		}
	}
}

$COMMENTSTYLE = "
<table style='width:100%'>
<tr>
<td colspan='2' class='forumheader3'>
{SUBJECT}
<b>
{USERNAME}
</b>
|
{TIMEDATE}
</td>
</tr>
<tr>
<td style='width:30%; vertical-align:top'>
<div class='spacer'>
{AVATAR}
</div>
<span class='smalltext'>
{COMMENTS}
<br />
{JOINED}
</span>
<br/>
{REPLY}
</td>
<td style='width:70%; vertical-align:top'>
{COMMENT} {COMMENTEDIT}
</td>
</tr>
</table>
<br />";

$POLLSTYLE = <<< EOF
<b>Poll:</b> {QUESTION}
<br /><br />
{OPTIONS=<div class='alttd8'>OPTION</div>BAR<br /><span class='smalltext'>PERCENTAGE VOTES</span><br />\n}
<br /><div style='text-align:center' class='smalltext'>{AUTHOR}<br />{VOTE_TOTAL} {COMMENTS}
<br />
{OLDPOLLS}
</div>
EOF;

$CHATBOXSTYLE = "
<img src='".THEME."images/bullet2.gif' alt='bullet' />
<b>{USERNAME}</b><br />{TIMEDATE}
<div class='smalltext'>
{MESSAGE}
</div>
<br />";

?>