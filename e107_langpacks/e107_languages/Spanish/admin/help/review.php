<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Spanish/admin/help/review.php,v $
|     $Revision: 1.7 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Las Revisiones son similares a los artículos pero no serán listados en su propio menú.<br />
 Para revisión multi-página separar cada página con el texto [newpage], ej <br /><code>Test1 [newpage] Test2</code><br />
 creará una revisión de dos páginas con 'Test1' en página 1 y 'Test2' en página 2.";
$ns -> tablerender("Ayuda Revisión", $text);
?>