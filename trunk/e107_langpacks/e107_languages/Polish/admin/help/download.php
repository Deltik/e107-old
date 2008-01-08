<?php
/*
+-----------------------------------------------------------------------------+
|     e107 website system - Language File.
+-----------------------------------------------------------------------------+
|     Spolszczenie systemu e107 v0.7
|     Polskie wsparcie: http://e107.org.pl - http://e107poland.org
|
|     $Revision: 1.6 $
|     $Date: 2008-01-08 19:25:34 $
|     $Author: marcelis_pl $
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Polish/admin/help/download.php,v $
+-----------------------------------------------------------------------------+
|     Zgodne z: /e107_languages/English/admin/help/download.php rev. 1.2
+-----------------------------------------------------------------------------+
*/
 
if (!defined('e107_INIT')) { exit; }

$text = "Proszę załadować swoje pliki do następujących katalogów: <br />pliki - ".e_FILE."downloads
<br />obrazki - ".e_FILE."downloadimages
<br />miniaturki obrazków - ".e_FILE."downloadthumbs
<br /><br />
Aby dodać plik do pobrania, najpierw utwórz dział główny, następnie utwórz podkategorię do utworzonego działu głównego, po wykonaniu przedstawionych czynności będziesz mógł udostępnić pliki do pobrania.";
$ns -> tablerender("Download", $text);

?>
