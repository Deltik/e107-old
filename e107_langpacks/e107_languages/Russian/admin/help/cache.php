<?php
/*
+ ----------------------------------------------------------------------------+
|     Russian Language Pack for e107 0.7
|     $Revision: 1.1 $
|     $Date: 2006-09-13 11:01:32 $
|     $Author: verant $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "Кэширование";
$text = "Если вы включите кэширование, это значительно увеличит скорость вашего сайта и минимизирует количество вызовов к базе данных SQL.<br /><br /><b>ВАЖНО! Если вы создаете ссобственную тему - выключите кэширование, т. к. в противном случае изменения, которые вы сделаете, не будут отражены.</b>";
$ns -> tablerender($caption, $text);
?>