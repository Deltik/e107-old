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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/list_new/new.php,v $
|     $Revision: 1.10 $
|     $Date: 2005-03-20 19:47:53 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/
require_once("../../class2.php");
	
if (!USER) {
	header("location:".e_BASE."index.php");
	exit;
}
	
require_once(HEADERF);
@include(e_PLUGIN."list_new/languages/".e_LANGUAGE.".php");
@include(e_PLUGIN."list_new/languages/English.php");
	
$bullet = (defined("BULLET") ? "<img src='".THEME."images/".BULLET."' alt='' style='vertical-align: middle;' />" : "<img src='".THEME."images/".(defined("BULLET") ? BULLET : "bullet2.gif")."' alt='bullet' style='vertical-align: middle;' />");
	
$sql2 = new db;
$lvisit = USERLV;
$news_items = $sql->db_Select("news", "*", "news_datestamp>$lvisit  ORDER BY news_datestamp DESC LIMIT 0,10");
	
while ($row = $sql->db_Fetch()) {
	extract($row);
	if (check_class($news_class)) {
		$str .= "$bullet<a href='".e_BASE."comment.php?comment.news.$news_id'>$news_title</a><br />";
	} else {
		$news_items = $news_items - 1;
	}
}
if (!$news_items) {
	$str = LIST_4;
}
	
$text = "<div style='text-align:center'>
	<table style='width:95%' class='fborder'>
	<tr>
	<td class='fcaption'>".LIST_1." ($news_items)</td>
	</tr>
	<td class='forumheader3'>".$str."</td>
	</tr>\n";
	
// Articles/content/reviews
unset($str);
$content_items = $sql->db_Select("content", "*", "content_type < 4 AND content_datestamp>$lvisit  ORDER BY content_datestamp DESC LIMIT 0,10");
while ($row = $sql->db_Fetch()) {
	extract($row);
	if (check_class($content_class)) {
		//        find out whether article, review or content page ...
		switch($content_type) {
			case 0:
			//        article
			$str .= $bullet."[ ".LIST_14." ] : <a href='".e_BASE."content.php?article.$content_id'>$content_heading</a><br />";
			break;
			case 1:
			//        content page
			$str .= $bullet."[ ".LIST_15." ] : <a href='".e_BASE."content.php?content.$content_id'>$content_heading</a><br />";
			break;
			case 3:
			//        review
			$str .= $bullet."[ ".LIST_16." ] : <a href='".e_BASE."content.php?review.$content_id.'>$content_heading</a><br />";
			break;
		}
	} else {
		$content_items = $content_items - 1;
	}
}
if (!$content_items) {
	$str = LIST_4;
}
	
$text .= "
	<tr>
	<td class='fcaption'>".LIST_21." ($content_items)</td>
	</tr>
	<td class='forumheader3'>".$str."</td>
	</tr>\n";
	
