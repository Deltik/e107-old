<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/administrator.php,v $
|     $Revision: 1.4 $
|     $Date: 2006-01-12 01:23:56 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Ayuda de Admin";
$text = "Usa esta p�gina para editar las preferencias o eliminar administradores del sitio. Los administradores solo tendr�n acceso a las caracter�sticas marcadas<br /><br />Para crear un nuevo administrador, vaya a la configuraci�n de usuarios y cambie el estado a admin en el usuario seleccionado.";
$ns -> tablerender($caption, $text);
?>