<?php
/*
+-----------------------------------------------------------------------------+
|     e107 website system - Language File.
+-----------------------------------------------------------------------------+
|     Spolszczenie systemu e107 v0.7
|     Polskie wsparcie: http://e107.org.pl - http://e107poland.org
|
|     $Revision: 1.8 $
|     $Date: 2009-09-13 10:26:27 $
|     $Author: marcelis_pl $
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Polish/admin/help/news_category.php,v $
+-----------------------------------------------------------------------------+
|     Zgodne z: /e107_languages/English/admin/help/news_category.php rev. 1.3
+-----------------------------------------------------------------------------+
*/
 
if (!defined('e107_INIT')) { exit; }

$text = "Możesz podzielić aktualności pomiędzy różne kategorie, a odwiedzającym zezwolić tylko na przeglądanie aktualności ze wskazanych kategorii.<br /><br />Załaduj ikony aktualności do jednego z dwóch katalogów ".e_THEME."-yourtheme-/images/ lub themes/shared/newsicons/.";
$ns -> tablerender("Kategorie aktualności", $text);

?>
