<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Swedish/admin/help/newsfeed.php,v $
|     $Revision: 1.3 $
|     $Date: 2006-01-24 12:49:09 $
|     $Author: mrpiercer $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "Du kan hämta och visa andra sajters RSS nyhetsflöden och visa dem på din egen sajt härifrån.<br />Ange den fullständiga URLen till flödet (t.ex. http://e107.org/news.xml). Du kan ange sökväg till en bil om du inte tycker om standardbilden, eller om det inte finns någon definierad. Du kan aktivera och avaktivera flödet om sajten skulle t.ex. gå ner.<br /><br />För att se rubrikerna på din sajt, se till att headlines_menu är aktiverad från din menysida.";

$ns -> tablerender("Nyhetsflöden", $text);

?>
