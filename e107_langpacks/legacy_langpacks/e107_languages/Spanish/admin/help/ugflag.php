<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/ugflag.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Si est�s actualizando e107 o necesitas tu sitio
fuera de l�nea por un momento solo marca la casilla mantenimiento
y tus visitantes ser�n redirigidos a una p�gina explicandoles que
el sitio est� en reparaci�n.
Cuando hayas finalizado desmarca la casilla para retornar al estado normal.";

$ns -> tablerender("Mantenimiento", $text);
?>