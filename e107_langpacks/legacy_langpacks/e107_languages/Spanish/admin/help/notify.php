<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/notify.php,v $
|     $Revision: 1.1 $
|     $Date: 2005-08-28 09:26:19 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
$text = "Notificaciones env�a notificaciones de correo cuando ocurren eventos en el sitio.<br /><br />
Por ejemplo, fijar 'IP expulsada por flooding' a la clase 'Admin' y a todos los Admins se les enviar� una notificaci�n
cuando el lugar est� siendo floodeado.<br /><br />
Tambi�n puede, como otro ejemplo, fijar 'Nuevos elementos enviados por admin' a la clase de usuario 'Miembros' y a todos los usuarios se les mandar�
nuevos elementos que env�e en un correo.<br /><br />
Si desea que las notificaciones se env�en en una direcci�n alternativa - seleccione la opci�n 'Email' y
escriba la direcci�n en el campo proporcionado.";

$ns -> tablerender("Ayufa Notificaciones", $text);
?>