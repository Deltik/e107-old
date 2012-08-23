<?php
/*
 * e107 website system
 *
 * Copyright (C) 2002-2010 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Comment handler class
 *
 * $URL$
 * $Id$
 */

if (!defined('e107_INIT'))
{
	exit;
}
include_lan(e_LANGUAGEDIR.e_LANGUAGE."/lan_comment.php");
global $comment_shortcodes;
require_once (e_CORE."shortcodes/batch/comment_shortcodes.php");
/**
 *
 *	@package     e107
 *	@subpackage	e107_handlers
 *	@version 	$Id$;
 *
 *	This class handles all comment-related functions.
 */


class comment
{
	public $known_types = array(
			0	=> "news",
			1	=> 'content',
			2	=> 'download',
			3	=> 'faq',
			4	=> 'poll',
			5	=> 'docs',
			6	=> 'bugtrack'
	);

	private $template;
	
	private $totalComments = 0;
	
	private $moderator = false;
	
	private $commentsPerPage = 5;
	
	private $table = null;

	function __construct()
	{
		
		if(getperms('B')) // moderator perms. 
		{
			$this->moderator = true;	
		}
		
		//TODO - add a pref for comments per page. 
		// $this->commentsPerPage = pref; 
				
		global $COMMENTSTYLE;
			
		if (!$COMMENTSTYLE)
		{
			require_once(e107::coreTemplatePath('comment'));	
		}
		elseif(!$COMMENT_TEMPLATE) // BC template. 
		{
			global $sc_style;
			/*
			$COMMENTSTYLE = "
				<table class='fborder' style='".USER_WIDTH."'>
				<tr>
					<td colspan='2' class='forumheader'>
						{SUBJECT} {USERNAME} {TIMEDATE} {REPLY} {COMMENTEDIT}
					</td>
				</tr>
				<tr>
					<td style='width:30%; vertical-align:top;'>
						{AVATAR}<span class='smalltext'>{COMMENTS}{JOINED}</span>
					</td>
					<td style='width:70%; vertical-align:top;'>
						{COMMENT}
						{RATING}
						{IPADDRESS}
						{LEVEL}
						{LOCATION}
						{SIGNATURE}
					</td>
				</tr>
				</table>
				<br />";
			*/	
			$COMMENT_TEMPLATE['ITEM_START'] = "";
			$COMMENT_TEMPLATE['ITEM'] 		= $COMMENTSTYLE;	
			$COMMENT_TEMPLATE['ITEM_END'] 	= "";
			$COMMENT_TEMPLATE['LAYOUT'] 	= "{COMMENTS}{COMMENTFORM}{MODERATE}{COMMENTNAV}";
			$COMMENT_TEMPLATE['FORM']			= "<table style='width:100%'>
													{SUBJECT_INPUT}
													{AUTHOR_INPUT}
													{RATE_INPUT}
													{COMMENT_INPUT}
													{COMMENT_BUTTON}
												</table>";
			
			$sc_style['SUBJECT_INPUT']['pre']		= "<tr><td style='width:20%'>".COMLAN_324."</td><td style='width:80%'>";
			$sc_style['SUBJECT_INPUT']['post']		= "</td></tr>";
			
			$sc_style['AUTHOR_INPUT']['pre']		= "<tr><td style='width:20%; vertical-align:top;'>".COMLAN_16."</td><td style='width:80%'>";
			$sc_style['AUTHOR_INPUT']['post']		= "</td></tr>";
			
			$sc_style['RATE_INPUT']['pre']			= "<tr><td style='width:20%; vertical-align:top;'>".COMLAN_327.":</td><td style='width:80%;'>";
			$sc_style['RATE_INPUT']['post']			= "</td></tr>";
			
			$sc_style['COMMENT_INPUT']['pre']		= "<tr><td style='width:20%; vertical-align:top;'>".COMLAN_8.":</td><td id='commentform' style='width:80%;'>";
			$sc_style['COMMENT_INPUT']['post']		= "</td></tr>";
			
			$sc_style['COMMENT_BUTTON']['pre']		= "<tr style='vertical-align:top'><td colspan='2' id='commentformbutton' style='width:80%;'>";
			$sc_style['COMMENT_BUTTON']['post']		= "</td></tr>";
					
		}	
		
		$this->template = $COMMENT_TEMPLATE;
		
		
	}



	function replyComment($id) // Ajax Reply. 
	{
		$sql = e107::getDb();
		if($sql->db_Select("comments","*","comment_id= ".intval($id)." LIMIT 1"))
		{
			$row = $sql->db_Fetch();
			// [comment_id] =&gt; 65

			return $this->form_comment('reply', $row['comment_type'], $row['comment_item_id'], $row['comment_subject'], false, true,false,false,$id);
	
		}			
	}
			
		




