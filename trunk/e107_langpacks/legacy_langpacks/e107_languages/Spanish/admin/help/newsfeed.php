<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/newsfeed.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$text = "Puede recuperar y cortar los contenidos de noticias de otros sitios y
pegarlos en su sitio desde aqu�.<br />
Introduzca la direcci�n URL entera hacia el backend (ej http://e107.org/portal).
Si el contenido que est� copiando a su sitio tiene una url enlazada a un bot�n y
desea mostrala deje el cuadro de im�gen en blanco,
de otra manera ponga una direcci�n a la im�gen, o escriba 'none' para no mostrar ninguna imagen.
Luego pulse en las casillas para mostrar exactamente lo que desee en su men� de titulares.
Puede activar y desactivar el backend si el sitio se da de baja por ejemplo.<br /><br />
Para ver los titulares en su sitio, asegurese de que el men� de titulares est� activado desde su p�gina
de men�s.";

$ns -> tablerender("Titulares", $text);
?>