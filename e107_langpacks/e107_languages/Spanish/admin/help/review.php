<?php
$text = "Las Revisiones son similares a los art�culos pero no ser�n listados en su propio men�.<br />
 Para revisi�n multi-p�gina separar cada p�gina con el texto [newpage], ej <br /><code>Test1 [newpage] Test2</code><br /> 
 crear� una revisi�n de dos p�ginas con 'Test1' en p�gina 1 y 'Test2' en p�gina 2.";
$ns -> tablerender("Ayuda Revisi�n", $text);
?>