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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/forum/templates/forum_viewtopic_template.php,v $
|     $Revision: 1.1 $
|     $Date: 2005-01-25 15:14:58 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/

$sc_style['LASTEDIT']['pre']="<br /><br /><span class='smallblacktext'>[ ".LAN_29." ";
$sc_style['LASTEDIT']['post']=" ]</span>";

$FORUMSTART = "<div style='text-align:center'>\n<table style='width:98%' class='fborder'>\n<tr>\n<td  colspan='2' class='fcaption'>\n{BACKLINK}\n</td>\n</tr>\n<tr>\n<td class='forumheader' colspan='2'>\n<table cellspacing='0' cellpadding='0' style='width:100%'>\n<tr>\n<td class='smalltext'>\n{NEXTPREV}\n</td>\n<td style='text-align:right'>&nbsp;\n{TRACK}\n</td>\n</tr>\n</table>\n</td>\n</tr>\n<tr>\n<td style='width:80%; vertical-align:bottom'><br /><div class='captiontext'>&nbsp;{THREADNAME}</div><br />\n{MODERATORS}\n<div class='mediumtext'>\n{GOTOPAGES}\n</div>\n</td>\n<td style='width:20%; text-align:right'>\n{BUTTONS}\n</td>\n</tr>\n<tr>\n<td colspan='2' style='text-align:center'>\n{THREADSTATUS}\n<table style='width:100%' class='fborder'>\n<tr>\n<td style='width:20%; text-align:center' class='fcaption'>\n".LAN_402."\n</td>\n<td style='width:80%; text-align:center' class='fcaption'>\n".LAN_403."\n</td>\n</tr>\n";
$FORUMTHREADSTYLE = "<tr>\n<td class='forumheader' style='vertical-align:middle'>\n{NEWFLAG}\n{POSTER}\n</td>\n<td class='forumheader' style='vertical-align:middle'>\n<table cellspacing='0' cellpadding='0' style='width:100%'>\n<tr>\n<td class='smallblacktext'>\n{THREADDATESTAMP}\n</td>\n<td style='text-align:right'>\n{REPORTIMG}{EDITIMG}{QUOTEIMG}\n</td>\n</tr>\n</table>\n</td>\n</tr>\n<tr>\n<td class='forumheader3' style='vertical-align:top'>\n{CUSTOMTITLE}\n{AVATAR}\n<span class='smalltext'>\n{LEVEL}\n{MEMBERID}\n{JOINED}\n{LOCATION}\n{POSTS}\n</span>\n</td>\n<td class='forumheader3' style='vertical-align:top'>\n{POST}\n{LASTEDIT}\n{SIGNATURE}\n</td>\n</tr>\n<tr>\n <td class='finfobar'>\n<span class='smallblacktext'>\n{TOP}\n</span>\n</td>\n<td class='finfobar' style='vertical-align:top'>\n<table cellspacing='0' cellpadding='0' style='width:100%'>\n<tr>\n<td>\n{PROFILEIMG}\n {EMAILIMG}\n {WEBSITEIMG}\n {PRIVMESSAGE}\n</td>\n<td style='text-align:right'>\n{MODOPTIONS}\n</td>\n</tr>\n</table>\n</td>\n</tr>\n<tr>\n<td colspan='2'>\n</td>\n</tr>\n";
$FORUMEND = "<tr><td colspan='2' class='forumheader3' style='text-align:center'>{QUICKREPLY}</td></tr></table>\n</td>\n</tr>\n<tr>\n<td style='width:80%; vertical-align:top'>\n<div class='mediumtext'>\n{GOTOPAGES}\n</div>\n{FORUMJUMP}\n</td>\n<td style='width:20%; text-align:right'>\n{BUTTONS}\n</td>\n</tr>\n</table>\n</div>";
$FORUMREPLYSTYLE = "";

?>