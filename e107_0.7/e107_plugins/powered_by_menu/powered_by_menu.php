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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/powered_by_menu/powered_by_menu.php,v $
|     $Revision: 1.5 $
|     $Date: 2005-01-27 22:28:47 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/
$text = "
<div style='text-align: center'>
<div class='spacer'>
<a href='http://e107.org' rel='external'><img src='".e_IMAGE."button.png' alt='e107' style='border: 0px; width: 88px; height: 31px' /></a>
</div>
<div class='spacer'>
<a href='http://php.net' rel='external'><img src='".e_IMAGE."generic/php-small-trans-light.gif' alt='PHP' style='border: 0px; width: 88px; height: 31px' /></a>
</div>
<div class='spacer'>
<a href='http://mysql.com' rel='external'><img src='".e_IMAGE."generic/poweredbymysql-88.png' alt='mySQL' style='border: 0px; width: 88px; height: 31px' /></a>
</div>
</div>";
$ns -> tablerender(POWEREDBY_L1,  $text, 'powered_by');
?>