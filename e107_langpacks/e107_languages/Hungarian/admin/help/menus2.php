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
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Hungarian/admin/help/menus2.php,v $
|     $Revision: 1.8 $
|     $Date: 2008-12-01 16:42:39 $
|     $Author: e107hun-lac $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text .= "Itt beállíthatod, hogy hol és milyen sorrendben jelenjenek meg a menük. Használd a nyilakat a menük le- ill. fel-mozgatásához, amíg eléred a megfelelő pozíciót.<br />
A képernyő közepén elhelyezkedő linkek inaktívak, aktiválásukhoz ki kell választani és megnyomni a megfelelő menüterület gombját.
";

$ns -> tablerender("Menü - Súgó", $text);
?>
