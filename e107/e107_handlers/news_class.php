<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/classes/news_class.php
|
|	�Steve Dunstan 2001-2002
|	http://jalist.com
|	stevedunstan@jalist.com
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).	
|
| $Source: /cvs_backup/e107/e107_handlers/news_class.php,v $
| $Revision: 1.22 $
| $Date: 2005-10-21 00:29:32 $
| $Author: mcfly_e107 $ 
+---------------------------------------------------------------+
*/
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
class news{

	
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function submit_item($news){
		
		if(!is_object($aj)) $aj = new textparse;
		if(!is_object($sql)) $sql = new db;
		extract($news);
		if($news_id){
			$news_title = $aj -> formtpa($news_title);
			$news_body = $aj -> formtpa($data);
			$news_extended = $aj -> formtpa($news_extended);
			$vals = $update_datestamp ? "news_datestamp = ".time().", " : "";
			$vals .= " news_title='$news_title', news_body='$news_body', news_extended='$news_extended', news_category='$cat_id', news_allow_comments='$news_allow_comments', news_start='$active_start', news_end='$active_end', news_class='$news_class', news_render_type='$news_rendertype' WHERE news_id='$news_id' ";
			if($sql -> db_Update("news",$vals)){
				$message = "News updated in database.";
             clear_cache("news.php");
			}else{
				$message = "<strong>Error! - Was unable to update news item into database!</strong>";
			}
		}else{
			$news_title = $aj -> formtpa($news_title);
			$news_body = $aj -> formtpa($data);
			$news_extended = $aj -> formtpa($news_extended);
			if($sql -> db_Insert("news", "0, '$news_title', '$news_body', '$news_extended', ".time().", ".USERID.", $cat_id, $news_allow_comments, $active_start, $active_end, '$news_class', '$news_rendertype' ")){
				$message = "News entered into database.";
             clear_cache("news.php");
			}else{
				$message = "<strong>Error! - Was unable to enter news item into database!</strong>";
			}
		}
		$this -> create_rss();
		return $message;
	}
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
	function render_newsitem($news, $mode="default", $n_restrict=""){
    if($n_restrict=="userclass"){
      $news['news_id'] = 0;
      $news['news_title'] = LAN_NEWS_1;
      $news['data'] = LAN_NEWS_2;
      $news['news_extended'] = "";
      $news['news_allow_comments'] = 1;
      $news['news_start'] = 0;
      $news['news_end'] = 0;
      $news['news_rendertype'] = 0;
      $news['comment_total'] = 0;
    }
		if(function_exists("theme_render_newsitem")){
			$result = call_user_func("theme_render_newsitem",$news);
			if($result == "return"){return;}
		}
		global $NEWSSTYLE, $NEWSLISTSTYLE;
		if(!is_object($aj)) $aj = new textparse;
		if(!is_object($sql)) $sql = new db;
		extract($news);

		define("IMAGE_nonew_small", (file_exists(THEME."generic/nonew_comments.png") ? "<img src='".THEME."generic/nonew_comments.png' alt=''  /> " : "<img src='".e_IMAGE."generic/nonew_comments.png' alt=''  />"));
		define("IMAGE_new_small", (file_exists(THEME."generic/new_comments.png") ? "<img src='".THEME."generic/new_comments.png' alt=''  /> " : "<img src='".e_IMAGE."generic/new_comments.png' alt=''  /> "));

		if(!$NEWSLISTSTYLE){
			$NEWSLISTSTYLE = "
<img src='".THEME."images/bullet2.gif' alt='bullet' /> 
<b>
{NEWSTITLE}
</b>
<div class='smalltext'>
{NEWSAUTHOR}
on
{NEWSDATE}
{NEWSCOMMENTS}
</div>
<hr />
";
	}

		$news_title = $aj -> tpa($news_title, "off", "admin");
		$highlight_search = FALSE;
		if(IsSet($_POST['highlight_search'])){
			$highlight_search = TRUE;
		}
		$news_body = $aj -> tpa($data, "off", "admin", $highlight_search);
		$news_extended = trim(chop($aj -> tpa($news_extended, "off", "admin")));

		if(!$comment_total) $comment_total = "0";
		$con = new convert;
		$datestamp = $con -> convert_date($news_datestamp, "long");

		$titleonly = (substr($news_body, 0, 6) == "&nbsp;" && $NEWSLISTSTYLE ? TRUE : FALSE);

		if($news_title == "Welcome to e107"){
			$admin_name = "e107";
			$admin_email = "e107@jalist.com";
			$category_name = "e107 welcome message";
			$category_id = 0;
			$category_icon = (strstr(SITEBUTTON, "http") ? SITEBUTTON : e_IMAGE.SITEBUTTON);
		}else{
			$category_icon = str_replace("../", "", $category_icon);
			if(strstr("images", $category_icon)){
				$category_icon = THEME.$category_icon;
			}else{
				$category_icon = e_IMAGE."newsicons/".$category_icon;
			}
		}

		$active_start = ($active_start ? str_replace(" - 00:00:00", "", $con -> convert_date($active_start, "long")) : "Now");
		$active_end = ($active_end ?  " to ".str_replace(" - 00:00:00", "", $con -> convert_date($active_end, "long")) : "");
		$info = "<div class='smalltext'><br /><br /><b>Info:</b><br />";
		$info .= ($titleonly ? "Title only is set - <b>only the news title will be shown</b><br />" : "");
		$info .= ($news_class==255 ? "This news post is <b>inactive</b> (It will be not shown on front page). " : "This news post is <b>active</b> (it will be shown on front page). ");
		$info .= ($news_allow_comments ? "Comments are turned <b>off</b>. " : "Comments are turned <b>on</b>. ");
		$info .= "<br />Activation period: ".$active_start.$active_end."<br />";
		$info .= "Body length: ".strlen($news_body)."b. Extended length: ".strlen($news_extended)."b.<br /><br /></div>";

		$sql -> db_Select("comments", "comment_datestamp", "comment_item_id='".$news['news_id']."' AND comment_type='0' ORDER BY comment_datestamp DESC LIMIT 0,1");
		list($comments['comment_datestamp']) = $sql -> db_Fetch();
		$latest_comment = $comments['comment_datestamp'];
		if($latest_comment > USERLV ){
				$NEWIMAGE = IMAGE_new_small;
		}else{
				$NEWIMAGE = IMAGE_nonew_small;
		}

		$news_category = "<a href='".e_BASE."news.php?cat.".$category_id."'>".$category_name."</a>";
		$news_author = "<a href='".e_BASE."user.php?id.".$admin_id."'>".$admin_name."</a>";
		
		require_once(e_HANDLER."emailprint_class.php");
		$ep = new emailprint;
		$textemail = $ep -> render_emailprint("news",$news_id, 1);
		$textprint = $ep -> render_emailprint("news",$news_id, 2);
		
		/*
		$etext = " <a href='".e_BASE."email.php?news.".$news_id."'><img src='".e_IMAGE."generic/friend.gif' style='border:0' alt='email to someone' title='email to someone'/></a>";
		$ptext = " <a href='".e_BASE."print.php?news.".$news_id."'><img src='".e_IMAGE."generic/printer.gif' style='border:0' alt='printer friendly' title='printer friendly'/></a>";
		*/
		if(ADMIN && getperms("H")){
			$adminoptions .= "<a href='".e_BASE.e_ADMIN."newspost.php?create.edit.".$news_id."'><img src='".e_IMAGE."generic/newsedit.png' alt='' style='border:0' /></a>\n";
		}

		$search[0] = "/\{NEWSTITLE\}(.*?)/si";
		$replace[0] = ($news_rendertype == 1 ? "<a href='".e_BASE."news.php?item.$news_id'>".$news_title."</a>" : $news_title);

		$search[13] = "/\{CAPTIONCLASS\}(.*?)/si";
		$replace[13] = "<div class='category".$category_id."'>".($titleonly ? "&nbsp;<a href='".e_BASE."comment.php?comment.news.$news_id'>".$news_title."</a>" : "&nbsp;".$news_title)."</div>";

		$search[14] = "/\{ADMINCAPTION\}(.*?)/si";
		$replace[14] = "<div class='$admin_name'>".($titleonly ? "&nbsp;<a href='".e_BASE."comment.php?comment.news.$news_id'>".$news_title."</a>" : "&nbsp;".$news_title)."</div>";

		$search[15] = "/\{ADMINBODY\}(.*?)/si";
		$replace[15] = "<div class='$admin_name'>".(strstr(e_QUERY, "extend") ? $news_body."<br /><br />".$news_extended : $news_body)."</div>";

		$search[1] = "/\{NEWSBODY\}(.*?)/si";
		$replace[1] = (strstr(e_QUERY, "extend") ? $news_body."<br /><br />".$news_extended : $news_body);
		$search[2] = "/\{NEWSICON\}(.*?)/si";
		$replace[2] = "<a href='".e_BASE."news.php?cat.$category_id'><img style='".ICONSTYLE."'  src='$category_icon' alt='' /></a>";
		$search[3] = "/\{NEWSHEADER\}(.*?)/si";
		$replace[3] = $category_icon;
		$search[4] = "/\{NEWSCATEGORY\}(.*?)/si";
		$replace[4] = "<a href='".e_BASE."news.php?cat.$category_id'>".$category_name."</a>";
		$search[5] = "/\{NEWSAUTHOR\}(.*?)/si";
		$replace[5] = $news_author;
		$search[6] = "/\{NEWSDATE\}(.*?)/si";
		$replace[6] = $datestamp;
		$search[7] = "/\{NEWSCOMMENTS\}(.*?)/si";
		$replace[7] = ($news_allow_comments ? COMMENTOFFSTRING : "".$NEWIMAGE." <a href='".e_BASE."comment.php?comment.news.$news_id'>".COMMENTLINK.$comment_total."</a>");
		$search[8] = "/\{EMAILICON\}(.*?)/si";
		$replace[8] = $textemail;
		$search[9] = "/\{PRINTICON\}(.*?)/si";
		$replace[9] = $textprint;
		$search[10] = "/\{NEWSID\}(.*?)/si";
		$replace[10] = $news_id;

		$search[11] = "/\{ADMINOPTIONS\}(.*?)/si";
		$replace[11] = $adminoptions;

		$search[12] = "/\{EXTENDED\}(.*?)/si";

		if($preview == "Preview" && $news_extended && !strstr(e_QUERY, "extend")){
			if(defined("PRE_EXTENDEDSTRING")){ $es1 = PRE_EXTENDEDSTRING; }
			if(defined("POST_EXTENDEDSTRING")){ $es2 = POST_EXTENDEDSTRING; }
			$replace[12] = $es1.EXTENDEDSTRING.$es2."<br />".$news_extended;
		}else if($news_extended && !strstr(e_QUERY, "extend")){
			if(defined("PRE_EXTENDEDSTRING")){ $es1 = PRE_EXTENDEDSTRING; }
			if(defined("POST_EXTENDEDSTRING")){ $es2 = POST_EXTENDEDSTRING; }
			$replace[12] = $es1."<a href='".e_BASE."news.php?extend.".$news_id."'>".EXTENDEDSTRING."</a>".$es2;
		}
		
		$text = preg_replace($search, $replace, ($news_rendertype == 1 && strstr(e_SELF, "news.php") ? $NEWSLISTSTYLE : $NEWSSTYLE));
		echo $text;
		if($preview == "Preview"){ echo $info; }

		return TRUE;
	}
	
function make_xml_compatible($original){
	global $aj;
  if(!is_object($aj)) $aj = new textparse;
  $original = $aj -> tpa($original);
  // remove html-only entities
  $original = str_replace('&pound', '&amp;#163;', $original);
  $original = str_replace('&copy;', '(c)', $original);
	// encode rest
  return htmlspecialchars($original);
}

function create_rss(){
                /*
                # rss create
                # - parameters                none
                # - return                                null
                # - scope                                        public
                */
                global $sql;
  							global $aj;
								if(!is_object($aj)) $aj = new textparse;
                setlocale (LC_TIME, CORE_LC);
                $pubdate = strftime("%a, %d %b %Y %I:%M:00 GMT", time());
                $sitebutton = (strstr(SITEBUTTON, "http:") ? SITEBUTTON : SITEURL.str_replace("../", "", e_IMAGE).SITEBUTTON);
                $sitedisclaimer = ereg_replace("<br />|\n", "", SITEDISCLAIMER);

        $rss = "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>
<rss version=\"2.0\">
<channel>
  <title>".$this->make_xml_compatible(SITENAME)."</title>
  <link>http://".$_SERVER['HTTP_HOST'].e_HTTP."index.php</link>
  <description>".$this->make_xml_compatible(SITEDESCRIPTION)."</description>
  <language>".CORE_LC."-".CORE_LC2."</language>
  <copyright>".$this->make_xml_compatible($sitedisclaimer)."</copyright>
  <managingEditor>".$this->make_xml_compatible(SITEADMIN)." - ".SITEADMINEMAIL."</managingEditor>
  <webMaster>".SITEADMINEMAIL."</webMaster>
  <pubDate>$pubdate</pubDate>
  <lastBuildDate>$pubdate</lastBuildDate>
  <docs>http://backend.userland.com/rss</docs>
  <generator>e107 website system (http://e107.org)</generator>
  <ttl>60</ttl>

  <image>
    <title>".$this->make_xml_compatible(SITENAME)."</title>
    <url>".$sitebutton."</url>
    <link>http://".$_SERVER['HTTP_HOST'].e_HTTP."index.php</link>
    <width>88</width>
    <height>31</height>
    <description>".$this->make_xml_compatible(SITETAG)."</description>
  </image>

  <textInput>
    <title>Search</title>
    <description>Search ".$this->make_xml_compatible(SITENAME)."</description>
    <name>query</name>
    <link>".SITEURL.(substr(SITEURL, -1) == "/" ? "" : "/")."search.php</link>
  </textInput>
  ";

        $sql2 = new db;

        $sql -> db_Select("news", "*", "news_class=0 AND (news_start=0 || news_start < ".time().") AND (news_end=0 || news_end>".time().") ORDER BY news_datestamp DESC LIMIT 0, 10");
        while($row = $sql -> db_Fetch()){
                extract($row);
                $sql2 -> db_Select("news_category", "*",  "category_id='$news_category' ");
                $row = $sql2 -> db_Fetch(); extract($row);
                $sql2 -> db_Select("user", "user_name, user_email", "user_id=$news_author");
                $row = $sql2 -> db_Fetch(); extract($row);
                $tmp = explode(" ", $news_body);
                unset($nb);
                for($a=0; $a<=100; $a++){
                        $nb .= $tmp[$a]." ";
                }
                if($tmp[($a-2)]){ $nb .= " [more ...]"; }
                //$nb = $this->make_xml_compatible($nb);
                // Code from Lisa
                //$search = array();
                //$replace = array();
                //$search[0] = "/\<a href=\"(.*?)\">(.*?)<\/a>/si";
                //$replace[0] = '\\2';
                //$search[1] = "/\<a href='(.*?)'>(.*?)<\/a>/si";
                //$replace[1] = '\\2';
                //$search[2] = "/\<a href='(.*?)'>(.*?)<\/a>/si";
                //$replace[2] = '\\2';
                //$search[3] = "/\<a href=&quot;(.*?)&quot;>(.*?)<\/a>/si";
                //$replace[3] = '\\2';
                //$news_title = preg_replace($search, $replace, $news_title);
                // End of code from Lisa
                $wlog .= strip_tags($aj -> tpa($news_title))."\n".SITEURL."comment.php?comment.news.".$news_id."\n\n";
                $itemdate = strftime("%a, %d %b %Y %I:%M:00 GMT", $news_datestamp);

  $rss .= "<item>
    <title>".$this->make_xml_compatible(strip_tags($aj -> tpa($news_title)))."</title>
    <link>http://".$_SERVER['HTTP_HOST'].e_HTTP."comment.php?comment.news.".$news_id."</link>
    <description>".$this->make_xml_compatible($nb)."</description>
    <category domain=\"".SITEURL."\">$category_name</category>
    <comments>http://".$_SERVER['HTTP_HOST'].e_HTTP."comment.php?comment.news.".$news_id."</comments>
    <author>".$this->make_xml_compatible($user_name)." - $user_email</author>
    <pubDate>$itemdate</pubDate>
    <guid isPermaLink=\"true\">http://".$_SERVER['HTTP_HOST'].e_HTTP."comment.php?comment.news.".$news_id."</guid>
  </item>
  ";

        }


        $rss .= "</channel>
</rss>";
        $rss = str_replace("&nbsp;", " ", $rss);
        $fp = fopen(e_FILE."backend/news.xml","w");
        @fwrite($fp, $rss);
        fclose($fp);
        $fp = fopen(e_FILE."backend/news.txt","w");
        @fwrite($fp, $wlog);
        fclose($fp);
        if(!fwrite){
                $text = "<div style='text-align:center'>".LAN_19."</div>";
                $ns -> tablerender("<div style='text-align:center'>".LAN_20."</div>", $text);
        }
}

	
}
?>