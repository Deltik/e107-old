<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Spanish/admin/help/forum.php,v $
|     $Revision: 1.6 $
|     $Date: 2005-11-11 23:49:25 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
$caption = "Foro de Ayuda";
$text = "<b>General</b><br />
Use este �rea para crear o editar tus foros<br />
<br />
<b>Foros/Categor�as</b><br />
Un Padre es un encabezado desde el que se despliegan otros foros,
esto har� las exposiciones m�s simples y har� que la navegaci�n en sus foros sea m�s simple
para sus usuarios.
<br /><br />
<b>Accesibilidad</b>
<br />
Puede configurar sus foros para hacerlos accesibles solo para ciertos visitantes.
Una vez que haya configurado la 'clase' de los usuarios puede marcar la
clase para permitir solo los accesos de esos visitantes a los foros.
Puede configurar cagtegor�as o foros de esta misma manera.
<br /><br />
<b>Moderadores</b>
<br />
Marque los nombres de los administradores listados para darles estado de moderador en el foro.
El administrador debe tener permisos de moderaci�n de foro para estar listado aqu�.
<br /><br />
<b>Rangos</b>
<br />
Configure sus rangos de usuario desde aqu�. Si el campo de im�genes esta relleno se usar�n im�genes, para usar nombres de rango escriba los nombres y aseg�rese de que la imagen en el campo del rango correspondiente est� en blanco.
<br />El umbral es el n�mero de puntos que el usuario necesita aumentar antes de que su nivel cambie.";

$ns -> tablerender($caption, $text);
unset($text);
?>