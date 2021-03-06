<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/forum_viewforum.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

require_once("class2.php");
if(IsSet($_POST['fjsubmit'])){
	header("location:".e_BASE."forum_viewforum.php?".$_POST['forumjump']);
	exit;
}
if(!e_QUERY){
	header("Location:".e_BASE."forum.php");
	exit;
}else{
	$tmp = explode(".", e_QUERY);
	$forum_id = $tmp[0]; $from = $tmp[1];
	if(!$from){ $from = 0; }
}

$image = (file_exists(THEME."forum/newthread.png")) ? THEME."forum/newthread.png" : e_IMAGE."forum/newthread.png";
define("IMAGE_newthread", "<img src='{$image}' alt='".FORLAN_10."' title='".FORLAN_10."' style='border:0' />");
$image = (file_exists(THEME."forum/new_small.png")) ? THEME."forum/new_small.png" : e_IMAGE."forum/new_small.png";
define("IMAGE_new_small", "<img src='{$image}' alt='".FORLAN_11."' title='".FORLAN_11."' style='border:0' />");
$image = (file_exists(THEME."forum/nonew_small.png")) ? THEME."forum/nonew_small.png" : e_IMAGE."forum/nonew_small.png";
define("IMAGE_nonew_small", "<img src='{$image}' alt='".FORLAN_12."' title='".FORLAN_12."' style='border:0' />");
$image = (file_exists(THEME."forum/new_popular.pgif")) ? THEME."forum/new_popular.gif" : e_IMAGE."forum/new_popular.gif";
define("IMAGE_new_popular", "<img src='{$image}' alt='".FORLAN_13."' title='".FORLAN_13."' style='border:0' />");
$image = (file_exists(THEME."forum/nonew_popular.gif")) ? THEME."forum/nonew_popular.gif" : e_IMAGE."forum/nonew_popular.gif";
define("IMAGE_nonew_popular", "<img src='{$image}' alt='".FORLAN_14."' title='".FORLAN_14."' style='border:0' />");
$image = (file_exists(THEME."forum/sticky.png")) ? THEME."forum/sticky.png" : e_IMAGE."forum/sticky.png";
define("IMAGE_sticky", "<img src='{$image}' alt='".FORLAN_15."' title='".FORLAN_15."' style='border:0' />");
$image = (file_exists(THEME."forum/stickyclosed.png")) ? THEME."forum/stickyclosed.png" : e_IMAGE."forum/stickyclosed.png";
define("IMAGE_stickyclosed", "<img src='{$image}' alt='".FORLAN_16."' title='".FORLAN_16."' style='border:0' />");
$image = (file_exists(THEME."forum/announce.png")) ? THEME."forum/announce.png" : e_IMAGE."forum/announce.png";
define("IMAGE_announce", "<img src='{$image}' alt='".FORLAN_17."' title='".FORLAN_17."' style='border:0' />");
$image = (file_exists(THEME."forum/closed_small.png")) ? THEME."forum/closed_small.png" : e_IMAGE."forum/closed_small.png";
define("IMAGE_closed_small", "<img src='{$image}' alt='".FORLAN_18."' title='".FORLAN_18."' style='border:0' />");
$image = (file_exists(THEME."forum/admin_move.png")) ? THEME."forum/admin_move.png" : e_IMAGE."forum/admin_move.png";
define("IMAGE_admin_move", "<img src='{$image}' alt='".LAN_402."' title='".LAN_402."' style='border:0' />");

