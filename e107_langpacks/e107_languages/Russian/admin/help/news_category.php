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

$text = "DВы можете разделить новости между различными категориями и позволить посетителям смотреть новости только выбранных категорий. <br /><br />Значки для новостей можно загрузить на сервер как в</br>".e_THEME."-yourtheme-/images/ или в themes/shared/newsicons/.";
$ns -> tablerender("Категории новостей: Справка", $text);
?>