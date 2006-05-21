<?php
/*
+-----------------------------------------------------------------------------+
|     e107 website system - Language File.
+-----------------------------------------------------------------------------+
|     Spolszczenie systemu e107 v0.7
|     Polskie wsparcie: http://e107.org.pl - http://e107poland.org
|
|     $Revision: 1.2 $
|     $Date: 2006-05-21 16:06:44 $
|     $Author: marcelis_pl $
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Polish/admin/help/cache.php,v $
+-----------------------------------------------------------------------------+
|     Zgodne z: /e107_languages/English/admin/help/cache.php rev. 1.2
+-----------------------------------------------------------------------------+
*/
 
if (!defined('e107_INIT')) { exit; }

$caption = "Cachowanie";
$text = "Włączenia cachowania znacznie zwiększy szybkość działania Twojego serwisu oraz zminimalizujesz ilość połączeń do bazy danych SQL.<br /><br /><b>UWAGA! Podczas tworzenia własnego tematu graficznego powinieneś wyłączyć cachowanie, ponieważ w przeciwnym wypadku jakiekolwiek wprowadzone zmiany nie będą odzwierciedlone.</b>";
$ns -> tablerender($caption, $text);

?>