unset($str);
$comment_count = 0;
if ($comments = $sql->db_Select("comments", "*", "comment_datestamp>$lvisit ORDER BY comment_datestamp DESC LIMIT 0,10")) {
	while ($row = $sql->db_Fetch()) {
		extract($row);
		switch($comment_type) {

			case "ideas":
				$sql2->db_Select("ideas", "ideas_summary", "ideas_id=$comment_item_id ");
				$row = $sql2->db_Fetch();
				extract($row);
				$str .= $bullet."[ ".LIST_22." ] Re: <a href='".e_PLUGIN."ideas/ideas.php?show.$comment_item_id'>".$tp -> toHTML($ideas_summary)."</a><br />";
			break;

			case 0:
			// news
			$sql2->db_Select("news", "*", "news_id=$comment_item_id ");
			$row = $sql2->db_Fetch();
			 extract($row);
			if (check_class($news_class)) {
				$news_title = $tp -> toHTML($news_title);
				$str .= $bullet."[ ".LIST_1." ] Re: <a href='".e_BASE."comment.php?comment.news.$comment_item_id'>".$tp -> toHTML($news_title, "admin")."</a><br />";
				$comment_count++;
			}
			break;
			 
			case 1:
			//        article, review or content page
			//        find out whether article, review or content page ...
			$sql2->db_Select("content", "content_heading, content_type, content_class", "content_id=$comment_item_id ");
			$row = $sql2->db_Fetch();
			extract($row);
			if (check_class($content_class)) {
				$comment_count++;
				switch($content_type) {
					case 0:
					//        article
					$str .= $bullet."[ ".LIST_14." ] Re: <a href='".e_BASE."content.php?article.$comment_item_id'>".$tp -> toHTML($content_heading, "admin")."</a><br />";
					break;
					case 1:
					//        content page
					$str .= $bullet."[ ".LIST_15." ] Re: <a href='".e_BASE."content.php?content.$comment_item_id'>".$tp -> toHTML($content_heading, "admin")."</a><br />";
					break;
					case 3:
					//        review
					$str .= $bullet."[ ".LIST_16." ] Re: <a href='".e_BASE."content.php?review.$comment_item_id.'>".$tp -> toHTML($content_heading, "admin")."</a><br />";
					break;
				}
			}
			break;
			 
			case 2: //        downloads
			$mp = MPREFIX;
			$qry = "SELECT download_name, {$mp}download_category.download_category_class FROM {$mp}download LEFT JOIN {$mp}download_category ON {$mp}download.download_category={$mp}download_category.download_category_id WHERE {$mp}download.download_id={$comment_item_id}";
			$sql2->db_Select_gen($qry);
			$row = $sql2->db_Fetch();
			extract($row);
			if (check_class($download_category_class)) {
				$str .= $bullet."[ ".LIST_17." ] Re: <a href='".e_BASE."download.php?view.$comment_item_id'>".$tp -> toHTML($download_name, "admin")."</a><br />";
				$comment_count++;
			}
			break;
			 
			case 3: //        faq
			$sql2->db_Select("faq", "faq_question", "faq_id=$comment_item_id ");
			$row = $sql2->db_Fetch();
			 extract($row);
			$str .= $bullet."[ ".LIST_18." ] Re: <a href='".e_BASE."faq.php?view.$comment_item_id'>".$tp -> toHTML($faq_question, "admin")."</a><br />";
			$comment_count++;
			break;
			 
			case 4:
			//        poll comment
			$sql2->db_Select("poll", "*", "poll_id=$comment_item_id ");
			$row = $sql2->db_Fetch();
			 extract($row);
			$str .= $bullet."[ ".LIST_19." ] Re: <a href='".e_BASE."comment.php?comment.poll.$comment_item_id'>".$tp -> toHTML($poll_title, "admin")."</a><br />";
			$comment_count++;
			break;
			 
			case 6:
			//        bugtracker
			$sql2->db_Select("bugtrack2_bugs", "bugtrack2_bugs_summary", "bugtrack2_bugs_id=$comment_item_id ");
			$row = $sql2->db_Fetch();
			 extract($row);
			$str .= $bullet."[ ".LIST_20." ] Re: <a href='".e_PLUGIN."bugtracker2/bugtracker2.php?0.bug.$comment_item_id'>".$tp -> toHTML($bugtrack_summary)."</a><br />";
			$comment_count++;
			break;
		}
		$handle = opendir(e_PLUGIN);
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && is_dir(e_PLUGIN.$file)) {
				$plugin_handle = opendir(e_PLUGIN.$file."/");
				while (false !== ($file2 = readdir($plugin_handle))) {
					if ($file2 == "e_comment.php") {
						include(e_PLUGIN.$file."/".$file2);
						if ($comment_type == $e_plug_table) {
							$sql2->db_Select("".$db_table."", "".$link_name."", "".$db_id."=$comment_item_id ");
							$row = $sql2->db_Fetch();
							 extract($row);
							$nid = $comment_item_id;
							$link = $row[0];
							$str .= $bullet."[ ".$plugin_name." ] Re: <a href='".$reply_location."'>".$tp -> toHTML($link)."</a><br />";
							$comment_count++;
							break 2;
						}
					}
				}
			}
		}
	}
}
if ($comment_count == 0) {
	$str = LIST_4;
}
	
$text .= "
	<tr>
	<td class='fcaption'>".LIST_2." ({$comment_count})</td>
	</tr>
	<td class='forumheader3'>".$str."</td>
	</tr>\n";
	
