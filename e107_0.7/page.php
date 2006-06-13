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
|     $Source: /cvs_backup/e107_0.7/page.php,v $
|     $Revision: 1.28 $
|     $Date: 2006-06-13 16:58:55 $ - mods to make password protected pages work
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

require_once("class2.php");
$page = new pageClass();
if(isset($_POST['enterpw']))
{
	$page -> setPageCookie();
}

if(!e_QUERY)
{
	require_once(HEADERF);
	$tmp = $page -> listPages();
	if(is_array($tmp))
	{
		$ns -> tablerender($tmp['title'], $tmp['text']);
		require_once(FOOTERF);
		exit;
	}
}
else
{

	$cacheString = 'page';
	$cachePageTitle = 'page-t';

	if($cacheData = $e107cache->retrieve($cacheString))
	{

		list($pagetitle,$comment_flag) = explode("^",$e107cache->retrieve($cachePageTitle));
		define("e_PAGETITLE", $pagetitle);
		require_once(HEADERF);
		echo $cacheData;

	}
	else
	{

		$tmp = $page -> showPage();
		define("e_PAGETITLE", $tmp['title']);
		require_once(HEADERF);
		if ($tmp['err'])		// Need to display error block after header defined
		{
			$ns -> tablerender($tmp['title'], $tmp['text']);
			require_once(FOOTERF);
			exit;
		}
		ob_start();
		$ns -> tablerender($tmp['title'], $tmp['text']);
		$cache_data = ob_get_flush();
		$e107cache->set($cacheString, $cache_data);
		$e107cache->set($cachePageTitle, $tmp['title']."^".$tmp['comment_flag']);
		$comment_flag = $tmp['comment_flag'];
	}

	if($com = $page -> pageComment($comment_flag))
	{
		echo $com['comment'].$com['comment_form'];
	}
}

require_once(FOOTERF);

/* EOF */

class pageClass
{

	var $bullet;																/* bullet image */
	var $pageText;														/* main text of selected page, not parsed */
	var $multipageFlag;												/* flag - true if multiple page page, false if not */
	var $pageTitles;														/* array containing page titles */
	var $pageID;															/* id number of page to be displayed */
	var $pageSelected;												/* selected page of multiple page page */
	var $pageToRender;												/* parsed page to be sent to screen */
	var $debug;															/* temp debug flag */
	var $title;																/* title of page, it if has one (as defined in [newpage=title] tag */


	function pageClass($debug=FALSE)
	{
		/* constructor */

		$tmp = explode(".", e_QUERY);
		$this -> pageID = intval($tmp[0]);
		$this -> pageSelected = (isset($tmp[1]) ? intval($tmp[1]) : 0);
		$this -> bullet = (defined("BULLET") ? "<img src='".THEME."images/".BULLET."' alt='' style='vertical-align: middle;' />" : "<img src='".THEME."images/bullet2.gif' alt='bullet' style='vertical-align: middle;' />");
		$this -> debug = $debug;

		if($this -> debug)
		{
			$this -> debug = "<b>PageID</b> ".$this -> pageID." <br />";
			$this -> debug .= "<b>pageSelected</b> ".$this -> pageSelected." <br />";
		}
	}

	function listPages()
	{
		global $pref, $sql, $ns;

		if(!$pref['listPages'])
		{
			message_handler("MESSAGE", LAN_PAGE_1);
		}
		else
		{
			if(!$sql -> db_Select("page", "*", "page_theme='' AND page_class IN (".USERCLASS_LIST.") "))
			{
				$text = LAN_PAGE_2;
			}
			else
			{
				$pageArray = $sql -> db_getList();
				foreach($pageArray as $page)
				{
					extract($page);
					$text .= $this -> bullet." <a href='".e_BASE."page.php?".$page_id."'>".$page_title."</a><br />";
				}
				$ns -> tablerender(LAN_PAGE_11, $text);
			}
		}
	}


