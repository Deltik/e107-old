<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Swedish/admin/help/review.php,v $
|     $Revision: 1.3 $
|     $Date: 2006-01-24 12:49:09 $
|     $Author: mrpiercer $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "Recensioner är liknande artiklar, men de listas i egna menyer.<br />
För en flersidig recension, separera sidorna med texten [newpage], alltså <br /><code>Test1 [newpage] Test2</code><br /> skapar en tvåsidig recension med 'Test1' på sidan 1 och 'Test2' på sidan 2.";
$ns -> tablerender("Recensionshjälp", $text);

?>
