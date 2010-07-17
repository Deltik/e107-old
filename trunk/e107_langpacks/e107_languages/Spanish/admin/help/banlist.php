<?php
/*
+ ----------------------------------------------------------------------------+
|     $Sitio web e107 - Archivos del lenguaje $
|     $Versión: 0.7.16 $
|     $Date: 2009/09/16 17:51:27 $
|     $Author: E107 <www.e107.org> $
|     $Traductor: Josico <www.e107.es> $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Expulsando usuarios de su sitio";
$text = "Usted puede expulsar usuarios de su sitio desde este área.<br />
Escriba una dirección IP completa o use comodines para expulsar un rango completo de direcciones IP.
También puede escribir una dirección email para impedir el registro como miembro en su sitio.<br /><br />
<b>Expulsando por dirección IP:</b><br />
Escribiendo la dirección IP 123.123.123.123 expulsará a los visitantes de su sitio que accedan desde esa dirección IP.<br />
Escribiendo la dirección IP 123.123.123.* expulsará a todos los visitantes que accedan desde una dirección IP dentro de ese rango.<br /><br />
<b>Expulsando por dirección email</b><br />
Escribiendo la dirección email foo@bar.com expulsará o no permitirá el registro al miembro registrado con esa dirección email.<br />
Escribiendo la dirección email *@bar.com expulsará o no permitirá el registro a los miembros que usen ese dominio en su dirección email.";
$ns -> tablerender($caption, $text);
?>