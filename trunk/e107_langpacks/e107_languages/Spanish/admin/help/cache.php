<?php
/*
+ ----------------------------------------------------------------------------+
|     $Sitio web e107 - Archivos del lenguaje $
|     $Versión: 0.7.16 $
|     $Date: 2009/09/16 17:51:27 $
|     $Author: E107 <www.e107.org> $
|     $Traductor: Josico <www.e107.es> $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Caching";
$text = "Si tienes el sistema de caching encendido, éste dará velocidad a tu sitio y
minimizará la cantidad de llamadas a la base de datos sql.<br /><br /><b>
¡IMPORTANTE! Si estás haciendo tu propio tema desactiva el sistema de caching porque cualquier
cambio que hagas no será reflejado.</b>";
$ns -> tablerender($caption, $text);
?>