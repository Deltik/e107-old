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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/forum/forum_post.php,v $
|     $Revision: 1.2 $
|     $Date: 2005-01-27 19:52:48 $
|     $Author: streaky $
+----------------------------------------------------------------------------+
*/
	
require_once("../../class2.php");
$lan_file = e_PLUGIN.'forum/languages/'.e_LANGUAGE.'/lan_forum_post.php';
include(file_exists($lan_file) ? $lan_file : e_PLUGIN.'forum/languages/English/lan_forum_post.php');
	
if (IsSet($_POST['fjsubmit'])) {
	header("location:".e_BASE.$PLUGINS_DIRECTORY."forum/forum_viewforum.php?".$_POST['forumjump']);
	exit;
}
require_once(e_PLUGIN.'forum/forum_class.php');
$forum = new e107forum;
	
if (!e_QUERY) {
	echo "no query!";
	 exit;
	header("Location:".e_PLUGIN."forum/forum.php");
	exit;
} else {
	$tmp = explode(".", e_QUERY);
	$action = preg_replace('#\W#', '', $tmp[0]);
	$id = intval($tmp[1]);
	$from = intval($tmp[2]);
}
	
// check if user can post to this forum ...
if ($action == 'rp') {
	// reply to thread
	$thread_info = $forum->thread_get($id, 0, 10);
	$forum_info = $forum->forum_get($thread_info['head']['thread_forum_id']);
} elseif ($action == 'nt') {
	// New post
	$forum_info = $forum->forum_get($id);
} elseif ($action == 'quote' || $action == 'edit') {
	$thread_info = $forum->thread_get_postinfo($id, TRUE);
	$forum_info = $forum->forum_get($thread_info['head']['thread_forum_id']);
}
	
if (!check_class($forum_info['forum_class'])) {
	require_once(HEADERF);
	$ns->tablerender(LAN_20, "<div style='text-align:center'>".LAN_399."</div>");
	require_once(FOOTERF);
	exit;
}
	
//require_once(e_HANDLER.'forum_include.php');
require_once(e_PLUGIN."forum/forum_post_shortcodes.php");
require_once(e_HANDLER."ren_help.php");
$gen = new convert;
$fp = new floodprotect;
global $tp;
	
if ($sql->db_Select("tmp", "*", "tmp_ip='$ip' ")) {
	$row = $sql->db_Fetch();
	$tmp = explode("^", $row['tmp_info']);
	$action = $tmp[0];
	$anonname = $tmp[1];
	$subject = $tmp[2];
	$post = $tmp[3];
	$sql->db_Delete("tmp", "tmp_ip='$ip' ");
}
	
//If anonymous posting is off and not logged it, show warning
if (ANON == FALSE && USER == FALSE) {
	require_once(HEADERF);
	$text .= "<div style='text-align:center'>".LAN_45." <a href='".e_SIGNUP."'>".LAN_411."</a> ".LAN_412."</div>";
	$ns->tablerender(LAN_20, $text);
	require_once(FOOTERF);
	exit;
}
	
//if readonly forum and not admin, show warning
if ($forum_info['forum_class'] == e_UC_READONLY && !ADMIN) {
	$text .= "<div style='text-align:center'>".LAN_398."</div>";
	$ns->tablerender(LAN_20, $text);
	require_once(FOOTERF);
	exit;
}
	
//if thread is not active and not new thread, show warning
if ($action != "nt" && !$thread_info['head']['thread_active']) {
	require_once(HEADERF);
	$ns->tablerender(LAN_20, "<div style='text-align:center'>".LAN_397."</div>");
	require_once(FOOTERF);
	exit;
}
	
define("e_PAGETITLE", LAN_01." / ".$forum_info['forum_name']." / ".($action == "rp" ? LAN_02.$forum_info['thread_name'] : LAN_03));
require_once(HEADERF);
	
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
if (IsSet($_POST['addoption']) && $_POST['option_count'] < 10) {
	$_POST['option_count']++;
	$anonname = $tp->post_toForm($_POST['anonname']);
	$subject = $tp->post_toForm($_POST['subject']);
	$post = $tp->post_toForm($_POST['post']);
}
	
