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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/forum/newforumposts_menu.php,v $
|     $Revision: 1.6 $
|     $Date: 2005-05-16 12:46:31 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
global $tp;
$gen = new convert;

$query2 = "
SELECT tp.thread_name AS parent_name, t.thread_thread, t.thread_id, t.thread_name, t.thread_datestamp, t.thread_parent, t.thread_user, t.thread_views, t.thread_lastpost, t.thread_anon, t.thread_lastuser, t.thread_total_replies, f.forum_id, f.forum_name, f.forum_class, u.user_name, fp.forum_class FROM #forum_t AS t 
LEFT JOIN #user AS u ON t.thread_user = u.user_id 
LEFT JOIN #forum_t AS tp ON t.thread_parent = tp.thread_id 
LEFT JOIN #forum AS f ON f.forum_id = t.thread_forum_id 
LEFT JOIN #forum AS fp ON f.forum_parent = fp.forum_id 
WHERE f.forum_class  IN (".USERCLASS_LIST.") 
AND fp.forum_class IN (".USERCLASS_LIST.") 
ORDER BY t.thread_datestamp DESC LIMIT 0, ".$menu_pref['newforumposts_display'];

$results = $sql->db_Select_gen($query2);

if(!$results)
{
	// no posts yet ..
	$text = NFP_2;
}
else
{
	$text = "";
	$forumArray = $sql->db_getList();
	foreach($forumArray as $forumInfo)
	{
		extract($forumInfo);
		$datestamp = $gen->convert_date($thread_datestamp, "short");
		$topic = ($parent_name ? "[re: <i>$parent_name</i>]" : "[thread: <i>$thread_name</i>]");
		$id = $thread_id;

		if(!$thread_user)
		{
			// anonymous post ...
			list($poster, $ip) = explode(chr(1), $thread_anon);				
		}
		else
		{
			$poster = $user_name;
			$poster_id = $thread_user;
		}
		$thread_thread = strip_tags(eregi_replace("\[.*\]", "", $thread_thread));
		$thread_thread = $tp->toHTML($thread_thread, FALSE, "emotes_off", "", $pref['menu_wordwrap']);
		if (strlen($thread_thread) > $menu_pref['newforumposts_characters'])
		{
			$thread_thread = substr($thread_thread, 0, $menu_pref['newforumposts_characters']).$menu_pref['newforumposts_postfix'];
		}
				 
		$text .= "<img src='".THEME."images/".(defined("BULLET") ? BULLET : "bullet2.gif")."' alt='' /> <a href='".e_PLUGIN."forum/forum_viewtopic.php?{$id}.post'><b>".$poster."</b> on ".$datestamp."</a><br/>";
		if ($menu_pref['newforumposts_title']) {
			$text .= $topic."<br />";
		}
		$text .= $thread_thread."<br /><br />";
	}
}

$ns->tablerender($menu_pref['newforumposts_caption'], $text, 'nfp_menu');
	
?>