<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/banner_menu.php.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
mt_srand ((double) microtime() * 1000000);
$seed = mt_rand(1,2000000000);

if($menu_pref['banner_campaign']){
	$query = "banner_active=1 AND (banner_startdate=0 OR banner_startdate<=".time().") AND (banner_enddate=0 OR banner_enddate>".time().") AND (banner_impurchased=0 OR banner_impressions<=banner_impurchased) AND banner_campaign='".$menu_pref['banner_campaign']."' ORDER BY RAND($seed)";
}else{
	$query = "banner_active=1 AND (banner_startdate=0 OR banner_startdate<=".time().") AND (banner_enddate=0 OR banner_enddate>".time().") AND (banner_impurchased=0 OR banner_impressions<=banner_impurchased) ORDER BY RAND($seed)";
}
$sql -> db_Select("banner", "*", $query);
$row = $sql -> db_Fetch(); extract($row);
$text = "<div style='text-align:center'><a href='".e_BASE."banner.php?".$banner_id."'  onclick=\"window.open('banner.php?".$banner_id."'); return false;\"><img src='".e_IMAGE."banners/".$banner_image."' alt='".$banner_clickurl."' style='border:0' /></a></div>";
$sql -> db_Update("banner", "banner_impressions=banner_impressions+1 WHERE banner_id='$banner_id' ");


$ns -> tablerender(BANNER_MENU_L1, $text);
?>