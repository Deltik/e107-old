<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Spanish/admin/help/content.php,v $
|     $Revision: 1.7 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Puede añadir una página normal a su sitio usando esta característica.
Se creará un link a la nueva página en el menú de navegación de su sitio.
Por ejemplo si crea una página nueva con el Nombre del Enlace 'Prueba', un enlace llamado 'Prueba'
aparecerá en su menú de navegación luego de enviar la página nueva.<br />
Si desea que su página de contenidos tenga un título, introdúzcalo en la caja de Encabezado de Página.";
$ns -> tablerender("Ayuda Contenidos", $text);
?>