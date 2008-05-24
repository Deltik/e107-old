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
|     $Source: /cvs_backup/e107_0.8/e107_handlers/comment_class.php,v $
|     $Revision: 1.11 $
|     $Date: 2008-05-24 15:14:36 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

@include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_comment.php");
@include_once(e_LANGUAGEDIR."English/lan_comment.php");
global $comment_shortcodes;
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
	function form_comment($action, $table, $id, $subject, $content_type, $return=FALSE, $rating=FALSE, $tablerender=TRUE)
	{
		//rating	: boolean, to show rating system in comment
		global $pref, $sql, $tp;
		if(isset($pref['comments_disabled']) && $pref['comments_disabled'] == TRUE){
        	return;
		}

		require_once(e_HANDLER."ren_help.php");
		if (ANON == TRUE || USER == TRUE)
		{
			$itemid = $id;
			$ns = new e107table;
			if ($action == "reply" && substr($subject, 0, 4) != "Re: ")
			{
				$subject = COMLAN_325.' '.$subject;
			}

			$text = "\n<div style='text-align:center'><form method='post' action='".e_SELF."?".e_QUERY."' id='dataform' >\n<table style='width:100%'>";

			if ($pref['nested_comments'])
			{
				$text .= "<tr>\n<td style='width:20%'>".COMLAN_324."</td>\n<td style='width:80%'>\n<input class='tbox comment subject' type='text' name='subject' size='61' value='".$tp -> toForm($subject)."' maxlength='100' />\n</td>\n</tr>";
				$text2 = "";
			}
			else
			{
				$text2 = "<input type='hidden' name='subject' value='".$tp -> toForm($subject)."'  />\n";
			}

			if (isset($_GET['comment']) && $_GET['comment'] == 'edit')
			{
				$eaction = 'edit';
				$id = $_GET['comment_id'];
			}
			else if (strstr(e_QUERY, "edit"))
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
				if (isset($ecom['comment_author']))
				{	// Old comment DB format
				  list($prid, $pname) = explode(".", $ecom['comment_author'],2);
				}
				else
				{
				  $prid = $ecom['comment_author_id'];
				  $pname = $ecom['comment_author_name'];
				}

				if($prid != USERID || !USER)
				{
					echo "<div style='text-align: center;'>".COMLAN_329."</div>";
					require_once(FOOTERF);
					exit;
				}

				$caption = COMLAN_318;
				$comval = $tp -> toFORM($ecom['comment_comment']);
				$comval = preg_replace("#\[ ".COMLAN_319.".*\]#si", "", $comval);
			}
			else
			{
				$caption = COMLAN_9;
				$comval = "";
			}

			//add the rating select box/result ?
			$rate = "";
			if($rating == TRUE && !(ANON == TRUE && USER == FALSE) )
			{
				global $rater;
				require_once(e_HANDLER."rate_class.php");
				if(!is_object($rater)){ $rater = new rater; }
				$rate = $rater -> composerating($table, $itemid, $enter=TRUE, USERID, TRUE);
				$rate = "<tr><td style='width:20%; vertical-align:top;'>".COMLAN_327.":</td>\n<td style='width:80%;'>".$rate."</td></tr>\n";
			}
			//end rating area

			if (ANON == TRUE && USER == FALSE)
			{
				$text .= "<tr>\n<td style='width:20%; vertical-align:top;'>".COMLAN_16."</td>\n<td style='width:80%'>\n<input class='tbox comment author' type='text' name='author_name' size='61' value='$author_name' maxlength='100' />\n</td>\n</tr>";
			}
			$text .= $rate."<tr> \n
			<td style='width:20%; vertical-align:top;'>".COMLAN_8.":</td>\n<td id='commentform' style='width:80%;'>\n<textarea class='tbox comment' id='comment' name='comment' cols='62' rows='7' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>$comval</textarea>\n<br />
			".display_help('helpb',"comment")."</td></tr>\n<tr style='vertical-align:top'> \n<td style='width:20%'>".$text2."</td>\n<td id='commentformbutton' style='width:80%;'>\n". (isset($action) && $action == "reply" ? "<input type='hidden' name='pid' value='$id' />" : '').(isset($eaction) && $eaction == "edit" ? "<input type='hidden' name='editpid' value='$id' />" : "").(isset($content_type) && $content_type ? "<input type='hidden' name='content_type' value='$content_type' />" : ''). "<input class='button' type='submit' name='".$action."submit' value='".(isset($eaction) && $eaction == "edit" ? COMLAN_320 : COMLAN_9)."' />\n</td>\n</tr>\n</table>\n</form></div>";

			if($tablerender)
			{
				$text = $ns->tablerender($caption, $text, '', TRUE);
			}

			if($return)
			{
				return $text;
			}
			else
			{
				echo $text;
			}
		}
		else
		{
			echo "<br /><div style='text-align:center'><b>".COMLAN_6." <a href='".e_SIGNUP."'>".COMLAN_321."</a> ".COMLAN_322."</b></div>";
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
	function render_comment($row, $table, $action, $id, $width, $subject, $addrating=FALSE) 
	{
		//addrating	: boolean, to show rating system in rendered comment
		global $sql, $sc_style, $comment_shortcodes, $COMMENTSTYLE, $rater, $gen, $imode;
		global $pref, $comrow, $tp, $NEWIMAGE, $USERNAME, $RATING, $datestamp;
		global $thisaction, $thistable, $thisid;

		if(isset($pref['comments_disabled']) && $pref['comments_disabled'] == TRUE)
		{
          return;
		}


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
			define("IMAGE_nonew_comments", (file_exists(THEME."images/nonew_comments.png") ? "<img src='".THEME_ABS."images/nonew_comments.png' alt=''  /> " : "<img src='".e_IMAGE_ABS."packs/".$imode."/generic/nonew_comments.png' alt=''  />"));
		}
		if(!defined("IMAGE_new_comments")){
			define("IMAGE_new_comments", (file_exists(THEME."images/new_comments.png") ? "<img src='".THEME_ABS."images/new_comments.png' alt=''  /> " : "<img src='".e_IMAGE_ABS."packs/".$imode."/generic/new_comments.png' alt=''  /> "));
		}
		$ns			= new e107table;
		if(!$gen || !is_object($gen)){ $gen = new convert; }
		$url		= e_PAGE."?".e_QUERY;
		$unblock	= "[<a href='".e_ADMIN_ABS."comment.php?unblock-".$comrow['comment_id']."-$url-".$comrow['comment_item_id']."'>".COMLAN_1."</a>] ";
		$block		= "[<a href='".e_ADMIN_ABS."comment.php?block-".$comrow['comment_id']."-$url-".$comrow['comment_item_id']."'>".COMLAN_2."</a>] ";
		$delete		= "[<a href='".e_ADMIN_ABS."comment.php?delete-".$comrow['comment_id']."-$url-".$comrow['comment_item_id']."'>".COMLAN_3."</a>] ";
		$userinfo	= "[<a href='".e_ADMIN_ABS."userinfo.php?".$comrow['comment_ip']."'>".COMLAN_4."</a>]";

		if (!$COMMENTSTYLE) 
		{
			global $THEMES_DIRECTORY;
			$COMMENTSTYLE = "";
			if (file_exists(THEME."comment_template.php")) {
				require_once(THEME."comment_template.php");
			} else {
				require_once(e_BASE.$THEMES_DIRECTORY."templates/comment_template.php");
			}
		}
		if ($pref['nested_comments']) 
		{
			$width2 = 100 - $width;
			$total_width = "95%";
			if($width)
			{
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
			define("IMAGE_rank_main_admin_image", (isset($pref['rank_main_admin_image']) && $pref['rank_main_admin_image'] && file_exists(THEME."forum/".$pref['rank_main_admin_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_main_admin_image']."' alt='' />" : "<img src='".e_PLUGIN_ABS."forum/images/".IMODE."/main_admin.png' alt='' />"));
		}
		if(!defined("IMAGE_rank_moderator_image")){
			define("IMAGE_rank_moderator_image", (isset($pref['rank_moderator_image']) && $pref['rank_moderator_image'] && file_exists(THEME."forum/".$pref['rank_moderator_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_moderator_image']."' alt='' />" : "<img src='".e_PLUGIN_ABS."forum/images/".IMODE."/admin.png' alt='' />"));
		}
		if(!defined("IMAGE_rank_admin_image")){
			define("IMAGE_rank_admin_image", (isset($pref['rank_admin_image']) && $pref['rank_admin_image'] && file_exists(THEME."forum/".$pref['rank_admin_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_admin_image']."' alt='' />" : "<img src='".e_PLUGIN_ABS."forum/images/".IMODE."/admin.png' alt='' />"));
		}

		$RATING = ($addrating==TRUE && $comrow['user_id'] ? $rater->composerating($thistable, $thisid, FALSE, $comrow['user_id']) : "");

		$text = $tp -> parseTemplate($renderstyle, TRUE, $comment_shortcodes);

		if ($action == "comment" && $pref['nested_comments']) 
		{
			$type = $this -> getCommentType($thistable);
			$sub_query = "
			SELECT c.*, u.*, ue.*
			FROM #comments AS c
			LEFT JOIN #user AS u ON c.comment_author_id = u.user_id
			LEFT JOIN #user_extended AS ue ON c.comment_author_id = ue.user_extended_id
			WHERE comment_item_id='".intval($thisid)."' AND comment_type='".$tp -> toDB($type, true)."' AND comment_pid='".intval($comrow['comment_id'])."'
			ORDER BY comment_datestamp
			";

			$sql_nc = new db;	/* a new db must be created here, for nested comment  */
			if ($sub_total = $sql_nc->db_Select_gen($sub_query)) 
			{
			  while ($row1 = $sql_nc->db_Fetch()) 
			  {
				if ($pref['nested_comments']) 
				{
				  $width = min($width + 3, 80);
				}
				$text .= $this->render_comment($row1, $table, $action, $id, $width, $subject, $addrating);
				unset($width);
			  }
			}
		}		// End (nested comment handling)
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
	function enter_comment($author_name, $comment, $table, $id, $pid, $subject, $rateindex=FALSE) 
	{
		//rateindex	: the posted value from the rateselect box (without the urljump) (see function rateselect())
		global $sql, $sql2, $tp, $e107cache, $e_event, $e107, $pref, $rater;

		if(isset($pref['comments_disabled']) && $pref['comments_disabled'] == TRUE)
		{
          return;
		}

		if (isset($_GET['comment']) && $_GET['comment'] == 'edit')
		{
		  $eaction = 'edit';
		  $editpid = $_GET['comment_id'];
		}
		elseif (strstr(e_QUERY, "edit"))
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
		$cuser_id = 0;
		$cuser_name = 'Anonymous';		// Preset as an anonymous comment
		if (!$sql->db_Select("comments", "*", "comment_comment='".$comment."' AND comment_item_id='".intval($id)."' AND comment_type='".$tp -> toDB($type, true)."' ")) 
		{
		  if ($_POST['comment']) 
		  {
			if (USER == TRUE) 
			{
			  $cuser_id = USERID;
			  $cuser_name = USERNAME;
			} 
			elseif($_POST['author_name'] != '') 
			{  // See if author name is registered user
			  if ($sql2->db_Select("user", "*", "user_name='".$tp -> toDB($_POST['author_name'])."' ")) 
			  {
				if ($sql2->db_Select("user", "*", "user_name='".$tp -> toDB($_POST['author_name'])."' AND user_ip='".$tp -> toDB($ip, true)."' ")) 
				{
				  list($cuser_id, $cuser_name) = $sql2->db_Fetch();
				} 
				else 
				{
				  define("emessage", COMLAN_310);
				}
			  } 
			  else 
			  {	// User not on-line, so can't be entering comments
				$cuser_name = $tp->toDB($author_name);
			  }
			}

			if (!defined("emessage"))
			{
			  $ip = $e107->getip();		// Store IP 'in the raw' - could be IPv4 or IPv6
			  $_t = time();

			  if($editpid)
			  {
				$comment .= "\n[ ".COMLAN_319." [time=short]".time()."[/time] ]";
				$sql -> db_Update("comments", "comment_comment='{$comment}' WHERE comment_id='".intval($editpid)."' ");
				$e107cache->clear("comment");
				return;
			  }

			  $edata_li = array(
			  // comment_id - auto-assigned
			    'comment_pid' => intval($pid),
				"comment_item_id" => $id, 
				"comment_subject" => $subject, 
				'comment_author_id' => $cuser_id,
				'comment_author_name' => $cuser_name,
			  //	'comment_author_email' => '',   Field not saved ATM
				"comment_datestamp" => $_t, 
				"comment_comment" => $comment,
				'comment_ip' => $ip,
				"comment_type" => $tp -> toDB($type, true)
				);
//			  if (!$sql->db_Insert("comments", "0, '".intval($pid)."', '".intval($id)."', '$subject', '$nick', '', '".$_t."', '$comment', '0', '$ip', '".$tp -> toDB($type, true)."', '0' "))
			  if (!$sql->db_Insert("comments", $edata_li))
			  {
				echo "<b>".COMLAN_323."</b> ".COMLAN_11;
			  }
			  else
			  {
				if (USER == TRUE) 
				{
				  $sql -> db_Update("user", "user_comments=user_comments+1, user_lastpost='".time()."' WHERE user_id='".USERID."' ");
				}
				// Next item for backward compatibility
				$edata_li["comment_nick"] = $cuser_id.'.'.$cuser_name;
				$edata_li["comment_time"] = $_t;
				unset($edata_li['comment_pid']);
				unset($edata_li['comment_author_email']);
				unset($edata_li['comment_ip']);
				
//				$edata_li = array("comment_type" => $type, "comment_subject" => $subject, "comment_item_id" => $id, "comment_nick" => $nick, "comment_time" => $_t, "comment_comment" => $comment);
				$e_event->trigger("postcomment", $edata_li);
				$e107cache->clear("comment");
				if(!$type || $type == "news")
				{
				  $sql->db_Update("news", "news_comment_total=news_comment_total+1 WHERE news_id=".intval($id));
				}
			  }
			}
		  }
		}
		else
		{
			define("emessage", COMLAN_312);
		}
		//if rateindex is posted, enter the rating from this user
		if($rateindex){
			$rater -> enterrating($rateindex);
		}

		if(defined("emessage"))
		{
			message_handler("ALERT", emessage);
		}
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $table
	 * @return unknown
	 */
	function getCommentType($table)
	{
			if(is_numeric($table)) { return $table;	}

			switch($table)
			{
				case "news"			: $type = 0; break;
				case "content"	: $type = 1; break;
				case "download"	: $type = 2; break;
				case "faq"			: $type = 3; break;
				case "poll"			: $type = 4; break;
				case "docs"			: $type = 5; break;
				case "bugtrack"	: $type = 6; break;
				default					: $type = $table; break;
				/****************************************
				Add your comment type here in same format as above, ie ...
				case "your_comment_type"; $type = your_type_id; break;
				****************************************/
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
	function count_comments($table, $id)
	{
		global $sql, $tp;
		$type = $this -> getCommentType($table);
		$count_comments = $sql -> db_Count("comments", "(*)", "WHERE comment_item_id='".intval($id)."' AND comment_type='".$tp -> toDB($type, true)."' ORDER BY comment_item_id");
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
	function compose_comment($table, $action, $id, $width, $subject, $rate=FALSE, $return=FALSE, $tablerender=TRUE){
		//compose comment	: single call function will render the existing comments and show the form_comment
		//rate				: boolean, to show/hide rating system in comment, default FALSE
		global $pref, $sql, $ns, $e107cache, $tp, $totcc;

		if(isset($pref['comments_disabled']) && $pref['comments_disabled'] == TRUE){
        	return;
		}

		$count_comments = $this -> count_comments($table, $id, $pid=FALSE);

		$type = $this -> getCommentType($table);

		$query = $pref['nested_comments'] ?
		"SELECT c.*, u.*, ue.* FROM #comments AS c
		LEFT JOIN #user AS u ON c.comment_author_id = u.user_id
		LEFT JOIN #user_extended AS ue ON c.comment_author_id = ue.user_extended_id
		WHERE c.comment_item_id='".intval($id)."' AND c.comment_type='".$tp -> toDB($type, true)."' AND c.comment_pid='0' ORDER BY c.comment_datestamp"
		:
		"SELECT c.*, u.*, ue.* FROM #comments AS c
		LEFT JOIN #user AS u ON c.comment_author_id = u.user_id
		LEFT JOIN #user_extended AS ue ON c.comment_author_id = ue.user_extended_id
		WHERE c.comment_item_id='".intval($id)."' AND c.comment_type='".$tp -> toDB($type, true)."' ORDER BY c.comment_datestamp";

		$text = "";
		$comment = '';
		$modcomment = '';
		$lock = '';
		$ret['comment'] = '';

		if ($comment_total = $sql->db_Select_gen($query))
		{
			$width = 0;
			while ($row = $sql->db_Fetch())
			{
				$lock = $row['comment_lock'];
				// $subject = $tp->toHTML($subject);
				if ($pref['nested_comments'])
				{
					$text .= $this->render_comment($row, $table , $action, $id, $width, $tp->toHTML($subject), $rate);
				}
				else
				{
					$text .= $this->render_comment($row, $table , $action, $id, $width, $tp->toHTML($subject), $rate);
				}
			}

			if ($tablerender)
			{
				$text = $ns->tablerender(COMLAN_99, $text, '', TRUE);
			}

			if (!$return)
			{
				echo $text;
            }
            else
            {
				$ret['comment'] = $text;
			}

			if (ADMIN && getperms("B"))
			{
				$modcomment =  "<div style='text-align:right'><a href='".e_ADMIN_ABS."modcomment.php?$table.$id'>".COMLAN_314."</a></div><br />";
			}
		}

		if ($lock != "1")
		{
		   	$comment =	$this->form_comment($action, $table, $id, $subject, "", TRUE, $rate, $tablerender);
		}
		else
		{
			$comment = "<br /><div style='text-align:center'><b>".COMLAN_328."</b></div>";
		}

		if (!$return)
		{
          	echo $modcomment.$comment;
		}

		$ret['comment'] .= $modcomment;
		$ret['comment_form'] = $comment;
		$ret['caption'] = COMLAN_99;

		return (!$return) ? "" : $ret;
	}

	function recalc_user_comments($id)
	{
	  global $sql;
	
	  if(is_array($id))
	  {
		foreach($id as $_id)
		{
		  $this->recalc_user_comments($_id);
		}
		return;
	  }
	  $qry = "
		SELECT COUNT(*) AS count
		FROM #comments
		WHERE comment_author_id = '{$id}'
		";
	  if($sql->db_Select_gen($qry))
	  {
		$row = $sql->db_Fetch();
		$sql->db_Update("user","user_comments = '{$row['count']}' WHERE user_id = '{$id}'");
	  }
	}

	
	function get_author_list($id, $comment_type)
	{
		global $sql;
		$authors = array();
		$qry = "
		SELECT DISTINCT(comment_author_id) AS author 
		FROM #comments
		WHERE comment_item_id='{$id}' AND comment_type='{$comment_type}' 
		GROUP BY author
		";
		if($sql->db_Select_gen($qry))
		{
			while($row = $sql->db_Fetch())
			{
				$authors[] = $row['author'];
			}
		}
		return $authors;
	}



	function delete_comments($table, $id)
	{
		global $sql, $tp;

		$type = $this -> getCommentType($table);
		$type = $tp -> toDB($type, true);
		$id = intval($id);
		$author_list = $this->get_author_list($id, $type);
		$num_deleted = $sql -> db_Delete("comments", "comment_item_id='{$id}' AND comment_type='{$type}'");
		$this->recalc_user_comments($author_list);
		return $num_deleted;
	}


	//1) call function getCommentData(); from file
	//2) function-> get number of records from comments db
	//3) get all e_comment.php files and collect the variables
	//4) interchange the db rows and the e_ vars
	//5) return the interchanged data in array
	//6) from file: render the returned data

	//get all e_comment.php files and collect the variables
	function get_e_comment()
	{
	  $data = getcachedvars('e_comment');
	  if($data!==FALSE)
	  {
		return $data;
	  }

		require_once(e_HANDLER."file_class.php");
		$fl = new e_file;

		$omit = array('^\.$','^\.\.$','^\/$','^CVS$','thumbs\.db','.*\._$','.bak$');
		$files = $fl->get_files(e_PLUGIN, $fmask = 'e_comment.php', $omit, $recurse_level = 1, $current_level = 0);

		foreach($files as $file){
			unset($e_comment, $key);
			include($file['path'].$file['fname']);
			if($e_comment && is_array($e_comment)){
				$key = $e_comment['eplug_comment_ids'];
				if(isset($key) && $key!=''){
					$data[$key] = $e_comment;
				}
			}else{
				//convert old method variables into the same array method
				$key = $e_plug_table;
				if(isset($key) && $key!=''){
					$e_comment['eplug_comment_ids']	= $e_plug_table;
					$e_comment['plugin_name']		= $plugin_name;
					$e_comment['plugin_path']		= $plugin_path;
					$e_comment['reply_location']	= $reply_location;
					$e_comment['db_title']			= $link_name;
					$e_comment['db_id']				= $db_id;
					$e_comment['db_table']			= $db_table;
					$e_comment['qry']				= '';
					$data[$key] = $e_comment;
				}
			}
		}
		cachevars('e_comment', $data);
		return $data;
	}


	/*
	* get number of records from comments db
	* interchange the db rows and the e_comment vars
	* return the interchanged data in array
	*
	* @param int $amount : holds numeric value for number of comments to ge
	* @param int $from : holds numeric value from where to start retrieving
	* @param string $qry : holds custom query to add in the comment retrieval
	* next two parms are only used in iterating loop if less valid records are found
	* @param int $cdvalid : number of valid records found
	* @param array $cdreta : current data set
	*/

	function getCommentData($amount='', $from='', $qry='', $cdvalid=FALSE, $cdreta=FALSE)
	{
		global $pref, $menu_pref, $sql, $sql2, $tp;

		$from1 = ($from ? $from : '0');
		$amount1 = ($amount ? $amount : '10');
		$valid = ($cdvalid ? $cdvalid : '0');
		$reta = ($cdreta ? $cdreta : array());

		//get all e_comment data
		$e_comment = $this->get_e_comment();

		$qry1 = ($qry ? " AND ".$qry : "");

		//get 'amount' of records from comment db
		/*
		$query = $pref['nested_comments'] ?
		"SELECT c.*, u.*, ue.* FROM #comments AS c
		LEFT JOIN #user AS u ON c.comment_author = u.user_id
		LEFT JOIN #user_extended AS ue ON c.comment_author = ue.user_extended_id
		WHERE c.comment_pid='0' ".$qry1." ORDER BY c.comment_datestamp DESC LIMIT ".intval($from1).",".intval($amount1)." "
		:
		"SELECT c.*, u.*, ue.* FROM #comments AS c
		LEFT JOIN #user AS u ON c.comment_author = u.user_id
		LEFT JOIN #user_extended AS ue ON c.comment_author = ue.user_extended_id
		WHERE c.comment_id!='' ".$qry1." ORDER BY c.comment_datestamp DESC LIMIT ".intval($from1).",".intval($amount1)." ";
		*/

		$query = "
		SELECT c.*, u.*, ue.* FROM #comments AS c
		LEFT JOIN #user AS u ON c.comment_author_id = u.user_id
		LEFT JOIN #user_extended AS ue ON c.comment_author_id = ue.user_extended_id
		WHERE c.comment_id!='' ".$qry1." ORDER BY c.comment_datestamp DESC LIMIT ".intval($from1).",".intval($amount1)." ";

		if ($comment_total = $sql->db_Select_gen($query))
		{
		  $width = 0;
		  while ($row = $sql->db_Fetch())
		  {
			$ret = array();

			//date
			$ret['comment_datestamp'] = $row['comment_datestamp'];

			//author - no ned to split now
			$comment_author_id = $ret['comment_author_id'];
			$comment_author_name = $ret['comment_author_name'];
			$ret['comment_author'] = (USERID ? "<a href='".e_BASE."user.php?id.".$comment_author_id."'>".$comment_author_name."</a>" : $comment_author_name);

			//comment text
			$comment = strip_tags(preg_replace("/\[.*\]/", "", $row['comment_comment'])); // remove bbcode
			$ret['comment_comment'] = $tp->toHTML($comment, FALSE, "", "", $pref['main_wordwrap']);

			//subject
			$ret['comment_subject'] = $tp->toHTML($row['comment_subject'], TRUE);

			switch ($row['comment_type'])
			{
				  case '0' :	// news
					if($sql2 -> db_Select("news", "*", "news_id='".$row['comment_item_id']."' AND news_class REGEXP '".e_CLASS_REGEXP."' "))
					{
						$row2 = $sql2 -> db_Fetch();

						$ret['comment_type']				= COMLAN_TYPE_1;
						$ret['comment_title']				= $tp -> toHTML($row2['news_title'], TRUE,'emotes_off, no_make_clickable');
						$ret['comment_url']					= e_BASE."comment.php?comment.news.".$row['comment_item_id'];
						$ret['comment_category_heading']	= COMLAN_TYPE_1;
						$ret['comment_category_url']		= e_BASE."news.php";
					}
					break;
					
				  case '1' :	//	article, review or content page - defunct category, but filter them out
					break;
					
				  case '2' :	//	downloads
					$qryd = "SELECT d.download_name, dc.download_category_class, dc.download_category_id, dc.download_category_name FROM #download AS d LEFT JOIN #download_category AS dc ON d.download_category=dc.download_category_id WHERE d.download_id={$row['comment_item_id']} AND dc.download_category_class REGEXP '".e_CLASS_REGEXP."' ";
					if($sql2->db_Select_gen($qryd))
					{
						$row2 = $sql2->db_Fetch();

						$ret['comment_type']				= COMLAN_TYPE_2;
						$ret['comment_title']				= $tp -> toHTML($row2['download_name'], TRUE,'emotes_off, no_make_clickable');
						$ret['comment_url']					= e_BASE."download.php?view.".$row['comment_item_id'];
						$ret['comment_category_heading']	= $row2['download_category_name'];
						$ret['comment_category_url']		= e_BASE."download.php?list.".$row2['download_category_id'];
					}
					break;
						// '3' was FAQ
				  case '4' :	//	poll
					if($sql2 -> db_Select("polls", "*", "poll_id='".$row['comment_item_id']."' "))
					{
						$row2 = $sql2 -> db_Fetch();

						$ret['comment_type']				= COMLAN_TYPE_4;
						$ret['comment_title']				= $tp -> toHTML($row2['poll_title'], TRUE,'emotes_off, no_make_clickable');
						$ret['comment_url']					= e_BASE."comment.php?comment.poll.".$row['comment_item_id'];
					}
					break;
					
					// '5' was docs
					// '6' was bugtracker
					// 'ideas' was implemented
			
				  case 'profile' :		//	userprofile
					if(USER)
					{
						$ret['comment_type']				= COMLAN_TYPE_8;
						$ret['comment_title']				= $comment_author_name;
						$ret['comment_url']					= e_BASE."user.php?id.".$row['comment_item_id'];
					}
					break;

				  case 'page' :		//	Custom Page
					$ret['comment_type']				= COMLAN_TYPE_PAGE;
					$ret['comment_title']				= $ret['comment_subject'] ? $ret['comment_subject'] : $ret['comment_comment'];
					$ret['comment_url']					= e_BASE."page.php?".$row['comment_item_id'];
					break;

				  default :
					if(isset($e_comment[$row['comment_type']]) && is_array($e_comment[$row['comment_type']]))
					{
					  $var = $e_comment[$row['comment_type']];
					  $qryp='';
						//new method must use the 'qry' variable
					  if(isset($var) && $var['qry']!='')
					  {
//						if ($installed = $sql2 -> db_Select("plugin", "*", "plugin_path = '".$var['plugin_path']."' AND plugin_installflag = '1' "))
						if ($installed = isset($pref['plug_installed'][$var['plugin_path']]))
						{
							$qryp = str_replace("{NID}", $row['comment_item_id'], $var['qry']);
							if($sql2 -> db_Select_gen($qryp))
							{
								$row2 = $sql2 -> db_Fetch();
								$ret['comment_type']				= $var['plugin_name'];
								$ret['comment_title']				= $tp -> toHTML($row2[$var['db_title']], TRUE,'emotes_off, no_make_clickable');
								$ret['comment_url']					= str_replace("{NID}", $row['comment_item_id'], $var['reply_location']);
								$ret['comment_category_heading']	= $var['plugin_name'];
								$ret['comment_category_url']		= $var['plugin_name'];
							}
						}
					//old method
					  }
					  else
					  {
						if($sql2 -> db_Select($var['db_table'], $var['db_title'], $var['db_id']." = '".$row['comment_item_id']."' ")){
							$row2 = $sql2 -> db_Fetch();
							$ret['comment_type']				= $var['plugin_name'];
							$ret['comment_title']				= $tp -> toHTML($row2[$var['db_title']], TRUE,'emotes_off, no_make_clickable');
							$ret['comment_url']					= str_replace("{NID}", $row['comment_item_id'], $var['reply_location']);
							$ret['comment_category_heading']	= $var['plugin_name'];
							$ret['comment_category_url']		= $var['plugin_name'];
						}
					  }
					}
			}		// End Switch
			if($ret['comment_title'])
			{
			  $reta[] = $ret;
			  $valid++;
			}
			if($amount && $valid>=$amount)
			{
			  return $reta;
			}
		  }
			//loop if less records found than given $amount - probably because we discarded some
		  if($amount && ($valid<$amount))
		  {
			$reta = $this->getCommentData($amount, $from+$amount, $qry, $valid, $reta);
		  }
		}
		return $reta;
	}

} //end class

?>