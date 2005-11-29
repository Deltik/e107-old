<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/fileinspector.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-11-29 20:10:58 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
$text = "<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_core.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Archivo n�cleo</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_check.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Archivo n�cleo (Integridad Ok)</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_warning.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Archivo n�cleo (Fallo Integridad)</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_missing.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Falta archivo del n�cleo</div>
<div style='margin-left: 0px; margin-bottom: 1px; margin-top: 2px; vertical-align: top; white-space: nowrap'>
<img src='".e_IMAGE."fileinspector/file_unknown.png' alt='".$dir."' style='margin-left: 3px; width: 16px; height: 16px' />&nbsp;Otros archivos</div>";
$ns -> tablerender("Clave Archivo", $text);
$text = "El inspector de archivos escanea y analiza los archivos en su servidor. CUando encuentra un archivo del n�cleo e107, chequea su consistenacia para comprobar que no est� corrupto.<br /><br />La herramienta adicional de coincidencia de cadena le permite escanear archivos de su servidor con expresiones de texto regulares. El motor regex en uso es una funci�n PHP<a href='http://php.net/pcre'>PCRE</a>(funciones preg_*), as� que escriba su petici�n como #pattern#modifiers en los campos dados.";
$ns -> tablerender("Ayuda del inspector de archivos", $text);
?>