	/**
	 * Display the comment editing form
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
	function form_comment($action, $table, $id, $subject, $content_type, $return = FALSE, $rating = FALSE, $tablerender = TRUE,$pid = false)
	{
		//rating	: boolean, to show rating system in comment
		
		$pref	= e107::getPref();
		$sql	= e107::getDb();
		$tp	= e107::getParser();
				
		if(vartrue($pref['comments_disabled']))
		{
			return;
		}

	// 	require_once(e_HANDLER."ren_help.php");
	
	
		if ($this->getCommentPermissions() == 'rw')
		{
			$itemid = $id;
			$ns = new e107table;
			if ($action == "reply" && substr($subject, 0, 4) != "Re: ")
			{
				$subject = COMLAN_325.' '.$subject;
			}
			//FIXME - e_REQUEST_URI?
			//e_SELF."?".e_QUERY
			
			if (vartrue($_GET['comment']) == 'edit')
			{
				$eaction = 'edit';
				$id = $_GET['comment_id'];
			}
			elseif (strpos(e_QUERY, 'edit.') !== FALSE)
			{
				$eaction = 'edit';
				$tmp = explode(".", e_QUERY);
				$count = 0;

				foreach ($tmp as $t)
				{
					if ($t == "edit")
					{
						$id = $tmp[($count + 1)];
						break;
					}
					$count++;
				}
			}
			if (isset($eaction) && $eaction == "edit")
			{ // Get existing comment
				$id = intval($id);
				$sql->db_Select("comments", "*", "comment_id='{$id}' ");
				$ecom = $sql->db_Fetch();
				if (isset($ecom['comment_author']))
				{ // Old comment DB format
					list($prid, $pname) = explode(".", $ecom['comment_author'], 2);
				}
				else
				{
					$prid = $ecom['comment_author_id'];
					$pname = $ecom['comment_author_name'];
				}
				if ($prid != USERID || !USER)
				{ // Editing not allowed
					echo "<div style='text-align: center;'>".COMLAN_329."</div>";
					require_once(FOOTERF);
					exit;
				}
				$caption = COMLAN_318;
				$comval = $tp->toForm($ecom['comment_comment']);
				$comval = preg_replace("#\[ ".COMLAN_319.".*\]#si", "", $comval);
			}
			else
			{ // New comment - blank form
				$caption = COMLAN_9;
				$comval = "";
			}

			//add the rating select box/result ?
			/*
			$rate = "";
			if ($rating == TRUE && !(ANON == TRUE && USER == FALSE))
			{
				global $rater;
				require_once(e_HANDLER."rate_class.php");
				if (!is_object($rater))
				{
					$rater = new rater;
				}
				$rate = $rater->composerating($table, $itemid, $enter = TRUE, USERID, TRUE);
				
			
			} //end rating area
			*/
			
			// -------------------------------------------------------------
			
			$indent = ($action == 'reply') ? "style='margin-left:40px'" : "";
			$formid = ($action == 'reply') ? "e-comment-form-reply" : "e-comment-form";
			
			$text = "\n<div id='{$formid}' {$indent}>\n".e107::getMessage()->render('postcomment', true, false, false);//temporary here
			$text .= "<form id='{$formid}' method='post' action='".str_replace('http:', '', $_SERVER['REQUEST_URI'])."'  >";	
					
			$data = array(
				'action'	=> $action,
				'subject' 	=> $subject,
				'table'		=> $table,
				'comval'	=> strip_tags(trim($comval)),
				'itemid'	=> $itemid,
				'pid'		=> $pid,
				'eaction'	=> varset($eaction),
				'rate'		=> $rating
			);
			
			e107::getScBatch('comment')->setParserVars($data);
			
			e107::getScBatch('comment')->setMode('edit');
	
			$text .= $tp->parseTemplate($this->template['FORM'], TRUE, e107::getScBatch('comment'));
			
			$text .= "\n<div>\n"; // All Hidden Elements. 
			
			$text .= (varset($action) == "reply" && $pid ? "<input type='hidden' name='pid' value='{$pid}' />" : '');
			$text .=(isset($eaction) && $eaction == "edit" ? "<input type='hidden' name='editpid' value='{$id}' />" : "");
			$text .=(isset($content_type) && $content_type ? "<input type='hidden' name='content_type' value='{$content_type}' />" : '');
		//	$text .= (!$pref['nested_comments']) ? "<input type='hidden' name='subject' value='".$tp->toForm($subject)."'  />\n" : "";
	
			$text .= "
			<input type='hidden' name='subject' value='".$tp->toForm($subject)."'  />
			<input type='hidden' name='e-token' value='".e_TOKEN."' />\n
			<input type='hidden' name='table' value='".$table."' />\n
			<input type='hidden' name='itemid' value='".$itemid."' />\n
			</div>
			</form>\n";
			
			$text .= "</div>";
			
