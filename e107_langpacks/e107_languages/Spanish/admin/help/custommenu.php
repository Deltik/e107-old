<?php
$text = "Desde esta p�gina puede crear men�s hechos a medida con su propio contenido.<br /><br /><b>
Importante</b> - 
para usar esta caracter�stica necesitar� configurar los permisos de la carpeta pluglins/custom a CHMOD 777.
<br /><br />
<i>Men� Nombre de archivo</i>: El nombre de su men� a medida, 
el men� ser� guardado como 'custom_nombre.php' en el directorio plugins<br />
<i>Men� T�tulo</i>: El texto desplegado en la barra de t�tulo del men�<br />
<i>Men� Texto</i>: Los datos actuales que son desplegados en el cuerpo del men�, pueden ser textos, 
im�genes, etc";

$ns -> tablerender(CUSLAN_18, $text);
?>