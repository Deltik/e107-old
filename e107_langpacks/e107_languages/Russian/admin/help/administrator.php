<?php
/*
+ ----------------------------------------------------------------------------+
|     Russian Language Pack for e107 0.7
|     $Revision: 1.4 $
|     $Date: 2009-09-26 15:53:33 $
|     $Author: yarodin $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

$caption = "Администраторы: Справка";
$text = "Используйте эту страницу для редактирования свойств или удаления администраторов сайта. Администратору будет разрешено обращаться только к тем возможностям, которые отмечены галочкой.<br /><br />
Чтобы создать нового администратора, перейдите на страницу изменения пользователей и добавьте существующему пользователю статус администратора.";
$ns -> tablerender($caption, $text);
?>