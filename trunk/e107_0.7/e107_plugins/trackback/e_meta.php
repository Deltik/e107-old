<?php
/*
+ ----------------------------------------------------------------------------------------------+
|     e107 website system  : http://e107.org
|     Steve Dunstan 2001-2002 : jalist@e107.org
|     Released under the terms and conditions of the GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_plugins/trackback/e_meta.php,v $
|     $Revision: 1.1 $
|     $Date: 2006-07-09 19:40:43 $
|     $Author: e107coders $
+-----------------------------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

if(isset($pref['trackbackEnabled'])){
	echo "<link rel='pingback' href='".SITEURLBASE.e_PLUGIN_ABS."trackback/xmlrpc.php' />";
}

?>

