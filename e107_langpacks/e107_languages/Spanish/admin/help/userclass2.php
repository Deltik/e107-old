<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Spanish/admin/help/userclass2.php,v $
|     $Revision: 1.7 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Ayuda Uso de Clase";
$text = "Puede crear o editar/borrar clases existentes desde esta página.<br />
Esto es útil para restringir usuarios a ciertos sectores de su sitio.
Por ejemplo, Puede crear una clase llamada PRUEBA, luego crear un foro en donde solo los usuarios
de clase PRUEBA puedan acceder.
<br /> usando clases puede fácilmente construir un área solo para miembros en su web.";
$ns -> tablerender($caption, $text);
?>