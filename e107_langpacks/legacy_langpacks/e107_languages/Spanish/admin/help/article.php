<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/article.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Desde esta p�gina puede a�adir art�culos simples o multi-p�gina.<br />
 Para un art�culo multi-p�gina debe separar cada p�gina con el texto [newpage], ej <br /><code>Test1 [newpage] Test2</code><br />
 Crear� un art�clo de dos p�ginas con 'Test1' en la p�gina 1 y 'Test2' en la p�gina 2.
<br /><br />
Si su art�culo contiene etiquetas HTML que desea preservar, encierre el c�digo con [preserve] [/preserve].
Por ejemplo, si ha introducido el texto '<table><tr><td>Hola </td></tr></table>'
en su art�culo, debe aparecer una tabla conteniendo la palabra Hola.
Si ha introducido '[preserve]<table><tr><td>Hola </td></tr></table>[/preserve]'
el debe aparecer el c�digo tal como lo introdujo y no la tabla que genera el c�digo.";
$ns -> tablerender("Ayuda Art�culo", $text);
?>