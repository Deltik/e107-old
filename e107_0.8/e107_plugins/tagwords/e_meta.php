<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.8/e107_plugins/tagwords/e_meta.php,v $
|     $Revision: 1.1 $
|     $Date: 2008-12-29 20:51:07 $
|     $Author: lisa_ $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

if (is_readable(THEME."tagwords_css.php"))
{
	$src = THEME."tagwords_css.php";
	} else {
	$src = e_PLUGIN."tagwords/tagwords_css.php";
}
echo "<link rel='stylesheet' href='".$src."' type='text/css' />\n";

?>