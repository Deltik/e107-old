<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     ฉSteve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_langpacks/e107_languages/Thai/admin/help/content.php,v $
|     $Revision: 1.1 $
|     $Date: 2007-03-22 00:34:29 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
แปลและพัฒนาส่วนระบบภาษาไทยโดย ผศ.ประชิด ทิณบุตร เมื่อวันที่ 18 มีนาคม 2549  แก้ไขล่าสุด 28 พย.2549
อาจารย์ประจำสาขาวิชาศิลปกรรม มหาวิทยาลัยราชภัฏจันทรเกษม ถนนรัชดาภิเษก เขตจตุจักร กทม 10900.โทร.(66) 0 2942 6900  ต่อ 3011,3014
Thai Developer & Translation : Assistant Professor Prachid Tinnabutr : Division of Art ,Chandrakasem Rajabhat University,Jatuchak,Bangkok ,Thailand.10900. Tel :(66) 02 9426900 ext:3011,3014
Last update:28 nov 2006 .
Personal Address : 144/157 Moo 1 ,Changwatana Rd.Pakkret District ,Nonthaburi Province,Thailand,11120 Tel/Fax:(66)0 2962 9505  prachid@prachid.com,prachid@wittycomputer.com ,Mobile Phone : (66) 08 9667 0091
URL : http://www.prachid.com, http://www.wittycomputer.com, http://www.e107thailand.com
*/

if (!defined('e107_INIT')) { exit; }

$text = "You can add a normal page to your site using this feature. A link to the new page will be created in your main site navigation box. For example, if you create a new page with the Link Name 'Test', a link called 'Test' will appear in your links box after submitting the new page.<br />
If you want your content page to have a caption, enter it in the Page Heading box.";
$ns -> tablerender("Content Help", $text);
?>