<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Language File.
|
|     $Source: /cvs_backup/e107_langpacks/legacy_langpacks/e107_languages/Swedish/admin/help/menus.php,v $
|     $Revision: 1.1 $
|     $Date: 2005-09-17 14:44:06 $
|     $Author: mrpiercer $
+----------------------------------------------------------------------------+
*/
if(!defined('e_HTTP')){ die("Oauktoriserad Tillg&aring;ng");}
if (!getperms("2")) {
	header("location:".e_BASE."index.php");
	 exit;
}
global $sql;
if(isset($_POST['reset'])){
		for($mc=1;$mc<=5;$mc++){
			$sql -> db_Select("menus","*", "menu_location='".$mc."' ORDER BY menu_order");
			$count = 1;
			$sql2 = new db;
			while(list($menu_id, $menu_name, $menu_location, $menu_order) = $sql-> db_Fetch()){
				$sql2 -> db_Update("menus", "menu_order='$count' WHERE menu_id='$menu_id' ");
				$count++;
			}
			$text = "<b>Menyer &aring;terst&auml;llda i databasen</b><br /><br />";
		}
}else{
	unset($text);
}

$text .= "
Du kan arrangera var och i vilken ordning dina menyer visas h&auml;rifr&aring;n.
Anv&auml;nd rullgardinsmenyn f&ouml;r att flytta menyerna upp och ner tills att du &auml;r n&ouml;jd med deras placering.
<br />
<br />
Om dina menyer inte uppdaterar som de skall, klicka p&aring; knappen uppdatera.
<br />
<form method='post' id='menurefresh' action='".$_SERVER['PHP_SELF']."'>
<div><input type='submit' class='button' name='reset' value='Uppdatera' /></div>
</form>
<br />
<div class='indent'><span style='color:red'>*</span> indikerar att synbarhet f&ouml;r meny &auml;ndrats</div>
";

$ns -> tablerender("Menyhj&auml;lp", $text);

?>
