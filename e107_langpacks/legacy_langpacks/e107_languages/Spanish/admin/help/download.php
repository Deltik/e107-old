<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/download.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Por favor, copie sus archivos en la carpeta ".e_FILE."downloads, sus im�genes en la carpeta ".e_FILE."downloadimages y la im�genes de muestra en la carpeta ".e_FILE."downloadthumbs.
<br /><br />
Para enviar una descarga, primero cree un padre, despu�s las categor�as dentro del padre, podr� activar las descargas dentro de cada categor�a.";
$ns -> tablerender("Ayuda Descargas", $text);
?>