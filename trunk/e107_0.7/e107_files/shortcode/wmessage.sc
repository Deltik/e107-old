// $Id: wmessage.sc,v 1.12 2006-05-30 01:47:46 e107coders Exp $

if (($pref['wmessage_sc'] && $parm == "header") || (!$pref['wmessage_sc'] && $parm !='header') ){
	return;
}

	global $e107;
	if (isset($pref['frontpage']['all']) && $pref['frontpage']['all']) {
		$full_url = ((strpos($pref['frontpage']['all'], 'http') === FALSE) ? SITEURL : '').$pref['frontpage']['all'];
		list($front_url,$front_qry) = explode("?",$full_url);
	} else if (ADMIN) {
		$full_url = ((strpos($pref['frontpage']['254'], 'http') === FALSE) ? SITEURL : '').$pref['frontpage']['254'];
        list($front_url,$front_qry) = explode("?",$full_url);
	} else if (USER) {
		require_once(e_HANDLER.'userclass_class.php');
		$class_list = get_userclass_list();
		foreach ($class_list as $fp_class) {
			if (check_class($fp_class['userclass_id'])) {
				$full_url = ((strpos($pref['frontpage'][$fp_class['userclass_id']], 'http') === FALSE) ? SITEURL : '').$pref['frontpage'][$fp_class['userclass_id']];
                list($front_url,$front_qry) = explode("?",$full_url);
				$class_match = true;
				break;
			}
		}
		if (!$class_match) {
			$full_url = ((strpos($pref['frontpage']['253'], 'http') === FALSE) ? SITEURL : '').$pref['frontpage']['253'];
            list($front_url,$front_qry) = explode("?",$full_url);
		}
	} else {
		$full_url = ((strpos($pref['frontpage']['252'], 'http') === FALSE) ? SITEURL : '').$pref['frontpage']['252'];
        list($front_url,$front_qry) = explode("?",$full_url);
	}

	if (e_SELF == $front_url && e_QUERY == $front_qry) {
		global $sql, $pref, $tp, $ns;
		if (!defined("WMFLAG")) {
			$qry = "
			SELECT * FROM #generic
			WHERE gen_type ='wmessage' AND gen_intdata IN (".USERCLASS_LIST.")";

			if($sql->db_Select_gen($qry))
			{
				while ($row = $sql->db_Fetch())
				{
					$wmessage .= $tp->toHTML($row['gen_chardata'], TRUE, 'parse_sc defs', 'admin')."<br />";
					if(!$wmcaption){
						$wmcaption = $tp->toHTML($row['gen_ip'], TRUE, 'defs');
					}
				}
			}

			if (isset($wmessage) && $wmessage)
			{
				if ($pref['wm_enclose'])
				{
					$ns->tablerender($wmcaption, $wmessage, "wm");
				}
				else
				{
					echo ($wmcaption) ? $wmcaption."<br />" : "";
					echo $wmessage;
				}
			}
		}
	}