<?php

@include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_userclass.php");
@include_once(e_LANGUAGEDIR."English/lan_userclass.php");

function r_userclass($fieldname, $curval=0, $mode="off"){
	$sql = new db;
	$text="<select class='tbox' name='{$fieldname}'>\n";
	($curval==e_UC_PUBLIC) ? $s=" selected" : $s="";
	$text.="<option  value='".e_UC_PUBLIC."' ".$s.">".UC_LAN_0."\n";
	($curval==e_UC_GUEST) ? $s=" selected" : $s="";
	$text.="<option  value='".e_UC_GUEST."' ".$s.">".UC_LAN_1."\n";
	($curval==e_UC_NOBODY) ? $s=" selected" : $s="";
	$text.="<option value='".e_UC_NOBODY."' ".$s.">".UC_LAN_2."\n";
	($curval==e_UC_MEMBER) ? $s=" selected" : $s="";
	$text.="<option value='".e_UC_MEMBER."' ".$s.">".UC_LAN_3."\n";
	if($sql -> db_Select("userclass_classes")){
		while($row = $sql -> db_Fetch()){
			extract($row);
			($userclass_id==$curval) ? $s=" selected" : $s="";
			$text .= "<option value='$userclass_id' ".$s.">".$userclass_name ."\n";
		}
	}
	if($mode != "off"){
		($curval==e_UC_READONLY) ? $s=" selected" : $s="";
		$text.="<option  value='".e_UC_READONLY."' ".$s.">".UC_LAN_4."\n";
		($curval==e_UC_ADMIN) ? $s=" selected" : $s="";
		$text.="<option  value='".e_UC_ADMIN."' ".$s.">".UC_LAN_5."\n";
	}
	$text.="</select>\n";
	return $text;
}

function r_userclass_radio($fieldname,$curval=0){
	$sql = new db;
	($curval==e_UC_PUBLIC) ? $c=" checked" : $c="";
	$text="<input type='radio' name='{$fieldname}' value='".e_UC_PUBLIC."' ".$c.">".UC_LAN_0."<br />";
	($curval==e_UC_NOBODY) ? $c=" checked" : $c="";
	$text.="<input type='radio' name='{$fieldname}' value='".e_UC_NOBODY."' ".$c.">".UC_LAN_2."<br />";	
	($curval==e_UC_GUEST) ? $c=" checked" : $c="";
	$text.="<input type='radio' name='{$fieldname}' value='".e_UC_GUEST."' ".$c.">".UC_LAN_1."<br />";
	($curval==e_UC_MEMBER) ? $c=" checked" : $c="";
	$text.="<input type='radio' name='{$fieldname}' value='".e_UC_MEMBER."' ".$c.">".UC_LAN_3."<br />";
	if($sql -> db_Select("userclass_classes")){
		while($row = $sql -> db_Fetch()){
			extract($row);
			($userclass_id==$curval) ? $c=" checked" : $c="";
			$text .= "<input type='radio' name='{$fieldname}' value='$userclass_id' ".$c.">".$userclass_name ."<br />";
		}
	}
	return $text;
}

function r_userclass_name($id){
	$sql = new db;
	if($sql -> db_Select("userclass_classes", "userclass_name", "userclass_id=$id")){
		extract($row = $sql -> db_Fetch());
		return $userclass_name;
	}else{
		switch ($id){
			case e_UC_PUBLIC:
				return UC_LAN_0;
			case e_UC_GUEST:
				return UC_LAN_1;
			case e_UC_NOBODY:
				return UC_LAN_2;
			case e_UC_MEMBER:
				return UC_LAN_3;
			case e_UC_READONLY:
				return UC_LAN_4;
			case e_UC_ADMIN:
				return UC_LAN_5;
		}
	}

}
?>