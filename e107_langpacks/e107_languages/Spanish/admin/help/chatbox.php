<?php

$text = "Configura las preferencias de tu Chatbox desde aqu�.<br />
Si la casilla de reemplazo est� marcada, cualquier enlace que hayan introducido ser� reemplazado 
con el texto que escribiste en la caja de texto, 
esto detendr� enlaces largos que causan problemas al mostrar. 
Cortar palabras autocortar� los textos que sean m�s largos que el n�mero especificado aqu�.";

$ns -> tablerender("Chatbox", $text);
?>