if (IsSet($_POST['submitpoll'])) {
	require_once(e_HANDLER."poll_class.php");
	$poll = new poll;
	$poll->submit_poll(0, $_POST['poll_title'], $_POST['poll_option'], $_POST['activate'], 0, $forum_id, "forum");
	 
	require_once(HEADERF);
	echo "<table style='width:100%' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>".LAN_133."</td>
		</tr><tr>
		<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'>".IMAGE_e."&nbsp;</td>
		<td style='vertical-align:center; width:80%' class='forumheader2'>
		<br />".LAN_384."<br />
		<span class='defaulttext'><a class='forumlink' href='".e_PLUGIN."forum/forum_viewtopic.php?".$thread_id."'>".LAN_325."</a><br />
		<a class='forumlink' href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
		</td></tr></table>";
	require_once(FOOTERF);
	exit;
}
	
if (IsSet($_POST['fpreview'])) {
	if (USER) {
		$poster = USERNAME;
	} else {
		$poster = ($_POST['anonname']) ? $_POST['anonname'] :
		 LAN_311;
	}
	$postdate = $gen->convert_date(time(), "forum");
	 
	$tsubject = $tp->post_toHTML($_POST['subject']);
	$tpost = $tp->post_toHTML($_POST['post']);
	 
	if ($_POST['poll_title'] != "" && $pref['forum_poll']) {
		require_once(e_HANDLER."poll_class.php");
		$poll = new poll;
		$poll_text .= $poll->render_poll($_POST['existing'], $_POST['poll_title'], $_POST['poll_option'], array($votes), "preview", "forum");
		$count = 0;
		while ($_POST['poll_option'][$count]) {
			$_POST['poll_option'][$count] = $tp->post_toForm($_POST['poll_option'][$count]);
			$count++;
		}
		$_POST['poll_title'] = $tp->post_toForm($_POST['poll_title']);
	}
	 
	$text = "<div style='text-align:center'>
		<table style='width:95%' class='fborder'>
		<tr>
		<td colspan='2' class='fcaption' style='vertical-align:top'>".LAN_323;
	$text .= ($action != "nt" ? "</td>" : " ( ".LAN_62.$tsubject." )</td>");
	$text .= "<tr>
		<td class='forumheader3' style='width:20%' style='vertical-align:top'><b>".$poster."</b></td>
		<td class='forumheader3' style='width:80%'>
		<div class='smallblacktext' style='text-align:right'>".IMAGE_post2." ".LAN_322.$postdate."</div>".$tpost."</td>
		</tr>
		</table>
		</div>";
	 
	if ($poll_text) {
		$ns->tablerender($_POST['poll_title'], $poll_text);
	}
	$ns->tablerender(LAN_323, $text);
	$anonname = $tp->post_toForm($_POST['anonname']);
	$post = $tp->post_toForm($_POST['post']);
	$subject = $tp->post_toForm($_POST['subject']);
	 
	if ($action == "edit") {
		if ($_POST['subject']) {
			$action = "nt";
		} else {
			$action = "reply";
		}
		$eaction = TRUE;
	}
	else if($action == "quote") {
		$action = "reply";
		$eaction = FALSE;
	}
}
	
	
if (isset($_POST['newthread']) || isset($_POST['reply'])) {
	if ((isset($_POST['newthread']) && trim(chop($_POST['subject'])) == "") || trim(chop($_POST['post'])) == "") {
		message_handler("ALERT", 5);
	} else {
		if ($fp->flood("forum_t", "thread_datestamp") == FALSE) {
			header("location: ".e_BASE."index.php");
			exit;
		}
		if (USER) {
			$user = USERID;
		} else {
			$user = getuser($_POST['anonname']);
			if (intval($user)) {
				require_once(HEADERF);
				$ns->tablerender(LAN_20, LAN_310);
				if (isset($_POST['reply'])) {
					$tmpdata = "reply.".$_POST['anonname'].".".$_POST['subject'].".".$_POST['post'];
				} else {
					$tmpdata = "newthread^".$_POST['anonname']."^".$_POST['subject']."^".$_POST['post'];
				}
				$sql->db_Insert("tmp", "'$ip', '".time()."', '$tmpdata' ");
				loginf();
				require_once(FOOTERF);
				exit;
			} else {
				list($user, $anon_info) = explode('.', $user, 2);
			}
		}
		 
		if ($file_userfile['error'] != 4) {
			require_once(e_HANDLER."upload_handler.php");
			if ($uploaded = file_upload('/'.e_FILE."public/", "attachment")) {
				$_POST['post'] .= "\n\n".(strstr($uploaded[0]['type'], "image") ? "[img]".e_FILE."public/".$uploaded[0]['name']."[/img] \n<span class='smalltext'>[ ".$uploaded[0]['name']." ]</span>" : "[file=".e_FILE."public/".$uploaded[0]['name']."]".$uploaded[0]['name']."[/file]");
			}
		}
		$post = $tp->toDB($_POST['post']);
		$subject = $tp->toDB($_POST['subject']);
		$email_notify = ($_POST['email_notify'] ? 99 : 1);
		if ($_POST['poll_title'] != "" && $_POST['poll_option'][0] != "" && $_POST['poll_option'][1] != "") {
			$subject = "[".LAN_402."] ".$subject;
		}
		 
		if ($_POST['threadtype'] == 2) {
			$subject = "[".LAN_403."] ".$subject;
		} elseif($_POST['threadtype'] == 1) {
			$subject = "[".LAN_404."] ".$subject;
		}
		 
		$threadtype = intval($_POST['threadtype']);
		if (isset($_POST['reply'])) {
			$parent = $thread_info['head']['thread_id'];
			$forum_id = $thread_info['head']['thread_forum_id'];
		} else {
			$parent = 0;
			$forum_id = $id;
		}
		$iid = $forum->thread_insert($subject, $post, $forum_id, $parent, $user, $email_notify, $threadtype, $anon_info);
		if (isset($_POST['reply'])) {
			$iid = $parent;
		}
		 
		if ($_POST['poll_title'] != "" && $_POST['poll_option'][0] != "" && $_POST['poll_option'][1] != "" && isset($_POST['newthread'])) {
			require_once(e_HANDLER."poll_class.php");
			$poll = new poll;
			$poll->submit_poll(0, $_POST['poll_title'], $_POST['poll_option'], $_POST['activate'], 0, $iid, "forum");
		}
		 
		if ($pref['forum_redirect']) {
			redirect(e_BASE."forum_viewtopic.php?{$iid}.last");
		} else {
			require_once(HEADERF);
			echo "<table style='width:100%' class='fborder'>
				<tr>
				<td class='fcaption' colspan='2'>".LAN_133."</td>
				</tr><tr>
				<td style='text-align:right; vertical-align:center; width:20%' class='forumheader2'>".IMAGE_e."&nbsp;</td>
				<td style='vertical-align:center; width:80%' class='forumheader2'>
				<br />".LAN_324."<br />
				<span class='defaulttext'><a href='".e_PLUGIN."forum/forum_viewtopic.php?{$iid}.last'>".LAN_325."</a><br />
				<a href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_id."'>".LAN_326."</a></span><br /><br />
				</td></tr></table>";
			$e107cache->clear("newforumposts");
			require_once(FOOTERF);
			exit;
		}
	}
}
	
	
if (isset($_POST['update_thread'])) {
	if (!$_POST['subject'] || !$_POST['post']) {
		$error = "<div style='text-align:center'>".LAN_27."</div>";
	} else {
		if (!isAuthor($id)) {
			$ns->tablerender(LAN_95, "<div style='text-align:center'>".LAN_96."</div>");
			require_once(FOOTERF);
			exit;
		}
		$newvals['thread_edit_datestamp'] = time();
		$newvals['thread_thread'] = $tp->toDB($_POST['post']);
		$newvals['thread_name'] = $tp->toDB($_POST['subject']);
		$newvals['thread_active'] = ($_POST['email_notify']) ? '99' :
		 '1';
		if (isset($_POST['threadtype'])) {
			$newvals['thread_s'] = $_POST['threadtype'];
		}
		$forum->thread_update($id, $newvals);
		$e107cache->clear("newforumposts");
		header("location:".e_PLUGIN."forum/forum_viewtopic.php?{$thread_info['head']['thread_id']}.0");
		exit;
	}
}
	
