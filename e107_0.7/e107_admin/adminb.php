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
|     $Source: /cvs_backup/e107_0.7/e107_admin/adminb.php,v $
|     $Revision: 1.4 $
|     $Date: 2005-01-06 18:20:08 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if (ADMIN) {
	header('Location:'.SITEURL.$ADMIN_DIRECTORY.'admin.php');
	exit;
} else {
	header('Location:'.SITEURL.'index.php');
	exit;	
}
?>