	function showPage()
	{
		global $sql, $ns;
		$query = "SELECT p.*, u.user_id, u.user_name FROM #page AS p
		LEFT JOIN #user AS u ON p.page_author = u.user_id
		WHERE p.page_id='".intval($this -> pageID)."' AND p.page_class IN (".USERCLASS_LIST.") ";

		if(!$sql -> db_Select_gen($query) && !$_GET['elan'])
		{
			$ret['title'] = LAN_PAGE_12;			// ***** CHANGED
			$ret['text'] = LAN_PAGE_3;
			$ret['comment_flag'] = '';
			$ret['err'] = TRUE;
			return $ret;
		}

		extract($sql -> db_Fetch());

		$this -> pageText = $page_text;

		$this -> pageCheckPerms($page_class, $page_password);

		if($this -> debug)
		{
			echo "<b>pageText</b> ".$this -> pageText." <br />";
		}

		$this -> parsePage();

		$gen = new convert;

		if($page_author)
		{
			$text = "<div class='smalltext' style='text-align:right'>".$user_name.", ".$gen->convert_date($page_datestamp, "long")."</div><br />";
		}

		if($this -> title)
		{
			$text .= "<b>".$this -> title."</b><br /><br />";
		}

		$text .= $this -> pageToRender;
		$text .= $this -> pageIndex();
		$text .= $this -> pageRating($page_rating_flag);

		$ret['title'] = $page_title;
		$ret['text'] = $text;
		$ret['comment_flag'] = $page_comment_flag;
		$ret['err'] = FALSE;

		return $ret;
	}

	function parsePage()
	{
		global $tp;
		if(preg_match_all("/\[newpage.*?\]/si", $this -> pageText, $pt))
		{
			$pages = preg_split("/\[newpage.*?\]/si", $this -> pageText, -1, PREG_SPLIT_NO_EMPTY);
			$this -> multipageFlag = TRUE;
		}
		else
		{
			$this -> pageToRender = $tp -> toHTML($this -> pageText, TRUE, 'parse_sc, constants');
			return;
		}

		foreach($pt[0] as $title)
		{
			$this -> pageTitles[] = $title;
		}


		if(!trim($pages[0]))
		{
			$count = 0;
			foreach($pages as $page)
			{
				$pages[$count] = $pages[($count+1)];
				$count++;
			}
			unset($pages[(count($pages)-1)]);
		}

		$pageCount = count($pages);
		$titleCount = count($this -> pageTitles);
		/* if the vars above don't match, page 1 has no [newpage] tag, so we need to create one ... */

		if($pageCount != $titleCount)
		{
			array_unshift($this -> pageTitles, "[newpage]");
		}

		/* ok, titles now match pages, rename the titles if needed ... */

		$count =0;
		foreach($this -> pageTitles as $title)
		{
			$titlep = preg_replace("/\[newpage=(.*?)\]/", "\\1", $title);
			$this -> pageTitles[$count] = ($titlep == "[newpage]" ? "Page ".($count+1)."&nbsp;" : $tp -> toHTML($titlep, TRUE, 'parse_sc, constants'));
			$count++;
		}

		$this -> pageToRender = $tp -> toHTML($pages[$this -> pageSelected], TRUE, 'parse_sc, constants');
		$this -> title = (substr($this -> pageTitles[$this -> pageSelected], -1) == ";" ? "" : $this -> pageTitles[$this -> pageSelected]);

		if($this -> debug)
		{
			echo "<b>multipageFlag</b> ".$this -> multipageFlag." <br />";
			if($this -> multipageFlag)
			{
				echo "<pre>"; print_r($pages); echo "</pre>";
				echo "<b>pageCount</b> ".$pageCount." <br />";
				echo "<b>titleCount</b> ".$titleCount." <br />";
				echo "<pre>"; print_r($this -> pageTitles); echo "</pre>";
			}
		}
	}

	function pageIndex()
	{
		$itext = "<br /><br />";
		$count = 0;
		foreach($this -> pageTitles as $title)
		{
			$itext .= $this -> bullet." ".($count == $this -> pageSelected ? $title : "<a href='".e_SELF."?".$this -> pageID.".".$count."'>".$title."</a>")."<br />\n";
			$count++;
		}
		return $itext;
	}

