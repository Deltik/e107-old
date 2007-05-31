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
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Turkish/admin/help/wmessage.php,v $
|     $Revision: 1.1 $
|     $Date: 2007-05-31 13:48:01 $
|     $Author: whoisbig $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "This page allows you to set a message that will appear at the top of your front page all the time it's activated. You can set a different message for guests, registered/logged-in members and administrators.";
$ns -> tablerender("WMessage Help", $text);
?>
