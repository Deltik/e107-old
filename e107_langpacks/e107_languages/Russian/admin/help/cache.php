<?php
/*
+ ----------------------------------------------------------------------------+
|     Russian Language Pack for e107 0.7
|     $Revision: 1.5 $
|     $Date: 2009-09-26 15:53:33 $
|     $Author: yarodin $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "Кеширование";
$text = "Если вы включите кеширование, это значительно увеличит скорость вашего сайта и минимизирует количество вызовов к базе данных SQL.<br /><br /><b>ВАЖНО! Если вы создаете собственную тему - выключите кеширование, т. к. в противном случае изменения, которые вы сделаете, не будут отражены.</b>";
$ns -> tablerender($caption, $text);
?>