	function pageRating($page_rating_flag)
	{
		if($page_rating_flag)
		{
			require_once(e_HANDLER."rate_class.php");
			$rater = new rater;
			$rate_text = "<br /><table style='width:100%'><tr><td style='width:50%'>";

			if ($ratearray = $rater->getrating("page", $this -> pageID))
			{
				if ($ratearray[2] == "")
				{
					$ratearray[2] = 0;
				}
				$rate_text .= "<img src='".e_IMAGE."rate/box/box".$ratearray[1].".png' alt='' style='vertical-align:middle;' />\n";
				$rate_text .= "&nbsp;".$ratearray[1].".".$ratearray[2]." - ".$ratearray[0]."&nbsp;";
				$rate_text .= ($ratearray[0] == 1 ? "vote" : "votes");
			}
			else
			{
				$rating .= LAN_PAGE_dl_13;
			}
			$rate_text .= "</td><td style='width:50%; text-align:right'>";

			if (!$rater->checkrated("page", $this -> pageID) && USER)
			{
				$rate_text .= $rater->rateselect("&nbsp;&nbsp;&nbsp;&nbsp; <b>".LAN_PAGE_4."</b>", "page", $this -> pageID);
			}
			else if(!USER)
			{
				$rate_text .= "&nbsp;";
			}
			else
			{
				$rate_text .= LAN_PAGE_5;
			}
			$rate_text .= "</td></tr></table>";
		}
		return $rate_text;
	}

	function pageComment($page_comment_flag)
	{
		global $sql, $ns, $e107cache, $tp, $comment_shortcodes,$cacheString;

		if($page_comment_flag)
		{
			require_once(e_HANDLER."comment_class.php");
			$cobj = new comment;

			if (isset($_POST['commentsubmit']))
			{
				$cobj->enter_comment($_POST['author_name'], $_POST['comment'], "page", $this -> pageID, $pid, $_POST['subject']);
				$e107cache->clear("comment.page.".$this -> pageID);
				$e107cache->clear($cacheString);
			}

			return $cobj->compose_comment("page", "comment", $this -> pageID, $width="", $subject="", $showrate=FALSE, $return=TRUE);
		}
	}

	function pageCheckPerms($page_class, $page_password)
	{
		global $ns, $tp, $HEADER, $FOOTER, $sql;     // $tp added

		if (!check_class($page_class))
		{
			message_handler("MESSAGE", LAN_PAGE_6);
			require_once(FOOTERF); exit;
		}

		if (!$page_password)
		{
			return TRUE;
		}

		if($_POST['submit_page_pw'])
		{
			if($_POST['page_pw'] == $page_password)
			{
				$this -> setPageCookie();
			}
		}
		else
		{
			$cookiename = "e107page_".$this -> pageID;

			if($_COOKIE[$cookiename] == md5($page_password.USERID))
			{
				return TRUE;
			}
		}

		if ($_POST['submit_page_pw'])
		{
			message_handler("MESSAGE", LAN_PAGE_7);
		}

		$pw_entry_text = "
		<div style='text-align:center; margin-left:auto; margin-right: auto;'>
		<form method='post' action='".e_SELF."?".e_QUERY."' id='pwform'>
		<table style='width:100%;' class='fborder'>
		<tr>
		<td class='forumheader' colspan='3' style='text-align:center; white-space:nowrap'>".LAN_PAGE_8."</td>
		</tr>
		<tr>
		<td class='forumheader3' style='width:20%;'>".LAN_PAGE_9.":</td>
		<td class='forumheader3' style='width: 60%;'><input type='password' id='page_pw' name='page_pw' style='width: 90%;'/></td>
		<td class='forumheader3' style='width:20%; vertical-align:middle; margin-left:auto; margin-right:auto; text-align:center;'><img src='".e_IMAGE."generic/".IMODE."/password.png' alt='' /></td>
		</tr>
		<tr>
		<td class='forumheader' colspan='3' style='text-align:center;'><input class='button' type='submit' name='submit_page_pw' value='".LAN_PAGE_10."' /></td>
		</tr>
		</table>
		</form>
		</div>
		";
		// Mustn't return to higher level code here

		// HEADERF requires that $tp is defined - hence declared as global above.
		require_once(HEADERF);
		$ns->tablerender("&nbsp;", $pw_entry_text);		// HEADERF also clears $text - hence different variable
		require_once(FOOTERF);
		exit;
	}

	function setPageCookie()
	{
		global $pref;
		$pref['pageCookieExpire'] = max($pref['pageCookieExpire'], 120);
		$hash = md5($_POST['page_pw'].USERID);
		cookie("e107page_".e_QUERY, $hash, (time() + $pref['pageCookieExpire']));
		header("location:".e_SELF."?".e_QUERY);
		exit;
	}
}

?>