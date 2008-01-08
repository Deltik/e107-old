<?php
/*
+-----------------------------------------------------------------------------+
|     e107 website system - Language File.
+-----------------------------------------------------------------------------+
|     Spolszczenie systemu e107 v0.7
|     Polskie wsparcie: http://e107.org.pl - http://e107poland.org
|
|     $Revision: 1.6 $
|     $Date: 2008-01-08 19:25:33 $
|     $Author: marcelis_pl $
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Polish/admin/help/administrator.php,v $
+-----------------------------------------------------------------------------+
|     Zgodne z: /e107_languages/English/admin/help/administrator.php rev. 1.3
+-----------------------------------------------------------------------------+
*/
 
if (!defined('e107_INIT')) { exit; }

$caption = "Administratorzy";
$text = "Używaj tej strony do edytowania preferencji oraz usuwania administratorów strony. Administrator będzie miał tylko dostęp do tych opcji, które mu nadasz.<br /><br />
Aby utworzyć nowego administratora, przejdź do strony konfiguracji użytkowników i zaktualizuj status wybranego użytkownika do rangi administratora.";
$ns -> tablerender($caption, $text);

?>
