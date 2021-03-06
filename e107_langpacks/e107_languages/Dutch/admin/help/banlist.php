<?php
/*
 * Dutch Language File for the
 *   e107 website system (http://e107.org).
 * Released under the terms and conditions of the
 *   GNU General Public License v3 (http://gnu.org).
 * $HeadURL$
 * $Revision$
 * $Date$
 * $Author$
 */

if (!defined('e107_INIT')) { exit(); }

$caption = "Blokkeer toegang tot je site";
$text = "Je kunt in dit scherm gebruikers de toegang tot je site ontzeggen.<br />
Dat kan ofwel met volledige IP-adres of met jokertekens om een hele serie IP-adressen te blokkeren. Je kunt ook een e-mailadres opgeven om te verhinderen dat iemand zich aanmeldt als lid.<br /><br />
<b>Blokkeren op IP adres:</b><br />
Het invoeren van het IP adres 123.123.123.123 blokkeert toegang tot je site vanaf dat ip-adres.<br />
Het invoeren van het ip adres 123.123.123.* verhindert toegang tot je site vanaf deze hele serie adressen.<br /><br />
<b>Blokkeren van e-mailadressen</b><br />
Het invoeren van het e-mailadres foo@bar.com verhindert dat iemand zich met dat e-mailadres aanmeldt op je site.<br />
Het invoeren van het e-mailadres *@bar.com voorkomt dat iemand met een e-mailadres van dat domein aanmeldt.";
$ns -> tablerender($caption, $text);

?>