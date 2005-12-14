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
|     $Source: /cvs_backup/e107_0.7/e107_languages/English/admin/help/chatbox.php,v $
|     $Revision: 1.2 $
|     $Date: 2005-12-14 17:37:43 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "Set your chatbox preferences from here.<br />If the replace link box is ticked, any links entered will be replaced by the text you enter in the textbox, this stops long links causing display problems. Wordwrap will auto wrap text that is longer than the length specified here.";

$ns -> tablerender("Chatbox", $text);
?>