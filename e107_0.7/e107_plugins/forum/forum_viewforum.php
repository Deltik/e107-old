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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/forum/forum_viewforum.php,v $
|     $Revision: 1.19 $
|     $Date: 2005-03-24 13:08:02 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/
	
require_once("../../class2.php");
$lan_file = e_PLUGIN.'forum/languages/'.e_LANGUAGE.'/lan_forum_viewforum.php';
include(file_exists($lan_file) ? $lan_file : e_PLUGIN.'forum/languages/English/lan_forum_viewforum.php');
	
if (IsSet($_POST['fjsubmit'])) {
	header("location:".e_PLUGIN."forum/forum_viewforum.php?".$_POST['forumjump']);
	exit;
}
if (!e_QUERY) {
	header("Location:".e_PLUGIN."/forum/forum.php");
	exit;
} else {
	$tmp = explode(".", e_QUERY);
	$forum_id = $tmp[0];
	$from = $tmp[1];
	if (!$from) {
		$from = 0;
	}
}
$view = 25;

if(is_numeric(e_MENU))
{
	$from = (intval(e_MENU)-1)*$view;	
}
require_once(e_PLUGIN.'forum/forum_class.php');
$forum = new e107forum;
	
$STARTERTITLE = LAN_54;
$THREADTITLE = LAN_53;
$REPLYTITLE = LAN_55;
$LASTPOSTITLE = LAN_57;
$VIEWTITLE = LAN_56;
	
if (!$FORUM_VIEW_START) {
	if (file_exists(THEME."forum_viewforum_template.php"))
	{
		require_once(THEME."forum_viewforum_template.php");
	}
	else if (file_exists(THEME."forum_template.php"))
	{
		require_once(THEME."forum_template.php");
	}
	else
	{
		require_once(e_PLUGIN."forum/templates/forum_viewforum_template.php");
	}
}
	
$forum_info = $forum->forum_get($forum_id);
$forum_info['forum_name'] = $tp -> toHTML($forum_info['forum_name'], TRUE);
$forum_info['forum_description'] = $tp -> toHTML($forum_info['forum_description'], TRUE);


define("e_PAGETITLE", LAN_01." / ".$forum_info['forum_name']);
	
if (($forum_info['forum_class'] && !check_class($forum_info['forum_class'])) || !$forum_info['forum_parent']) {
	header("Location:".e_PLUGIN."forum/forum.php");
	exit;
}
	
define("MODERATOR", (preg_match("/".preg_quote(ADMINNAME)."/", $forum_info['forum_moderators']) && getperms("A") ? TRUE : FALSE));
	
$message = "";
if (MODERATOR) {
	if ($_POST) {
		require_once(e_PLUGIN."forum/forum_mod.php");
		$message = forum_thread_moderate($_POST);
	}
}
	
$member_users = $sql->db_Select("online", "*", "online_location REGEXP('forum_viewforum.php.$forum_id') AND online_user_id!='0' ");
$guest_users = $sql->db_Select("online", "*", "online_location REGEXP('forum_viewforum.php.$forum_id') AND online_user_id='0' ");
$users = $member_users+$guest_users;
	
require_once(HEADERF);
$text='';
if ($message) {
	$ns->tablerender("", $message);
}
	
$topics = $forum->forum_get_topic_count($forum_id);
	
if ($topics > $view) {
	$pages = ceil($topics/$view);
} else {
	$pages = FALSE;
}
	
if ($pages)
{
	if(strpos($FORUM_VIEW_START, 'THREADPAGES') !== FALSE || strpos($FORUM_VIEW_END, 'THREADPAGES') !== FALSE)
	{
		$parms = "{$topics},{$view},{$from},".e_SELF.'?'.$forum_id.'.[FROM]';
		$THREADPAGES = LAN_316." ".$tp->parseTemplate("{NEXTPREV={$parms}}");
	}
}
	