if (IsSet($_POST['update_reply'])) {
	if (!$_POST['post']) {
		$error = "<div style='text-align:center'>".LAN_27."</div>";
	} else {
		if (!isAuthor($id)) {
			$ns->tablerender(LAN_95, "<div style='text-align:center'>".LAN_96."</div>");
			require_once(FOOTERF);
			exit;
		}
		$newvals['thread_edit_datestamp'] = time();
		$newvals['thread_thread'] = $tp->toDB($_POST['post']);
		$forum->thread_update($id, $newvals);
		$e107cache->clear("newforumposts");
		header("location:".e_PLUGIN."forum/forum_viewtopic.php?{$thread_info['head']['thread_id']}.{$from}");
		exit;
	}
}
	
if ($error) {
	$ns->tablerender(LAN_20, $error);
}
	
if ($action == 'edit' || $action == 'quote') {
	if ($action == "edit") {
		if ((!$thread_info[0]['user_id'] || $thread_info[0]['user_id'] != USERID) && !ADMIN) {
			$ns->tablerender(LAN_95, "<div style='text-align:center'>".LAN_96."</div>");
			require_once(FOOTERF);
			exit;
		}
	}
	 
	$thread_info['0']['user_name'] = $forum->thread_user($thread_info['0']);
	$subject = $thread_info['0']['thread_name'];
	$post = $tp->toForm($thread_info[0]['thread_thread']);
	$post = ereg_replace("&lt;span class=&#39;smallblacktext&#39;.*\span\>", "", $post);
	if ($action == 'quote') {
		$timeStamp = time();
		$post = "[quote{$timeStamp}={$thread_info['0']['user_name']}]\n".$post."\n[/quote{$timeStamp}]\n";
		$eaction = FALSE;
		$action = 'reply';
	} else {
		$eaction = TRUE;
		if ($thread_info['0']['thread_parent']) {
			$action = "reply";
		} else {
			$action = "nt";
		}
	}
}
	
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
$text = "<div style='text-align:center'>
	<form enctype='multipart/form-data' method='post' action='".e_SELF."?".e_QUERY."' name='dataform'>
	<table style='width:95%' class='fborder'>
	<tr><td colspan='2' class='fcaption'><a class='forumlink' href='".e_PLUGIN."forum/forum.php'>".LAN_405."</a>-><a class='forumlink' href='".e_PLUGIN."forum/forum_viewforum.php?".$forum_info['forum_id']."'>".$forum_info['forum_name']."</a>->";
	