			if ($tablerender)
			{
				$text = $ns->tablerender($caption, $text, '', TRUE);
			}
		}
		else
		{ // Comment entry not allowed - point to signup link
			$text = "<br /><div style='text-align:center'><b>".COMLAN_6." <a href='".e_SIGNUP."'>".COMLAN_321."</a> ".COMLAN_322."</b></div>";
		}
		if ($return)
		{
			return $text;
		}
		else
		{
			echo $text;
		}
	}

	/**
	 * Check if comment is pending approval. 
	 * @param array - a row from the comments table. 
	 * @return boolean True/False
	 */
	private function isPending($row)
	{
		if($row['comment_blocked'] > 0 && ($row['comment_author_id'] != USERID || ($row['comment_author_id']==0 && $row['comment_author_name'] != $_SESSION['comment_author_name'])) && $this->moderator == false)
		{
			$this->totalComments = $this->totalComments - 1;
			return true;
		}
		
		return false;		
	}
		
		
	
	
	/**
	 * Render a single comment and any nested comments it may have. 
	 *
	 * @param array $row
	 * @param string $table
	 * @param string $action
	 * @param integer $id
	 * @param interger $width
	 * @param string $subject
	 * @param integer $addrating
	 * @return html
	 */
	function render_comment($row, $table, $action, $id, $width, $subject, $addrating = FALSE)
	{
		//addrating	: boolean, to show rating system in rendered comment
		global $sc_style, $gen;
			
		$tp 	= e107::getParser();
		$sql 	= e107::getDb();
		$pref 	= e107::getPref();
		
		if (vartrue($pref['comments_disabled']))
		{
			return;
		}
				
		global $NEWIMAGE, $USERNAME, $RATING, $datestamp;
		global $thisaction,$thistable,$thisid,$e107;
				
		$comrow 		= $row;			
		$thistable 		= $table;
		$thisid 		= $id;
		$thisaction 	= $action;

		//FIXME - new level handler, currently commented to avoid parse errors
		//require_once (e_HANDLER."level_handler.php");
		
		if (!$width)
		{
			$width = 0;
		}
		if (!defined("IMAGE_nonew_comments"))
		{
			define("IMAGE_nonew_comments", (file_exists(THEME."images/nonew_comments.png") ? "<img src='".THEME_ABS."images/nonew_comments.png' alt=''  /> " : "<img src='".e_IMAGE_ABS."generic/nonew_comments.png' alt=''  />"));
		}
		if (!defined("IMAGE_new_comments"))
		{
			define("IMAGE_new_comments", (file_exists(THEME."images/new_comments.png") ? "<img src='".THEME_ABS."images/new_comments.png' alt=''  /> " : "<img src='".e_IMAGE_ABS."generic/new_comments.png' alt=''  /> "));
		}
		
//		$ns = new e107table;
		
		if (!$gen || !is_object($gen))
		{
			$gen = new convert;
		}	
		
		$row['rating_enabled'] = true; // Toggles rating shortcode. //TODO add pref
		
		e107::getScBatch('comment')->setParserVars($row);
		
		$COMMENT_TEMPLATE 					= $this->template; 
		
		$COMMENT_TEMPLATE['ITEM_START'] 	= "\n\n<div id='{COMMENT_ITEMID}' class='comment-box clearfix'>\n";
		$COMMENT_TEMPLATE['ITEM_END']		= "\n</div><div class='clear_b'><!-- --></div>\n";
			
		if (vartrue($pref['nested_comments']))
		{
			$width2 = 100 - $width;
			$total_width = "95%";
			if ($width)
			{		
				$renderstyle = $COMMENT_TEMPLATE['ITEM_START'];
				$renderstyle .= "<div style='margin-left:{$width}%'>".$COMMENT_TEMPLATE['ITEM']."</div>";	
				$renderstyle .= $COMMENT_TEMPLATE['ITEM_END'];					
			}
			else
			{
					
				$renderstyle = $COMMENT_TEMPLATE['ITEM_START'].$COMMENT_TEMPLATE['ITEM'].$COMMENT_TEMPLATE['ITEM_END'];

			}
			if ($pref['comments_icon'])
			{
				if ($comrow['comment_datestamp'] > USERLV)
				{
					$NEWIMAGE = IMAGE_new_comments;
				}
				else
				{
					$NEWIMAGE = IMAGE_nonew_comments;
				}
			}
			else
			{
				$NEWIMAGE = "";
			}
		}
		else
		{
			$renderstyle = $COMMENT_TEMPLATE['ITEM'];
		}
		$highlight_search = FALSE;
		
		
		if (isset($_POST['highlight_search']))
		{
			$highlight_search = TRUE;
		}
		
		if (!defined("IMAGE_rank_main_admin_image"))
		{
			define("IMAGE_rank_main_admin_image", (isset($pref['rank_main_admin_image']) && $pref['rank_main_admin_image'] && file_exists(THEME."forum/".$pref['rank_main_admin_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_main_admin_image']."' alt='' />" : "<img src='".e_PLUGIN_ABS."forum/images/lite/main_admin.png' alt='' />"));
		}
		if (!defined("IMAGE_rank_moderator_image"))
		{
			define("IMAGE_rank_moderator_image", (isset($pref['rank_moderator_image']) && $pref['rank_moderator_image'] && file_exists(THEME."forum/".$pref['rank_moderator_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_moderator_image']."' alt='' />" : "<img src='".e_PLUGIN_ABS."forum/images/lite/admin.png' alt='' />"));
		}
		if (!defined("IMAGE_rank_admin_image"))
		{
			define("IMAGE_rank_admin_image", (isset($pref['rank_admin_image']) && $pref['rank_admin_image'] && file_exists(THEME."forum/".$pref['rank_admin_image']) ? "<img src='".THEME_ABS."forum/".$pref['rank_admin_image']."' alt='' />" : "<img src='".e_PLUGIN_ABS."forum/images/lite/admin.png' alt='' />"));
		}
		
	//	$RATING = ($addrating == TRUE && $comrow['user_id'] ? $rater->composerating($thistable, $thisid, FALSE, $comrow['user_id']) : "");
		
		$comment_shortcodes = e107::getScBatch('comment');
		
		$text = $tp->parseTemplate($renderstyle, TRUE, $comment_shortcodes);
			
		//FIXME - dramatically increases the number of queries performed. 
		
		if ($action == "comment" && vartrue($pref['nested_comments']))
		{
			$type = $this->getCommentType($thistable);
			$sub_query = "
			SELECT c.*, u.*, ue.*, r.*
			FROM #comments AS c
			LEFT JOIN #user AS u ON c.comment_author_id = u.user_id
			LEFT JOIN #user_extended AS ue ON c.comment_author_id = ue.user_extended_id
			LEFT JOIN #rate AS r ON c.comment_id = r.rate_itemid AND r.rate_table = 'comments' 
			
			WHERE comment_item_id='".intval($thisid)."' AND comment_type='".$tp->toDB($type, true)."' AND comment_pid='".intval($comrow['comment_id'])."'
			
			
			ORDER BY comment_datestamp
			";
			$sql_nc = new db; /* a new db must be created here, for nested comment  */
			if ($sub_total = $sql_nc->db_Select_gen($sub_query))
			{
				while ($row1 = $sql_nc->db_Fetch())
				{
					
					if($this->isPending($row1))
					{
						$sub_total = $sub_total - 1;
				 		continue;	
					}	
					
					
					if ($pref['nested_comments'])
					{
						$width = min($width + 3, 80);
					}
					$text .= $this->render_comment($row1, $table, $action, $id, $width, $subject, $addrating);
					unset($width);
				}
			}
			
			$this->totalComments = $this->totalComments + $sub_total;
		} // End (nested comment handling)
		
	
		
		return $text;
	}

	
	function deleteComment($id) // delete a single comment by comment id.  
	{
		if(!getperms('0') && !getperms("B"))
		{
			return;	
		}
		return e107::getDb()->db_Delete("comments","comment_id = ".intval($id)." LIMIT 1");	
	}
	
	function approveComment($id) // appropve a single comment by comment id.  
	{
		if(!getperms('0') && !getperms("B"))
		{
			return;	
		}
		
		return e107::getDb()->db_Update("comments","comment_blocked=0 WHERE comment_id = ".intval($id)." LIMIT 1");
	}

	
	function updateComment($id,$comment)
	{
		$tp = e107::getParser();
		
		if(!e107::getDb()->db_Update("comments","comment_comment=\"".$tp->toDB($comment)."\" WHERE comment_id = ".intval($id)." LIMIT 1"))
		{
			return "Update Failed"; // trigger ajax error message. 
		}		
	}
	
	
	function moderateComment($var)
	{	
		if ($var == e_UC_MEMBER) // different behavior to check_class();
		{
			return (USER == TRUE && ADMIN == FALSE) ? TRUE : FALSE;
		}
		
		return check_class($var);
	}
			
		
	
	
	
	
	/**
	 * Add a comment to an item
	 * e-token POST value should be always valid when using this method.
	 *
	 * @param string or array $data - $author_name or array of all values. 
	 * @param unknown_type $comment
	 * @param unknown_type $table
	 * @param integer $id - reference of item in source table to which comment is linked
	 * @param unknown_type $pid - parent comment id when it's a reply to a specific comment. t
	 * @param unknown_type $subject
	 * @param unknown_type $rateindex
	 */


	function enter_comment($data, $comment='', $table='', $id='', $pid='', $subject='', $rateindex = FALSE)
	{
		//rateindex	: the posted value from the rateselect box (without the urljump) (see function rateselect())
		
		if(is_array($data))
		{
			$table 				= $data['comment_type'];
			$id					= intval($data['comment_item_id']);
			$pid				= intval($data['comment_pid']);
			$subject			= $data['comment_subject'];
			$comment			= $data['comment_comment'];
			$author_name		= $data['comment_author_name'];
			$comment_share		= intval($data['comment_share']);
			$comment_datestamp	= $data['comment_datestamp'];	
		}
		else
		{
			$author_name = $data; //BC Fix. 	
		}
		
		
		global $e_event,$e107,$rater;

		$sql 		= e107::getDb();
		$sql2 		= e107::getDb('sql2');
		$tp 		= e107::getParser();
		$e107cache 	= e107::getCache();
		$pref 		= e107::getPref();

		if ($this->getCommentPermissions() != 'rw') return;

		if(!isset($_POST['e-token'])) $_POST['e-token'] = '';		// check posted token
		if(!e107::getSession()->check(false)) return false;			// This will return false on error

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
			foreach ($tmp as $t)
			{
				if ($t == "edit")
				{
					$editpid = $tmp[($count + 1)];
					break;
				}
				$count++;
			}
		}
		$type = $this->getCommentType($table);
		$comment = $tp->toDB($comment);
		$subject = $tp->toDB($subject);
		$cuser_id = 0;
		$cuser_name = 'Anonymous'; // Preset as an anonymous comment
		
		if (!$sql->db_Select("comments", "*", "comment_comment='".$comment."' AND comment_item_id='".intval($id)."' AND comment_type='".$tp->toDB($type, true)."' "))
		{
			if ($_POST['comment'])
			{
				if (USER == TRUE)
				{
					$cuser_id = USERID;
					$cuser_name = USERNAME;
					$cuser_mail = USEREMAIL;
				}
				elseif ($_POST['author_name'] != '') // See if author name is registered user
				{ 
					if ($sql2->db_Select("user", "*", "user_name='".$tp->toDB($_POST['author_name'])."' "))
					{
						if ($sql2->db_Select("user", "*", "user_name='".$tp->toDB($_POST['author_name'])."' AND user_ip='".$tp->toDB($ip, true)."' "))
						{
							//list($cuser_id, $cuser_name) = $sql2->db_Fetch();
							$tmp = $sql2->db_Fetch();
							$cuser_id = $tmp['user_id'];
							$cuser_name = $tmp['user_name'];
							$cuser_mail = $tmp['user_email'];
						}
						else
						{
							define("emessage", COMLAN_310);
						}
					}
					else // User not on-line, so can't be entering comments
					{ 
						$cuser_name = $tp->toDB($author_name);
					}
				}
				if (!defined("emessage"))
				{
					$ip = $e107->getip(); // Store IP 'in the raw' - could be IPv4 or IPv6. Its always returned in a normalised form
					$_t = time();
					if ($editpid)
					{
						$comment .= "\n[ ".COMLAN_319." [time=short]".time()."[/time] ]";
						$sql->db_Update("comments", "comment_comment='{$comment}' WHERE comment_id='".intval($editpid)."' ");
						$e107cache->clear("comment");
						return;
					}

					//FIXME - don't sanitize, pass raw data to e_event, use DB array (inner db sanitize)
					$edata_li = array(
						// comment_id - auto-assigned
						'comment_pid'			=> intval($pid),
						'comment_item_id'		=> $id,
						'comment_subject'		=> $subject,
						'comment_author_id'		=> $cuser_id,
						'comment_author_name'	=> $cuser_name,
						'comment_author_email'	=> $tp->toDB($cuser_mail),
						'comment_datestamp'		=> $_t,
						'comment_comment'		=> $comment,
						'comment_blocked'		=> ($this->moderateComment($pref['comments_moderate']) ? 2 : 0), 
						'comment_ip'			=> $ip,
						'comment_type'			=> $tp->toDB($type, true),
						'comment_lock'			=> 0,//Not locked by default
						'comment_share'			=> $comment_share
					);

					//SecretR: new event 'prepostcomment' - allow plugin hooks - e.g. Spam Check
					$edata_li_hook = array_merge($edata_li, array('comment_nick' => $cuser_id.'.'.$cuser_name, 'comment_time' => $_t));
					if($e_event->trigger("prepostcomment", $edata_li_hook))
					{
						return false; //3rd party code interception
					}

					//allow 3rd party code to modify insert data
					if(is_array($edata_li_hook))
					{
						foreach (array_keys($edata_li) as $k)
						{
							if(isset($edata_li_hook[$k]))
							{
								$edata_li[$k] = $edata_li_hook[$k]; //sanitize?
								continue;
							}
							if($k === 'break')
							{
								$break = $edata_li_hook[$k];
							}
						}
					}
					unset($edata_li_hook);

					if (!($inserted_id = $sql->db_Insert("comments", $edata_li)))
					{
						//echo "<b>".COMLAN_323."</b> ".COMLAN_11;
						if(e_AJAX_REQUEST)
						{
							return "Error";	
						}
						e107::getMessage()->addStack(COMLAN_11, 'postcomment', E_MESSAGE_ERROR);

					}
					else
					{
						if (USER == TRUE)
						{
							$sql->db_Update("user", "user_comments=user_comments+1, user_lastpost='".time()."' WHERE user_id='".USERID."' ");
						}
						// Next item for backward compatibility
						$edata_li["comment_nick"] = $cuser_id.'.'.$cuser_name;
						$edata_li["comment_time"] = $_t;
						$edata_li["comment_id"] = $inserted_id;

						//Why?
						/*unset($edata_li['comment_pid']);
						unset($edata_li['comment_author_email']);
						unset($edata_li['comment_ip']);*/

						$e_event->trigger("postcomment", $edata_li);
						$e107cache->clear("comment");

						//TODO - should be handled by news
						if (!$type || $type == "news")
						{
							$sql->db_Update("news", "news_comment_total=news_comment_total+1 WHERE news_id=".intval($id));
						}

						//if rateindex is posted, enter the rating from this user
					//	if ($rateindex)
					//	{
					//		$rater->enterrating($rateindex);
					//	}
						return $inserted_id; // return the ID number so it can be used. true;
					}
				}
			}
		}
		else
		{
			define("emessage", COMLAN_312);
		}

		if (defined("emessage"))
		{
			if(e_AJAX_REQUEST)
			{
				return emessage;	
			}
			
			
			message_handler("ALERT", emessage);
		}
		return false;
	}

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $table
	 * @return unknown
	 */
	function getCommentType($table)
	{
		if (is_numeric($table))
		{
			return $table;
		}

		switch ($table)
		{
			case "news":
				$type = 0;
				break;
			case "content":
				$type = 1;
				break;
			case "download":
				$type = 2;
				break;
			case "faq":
				$type = 3;
				break;
			case "poll":
				$type = 4;
				break;
			case "docs":
				$type = 5;
				break;
			case "bugtrack":
				$type = 6;
				break;
			default :
				$type = $table;
				break;
				/****************************************
				Add your comment type here in same format as above, ie ...
				case "your_comment_type"; $type = your_type_id; break;
				****************************************/
		}
		return $type;
	}

	/**
	 * Convert type number to (core) table string
	 * @param integer|string $type
	 * @return string
	 */
	public function getTable($type)
	{
		if (!is_numeric($type))
		{
			return $type;
		}
		else
		{
			if(varset($this->known_types[$type]))
			{
				return $this->known_types[$type];
			}
		}
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
		$type = $this->getCommentType($table);
		$count_comments = $sql->db_Count("comments", "(*)", "WHERE comment_item_id='".intval($id)."' AND comment_type='".$tp->toDB($type, true)."' ");
		return $count_comments;
	}

	/**
	 *	Get comment permissions; may be:
	 *		- FALSE - no permission
	 *		- 'ro' - read-only (Can't create)
	 *		- 'rw' - can create and see
	 *
	 *	This is an embryonic routine which is expected to evolve
	 */
	function getCommentPermissions()
	{
		global $pref;

		if(isset($pref['comments_disabled']) && $pref['comments_disabled'] == TRUE)
		{
			
        	return FALSE;
		}
		if (isset($pref['comments_class']))
		{
			if (!check_class($pref['comments_class']))
			{
				return FALSE;
			}
			return 'rw';
		}
		else
		{
			if (USER) return 'rw';			// Only allow anonymous comments if specifically enabled.
			if (ANON) return 'rw';
		}
		return 'ro';
	}


	/**
	 * Displays existing comments, and a comment entry form
	 *
	 * @param unknown_type $table - the source table for the associated item
	 * @param unknown_type $action - usually 'comment' or 'reply'
	 * @param unknown_type $id - ID of item associated with comments (e.g. news ID)
	 * @param unknown_type $width - appears to not be used
	 * @param unknown_type $subject
	 * @param unknown_type $rate
	 */
	function compose_comment($table, $action, $id, $width, $subject, $rate = FALSE, $return = FALSE, $tablerender = TRUE)
	{
		//compose comment	: single call function will render the existing comments and show the form_comment
		//rate				: boolean, to show/hide rating system in comment, default FALSE
		global $e107cache, $totcc;
		
	
		
		$tp = e107::getParser();
		$ns = e107::getRender();
		$pref = e107::getPref();
		
		
		if ($this->getCommentPermissions() === FALSE) return;


// ------------- TODO move the 'listing' into separate function so that ajax can access it easily. 

		$options = array(
			'action'	=> $action,
			'subject'	=> $subject,
			'rate'		=> $rate
		);
		$text = $lock = $modcomment ='';
		
		if($action != 'reply')
		{
			$tmp = $this->getComments($table,$id,0,$options); // render all comments;
			$text = $tmp['comments'];
			$lock = $tmp['lock'];
			unset($tmp);
		}
		// -------------------------------------------------------
		
		if($text)
		{
			if($this->totalComments && getperms("B"))
			{
					$modcomment = "<div class='comment-moderate'>";		
				//	$modcomment .= "<a href='".e_ADMIN_ABS."modcomment.php?$table.$id'>".COMLAN_314."</a>";
					$modcomment .= "<a href='".e_ADMIN_ABS."comment.php?searchquery={$id}&filter_options=comment_type__".$this->getCommentType($table)."'>".COMLAN_314."</a>";		
					$modcomment .= "</div>";
			}
			
			$modcomment .= 	$this->nextprev($table,$id,$from);
		}	
	// ---------------------------
		
		if ($lock != '1')
		{
			$comment = $this->form_comment($action, $table, $id, $subject, "", TRUE, $rate, false); // tablerender turned off. 
		}
		else
		{
			$comment = "<br /><div style='text-align:center'><b>".COMLAN_328."</b></div>";
		}
		
		if($text)
		{
			$text = "<div id='comments-container'>\n".$text."\n</div>";
		}
		$search = array("{MODERATE}","{COMMENTS}","{COMMENTFORM}","{COMMENTNAV}");
		$replace = array($modcomment,$text,$comment,$pagination);
		$TEMPL = str_replace($search,$replace,$this->template['LAYOUT']);		
			
		if(!$return)
		{		
			if ($tablerender)
			{
					
					echo $ns->tablerender("<span id='e-comment-total'>".$this->totalComments."</span> ".COMLAN_99, $TEMPL, 'comment', TRUE);	
			}
			else
			{
				echo $TEMPL;	
			}
		}		
			//echo $modcomment.$comment;
			//echo $text;
		
		
		

		$ret['comment'] = $text;
		
		$ret['comment_form'] = $comment;
		$ret['caption'] = "<span id='e-comment-total'>".$this->totalComments."</span> ".COMLAN_99;

		return (!$return) ? "" : $ret;
	}




	
	function getComments($table,$id,$from=0,$att=null)
	{
		// global $e107cache, $totcc;
		
		//TODO Cache 
		
		$sql 		= e107::getDb();
		$tp 		= e107::getParser();
		$pref 		= e107::getPref();
		
		$action 	= varset($att['action']);
		$subject 	= varset($att['subject']);
		$rate		= varset($att['rate']);
			
		$type = $this->getCommentType($table);
		$sort = vartrue($pref['comments_sort'],'desc');
		
		if(vartrue($pref['nested_comments']))
		{
			$query = "SELECT c.*, u.*, ue.*, r.* FROM #comments AS c
			LEFT JOIN #user AS u ON c.comment_author_id = u.user_id
			LEFT JOIN #user_extended AS ue ON c.comment_author_id = ue.user_extended_id 
			LEFT JOIN #rate AS r ON c.comment_id = r.rate_itemid AND r.rate_table = 'comments' 
			
			WHERE c.comment_item_id='".intval($id)."' AND c.comment_type='".$tp->toDB($type, true)."' AND c.comment_pid='0' 
			ORDER BY c.comment_datestamp ".$sort;
		}
		else
		{
			$query = "SELECT c.*, u.*, ue.*, r.* FROM #comments AS c
			LEFT JOIN #user AS u ON c.comment_author_id = u.user_id
			LEFT JOIN #user_extended AS ue ON c.comment_author_id = ue.user_extended_id 		
			LEFT JOIN #rate AS r ON c.comment_id = r.rate_itemid AND r.rate_table = 'comments' 	";			
			$query .= "WHERE c.comment_item_id='".intval($id)."' AND c.comment_type='".$tp->toDB($type, true)."' 		
			ORDER BY c.comment_datestamp ".$sort;
		}
		
		$this->totalComments = $sql->db_Select_gen($query);
			
		$query .= " LIMIT ".$from.",".$this->commentsPerPage;
		
		$text 			= "";
		$lock 			= '';

		if ($sql->db_Select_gen($query))
		{			
			$width = 0; 			
			$rows = $sql->db_getList(); //Shortcodes could use $sql, so just grab all results

			foreach ($rows as $row)
			{
				
				if($this->isPending($row))
				{
				 	continue;	
				}					
									
				$lock = $row['comment_lock'];
	
				if ($pref['nested_comments'])
				{
					$text .= $this->render_comment($row, $table, $action, $id, $width, $tp->toHTML($subject), $rate);
				}
				else
				{
					$text .= $this->render_comment($row, $table, $action, $id, $width, $tp->toHTML($subject), $rate);
				}
			} // end loop
			
		} // end if
					
		return array('comments'=> $text,'lock'=> $lock);		
	}



	function nextprev($table,$id,$from=0)
	{
		//return "table=".$table."  id=".$id."  from=".$from;
		//$from = $from + $this->commentsPerPage;
		
		
		// from calculations are done by eNav() js. 
		return "
		<a class='e-ajax' href='#' data-nav-total='{$this->totalComments}' data-nav-dir='down' data-nav-inc='{$this->commentsPerPage}' data-target='comments-container' data-src='".e_BASE."comment.php?mode=list&amp;type=".$table."&amp;id=".$id."&amp;from=0'>Previous</a>
		
		<a class='e-ajax' href='#' data-nav-total='{$this->totalComments}' data-nav-dir='up' data-nav-inc='{$this->commentsPerPage}' data-target='comments-container' data-src='".e_BASE."comment.php?mode=list&amp;type=".$table."&amp;id=".$id."&amp;from=0'>Next</a>
		
		";	
		
		
	}







	function recalc_user_comments($id)
	{
		global $sql;
			if (is_array($id))
			{
				foreach ($id as $_id)
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
			if ($sql->db_Select_gen($qry))
			{
				$row = $sql->db_Fetch();
				$sql->db_Update("user", "user_comments = '{$row['count']}' WHERE user_id = '{$id}'");
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
			if ($sql->db_Select_gen($qry))
			{
				while ($row = $sql->db_Fetch())
				{
					$authors[] = $row['author'];
				}
			}
			return $authors;
		}


		function delete_comments($table, $id)
		{
			global $sql,$tp;
			$type = $this->getCommentType($table);
			$type = $tp->toDB($type, true);
			$id = intval($id);
			$author_list = $this->get_author_list($id, $type);
			$num_deleted = $sql->db_Delete("comments", "comment_item_id='{$id}' AND comment_type='{$type}'");
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
			if ($data !== FALSE)
			{
				return $data;
			}

			$files = e107::getPref('e_comment_list');

			foreach ($files as $file=>$perms)
			{
				unset($e_comment, $key);
				include (e_PLUGIN.$file."/e_comment.php");
				if ($e_comment && is_array($e_comment))
				{
					$key = $e_comment['eplug_comment_ids'];
					if (isset($key) && $key != '')
					{
						$data[$key] = $e_comment;
					}
				}
				else
				{
					//convert old method variables into the same array method
					$key = $e_plug_table;
					if (isset($key) && $key != '')
					{
						$e_comment['eplug_comment_ids'] = $e_plug_table;
						$e_comment['plugin_name'] = $plugin_name;
						$e_comment['plugin_path'] = $plugin_path;
						$e_comment['reply_location'] = $reply_location;
						$e_comment['db_title'] = $link_name;
						$e_comment['db_id'] = $db_id;
						$e_comment['db_table'] = $db_table;
						$e_comment['qry'] = '';
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


		function getCommentData($amount = '', $from = '', $qry = '', $cdvalid = FALSE, $cdreta = FALSE)
		{
			global $pref,$sql,$sql2,$tp;
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
		WHERE c.comment_id!='' AND c.comment_blocked = 0 ".$qry1." ORDER BY c.comment_datestamp DESC LIMIT ".intval($from1).",".intval($amount1)." ";
			if ($comment_total = $sql->db_Select_gen($query))
			{
				$width = 0;
				while ($row = $sql->db_Fetch())
				{
					$ret = array();
					//date
					$ret['comment_datestamp'] = $row['comment_datestamp'];
					//author - no ned to split now
					$comment_author_id = $row['comment_author_id'];
					$ret['comment_author_id'] = $comment_author_id ;
					$comment_author_name = $row['comment_author_name'];
					$ret['comment_author'] = (USERID ? "<a href='".e107::getUrl()->create('user/profile/view', array('id' => $comment_author_id, 'name' => $comment_author_name))."'>".$comment_author_name."</a>" : $comment_author_name);
					//comment text
					$comment = strip_tags(preg_replace("/\[.*?\]/", "", $row['comment_comment'])); // remove bbcode - but leave text in between
					$ret['comment_comment'] = $tp->toHTML($comment, FALSE, "", "", $pref['main_wordwrap']);
					//subject
					$ret['comment_subject'] = $tp->toHTML($row['comment_subject'], TRUE);
					switch ($row['comment_type'])
					{
						case '0': // news
							if ($sql2->db_Select("news", "*", "news_id='".$row['comment_item_id']."' AND news_class REGEXP '".e_CLASS_REGEXP."' "))
							{
								$row2 = $sql2->db_Fetch();
								require_once(e_HANDLER.'news_class.php');
								$ret['comment_type'] = COMLAN_TYPE_1;
								$ret['comment_title'] = $tp->toHTML($row2['news_title'], TRUE, 'emotes_off, no_make_clickable');
								$ret['comment_url'] = e107::getUrl()->create('news/view/item', $row2);//e_HTTP."comment.php?comment.news.".$row['comment_item_id'];
								$ret['comment_category_heading'] = COMLAN_TYPE_1;
								$ret['comment_category_url'] = e107::getUrl()->create('news');//e_HTTP."news.php";
							}
							break;
						case '1': //	article, review or content page - defunct category, but filter them out
							break;
						case '2': //	downloads
							$qryd = "SELECT d.download_name, dc.download_category_class, dc.download_category_id, dc.download_category_name FROM #download AS d LEFT JOIN #download_category AS dc ON d.download_category=dc.download_category_id WHERE d.download_id={$row['comment_item_id']} AND dc.download_category_class REGEXP '".e_CLASS_REGEXP."' ";
							if ($sql2->db_Select_gen($qryd))
							{
								$row2 = $sql2->db_Fetch();
								$ret['comment_type'] = COMLAN_TYPE_2;
								$ret['comment_title'] = $tp->toHTML($row2['download_name'], TRUE, 'emotes_off, no_make_clickable');
								$ret['comment_url'] = e_HTTP."download.php?view.".$row['comment_item_id'];
								$ret['comment_category_heading'] = $row2['download_category_name'];
								$ret['comment_category_url'] = e_HTTP."download.php?list.".$row2['download_category_id'];
							}
							break;
						// '3' was FAQ
						case '4': //	poll
							if ($sql2->db_Select("polls", "*", "poll_id='".$row['comment_item_id']."' "))
							{
								$row2 = $sql2->db_Fetch();
								$ret['comment_type'] = COMLAN_TYPE_4;
								$ret['comment_title'] = $tp->toHTML($row2['poll_title'], TRUE, 'emotes_off, no_make_clickable');
								$ret['comment_url'] = e_HTTP."comment.php?comment.poll.".$row['comment_item_id'];
								$ret['comment_category_url'] = e_PLUGIN_ABS.'poll/poll.php';
							}
							break;
						// '5' was docs
						// '6' was bugtracker
						// 'ideas' was implemented
						case 'profile': //	userprofile
							if (USER)
							{
								$ret['comment_type'] = COMLAN_TYPE_8;
								$ret['comment_title'] = $comment_author_name;
								$ret['comment_url'] = e107::getUrl()->create('user/pofile/view', array('id' => $row['user_id'], 'name' => $row['user_name']));//e_HTTP."user.php?id.".$row['comment_item_id'];
							}
							break;
						case 'page': //	Custom Page
							$ret['comment_type'] = COMLAN_TYPE_PAGE;
							$ret['comment_title'] = $ret['comment_subject'] ? $ret['comment_subject']:
								$ret['comment_comment'];
								$ret['comment_url'] = e_HTTP."page.php?".$row['comment_item_id'];
								break;
							default:
								if (isset($e_comment[$row['comment_type']]) && is_array($e_comment[$row['comment_type']]))
								{
									$var = $e_comment[$row['comment_type']];
									$qryp = '';
									//new method must use the 'qry' variable
									if (isset($var) && $var['qry'] != '')
									{
										if ($installed = isset($pref['plug_installed'][$var['plugin_path']]))
										{
											$qryp = str_replace("{NID}", $row['comment_item_id'], $var['qry']);
											if ($sql2->db_Select_gen($qryp))
											{
												$row2 = $sql2->db_Fetch();
												$ret['comment_type'] = $var['plugin_name'];
												$ret['comment_title'] = $tp->toHTML($row2[$var['db_title']], TRUE, 'emotes_off, no_make_clickable');
												$ret['comment_url'] = str_replace("{NID}", $row['comment_item_id'], $var['reply_location']);
												$ret['comment_category_heading'] = $var['plugin_name'];
												$ret['comment_category_url'] = e_PLUGIN_ABS.$var['plugin_name'].'/'.$var['plugin_name'].'.php';
											}
										}
										//old method
									}
									else
									{
										if ($sql2->db_Select($var['db_table'], $var['db_title'], $var['db_id']." = '".$row['comment_item_id']."' "))
										{
											$row2 = $sql2->db_Fetch();
											$ret['comment_type'] = $var['plugin_name'];
											$ret['comment_title'] = $tp->toHTML($row2[$var['db_title']], TRUE, 'emotes_off, no_make_clickable');
											$ret['comment_url'] = str_replace("{NID}", $row['comment_item_id'], $var['reply_location']);
											$ret['comment_category_heading'] = $var['plugin_name'];
											$ret['comment_category_url'] = e_PLUGIN_ABS.$var['plugin_name'].'/'.$var['plugin_name'].'.php';
										}
									}
								}
						} // End Switch
				if (varset($ret['comment_title']))
				{
					$reta[] = $ret;
					$valid++;
				}
				if ($amount && $valid >= $amount)
				{
					return $reta;
				}
			}
			//loop if less records found than given $amount - probably because we discarded some
			if ($amount && ($valid < $amount))
			{
				$reta = $this->getCommentData($amount, $from + $amount, $qry, $valid, $reta);
			}
		}
		return $reta;
	}
} //end class
