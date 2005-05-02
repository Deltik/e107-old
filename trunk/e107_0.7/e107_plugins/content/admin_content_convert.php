<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /admin/review.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
|
|		$Source: /cvs_backup/e107_0.7/e107_plugins/content/admin_content_convert.php,v $
|		$Revision: 1.7 $
|		$Date: 2005-05-02 22:47:26 $
|		$Author: lisa_ $
+---------------------------------------------------------------+
*/

require_once("../../class2.php");

if(!getperms("P")){header("location:".e_BASE."index.php"); exit; }
$e_sub_cat = 'content';

$lan_file = e_PLUGIN.'content/languages/'.e_LANGUAGE.'/lan_content.php';
include_once(file_exists($lan_file) ? $lan_file : e_PLUGIN.'content/languages/English/lan_content.php');
require_once(e_ADMIN."auth.php");
require_once(e_HANDLER."form_handler.php");
$rs = new form;
require_once(e_PLUGIN."content/handlers/content_class.php");
$aa = new content;
global $tp;

if (e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$sub_action = $tmp[1];
	$id = $tmp[2];
	unset($tmp);
}

$plugintable = "pcontent";

if(isset($_POST['convert_table'])){
	
	$text = "";
	$textcontent = "";
	$textreview = "";
	$textarticle = "";
	$textreviewcat = "";
	$textarticlecat = "";

	$countcontent = "0";
	$countreview = "0";
	$countarticle = "0";
	$countreviewcat = "0";
	$countarticlecat = "0";

	if(!is_object($sql)){ $sql = new db; }
	if(!is_object($sql2)){ $sql2 = new db; }
	if(!is_object($sql3)){ $sql3 = new db; }
	if(!is_object($sql4)){ $sql4 = new db; }
	if(!is_object($sql5)){ $sql5 = new db; }
	if(!is_object($sql6)){ $sql6 = new db; }
	if(!is_object($sql7)){ $sql7 = new db; }
	if(!is_object($sql8)){ $sql8 = new db; }

	// ##### STAGE 1 : ANALYSE OLD CONTENT --------------------------------------------------------
					//analyse old content table
					if(!is_object($sql)){ $sql = new db; }
					$totaloldcontentrows = $sql -> db_Count("content");
					$totaloldrowscat_content = $sql -> db_Count("content", "(*)", "WHERE content_parent = '0' AND content_type = '1'");
					$totaloldrowscat_article = $sql -> db_Count("content", "(*)", "WHERE content_parent = '0' AND content_type = '6'");
					$totaloldrowscat_review = $sql -> db_Count("content", "(*)", "WHERE content_parent = '0' AND content_type = '10'");
					$totaloldrowsitem_review = $sql -> db_Count("content", "(*)", "WHERE content_type = '3' || content_type = '16'");
					$totaloldrowsitem_article = $sql -> db_Count("content", "(*)", "WHERE content_type = '0' || content_type = '15'");

					if(!is_object($sql)){ $sql = new db; }
					$totaloldrowsunknown = $sql -> db_Select("content", "*", " NOT ( (content_parent = '0' AND content_type = '1') || (content_parent = '0' AND content_type = '6') || (content_parent = '0' AND content_type = '10') || (content_type = '3' || content_type = '16') || (content_type = '0' || content_type = '15') ) ");

					while($row = $sql -> db_Fetch()){
						$bug_unknownrows[] = $row['content_id']." ".$row['content_heading']." - parent=".$row['content_parent']." - type=".$row['content_type'];
						$unknownrows[] = $row['content_id'];
					}

					//content page:		$content_parent == "0" && $content_type == "1"
					//review category:	$content_parent == "0" && $content_type == "10"
					//article category:	$content_parent == "0" && $content_type == "6"
					//review:	$content_type == "3" || $content_type == "16"
					//article:	$content_type == "0" || $content_type == "15"


	// ##### STAGE 2 : INSERT MAIN PARENT FOR CONTENT ---------------------------------------------
	$mainparentcontent = "0";
	if($totaloldrowscat_content > "0"){
					//insert a content main parent cat, then insert all content pages into this main parent
					if(!is_object($sql)){ $sql = new db; }
					if(!$sql -> db_Select($plugintable, "content_heading", "content_heading = 'content' AND content_parent = '0' ")){
						$sql -> db_Insert($plugintable, "'0', 'content', '', '', '', '1', '', '', '', '0', '0', '0', '0', '', '".time()."', '0', '0', '', '1' ");

						//check if row is present in the db (is it a valid insert)
						if(!is_object($sql2)){ $sql2 = new db; }
						if(!$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'content' ")){
							$content_mainparent = CONTENT_ADMIN_CONVERSION_LAN_45;
						}else{
							$content_mainparent = CONTENT_ADMIN_CONVERSION_LAN_0." ".CONTENT_ADMIN_CONVERSION_LAN_7."<br />";
							$mainparentcontent = "1";

							//select main content parent id
							if(!is_object($sql3)){ $sql3 = new db; }
							$sql3 -> db_Select($plugintable, "content_id", "content_heading = 'content' AND content_parent = '0' ");
							list($content_main_id) = $sql3 -> db_Fetch();

							//insert default prefs into the main content cat
							//unset($content_pref, $tmp);
							//$content_pref = $aa -> ContentDefaultPrefs($content_main_id);
							//$tmp = addslashes(serialize($content_pref));
							//if(!is_object($sql4)){ $sql4 = new db; }
							//$sql4 -> db_Update($plugintable, "content_pref='$tmp' WHERE content_id='$content_main_id' ");
							
							insert_default_prefs($content_main_id);
							$aa -> CreateParentMenu($content_main_id);

							$content_mainparent .= CONTENT_ADMIN_CONVERSION_LAN_0." ".CONTENT_ADMIN_CONVERSION_LAN_8."<br />";
						}
					}else{
						$content_mainparent = CONTENT_ADMIN_CONVERSION_LAN_9." ".CONTENT_ADMIN_CONVERSION_LAN_0." ".CONTENT_ADMIN_CONVERSION_LAN_10."<br />";
					}
	}else{
		$content_mainparent .= CONTENT_ADMIN_CONVERSION_LAN_9." ".CONTENT_ADMIN_CONVERSION_LAN_0." ".CONTENT_ADMIN_CONVERSION_LAN_10."<br />";
	}
	


	// ##### STAGE 3 : INSERT MAIN PARENT FOR REVIEW ----------------------------------------------
	$mainparentreview = "0";
	if($totaloldrowscat_review > "0"){
					//insert a review main parent cat, then insert all review cats into this main parent
					if(!is_object($sql)){ $sql = new db; }
					if(!$sql -> db_Select($plugintable, "content_heading", "content_heading = 'review' AND content_parent = '0' ")){
						$sql -> db_Insert($plugintable, "'0', 'review', '', '', '', '1', '', '', '', '0', '0', '0', '0', '', '".time()."', '0', '0', '', '1' ");

						//check if row is present in the db (is it a valid insert)
						if(!is_object($sql2)){ $sql2 = new db; }
						if(!$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'review' ")){
							$review_mainparent = CONTENT_ADMIN_CONVERSION_LAN_45;
						}else{
							$review_mainparent = CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_7."<br />";
							$mainparentreview = "1";

							//select main review parent id
							if(!is_object($sql3)){ $sql3 = new db; }
							$sql3 -> db_Select($plugintable, "content_id", "content_heading = 'review' AND content_parent = '0' ");
							list($review_main_id) = $sql3 -> db_Fetch();

							//insert default prefs into the main review cat
							//unset($content_pref, $tmp);
							//$content_pref = $aa -> ContentDefaultPrefs($review_main_id);
							//$tmp = addslashes(serialize($content_pref));
							//if(!is_object($sql4)){ $sql4 = new db; }
							//$sql4 -> db_Update($plugintable, "content_pref='$tmp' WHERE content_id='$review_main_id' ");
							
							insert_default_prefs($review_main_id);
							$aa -> CreateParentMenu($review_main_id);
							
							$review_mainparent .= CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_8."<br />";
							
						}
					}else{
						$review_mainparent = CONTENT_ADMIN_CONVERSION_LAN_9." ".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_10."<br />";
					}
	}else{
		$review_mainparent = CONTENT_ADMIN_CONVERSION_LAN_9." ".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_10."<br />";
	}
	

	// ##### STAGE 4 : INSERT MAIN PARENT FOR ARTICLE ---------------------------------------------
	$mainparentarticle = "0";
	if($totaloldrowscat_article > "0"){
					//insert a article main parent cat, then insert all article cats into this main parent
					if(!is_object($sql)){ $sql = new db; }
					if(!$sql -> db_Select($plugintable, "content_heading", "content_heading = 'article' AND content_parent = '0' ")){
						$sql -> db_Insert($plugintable, "'0', 'article', '', '', '', '1', '', '', '', '0', '0', '0', '0', '', '".time()."', '0', '0', '', '1' ");

						//check if row is present in the db (is it a valid insert)
						if(!is_object($sql2)){ $sql2 = new db; }
						if(!$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'article' ")){
							$article_mainparent = CONTENT_ADMIN_CONVERSION_LAN_45;
						}else{
							$article_mainparent = CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_7."<br />";
							$mainparentarticle = "1";

							//select main article parent id
							if(!is_object($sql3)){ $sql3 = new db; }
							$sql3 -> db_Select($plugintable, "content_id", "content_heading = 'article' AND content_parent = '0' ");
							list($article_main_id) = $sql3 -> db_Fetch();

							//insert default prefs into the main article cat
							//unset($content_pref, $tmp);
							//$content_pref = $aa -> ContentDefaultPrefs($article_main_id);
							//$tmp = addslashes(serialize($content_pref));
							//if(!is_object($sql4)){ $sql4 = new db; }
							//$sql4 -> db_Update($plugintable, "content_pref='$tmp' WHERE content_id='$article_main_id' ");
							
							insert_default_prefs($article_main_id);
							$aa -> CreateParentMenu($article_main_id);

							$article_mainparent .= CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_8."<br />";
						}
					}else{
						$article_mainparent = CONTENT_ADMIN_CONVERSION_LAN_9." ".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_10."<br />";
					}
	}else{
		$article_mainparent = CONTENT_ADMIN_CONVERSION_LAN_9." ".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_10."<br />";
	}

	// ##### STAGE 5 : INSERT CONTENT -------------------------------------------------------------
	if(!is_object($sql)){ $sql = new db; }
	if(!$sql -> db_Select("content", "*", "content_parent = '0' AND content_type = '1' ORDER BY content_id " )){
		$content_present = "0";
	}else{
		$count = 1;
		$content_present = "1";
		while($row = $sql -> db_Fetch()){
					$oldcontentid = $row['content_id'];

					//select main content parent id
					if(!is_object($sql2)){ $sql2 = new db; }
					$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'content' AND content_parent = '0' ");
					list($content_main_id2) = $sql2 -> db_Fetch();

					//summary can contain link to image in e107_images/link_icons/".$summary." THIS STILL NEEDS TO BE CHECKED
					$newcontent_heading = $tp -> toDB($row['content_heading']);
					$newcontent_subheading = ($row['content_subheading'] ? $tp -> toDB($row['content_subheading']) : "");
					$newcontent_summary = ($row['content_summary'] ? $tp -> toDB($row['content_summary']) : "");
					$newcontent_text = $tp -> toDB($row['content_content']);
					$newcontent_author = (is_numeric($row['content_author']) ? $row['content_author'] : "0^".$row['content_author']);
					$newcontent_icon = "";
					$newcontent_attach = "";
					$newcontent_images = "";
					$newcontent_parent = $content_main_id2.".".$content_main_id2;
					$newcontent_comment = $row['content_comment'];
					$newcontent_rate = "0";
					$newcontent_pe = $row['content_pe_icon'];
					$newcontent_refer = "0";
					$newcontent_starttime = $row['content_datestamp'];
					$newcontent_endtime = "0";
					$newcontent_class = $row['content_class'];
					$newcontent_pref = "";

					if(!is_object($sql3)){ $sql3 = new db; }
					$sql6 -> db_Insert($plugintable, "'0', '".$newcontent_heading."', '".$newcontent_subheading."', '".$newcontent_summary."', '".$newcontent_text."', '".$newcontent_author."', '".$newcontent_icon."', '".$newcontent_attach."', '".$newcontent_images."', '".$newcontent_parent."', '".$newcontent_comment."', '".$newcontent_rate."', '".$newcontent_pe."', '".$newcontent_refer."', '".$newcontent_starttime."', '".$newcontent_endtime."', '".$newcontent_class."', '".$newcontent_pref."', '1.".$count."' ");

					if(!$sql3 -> db_Select($plugintable, "content_id, content_heading", "content_heading = '".$newcontent_heading."' ")){
						$bug_content_insert[] = $row['content_id']." ".$row['content_heading'];
					}else{
						$valid_content_insert[] = $row['content_id']." ".$row['content_heading'];
						$countcontent = $countcontent + 1;

						list($thenewcontent_id, $thenewcontent_heading) = $sql3 -> db_Fetch();
						convert_comments($row['content_id'], $thenewcontent_id);
						convert_rating($row['content_id'], $thenewcontent_id);
					}
					$count = $count + 1;
		}
	}


	// ##### STAGE 6 : INSERT REVIEW CATEGORY -----------------------------------------------------
	if(!is_object($sql)){ $sql = new db; }
	if(!$sql -> db_Select("content", "*", "content_parent = '0' AND content_type = '10' ORDER BY content_id " )){
		$review_cat_present = "0";
	}else{
		$count = 2;
		$review_cat_present = "1";
		while($row = $sql -> db_Fetch()){

					//select main review parent id
					if(!is_object($sql2)){ $sql2 = new db; }
					$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'review' AND content_parent = '0' ");
					list($review_main_id2) = $sql2 -> db_Fetch();

					//summary can contain link to image in e107_images/link_icons/".$summary." THIS STILL NEEDS TO BE CHECKED
					$newcontent_heading = $tp -> toDB($row['content_heading']);
					$newcontent_subheading = ($row['content_subheading'] ? $tp -> toDB($row['content_subheading']) : "");
					$newcontent_summary = ($row['content_summary'] ? $tp -> toDB($row['content_summary']) : "");
					$newcontent_text = $tp -> toDB($row['content_content']);
					$newcontent_author = (is_numeric($row['content_author']) ? $row['content_author'] : "0^".$row['content_author']);
					$newcontent_icon = "";
					$newcontent_attach = "";
					$newcontent_images = "";
					$newcontent_parent = "0.".$review_main_id2;				//make each review cat a first level subcat of the main review parent
					$newcontent_comment = $row['content_comment'];
					$newcontent_rate = "0";
					$newcontent_pe = $row['content_pe_icon'];
					$newcontent_refer = "0";
					$newcontent_starttime = $row['content_datestamp'];
					$newcontent_endtime = "0";
					$newcontent_class = $row['content_class'];
					$newcontent_pref = "";

					if(!is_object($sql3)){ $sql3 = new db; }
					$sql3 -> db_Insert($plugintable, "'0', '".$newcontent_heading."', '".$newcontent_subheading."', '".$newcontent_summary."', '".$newcontent_text."', '".$newcontent_author."', '".$newcontent_icon."', '".$newcontent_attach."', '".$newcontent_images."', '".$newcontent_parent."', '".$newcontent_comment."', '".$newcontent_rate."', '".$newcontent_pe."', '".$newcontent_refer."', '".$newcontent_starttime."', '".$newcontent_endtime."', '".$newcontent_class."', '".$newcontent_pref."', '".$count."' ");

					if(!$sql3 -> db_Select($plugintable, "content_id, content_heading", "content_heading = '".$newcontent_heading."' ")){
						$bug_review_cat_insert[] = $row['content_id']." ".$row['content_heading'];
					}else{
						$valid_review_cat_insert[] = $row['content_id']." ".$row['content_heading'];
						$countreviewcat = $countreviewcat + 1;
					}
					$count = $count + 1;
		}
	}


	// ##### STAGE 7 : INSERT ARTICLE CATEGORY ----------------------------------------------------
	if(!is_object($sql)){ $sql = new db; }
	if(!$sql -> db_Select("content", "*", "content_parent = '0' AND content_type = '6' ORDER BY content_id " )){
		$article_cat_present = "0";
	}else{
		$count = 2;
		$article_cat_present = "1";
		while($row = $sql -> db_Fetch()){

					//select main article parent id
					if(!is_object($sql2)){ $sql2 = new db; }
					$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'article' AND content_parent = '0' ");
					list($article_main_id2) = $sql2 -> db_Fetch();

					//summary can contain link to image in e107_images/link_icons/".$summary." THIS STILL NEEDS TO BE CHECKED
					$newcontent_heading = $tp -> toDB($row['content_heading']);
					$newcontent_subheading = ($row['content_subheading'] ? $tp -> toDB($row['content_subheading']) : "");
					$newcontent_summary = ($row['content_summary'] ? $tp -> toDB($row['content_summary']) : "");
					$newcontent_text = $tp -> toDB($row['content_content']);
					$newcontent_author = (is_numeric($row['content_author']) ? $row['content_author'] : "0^".$row['content_author']);
					$newcontent_icon = "";
					$newcontent_attach = "";
					$newcontent_images = "";
					$newcontent_parent = "0.".$article_main_id2;										//make each article cat a first level subcat of the main article parent
					$newcontent_comment = $row['content_comment'];
					$newcontent_rate = "0";
					$newcontent_pe = $row['content_pe_icon'];
					$newcontent_refer = "0";
					$newcontent_starttime = $row['content_datestamp'];
					$newcontent_endtime = "0";
					$newcontent_class = $row['content_class'];
					$newcontent_pref = "";

					if(!is_object($sql3)){ $sql3 = new db; }
					$sql3 -> db_Insert($plugintable, "'0', '".$newcontent_heading."', '".$newcontent_subheading."', '".$newcontent_summary."', '".$newcontent_text."', '".$newcontent_author."', '".$newcontent_icon."', '".$newcontent_attach."', '".$newcontent_images."', '".$newcontent_parent."', '".$newcontent_comment."', '".$newcontent_rate."', '".$newcontent_pe."', '".$newcontent_refer."', '".$newcontent_starttime."', '".$newcontent_endtime."', '".$newcontent_class."', '".$newcontent_pref."', '".$count."' ");

					if(!$sql3 -> db_Select($plugintable, "content_id, content_heading", "content_heading = '".$newcontent_heading."' ")){
						$bug_article_cat_insert[] = $row['content_id']." ".$row['content_heading'];
					}else{
						$valid_article_cat_insert[] = $row['content_id']." ".$row['content_heading'];
						$countarticlecat = $countarticlecat + 1;
					}
					$count = $count + 1;
		}
	}


	// ##### STAGE 8 : INSERT REVIEWS -------------------------------------------------------------
	if(!is_object($sql)){ $sql = new db; }
	if(!$thisreviewcount = $sql -> db_Select("content", "*", "content_type = '3' || content_type = '16' ORDER BY content_id " )){
		$review_present = "0";
	}else{
		$count = 1;
		$review_present = "1";
		while($row = $sql -> db_Fetch()){

					$oldcontentid = $row['content_id'];

					//select main review parent id
					if(!is_object($sql2)){ $sql2 = new db; }
					$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'review' AND content_parent = '0' ");
					list($review_main_id) = $sql2 -> db_Fetch();

					//review is in main review cat
					if($row['content_parent'] == "0"){
						$newcontent_parent = $review_main_id.".".$review_main_id;

					//review is in review subcat
					}else{
						//select old review cat heading
						if(!is_object($sql3)){ $sql3 = new db; }
						if(!$sql3 -> db_Select("content", "content_id, content_heading", "content_id = '".$row['content_parent']."' ")){
							$bug_article_oldcat[] = $row['content_id']." ".$row['content_heading'];
							$newcontent_parent = $review_main_id.".".$review_main_id;
						}else{
							list($old_review_cat_id, $old_review_cat_heading) = $sql3 -> db_Fetch();

							//select new review cat id from the cat with the old_review_cat_heading
							if(!is_object($sql4)){ $sql4 = new db; }
							if(!$sql4 -> db_Select($plugintable, "content_id", "content_heading = '".$old_review_cat_heading."' AND content_parent = '0.".$review_main_id."' ")){
								$bug_review_newcat[] = $row['content_id']." ".$row['content_heading'];
								$newcontent_parent = $review_main_id.".".$review_main_id;
							}else{
								list($new_review_cat_id) = $sql4 -> db_Fetch();
								$newcontent_parent = $review_main_id.".".$review_main_id.".".$new_review_cat_id;
							}
						}
					}
					
					if (strstr($row['content_content'], "{EMAILPRINT}")) {
						$row['content_content'] = str_replace("{EMAILPRINT}", "", $row['content_content']);
					}

					$newcontent_heading = $tp -> toDB($row['content_heading']);
					$newcontent_subheading = ($row['content_subheading'] ? $tp -> toDB($row['content_subheading']) : "");
					//summary can contain link to image in e107_images/link_icons/".$summary." THIS STILL NEEDS TO BE CHECKED
					$newcontent_summary = ($row['content_summary'] ? $tp -> toDB($row['content_summary']) : "");
					$newcontent_text = $tp -> toDB($row['content_content']);
					$newcontent_author = (is_numeric($row['content_author']) ? $row['content_author'] : "0^".$row['content_author']);
					$newcontent_icon = "";
					$newcontent_attach = "";
					$newcontent_images = "";
					$newcontent_comment = $row['content_comment'];
					$newcontent_rate = "0";
					$newcontent_pe = $row['content_pe_icon'];
					$newcontent_refer = ($row['content_type'] == "16" ? "sa" : "");
					$newcontent_starttime = $row['content_datestamp'];
					$newcontent_endtime = "0";
					$newcontent_class = $row['content_class'];
					
					$custom["content_custom_score"] = ($content_review_score != "none" && $content_review_score ? $content_review_score : "");
					$contentreviewprefvalue = addslashes(serialize($custom));
					$newcontent_pref = $contentreviewprefvalue;

					if(!is_object($sql5)){ $sql5 = new db; }
					$sql5 -> db_Insert($plugintable, "'0', '".$newcontent_heading."', '".$newcontent_subheading."', '".$newcontent_summary."', '".$newcontent_text."', '".$newcontent_author."', '".$newcontent_icon."', '".$newcontent_attach."', '".$newcontent_images."', '".$newcontent_parent."', '".$newcontent_comment."', '".$newcontent_rate."', '".$newcontent_pe."', '".$newcontent_refer."', '".$newcontent_starttime."', '".$newcontent_endtime."', '".$newcontent_class."', '".$newcontent_pref."', '1.".$count."' ");

					if(!is_object($sql6)){ $sql6 = new db; }
					if(!$sql6 -> db_Select($plugintable, "content_id, content_heading", "content_heading = '".$newcontent_heading."' ")){
						$bug_review_insert[] = $row['content_id']." ".$row['content_heading'];
					}else{
						$valid_review_insert[] = $row['content_id']." ".$row['content_heading'];
						$countreview = $countreview + 1;

						list($thenewcontent_id, $thenewcontent_heading) = $sql6 -> db_Fetch();
						convert_comments($row['content_id'], $thenewcontent_id);
						convert_rating($row['content_id'], $thenewcontent_id);
					}
					$count = $count + 1;
					
		}
	}


	// ##### STAGE 9 : INSERT ARTICLES ------------------------------------------------------------
	if(!is_object($sql)){ $sql = new db; }
	if(!$sql -> db_Select("content", "*", "content_type = '0' || content_type = '15' ORDER BY content_id " )){
		$article_present = "0";
	}else{
		$count = 1;
		$article_present = "1";
		while($row = $sql -> db_Fetch()){

					$oldcontentid = $row['content_id'];

					//select main article parent id
					if(!is_object($sql2)){ $sql2 = new db; }
					$sql2 -> db_Select($plugintable, "content_id", "content_heading = 'article' AND content_parent = '0' ");
					list($article_main_id) = $sql2 -> db_Fetch();

					//article is in main article cat
					if($row['content_parent'] == "0"){
						$newcontent_parent = $article_main_id.".".$article_main_id;											//place each article in correct cat

					//article is in article subcat
					}else{
						//select old article cat heading
						if(!is_object($sql3)){ $sql3 = new db; }
						if(!$sql3 -> db_Select("content", "content_id, content_heading", "content_id = '".$row['content_parent']."' ")){
							$bug_article_oldcat[] = $row['content_id']." ".$row['content_heading'];
							$newcontent_parent = $article_main_id.".".$article_main_id;
						}else{
							list($old_article_cat_id, $old_article_cat_heading) = $sql3 -> db_Fetch();

							//select new article cat id from the cat with the old_article_cat_heading
							if(!is_object($sql4)){ $sql4 = new db; }
							if(!$sql4 -> db_Select($plugintable, "content_id", "content_heading = '".$old_article_cat_heading."' AND content_parent = '0.".$article_main_id."' ")){
								$bug_article_newcat[] = $row['content_id']." ".$row['content_heading'];
								$newcontent_parent = $article_main_id.".".$article_main_id;
							}else{
								list($new_article_cat_id) = $sql4 -> db_Fetch();
								$newcontent_parent = $article_main_id.".".$article_main_id.".".$new_article_cat_id;					//place each article in correct cat
							}
						}
					}

					if (strstr($row['content_content'], "{EMAILPRINT}")) {
						$row['content_content'] = str_replace("{EMAILPRINT}", "", $row['content_content']);
					}

					$newcontent_heading = $tp -> toDB($row['content_heading']);
					$newcontent_subheading = ($row['content_subheading'] ? $tp -> toDB($row['content_subheading']) : "");
					//summary can contain link to image in e107_images/link_icons/".$summary." THIS STILL NEEDS TO BE CHECKED
					$newcontent_summary = ($row['content_summary'] ? $tp -> toDB($row['content_summary']) : "");
					$newcontent_text = $tp -> toDB($row['content_content']);
					$newcontent_author = (is_numeric($row['content_author']) ? $row['content_author'] : "0^".$row['content_author']);
					$newcontent_icon = "";
					$newcontent_attach = "";
					$newcontent_images = "";				
					$newcontent_comment = $row['content_comment'];
					$newcontent_rate = "0";
					$newcontent_pe = $row['content_pe_icon'];
					$newcontent_refer = ($row['content_type'] == "15" ? "sa" : "");
					$newcontent_starttime = $row['content_datestamp'];
					$newcontent_endtime = "0";
					$newcontent_class = $row['content_class'];
					$newcontent_pref = "";

					if(!is_object($sql5)){ $sql5 = new db; }
					$sql5 -> db_Insert($plugintable, "'0', '".$newcontent_heading."', '".$newcontent_subheading."', '".$newcontent_summary."', '".$newcontent_text."', '".$newcontent_author."', '".$newcontent_icon."', '".$newcontent_attach."', '".$newcontent_images."', '".$newcontent_parent."', '".$newcontent_comment."', '".$newcontent_rate."', '".$newcontent_pe."', '".$newcontent_refer."', '".$newcontent_starttime."', '".$newcontent_endtime."', '".$newcontent_class."', '".$newcontent_pref."', '1.".$count."' ");

					if(!is_object($sql6)){ $sql6 = new db; }
					if(!$sql6 -> db_Select($plugintable, "content_id, content_heading", "content_heading = '".$newcontent_heading."' ")){
						$bug_article_insert[] = $row['content_id']." ".$row['content_heading'];
					}else{
						$valid_article_insert[] = $row['content_id']." ".$row['content_heading'];
						$countarticle = $countarticle + 1;

						list($thenewcontent_id, $thenewcontent_heading) = $sql6 -> db_Fetch();
						convert_comments($row['content_id'], $thenewcontent_id);
						convert_rating($row['content_id'], $thenewcontent_id);
					}
					$count = $count + 1;
		}
	}




	$text = "
	<table class='fborder' style='width:95%; padding:3px;' >

	<tr><td class='forumheader' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_11."</td></tr>
	<tr><td class='forumheader3' colspan='2'>
		".CONTENT_ADMIN_CONVERSION_LAN_12.": ".$totaloldcontentrows."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_13.": ".(count($valid_article_insert) + count($valid_article_cat_insert) + count($valid_review_insert) + count($valid_review_cat_insert) + count($valid_content_insert))."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_14.": ".(count($bug_article_oldcat) + count($bug_article_newcat) + count($bug_review_oldcat) + count($bug_review_newcat))."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_15.": ".(count($bug_article_insert) + count($bug_article_cat_insert) + count($bug_review_insert) + count($bug_review_cat_insert) + count($bug_content_insert) + count($bug_unknownrows))."<br />
	</td></tr>

	<tr><td class='forumheader3' colspan='2'><br /></td></tr>

	<tr><td class='forumheader' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_16."</td></tr>
	<tr><td class='forumheader3' colspan='2'>
		".CONTENT_ADMIN_CONVERSION_LAN_17.": ".$totaloldcontentrows."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_0." ".CONTENT_ADMIN_CONVERSION_LAN_6.": ".$totaloldrowscat_content."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_4.": ".$totaloldrowscat_review."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_6.": ".$totaloldrowsitem_review."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_4.": ".$totaloldrowscat_article."<br />
		".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_6.": ".$totaloldrowsitem_article."<br />";

		if($totaloldcontentrows > ($totaloldrowscat_content + $totaloldrowscat_review + $totaloldrowsitem_review + $totaloldrowscat_article + $totaloldrowsitem_article) ){
			$text .= CONTENT_ADMIN_CONVERSION_LAN_18.": ".($totaloldcontentrows - ($totaloldrowscat_content + $totaloldrowscat_review + $totaloldrowsitem_review + $totaloldrowscat_article + $totaloldrowsitem_article))."<br />";
		}else{
			$text .= CONTENT_ADMIN_CONVERSION_LAN_19."<br />";
		}

	$text .= "
	</td>
	</tr>";

	//unknown rows
	$text .= "
	<tr><td class='forumheader3' colspan='2'><br /></td></tr>
	<tr><td class='fcaption' colspan='2'>unknown rows</td></tr>";

		$text .= $rs -> form_open("post", e_SELF."?unknown", "dataform", "", "enctype='multipart/form-data'");

		if(count($bug_unknownrows) > 0 ){
			for($i=0;$i<count($bug_unknownrows);$i++){
				$text .= "<tr><td class='forumheader3' colspan='2'>
				".CONTENT_ICON_ERROR." ".$bug_unknownrows[$i]."
				".$rs -> form_hidden("unknownrows[]", $unknownrows[$i])."
				</td></tr>";
			}
			$text .= "<tr><td class='forumheader3' colspan='2'>".$rs -> form_button("submit", "convert_unknown", "manually convert unknown rows")."</td></tr>";
			$text .= $rs -> form_close();
		}


	$text .= "<tr><td class='forumheader3' colspan='2'><br /></td></tr>";

	//main parents
	$text .= "
	<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_20."</td></tr>
	<tr><td class='forumheader3' colspan='2'>".($mainparentcontent == "1" ? CONTENT_ICON_OK : CONTENT_ICON_ERROR)." ".$content_mainparent."</td></tr>
	<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_21."</td></tr>
	<tr><td class='forumheader3' colspan='2'>".($mainparentreview == "1" ? CONTENT_ICON_OK : CONTENT_ICON_ERROR)." ".$review_mainparent."</td></tr>
	<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_22."</td></tr>
	<tr><td class='forumheader3' colspan='2'>".($mainparentarticle == "1" ? CONTENT_ICON_OK : CONTENT_ICON_ERROR)." ".$article_mainparent."</td></tr>

	<tr><td class='forumheader3' colspan='2'><br /></td></tr>";

	//content
	if($content_present != "1"){
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_24."</td></tr>";
	}else{
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_25."</td></tr>";
		if(count($valid_content_insert) > 0 ){
			for($i=0;$i<count($valid_content_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_OK." ".$valid_content_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_0." ".CONTENT_ADMIN_CONVERSION_LAN_5." ".CONTENT_ADMIN_CONVERSION_LAN_26."</td></tr>";
			}
		}
		if(count($bug_content_insert) > 0 ){
			for($i=0;$i<count($bug_content_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_ERROR." ".$bug_content_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_23."</td></tr>";
			}
		}
		$text .= "
		<tr><td class='forumheader' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_27."</td></tr>
		<tr><td class='forumheader3' colspan='2'>
		".$totaloldrowscat_content." ".CONTENT_ADMIN_CONVERSION_LAN_28."<br />
		".count($valid_content_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_29."<br />
		".count($bug_content_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_30."<br />
		</td></tr>";
	}

	$text .= "<tr><td class='forumheader3' colspan='2'><br /></td></tr>";

	//review category
	if($review_cat_present != "1"){
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_34."</td></tr>";
	}else{
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_35."</td></tr>";
		if(count($valid_review_cat_insert) > 0 ){
			for($i=0;$i<count($valid_review_cat_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_OK." ".$valid_review_cat_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_3." ".CONTENT_ADMIN_CONVERSION_LAN_26."</td></tr>";
			}
		}
		if(count($bug_review_cat_insert) > 0 ){
			for($i=0;$i<count($bug_review_cat_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_ERROR." ".$bug_review_cat_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_23."</td></tr>";
			}
		}
		$text .= "
		<tr><td class='forumheader' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_3." ".CONTENT_ADMIN_CONVERSION_LAN_27."</td></tr>
		<tr><td class='forumheader3' colspan='2'>
		".$totaloldrowscat_review." ".CONTENT_ADMIN_CONVERSION_LAN_28."<br />
		".count($valid_review_cat_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_29."<br />
		".count($bug_review_cat_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_30."<br />
		</td></tr>";
	}

	$text .= "<tr><td class='forumheader3' colspan='2'><br /></td></tr>";

	//review pages
	if($review_present != "1"){
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_36."</td></tr>";
	}else{
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_37."</td></tr>";
		if(count($valid_review_insert) > 0 ){
			for($i=0;$i<count($valid_review_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_OK." ".$valid_review_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_6." ".CONTENT_ADMIN_CONVERSION_LAN_26."</td></tr>";
			}
		}
		if(count($bug_review_oldcat) > 0 ){
			for($i=0;$i<count($bug_review_oldcat);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_WARNING." ".$bug_review_oldcat[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_32."</td></tr>";
			}
		}
		if(count($bug_review_newcat) > 0 ){
			for($i=0;$i<count($bug_review_newcat);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_WARNING." ".$bug_review_newcat[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_33."</td></tr>";
			}
		}
		if(count($bug_review_insert) > 0 ){
			for($i=0;$i<count($bug_review_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_ERROR." ".$bug_review_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_23."</td></tr>";
			}
		}
		$text .= "
		<tr><td class='forumheader' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_1." ".CONTENT_ADMIN_CONVERSION_LAN_27."</td></tr>
		<tr><td class='forumheader3' colspan='2'>
		".$totaloldrowsitem_review." ".CONTENT_ADMIN_CONVERSION_LAN_28."<br />
		".count($valid_review_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_29."<br />
		".count($bug_review_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_30."<br />
		".count($bug_review_oldcat)." ".CONTENT_ADMIN_CONVERSION_LAN_31.": ".CONTENT_ADMIN_CONVERSION_LAN_32."<br />
		".count($bug_review_newcat)." ".CONTENT_ADMIN_CONVERSION_LAN_31.": ".CONTENT_ADMIN_CONVERSION_LAN_33."<br />
		</td></tr>";
	}

	$text .= "<tr><td class='forumheader3' colspan='2'><br /></td></tr>";

	//article category
	if($article_cat_present != "1"){
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_38."</td></tr>";
	}else{
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_39."</td></tr>";
		if(count($valid_article_cat_insert) > 0 ){
			for($i=0;$i<count($valid_article_cat_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_OK." ".$valid_article_cat_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_3." ".CONTENT_ADMIN_CONVERSION_LAN_26."</td></tr>";
			}
		}
		if(count($bug_article_cat_insert) > 0 ){
			for($i=0;$i<count($bug_article_cat_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_ERROR." ".$bug_article_cat_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_23."</td></tr>";
			}
		}
		$text .= "
		<tr><td class='forumheader' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_3." ".CONTENT_ADMIN_CONVERSION_LAN_27."</td></tr>
		<tr><td class='forumheader3' colspan='2'>
		".$totaloldrowscat_article." ".CONTENT_ADMIN_CONVERSION_LAN_28."<br />
		".count($valid_article_cat_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_29."<br />
		".count($bug_article_cat_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_30."<br />
		</td></tr>";
	}

	$text .= "<tr><td class='forumheader3' colspan='2'><br /></td></tr>";

	//article pages
	if($article_present != "1"){
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_40."</td></tr>";
	}else{
		$text .= "<tr><td class='fcaption' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_41."</td></tr>";
		if(count($valid_article_insert) > 0 ){
			for($i=0;$i<count($valid_article_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_OK." ".$valid_article_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_5." ".CONTENT_ADMIN_CONVERSION_LAN_26."</td></tr>";
			}
		}
		if(count($bug_article_oldcat) > 0 ){
			for($i=0;$i<count($bug_article_oldcat);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_WARNING." ".$bug_article_oldcat[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_32."</td></tr>";
				
			}
		}
		if(count($bug_article_newcat) > 0 ){
			for($i=0;$i<count($bug_article_newcat);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_WARNING." ".$bug_article_newcat[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_33."</td></tr>";
			}
		}
		if(count($bug_article_insert) > 0 ){
			for($i=0;$i<count($bug_article_insert);$i++){
				$text .= "<tr><td class='forumheader3'>".CONTENT_ICON_ERROR." ".$bug_article_insert[$i]."</td><td class='forumheader3' style='white-space:nowrap;'>".CONTENT_ADMIN_CONVERSION_LAN_23."</td></tr>";
			}
		}
		$text .= "
		<tr><td class='forumheader' colspan='2'>".CONTENT_ADMIN_CONVERSION_LAN_2." ".CONTENT_ADMIN_CONVERSION_LAN_27."</td></tr>
		<tr><td class='forumheader3' colspan='2'>
		".$totaloldrowsitem_article." ".CONTENT_ADMIN_CONVERSION_LAN_28."<br />
		".count($valid_article_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_29."<br />
		".count($bug_article_insert)." ".CONTENT_ADMIN_CONVERSION_LAN_30."<br />
		".count($bug_article_oldcat)." ".CONTENT_ADMIN_CONVERSION_LAN_31.": ".CONTENT_ADMIN_CONVERSION_LAN_32."<br />
		".count($bug_article_newcat)." ".CONTENT_ADMIN_CONVERSION_LAN_31.": ".CONTENT_ADMIN_CONVERSION_LAN_33."<br />
		</td></tr>";
	}

	$text .= "
	</table>";

	$caption = CONTENT_ADMIN_CONVERSION_LAN_42;
	$ns -> tablerender($caption, $text);

}


//show button to start conversion
if(!isset($_POST['convert_table']) && !isset($action)){
	$totalnewcontent = $sql -> db_Count($plugintable);

	$text = "
	<div style='text-align:center'>
	".$rs -> form_open("post", e_SELF, "dataform")."
	<table class='fborder' style='width:95%;'>";

	if($totalnewcontent != "0"){
		$text .= "<tr><td class='forumheader3' style='text-align:center;'>".CONTENT_ADMIN_CONVERSION_LAN_44."</td></tr>";
	}

	$text .= "
	<tr><td class='forumheader3' style='text-align:center;'>".$rs -> form_button("submit", "convert_table", "convert table")."</td></tr>

	</table>
	</form>
	</div>";

	$caption = CONTENT_ADMIN_CONVERSION_LAN_43;
	$ns -> tablerender($caption, $text);
}

//show start page for manual conversion of unknown rows
if($action == "unknown" && !isset($sub_action)){
	unset($text);

	if(!is_object($sql)){ $sql = new db; }
	if(!is_object($sql2)){ $sql2 = new db; }
	
	$text .= $rs -> form_open("post", e_SELF."?unknown.conversion", "dataform", "", "enctype='multipart/form-data'");
	$text .= "<table class='fborder' style='width:95%;'>";

	//get all parents into a form option array
	$parentdetails = $aa -> getParent("", "", "");
	for($a=0;$a<count($parentdetails);$a++){
		$pre = "";
		if(strpos($parentdetails[$a][9], ".")){
			$id = substr($parentdetails[$a][9], 2).".".substr($parentdetails[$a][9], 2).".".$parentdetails[$a][0];
		}else{
			$id = $parentdetails[$a][0].".".$parentdetails[$a][0];
		}
		for($b=0;$b<$parentdetails[$a][17];$b++){
			$pre .= "_";
		}
		$options .= $rs -> form_option($pre.$parentdetails[$a][1], "0", $id);
	}

	for($i=0;$i<count($_POST['unknownrows']);$i++){
		$item = $sql -> db_Select("content", "*", " content_id = '".$_POST['unknownrows'][$i]."' ");
		$row = $sql -> db_Fetch();

		$text .= "
		<tr>
		<td class='forumheader3'>".CONTENT_ICON_ERROR." ".$row['content_id']." ".$row['content_heading']." (parent=".$row['content_parent']." - type=".$row['content_type'].")</td>
		<td class='forumheader3'>";

		if(!$sql2 -> db_Select($plugintable, "content_id as newcontentid, content_heading as newcontentheading", "content_parent = '0' ")){
			//$text .= "no main parents present, manually conversion of old rows is not possible";
		}else{
			$cid = $row['content_id'];
			$text .= "
			".$rs -> form_select_open("newcontent_parent[{$cid}]")."
			".$rs -> form_option("choose parent", "0", "")."
			".$options."
			";
		}
		$text .= $rs -> form_select_close()."
		</td>
		</tr>";
	}
	$text .= "<tr><td class='forumheader' colspan='2' style='text-align:center;'>".$rs -> form_button("submit", "convert_unknownrows", "convert unknown rows")."</td></tr>";
	$text .= "</table>".$rs -> form_close();
	$caption = "manually convert unknown rows";
	$ns -> tablerender($caption, $text);
}

//convert unknown rows and show results
if($action == "unknown" && $sub_action == "conversion"){
		unset($text);
		if(isset($_POST['convert_unknownrows'])){
			foreach($_POST['newcontent_parent'] as $key => $value){

					$oldrow = $sql -> db_Select("content", "*", "content_id = '".$key."' ");
					if(!$oldrow){
						return FALSE;
					}
					$row = $sql -> db_Fetch();

					//summary can contain link to image in e107_images/link_icons/".$summary." THIS STILL NEEDS TO BE CHECKED
					$newcontent_heading = $tp -> toDB($row['content_heading']);
					$newcontent_subheading = ($row['content_subheading'] ? $tp -> toDB($row['content_subheading']) : "");
					$newcontent_summary = ($row['content_summary'] ? $tp -> toDB($row['content_summary']) : "");
					$newcontent_text = $tp -> toDB($row['content_content']);
					$newcontent_author = (is_numeric($row['content_author']) ? $row['content_author'] : "0^".$row['content_author']);
					$newcontent_icon = "";
					$newcontent_attach = "";
					$newcontent_images = "";
					$newcontent_parent = $value;
					$newcontent_comment = $row['content_comment'];
					$newcontent_rate = "0";
					$newcontent_pe = $row['content_pe_icon'];
					$newcontent_refer = "0";
					$newcontent_starttime = $row['content_datestamp'];
					$newcontent_endtime = "0";
					$newcontent_class = $row['content_class'];
					$newcontent_pref = "";

					if(!is_object($sql2)){ $sql2 = new db; }
					$sql2 -> db_Insert($plugintable, "'0', '".$newcontent_heading."', '".$newcontent_subheading."', '".$newcontent_summary."', '".$newcontent_text."', '".$newcontent_author."', '".$newcontent_icon."', '".$newcontent_attach."', '".$newcontent_images."', '".$newcontent_parent."', '".$newcontent_comment."', '".$newcontent_rate."', '".$newcontent_pe."', '".$newcontent_refer."', '".$newcontent_starttime."', '".$newcontent_endtime."', '".$newcontent_class."', '".$newcontent_pref."', '0.0' ");

					if(!is_object($sql3)){ $sql3 = new db; }
					if(!$sql3 -> db_Select($plugintable, "content_id, content_heading", "content_heading = '".$newcontent_heading."' ")){
						$text .= CONTENT_ICON_ERROR." conversion not succesfull : ".$row['content_id']." ".$row['content_heading']."<br />";
					}else{
						list($thenewcontent_id, $thenewcontent_heading) = $sql3 -> db_Fetch();

						convert_comments($row['content_id'], $thenewcontent_id);
						convert_rating($row['content_id'], $thenewcontent_id);

						$text .= CONTENT_ICON_OK." conversion succesfull : ".$row['content_id']." ".$thenewcontent_heading."<br />";
					}
			}
		}
		$caption = "conversion results for unknown rows";
		$ns -> tablerender($caption, $text);
}


//show link to start managing the content management plugin
if(isset($_POST['convert_table']) || isset($action)){
		$text = "<div style='text-align:center'>".CONTENT_ADMIN_CONVERSION_LAN_46."</div>";
		$caption = CONTENT_ADMIN_CONVERSION_LAN_47;
		$ns -> tablerender($caption, $text);
}

/*
//function to convert unknown rows
function convert_row($id, $parent){
					global $sql, $tp, $plugintable;

					$oldrow = $sql -> db_Select("content", "*", "content_id = '".$id."' ");
					if(!$oldrow){
						return FALSE;
					}
					$row = $sql -> db_Fetch();
					extract($row);

					//summary can contain link to image in e107_images/link_icons/".$summary." THIS STILL NEEDS TO BE CHECKED
					$newcontent_heading = $tp -> toDB($content_heading);
					$newcontent_subheading = ($content_subheading ? $tp -> toDB($content_subheading) : "");
					$newcontent_summary = ($content_summary ? $tp -> toDB($content_summary) : "");
					$newcontent_text = $tp -> toDB($content_content);
					$newcontent_author = (is_numeric($content_author) ? $content_author : "0^".$content_author);
					$newcontent_icon = "";
					$newcontent_attach = "";
					$newcontent_images = "";
					$newcontent_parent = $parent;
					$newcontent_comment = $content_comment;
					$newcontent_rate = "0";
					$newcontent_pe = $content_pe_icon;
					$newcontent_refer = "0";
					$newcontent_starttime = $content_datestamp;
					$newcontent_endtime = "0";
					$newcontent_class = $content_class;
					$newcontent_pref = "";

					
					if(!is_object($sql2)){ $sql2 = new db; }
					$sql2 -> db_Insert($plugintable, "'0', '".$newcontent_heading."', '".$newcontent_subheading."', '".$newcontent_summary."', '".$newcontent_text."', '".$newcontent_author."', '".$newcontent_icon."', '".$newcontent_attach."', '".$newcontent_images."', '".$newcontent_parent."', '".$newcontent_comment."', '".$newcontent_rate."', '".$newcontent_pe."', '".$newcontent_refer."', '".$newcontent_starttime."', '".$newcontent_endtime."', '".$newcontent_class."', '".$newcontent_pref."', '0.0' ");

					if(!is_object($sql3)){ $sql3 = new db; }
					if(!$sql3 -> db_Select($plugintable, "content_id, content_heading", "content_heading = '".$newcontent_heading."' ")){
						$bug_content_insert[] = $content_id." ".$content_heading;
						return CONTENT_ICON_ERROR." conversion not succesfull : ".$content_id." ".$content_heading."<br />";
					}else{
						$valid_content_insert[] = $content_id." ".$content_heading;
						$countcontent = $countcontent + 1;

						list($thenewcontent_id, $thenewcontent_heading) = $sql3 -> db_Fetch();
						
						convert_comments($content_id, $thenewcontent_id);
						convert_rating($content_id, $thenewcontent_id);

						return CONTENT_ICON_OK." conversion succesfull : ".$content_id." ".$thenewcontent_heading."<br />";
					}
}
*/



//function to insert default preferences for a main parent
function insert_default_prefs($id){
		global $aa;
		unset($content_pref, $tmp);

		$content_pref = $aa -> ContentDefaultPrefs($content_main_id);
		$tmp = addslashes(serialize($content_pref));
		if(!is_object($sql4)){ $sql4 = new db; }
		$sql4 -> db_Update($plugintable, "content_pref='$tmp' WHERE content_id='$content_main_id' ");
}

//function to convert comments
function convert_comments($oldid, $newid){
		global $sql, $plugintable;

		if(!is_object($sql7)){ $sql7 = new db; }
		//check if comments present, if so, convert those to new content item id's								
		$numc = $sql7 -> db_Count("comments", "(*)", "WHERE comment_type = '1' AND comment_item_id = '".$oldid."' ");
		if($numc > 0){
			$sql7 -> db_Update("comments", "comment_item_id = '".$newid."', comment_type = '".$plugintable."' WHERE comment_item_id = '".$oldid."' ");
		}
}

//function to convert rating
function convert_rating($oldid, $newid){
		global $sql, $plugintable;

		if(!is_object($sql8)){ $sql8 = new db; }
		//check if rating present, if so, convert those to new content item id's		
		$numr = $sql8 -> db_Count("rate", "(*)", "WHERE rate_itemid = '".$oldid."' AND (rate_table = 'content' || rate_table = 'article' || rate_table = 'review') ");
		if($numr > 0){
			$sql8 -> db_Update("rate", "rate_table = '".$plugintable."', rate_itemid = '".$newid."' WHERE rate_itemid = '".$oldid."' ");
		}
}



require_once(e_ADMIN."footer.php");

?>