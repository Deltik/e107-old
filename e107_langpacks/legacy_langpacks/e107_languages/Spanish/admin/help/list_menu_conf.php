<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/list_menu_conf.php,v $
|     $Revision: 1.1 $
|     $Date: 2005-08-28 09:26:19 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
$text = "En esta secci�n puede configurar 3 men�s<br>
<b> Men� de Nuevos Art�culos</b> <br>
Introduzca un n�mero, por ejemplo '5' en el primer campo para mostrar los 5 primeros art�culos o
deje en blanco para ver todos.
Puede configurar el t�tulo del enlace para el resto de los art�culos en el segundo campo,
cuando deje esta opci�n en blanco no crear� un enlace, por ejemplo: 'Todos los art�culos'<br>
<b> Men� Comentarios/Foro</b> <br>
El n�mero de comentarios predeterminado es 5,
el n�mero predeterminado de caracteres es 10000.
El mensaje fijo es para cortar lineas muy largas, pondr� un mensaje fijo en el final,
una buena elecci�n para esto es
'...', revise los temas originales si quiere verlos en toda la visi�n de conjunto.<br>

";
$ns -> tablerender("Ayuda Configuraci�n de men�", $text);
?>