unset($str);
if ($chatbox_posts = $sql->db_Select("chatbox", "*", "cb_datestamp>$lvisit ORDER BY cb_datestamp DESC LIMIT 0,50")) {
	while ($row = $sql->db_Fetch()) {
		extract($row);
		$cb_id = substr($cb_nick , 0, strpos($cb_nick , "."));
		$cb_nick = substr($cb_nick , (strpos($cb_nick , ".")+1));
		$cb_message = ($cb_blocked ? CHATBOX_L6 : str_replace("<br />", "", $tp -> toHTML($cb_message)));
		$str .= $bullet."[ <a href='".e_BASE."user.php?id.$cb_id'>$cb_nick</a> ] {$cb_message}<br />";
	}
} else {
	$str = LIST_4;
}
	
$text .= "
	<tr>
	<td class='fcaption'>".LIST_5." ($chatbox_posts)</td>
	</tr>
	<td class='forumheader3'>".$str."</td>
	</tr>\n";
	
unset($str);


$query = "SELECT tp.thread_name AS parent_name, t.thread_thread, t.thread_id, t.thread_name, t.thread_parent, f.forum_id, f.forum_name FROM #forum_t AS t 
LEFT JOIN #forum_t AS tp ON t.thread_parent = tp.thread_id 
LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id 
WHERE f.forum_class  IN (".USERCLASS_LIST.") 
AND t.thread_datestamp > $lvisit
ORDER BY t.thread_datestamp DESC LIMIT 0, 50";





if($forum_posts = $sql->db_Select_gen($query))
{
	while ($row = $sql->db_Fetch()) {
		extract($row);
		if ($thread_parent) {
			$str .= $bullet."[ <a href='".e_PLUGIN."forum/forum_viewforum.php?$forum_id'>$forum_name</a> ] Re: <a href='".e_PLUGIN."forum/forum_viewtopic.php?$thread_parent#$thread_id'>".$tp -> toHTML($parent_name)."</a><br />";
		}
		else
		{
			$str .= $bullet."[ <a href='".e_PLUGIN."forum/forum_viewforum.php?$forum_id'>$forum_name</a> ] <a href='".e_PLUGIN."forum/forum_viewtopic.php?$thread_id'>".$tp -> toHTML($thread_name)."</a><br/>";
		}
	}
}
if (!$forum_posts) {
	$str = LIST_4;
}
	
$text .= "
	<tr>
	<td class='fcaption'>".LIST_6." ($forum_posts)</td>
	</tr>
	<td class='forumheader3'>".$str."</td>
	</tr>\n";
	
unset($str);
if ($new_members = $sql->db_Select("user", "*", "user_join>$lvisit ORDER BY user_join DESC LIMIT 0,30")) {
	while ($row = $sql->db_Fetch()) {
		extract($row);
		$str .= "$bullet<a href='".e_BASE."user.php?id.$user_id'>$user_name</a><br />";
	}
} else {
	$str = LIST_4;
}
	
$text .= "
	<tr>
	<td class='fcaption'>".LIST_7." ($new_members)</td>
	</tr>
	<td class='forumheader3'>".$str."</td>
	</tr>\n";
	
	
$text .= "</table>\n</div>";
$ns->tablerender(LIST_3, $text);
	
/*
$time = USERLV;
$new_news = $sql->db_Count("news", "(*)", "WHERE news_datestamp>'".$time."' "); if(!$new_news){ $new_news = "no"; }
$new_comments = $sql->db_Count("comments", "(*)", "WHERE comment_datestamp>'".$time."' "); if(!$new_comments){ $new_comments = "no"; }
$new_chat = $sql->db_Count("chatbox", "(*)", "WHERE cb_datestamp>'".$time."' "); if(!$new_chat){ $new_chat = "no"; }
$new_forum = $sql->db_Count("forum_t", "(*)", "WHERE thread_datestamp>'".$time."' "); if(!$new_forum){ $new_forum = "no"; }
$new_users = $sql->db_Count("user", "(*)", "WHERE user_join>'".$time."' "); if(!$new_users){ $new_users = "no"; }
*/
	
	
require_once(FOOTERF);
?>