define("IMAGE_admin_unstick", ((file_exists(THEME."forum/admin_unstick.png") ? "src='".THEME."forum/admin_unstick.png' " : "src='".e_IMAGE."forum/admin_unstick.png' ")."alt='".LAN_398."' title='".LAN_398."' style='border:0' "));
define("IMAGE_admin_stick", ((file_exists(THEME."forum/admin_stick.png")     ? "src='".THEME."forum/admin_stick.png' "   : "src='".e_IMAGE."forum/admin_stick.png' ")."alt='".LAN_401."' title='".LAN_401."' style='border:0' "));
define("IMAGE_admin_lock", ((file_exists(THEME."forum/admin_lock.png")     ? "src='".THEME."forum/admin_lock.png' "      : "src='".e_IMAGE."forum/admin_lock.png' ")."alt='".LAN_399."' title='".LAN_399."' style='border:0' "));
define("IMAGE_admin_unlock", ((file_exists(THEME."forum/admin_unlock.png") ? "src='".THEME."forum/admin_unlock.png' "    : "src='".e_IMAGE."forum/admin_unlock.png' ")."alt='".LAN_400."' title='".LAN_400."' style='border:0' "));


$STARTERTITLE = LAN_54;
$THREADTITLE = LAN_53;
$REPLYTITLE = LAN_55;
$LASTPOSTITLE = LAN_57;
$VIEWTITLE = LAN_56;

if(!$FORUM_VIEW_START)
{
	if(file_exists(THEME."forum_viewforum_template.php"))
	{
    require_once(THEME."forum_viewforum_template.php");
  }
  else
  {
    require_once(e_BASE.$THEMES_DIRECTORY."templates/forum_viewforum_template.php");
  }
}

$sql -> db_Select("forum", "*", "forum_id='".$forum_id."' ");
$row = $sql-> db_Fetch(); extract($row);
define("e_PAGETITLE", LAN_01." / ".$row['forum_name']);

if($forum_class && !check_class($forum_class) || !$forum_parent){ header("Location:".e_BASE."forum.php"); exit;}

define("MODERATOR", (preg_match("/".preg_quote(ADMINNAME)."/", $forum_moderators) && getperms("A") ? TRUE : FALSE));

$message="";
if(MODERATOR)
{
	if($_POST)
	{
		require_once(e_HANDLER."forum_mod.php");
		$message = forum_thread_moderate($_POST);
	}
}

$member_users = $sql -> db_Select("online", "*", "online_location REGEXP('forum_viewforum.php.$forum_id') AND online_user_id!='0' ");
$guest_users = $sql -> db_Select("online", "*", "online_location REGEXP('forum_viewforum.php.$forum_id') AND online_user_id='0' ");
$users = $member_users+$guest_users;

require_once(HEADERF);
if($message)
{
	$ns -> tablerender("",$message);
}

$view=25;
$topics = $sql -> db_Count("forum_t", "(*)", " WHERE thread_forum_id='".$forum_id."' AND thread_parent='0' ");
if($topics > $view){
	$a = $topics/$view;
	$r = explode(".", $a);
	if($r[1] != 0 ? $pages = ($r[0]+1) : $pages = $r[0]);
}else{
	$pages = FALSE;
}

$sql -> db_Select("forum", "*", "forum_id='".$forum_parent."' ");
$row_par = $sql-> db_Fetch();
$parent_title = $row_par['forum_name'];

