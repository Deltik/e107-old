<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Swedish/admin/help/userclass2.php,v $
|     $Revision: 1.4 $
|     $Date: 2006-04-19 11:23:10 $
|     $Author: mrpiercer $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "Hjälp användande av klasser";
$text = "Du kan skapa och redigera/radera befintliga klasser från denna sidan.<br />Det är användbart för att begränsa användare från/till valda delar av din sajt.
 Till exempel kan du skapa en klass kallad TEST, sedan skapa ett forum som endast användare i klassen TEST har tillgång till.";
$ns -> tablerender($caption, $text);

?>
