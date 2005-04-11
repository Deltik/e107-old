if (ADMIN) {
	if (!function_exists('admin_status')) {
		function admin_status() {
			global $sql, $ns, $pref;
			$members = $sql -> db_Count("user");
			$unverified = $sql -> db_Count("user", "(*)", "WHERE user_ban=2");
			$banned = $sql -> db_Count("user", "(*)", "WHERE user_ban=1");
			$comments = $sql -> db_Count("comments");

			$text = "<div style='padding-bottom: 2px;'>".E_16_USER." ".ADLAN_110.": ".$members."</div>";
			$text .= "<div style='padding-bottom: 2px;'>".E_16_USER." ".ADLAN_111.": ".$unverified."</div>";
			$text .= "<div style='padding-bottom: 2px;'>".E_16_BANLIST." ".ADLAN_112.": ".$banned."</div>";
			$text .= "<div style='padding-bottom: 2px;'>".E_16_COMMENT." ".ADLAN_114.": ".$comments."</div>";

			if ($pref['plug_status']) {
				$stas = explode(",", $pref['plug_status']);
				foreach($stas as $sta) {
					if (file_exists(e_PLUGIN.$sta."/status.php")) {
						include(e_PLUGIN.$sta."/status.php");
					}
				}
			}

			require_once(e_HANDLER.'file_class.php');
			$fl = new e_file;
			$pluginList = $fl->get_files(e_PLUGIN, "^adminlist\.php$", "standard", 1);

			foreach($pluginList as $p)
			{
				$text .= include($p['path']."/".$p['fname']);
			}

			if($flo = $sql -> db_Select("generic", "*", "gen_type='failed_login' "))
			{
				
				$text .= "<img src='".e_IMAGE."admin_images/failedlogin_16.png' alt='' style='vertical-align: middle;' /> <a href='".e_ADMIN."fla.php'>".ADLAN_146.": $flo</a>";
			}


			return $ns -> tablerender(ADLAN_134, $text, '', TRUE);	
		}
	}
	
	if ($parm == 'request') {
		if (function_exists('status_request')) {
			if (status_request()) {
				return admin_status();
			}
		}	
	} else {
		return admin_status();
	}
}