if ((ANON || USER) && ($forum_info['forum_class'] != e_UC_READONLY || MODERATOR)) {
	$NEWTHREADBUTTON = "<a href='".e_PLUGIN."forum/forum_post.php?nt.".$forum_id."'>".IMAGE_newthread."</a>";
}
	
$BREADCRUMB = "<a class='forumlink' href='".e_BASE."index.php'>".SITENAME."</a> -> <a class='forumlink' href='".e_PLUGIN."forum/forum.php'>".LAN_01."</a> -> <b>".$forum_info['forum_name']."</b>";
$FORUMTITLE = $forum_info['forum_name'];
$MODERATORS = LAN_404.": ".$forum_info['forum_moderators'];
$BROWSERS = $users." ".($users == 1 ? LAN_405 : LAN_406)." (".$member_users." ".($member_users == 1 ? LAN_407 : LAN_409).", ".$guest_users." ".($guest_users == 1 ? LAN_408 : LAN_410).")";
	
	
$ICONKEY = "
	<table style='width:100%'>
	<tr>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_new_small."</td>
	<td style='width:10%' class='smallblacktext'>".LAN_79."</td>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_nonew_small."</td>
	<td style='width:10%' class='smallblacktext'>".LAN_80."</td>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_sticky_small."</td>
	<td style='width:10%' class='smallblacktext'>".LAN_202."</td>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_announce_small."</td>
	<td style='width:10%' class='smallblacktext'>".LAN_396."</td>
	</tr>
	<tr>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_new_popular_small."</td>
	<td style='width:2%' class='smallblacktext'>".LAN_79." ".LAN_395."</td>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_nonew_popular_small."</td>
	<td style='width:10%' class='smallblacktext'>".LAN_80." ".LAN_395."</td>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_stickyclosed_small."</td>
	<td style='width:10%' class='smallblacktext'>".LAN_203."</td>
	<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_closed_small."</td>
	<td style='width:10%' class='smallblacktext'>".LAN_81."</td>
	</tr>
	</table>";
	
$SEARCH = "
	<form method='get' action='".e_BASE."search.php'>
	<p>
	<input class='tbox' type='text' name='q' size='20' value='' maxlength='50' />
	<input type='hidden' name='r' value='0' />
	<input class='button' type='submit' name='s' value='".LAN_180."' />
	</p>
	</form>";
	
if (USER == TRUE || ANON == TRUE) {
	$PERMS = LAN_204." - ".LAN_206." - ".LAN_208;
} else {
	$PERMS = LAN_205." - ".LAN_207." - ".LAN_209;
}
	
$sticky_threads = 0;
$stuck = FALSE;
$reg_threads = 0;
$unstuck = FALSE;
	
$thread_list = $forum->forum_get_topics($forum_id, $from, $view);
$gen = new convert;
	
if ($thread_list) {
	foreach($thread_list as $thread_info) {
		$idArray[] = $thread_info['thread_id'];
	}
	$inList = '('.implode(',', $idArray).')';
	foreach($thread_list as $thread_info) {
		if ($thread_info['thread_s']) {
			$sticky_threads ++;
		}
		if ($sticky_threads == "1" && !$stuck) {
			$forum_view_forum .= "<tr><td class='forumheader'>&nbsp;</td><td colspan='5'  class='forumheader'><span class='mediumtext'><b>".LAN_411."</b></span></td></tr>";
			$stuck = TRUE;
		}
		if (!$thread_info['thread_s']) {
			$reg_threads ++;
		}
		if ($reg_threads == "1" && !$unstuck && $stuck) {
			$forum_view_forum .= "<tr><td class='forumheader'>&nbsp;</td><td colspan='5'  class='forumheader'><span class='mediumtext'><b>".LAN_412."</b></span></td></tr>";
			$unstuck = TRUE;
		}
		$forum_view_forum .= parse_thread($thread_info);
	}
} else {
	$forum_view_forum = "<tr><td colspan='6' style='text-align:center' class='forumheader2'><br /><span class='mediumtext'><b>".LAN_58."</b></span><br /><br /></td></tr>";
}
	
