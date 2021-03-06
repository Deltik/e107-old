<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/banlist.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Expulsando usuarios de su sitio";
$text = "Usted puede expulsar usuarios de su sitio desde este �rea.<br />
Escriba una direcci�n IP completa o use comodines para expulsar un rango completo de direcciones IP.
Tambi�n puede escribir una direcci�n email para impedir el registro como miembro en su sitio.<br /><br />
<b>Expulsando por direcci�n IP:</b><br />
Escribiendo la direcci�n IP 123.123.123.123 expulsar� a los visitantes de su sitio que accedan desde esa direcci�n IP.<br />
Escribiendo la direcci�n IP 123.123.123.* expulsar� a todos los visitantes que accedan desde una direcci�n IP dentro de ese rango.<br /><br />
<b>Expulsando por direcci�n email</b><br />
Escribiendo la direcci�n email foo@bar.com expulsar� o no permitir� el registro al miembro registrado con esa direcci�n email.<br />
Escribiendo la direcci�n email *@bar.com expulsar� o no permitir� el registro a los miembros que usen ese dominio en su direcci�n email.";
$ns -> tablerender($caption, $text);
?>