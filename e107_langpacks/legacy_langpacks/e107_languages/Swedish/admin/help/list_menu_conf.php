<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Swedish/admin/help/list_menu_conf.php,v $
|     $Revision: 1.3 $
|     $Date: 2006-04-19 09:33:06 $
|     $Author: mrpiercer $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "I denna sektion kan du konfigurera 3 menyer&lt;br&gt;
&lt;b&gt;Nya artiklar meny&lt;/b&gt; &lt;br&gt;
Skriv in ett nummer, t.ex. '5' i det f&ouml;rsta f&auml;ltet f&ouml;r att visa de fem f&ouml;rsta artiklarna, l&auml;mna tomt f&ouml;r att visa alla. Du konfigurerar vad rubriken till l&auml;nken skall vara f&ouml;r resten av artiklarna i det andra f&auml;ltet, om du l&auml;mnar det sista valet tomt skapa ingen l&auml;nk, t.ex.: 'Alla artiklar'&lt;br&gt;
&lt;b&gt;Kommentarer/Forum meny&lt;/b&gt; &lt;br&gt;
Antalet kommentarer &auml;r 5 som standard, antalet tecken &auml;r 10000 som standard. Postfixet &auml;r till f&ouml;r om en rad &auml;r f&ouml;r l&aring;ng s&aring; kommer den att kapas och s&aring; l&auml;ggs postfixet till sist, ett bra val kan vara '...', markera original&auml;mnena om du vill se dessa i &ouml;versikten.&lt;br&gt;

";
$ns -> tablerender("Hj&auml;lp menykonfiguration", $text);

?>
