<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/chatbox.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Configura las preferencias de tu Chatbox desde aqu�.<br />
Si la casilla de reemplazo est� marcada, cualquier enlace que hayan introducido ser� reemplazado
con el texto que escribiste en la caja de texto,
esto detendr� enlaces largos que causan problemas al mostrar.
Cortar palabras autocortar� los textos que sean m�s largos que el n�mero especificado aqu�.";

$ns -> tablerender("Chatbox", $text);
?>