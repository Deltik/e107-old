﻿<?php
if(!defined('e_HTTP')){ die("Unauthorised Access");}
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
			$text = "<b>Menus reset in database</b><br /><br />";
		}
}else{
	unset($text);
}

$text .= "
Čia galite valdyti tinklapio meniu vietą ir tvarką. 
Iš skleidžiamo sąrašo galite perkelti meniu žemyn ar aukštyn,kol būsite patenkintas jų išdėstymu.
<br />
<br />
Jei pastebėjote,kad meniu neatsinaujina, paspauskite mygtuką  Refresh.
<br />
<form method='post' id='menurefresh' action='".$_SERVER['PHP_SELF']."'>
<div><input type='submit' class='button' name='reset' value='Refresh' /></div>
</form>
<br />
<div class='indent'><span style='color:red'>*</span> indicates menu visibility has been modified</div>
";

$ns -> tablerender("Menus Help", $text);
?>