<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/review.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Las Revisiones son similares a los art�culos pero no ser�n listados en su propio men�.<br />
 Para revisi�n multi-p�gina separar cada p�gina con el texto [newpage], ej <br /><code>Test1 [newpage] Test2</code><br />
 crear� una revisi�n de dos p�ginas con 'Test1' en p�gina 1 y 'Test2' en p�gina 2.";
$ns -> tablerender("Ayuda Revisi�n", $text);
?>