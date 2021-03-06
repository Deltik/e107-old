<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ・teve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Japanese/admin/help/userclass2.php,v $
|     $Revision: 1.1 $
|     $Date: 2006-12-21 15:43:46 $
|     $Author: e107coders $
+-----Translation Updated by: difda on 2006/01/18---------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "ユーザークラス Help";
$text = "あなたはこのページからクラスを作成したり,既存のクラスを編集／削除することができます.<br />これは,ユーザーをサイトの特定の部分に制限することに役立ちます.たとえば,あなたはTESTと呼ばれているクラスを作成することができて,そしてTESTクラスでユーザーがそれにアクセスするのを許すだけのフォーラムをつくることができます.";
$ns -> tablerender($caption, $text);
?>