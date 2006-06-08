<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ?Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_plugins/newsfeed/templates/newsfeed_menu_template.php,v $
|     $Revision: 1.8 $
|     $Date: 2006-06-08 04:02:47 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$truncate = 100;
$truncate_string = " ...";
// $items = 2;

$NEWSFEED_MENU_START = "\n\n<!-- Start NewsFeed Menu -->
<div style='text-align: center; margin-left: auto; margin-right: auto;'>{FEEDIMAGE}<br /><b>{FEEDTITLE}</b></div>\n<br />
<table class='newsfeed_menu_table' style='width:100%'>
";

$NEWSFEED_MENU = "
<tr><td class='newsfeed_menu_cell' style='vertical-align:top;width:5%'><b>&raquo;</b></td><td>{FEEDITEMLINK}<br /><span class='smalltext'>{FEEDITEMTEXT}</span></td></tr>\n";

$NEWSFEED_MENU_END = "</table><br />

<div style='text-align: center;'><hr /><span class='smalltext'>{FEEDLASTBUILDDATE}<br />{LINKTOMAIN}</span></div>\n
<!-- End News Feed Menu -->\n\n";


?>