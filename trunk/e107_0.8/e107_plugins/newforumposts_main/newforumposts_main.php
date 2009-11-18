<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/newforumposts_main/newforumposts_main.php,v $
 * $Revision: 1.5 $
 * $Date: 2009-11-18 01:05:53 $
 * $Author: e107coders $
 */

if (!defined('e107_INIT')) { exit; }

require_once(e_HANDLER."userclass_class.php");
$query = ($pref['nfp_posts'] ? "thread_lastpost" : "thread_datestamp");
$lan_file = e_PLUGIN."newforumposts_main/languages/".e_LANGUAGE.".php";
$path = e_PLUGIN."forum/";
include_once((file_exists($lan_file) ? $lan_file : e_PLUGIN."newforumposts_main/languages/English.php"));
global $sql, $ns;
// get template ...

if (file_exists(THEME."newforumpost.php")) {
	require_once(THEME."newforumpost.php");
}
else if(!isset($NEWFORUMPOSTSTYLE_HEADER)) {
	// no template found - use default ...
	$NEWFORUMPOSTSTYLE_HEADER = "
		<!-- newforumposts -->
		<div style='text-align:center'>\n<table style='width:auto' class='fborder'>
		<tr>
		<td style='width:5%' class='forumheader'>&nbsp;</td>
		<td style='width:45%' class='forumheader'>".NFPM_LAN_1."</td>
		<td style='width:15%; text-align:center' class='forumheader'>".NFPM_LAN_2."</td>
		<td style='width:5%; text-align:center' class='forumheader'>".NFPM_LAN_3."</td>
		<td style='width:5%; text-align:center' class='forumheader'>".NFPM_LAN_4."</td>
		<td style='width:25%; text-align:center' class='forumheader'>".NFPM_LAN_5."</td>
		</tr>\n";

	$NEWFORUMPOSTSTYLE_MAIN = "
		<tr>
		<td style='width:5%; text-align:center' class='forumheader3'>{ICON}</td>
		<td style='width:45%' class='forumheader3'><b>{THREAD}</b> <span class='smalltext'>({FORUM})</span></td>
		<td style='width:15%; text-align:center' class='forumheader3'>{POSTER}</td>
		<td style='width:5%; text-align:center' class='forumheader3'>{VIEWS}</td>
		<td style='width:5%; text-align:center' class='forumheader3'>{REPLIES}</td>
		<td style='width:25%; text-align:center' class='forumheader3'>{LASTPOST}<br /><span class='smalltext'>{LASTPOSTDATE}&nbsp;</span></td>
		</tr>\n";

	$NEWFORUMPOSTSTYLE_FOOTER = "<tr>\n<td colspan='6' style='text-align:center' class='forumheader2'>
		<span class='smalltext'>".NFPM_LAN_6.": <b>{TOTAL_TOPICS}</b> | ".NFPM_LAN_4.": <b>{TOTAL_REPLIES}</b> | ".NFPM_LAN_3.": <b>{TOTAL_VIEWS}</b></span>\n</td>\n</tr>\n</table>\n</div>";

}

$results = $sql->db_Select_gen("
SELECT t.thread_id, t.thread_name, t.thread_datestamp, t.thread_user, t.thread_views, t.thread_lastpost, t.thread_lastuser, t.thread_total_replies, f.forum_id, f.forum_name, f.forum_class, u.user_name, fp.forum_class, lp.user_name AS lp_name
FROM #forum_t AS t
LEFT JOIN #user AS u ON SUBSTRING_INDEX(t.thread_user,'.',1) = u.user_id
LEFT JOIN #user AS lp ON SUBSTRING_INDEX(t.thread_lastuser,'.',1) = lp.user_id
LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id
LEFT JOIN #forum AS fp ON f.forum_parent = fp.forum_id
WHERE f.forum_id = t.thread_forum_id AND t.thread_parent=0 AND f.forum_class IN (".USERCLASS_LIST.")
AND fp.forum_class IN (".USERCLASS_LIST.")
ORDER BY t.$query DESC LIMIT 0, ".$pref['nfp_amount']);

$forumArray = $sql->db_getList();

if (!isset($gen) || !is_object($gen)) {
	$gen = new convert;
}

if (file_exists(THEME."forum/new_small.png")) 
{
  $ICON = "<img src='".THEME."forum/new_small.png' alt='' />";
}
else
{
  $ICON = "<img src='".e_PLUGIN_ABS."forum/images/".IMODE."/new_small.png' alt='' />";
}
$TOTAL_TOPICS = $sql->db_Count("forum_t", "(*)", " WHERE thread_parent='0' ");
$TOTAL_REPLIES = $sql->db_Count("forum_t", "(*)", " WHERE thread_parent!='0' ");
$sql->db_Select_gen("SELECT sum(thread_views) FROM ".MPREFIX."forum_t");
$tmp = $sql->db_Fetch();
$TOTAL_VIEWS = $tmp[0];
$text = preg_replace("/\{(.*?)\}/e", '$\1', $NEWFORUMPOSTSTYLE_HEADER);

foreach($forumArray as $forumInfo)
{
	extract($forumInfo);

	$r_datestamp = $gen->convert_date($thread_lastpost, "forum");
	if($thread_total_replies)
	{
		$tmp = explode(".", $thread_lastuser, 2);
		if($lp_name)
		{
			$LASTPOST = "<a href='".e_BASE."user.php?id.{$tmp[0]}'>$lp_name</a>";
		}
		else
		{
			if($tmp[1])
			{
				$LASTPOST = $tmp[1];
			}
			else
			{
				$LASTPOST = NFPM_L16;
			}
		}
		$LASTPOSTDATE = "<span class='smalltext'>$r_datestamp</span>";
	}
	else
	{
		$LASTPOST = " - ";
		$LASTPOSTDATE = "";
	}

	$x = explode(chr(1), $thread_user);
	$tmp = explode(".", $x[0], 2);
	if($user_name)
	{
		$POSTER = "<a href='".e_BASE."user.php?id.{$tmp[0]}'>$user_name</a>";
	}
	else
	{
		if($tmp[1])
		{
			$POSTER = $tmp[1];
		}
		else
		{
			$POSTER = NFPM_L16;
		}
	}

	$THREAD = "<a href='".$path."forum_viewtopic.php?{$thread_id}.last'>$thread_name</a>";
	$FORUM = "<a href='".$path."forum_viewforum.php?{$forum_id}'>$forum_name</a>";

	$VIEWS = $thread_views;
	$REPLIES = $thread_total_replies;
	$text .= preg_replace("/\{(.*?)\}/e", '$\1', $NEWFORUMPOSTSTYLE_MAIN);

}
$text .= preg_replace("/\{(.*?)\}/e", '$\1', $NEWFORUMPOSTSTYLE_FOOTER);

$text = ($pref['nfp_layer'] ? "<div style='border : 0; padding : 4px; width : auto; height : ".$pref['nfp_layer_height']."px; overflow : auto; '>".$text."</div>" : $text);

if ($results)
{
	$ns->tablerender($pref['nfp_caption'], $text, "nfp");
}

?>
