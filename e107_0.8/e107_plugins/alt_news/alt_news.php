<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Alternate News
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/alt_news/alt_news.php,v $
 * $Revision: 1.3 $
 * $Date: 2008-12-07 13:08:54 $
 * $Author: e107steved $
*/

if (!defined('e107_INIT')) { exit; }

require_once(e_HANDLER."news_class.php");

function alt_news($news_category) {
	global $ml, $sql, $aj, $ns;
	$ix = new news;
	if (strstr(e_QUERY, "cat")) {
		$category = $news_category;
		if ($category != 0) {
			$gen = new convert;
			$sql2 = new db;
			$sql->db_Select("news_category", "*", "category_id='".intval($category)."'");
			list($category_id, $category_name, $category_icon) = $sql->db_Fetch();
			$category_name = $aj->tpa($category_name);
			if (strstr($category_icon, "../")) {
				$category_icon = str_replace("../", "", e_BASE.$category_icon);
			} else {
				$category_icon = THEME.$category_icon;
			}

			if ($count = $sql->db_Select("news", "*", "news_category='".intval($category)."' ORDER BY news_datestamp DESC")) {
				while ($row = $sql->db_Fetch()) {
					extract($row);
					if ($news_title == "") {
						$news_title = "Untitled";
					}
					$datestamp = $gen->convert_date($news_datestamp, "short");
					$news_body = strip_tags(substr($news_body, 0, 100))." ...";
					$comment_total = $sql2->db_Count("comments", "(*)", "WHERE comment_item_id='".intval($news_id)."' AND comment_type='0' ");
					$text .= "<div class='mediumtext'>
						<img src='".THEME."images/".(defined("BULLET") ? BULLET : "bullet2.gif")."' alt='bullet' /> ";

					if ($news_allow_comments) {
						$text .= "<a href='news.php?extend.".$news_id."'>".$news_title."</a>";
					} else {
						$text .= "<a href='comment.php?comment.news.".$news_id."'>".$news_title."</a>";
					}
					$text .= "<br />
						".LAN_NEWS_100." ".$datestamp." (".LAN_NEWS_99.": ";
					if ($news_allow_comments) {
						$text .= COMMENTOFFSTRING.")";
					} else {
						$text .= $comment_total.")";
					}
					$text .= "</div>
						".$news_body."
						<br /><br />\n";
				}
				$text = "<img src='$category_icon' alt='' /><br />". LAN_NEWS_307.$count."
					<br /><br />".$text;
				$ns->tablerender(LAN_NEWS_82." '".$category_name."'", $text, 'alt_news');
			}
		}
		return TRUE;
	}

	if ($sql->db_Select("news", "*", "news_class<255 AND (news_start=0 || news_start < ".time().") AND (news_end=0 || news_end>".time().") AND news_category='".intval($news_category)."' ORDER BY news_datestamp DESC LIMIT 0,".ITEMVIEW)) {
		$sql2 = new db;
		while (list($news['news_id'], $news['news_title'], $news['data'], $news['news_extended'], $news['news_datestamp'], $news['admin_id'], $news_category, $news['news_allow_comments'], $news['news_start'], $news['news_end'], $news['news_class']) = $sql->db_Fetch()) {

			if (check_class($news['news_class']) || !$news['news_class']) {

				if ($news['admin_id'] == 1 && $pref['siteadmin']) {
					$news['admin_name'] = $pref['siteadmin'];
				}
				else if(!$news['admin_name'] = getcachedvars($news['admin_id'])) {
					$sql2->db_Select("user", "user_name", "user_id='".intval($news['admin_id'])."' ");
					list($news['admin_name']) = $sql2->db_Fetch();
					cachevars($news['admin_id'], $news['admin_name']);
				}

					$sql2->db_Select("news_category", "*", "category_id='".intval($news_category)."' ");

				list($news['category_id'], $news['category_name'], $news['category_icon']) = $sql2->db_Fetch();
				$news['comment_total'] = $sql2->db_Count("comments", "(*)", "WHERE comment_item_id='".intval($news['news_id'])."' AND comment_type='0' ");
				$ix->render_newsitem($news);
			} 
			/*
			else 
			{
				if ($pref['subnews_hide_news'] == 1) 		This $pref no longer available
				{
					if ($news['admin_id'] == 1 && $pref['siteadmin']) {
						$news['admin_name'] = $pref['siteadmin'];
					}
					else if(!$news['admin_name'] = getcachedvars($news['admin_id'])) {
						$sql2->db_Select("user", "user_name", "user_id='".intval($news['admin_id'])."' ");
						list($news['admin_name']) = $sql2->db_Fetch();
						cachevars($news['admin_id'], $news['admin_name']);
					}

					$sql2->db_Select("news_category", "*", "category_id='".intval($news_category)."' ");

					list($news['category_id'], $news['category_name'], $news['category_icon']) = $sql2->db_Fetch();
					$ix->render_newsitem($news, "", "userclass");
				}
			} */
		}
	}
}

?>