<?php
/*
+ ----------------------------------------------------------------------------+
|     Russian Language Pack for e107 0.7
|     $Revision: 1.4 $
|     $Date: 2006-10-29 17:19:35 $
|     $Author: yarodin $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }
$text = "В данной секции вы можете настроить 3 меню<br>
<b>Меню Новых Статей</b> <br />
Введите например число '5' в первое поле для показа 5и первых статей, или оставьте его пустым для отображения всех статей. Во втором поле вы можете задать, как должна быть названа ссылка на все остальные статьи, если вы оставите это поле пустым, то ссылка не будет создана, например: 'Все статьи'<br>
<b>Меню Комментариев/Форума</b> <br>
По умолчанию количество комментариев равно 5, количество символов равно 10000. Если строка слишком длинная, то она будет обрезана и в ее конец будет добавлен постфикс. Как правило в качестве постфикса используется '...'.  Выберите темы форума, которые будут отображаться в кратком обзоре.<br>

";
$ns -> tablerender("Настройка меню: Справка", $text);
?>
