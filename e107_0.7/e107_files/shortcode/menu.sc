global $sql;
global $ns;
global $eMenuList;
if (!array_key_exists($parm,$eMenuList)) {
	return;
}
foreach($eMenuList[$parm] as $row) {
	$show_menu = TRUE;
	if($row['menu_pages']) {
		list($listtype,$listpages) = explode("-",$row['menu_pages']);
		$pagelist = explode("|",$listpages);
		$check_url = e_SELF."?".e_QUERY;
		if($listtype == '1')  //show menu
		{
			$show_menu = FALSE;
			foreach($pagelist as $p) {
				if(strpos($check_url,$p) !== FALSE) {
					$show_menu = TRUE;
				}
			}
		}
		elseif($listtype == '2') //hide menu
		{
			$show_menu = TRUE;
			foreach($pagelist as $p) {
				if(strpos($check_url,$p) !== FALSE) {
					$show_menu = FALSE;
				}
			}
		}
	}
	if($show_menu) {
		$sql->db_Mark_Time($row['menu_name']);
		if($row['menu_path'] != 'custom')
		{
			if(is_readable(e_LANGUAGEDIR.e_LANGUAGE."/plugins/lan_{$row['menu_path']}.php")) {
				include(e_LANGUAGEDIR.e_LANGUAGE."/plugins/lan_{$row['menu_path']}.php");
			} elseif (is_readable(e_PLUGIN.$row['menu_path']."/languages/".e_LANGUAGE.".php")) {
				include(e_PLUGIN.$row['menu_path']."/languages/".e_LANGUAGE.".php");	
			} elseif (is_readable(e_LANGUAGEDIR."english/plugins/lan_{$row['menu_path']}.php")) {
				include(e_LANGUAGEDIR."English/plugins/lan_{$row['menu_path']}.php");
			} elseif (is_readable(e_PLUGIN.$row['menu_path']."/languages/English.php")) {
				include(e_PLUGIN.$row['menu_path']."/languages/English.php");
			}
		}
		$mname = $row['menu_name'];
		if(file_exists(e_PLUGIN.$row['menu_path']."/".$mname.".php"))
		{
			include(e_PLUGIN.$row['menu_path']."/".$mname.".php");
		}
		$sql->db_Mark_Time("(After ".$mname.")");
	}
}