<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Spanish/admin/help/fileinspector.php,v $
|     $Revision: 1.7 $
|     $Date: 2005-11-29 20:10:58 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
$text = "<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_core.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Archivo núcleo</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_check.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Archivo núcleo (Integridad Ok)</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_warning.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Archivo núcleo (Fallo Integridad)</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_missing.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Falta archivo del núcleo</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_unknown.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Otros archivos</div>";
$ns -> tablerender("Clave Archivo", $text);
$text = "El inspector de archivos escanea y analiza los archivos en su servidor. CUando encuentra un archivo del núcleo e107, chequea su consistenacia para comprobar que no está corrupto.<br /><br />La herramienta adicional de coincidencia de cadena le permite escanear archivos de su servidor con expresiones de texto regulares. El motor regex en uso es una función PHP<a href='http://php.net/pcre'>PCRE</a>(funciones preg_*), así que escriba su petición como #pattern#modifiers en los campos dados.";
$ns -> tablerender("Ayuda del inspector de archivos", $text);
?>