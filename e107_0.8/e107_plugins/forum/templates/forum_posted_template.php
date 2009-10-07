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
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }
if(!defined('USER_WIDTH')) { define('USER_WIDTH','width:95%'); }

$FORUMPOLLPOSTED =
"<table style='".USER_WIDTH."' class='fborder'>
<tr>
<td class='fcaption' colspan='2'>".LAN_133."</td>
</tr><tr>
<td style='text-align:right; vertical-align:middle; width:20%' class='forumheader2'>".IMAGE_e."&nbsp;</td>
<td style='vertical-align:middle; width:80%' class='forumheader2'>
<br />".LAN_413."<br />
<span class='defaulttext'><a class='forumlink' href='{$threadLink}'>".LAN_414."</a><br />
<a class='forumlink' href='{$forumLink}'>".LAN_326."</a></span><br /><br />
</td></tr></table>";

$FORUMTHREADPOSTED = "
<table style='".USER_WIDTH."' class='fborder'>
<tr>
<td class='fcaption' colspan='2'>".LAN_133."</td>
</tr><tr>
<td style='text-align:right; vertical-align:middle; width:20%' class='forumheader2'>".IMAGE_e."&nbsp;</td>
<td style='vertical-align:middle; width:80%' class='forumheader2'>
<br />".LAN_324."<br />
".(defined('F_MESSAGE') ? F_MESSAGE.'<br />' : '')."
<span class='defaulttext'><a href='{$threadLink}'>".LAN_325."</a><br />
<a href='{$forumLink}'>".LAN_326."</a></span><br /><br />
</td></tr></table>";


$FORUMREPLYPOSTED = "
<table style='".USER_WIDTH."' class='fborder'>
<tr>
<td class='fcaption' colspan='2'>".LAN_133."</td>
</tr><tr>
<td style='text-align:right; vertical-align:middle; width:20%' class='forumheader2'>".IMAGE_e."&nbsp;</td>
<td style='vertical-align:middle; width:80%' class='forumheader2'>
<br />".LAN_415."<br />
".(defined('F_MESSAGE') ? F_MESSAGE.'<br />' : '')."
<span class='defaulttext'><a href='{$threadLink}'>".LAN_325."</a><br />
<a href='{$forumLink}'>".LAN_326."</a></span><br /><br />
</td></tr></table>";

?>