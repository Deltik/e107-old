<?php
/*
+ ----------------------------------------------------------------------------+
|     Russian Language Pack for e107 0.7
|     $Revision: 1.1 $
|     $Date: 2006-09-15 10:16:03 $
|     $Author: verant $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$text = "Пожалуйста, загружайте на сервер файлы в папку загрузок ".e_FILE.", ваши изображения в папку ".e_FILE."downloadimages и эскизы в ".e_FILE."downloadthumbs.
</br></br>
Чтобы предложить загрузку, нужно вначале создать родителя, затем создать категорию внутри родителя, затем вы сможете сделать доступной загрузку.";
$ns -> tablerender("Загрузки: Справка", $text);
?>