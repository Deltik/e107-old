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
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Bulgarian/admin/help/search.php,v $
|     $Revision: 1.5 $
|     $Date: 2007-03-04 22:09:38 $
|     $Author: secretr $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "If your MySql server version supports it you can switch 
to the MySql sort method which is faster than the PHP sort method. See preferences.<br /><br />
If your site includes Ideographic languages such as Chinese and Japanese you must 
use the PHP sort method and switch whole word matching off.";
$ns -> tablerender("Search Help", $text);
?>