<?php
/*
+ ----------------------------------------------------------------------------+
|     Russian Language Pack for e107 0.7
|     $Revision: 1.3 $
|     $Date: 2009-09-26 15:53:33 $
|     $Author: yarodin $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "Если ваш MySQL-сервер поддерживает это, то вы можете выбрать
MySQL-метод сортировки, как наиболее быстрый по сравнении с PHP-методом. Смотрите настройки.<br /><br />
Если ваш сайт включает идеографические языки, такие как Китайский или Японский, вы должны
использовать сортировку PHP и выключить свойство 'Только слово целиком'.";
$ns -> tablerender("Поиск: Справка", $text);
?>