if ($action == "nt") {
	$text .= ($eaction ? LAN_77 : LAN_60);
} else {
	$text .= ($eaction ? LAN_78 : LAN_406." ".$thread_info['head']['thread_name']);
}
	
$text .= "</td></tr>";
	
if (ANON == TRUE && USER == FALSE) {
	$text .= "<tr>
		<td class='forumheader2' style='width:20%'>".LAN_61."</td>
		<td class='forumheader2' style='width:80%'>
		<input class='tbox' type='text' name='anonname' size='71' value='".$anonname."' maxlength='20' />
		</td>
		</tr>";
}
	
if ($action == "nt") {
	$text .= "<tr>
		<td class='forumheader2' style='width:20%'>".LAN_62."</td>
		<td class='forumheader2' style='width:80%'>
		<input class='tbox' type='text' name='subject' size='71' value='".$subject."' maxlength='100' />
		</td>
		</tr>";
}
	
$text .= "<tr>
	<td class='forumheader2' style='width:20%'>";
$text .= ($action == "nt" ? LAN_63 : LAN_73);
	
$text .= "</td>
	<td class='forumheader2' style='width:80%'>
	<textarea class='tbox' name='post' cols='70' rows='10' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$aj->formtparev($post)."</textarea>
	<br />\n".ren_help(2);
	
$text .= "<br />";
require_once(e_HANDLER."emote.php");
$text .= r_emote();
	
if ($pref['email_notify'] && $action == "nt") {
	$text .= "<span class='defaulttext'>".LAN_380."</span>";
	$chkd = '';
	if ($_POST['email_notify'] || $thread_info['head']['thread_active'] == 99) {
		$chkd = "checked = 'checked' ";
	}
	$text .= "<input type='checkbox' name='email_notify' value='1' {$chkd}>";
	unset($chkd);
}
	
