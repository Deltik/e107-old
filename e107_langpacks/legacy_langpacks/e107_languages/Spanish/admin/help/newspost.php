<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Spanish/admin/help/newspost.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-12-15 23:43:32 $
|     $Author: natxocc $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
$caption = "Ayuda de mensaje Noticias";
$text = "<b>General</b><br />
El cuerpo ser� desplegado en la p�gina principal,
el texto completo ser� le�do pulsando en 'Leer m�s'.
Recurso y URL es donde est� publicada la noticia.
<br />
<br />
<b>Atajos</b><br />
Puede usar estos atajos en vez de escribir toda la etiqueta ,
al enviar la noticia el atajo ser� convertido en c�digo xhtml.
<br /><br />
<b>Enlaces</b>
<br />
Por favor use direcciones completas a cualquier enlace a�n si es local.
<br /><br />
<b>Mostrar solo T�tulo</b>
<br />
Permite mostrar solamente el t�tulo de la noticia con un enlace para leer la noticia completa.
<br /><br />
<b>Estado</b>
<br />
Si pulsa el bot�n para deshabilitar la noticia no ser� mostrada en tu p�gina principal.
<br /><br />
<b>Activaci�n</b>
<br />
Si configura una fecha de inicio/fin tu noticia solo ser� mostrada entre estas fechas.
";
$ns -> tablerender($caption, $text);
?>