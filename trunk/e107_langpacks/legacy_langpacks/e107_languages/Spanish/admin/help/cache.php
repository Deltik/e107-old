<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/cache.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Caching";
$text = "Si tienes el sistema de caching encendido, �ste dar� velocidad a tu sitio y
minimizar� la cantidad de llamadas a la base de datos sql.<br /><br /><b>
�IMPORTANTE! Si est�s haciendo tu propio tema desactiva el sistema de caching porque cualquier
cambio que hagas no ser� reflejado.</b>";
$ns -> tablerender($caption, $text);
?>