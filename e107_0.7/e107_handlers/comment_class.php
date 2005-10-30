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
|     $Source: /cvs_backup/e107_0.7/e107_handlers/comment_class.php,v $
|     $Revision: 1.47 $
|     $Date: 2005-10-30 19:11:49 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

@include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_comment.php");
@include_once(e_LANGUAGEDIR."English/lan_comment.php");
require_once(e_FILE."shortcode/batch/comment_shortcodes.php");

/**
 * Enter description here...
 *
 */
class comment {
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $action
	 * @param unknown_type $table
	 * @param unknown_type $id
	 * @param unknown_type $subject
	 * @param unknown_type $content_type
	 * @param unknown_type $return
	 * @param unknown_type $rating
	 * @return unknown
	 */
	function form_comment($action, $table, $id, $subject, $content_type, $return=FALSE, $rating=FALSE) {
		//rating	: boolean, to show rating system in comment
		global $pref, $sql, $tp;
		require_once(e_HANDLER."ren_help.php");
		if (ANON == TRUE || USER == TRUE) {
			$itemid = $id;
			$ns = new e107table;
			if ($action == "reply" && substr($subject, 0, 4) != "Re: ") {
				$subject = COMLAN_5.' '.$subject;
			}
			$text = "\n<div style='text-align:center'><form method='post' action='".e_SELF."?".e_QUERY."' id='dataform' >\n<table style='width:100%'>";
			if ($pref['nested_comments']) {
				$text .= "<tr>\n<td style='width:20%'>".COMLAN_4."</td>\n<td style='width:80%'>\n<input class='tbox' type='text' name='subject' size='66' value='$subject' maxlength='100' />\n</td>\n</tr>";
				$text2 = "";
			} else {
				$text2 = "<input type='hidden' name='subject' value='$subject'  />\n";
			}

			if(strstr(e_QUERY, "edit"))
			{
				$eaction = "edit";
				$tmp = explode(".", e_QUERY);
				$count = 0;
				foreach($tmp as $t)
				{
					if($t == "edit")
					{
						$id = $tmp[($count+1)];
						break;
					}
					$count++;
				}
			}

			if(isset($eaction) && $eaction == "edit")
			{
				$id=intval($id);
				$sql -> db_Select("comments", "*", "comment_id='$id' ");
				$ecom = $sql -> db_Fetch();
				list($prid, $pname) = explode(".", $ecom['comment_author']);

				if($prid != USERID || !USER)
				{
					echo "<div style='text-align: center;'>Unauthorized</div>";
					require_once(FOOTERF);
					exit;
				}
				$caption = LAN_318;
				$comval = $tp -> toFORM($ecom['comment_comment']);
				$comval = preg_replace("#\[ ".LAN_319.".*\]#si", "", $comval);
			}
			else
			{
				$caption = LAN_9;
				$comval = "";
			}

			//add the rating select box/result ?
			$rate = "";
			if($rating == TRUE && !(ANON == TRUE && USER == FALSE) ){
				global $rater;
				require_once(e_HANDLER."rate_class.php");
				if(!is_object($rater)){ $rater = new rater; }
				$rate = $rater -> composerating($table, $itemid, $enter=TRUE, USERID, TRUE);
				$rate = "<tr><td style='width:20%; vertical-align:top;'>".COMLAN_7.":</td>\n<td style='width:80%;'>".$rate."</td></tr>\n";
			}
			//end rating area

			if (ANON == TRUE && USER == FALSE) {
				$text .= "<tr>\n<td style='width:20%; vertical-align:top;'>".LAN_16."</td>\n<td style='width:80%'>\n<input class='tbox' type='text' name='author_name' size='60' value='$author_name' maxlength='100' />\n</td>\n</tr>";
			}
			$text .= $rate."<tr> \n
			<td style='width:20%; vertical-align:top;'>".LAN_8.":</td>\n<td id='commentform' style='width:80%;'>\n<textarea style='width:80%' class='tbox' name='comment' cols='1' rows='7' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>$comval</textarea>\n<br />
			<input class='helpbox' type='text' name='helpb' style='width:80%' /><br />".ren_help(1, 'addtext', 'help')."</td></tr>\n<tr style='vertical-align:top'> \n<td style='width:20%'>".$text2."</td>\n<td id='commentformbutton' style='width:80%;'>\n". (isset($action) && $action == "reply" ? "<input type='hidden' name='pid' value='$id' />" : '').(isset($eaction) && $eaction == "edit" ? "<input type='hidden' name='editpid' value='$id' />" : "").(isset($content_type) && $content_type ? "<input type='hidden' name='content_type' value='$content_type' />" : ''). "<input class='button' type='submit' name='".$action."submit' value='".(isset($eaction) && $eaction == "edit" ? LAN_320 : LAN_9)."' />\n</td>\n</tr>\n</table>\n</form></div>";
			if($return)
			{
				return $text;
			}
			else
			{
				$ns->tablerender($caption, $text);
			}
			} else {
			echo "<br /><div style='text-align:center'><b>".LAN_6." <a href='".e_SIGNUP."'>".COMLAN_1."</a> ".COMLAN_2."</b></div>";
		}
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $row
	 * @param unknown_type $table
	 * @param unknown_type $action
	 * @param unknown_type $id
	 * @param unknown_type $width
	 * @param unknown_type $subject
	 * @param unknown_type $addrating
	 * @return unknown
	 */
	function render_comment($row, $table, $action, $id, $width, $subject, $addrating=FALSE) {
		//addrating	: boolean, to show rating system in rendered comment
		global $sql, $sc_style, $comment_shortcodes, $COMMENTSTYLE, $rater, $gen;
		global $pref, $comrow, $tp, $NEWIMAGE, $USERNAME, $RATING, $datestamp;
		global $thisaction, $thistable, $thisid;

		$comrow				= $row;
		$thistable			= $table;
		$thisid				= $id;
		$thisaction			= $action;
		
		if($addrating===TRUE){
			require_once(e_HANDLER."rate_class.php");
			if(!$rater || !is_object($rater)){ $rater = new rater; }
		}

		require_once(e_HANDLER."level_handler.php");
		if (!$width) {
			$width = 0;
		}
		if(!defined("IMAGE_nonew_comments")){
			define("IMAGE_nonew_comments", (file_exists(THEME."generic/nonew_comments.png") ? "<img src='".THEME_ABS."generic/nonew_comments.png' alt=''  /> " : "<img src='".e_IMAGE_ABS."generic/nonew_comments.png' alt=''  />"));
		}
		if(!defined("IMAGE_new_comments")){
			define("IMAGE_new_comments", (file_exists(THEME."generic/new_comments.png") ? "<img src='".THEME_ABS."generic/new_comments.png' alt=''  /> " : "<img src='".e_IMAGE_ABS."generic/".IMODE."/new_comments.png' alt=''  /> "));
		}
		$ns			= new e107table;
		if(!$gen || !is_object($gen)){ $gen = new convert; }
		$url		= e_PAGE."?".e_QUERY;
		$unblock	= "[<a href='".e_ADMIN_ABS."comment.php?unblock-".$comrow['comment_id']."-$url-".$comrow['comment_item_id']."'>".LAN_1."</a>] ";
		$block		= "[<a href='".e_ADMIN_ABS."comment.php?block-".$comrow['comment_id']."-$url-".$comrow['comment_item_id']."'>".LAN_2."</a>] ";
		$delete		= "[<a href='".e_ADMIN_ABS."comment.php?delete-".$comrow['comment_id']."-$url-".$comrow['comment_item_id']."'>".LAN_3."</a>] ";
		$userinfo	= "[<a href='".e_ADMIN_ABS."userinfo.php?".$comrow['comment_ip']."'>".LAN_4."</a>]";
		
		if (!$COMMENTSTYLE) {
			global $THEMES_DIRECTORY;
			$COMMENTSTYLE = "";
			if (file_exists(THEME."comment_template.php")) {
				require_once(THEME."comment_template.php");
			} else {
				require_once(e_BASE.$THEMES_DIRECTORY."templates/comment_template.php");
			}
		}
		if ($pref['nested_comments']) {
			$width2 = 100 - $width;
			$total_width = (isset($pref['standards_mode']) && $pref['standards_mode'] ? "98%" : "95%");
			if($width){
				$renderstyle = "
				<table style='width:".$total_width."' border='0'>
				<tr>
				<td style='width:".$width."%' ></td>
				<td style='width:".$width2."%'>" .$COMMENTSTYLE. "
				</td>
				</tr>
				</table>";
			}else{
				$renderstyle = $COMMENTSTYLE;
			}
			if($pref['comments_icon']) {
				if ($comrow['comment_datestamp'] > USERLV ) {
					$NEWIMAGE = IMAGE_new_comments;
				} else {
					$NEWIMAGE = IMAGE_nonew_comments;
				}
			} else {
				$NEWIMAGE = "";
			}
		} else {
			$renderstyle = $COMMENTSTYLE;
		}

		$highlight_search = FALSE;
		if (isset($_POST['highlight_search'])) {
			$highlight_search = TRUE;
		}

		if(!defined("IMAGE_rank_main_admin_image")){
			define("IMAGE_rank_main_admin_image", (isset($pref['rank_main_admin_image']) && $pref['rank_main_admin_image'] && file_exists(THEME."forum/".$pref['rank_main_admin_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_main_admin_image']."' alt='' />" : "<img src='".e_IMAGE_ABS."forum/main_admin.png' alt='' />"));
		}
		if(!defined("IMAGE_rank_moderator_image")){
			define("IMAGE_rank_moderator_image", (isset($pref['rank_moderator_image']) && $pref['rank_moderator_image'] && file_exists(THEME."forum/".$pref['rank_moderator_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_moderator_image']."' alt='' />" : "<img src='".e_IMAGE_ABS."forum/admin.png' alt='' />"));
		}
		if(!defined("IMAGE_rank_admin_image")){
			define("IMAGE_rank_admin_image", (isset($pref['rank_admin_image']) && $pref['rank_admin_image'] && file_exists(THEME."forum/".$pref['rank_admin_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_admin_image']."' alt='' />" : "<img src='".e_IMAGE_ABS."forum/admin.png' alt='' />"));
		}

		$RATING = ($addrating==TRUE && $comrow['user_id'] ? $rater->composerating($thistable, $thisid, FALSE, $comrow['user_id']) : "");

		$text = $tp -> parseTemplate($renderstyle, FALSE, $comment_shortcodes);

		if ($action == "comment" && $pref['nested_comments']) {

			$type = $this -> getCommentType($thistable);
			$sub_query = "
			SELECT c.*, u.*, ue.*
			FROM #comments AS c
			LEFT JOIN #user AS u ON c.comment_author = u.user_id 
			LEFT JOIN #user_extended AS ue ON c.comment_author = ue.user_extended_id 
			WHERE comment_item_id='".$thisid."' AND comment_type='".$type."' AND comment_pid='".$comrow['comment_id']."' 
			ORDER BY comment_datestamp
			";

			$sql2 = new db;	/* a new db must be created here, for nested comment  */
			if ($sub_total = $sql2->db_Select_gen($sub_query)) {
				while ($row1 = $sql2->db_Fetch()) {
					if ($pref['nested_comments']) {
						$width = $width + 3;
						if ($width > 80) {
							$width = 80;
						}
					}
					$text .= $this->render_comment($row1, $table, $action, $id, $width, $subject, $addrating);
					unset($width);
				}
			}
		}
		//echo $text;
		return stripslashes($text);
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $author_name
	 * @param unknown_type $comment
	 * @param unknown_type $table
	 * @param unknown_type $id
	 * @param unknown_type $pid
	 * @param unknown_type $subject
	 * @param unknown_type $rateindex
	 */
	function enter_comment($author_name, $comment, $table, $id, $pid, $subject, $rateindex=FALSE) {
		//rateindex	: the posted value from the rateselect box (without the urljump) (see function rateselect())
		global $sql, $sql2, $tp, $e107cache, $e_event, $e107, $pref, $rater;

		if(strstr(e_QUERY, "edit"))
		{
			$eaction = "edit";
			$tmp = explode(".", e_QUERY);
			$count = 0;
			foreach($tmp as $t)
			{
				if($t == "edit")
				{
					$editpid = $tmp[($count+1)];
					break;
				}
				$count++;
			}
		}

		$type = $this -> getCommentType($table);

		$comment = $tp->toDB($comment);
		$subject = $tp->toDB($subject);
		if (!$sql->db_Select("comments", "*", "comment_comment='".$comment."' AND comment_item_id='$id' AND comment_type='$type' ")) {
			if ($_POST['comment']) {
				if (USER == TRUE) {
					$nick = USERID.".".USERNAME;
				} else if($_POST['author_name'] == '') {
					$nick = "0.Anonymous";
				} else {
					if ($sql2->db_Select("user", "*", "user_name='".$_POST['author_name']."' ")) {
						if ($sql2->db_Select("user", "*", "user_name='".$_POST['author_name']."' AND user_ip='$ip' ")) {
							list($cuser_id, $cuser_name) = $sql2->db_Fetch();
							$nick = $cuser_id.".".$cuser_name;
						} else {
							define("emessage", LAN_310);
						}
					} else {
						$nick = "0.".$tp->toDB($author_name);
					}
				}
				
				if (!defined("emessage"))
				{
					$ip = $e107->getip();
					require_once(e_HANDLER."encrypt_handler.php");
					$ip = encode_ip($ip);
					$_t = time();

					if($editpid)
					{
						$comment .= "\n[ ".LAN_319." [time=short]".time()."[/time] ]";
						$sql -> db_Update("comments", "comment_comment='$comment' WHERE comment_id='$editpid' ");
						$e107cache->clear("comment");
						return;
					}

					if (!$sql->db_Insert("comments", "0, '$pid', '$id', '$subject', '$nick', '', '".$_t."', '$comment', '0', '$ip', '$type', '0' "))
					{
						echo "<b>".COMLAN_3."</b> ".LAN_11;
					}
					else
					{
						if (USER == TRUE) {
							$sql -> db_Update("user", "user_comments=user_comments+1, user_lastpost='".time()."' WHERE user_id='".USERID."' ");
						}
						$edata_li = array("comment_type" => $type, "comment_subject" => $subject, "comment_item_id" => $id, "comment_nick" => $nick, "comment_time" => $_t, "comment_comment" => $comment);
						$e_event->trigger("postcomment", $edata_li);
						$e107cache->clear("comment");
						if(!$type || $type == "news")
						{
							$sql->db_Update("news", "news_comment_total=news_comment_total+1 WHERE news_id=$id");
						}
					}
				}
			}
		}
		else
		{
			define("emessage", LAN_312);
		}
		//if rateindex is posted, enter the rating from this user
		if($rateindex){
			$rater -> enterrating($rateindex);
		}
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $table
	 * @return unknown
	 */
	function getCommentType($table){
			switch($table) {
				case "news"		: $type = 0; break;
				case "content"	: $type = 1; break;
				case "download"	: $type = 2; break;
				case "faq"		: $type = 3; break;
				case "poll"		: $type = 4; break;
				case "docs"		: $type = 5; break;
				case "bugtrack"	: $type = 6; break;
				/****************************************
				Add your comment type here in same format as above, ie ...
				case "your_comment_type"; $type = your_type_id; break;
				****************************************/
			}
			if (!isset($type)) {
				$type = $table;
			}
			return $type;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $table
	 * @param unknown_type $id
	 * @return unknown
	 */
	function count_comments($table, $id){
		global $sql;

		if(is_numeric($table)){
			$type = $table;
		}else{
			$type = $this -> getCommentType($table);
		}
		$count_comments = $sql -> db_Count("comments", "(*)", "WHERE comment_item_id='".$id."' AND comment_type='".$type."' ORDER BY comment_item_id");
		return $count_comments;
	}


	/**
	 * Enter description here...
	 *
	 * @param unknown_type $table
	 * @param unknown_type $action
	 * @param unknown_type $id
	 * @param unknown_type $width
	 * @param unknown_type $subject
	 * @param unknown_type $rate
	 */
	function compose_comment($table, $action, $id, $width, $subject, $rate = false){
			//compose comment	: single call function will render the existing comments and show the form_comment
	//rate				: boolean, to show/hide rating system in comment, default FALSE
		global $pref, $sql, $ns, $e107cache, $tp, $totcc;

		$count_comments = $this -> count_comments($table, $id, $pid=FALSE);

		$type = $this -> getCommentType($table);

		$text = "";
		$query = ($pref['nested_comments'] ?
		"SELECT c.*, u.*, ue.* FROM #comments AS c
		LEFT JOIN #user AS u ON c.comment_author = u.user_id 
		LEFT JOIN #user_extended AS ue ON c.comment_author = ue.user_extended_id 
		WHERE c.comment_item_id='".$id."' AND c.comment_type='".$type."' AND c.comment_pid='0' ORDER BY c.comment_datestamp"
		:
		"SELECT c.*, u.*, ue.* FROM #comments AS c
		LEFT JOIN #user AS u ON c.comment_author = u.user_id 
		LEFT JOIN #user_extended AS ue ON c.comment_author = ue.user_extended_id 
		WHERE c.comment_item_id='".$id."' AND c.comment_type='".$type."' ORDER BY c.comment_datestamp"
		);

		$text = "";
		$comment_total = $sql->db_Select_gen($query);
		if ($comment_total) {
			$width = 0;
			while ($row = $sql->db_Fetch()) {
				$lock = $row['comment_lock'];
				$subject = $tp->toHTML($subject);
				if ($pref['nested_comments']) {
					$text .= $this->render_comment($row, $table , $action, $id, $width, $subject, $rate);
				} else {
					$text .= $this->render_comment($row, $table , $action, $id, $width, $subject, $rate);
				}
			}
			$ns->tablerender(LAN_99, $text);

			if(ADMIN==TRUE && getperms("B")){
				echo "<div style='text-align:right'><a href='".e_ADMIN_ABS."modcomment.php?$table.$id'>".LAN_314."</a></div><br />";
			}
		}
		if($lock != "1"){
			$this->form_comment($action, $table, $id, $subject, "", "", $rate);
		}else{
			echo "<br /><div style='text-align:center'><b>".COMLAN_8."</b></div>";
		}
		return;
	}
}

?>
