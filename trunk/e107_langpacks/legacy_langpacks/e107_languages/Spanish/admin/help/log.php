<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/log.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Activa las estad�sticas del sitio desde esta p�gina.
Si tienes poco espacio en el servidor marca la casilla de dominio como referencia de inicio de sesi�n,
esto solo indicar� el dominio en lugar de todo el url, ejemplo 'e107.org' en lugar de 'http://e107.org/portal' ";
$ns -> tablerender("Ayuda de Inicio de sesi�n", $text);
?>