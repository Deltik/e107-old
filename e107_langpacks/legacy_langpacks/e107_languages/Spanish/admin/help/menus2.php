<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/menus2.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Ayuda de Men�";
$text .= "Puede indicar donde y en que orden se mostrar�n los men�s.
Use las flechas para mover los men�s arriba y abajo hasta que est� conforme con el aspecto.<br />
Los men� que est�n en la mitad de la pantalla est�n desactivados,
puede activarlos eligiendo una locaci�n para colocarlos.
";

$ns -> tablerender("Ayuda de Men�s", $text);
?>