if (ADMIN) {
	global $e107_plug, $ns, $pref;
	if ($pref['admin_alerts_ok'] == 1) {
		ob_start();
		$text = "";
		$i = 0;
		$adminfpage = (!$pref['adminstyle'] || $pref['adminstyle'] == "default" ? "admin.php" : $pref['adminstyle'].".php");
		if (strstr(e_SELF, "/".$adminfpage)) {
			global $sql;
			if ($sql -> db_Select("plugin", "*", "plugin_installflag=1")) {
				while($rowplug = $sql -> db_Fetch()){
					extract($rowplug);
					$e107_plug[$rowplug[1]] = $rowplug[3];
				}
			}
		}
		foreach(array_keys($e107_plug) as $xplug){
			if (file_exists(e_PLUGIN.$e107_plug[$xplug]."/admin_info.php")) {
				if ($pref['admin_alerts_uniquemenu'] == 1) {
					$text .= "<b>".$xplug."</b><br />";
				} else {
					$text = "";
				}
				require_once(e_PLUGIN.$e107_plug[$xplug]."/admin_info.php");
				$text .= "<br />";
				if ($pref['admin_alerts_uniquemenu'] != 1) {
					$caption = $xplug;
					$ns -> tablerender($caption, $text);
				} else {
					$text .= "<br />";
				}
				$i++;
			}
		}
    
		$caption = LAN_head_6;
    
		if ($i>0 && $pref['admin_alerts_uniquemenu'] == 1) {
			$ns -> tablerender($caption, $text);
		}
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}
}