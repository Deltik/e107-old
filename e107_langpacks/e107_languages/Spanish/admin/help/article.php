<?php
$text = "Desde esta p�gina puede a�adir art�culos simples o multi-p�gina.<br />
 Para art�culo multi-p�gina separar cada p�gina con el texto [newpage], ej <br /><code>Test1 [newpage] Test2</code><br /> 
 crear� un art�clo de dos p�ginas con 'Test1' en p�gina 1 y 'Test2' en p�gina 2.
<br /><br />
Si su art�culo contiene etiquetas HTML que desea preservar, encierre el c�digo con [preserve] [/preserve]. 
Por ejemplo, si ha introducido el texto '&lt;table>&lt;tr>&lt;td>Hola &lt;/td>&lt;/tr>&lt;/table>' 
en su art�culo, debe aparecer una tabla conteniendo la palabra Hola. 
Si ha introducido '[preserve]&lt;table>&lt;tr>&lt;td>Hola &lt;/td>&lt;/tr>&lt;/table>[/preserve]' 
el debe aparecer el c�digo tal como lo introdujo y no la tabla que genera el c�digo.";
$ns -> tablerender("Ayuda Art�culo", $text);
?>