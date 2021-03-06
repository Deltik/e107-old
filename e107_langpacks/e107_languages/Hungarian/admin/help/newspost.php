<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ŠSteve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Hungarian/admin/help/newspost.php,v $
|     $Revision: 1.8 $
|     $Date: 2008-12-01 16:42:39 $
|     $Author: e107hun-lac $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "Új hír - Súgó";
$text = "<b>Általános</b><br />
A törzs a főoldalon fog megjelenni, míg a bővített a 'Tovább' linkre kattintva lesz olvasható.
<br />
<br />
<b>Csak a cím mutatása</b>
<br />
Ennek engedélyezésekor a főoldalon csak a hír címe jelenik meg,egy kattintható linkkel a teljes szöveghez.
<br /><br />
<b>Aktiválás</b>
<br />
Ha megadsz egy kezdési és/vagy befejezési dátumot, akkor a cikk csak a két időpont közt jelenik meg.
";
$ns -> tablerender($caption, $text);
?>
