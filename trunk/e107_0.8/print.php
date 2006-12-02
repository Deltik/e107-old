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
|     $Source: /cvs_backup/e107_0.8/print.php,v $
|     $Revision: 1.1.1.1 $
|     $Date: 2006-12-02 04:33:09 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
require_once("class2.php");
$HEADER="";
$FOOTER="";
require_once(HEADERF);

$qs = explode(".", e_QUERY);
if ($qs[0] == "") {
	header("location:".e_BASE."index.php");
	 exit;
}
$source = $qs[0];
$parms = intval($qs[1]);
unset($qs);

if(strpos($source,'plugin:') !== FALSE)
{
	$plugin = substr($source,7);
	if(file_exists(e_PLUGIN.$plugin."/e_emailprint.php"))
	{
		include_once(e_PLUGIN.$plugin."/e_emailprint.php");
		$text = print_item($parms);
	}
	else
	{
		echo "file missing.";
		exit;
	}
}
else
{
	$con = new convert;
	$sql->db_Select("news", "*", "news_id='{$parms}'");
	$row = $sql->db_Fetch(); 
	extract($row);
	$news_body = $tp->toHTML($news_body, TRUE);
	$news_extended = $tp->toHTML($news_extended, TRUE);
	if ($news_author == 0)
	{
		$a_name = "e107";
		$category_name = "e107 welcome message";
	}
	else
	{
		$sql->db_Select("news_category", "category_id, category_name", "category_id='{$news_category}'");
		list($category_id, $category_name) = $sql->db_Fetch();
		$sql->db_Select("user", "user_id, user_name", "user_id='{$news_author}'");
		list($a_id, $a_name) = $sql->db_Fetch();
	}
	$news_datestamp = $con->convert_date($news_datestamp, "long");
	$text = "<font style=\"font-size: 11px; color: black; font-family: tahoma, verdana, arial, helvetica; text-decoration: none\">
	<b>".LAN_PRINT_135.": ".$news_title."</b>
	<br />
	(".LAN_PRINT_86." ".$category_name.")
	<br />
	".LAN_PRINT_94." ".$a_name."<br />
	".$news_datestamp."
	<br /><br />".
	$news_body;

	if ($news_extended != ""){ $text .= "<br /><br />".$news_extended; }
	if ($news_source != ""){ $text .= "<br /><br />".$news_source; }
	if ($news_url != ""){ $text .= "<br />".$news_url; }
	 
	$text .= "<br /><br /></font><hr />".
	LAN_PRINT_303.SITENAME."
	<br />
	( http://".$_SERVER[HTTP_HOST].e_HTTP."comment.php?comment.news.".$news_id." )
	";
}

if(defined("TEXTDIRECTION") && TEXTDIRECTION == "rtl"){
	$align = 'right';
}else{
	$align = 'left';
}

echo "
<div style='text-align:".$align."'>".$tp->parseTemplate("{LOGO}", TRUE)."</div><hr /><br />
<div style='text-align:".$align."'>".$text."</div><br /><br />
<div style='text-align:".$align."'><form action=''><input type='button' value='".LAN_PRINT_307."' onClick='window.print()' /></form></div>";

require_once(FOOTERF);

?>