if (ADMIN && getperms("5") && $action == "nt") {
	if (isset($_POST['threadtype'])) {
		$thread_s = $_POST['threadtype'];
	} else {
		$thread_s = $thread_info['head']['thread_s'];
	}
	$text .= "<br />
		<span class='defaulttext'>
		".LAN_400."
		<input name='threadtype' type='radio' value='0' ".(!$thread_s ? "checked='checked' " : "").">".LAN_1."
		&nbsp;
		<input name='threadtype' type='radio' value='1' ".($thread_s == 1 ? "checked='checked' " : "").">".LAN_2."
		&nbsp;
		<input name='threadtype' type='radio' value='2' ".($thread_s == 2 ? "checked='checked' " : "").">".LAN_3."
		</span>";
}
	
if ($action == "nt" && $pref['forum_poll'] && !eregi("edit", e_QUERY)) {
	$text .= "</td>
		</tr>
		<tr>
		<td colspan='2' class='fcaption'>".LAN_4."</td>
		 
		</tr>
		<tr>
		 
		<td colspan='2' class='forumheader3'>
		<span class='smalltext'>".LAN_386."
		</td>
		</tr>
		 
		<tr>
		<td style='width:20%' class='forumheader3'><div class='normaltext'>".LAN_5."</div></td>
		<td style='width:80%'class='forumheader3'>
		<input class='tbox' type='text' name='poll_title' size='70' value=\"".$aj->tpa($_POST['poll_title'])."\" maxlength='200' />";
	 
	$option_count = ($_POST['option_count'] ? $_POST['option_count'] : 1);
	$text .= "<input type='hidden' name='option_count' value='$option_count'>";
	 
	for($count = 1; $count <= $option_count; $count++) {
		$var = "poll_option_".$count;
		$option = stripslashes($$var);
		$text .= "<tr>
			<td style='width:20%' class='forumheader3'>".LAN_391." ".$count.":</td>
			<td style='width:80%' class='forumheader3'>
			<input class='tbox' type='text' name='poll_option[]' size='60' value=\"".$aj->tpa($_POST['poll_option'][($count-1)])."\" maxlength='200' />";
		if ($option_count == $count) {
			$text .= " <input class='button' type='submit' name='addoption' value='".LAN_6."' /> ";
		}
		$text .= "</td></tr>";
	}
	 
	$text .= "<tr>
		<td style='width:20%' class='forumheader3'>".LAN_7."</td>
		<td class='forumheader3'>";
	$text .= ($_POST['activate'] == 9 ? "<input name='activate' type='radio' value='9' checked>".LAN_8."<br />" : "<input name='activate' type='radio' value='9'>".LAN_8."<br />");
	$text .= ($_POST['activate'] == 10 ? "<input name='activate' type='radio' value='10' checked>".LAN_9."<br />" : "<input name='activate' type='radio' value='10'>".LAN_9."<br />");
	 
	$text .= "</td>
		</tr>";
}
	
	
if ($pref['forum_attach'] && !eregi("edit", e_QUERY) && (check_class($pref['upload_class']) || getperms('0'))) {
	$text .= "<tr>
		<td colspan='2' class='fcaption'>".LAN_390."</td>
		</tr>
		<tr>
		<td style='width:20%' class='forumheader3'>".LAN_392."</td>
		<td style='width:80%' class='forumheader3'>
		".LAN_393." | ".str_replace("\n", " | ", $pref['upload_allowedfiletype'])." |<br />".LAN_394."<br />".LAN_395.": ".($pref['upload_maxfilesize'] ? $pref['upload_maxfilesize'].LAN_396 : ini_get('upload_max_filesize'))."<br />
		<input class='tbox' name='file_userfile[]' type='file' size='47'>
		</td>
		</tr>
		";
}
	
$text .= "<tr style='vertical-align:top'>
	<td colspan='2' class='forumheader' style='text-align:center'>
	<input class='button' type='submit' name='fpreview' value='".LAN_323."' /> ";
	