$sql->db_Select("forum", "*", "forum_parent !=0 AND forum_class!='255' ");
$FORUMJUMP = "<form method='post' action='".e_SELF."'><p>".LAN_403.": <select name='forumjump' class='tbox'>";
while ($row = $sql->db_Fetch()) {
	extract($row);
	if (check_class($forum_class)) {
		$FORUMJUMP .= "\n<option value='".$forum_id."'>".$forum_name."</option>";
	}
}
$FORUMJUMP .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_03."' /></p></form>";
$TOPLINK = "<a href='".e_SELF."?".$_SERVER['QUERY_STRING']."#top'>".LAN_02."</a>";
	
$forum_view_start = preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_VIEW_START);
$forum_view_end = preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_VIEW_END);
if ($pref['forum_enclose']) {
	$ns->tablerender($pref['forum_title'], $forum_view_start.$forum_view_forum.$forum_view_end);
} else {
	echo $forum_view_start.$forum_view_forum.$forum_view_end;
}
require_once(FOOTERF);
	
	
function parse_thread($thread_info) {
	global $forum, $tp, $FORUM_VIEW_FORUM, $gen, $aj, $pref, $forum_id, $menu_pref;
	$text = "";
	 
	$VIEWS = $thread_info['thread_views'];
	$REPLIES = $thread_info['thread_total_replies'];

//	$forum->showvar($thread_info);
	 
	if ($REPLIES) {
		$lastpost_datestamp = $gen->convert_date($thread_info['thread_lastpost'], 'forum');
		/*
		changes by jalist 27/01/2005:
		if lastpost name has period(s) in it list/explode would fail
		*/
		list($lastpost_id, $lastpost_name) = explode('.', $thread_info['thread_lastuser'],2);
//		$count = count($lastpost);
//		$lastpost_id = $lastpost[0];
//		$lastpost_name = "";
//		if (count($lastpost) > 2) {
//			for($a = 1; $a <= ($count-1); $a++) {
//				$lastpost_name .= $lastpost[$a];
//			}
//		} else {
//			$lastpost_name = $lastpost[($count-1)];
//		}
		if (!$lastpost_id) {
			$LASTPOST = $lastpost_name.'<br />'.$lastpost_datestamp;
		} else {
			$LASTPOST = "<a href='".e_BASE."user.php?id.".$lastpost_id."'>".$lastpost_name."</a><br />".$lastpost_datestamp;
		}
	} else {
		$REPLIES = LAN_317;
		$LASTPOST = " - ";
	}
	 
	$newflag = FALSE;
	if (USER) {
		if ($thread_info['thread_lastpost'] > USERLV && (!ereg("\.".$thread_info['thread_id']."\.", USERVIEWED))) {
			$newflag = TRUE;
		}
	}
	 
	$THREADDATE = $gen->convert_date($thread_info['thread_datestamp'], 'forum');
	$ICON = ($newflag ? IMAGE_new : IMAGE_nonew);
	if ($REPLIES >= $pref['forum_popular'] && $REPLIES != "None") {
		$ICON = ($newflag ? IMAGE_new_popular : IMAGE_nonew_popular);
	}
	 
	$THREADTYPE = '';
	if ($thread_info['thread_s'] == 1) {
		$ICON = ($thread_info['thread_active'] ? IMAGE_sticky : IMAGE_stickyclosed);
		$THREADTYPE = '['.LAN_202.']';
	} elseif($thread_info['thread_s'] == 2) {
		$ICON = IMAGE_announce;
		$THREADTYPE = '['.LAN_396.']';
	} elseif(!$thread_info['thread_active']) {
		$ICON = IMAGE_closed;
	}

//	echo $thread_info['thread_id'].' = ['.$thread_info['thread_active'].']';
	 
	$thread_name = strip_tags($tp->toHTML($thread_info['thread_name']));
	if (strtoupper($THREADTYPE) == strtoupper(substr($thread_name, 0, strlen($THREADTYPE)))) {
		$thread_name = substr($thread_name, strlen($THREADTYPE));
	}
	if ($pref['forum_tooltip']) {
		$thread_thread = strip_tags($tp->toHTML($thread_info['thread_thread']));
		$tip_length = ($pref['forum_tiplength'] ? $pref['forum_tiplength'] : 400);
		if (strlen($thread_thread) > $tip_length) {
			$thread_thread = substr($thread_thread, 0, $tip_length)." ".$menu_pref['newforumposts_postfix'];
		}
		$thread_thread = str_replace("'", "&#39;", $thread_thread);
		$title = "title='".$thread_thread."'";
	} else {
		$title = "";
	}
	$THREADNAME = "<a {$title} href='".e_PLUGIN."forum/forum_viewtopic.php?{$thread_info['thread_id']}'>{$thread_name}</a>";
	 
	$pages = ceil($REPLIES/$pref['forum_postspage']);
	if ($pages > 1) {
		$PAGES = LAN_316." [ ";
		for($a = 0; $a <= ($pages-1); $a++) {
			$PAGES .= "-<a href='".e_PLUGIN."forum/forum_viewtopic.php?".$thread_info['thread_id'].".".($a * $pref['forum_postspage'])."'>".($a+1)."</a>";
		}
		$PAGES .= " ]";
	} else {
		$PAGES = "";
	}
	 
	if (MODERATOR) {
		$thread_id = $thread_info['thread_id'];
		$ADMIN_ICONS = "
			<form method='post' action='".e_SELF."?{$forum_id}' id='frmMod_{$forum_id}_{$thread_id}' style='margin:0;'><div>
			";
		$ADMIN_ICONS .= ($thread_info['thread_s'] == 1) ? "<input type='image' ".IMAGE_admin_unstick." name='unstick_{$thread_id}' value='thread_action' /> " :
		 "<input type='image' ".IMAGE_admin_stick." name='stick_{$thread_id}' value='thread_action' /> ";
		$ADMIN_ICONS .= ($thread_info['thread_active']) ? "<input type='image' ".IMAGE_admin_lock." name='lock_{$thread_id}' value='thread_action' /> " :
		 "<input type='image' ".IMAGE_admin_unlock." name='unlock_{$thread_id}' value='thread_action' /> ";
		$ADMIN_ICONS .= "<a href='".e_ADMIN."forum_conf.php?move.".$forum_id.".".$thread_id."'>".IMAGE_admin_move."</a>";
		$ADMIN_ICONS .= "
			</div></form>
			";
	}
	 
	$text .= "</td>
		<td style='vertical-align:top; text-align:center; width:20%' class='forumheader3'>".$THREADDATE."<br />
		";
//	$POSTER = (!isset($post_author_id) ? $post_author_name : "<a href='".e_BASE."user.php?id.".$post_author_id."'>".$post_author_name."</a>");
	 
	if (!$thread_info['thread_user']) {
		$tmp = explode(chr(1), $thread_info['thread_anon']);
		$POSTER = $tmp[0];
	} else {
		$POSTER = "<a href='".e_BASE."user.php?id.".$thread_info['thread_user']."'>".$thread_info['user_name']."</a>";
	}
	 
	return(preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_VIEW_FORUM));
}
	
	
function forumjump() {
	global $sql;
	$sql->db_Select("forum", "*", "forum_parent !=0 AND forum_class!='255' ");
	$FORUMJUMP = "<form method='post' action='".e_SELF."'><p>".LAN_403.": <select name='forumjump' class='tbox'>";
	while ($row = $sql->db_Fetch()) {
		extract($row);
		if (check_class($forum_class)) {
			$FORUMJUMP .= "\n<option value='".$forum_id."'>".$forum_name."</option>";
		}
	}
	$FORUMJUMP .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_03."' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='".e_SELF."?".$_SERVER['QUERY_STRING']."#top'>".LAN_02."</a></p></form>";
	return ($FORUMJUMP);
}
	
?>