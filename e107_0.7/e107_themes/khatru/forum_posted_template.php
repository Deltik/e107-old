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

$FORUMPOLLPOSTED =
BOXOPEN.LAN_133.BOXMAIN."
<table style='width:100%' class='fborder'>
<tr>
<td style='text-align:right; vertical-align:middle; width:20%'>".IMAGE_e."&nbsp;</td>

<td style='vertical-align:middle; width:80%'>

<br /><b>".LAN_413."</b><br />
<span class='defaulttext'><a href='".e_PLUGIN."forum/forum_viewtopic.php?".$thread_id."'>".LAN_414."</a><br />
<a href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
</td></tr></table>".BOXCLOSE;

$FORUMTHREADPOSTED = 
BOXOPEN.LAN_133.BOXMAIN."
<table style='width:100%' class='fborder'>
<tr>
<td style='text-align:right; vertical-align:middle; width:20%'>".IMAGE_e."&nbsp;</td>

<td style='vertical-align:middle; width:80%'>

<br /><b>".LAN_324."</b><br />
".(defined("F_MESSAGE") ? F_MESSAGE."<br />" : "")."
<span class='defaulttext'><a href='".e_PLUGIN."forum/forum_viewtopic.php?{$iid}.last'>".LAN_325."</a><br />
<a href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
</td></tr></table>".BOXCLOSE;


$FORUMREPLYPOSTED = 
BOXOPEN.LAN_133.BOXMAIN."
<table style='width:100%' class='fborder'>
<tr>
<td style='text-align:right; vertical-align:middle; width:20%'>".IMAGE_e."&nbsp;</td>

<td style='vertical-align:middle; width:80%'>

<br /><b>".LAN_415."</b><br />
".(defined("F_MESSAGE") ? F_MESSAGE."<br />" : "")."
<span class='defaulttext'><a href='".e_PLUGIN."forum/forum_viewtopic.php?{$iid}.last'>".LAN_325."</a><br />
<a href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
</td></tr></table>".BOXCLOSE;

?>