if($pages){

	$THREADPAGES = LAN_316.": ";
	if($pages > 10){
		$current = ($from/$view)+1;
		for($c=0; $c<=2; $c++){
			$THREADPAGES .= ($view*$c == $from ? "<span style='text-decoration: underline;'>".($c+1)."</span> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
		}
		if($current >=3 && $current <= 5){
			for($c=3; $c<=$current; $c++){
				$THREADPAGES .= ($view*$c == $from ? "<span style='text-decoration: underline;'>".($c+1)."</span> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
			}
		}else if($current >= 6){
			$text .= " ... ";
			for($c=($current-2); $c<=$current; $c++){
				$THREADPAGES .= ($view*$c == $from ? "<span style='text-decoration: underline;'>".($c+1)."</span> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
			}
		}
		$text .= " ... ";
		$tmp = $pages-3;
		for($c=$tmp; $c<=($pages-1); $c++){
			$THREADPAGES .= ($view*$c == $from ? "<span style='text-decoration: underline;'>".($c+1)."</span> " : "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
		}
	}else{
		for($c=0; $c < $pages; $c++){
			if($view*$c == $from ? $THREADPAGES .= "<b>".($c+1)."</b> " : $THREADPAGES .= "<a href='".e_SELF."?".$forum_id.".".($view*$c)."'>".($c+1)."</a> ");
		}
	}
	$THREADPAGES .= "<br />";
}

if((ANON || USER) && ($forum_class != e_UC_READONLY || MODERATOR)){
	$NEWTHREADBUTTON = "<a href='".e_BASE."forum_post.php?nt.".$forum_id."'>".IMAGE_newthread."</a>";
}

$BREADCRUMB = "<a class='forumlink' href='index.php'>".SITENAME."</a> >> <a class='forumlink' href='forum.php'>".LAN_01."</a> >> <b>".$forum_name."</b>";
$FORUMTITLE = $forum_name;
$MODERATORS = LAN_404.": ".$forum_moderators;
$BROWSERS = $users." ".($users == 1 ? LAN_405 : LAN_406)." (".$member_users." ".($member_users==1 ? LAN_407 : LAN_409).", ".$guest_users." ".($guest_users == 1 ? LAN_408 : LAN_410).")";


$ICONKEY = "
<table style='width:100%'>
<tr>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_new_small."</td>
<td style='width:10%' class='smallblacktext'>".LAN_79."</td>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_nonew_small."</td>
<td style='width:10%' class='smallblacktext'>".LAN_80."</td>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_sticky."</td>
<td style='width:10%' class='smallblacktext'>".LAN_202."</td>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_announce."</td>
<td style='width:10%' class='smallblacktext'>".LAN_396."</td>
</tr>
<tr>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_new_popular."</td>
<td style='width:2%' class='smallblacktext'>".LAN_79." ".LAN_395."</td>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_nonew_popular."</td>
<td style='width:10%' class='smallblacktext'>".LAN_80." ".LAN_395."</td>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_stickyclosed."</td>
<td style='width:10%' class='smallblacktext'>".LAN_203."</td>
<td style='vertical-align:center; text-align:center; width:2%'>".IMAGE_closed_small."</td>
<td style='width:10%' class='smallblacktext'>".LAN_81."</td>
</tr>
</table>";

$SEARCH = "
<form method='post' action='search.php'>
<p>
<input class='tbox' type='text' name='searchquery' size='20' value='' maxlength='50' />
<input class='button' type='submit' name='searchsubmit' value='".LAN_180."' />
<input type='hidden' name='searchtype' value='7' />
</p>
</form>";

$PERMS = 
(USER == TRUE || ANON == TRUE ? LAN_204." - ".LAN_206." - ".LAN_208 : LAN_205." - ".LAN_207." - ".LAN_209);

$sticky_threads = 0;$stuck = FALSE;
$reg_threads = 0;$unstuck = FALSE;
if($sql -> db_Select("forum_t", "*",  "thread_forum_id='".$forum_id."' AND thread_parent='0' ORDER BY thread_s DESC, thread_lastpost DESC, thread_datestamp DESC LIMIT $from, $view")){
	$sql2 = new db; $sql3 = new db; $gen = new convert;
	while($row= $sql -> db_Fetch()){
		if($row['thread_s']){
			$sticky_threads ++;
		}
		if($sticky_threads == "1" && !$stuck){
			$forum_view_forum .= "<tr><td class='forumheader'>&nbsp;</td><td colspan='5'  class='forumheader'><span class='mediumtext'><b>".LAN_411."</b></span></td></tr>";
			$stuck = TRUE;
		}
		if(!$row['thread_s']){
			$reg_threads ++;
		}
		if($reg_threads == "1" && !$unstuck && $stuck){
			$forum_view_forum .= "<tr><td class='forumheader'>&nbsp;</td><td colspan='5'  class='forumheader'><span class='mediumtext'><b>".LAN_412."</b></span></td></tr>";
			$unstuck = TRUE;
		}
		$forum_view_forum .= parse_thread($row);
	}
}else{
	$forum_view_forum = "<tr><td colspan='6' style='text-align:center' class='forumheader2'><br /><span class='mediumtext'><b>".LAN_58."</b></span><br /><br /></td></tr>";
}

$sql -> db_Select("forum", "*", "forum_parent !=0 AND forum_class!='255' ");
$FORUMJUMP = "<form method='post' action='".e_SELF."'><p>".LAN_403.": <select name='forumjump' class='tbox'>";
while($row = $sql -> db_Fetch()){
	extract($row);
	if(check_class($forum_class)){
		$FORUMJUMP .= "\n<option value='".$forum_id."'>".$forum_name."</option>";
	}
}
$FORUMJUMP .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_03."' /></p></form>";
$TOPLINK = "<a href='".e_SELF."?".$_SERVER['QUERY_STRING']."#top'>".LAN_02."</a>";

$forum_view_start = preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_VIEW_START);
$forum_view_end = preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_VIEW_END);
if($pref['forum_enclose']){ $ns -> tablerender($pref['forum_title'], $forum_view_start.$forum_view_forum.$forum_view_end); }else{ echo $forum_view_start.$forum_view_forum.$forum_view_end; }
require_once(FOOTERF);


function parse_thread($row){
	global $sql2, $sql3, $FORUM_VIEW_FORUM, $gen, $aj, $pref, $forum_id, $menu_pref;
	extract($row);
	$VIEWS = $thread_views;
	$REPLIES = $sql2 -> db_Count("forum_t", "(*)", " WHERE thread_parent='$thread_id' ");
	if($REPLIES){
		$sql2 -> db_Select("forum_t", "*", "thread_parent='$thread_id' ORDER BY thread_datestamp DESC");
		list($null, $null, $null, $null, $r_datestamp, $null, $r_user) = $sql2 -> db_Fetch();
		$r_id = substr($r_user, 0, strpos($r_user, "."));
		$r_name = substr($r_user, (strpos($r_user, ".")+1));

		if(strstr($r_name, chr(1))){ $tmp = explode(chr(1), $r_name); $r_name = $tmp[0]; }

		$r_datestamp = $gen->convert_date($r_datestamp, "forum");
		if(!$r_id ? $LASTPOST = $r_name."<br />".$r_datestamp : $LASTPOST = "<a href='".e_BASE."user.php?id.".$r_id."'>".$r_name."</a><br />".$r_datestamp);

	}else{
		$REPLIES = LAN_317;
		$LASTPOST = " - ";
	}

	$post_author_id = substr($thread_user, 0, strpos($thread_user, "."));
	$post_author_name = substr($thread_user, (strpos($thread_user, ".")+1));
	if(strstr($post_author_name, chr(1))){ $tmp = explode(chr(1), $post_author_name); $post_author_name = $tmp[0]; }
	

	$newflag = FALSE;
	if(USER){
		if($thread_datestamp > USERLV && (!ereg("\.".$thread_id."\.", USERVIEWED))){
			$newflag = TRUE;
		}else if($sql3 -> db_SELECT("forum_t", "*", "thread_parent='$thread_id' AND thread_datestamp > '".USERLV."' ")){
			while(list($nthread_id) = $sql3 -> db_Fetch()){
				if(!ereg("\.".$nthread_id."\.", USERVIEWED)){
					$newflag = TRUE;
				}
			}
		}
	}

	$THREADDATE = $gen->convert_date($thread_datestamp, "forum");
	$ICON = ($newflag ? IMAGE_new_small : IMAGE_nonew_small);
	if($REPLIES >= $pref['forum_popular'] && $REPLIES != "None"){
		$ICON = ($newflag ? IMAGE_new_popular : IMAGE_nonew_popular);
	}

	if($thread_s == 1){
		$ICON = ($thread_active ? IMAGE_sticky : IMAGE_stickyclosed);
	}else if($thread_s == 2){
		$ICON = IMAGE_announce;
	}else if(!$thread_active){
		$ICON = IMAGE_closed_small;
	}

	$thread_name = strip_tags($aj -> tpa($thread_name));
	$result = preg_split("/\]/", $thread_name);
	if($pref['forum_tooltip']){
		$thread_thread = strip_tags($aj -> tpa($thread_thread));
		$tip_length = ($pref['forum_tiplength'] ? $pref['forum_tiplength'] : 400);
		if(strlen($thread_thread) > $tip_length) {
			$thread_thread = substr($thread_thread, 0, $tip_length)." ".$menu_pref['newforumposts_postfix'];
		}
		$thread_thread = str_replace("'", "&#39;", $thread_thread);
		$title = "title='".$thread_thread."'";
	}else{
		$title = "";
	}
	$THREADNAME = ($result[1] ? $result[0]."] <a  ".$title." href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_id."'>".ereg_replace("\[.*\]", "", $thread_name)."</a>" : "<a ".$title." href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_id."'>".$thread_name."</a>");

	$pages = ceil($REPLIES/$pref['forum_postspage']);
	if($pages>1){
		$PAGES = LAN_316." [ ";
		for($a=0; $a<=($pages-1); $a++){
			$PAGES .= "-<a href='".e_BASE."forum_viewtopic.php?".$forum_id.".".$thread_id.".".($a*$pref['forum_postspage'])."'>".($a+1)."</a>";
		}
		$PAGES .= " ]";
	}

	if(MODERATOR)
	{
		$ADMIN_ICONS = "
		<form method='post' action='".e_SELF."?{$forum_id}' id='frmMod_{$forum_id}_{$thread_id}'><div>
		";
		$ADMIN_ICONS .= ($thread_s == 1) ? "<input type='image' ".IMAGE_admin_unstick." name='unstick_{$thread_id}' value='thread_action' /> " : "<input type='image' ".IMAGE_admin_stick." name='stick_{$thread_id}' value='thread_action' /> ";
		$ADMIN_ICONS .= ($thread_active) ? "<input type='image' ".IMAGE_admin_lock." name='lock_{$thread_id}' value='thread_action' /> " : "<input type='image' ".IMAGE_admin_unlock." name='unlock_{$thread_id}' value='thread_action' /> ";
		$ADMIN_ICONS .= "<a href='".e_ADMIN."forum_conf.php?move.".$forum_id.".".$thread_id."'>".IMAGE_admin_move."</a>";
		$ADMIN_ICONS .= "
		</div></form>
		";
	}
			
	$text .= "</td>
	<td style='vertical-align:top; text-align:center; width:20%' class='forumheader3'>".$THREADDATE."<br />";
	$POSTER = (!$post_author_id ? $post_author_name :  "<a href='".e_BASE."user.php?id.".$post_author_id."'>".$post_author_name."</a>");
	return(preg_replace("/\{(.*?)\}/e", '$\1', $FORUM_VIEW_FORUM));
}


function forumjump(){
	global $sql;
	$sql -> db_Select("forum", "*", "forum_parent !=0 AND forum_class!='255' ");
	$FORUMJUMP = "<form method='post' action='".e_SELF."'><p>".LAN_403.": <select name='forumjump' class='tbox'>";
	while($row = $sql -> db_Fetch()){
		extract($row);
		if(check_class($forum_class)){
			$FORUMJUMP .= "\n<option value='".$forum_id."'>".$forum_name."</option>";
		}
	}
	$FORUMJUMP .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_03."' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='".e_SELF."?".$_SERVER['QUERY_STRING']."#top'>".LAN_02."</a></p></form>";
	return ($FORUMJUMP);
}

?>