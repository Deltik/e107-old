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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/forum/forum_mod.php,v $
|     $Revision: 1.4 $
|     $Date: 2005-05-07 02:28:20 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
@include_once e_PLUGIN.'forum/languages/'.e_LANGUAGE.'/lan_forum_admin.php';
@include_once e_PLUGIN.'forum/languages/English/lan_forum_admin.php';
	
function forum_thread_moderate($p)
{
	global $sql;
	foreach($p as $key => $val) {
		if (preg_match("#(.*?)_(\d+)_x#", $key, $matches))
		{
			$act = $matches[1];
			$id = $matches[2];
			 
			switch($act)
			{
				case 'lock' :
				$sql->db_Update("forum_t", "thread_active='0' WHERE thread_id='$id' ");
				return FORLAN_CLOSE;
				break;
				 
				case 'unlock' :
				$sql->db_Update("forum_t", "thread_active='1' WHERE thread_id='$id' ");
				return FORLAN_OPEN;
				break;
				 
				case 'stick' :
				$sql->db_Update("forum_t", "thread_s='1' WHERE thread_id='$id' ");
				return FORLAN_STICK;
				break;
				 
				case 'unstick' :
				$sql->db_Update("forum_t", "thread_s='0' WHERE thread_id='$id' ");
				return FORLAN_UNSTICK;
				break;
				 
				case 'delete' :
				return forum_delete_thread($id);
				break;
				 
			}
		}
	}
}
	
function forum_delete_thread($thread_id)
{
	global $sql;
	$sql->db_Select("forum_t", "*", "thread_id='".$thread_id."' ");
	$row = $sql->db_Fetch();
	extract($row);
	 
	if ($thread_parent)
	{
		// is post a reply?
		$sql->db_Delete("forum_t", "thread_id='$thread_id' ");
		// delete reply only
		$sql->db_Update("forum", "forum_replies=forum_replies-1 WHERE forum_id='$thread_forum_id' ");
		// dec reply count by 1
		$sql->db_Update("forum_t", "thread_total_replies=thread_total_replies-1 WHERE thread_id='$thread_parent' ");
		// dec reply count by 1
		$sql->db_Select("forum_t", "*", "thread_id=$thread_id");
		$row = $sql->db_Fetch();
		extract($row);
		$replies = $sql->db_Count("forum_t", "(*)", "WHERE thread_parent='".$thread_parent."'");
		$pref['forum_postspage'] = ($pref['forum_postspage'] ? $pref['forum_postspage'] : 10);
		$pages = 0;
		if ($replies)
		{
			$pages = ((ceil($replies/$pref['forum_postspage']) -1) * $pref['forum_postspage']);
		}
		// set return url
		$url = e_PLUGIN."forum/forum_viewtopic.php?".$thread_parent;
		return FORLAN_26;
	}
	else
	{
		// post is thread
		// delete poll if there is one
		$sql->db_Delete("poll", "poll_datestamp='$thread_id' ");
		// delete replies and grab how many there were
		$count = $sql->db_Delete("forum_t", "thread_parent='$thread_id' ");
		// delete the post itself
		$sql->db_Delete("forum_t", "thread_id='$thread_id' ");
		// update thread/reply counts
		$sql->db_Update("forum", "forum_threads=forum_threads-1, forum_replies=forum_replies-$count WHERE forum_id='$thread_forum_id' ");
		// set return url
		$url = e_PLUGIN."forum_viewforum.php?".$forum_id;
		return FORLAN_6.($count ? ", ".$count." ".FORLAN_7."." : ".");
	}
}
?>