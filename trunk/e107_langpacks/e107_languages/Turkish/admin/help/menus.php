<?php
if(IsSet($_POST['reset'])){
	if(!check_class("FAKE","",TRUE)){
		$text = "<b>Bu s�rece izin yok</b><br /><br />";
	} else {
		for($mc=1;$mc<=5;$mc++){
			$sql -> db_Select("menus","*", "menu_location='$mc' ORDER BY menu_order");
			$count = 1;
			$sql2 = new db;
			while(list($menu_id, $menu_name, $menu_location, $menu_order) = $sql-> db_Fetch()){
				$sql2 -> db_Update("menus", "menu_order='$count' WHERE menu_id='$menu_id' ");
				$count++;
			}
			$text = "<b>Veri bankas� men� s�f�rlama</b><br /><br />";
		}
	}
}else{
	unset($text);
}

$text .= "
Buradan CMS Men�lerinizin s�ralamas�n� ve yerini aranje edebilirsiniz.
Men�lerinizi istedi�iniz yere getirmek i�in A��l�r Men�y� kullan�n. Men� pozisyonlar� ile memnun olana dek kayd�rmaca  yapabilirsiniz.
<br />
<br />
�ayet g�r�n�m�n, sizin isteklerinize uygun ayarda g�z�km�yorsa (g�r�n�m) g�ncelle�tir butonuna t�klay�n.
<br />
<form method='post' id='menurefresh' action='".$_SERVER['PHP_SELF']."'>
<div><input type='submit' class='button' name='reset' value='Refresh' /></div>
</form>
<br />
<div class='indent'><span style='color:red'>*</span> Men� g�r�n�m� de�i�tirildi</div>
";

$ns -> tablerender("Men�ler Yard�m", $text);
?>

