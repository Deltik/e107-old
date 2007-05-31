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
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Turkish/admin/help/review.php,v $
|     $Revision: 1.1 $
|     $Date: 2007-05-31 13:48:01 $
|     $Author: whoisbig $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "Reviews are similar to articles but they will be listed in their own menu item.<br />
 For a multi-page review separate each page with the text [newpage], i.e. <br /><code>Test1 [newpage] Test2</code><br /> would create a two page review with 'Test1' on page 1 and 'Test2' on page 2.";
$ns -> tablerender("Review Help", $text);
?>