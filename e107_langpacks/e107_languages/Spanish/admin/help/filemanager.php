<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Spanish/admin/help/filemanager.php,v $
|     $Revision: 1.7 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Tiene la posibilidad de manejar los archivos en su directorio de archivos desde esta página.
Si está teniendo mensajes de error acerca de los permisos para transferir archivos por favor cambie el CHMOD del directorio
al que quiere transferir a 777.";
$ns -> tablerender("Ayuda Director de Archivos", $text);
?>