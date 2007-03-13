<?php
if (!defined('e107_INIT')) { exit; }
include_once(e_HANDLER.'shortcode_handler.php');
$content_shortcodes = $tp -> e_sc -> parse_scbatch(__FILE__);
/*

SC_BEGIN CONTENT_NEXTPREV
global $CONTENT_NEXTPREV;
return $CONTENT_NEXTPREV;
SC_END

// CONTENT_TYPE_TABLE ------------------------------------------------
SC_BEGIN CONTENT_TYPE_TABLE_TOTAL
global $contenttotal;
return $contenttotal." ".($contenttotal == 1 ? CONTENT_LAN_53 : CONTENT_LAN_54);
SC_END

SC_BEGIN CONTENT_TYPE_TABLE_HEADING
global $contenttotal, $row, $tp;
$row['content_heading'] = $tp -> toHTML($row['content_heading'], TRUE, "emotes_off, no_make_clickable");
return ($contenttotal != "0" ? "<a href='".e_SELF."?cat.".$row['content_id']."'>".$row['content_heading']."</a>" : $row['content_heading'] );
SC_END

SC_BEGIN CONTENT_TYPE_TABLE_LINK
global $row, $tp;
$text = "
[<a href='".e_PLUGIN."content/content.php?cat.list.".$row['content_id']."'>".CONTENT_TYPE_LAN_0."</a>] 
[<a href='".e_PLUGIN."content/content.php?author.list.".$row['content_id']."'>".CONTENT_TYPE_LAN_1."</a>] 
[<a href='".e_PLUGIN."content/content.php?list.".$row['content_id']."'>".CONTENT_TYPE_LAN_2."</a>] 
[<a href='".e_PLUGIN."content/content.php?top.".$row['content_id']."'>".CONTENT_TYPE_LAN_3."</a>] 
[<a href='".e_PLUGIN."content/content.php?score.".$row['content_id']."'>".CONTENT_TYPE_LAN_4."</a>] 
[<a href='".e_PLUGIN."content/content.php?recent.".$row['content_id']."'>".CONTENT_TYPE_LAN_5."</a>]";
return $text;
SC_END


SC_BEGIN CONTENT_TYPE_TABLE_SUBHEADING
global $row, $tp;
$row['content_subheading'] = $tp -> toHTML($row['content_subheading'], TRUE, "emotes_off, no_make_clickable");
return ($row['content_subheading'] ? $row['content_subheading'] : "");
SC_END

SC_BEGIN CONTENT_TYPE_TABLE_ICON
global $row, $aa, $content_cat_icon_path_large, $content_pref;
$qry = "cat.".$row['content_id'];
return $aa -> getIcon("catlarge", $row['content_icon'], $content_cat_icon_path_large, $qry, "", $content_pref["content_blank_caticon"]);
SC_END

// CONTENT_TYPE_TABLE_MANAGER ------------------------------------------------
SC_BEGIN CONTENT_TYPE_TABLE_MANAGER_ICON
global $plugindir;
return "<a href='".$plugindir."content_manager.php'>".CONTENT_ICON_CONTENTMANAGER."</a>";
SC_END

SC_BEGIN CONTENT_TYPE_TABLE_MANAGER_HEADING
global $plugindir;
return "<a href='".$plugindir."content_manager.php'>".CONTENT_LAN_67."</a>";
SC_END

// CONTENT_TOP_TABLE ------------------------------------------------
SC_BEGIN CONTENT_TOP_TABLE_HEADING
global $row;
return "<a href='".e_SELF."?content.".$row['content_id']."'>".$row['content_heading']."</a>";
SC_END

SC_BEGIN CONTENT_TOP_TABLE_ICON
global $aa, $row, $content_pref, $content_icon_path;
if($content_pref["content_top_icon"]){
	$width = (isset($content_pref["content_upload_icon_size"]) && $content_pref["content_upload_icon_size"] ? $content_pref["content_upload_icon_size"] : "100");
	$width = (isset($content_pref["content_top_icon_width"]) && $content_pref["content_top_icon_width"] ? $content_pref["content_top_icon_width"] : $width);
	return $aa -> getIcon("item", $row['content_icon'], $content_icon_path, "content.".$row['content_id'], $width, $content_pref["content_blank_icon"]);
}
SC_END

SC_BEGIN CONTENT_TOP_TABLE_AUTHOR
global $CONTENT_TOP_TABLE_AUTHOR;
return $CONTENT_TOP_TABLE_AUTHOR;
SC_END

SC_BEGIN CONTENT_TOP_TABLE_RATING
global $row, $imode;
$row['rate_avg'] = round($row['rate_avg'], 1);
$row['rate_avg'] = (strlen($row['rate_avg'])>1 ? $row['rate_avg'] : $row['rate_avg'].".0");
$tmp = explode(".", $row['rate_avg']);
$rating = "";
$rating .= $row['rate_avg']." ";
for($c=1; $c<= $tmp[0]; $c++){
	$rating .= "<img src='".e_IMAGE."packs/".$imode."/rate/box.png' alt='' style='border:0; height:8px; vertical-align:middle' />";
}
if($tmp[0] < 10){
	for($c=9; $c>=$tmp[0]; $c--){
		$rating .= "<img src='".e_IMAGE."packs/".$imode."/rate/empty.png' alt='' style='border:0; height:8px; vertical-align:middle' />";
	}
}
$rating .= "<img src='".e_IMAGE."packs/".$imode."/rate/boxend.png' alt='' style='border:0; height:8px; vertical-align:middle' />";
return $rating;
SC_END

// CONTENT_SCORE_TABLE ------------------------------------------------
SC_BEGIN CONTENT_SCORE_TABLE_HEADING
global $row;
return "<a href='".e_SELF."?content.".$row['content_id']."'>".$row['content_heading']."</a>";
SC_END

SC_BEGIN CONTENT_SCORE_TABLE_ICON
global $aa, $row, $content_pref, $content_icon_path;
if(isset($content_pref["content_score_icon"]) && $content_pref["content_score_icon"]){
	$width = (isset($content_pref["content_upload_icon_size"]) && $content_pref["content_upload_icon_size"] ? $content_pref["content_upload_icon_size"] : "100");
	$width = (isset($content_pref["content_score_icon_width"]) && $content_pref["content_score_icon_width"] ? $content_pref["content_score_icon_width"] : $width);
	return $aa -> getIcon("item", $row['content_icon'], $content_icon_path, "content.".$row['content_id'], $width, $content_pref["content_blank_icon"]);
}
SC_END

SC_BEGIN CONTENT_SCORE_TABLE_AUTHOR
global $CONTENT_SCORE_TABLE_AUTHOR;
return $CONTENT_SCORE_TABLE_AUTHOR;
SC_END

SC_BEGIN CONTENT_SCORE_TABLE_SCORE
global $row;
$score = $row['content_score'];
$height = "height:8px;";
$img = "";
$img .= "<img src='".e_PLUGIN."content/images/score_end.png' alt='' style='$height width:1px; border:0;' />";
$img .= "<img src='".e_PLUGIN."content/images/score.png' alt='' style='$height width:".$score."px; border:0;' />";
$img .= "<img src='".e_PLUGIN."content/images/score_end.png' alt='' style='$height width:1px; border:0;' />";
if($score < 100){
	$empty = 100-$score;
	$img .= "<img src='".e_PLUGIN."content/images/score_empty.png' alt='' style='$height width:".$empty."px; border:0;' />";
}
$img .= "<img src='".e_PLUGIN."content/images/score_end.png' alt='' style='$height width:1px; border:0;' />";
return $score."/100 ".$img;
SC_END

// CONTENT_CONTENT_TABLEMANAGER ------------------------------------------------
SC_BEGIN CONTENT_CONTENTMANAGER_CATEGORY
global $row, $content_pref;
return "<a href='".e_SELF."?content.".$row['content_id']."'>".$row['content_heading']."</a>";
SC_END

SC_BEGIN CONTENT_CONTENTMANAGER_CATEGORY_SUBHEADING
global $row, $tp;
return $tp->toHTML($row['content_subheading'], TRUE);
SC_END

SC_BEGIN CONTENT_CONTENTMANAGER_ICONNEW
global $row, $content_pref;
if( (isset($content_pref["content_manager_personal"]) && check_class($content_pref["content_manager_personal"])) || (isset($content_pref["content_manager_category"]) && check_class($content_pref["content_manager_category"])) || (isset($content_pref["content_manager_submit"]) && check_class($content_pref["content_manager_submit"])) ){

	//if(getperms('0')){
	//	return "<a href='".e_SELF."?content.create.".$row['content_id']."'>".CONTENT_MANAGER_LAN_1."</a> | <a href='".e_SELF."?content.submit.".$row['content_id']."'>".CONTENT_MANAGER_LAN_4."</a>";
	//}

	if( (isset($content_pref["content_manager_personal"]) && check_class($content_pref["content_manager_personal"])) || (isset($content_pref["content_manager_category"]) && check_class($content_pref["content_manager_category"])) ){
		return "<a href='".e_SELF."?content.create.".$row['content_id']."'>".CONTENT_MANAGER_LAN_1."</a>";
	}elseif( isset($content_pref["content_manager_submit"]) && check_class($content_pref["content_manager_submit"]) ){
		return "<a href='".e_SELF."?content.submit.".$row['content_id']."'>".CONTENT_MANAGER_LAN_4."</a>";
	}
}
SC_END

SC_BEGIN CONTENT_CONTENTMANAGER_ICONEDIT
global $row, $content_pref;
if( (isset($content_pref["content_manager_personal"]) && check_class($content_pref["content_manager_personal"])) || (isset($content_pref["content_manager_category"]) && check_class($content_pref["content_manager_category"])) ){
	return "<a href='".e_SELF."?content.".$row['content_id']."'>".CONTENT_MANAGER_LAN_2."</a>";
}
SC_END

SC_BEGIN CONTENT_CONTENTMANAGER_ICONSUBM
global $row, $content_pref, $plugintable;
if(isset($content_pref["content_manager_approve"]) && check_class($content_pref["content_manager_approve"])){
	if(!is_object($sqls)){ $sqls = new db; }
	$num = $sqls -> db_Count($plugintable, "(*)", "WHERE content_refer = 'sa' AND content_parent='".intval($row['content_id'])."' ");
	return "<a href='".e_SELF."?content.approve.".$row['content_id']."'>".CONTENT_MANAGER_LAN_3." (".$num.")</a>";
}
SC_END


// CONTENT_AUTHOR_TABLE ------------------------------------------------
SC_BEGIN CONTENT_AUTHOR_TABLE_NAME
global $authordetails, $i, $row;
$name = ($authordetails[$i][1] == "" ? "... ".CONTENT_LAN_29." ..." : $authordetails[$i][1]);
return "<a href='".e_SELF."?author.".$row['content_id']."'>".$name."</a>";
SC_END

SC_BEGIN CONTENT_AUTHOR_TABLE_ICON
global $row;
return "<a href='".e_SELF."?author.".$row['content_id']."'>".CONTENT_ICON_AUTHORLIST."</a>";
SC_END

SC_BEGIN CONTENT_AUTHOR_TABLE_TOTAL
global $totalcontent, $content_pref;
if($content_pref["content_author_amount"]){
	return $totalcontent." ".($totalcontent==1 ? CONTENT_LAN_53 : CONTENT_LAN_54);
}
SC_END

SC_BEGIN CONTENT_AUTHOR_TABLE_LASTITEM
global $gen, $row, $content_pref;
if($content_pref["content_author_lastitem"]){
if(!is_object($gen)){ $gen = new convert; }
	$CONTENT_AUTHOR_TABLE_LASTITEM = preg_replace("# -.*#", "", $gen -> convert_date($row['content_datestamp'], "short"));
	$CONTENT_AUTHOR_TABLE_LASTITEM .= " : <a href='".e_SELF."?content.".$row['content_id']."'>".$row['content_heading']."</a>";
	return $CONTENT_AUTHOR_TABLE_LASTITEM;
}
SC_END

// CONTENT_CAT_TABLE ------------------------------------------------
SC_BEGIN CONTENT_CAT_TABLE_INFO_PRE
global $CONTENT_CAT_TABLE_INFO_PRE;
if($CONTENT_CAT_TABLE_INFO_PRE === TRUE){
	$CONTENT_CAT_TABLE_INFO_PRE = " ";
	return $CONTENT_CAT_TABLE_INFO_PRE;
}
SC_END
SC_BEGIN CONTENT_CAT_TABLE_INFO_POST
global $CONTENT_CAT_TABLE_INFO_POST;
if($CONTENT_CAT_TABLE_INFO_POST === TRUE){
	$CONTENT_CAT_TABLE_INFO_POST = " ";
	return $CONTENT_CAT_TABLE_INFO_POST;
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_ICON
global $aa, $row, $content_pref, $content_cat_icon_path_large;
if(isset($content_pref["content_catall_icon"]) && $content_pref["content_catall_icon"]){
	$qry = "cat.".$row['content_id'];
	return $aa -> getIcon("catlarge", $row['content_icon'], $content_cat_icon_path_large, $qry, "", $content_pref["content_blank_caticon"]);
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_HEADING
global $row, $tp;
return "<a href='".e_SELF."?cat.".$row['content_id']."'>".$tp -> toHTML($row['content_heading'], TRUE, "")."</a>";
SC_END

SC_BEGIN CONTENT_CAT_TABLE_AMOUNT
global $row, $totalitems, $content_pref;
if(isset($content_pref["content_catall_amount"]) && $content_pref["content_catall_amount"]){
	return $totalitems." ".($totalitems == "1" ? CONTENT_LAN_53 : CONTENT_LAN_54);
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_SUBHEADING
global $row, $tp, $content_pref;
if(isset($content_pref["content_catall_subheading"]) && $content_pref["content_catall_subheading"]){
	return ($row['content_subheading'] ? $tp -> toHTML($row['content_subheading'], TRUE, "") : "");
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_DATE
global $gen, $row, $content_pref;
if(isset($content_pref["content_catall_date"]) && $content_pref["content_catall_date"]){
	if(!is_object($gen)){ $gen = new convert; }
	$datestamp = preg_replace("# -.*#", "", $gen -> convert_date($row['content_datestamp'], "long"));
	return ($datestamp != "" ? $datestamp : "");
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_AUTHORDETAILS
global $CONTENT_CAT_TABLE_AUTHORDETAILS;
return $CONTENT_CAT_TABLE_AUTHORDETAILS;
SC_END

SC_BEGIN CONTENT_CAT_TABLE_EPICONS
global $row, $tp, $content_pref;
$EPICONS = "";
if($row['content_pe'] && isset($content_pref["content_catall_peicon"]) && $content_pref["content_catall_peicon"]){
	$EPICONS = $tp -> parseTemplate("{EMAIL_ITEM=".CONTENT_LAN_69." ".CONTENT_LAN_72."^plugin:content.".$row['content_id']."}");
	$EPICONS .= " ".$tp -> parseTemplate("{PRINT_ITEM=".CONTENT_LAN_70." ".CONTENT_LAN_72."^plugin:content.".$row['content_id']."}");
	$EPICONS .= " ".$tp -> parseTemplate("{PDF=".CONTENT_LAN_76." ".CONTENT_LAN_71."^plugin:content.".$row['content_id']."}");
	return $EPICONS;
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_COMMENT
global $row, $comment_total, $content_pref, $plugintable;
if($row['content_comment'] && isset($content_pref["content_catall_comment"]) && $content_pref["content_catall_comment"]){
	$sqlc = new db;
	$comment_total = $sqlc -> db_Select("comments", "*",  "comment_item_id='".$row['content_id']."' AND comment_type='".$plugintable."' AND comment_pid='0' ");
	return "<a href='".e_SELF."?cat.".$row['content_id'].".comment'>".CONTENT_LAN_57." ".$comment_total."</a>";
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_TEXT
global $row, $tp, $content_pref;
if($row['content_text'] && isset($content_pref["content_catall_text"]) && $content_pref["content_catall_text"] && ($content_pref["content_catall_text_char"] > 0 || $content_pref["content_catall_text_char"] == 'all')){
	if($content_pref["content_catall_text_char"] == 'all'){
		$CONTENT_CAT_TABLE_TEXT = $row['content_text'];
	}else{
		$rowtext = preg_replace("/\[newpage.*?]/si", " ", $row['content_text']);
		$rowtext = $tp->toHTML($rowtext, TRUE, "nobreak");
		$rowtext = strip_tags($rowtext);
		$words = explode(" ", $rowtext);
		$CONTENT_CAT_TABLE_TEXT = implode(" ", array_slice($words, 0, $content_pref["content_catall_text_char"]));
		if($content_pref["content_catall_text_link"]){
			$CONTENT_CAT_TABLE_TEXT .= " <a href='".e_SELF."?cat.".$row['content_id']."'>".$content_pref["content_catall_text_post"]."</a>";
		}else{
			$CONTENT_CAT_TABLE_TEXT .= " ".$content_pref["content_catall_text_post"];
		}
	}
	return $CONTENT_CAT_TABLE_TEXT;
}
SC_END

SC_BEGIN CONTENT_CAT_TABLE_RATING
global $row, $rater, $content_pref, $plugintable;
if($row['content_rate'] && isset($content_pref["content_catall_rating"]) && $content_pref["content_catall_rating"]){
	return $rater->composerating($plugintable, $row['content_id'], $enter=TRUE, $userid=FALSE);
}
SC_END

// CONTENT_CAT_LIST_TABLE ------------------------------------------------
SC_BEGIN CONTENT_CAT_LIST_TABLE_INFO_PRE
global $CONTENT_CAT_LIST_TABLE_INFO_PRE;
if($CONTENT_CAT_LIST_TABLE_INFO_PRE === TRUE){
	$CONTENT_CAT_LIST_TABLE_INFO_PRE = " ";
	return $CONTENT_CAT_LIST_TABLE_INFO_PRE;
}
SC_END
SC_BEGIN CONTENT_CAT_LIST_TABLE_INFO_POST
global $CONTENT_CAT_LIST_TABLE_INFO_POST;
if($CONTENT_CAT_LIST_TABLE_INFO_POST === TRUE){
	$CONTENT_CAT_LIST_TABLE_INFO_POST = " ";
	return $CONTENT_CAT_LIST_TABLE_INFO_POST;
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_ICON
global $aa, $row, $content_pref, $content_cat_icon_path_large;
if(isset($content_pref["content_cat_icon"]) && $content_pref["content_cat_icon"]){
	return $aa -> getIcon("catlarge", $row['content_icon'], $content_cat_icon_path_large, "", "", $content_pref["content_blank_caticon"]);
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_HEADING
global $tp, $row;
return "<a href='".e_SELF."?cat.".$row['content_id'].".view'>".$tp -> toHTML($row['content_heading'], TRUE, "")."</a>";
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_SUMMARY
global $tp, $row;
return ($row['content_summary'] ? $tp -> toHTML($row['content_summary'], TRUE, "") : "");
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_TEXT
global $tp, $row, $content_pref;
if($row['content_text'] && isset($content_pref["content_cat_text"]) && $content_pref["content_cat_text"] && ($content_pref["content_cat_text_char"] > 0 || $content_pref["content_cat_text_char"] == 'all')){
	if($content_pref["content_cat_text_char"] == 'all'){
		$CONTENT_CAT_LIST_TABLE_TEXT = $tp->toHTML($row['content_text'], TRUE, "constants");
	}else{
		$rowtext = preg_replace("/\[newpage.*?]/si", " ", $row['content_text']);
		$rowtext = $tp->toHTML($rowtext, TRUE, "nobreak, constants");
		$rowtext = strip_tags($rowtext);
		$words = explode(" ", $rowtext);
		$CONTENT_CAT_LIST_TABLE_TEXT = implode(" ", array_slice($words, 0, $content_pref["content_cat_text_char"]));
		if($content_pref["content_cat_text_link"]){
			$CONTENT_CAT_LIST_TABLE_TEXT .= " <a href='".e_SELF."?cat.".$row['content_id'].".view'>".$content_pref["content_cat_text_post"]."</a>";
		}else{
			$CONTENT_CAT_LIST_TABLE_TEXT .= " ".$content_pref["content_cat_text_post"];
		}
	}
	return $CONTENT_CAT_LIST_TABLE_TEXT;
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_AMOUNT
global $row, $content_pref, $totalparent;
if(isset($content_pref["content_cat_amount"]) && $content_pref["content_cat_amount"]){
	return $totalparent." ".($totalparent == "1" ? CONTENT_LAN_53 : CONTENT_LAN_54);
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_SUBHEADING
global $tp, $row, $content_pref;
if(isset($content_pref["content_cat_subheading"]) && $content_pref["content_cat_subheading"]){
	return ($row['content_subheading'] ? $tp -> toHTML($row['content_subheading'], TRUE, "") : "");
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_DATE
global $row, $gen, $content_pref;
if(isset($content_pref["content_cat_date"]) && $content_pref["content_cat_date"]){
	if(!is_object($gen)){ $gen = new convert; }
	$datestamp = preg_replace("# -.*#", "", $gen -> convert_date($row['content_datestamp'], "long"));
	return ($datestamp != "" ? $datestamp : "");
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_AUTHORDETAILS
global $CONTENT_CAT_LIST_TABLE_AUTHORDETAILS;
return $CONTENT_CAT_LIST_TABLE_AUTHORDETAILS;
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_EPICONS
global $row, $tp, $qs, $content_pref;
$EPICONS = "";
if( (isset($content_pref["content_cat_peicon"]) && $content_pref["content_cat_peicon"] && $row['content_pe']) || (isset($content_pref["content_cat_peicon_all"]) && $content_pref["content_cat_peicon_all"])){
	$EPICONS = $tp -> parseTemplate("{EMAIL_ITEM=".CONTENT_LAN_69." ".CONTENT_LAN_72."^plugin:content.$qs[1]}");
	$EPICONS .= " ".$tp -> parseTemplate("{PRINT_ITEM=".CONTENT_LAN_70." ".CONTENT_LAN_72."^plugin:content.$qs[1]}");
	$EPICONS .= " ".$tp -> parseTemplate("{PDF=".CONTENT_LAN_76." ".CONTENT_LAN_71."^plugin:content.$qs[1]}");
	return $EPICONS;
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_COMMENT
global $qs, $row, $comment_total, $content_pref, $sql, $plugintable;
if($row['content_comment'] && isset($content_pref["content_cat_comment"]) && $content_pref["content_cat_comment"]){
	$comment_total = $sql -> db_Count("comments", "(*)",  "WHERE comment_item_id='".intval($qs[1])."' AND comment_type='".$plugintable."' AND comment_pid='0' ");
	return "<a href='".e_SELF."?cat.".$qs[1].".comment'>".CONTENT_LAN_57." ".$comment_total."</a>";
}
SC_END

SC_BEGIN CONTENT_CAT_LIST_TABLE_RATING
global $row, $rater, $content_pref, $plugintable;
if( (isset($content_pref["content_cat_rating_all"]) && $content_pref["content_cat_rating_all"]) || (isset($content_pref["content_cat_rating"]) && $content_pref["content_cat_rating"] && $row['content_rate'])){
	return $rater->composerating($plugintable, $row['content_id'], $enter=TRUE, $userid=FALSE);
}
SC_END

// CONTENT_CAT_LISTSUB ------------------------------------------------
SC_BEGIN CONTENT_CAT_LISTSUB_TABLE_ICON
global $aa, $row, $content_pref, $content_cat_icon_path_small;
if(isset($content_pref["content_catsub_icon"]) && $content_pref["content_catsub_icon"]){
	return $aa -> getIcon("catsmall", $row['content_icon'], $content_cat_icon_path_small, "cat.".$row['content_id'], "", $content_pref["content_blank_caticon"]);
}
SC_END

SC_BEGIN CONTENT_CAT_LISTSUB_TABLE_HEADING
global $tp, $row;
return "<a href='".e_SELF."?cat.".$row['content_id']."'>".$tp -> toHTML($row['content_heading'], TRUE, "")."</a>";
SC_END

SC_BEGIN CONTENT_CAT_LISTSUB_TABLE_AMOUNT
global $row, $content_pref, $totalsubcat;
if(isset($content_pref["content_catsub_amount"]) && $content_pref["content_catsub_amount"]){
	return $totalsubcat." ".($totalsubcat == "1" ? CONTENT_LAN_53 : CONTENT_LAN_54);
}
SC_END

SC_BEGIN CONTENT_CAT_LISTSUB_TABLE_SUBHEADING
global $row, $tp, $content_pref;
if(isset($content_pref["content_catsub_subheading"]) && $content_pref["content_catsub_subheading"]){
	return ($row['content_subheading'] ? $tp -> toHTML($row['content_subheading'], TRUE, "") : "");
}
SC_END

// CONTENT_SEARCH_TABLE ------------------------------------------------
SC_BEGIN CONTENT_SEARCH_TABLE_SELECT
global $CONTENT_SEARCH_TABLE_SELECT;
return $CONTENT_SEARCH_TABLE_SELECT;
SC_END

SC_BEGIN CONTENT_SEARCH_TABLE_ORDER
global $CONTENT_SEARCH_TABLE_ORDER;
return $CONTENT_SEARCH_TABLE_ORDER;
SC_END

SC_BEGIN CONTENT_SEARCH_TABLE_KEYWORD
global $CONTENT_SEARCH_TABLE_KEYWORD;
return $CONTENT_SEARCH_TABLE_KEYWORD;
SC_END

// CONTENT_SEARCHRESULT_TABLE ------------------------------------------------
SC_BEGIN CONTENT_SEARCHRESULT_TABLE_ICON
global $aa, $row, $content_icon_path, $content_pref;
$width = (isset($content_pref["content_upload_icon_size"]) && $content_pref["content_upload_icon_size"] ? $content_pref["content_upload_icon_size"] : "100");
return $aa -> getIcon("item", $row['content_icon'], $content_icon_path, "content.".$row['content_id'], $width, $content_pref["content_blank_icon"]);
SC_END

SC_BEGIN CONTENT_SEARCHRESULT_TABLE_HEADING
global $row, $tp;
return ($row['content_heading'] ? "<a href='".e_SELF."?content.".$row['content_id']."'>".$tp -> toHTML($row['content_heading'], TRUE, "")."</a>" : "");
SC_END

SC_BEGIN CONTENT_SEARCHRESULT_TABLE_SUBHEADING
global $row, $tp;
return ($row['content_subheading'] ? $tp -> toHTML($row['content_subheading'], TRUE, "") : "");
SC_END

SC_BEGIN CONTENT_SEARCHRESULT_TABLE_AUTHORDETAILS
global $aa, $row;
$authordetails = $aa -> getAuthor($row['content_author']);
$CONTENT_SEARCHRESULT_TABLE_AUTHORDETAILS = $authordetails[1];
if(USER){
	if(is_numeric($authordetails[3])){
		$CONTENT_SEARCHRESULT_TABLE_AUTHORDETAILS .= " <a href='".e_BASE."user.php?id.".$authordetails[0]."' title='".CONTENT_LAN_40."'>".CONTENT_ICON_USER."</a>";
	}else{
		$CONTENT_SEARCHRESULT_TABLE_AUTHORDETAILS .= " ".CONTENT_ICON_USER;
	}
}else{
	$CONTENT_SEARCHRESULT_TABLE_AUTHORDETAILS .= " ".CONTENT_ICON_USER;
}
$CONTENT_SEARCHRESULT_TABLE_AUTHORDETAILS .= " <a href='".e_SELF."?author.".$row['content_id']."' title='".CONTENT_LAN_39."'>".CONTENT_ICON_AUTHORLIST."</a>";
return $CONTENT_SEARCHRESULT_TABLE_AUTHORDETAILS;
SC_END

SC_BEGIN CONTENT_SEARCHRESULT_TABLE_DATE
global $gen, $row;
$datestamp = preg_replace("# -.*#", "", $gen -> convert_date($row['content_datestamp'], "short"));
return $datestamp;
SC_END

SC_BEGIN CONTENT_SEARCHRESULT_TABLE_TEXT
global $row, $tp;
return ($row['content_text'] ? $tp -> toHTML($row['content_text'], TRUE, "") : "");
SC_END

// CONTENT_RECENT_TABLE ------------------------------------------------
SC_BEGIN CONTENT_RECENT_TABLE_INFOPRE
global $CONTENT_RECENT_TABLE_INFOPRE;
if($CONTENT_RECENT_TABLE_INFOPRE === TRUE){
	$CONTENT_RECENT_TABLE_INFOPRE = " ";
	return $CONTENT_RECENT_TABLE_INFOPRE;
}
SC_END
SC_BEGIN CONTENT_RECENT_TABLE_INFOPOST
global $CONTENT_RECENT_TABLE_INFOPOST;
if($CONTENT_RECENT_TABLE_INFOPOST === TRUE){
	$CONTENT_RECENT_TABLE_INFOPOST = " ";
	return $CONTENT_RECENT_TABLE_INFOPOST;
}
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_ICON
global $aa, $row, $content_icon_path, $content_pref;
if(isset($content_pref["content_list_icon"]) && $content_pref["content_list_icon"]){
	$width = (isset($content_pref["content_upload_icon_size"]) && $content_pref["content_upload_icon_size"] ? $content_pref["content_upload_icon_size"] : "100");
	return $aa -> getIcon("item", $row['content_icon'], $content_icon_path, "content.".$row['content_id'], $width, $content_pref["content_blank_icon"]);
}
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_HEADING
global $row, $tp;
return ($row['content_heading'] ? "<a href='".e_SELF."?content.".$row['content_id']."'>".$tp->toHTML($row['content_heading'], TRUE, "")."</a>" : "");
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_SUBHEADING
global $tp, $content_pref, $qs, $row;
if (isset($content_pref["content_list_subheading"]) && $content_pref["content_list_subheading"] && $row['content_subheading'] && $content_pref["content_list_subheading_char"] && $content_pref["content_list_subheading_char"] != "" && $content_pref["content_list_subheading_char"] != "0"){
	if(strlen($row['content_subheading']) > $content_pref["content_list_subheading_char"]) {
		$row['content_subheading'] = substr($row['content_subheading'], 0, $content_pref["content_list_subheading_char"]).$content_pref["content_list_subheading_post"];
	}
	$CONTENT_RECENT_TABLE_SUBHEADING = ($row['content_subheading'] != "" && $row['content_subheading'] != " " ? $row['content_subheading'] : "");
}else{
	$CONTENT_RECENT_TABLE_SUBHEADING = ($row['content_subheading'] ? $row['content_subheading'] : "");
}
return $tp->toHTML($CONTENT_RECENT_TABLE_SUBHEADING, TRUE, "");
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_SUMMARY
global $content_pref, $tp, $row;
if (isset($content_pref["content_list_summary"]) && $content_pref["content_list_summary"]){
	if($row['content_summary'] && $content_pref["content_list_summary_char"] && $content_pref["content_list_summary_char"] != "" && $content_pref["content_list_summary_char"] != "0"){
		if(strlen($row['content_summary']) > $content_pref["content_list_summary_char"]) {
			$row['content_summary'] = substr($row['content_summary'], 0, $content_pref["content_list_summary_char"]).$content_pref["content_list_summary_post"];
		}
		$CONTENT_RECENT_TABLE_SUMMARY = ($row['content_summary'] != "" && $row['content_summary'] != " " ? $row['content_summary'] : "");
	}else{
		$CONTENT_RECENT_TABLE_SUMMARY = ($row['content_summary'] ? $row['content_summary'] : "");
	}
	return $tp->toHTML($CONTENT_RECENT_TABLE_SUMMARY, TRUE, "");
}
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_TEXT
global $content_pref, $row, $tp;
if(isset($content_pref["content_list_text"]) && $content_pref["content_list_text"] && $content_pref["content_list_text_char"] > 0){
	$rowtext = preg_replace("/\[newpage.*?]/si", " ", $row['content_text']);
	$rowtext = $tp->toHTML($rowtext, TRUE, "nobreak");
	$rowtext = strip_tags($rowtext);
	$words = explode(" ", $rowtext);
	$CONTENT_RECENT_TABLE_TEXT = implode(" ", array_slice($words, 0, $content_pref["content_list_text_char"]));
	if($CONTENT_RECENT_TABLE_TEXT){
		if($content_pref["content_list_text_link"]){
			$CONTENT_RECENT_TABLE_TEXT .= " <a href='".e_SELF."?content.".$row['content_id']."'>".$content_pref["content_list_text_post"]."</a>";
		}else{
			$CONTENT_RECENT_TABLE_TEXT .= " ".$content_pref["content_list_text_post"];
		}
	}
}
return $CONTENT_RECENT_TABLE_TEXT;
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_DATE
global $content_pref, $row;
if(isset($content_pref["content_list_date"]) && $content_pref["content_list_date"]){
	$datestyle = ($content_pref["content_list_datestyle"] ? $content_pref["content_list_datestyle"] : "%d %b %Y");
	return strftime($datestyle, $row['content_datestamp']);
}
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_EPICONS
global $tp, $content_pref, $row;
$CONTENT_RECENT_TABLE_EPICONS = "";
if(isset($content_pref["content_list_peicon"]) && $content_pref["content_list_peicon"]){
	if($row['content_pe'] || isset($content_pref["content_list_peicon_all"]) && $content_pref["content_list_peicon_all"]){
		$CONTENT_RECENT_TABLE_EPICONS = $tp -> parseTemplate("{EMAIL_ITEM=".CONTENT_LAN_69." ".CONTENT_LAN_71."^plugin:content.".$row['content_id']."}");
		$CONTENT_RECENT_TABLE_EPICONS .= " ".$tp -> parseTemplate("{PRINT_ITEM=".CONTENT_LAN_70." ".CONTENT_LAN_71."^plugin:content.".$row['content_id']."}");
		$CONTENT_RECENT_TABLE_EPICONS .= " ".$tp -> parseTemplate("{PDF=".CONTENT_LAN_76." ".CONTENT_LAN_71."^plugin:content.".$row['content_id']."}");
	}
}
return $CONTENT_RECENT_TABLE_EPICONS;
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_AUTHORDETAILS
global $CONTENT_RECENT_TABLE_AUTHORDETAILS;
return $CONTENT_RECENT_TABLE_AUTHORDETAILS;
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_EDITICON
global $content_pref, $row, $plugindir;
if(ADMIN && getperms("P") && isset($content_pref["content_list_editicon"]) && $content_pref["content_list_editicon"]){
	return $CONTENT_RECENT_TABLE_EDITICON = "<a href='".$plugindir."admin_content_config.php?content.edit.".$row['content_id']."'>".CONTENT_ICON_EDIT."</a>";
}
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_REFER
global $content_pref, $row;
if($content_pref["content_log"] && $content_pref["content_list_refer"]){
	$refercounttmp = explode("^", $row['content_refer']);
	$CONTENT_RECENT_TABLE_REFER = ($refercounttmp[0] ? $refercounttmp[0] : "0");
	if($CONTENT_RECENT_TABLE_REFER > 0){
		return $CONTENT_RECENT_TABLE_REFER;
	}
}
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_RATING
global $rater, $row, $content_pref, $plugintable;
if($content_pref["content_list_rating"]){
	if($content_pref["content_list_rating_all"] || $row['content_rate']){
		return $rater->composerating($plugintable, $row['content_id'], $enter=FALSE, $userid=FALSE);
	}
}
SC_END

SC_BEGIN CONTENT_RECENT_TABLE_PARENT
global $content_pref, $row, $array, $aa;
if(isset($content_pref["content_list_parent"]) && $content_pref["content_list_parent"]){
	return $aa -> getCrumbItem($row['content_parent'], $array);
}
SC_END

// CONTENT_ARCHIVE_TABLE ------------------------------------------------
SC_BEGIN CONTENT_ARCHIVE_TABLE_LETTERS
global $content_pref;
if($content_pref["content_archive_letterindex"]){
	return $CONTENT_ARCHIVE_TABLE_LETTERS;
}
SC_END

SC_BEGIN CONTENT_ARCHIVE_TABLE_HEADING
global $row;
return "<a href='".e_SELF."?content.".$row['content_id']."'>".$row['content_heading']."</a>";
SC_END

SC_BEGIN CONTENT_ARCHIVE_TABLE_DATE
global $row, $content_pref;
if(isset($content_pref["content_archive_date"]) && $content_pref["content_archive_date"]){
	$datestyle = ($content_pref["content_archive_datestyle"] ? $content_pref["content_archive_datestyle"] : "%d %b %Y");
	return strftime($datestyle, $row['content_datestamp']);
}
SC_END

SC_BEGIN CONTENT_ARCHIVE_TABLE_AUTHOR
global $CONTENT_ARCHIVE_TABLE_AUTHOR;
return $CONTENT_ARCHIVE_TABLE_AUTHOR;
SC_END

// CONTENT_CONTENT_TABLE ------------------------------------------------
SC_BEGIN CONTENT_CONTENT_TABLE_INFO_PRE
global $CONTENT_CONTENT_TABLE_INFO_PRE;
if($CONTENT_CONTENT_TABLE_INFO_PRE === TRUE){
	$CONTENT_CONTENT_TABLE_INFO_PRE = " ";
	return $CONTENT_CONTENT_TABLE_INFO_PRE;
}
SC_END
SC_BEGIN CONTENT_CONTENT_TABLE_INFO_POST
global $CONTENT_CONTENT_TABLE_INFO_POST;
if($CONTENT_CONTENT_TABLE_INFO_POST === TRUE){
	$CONTENT_CONTENT_TABLE_INFO_POST = " ";
	return $CONTENT_CONTENT_TABLE_INFO_POST;
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_INFO_PRE_HEADDATA
global $CONTENT_CONTENT_TABLE_INFO_PRE_HEADDATA;
if($CONTENT_CONTENT_TABLE_INFO_PRE_HEADDATA === TRUE){
	$CONTENT_CONTENT_TABLE_INFO_PRE_HEADDATA = " ";
	return $CONTENT_CONTENT_TABLE_INFO_PRE_HEADDATA;
}
SC_END
SC_BEGIN CONTENT_CONTENT_TABLE_INFO_POST_HEADDATA
global $CONTENT_CONTENT_TABLE_INFO_POST_HEADDATA;
if($CONTENT_CONTENT_TABLE_INFO_POST_HEADDATA === TRUE){
	$CONTENT_CONTENT_TABLE_INFO_POST_HEADDATA = " ";
	return $CONTENT_CONTENT_TABLE_INFO_POST_HEADDATA;
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_PARENT
global $aa, $array, $row, $content_pref;
if(isset($content_pref["content_content_parent"]) && $content_pref["content_content_parent"]){
	return $aa -> getCrumbItem($row['content_parent'], $array);
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_ICON
global $row, $aa, $content_pref, $content_icon_path;
if(isset($content_pref["content_content_icon"]) && $content_pref["content_content_icon"]){
	$width = (isset($content_pref["content_upload_icon_size"]) && $content_pref["content_upload_icon_size"] ? $content_pref["content_upload_icon_size"] : "100");
	return $aa -> getIcon("item", $row['content_icon'], $content_icon_path, "", $width, $content_pref["content_blank_icon"]);
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_HEADING
global $row, $tp;
return ($row['content_heading'] ? $tp -> toHTML($row['content_heading'], TRUE, "") : "");
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_REFER
global $sql, $qs, $content_pref, $plugintable;
if(isset($content_pref["content_content_refer"]) && $content_pref["content_content_refer"]){
	$sql -> db_Select($plugintable, "content_refer", "content_id='".intval($qs[1])."' ");
	list($content_refer) = $sql -> db_Fetch();
	$refercounttmp = explode("^", $content_refer);
	return ($refercounttmp[0] ? $refercounttmp[0] : "");
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_SUBHEADING
global $row, $tp, $content_pref;
return ($content_pref["content_content_subheading"] && $row['content_subheading'] ? $tp -> toHTML($row['content_subheading'], TRUE, "") : "");
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_COMMENT
global $cobj, $qs, $content_pref, $row, $plugintable;
if((isset($content_pref["content_content_comment"]) && $content_pref["content_content_comment"] && $row['content_comment']) || $content_pref["content_content_comment_all"] ){
	return $cobj -> count_comments($plugintable, $qs[1]);
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_DATE
global $gen, $row, $content_pref;
if(isset($content_pref["content_content_date"]) && $content_pref["content_content_date"]){
	$gen = new convert;
	$datestamp = preg_replace("# -.*#", "", $gen -> convert_date($row['content_datestamp'], "long"));
	$CONTENT_CONTENT_TABLE_DATE = ($datestamp != "" ? $datestamp : "");
	return $CONTENT_CONTENT_TABLE_DATE;
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_AUTHORDETAILS
global $CONTENT_CONTENT_TABLE_AUTHORDETAILS;
return $CONTENT_CONTENT_TABLE_AUTHORDETAILS;
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_EPICONS
global $content_pref, $row, $tp;
$CONTENT_CONTENT_TABLE_EPICONS = "";
if(($content_pref["content_content_peicon"] && $row['content_pe']) || $content_pref["content_content_peicon_all"]){
	$CONTENT_CONTENT_TABLE_EPICONS = $tp -> parseTemplate("{EMAIL_ITEM=".CONTENT_LAN_69." ".CONTENT_LAN_71."^plugin:content.".$row['content_id']."}");
	$CONTENT_CONTENT_TABLE_EPICONS .= " ".$tp -> parseTemplate("{PRINT_ITEM=".CONTENT_LAN_70." ".CONTENT_LAN_71."^plugin:content.".$row['content_id']."}");
	$CONTENT_CONTENT_TABLE_EPICONS .= " ".$tp -> parseTemplate("{PDF=".CONTENT_LAN_76." ".CONTENT_LAN_71."^plugin:content.".$row['content_id']."}");
	return $CONTENT_CONTENT_TABLE_EPICONS;
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_EDITICON
global $content_pref, $row, $plugindir;
if(ADMIN && getperms("P") && isset($content_pref["content_content_editicon"])){
	return "<a href='".$plugindir."admin_content_config.php?content.edit.".$row['content_id']."'>".CONTENT_ICON_EDIT."</a>";
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_RATING
global $content_pref, $row, $rater, $plugintable;
if(($content_pref["content_content_rating"] && $row['content_rate']) || $content_pref["content_content_rating_all"] ){
	return $rater->composerating($plugintable, $row['content_id'], $enter=TRUE, $userid=FALSE);
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_FILE
global $row, $content_file_path, $content_pref;
if($content_pref["content_content_attach"]){
	$filestmp = explode("[file]", $row['content_file']);
	foreach($filestmp as $key => $value) { 
		if($value == "") { 
			unset($filestmp[$key]); 
		} 
	} 
	$files = array_values($filestmp);
	$content_files_popup_name = str_replace("'", "", $row['content_heading']);
	$file = "";
	$filesexisting = "0";
	for($i=0;$i<count($files);$i++){
		if(file_exists($content_file_path.$files[$i])){
			$filesexisting = $filesexisting+1;
			$file .= "<a href='".$content_file_path.$files[$i]."' rel='external'>".CONTENT_ICON_FILE."</a> ";						
		}else{
			$file .= "&nbsp;";
		}
	}
	return ($filesexisting == "0" ? "" : CONTENT_LAN_41." ".($filesexisting == 1 ? CONTENT_LAN_42 : CONTENT_LAN_43)." ".$file." ");
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_SCORE
global $row;
$score = $row['content_score'];
if($score){
	$height = "height:8px;";
	$img = "";
	$img .= "<img src='".e_PLUGIN."content/images/score_end.png' alt='' style='$height width:1px; border:0;' />";
	$img .= "<img src='".e_PLUGIN."content/images/score.png' alt='' style='$height width:".$score."px; border:0;' />";
	$img .= "<img src='".e_PLUGIN."content/images/score_end.png' alt='' style='$height width:1px; border:0;' />";
	if($score < 100){
		$empty = 100-$score;
		$img .= "<img src='".e_PLUGIN."content/images/score_empty.png' alt='' style='$height width:".$empty."px; border:0;' />";
	}
	$img .= "<img src='".e_PLUGIN."content/images/score_end.png' alt='' style='$height width:1px; border:0;' />";
	return $img." ".$score;
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_SUMMARY
global $CONTENT_CONTENT_TABLE_SUMMARY;
return $CONTENT_CONTENT_TABLE_SUMMARY;
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_TEXT
global $CONTENT_CONTENT_TABLE_TEXT;
return $CONTENT_CONTENT_TABLE_TEXT;
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_IMAGES
global $row, $content_image_path, $aa, $tp, $authordetails, $content_pref;
if($content_pref["content_content_images"]){
	$authordetails = $aa -> getAuthor($row['content_author']);
	$imagestmp = explode("[img]", $row['content_image']);
	foreach($imagestmp as $key => $value) { 
		if($value == "") { 
			unset($imagestmp[$key]); 
		} 
	} 
	$images = array_values($imagestmp);
	$content_image_popup_name = $row['content_heading'];
	$CONTENT_CONTENT_TABLE_IMAGES = "";
	require_once(e_HANDLER."popup_handler.php");
	$pp = new popup;
	$gen = new convert;
	$datestamp = preg_replace("# -.*#", "", $gen -> convert_date($row['content_datestamp'], "long"));
	for($i=0;$i<count($images);$i++){		
		$oSrc = $content_image_path.$images[$i];
		$oSrcThumb = $content_image_path."thumb_".$images[$i];

		$oIconWidth = (isset($content_pref["content_upload_image_size_thumb"]) && $content_pref["content_upload_image_size_thumb"] ? $content_pref["content_upload_image_size_thumb"] : "100");
		
		$oMaxWidth = (isset($content_pref["content_upload_image_size"]) && $content_pref["content_upload_image_size"] ? $content_pref["content_upload_image_size"] : "500");
		
		$subheading	= $tp -> toHTML($row['content_subheading'], TRUE);
		$popupname	= $tp -> toHTML($content_image_popup_name, TRUE);
		$author		= $tp -> toHTML($authordetails[1], TRUE);
		$oTitle		= $popupname." ".($i+1);
		$oText		= $popupname." ".($i+1)."<br />".$subheading."<br />".$author." (".$datestamp.")";
		$CONTENT_CONTENT_TABLE_IMAGES .= $pp -> popup($oSrc, $oSrcThumb, $oIconWidth, $oMaxWidth, $oTitle, $oText);
	}
	return $CONTENT_CONTENT_TABLE_IMAGES;
}
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_CUSTOM_TAGS
global $CONTENT_CONTENT_TABLE_CUSTOM_TAGS;
return $CONTENT_CONTENT_TABLE_CUSTOM_TAGS;
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_PAGENAMES
global $CONTENT_CONTENT_TABLE_PAGENAMES;
return $CONTENT_CONTENT_TABLE_PAGENAMES;
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_NEXT_PAGE
global $CONTENT_CONTENT_TABLE_NEXT_PAGE;
return $CONTENT_CONTENT_TABLE_NEXT_PAGE;
SC_END

SC_BEGIN CONTENT_CONTENT_TABLE_PREV_PAGE
global $CONTENT_CONTENT_TABLE_PREV_PAGE;
return $CONTENT_CONTENT_TABLE_PREV_PAGE;
SC_END




// PRINT PAGE ------------------------------------------------

//content images (from uploaded area) used in the print page
SC_BEGIN CONTENT_PRINT_IMAGES
global $row, $content_image_path, $tp, $content_pref;
if($content_pref["content_content_images"]){
	$imagestmp = explode("[img]", $row['content_image']);
	foreach($imagestmp as $key => $value) { 
		if($value == "") { 
			unset($imagestmp[$key]); 
		} 
	} 
	$images = array_values($imagestmp);
	$CONTENT_PRINT_IMAGES = "";
	for($i=0;$i<count($images);$i++){		
		$oSrc = $content_image_path.$images[$i];
		$oSrcThumb = $content_image_path."thumb_".$images[$i];

		$iconwidth = (isset($content_pref["content_upload_image_size_thumb"]) && $content_pref["content_upload_image_size_thumb"] ? $content_pref["content_upload_image_size_thumb"] : "100");
		if($iconwidth){
			$style = "style='width:".$iconwidth."px;'";
		}
		
		//use $image if $thumb doesn't exist
		if(file_exists($oSrc)){
			if(!file_exists($oSrcThumb)){
				$thumb = $oSrc;
			}else{
				$thumb = $oSrcThumb;
			}
			$CONTENT_PRINT_IMAGES .= "<img src='".$thumb."' ".$style." alt='' /><br /><br />";
		}
	}
	return $CONTENT_PRINT_IMAGES;
}
SC_END


// PDF PAGE ------------------------------------------------

//content images (from uploaded area) used in the pdf creation
SC_BEGIN CONTENT_PDF_IMAGES
global $row, $content_image_path, $tp, $content_pref;
if($content_pref["content_content_images"]){
	$imagestmp = explode("[img]", $row['content_image']);
	foreach($imagestmp as $key => $value) { 
		if($value == "") { 
			unset($imagestmp[$key]); 
		} 
	} 
	$images = array_values($imagestmp);
	$CONTENT_PDF_IMAGES = "";
	for($i=0;$i<count($images);$i++){		
		$oSrc = $content_image_path.$images[$i];
		$oSrcThumb = $content_image_path."thumb_".$images[$i];

		$iconwidth = (isset($content_pref["content_upload_image_size_thumb"]) && $content_pref["content_upload_image_size_thumb"] ? $content_pref["content_upload_image_size_thumb"] : "100");
		if($iconwidth){
			$style = "style='width:".$iconwidth."px;'";
		}
		
		//use $image if $thumb doesn't exist
		if(file_exists($oSrc)){
			if(!file_exists($oSrcThumb)){
				$thumb = $oSrc;
			}else{
				$thumb = $oSrcThumb;
			}
			$thumb = $oSrc;
			$CONTENT_PDF_IMAGES .= "<img src='".$thumb."' ".$style." alt='' />";
		}
	}
	return $CONTENT_PDF_IMAGES;
}
SC_END


//##### ADMIN PAGE --------------------------------------------------

SC_BEGIN CONTENT_ID
global $row;
return $row['content_id'];
SC_END

SC_BEGIN CONTENT_CAT_ICON
global $row, $content_pref, $tp;
$content_cat_icon_path_large = $tp -> replaceConstants($content_pref["content_cat_icon_path_large"]);
$content_cat_icon_path_small = $tp -> replaceConstants($content_pref["content_cat_icon_path_small"]);
$caticon = $content_cat_icon_path_small.$row['content_icon'];
return ($row['content_icon'] ? "<img src='".$caticon."' alt='' style='vertical-align:middle' />" : "&nbsp;");
SC_END

SC_BEGIN CONTENT_ICON
global $CONTENT_ICON;
return $CONTENT_ICON;
SC_END

SC_BEGIN CONTENT_AUTHOR
global $row, $aa;
$authordetails = $aa -> getAuthor($row['content_author']);
return ($authordetails[0] != "0" ? "<a href='".e_BASE."user.php?id.".$authordetails[0]."'>".CONTENT_ICON_USER."</a>" : "")." ".$authordetails[1];
SC_END

SC_BEGIN CONTENT_HEADING
global $row, $tp;
return $tp->toHTML($row['content_heading'], TRUE, "");
SC_END

SC_BEGIN CONTENT_SUBHEADING
global $row, $tp;
return $tp->toHTML($row['content_subheading'], TRUE, "");
SC_END

SC_BEGIN CONTENT_LINK_ITEM
global $row, $plugindir;
return "<a href='".$plugindir."content.php?content.".$row['content_id']."'>".CONTENT_ICON_LINK."</a> ";
SC_END

SC_BEGIN CONTENT_LINK_CATEGORY
global $row, $plugindir;
return "<a href='".$plugindir."content.php?cat.".$row['content_id']."'>".CONTENT_ICON_LINK."</a>";
SC_END

SC_BEGIN CONTENT_LINK_OPTION
global $row;
return "<a href='".e_SELF."?option.".$row['content_id']."'>".CONTENT_ICON_OPTIONS."</a>";
SC_END

SC_BEGIN CONTENT_INHERIT
global $row, $content_pref;
return "<input type='checkbox' value='1' name='content_inherit[".$row['content_id']."]' ".(isset($content_pref['content_inherit']) && $content_pref['content_inherit']=='1' ? "checked='checked'" : "")." /><input type='hidden' name='id[".$row['content_id']."]' value='1' />";
SC_END

SC_BEGIN CONTENT_LINK_MANAGER
global $row;
return "<a href='".e_SELF."?manager.".intval($row['content_id'])."'>".CONTENT_ICON_CONTENTMANAGER_SMALL."</a>";
SC_END

SC_BEGIN CONTENT_MANAGER_PRE
global $row, $catarray, $catid;
$pre = '';
if($row['content_parent'] != "0"){
	for($b=0;$b<(count($catarray[$catid])/2)-1;$b++){
		$pre .= "_";
	}
}
return $pre;
SC_END

SC_BEGIN CONTENT_ADMIN_HTML_CLASS
global $row;
if($row['content_parent'] == "0"){
	//top level
	$class = "forumheader";
}else{
	//sub level
	$class = "forumheader3";
}
return $class;
SC_END

SC_BEGIN CONTENT_MANAGER_INHERIT
global $row, $content_pref;
return "<input type='checkbox' value='1' name='content_manager_inherit[".$row['content_id']."]' ".(isset($content_pref['content_manager_inherit']) && $content_pref['content_manager_inherit']=='1' ? "checked='checked'" : "")." /><input type='hidden' name='id[".$row['content_id']."]' value='1' />";
SC_END

SC_BEGIN CONTENT_ADMIN_MANAGER_SUBMIT
global $row, $content_pref;
return r_userclass("content_manager_submit", $content_pref["content_manager_submit"], 'off', "public,guest,nobody,member,admin,classes");
SC_END

SC_BEGIN CONTENT_ADMIN_MANAGER_APPROVE
global $row, $content_pref;
return r_userclass("content_manager_approve", $content_pref["content_manager_approve"], 'off', "public,guest,nobody,member,admin,classes");
SC_END

SC_BEGIN CONTENT_ADMIN_MANAGER_PERSONAL
global $row, $content_pref;
return r_userclass("content_manager_personal", $content_pref["content_manager_personal"], 'off', "nobody,member,admin,classes");
SC_END

SC_BEGIN CONTENT_ADMIN_MANAGER_CATEGORY
global $row, $content_pref;
return r_userclass("content_manager_category", $content_pref["content_manager_category"], 'off', "nobody,member,admin,classes");
SC_END

SC_BEGIN CONTENT_ADMIN_MANAGER_OPTIONS
global $CONTENT_ADMIN_MANAGER_OPTIONS;
return $CONTENT_ADMIN_MANAGER_OPTIONS;
SC_END

SC_BEGIN CONTENT_ORDER
global $row;
return $row['content_order'];
SC_END

SC_BEGIN CONTENT_ADMIN_CATEGORY
global $CONTENT_ADMIN_CATEGORY;
return $CONTENT_ADMIN_CATEGORY;
SC_END

SC_BEGIN CONTENT_ADMIN_OPTIONS
global $CONTENT_ADMIN_OPTIONS;
return $CONTENT_ADMIN_OPTIONS;
SC_END

SC_BEGIN CONTENT_ADMIN_BUTTON
global $CONTENT_ADMIN_BUTTON;
return $CONTENT_ADMIN_BUTTON;
SC_END

SC_BEGIN CONTENT_ADMIN_SPACER
global $CONTENT_ADMIN_SPACER;
return ($parm==true || $CONTENT_ADMIN_SPACER ? " " : "");
SC_END

SC_BEGIN CONTENT_ADMIN_FORM_TARGET
global $CONTENT_ADMIN_FORM_TARGET;
return $CONTENT_ADMIN_FORM_TARGET;
SC_END

SC_BEGIN CONTENT_ADMIN_ORDER_SELECT
global $CONTENT_ADMIN_ORDER_SELECT;
return $CONTENT_ADMIN_ORDER_SELECT;
SC_END

SC_BEGIN CONTENT_ADMIN_ORDER_UPDOWN
global $CONTENT_ADMIN_ORDER_UPDOWN;
return $CONTENT_ADMIN_ORDER_UPDOWN;
SC_END

SC_BEGIN CONTENT_ADMIN_ORDER_AMOUNT
global $CONTENT_ADMIN_ORDER_AMOUNT;
return $CONTENT_ADMIN_ORDER_AMOUNT;
SC_END

SC_BEGIN CONTENT_ADMIN_ORDER_CAT
global $CONTENT_ADMIN_ORDER_CAT;
return $CONTENT_ADMIN_ORDER_CAT;
SC_END

SC_BEGIN CONTENT_ADMIN_ORDER_CATALL
global $CONTENT_ADMIN_ORDER_CATALL;
return $CONTENT_ADMIN_ORDER_CATALL;
SC_END

SC_BEGIN CONTENT_ADMIN_LETTERINDEX
global $CONTENT_ADMIN_LETTERINDEX;
return $CONTENT_ADMIN_LETTERINDEX;
SC_END

//##### CONTENT CATEGORY CREATE FORM -------------------------

SC_BEGIN CATFORM_CATEGORY
global $CATFORM_CATEGORY;
return $CATFORM_CATEGORY;
SC_END

SC_BEGIN CATFORM_HEADING
global $row, $rs;
return $rs -> form_text("cat_heading", 90, $row['content_heading'], 250);
SC_END

SC_BEGIN CATFORM_SUBHEADING
global $row, $rs, $show;
if($show['subheading']===true){
	return $rs -> form_text("cat_subheading", 90, $row['content_subheading'], 250);
}
SC_END

SC_BEGIN CATFORM_TEXT
global $row, $rs, $show, $pref;
require_once(e_HANDLER."ren_help.php");
$insertjs = (!$pref['wysiwyg'] ? "onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'" : "");
$text = $rs -> form_textarea("cat_text", 80, 20, $row['content_text'], $insertjs)."<br />";
if (!$pref['wysiwyg']) { $text .= $rs -> form_text("helpb", 90, '', '', "helpbox")."<br />". display_help("helpb"); }
return $text;
SC_END

SC_BEGIN CATFORM_DATESTART
global $row, $rs, $show, $months, $ne_day, $ne_month, $ne_year, $current_year;
if($show['startdate']===true){
	$text = "
	".$rs -> form_select_open("ne_day")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_12, 0, "none");
	for($count=1; $count<=31; $count++){
		$text .= $rs -> form_option($count, ($ne_day == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("ne_month")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_13, 0, "none");
	for($count=1; $count<=12; $count++){
		$text .= $rs -> form_option($months[($count-1)], ($ne_month == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("ne_year")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_14, 0, "none");
	for($count=($current_year-5); $count<=$current_year; $count++){
		$text .= $rs -> form_option($count, ($ne_year == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close();
	return $text;
}
SC_END

SC_BEGIN CATFORM_DATEEND
global $row, $rs, $show, $months, $end_day, $end_month, $end_year, $current_year;
if($show['enddate']===true){
	$text = "
	".$rs -> form_select_open("end_day")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_12, 1, "none");
	for($count=1; $count<=31; $count++){
		$text .= $rs -> form_option($count, ($end_day == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("end_month")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_13, 1, "none");
	for($count=1; $count<=12; $count++){
		$text .= $rs -> form_option($months[($count-1)], ($end_month == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("end_year")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_14, 1, "none");
	for($count=($current_year-5); $count<=$current_year; $count++){
		$text .= $rs -> form_option($count, ($end_year == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close();
	return $text;
}
SC_END

SC_BEGIN CATFORM_UPLOAD
global $row, $show, $content_cat_icon_path_large, $content_cat_icon_path_small;
if($show['uploadicon']===true){
	$text='';
	if(!FILE_UPLOADS){
		$text = "<b>".CONTENT_ADMIN_ITEM_LAN_21."</b>";
	}else{
		if(!is_writable($content_cat_icon_path_large)){
			$text = "<b>".CONTENT_ADMIN_ITEM_LAN_22." ".$content_cat_icon_path_large." ".CONTENT_ADMIN_ITEM_LAN_23."</b><br />";
		}
		$text .= CONTENT_ADMIN_CAT_LAN_62."
		<input class='tbox' type='file' name='file_userfile[]'  size='58' /> 
		<input type='hidden' name='iconpathlarge' value='".$content_cat_icon_path_large."' />
		<input type='hidden' name='iconpathsmall' value='".$content_cat_icon_path_small."' />
		<input class='button' type='submit' name='uploadcaticon' value='".CONTENT_ADMIN_CAT_LAN_63."' />";
	}
	return $text;
}
SC_END

SC_BEGIN CATFORM_ICON
global $row, $rs, $show, $fl, $content_cat_icon_path_large;
if($show['selecticon']===true){
	$rejectlist = array('$.','$..','/','CVS','thumbs.db','Thumbs.db','*._$', 'index', 'null*');
	$iconlist = $fl->get_files($content_cat_icon_path_large,"",$rejectlist);
	$text = $rs -> form_text("cat_icon", 60, $row['content_icon'], 100)."
	".$rs -> form_button("button", '', CONTENT_ADMIN_CAT_LAN_8, "onclick=\"expandit('divcaticon')\"")."
	<div id='divcaticon' style='{head}; display:none'>";
	foreach($iconlist as $icon){
		$text .= "<a href=\"javascript:insertext('".$icon['fname']."','cat_icon','divcaticon')\"><img src='".$icon['path'].$icon['fname']."' style='border:0' alt='' /></a> ";
	}
	$text .= "</div>";
	return $text;
}
SC_END

SC_BEGIN CATFORM_COMMENT
global $row, $rs, $show;
if($show['comment']===true){
	return $rs -> form_radio("cat_comment", "1", ($row['content_comment'] ? "1" : "0"), "", "").CONTENT_ADMIN_ITEM_LAN_85." ".$rs -> form_radio("cat_comment", "0", ($row['content_comment'] ? "0" : "1"), "", "").CONTENT_ADMIN_ITEM_LAN_86;
}
SC_END

SC_BEGIN CATFORM_RATING
global $row, $rs, $show;
if($show['rating']===true){
	return $rs -> form_radio("cat_rate", "1", ($row['content_rate'] ? "1" : "0"), "", "").CONTENT_ADMIN_ITEM_LAN_85." ".$rs -> form_radio("cat_rate", "0", ($row['content_rate'] ? "0" : "1"), "", "").CONTENT_ADMIN_ITEM_LAN_86;
}
SC_END

SC_BEGIN CATFORM_PEICON
global $row, $rs, $show;
if($show['pe']===true){
	return $rs -> form_radio("cat_pe", "1", ($row['content_pe'] ? "1" : "0"), "", "").CONTENT_ADMIN_ITEM_LAN_85." ".$rs -> form_radio("cat_pe", "0", ($row['content_pe'] ? "0" : "1"), "", "").CONTENT_ADMIN_ITEM_LAN_86;
}
SC_END

SC_BEGIN CATFORM_VISIBILITY
global $row, $show;
if($show['visibility']===true){
	return r_userclass("cat_class",$row['content_class'], "CLASSES");
}
SC_END

//##### CONTENT CREATE FORM -------------------------

SC_BEGIN CONTENTFORM_CATEGORYSELECT
global $CONTENTFORM_CATEGORYSELECT;
return $CONTENTFORM_CATEGORYSELECT;
SC_END

SC_BEGIN CONTENTFORM_CATEGORY
global $CONTENTFORM_CATEGORY;
return $CONTENTFORM_CATEGORY;
SC_END

SC_BEGIN CONTENTFORM_HEADING
global $row, $rs;
return $rs -> form_text("content_heading", 74, $row['content_heading'], 250);
SC_END

SC_BEGIN CONTENTFORM_SUBHEADING
global $row, $rs, $show;
if($show['subheading']===true){
	return $rs -> form_text("content_subheading", 74, $row['content_subheading'], 250);
}
SC_END

SC_BEGIN CONTENTFORM_SUMMARY
global $row, $rs, $show;
if($show['summary']===true){
	return $rs -> form_textarea("content_summary", 74, 5, $row['content_summary']);
}
SC_END

SC_BEGIN CONTENTFORM_TEXT
global $row, $rs, $tp, $show, $pref;
if(e_WYSIWYG){
	$row['content_text'] = $tp->replaceConstants($row['content_text'], true);
}
require_once(e_HANDLER."ren_help.php");
$insertjs = (!e_WYSIWYG) ? "onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'": "";
$text = $rs -> form_textarea("content_text", 74, 20, $row['content_text'], $insertjs)."<br />";
if (!$pref['wysiwyg']) { $text .= $rs -> form_text("helpb", 90, '', '', "helpbox")."<br />".display_help("helpb"); }
return $text;
SC_END

SC_BEGIN CONTENTFORM_AUTHOR
global $row, $rs, $show, $content_author_name_value, $content_author_name_js, $content_author_email_value, $content_author_email_js, $content_author_id;
$text = "
<table style='width:100%; text-align:left;'>
<tr><td>".CONTENT_ADMIN_ITEM_LAN_14."</td><td>".$rs -> form_text("content_author_name", 70, $content_author_name_value, 100, "tbox", "", "", $content_author_name_js )."</td></tr>
<tr><td>".CONTENT_ADMIN_ITEM_LAN_15."</td><td>".$rs -> form_text("content_author_email", 70, $content_author_email_value, 100, "tbox", "", "", $content_author_email_js )."
".$rs -> form_hidden("content_author_id", $content_author_id)."
</td></tr></table>";
return $text;
SC_END

SC_BEGIN CONTENTFORM_DATESTART
global $row, $rs, $show, $months, $ne_day, $ne_month, $ne_year, $current_year;
if($show['startdate']===true){
	$text = "
	".$rs -> form_select_open("ne_day")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_12, 0, "none");
	for($count=1; $count<=31; $count++){
		$text .= $rs -> form_option($count, (isset($ne_day) && $ne_day == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("ne_month")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_13, 0, "none");
	for($count=1; $count<=12; $count++){
		$text .= $rs -> form_option($months[($count-1)], (isset($ne_month) && $ne_month == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("ne_year")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_14, 0, "none");
	for($count=($current_year-5); $count<=($current_year+1); $count++){
		$text .= $rs -> form_option($count, (isset($ne_year) && $ne_year == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close();
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_DATEEND
global $row, $rs, $show, $months, $end_day, $end_month, $end_year, $current_year;
if($show['enddate']===true){
	$text = "
	".$rs -> form_select_open("end_day")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_12, 0, "none");
	for($count=1; $count<=31; $count++){
		$text .= $rs -> form_option($count, (isset($end_day) && $end_day == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("end_month")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_13, 0, "none");
	for($count=1; $count<=12; $count++){
		$text .= $rs -> form_option($months[($count-1)], (isset($end_month) && $end_month == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close()."
	".$rs -> form_select_open("end_year")."
	".$rs -> form_option(CONTENT_ADMIN_DATE_LAN_14, 0, "none");
	for($count=($current_year-5); $count<=($current_year+1); $count++){
		$text .= $rs -> form_option($count, (isset($end_year) && $end_year == $count ? "1" : "0"), $count);
	}
	$text .= $rs -> form_select_close();
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_UPLOAD
global $row, $rs, $show, $checkicon, $checkattach, $checkimages, $content_tmppath_icon, $content_tmppath_file, $content_tmppath_image;
if($show['upload']===true){
	$text = "";
	if(!FILE_UPLOADS){
		$text .= "<b>".CONTENT_ADMIN_ITEM_LAN_21."</b>";
	}else{
		if(!is_writable($content_tmppath_icon)){
			$text .= "<b>".CONTENT_ADMIN_ITEM_LAN_22." ".$content_tmppath_icon." ".CONTENT_ADMIN_ITEM_LAN_23."</b><br />";
		}
		if(!is_writable($content_tmppath_file)){
			$text .= "<b>".CONTENT_ADMIN_ITEM_LAN_22." ".$content_tmppath_file." ".CONTENT_ADMIN_ITEM_LAN_23."</b><br />";
		}
		if(!is_writable($content_tmppath_image)){
			$text .= "<b>".CONTENT_ADMIN_ITEM_LAN_22." ".$content_tmppath_image." ".CONTENT_ADMIN_ITEM_LAN_23."</b><br />";
		}
		$text .= "<br />
		<input class='tbox' type='file' name='file_userfile[]'  size='36' /> 
			".$rs -> form_select_open("uploadtype")."
			".($checkicon ? $rs -> form_option(CONTENT_ADMIN_ITEM_LAN_114, "0", "1") : '')."
			".($checkattach ? $rs -> form_option(CONTENT_ADMIN_ITEM_LAN_115, "0", "2") : '')."
			".($checkimages ? $rs -> form_option(CONTENT_ADMIN_ITEM_LAN_116, "0", "3") : '')."
			".$rs -> form_select_close()."
		<input type='hidden' name='tmppathicon' value='".$content_tmppath_icon."' />
		<input type='hidden' name='tmppathfile' value='".$content_tmppath_file."' />
		<input type='hidden' name='tmppathimage' value='".$content_tmppath_image."' />
		<input class='button' type='submit' name='uploadfile' value='".CONTENT_ADMIN_ITEM_LAN_104."' />";
	}
	$text .= "<br />";
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_ICON
global $row, $rs, $show, $iconlist;
if($show['icon']===true){
	$text = $rs -> form_text("content_icon", 60, $row['content_icon'], 100)."
	".$rs -> form_button("button", '', CONTENT_ADMIN_ITEM_LAN_105, "onclick=\"expandit('divicon')\"")."
	<div id='divicon' style='{head}; display:none'>";
	if(empty($iconlist)){
		$text .= CONTENT_ADMIN_ITEM_LAN_121;
	}else{
		foreach($iconlist as $icon){
			if(file_exists($icon['path']."thumb_".$icon['fname'])){
				$img = "<img src='".$icon['path']."thumb_".$icon['fname']."' style='width:100px; border:0' alt='' />";
			}else{
				$img = "<img src='".$icon['path'].$icon['fname']."' style='width:100px; border:0' alt='' />";
			}
			$text .= "<a href=\"javascript:insertext('".$icon['fname']."','content_icon','divicon')\">".$img."</a> ";
		}
	}
	$text .= "</div>";
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_ATTACH
global $row, $rs, $show, $checkattachnumber, $filelist;
if($show['attach']===true){

	$filetmp = explode("[file]", $row['content_file']);
	foreach($filetmp as $key => $value) { 
		if($value == "") { 
			unset($filetmp[$key]); 
		} 
	} 
	$attachments = array_values($filetmp);
	for($i=0;$i<$checkattachnumber;$i++){
		$k=$i+1;
		$num = (strlen($k) == 1 ? "0".$k : $k);
		$attachments[$i] = ($attachments[$i] ? $attachments[$i] : "");

		//choose file
		$text .= "
		<div style='padding:2px;'>
		".$num." ".$rs -> form_text("content_files".$i."", 60, $attachments[$i], 100)."
		".$rs -> form_button("button", '', CONTENT_ADMIN_ITEM_LAN_105, "onclick=\"expandit('divfile".$i."')\"")."
		<div id='divfile".$i."' style='{head}; display:none'>";
		if(empty($filelist)){
			$text .= CONTENT_ADMIN_ITEM_LAN_122;
		}else{
			foreach($filelist as $file){
				$text .= CONTENT_ICON_FILE." <a href=\"javascript:insertext('".$file['fname']."','content_files".$i."','divfile".$i."')\">".$file['fname']."</a><br />";
			}
		}
		$text .= "</div></div>";
	}
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_IMAGES
global $row, $rs, $show, $checkimagesnumber, $imagelist;
if($show['images']===true){
	$imagestmp = explode("[img]", $row['content_image']);
	foreach($imagestmp as $key => $value) { 
		if($value == "") { 
			unset($imagestmp[$key]); 
		} 
	} 
	$imagesarray = array_values($imagestmp);
	for($i=0;$i<$checkimagesnumber;$i++){
		$k=$i+1;
		$num = (strlen($k) == 1 ? "0".$k : $k);
		$imagesarray[$i] = ($imagesarray[$i] ? $imagesarray[$i] : "");

		//choose image
		$text .= "
		<div style='padding:2px;'>
		".$num." ".$rs -> form_text("content_images".$i."", 60, $imagesarray[$i], 100)."
		".$rs -> form_button("button", '', CONTENT_ADMIN_ITEM_LAN_105, "onclick=\"expandit('divimage".$i."')\"")."
		<div id='divimage".$i."' style='{head}; display:none'>";
		if(empty($imagelist)){
			$text .= CONTENT_ADMIN_ITEM_LAN_123;
		}else{
			foreach($imagelist as $image){
				if(file_exists($image['path']."thumb_".$image['fname'])){
					$img = "<img src='".$image['path']."thumb_".$image['fname']."' style='width:100px; border:0' alt='' />";
				}else{
					$img = "<img src='".$image['path'].$image['fname']."' style='width:100px; border:0' alt='' />";
				}
				$text .= "<a href=\"javascript:insertext('".$image['fname']."','content_images".$i."','divimage".$i."')\">".$img."</a> ";
			}
		}
		$text .= "</div></div>";								
	}
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_COMMENT
global $row, $rs, $show;
if($show['comment']===true){
	return $rs -> form_radio("content_comment", "1", ($row['content_comment'] ? "1" : "0"), "", "").CONTENT_ADMIN_ITEM_LAN_85." ".$rs -> form_radio("content_comment", "0", ($row['content_comment'] ? "0" : "1"), "", "").CONTENT_ADMIN_ITEM_LAN_86;
}
SC_END

SC_BEGIN CONTENTFORM_RATING
global $row, $rs, $show;
if($show['rating']===true){
	return $rs -> form_radio("content_rate", "1", ($row['content_rate'] ? "1" : "0"), "", "").CONTENT_ADMIN_ITEM_LAN_85." ".$rs -> form_radio("content_rate", "0", ($row['content_rate'] ? "0" : "1"), "", "").CONTENT_ADMIN_ITEM_LAN_86;
}
SC_END

SC_BEGIN CONTENTFORM_PEICON
global $row, $rs, $show;
if($show['pe']===true){
	return $rs -> form_radio("content_pe", "1", ($row['content_pe'] ? "1" : "0"), "", "").CONTENT_ADMIN_ITEM_LAN_85." ".$rs -> form_radio("content_pe", "0", ($row['content_pe'] ? "0" : "1"), "", "").CONTENT_ADMIN_ITEM_LAN_86;
}
SC_END

SC_BEGIN CONTENTFORM_VISIBILITY
global $row, $show;
if($show['visibility']===true){
	return r_userclass("content_class",$row['content_class'], "CLASSES");
}
SC_END

SC_BEGIN CONTENTFORM_SCORE
global $row, $rs, $show;
if($show['score']===true){
	$text = $rs -> form_select_open("content_score")."
	".$rs -> form_option(CONTENT_ADMIN_ITEM_LAN_41, 0, "none");
	for($a=1; $a<=100; $a++){
		$text .= $rs -> form_option($a, ($row['content_score'] == $a ? "1" : "0"), $a);
	}
	$text .= $rs -> form_select_close();
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_META
global $row, $rs, $show;
if($show['meta']===true){
	return $rs -> form_text("content_meta", 74, $row['content_meta'], 250);
}
SC_END

SC_BEGIN CONTENTFORM_LAYOUT
global $row, $rs, $show, $tp, $fl, $content_pref;
if($show['layout']===true){

	if(!isset($content_pref["content_theme"])){
		$dir = $plugindir."templates/default";
	}else{
		if(is_readable($tp->replaceConstants($content_pref["content_theme"])."content_content_template.php")){
			$dir = $tp->replaceConstants($content_pref["content_theme"]);
		}else{
			$dir = $plugindir."templates/default";
		}
	}
	//get_files($path, $fmask = '', $omit='standard', $recurse_level = 0, $current_level = 0, $dirs_only = FALSE)
	$rejectlist = array('$.','$..','/','CVS','thumbs.db','Thumbs.db','*._$', 'index', 'null*', '.bak');
	$templatelist = $fl->get_files($dir,"content_content",$rejectlist);

	//template
	$check = "";
	if(isset($row['content_layout']) && $row['content_layout'] != ""){
		$check = $row['content_layout'];
	}else{
		if(isset($content_pref["content_layout"])){
			$check = $content_pref["content_layout"];
		}
	}

	$text = $rs -> form_select_open("content_layout")."
	".$rs -> form_option(CONTENT_ADMIN_ITEM_LAN_94, 0, "none");
	foreach($templatelist as $template){
		$templatename = substr($template['fname'], 25, -4);
		$templatename = ($template['fname'] == "content_content_template.php" ? "default" : $templatename);
		$text .= $rs -> form_option($templatename, ($check == $template['fname'] ? "1" : "0"), $template['fname']);
	}
	$text .= $rs -> form_select_close();
	return $text;
}
SC_END

SC_BEGIN CONTENTFORM_CUSTOM
global $CONTENTFORM_CUSTOM;
return $CONTENTFORM_CUSTOM;
SC_END

SC_BEGIN CONTENTFORM_CUSTOM_KEY
global $CONTENTFORM_CUSTOM_KEY;
return $CONTENTFORM_CUSTOM_KEY;
SC_END

SC_BEGIN CONTENTFORM_CUSTOM_VALUE
global $CONTENTFORM_CUSTOM_VALUE;
return $CONTENTFORM_CUSTOM_VALUE;
SC_END

SC_BEGIN CONTENTFORM_PRESET
global $CONTENTFORM_PRESET;
return $CONTENTFORM_PRESET;
SC_END

SC_BEGIN CONTENTFORM_PRESET_KEY
global $CONTENTFORM_PRESET_KEY;
return $CONTENTFORM_PRESET_KEY;
SC_END

SC_BEGIN CONTENTFORM_PRESET_VALUE
global $CONTENTFORM_PRESET_VALUE;
return $CONTENTFORM_PRESET_VALUE;
SC_END

*/
?>