if ($action != "nt") {
	$text .= ($eaction ? "<input class='button' type='submit' name='update_reply' value='".LAN_78."' />" : "<input class='button' type='submit' name='reply' value='".LAN_74."' />");
} else {
	$text .= ($eaction ? "<input class='button' type='submit' name='update_thread' value='".LAN_77."' />" : "<input class='button' type='submit' name='newthread' value='".LAN_64."' />");
}
$text .= "</td>
	</tr>
	<input type='hidden' name='thread_id' value='{$thread_info['head']['thread_id']}'>
	</table>
	</form>
	</div>";
	
$text .= "<table style='width:95%'>
	<tr>
	<td style='width:50%'>";
$text .= forumjump();
$text .= "</td></tr></table><br />";
	
if ($action == 'rp') {
	$tmp_template = "
		<div style='text-align:center'>
		{THREADTOPIC}
		{LATESTPOSTS}
		</div>
		";
	$text .= $tp->parseTemplate($tmp_template, FALSE, $forum_post_shortcodes);
}
	
if ($pref['forum_enclose']) {
	$ns->tablerender($pref['forum_title'], $text);
} else {
	echo $text;
}
	
function isAuthor($thread) {
	global $sql;
	$sql->db_Select("forum_t", "thread_user", "thread_id='".$thread."' ");
	$row = $sql->db_Fetch("no_strip");
	$post_author_id = substr($row[0], 0, strpos($row[0], "."));
	return ($post_author_id == USERID || ADMIN === TRUE);
}
	
function getuser($name) {
	global $tp;
	global $sql;
	$name = preg_replace("#\'#", "", $name);
	if (!$name) {
		// anonymous guest
		$name = "0.".LAN_311.chr(1).$ip;
	} else {
		if ($sql->db_Select("user", "user_id, user_ip", "user_name='$name' ")) {
			$row = $sql->db_Fetch();
			if ($row['user_ip'] == $ip) {
				$name = $row['user_id'];
			} else {
				return FALSE;
			}
		} else {
			$ip = getip();
			$name = "0.".substr($tp->toDB($name), 0, 20).chr(1).$ip;
		}
	}
	return $name;
}
	
function loginf() {
	$text .= "<div style='text-align:center'>
		<form method='post' action='".e_SELF."?".e_QUERY."'><p>
		".LAN_16."<br />
		<input class='tbox' type='text' name='username' size='15' value='' maxlength='20' />\n
		<br />
		".LAN_17."
		<br />
		<input class='tbox' type='password' name='userpass' size='15' value='' maxlength='20' />\n
		<br />
		<input class='button' type='submit' name='userlogin' value='".LAN_10."' />\n
		<br />
		<input type='checkbox' name='autologin' value='1' /> ".LAN_11."
		<br /><br />
		[ <a href='".e_BASE."signup.php'>".LAN_174."</a> ]<br />[ <a href='fpw.php'>".LAN_212."</a> ]
		</p>
		</form>
		</div>";
	$ns = new e107table;
	$ns->tablerender(LAN_175, $text);
}
function forumjump() {
	global $sql;
	$sql->db_Select("forum", "*", "forum_parent !=0 AND forum_class!='255' ");
	$text .= "<form method='post' action='".e_SELF."'><p>".LAN_401.": <select name='forumjump' class='tbox'>";
	while ($row = $sql->db_Fetch()) {
		extract($row);
		// if(($forum_class && check_class($forum_class)) || ($forum_class == 254 && USER) || !$forum_class){
		if (check_class($forum_class)) {
			$text .= "\n<option value='".$forum_id."'>".$forum_name."</option>";
		}
	}
	$text .= "</select> <input class='button' type='submit' name='fjsubmit' value='".LAN_387."' /></p></form>";
	return $text;
}
function redirect($url) {
	global $ns;
	if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE'))) {
		header('Refresh: 0; URL=' .$url);
		$text = "<div style='text-align:center'>".LAN_408."<a href='".$url."'> ".LAN_409." </a>".LAN_410."</div>";
		$ns->tablerender(LAN_407, $text);
		require_once(FOOTERF);
		exit;
	}
	 
	header('Location: ' . $url);
	exit;
}
require_once(FOOTERF);
	
?>