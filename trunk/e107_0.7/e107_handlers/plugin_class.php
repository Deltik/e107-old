<?php

/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_handlers/plugin_class.php,v $
|     $Revision: 1.31 $
|     $Date: 2005-08-30 11:16:17 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

class e107plugin
{

	/**
	 * Returns an array containing details of all plugins in the plugin table - should noramlly use e107plugin::update_plugins_table() first to make sure the table is up to date.
	 *
	 * @return array plugin details
	 */
	function getall($flag)
	{
		global $sql;
		if ($sql->db_Select("plugin","*","plugin_installflag = '$flag' ORDER BY plugin_name ASC"))
		{
			$ret = $sql->db_getList();
 		}
		return ($ret) ? $ret : FALSE;
	}

	/**
	 * Check for new plugins, create entry in plugin table and remove deleted plugins
	 *
	 */
	function update_plugins_table() {
		global $sql, $mySQLprefix, $menu_pref;

		require_once(e_HANDLER.'file_class.php');

		$fl = new e_file;
		$pluginList = $fl->get_files(e_PLUGIN, "^plugin\.php$", "standard", 1);

		foreach($pluginList as $p)
		{
			foreach($defined_vars as $varname) {
				if (substr($varname, 0, 6) == 'eplug_' || substr($varname, 0, 8) == 'upgrade_') {
					unset($$varname);
				}
			}

		 	include_once("{$p['path']}{$p['fname']}");
			$plugin_path = substr(str_replace(e_PLUGIN,"",$p['path']),0,-1);
			if ((!$sql->db_Select("plugin", "plugin_id", "plugin_path = '{$plugin_path}'")) && $eplug_name){
				if (!$eplug_link_url && !$eplug_link && !$eplug_prefs && !$eplug_table_names && !$eplug_user_prefs && !$eplug_sc && !$eplug_userclass && !$eplug_module && !$eplug_bb && !$eplug_latest && !$eplug_status){
					if(is_array($eplug_rss)){
                    	foreach($eplug_rss as $key=>$val){
                        	$feeds[] = $key;
						}
						$plugin_rss = implode(",",$feeds);
					}
					// new plugin, assign entry in plugin table, install is not necessary so mark it as intalled
					$sql->db_Insert("plugin", "0, '{$eplug_name}', '{$eplug_version}', '{$eplug_folder}', 1, '{$plugin_rss}' ");
				}
				else
				{
					// new plugin, assign entry in plugin table, install is necessary
					$sql->db_Insert("plugin", "0, '{$eplug_name}', '{$eplug_version}', '{$eplug_folder}', 0, '' ");
				}
			}
		}

		$sql->db_Select("plugin");
		while ($row = $sql->db_fetch()) {
			if (!is_dir(e_PLUGIN.$row['plugin_path'])) {
				$sql->db_Delete('plugin', "plugin_path = '{$row['plugin_path']}'");
			}
		}
	}

	/**
	 * Returns deatils of a plugin from the plugin table from it's ID
	 *
	 * @param int $id
	 * @return array plugin info
	 */
	function getinfo($id) {
		global $sql;
		$id = intval($id);
		if ($sql->db_Select('plugin', '*', "plugin_id = {$id}")) {
			return $sql->db_Fetch();
		}
	}

	function manage_userclass($action, $class_name, $class_description) {
		global $sql;
		if ($action == 'add') {
			$i = 1;
			while ($sql->db_Select('userclass_classes', '*', "userclass_id='{$i}' ") && $i < e_UC_READONLY) {
				$i++;
			}
			if ($i < e_UC_READONLY) {
				return $sql->db_Insert('userclass_classes', "{$i},'".strip_tags(strtoupper($class_name))."', '{$class_description}' ,".e_UC_PUBLIC);
			} else {
				return FALSE;
			}
		}
		if ($action == 'remove') {
			if ($sql->db_Select('userclass_classes', 'userclass_id', "userclass_name = '{$class_name}'")) {
				$row = $sql->db_Fetch();
				$class_id = $row['userclass_id'];
				if ($sql->db_Delete('userclass_classes', "userclass_id = '{$class_id}'")) {
					if ($sql->db_Select('user', 'user_id, user_class', "user_class REGEXP('^{$class_id}\.') OR user_class REGEXP('\.{$class_id}\.') OR user_class REGEXP('\.{$class_id}$')")) {
						$sql2 = new db;
						while ($row = $sql->db_Fetch()) {
							$classes = explode(".", $row['user_class']);
							unset($classes[$class_id]);
							foreach($classes as $k => $v) {
								if ($v = '') {
									unset($classes[$k]);
								}
							}
							$newclass = '.'.implode('.', $classes).'.';
							$sql2->db_Update('user', "user_class = '{$newclass}' WHERE user_id = '{$row['user_id']}");
						}
					}
				}
			}
		}
	}

	function manage_link($action, $link_url, $link_name) {
		global $sql;
		if ($action == 'add') {
			$path = str_replace("../", "", $link_url);
			$link_t = $sql->db_Count('links');
			if (!$sql->db_Count('links', '(*)', "link_name = '{$link_name}'")) {
				return $sql->db_Insert('links', "0, '{$link_name}', '{$path}', '', '', '1', '".($link_t + 1)."', '0', '0', '0' ");
			} else {
				return FALSE;
			}
		}
		if ($action == 'remove') {
			if ($sql->db_Select('links', 'link_order', "link_name = '{$link_name}'")) {
				$row = $sql->db_Fetch();
				$sql->db_Update('links', "link_order = link_order - 1 WHERE link_order > {$row['link_order']}");
				return $sql->db_Delete('links', "link_name = '{$link_name}'");
			}
		}
	}

	function manage_prefs($action, $var) {
		global $pref;
		if (is_array($var)) {
			if ($action == 'add') {
				foreach($var as $k => $v) {
					$pref[$k] = $v;
				}
			}
			if ($action == 'remove') {
				foreach($var as $k => $v) {
					unset($pref[$k]);
				}
			}
			save_prefs();
		}
	}

	function manage_tables($action, $var) {
		global $sql;
		if ($action == 'add') {
			if (is_array($var)) {
				foreach($var as $tab) {
					if (!$sql->db_Query($tab)) {
						return FALSE;
					}
				}
				return TRUE;
			}
			return TRUE;
		}
		if ($action == 'remove') {
			if (is_array($var)) {
				foreach($var as $tab) {
					$qry = 'DROP TABLE '.MPREFIX.$tab;
					if (!$sql->db_Query($qry)) {
						return $tab;
					}
				}
				return TRUE;
			}
			return TRUE;
		}
	}

	function manage_plugin_prefs($action, $prefname, $plugin_folder, $varArray = '') {
		global $pref;
		if ($prefname == 'plug_sc' || $prefname == 'plug_bb') {
			foreach($varArray as $code) {
				$prefvals[] = "$code:$plugin_folder";
			}
		} else {
			$prefvals[] = $plugin_folder;
		}
		$curvals = explode(',', $pref[$prefname]);

		if ($action == 'add') {
			$newvals = array_merge($curvals, $prefvals);
		}
		if ($action == 'remove') {
			foreach($prefvals as $v) {
				if ($i = array_search($v, $curvals)) {
					unset($curvals[$i]);
				}
			}
			// $newvals = explode(',', $curvals);
			$newvals = $curvals;
		}
		$newvals = array_unique($newvals);
		if(count($newvals) < 2)
		{
			$pref[$prefname] = $newvals[0];
		}
		else
		{
			$pref[$prefname] = implode(',', $newvals);
		}
		if(substr($pref[$prefname], 0, 1) == ",")
		{
			$pref[$prefname] = substr($pref[$prefname], 1);
		}
		save_prefs();
	}

	function manage_search($action, $eplug_folder) {
		global $sql, $sysprefs;
		$search_prefs = $sysprefs -> getArray('search_prefs');
		$default = file_exists(e_PLUGIN.$eplug_folder.'/e_search.php') ? TRUE : FALSE;
		$comments = file_exists(e_PLUGIN.$eplug_folder.'/search/search_comments.php') ? TRUE : FALSE;
		if ($action == 'add'){
			$install_default = $default ? TRUE : FALSE;
			$install_comments = $comments ? TRUE : FALSE;
		} else if ($action == 'remove'){
			$uninstall_default = isset($search_prefs['plug_handlers'][$eplug_folder]) ? TRUE : FALSE;
			$uninstall_comments = isset($search_prefs['comments_handlers'][$eplug_folder]) ? TRUE : FALSE;
		} else if ($action == 'upgrade'){
			if (isset($search_prefs['plug_handlers'][$eplug_folder])) {
				$uninstall_default = $default ? FALSE : TRUE;
			} else {
				$install_default = $default ? TRUE : FALSE;
			}
			if (isset($search_prefs['comments_handlers'][$eplug_folder])) {
				$uninstall_comments = $comments ? FALSE : TRUE;
			} else {
				$install_comments = $comments ? TRUE : FALSE;
			}
		}
		if ($install_default) {
			$search_prefs['plug_handlers'][$eplug_folder] = array('class' => 0, 'pre_title' => 1, 'pre_title_alt' => '', 'chars' => 150, 'results' => 10);
		} else if ($uninstall_default) {
			unset($search_prefs['plug_handlers'][$eplug_folder]);
		}
		if ($install_comments) {
			require_once(e_PLUGIN.$eplug_folder.'/search/search_comments.php');
			$search_prefs['comments_handlers'][$eplug_folder] = array('id' => $comments_type_id, 'class' => 0, 'dir' => $eplug_folder);
		} else if ($uninstall_comments) {
			unset($search_prefs['comments_handlers'][$eplug_folder]);
		}
		$tmp = addslashes(serialize($search_prefs));
		$sql->db_Update("core", "e107_value = '{$tmp}' WHERE e107_name = 'search_prefs' ");
	}

	function manage_notify($action, $eplug_folder) {
		global $sql, $sysprefs, $eArrayStorage, $tp;
		$notify_prefs = $sysprefs -> get('notify_prefs');
		$notify_prefs = $eArrayStorage -> ReadArray($notify_prefs);
		$e_notify = file_exists(e_PLUGIN.$eplug_folder.'/e_notify.php') ? TRUE : FALSE;
		if ($action == 'add'){
			$install_notify = $e_notify ? TRUE : FALSE;
		} else if ($action == 'remove'){
			$uninstall_notify = isset($notify_prefs['plugins'][$eplug_folder]) ? TRUE : FALSE;
		} else if ($action == 'upgrade'){
			if (isset($notify_prefs['plugins'][$eplug_folder])) {
				$uninstall_notify = $e_notify ? FALSE : TRUE;
			} else {
				$install_notify = $e_notify ? TRUE : FALSE;
			}
		}
		if ($install_notify) {
			$notify_prefs['plugins'][$eplug_folder] = TRUE;
			require_once(e_PLUGIN.$eplug_folder.'/e_notify.php');
			foreach ($config_events as $event_id => $event_text) {
				$notify_prefs['event'][$event_id] = array('type' => 'off', 'class' => '254', 'email' => '');
			}
		} else if ($uninstall_notify) {
			unset($notify_prefs['plugins'][$eplug_folder]);
			require_once(e_PLUGIN.$eplug_folder.'/e_notify.php');
			foreach ($config_events as $event_id => $event_text) {
				unset($notify_prefs['event'][$event_id]);
			}
		}
		$s_prefs = $tp -> recurse_toDB($notify_prefs, true);
		$s_prefs = $eArrayStorage -> WriteArray($s_prefs);
		$sql -> db_Update("core", "e107_value='".$s_prefs."' WHERE e107_name='notify_prefs'");
	}

	/**
	 * Installs a plugin by ID
	 *
	 * @param int $id
	 */
	function install_plugin($id) {
		global $sql, $ns, $sysprefs,$mySQLprefix;

		// install plugin ...
		$plug = $this->getinfo($id);

		if ($plug['plugin_installflag'] == FALSE) {
			include_once(e_PLUGIN.$plug['plugin_path'].'/plugin.php');

			$func = $eplug_folder.'_install';
			if (function_exists($func)) {
				$text .= call_user_func($func);
			}

			if (is_array($eplug_tables)) {
				$result = $this->manage_tables('add', $eplug_tables);
				if ($result === TRUE) {
					$text .= EPL_ADLAN_19.'<br />';
					//success
				} else {
					$text .= EPL_ADLAN_18.'<br />';
					//fail
				}
			}



			if (is_array($eplug_prefs)) {
				$this->manage_prefs('add', $eplug_prefs);
				$text .= EPL_ADLAN_20.'<br />';
			}

			if ($eplug_module === TRUE) {
				$this->manage_plugin_prefs('add', 'modules', $eplug_folder);
			}

			if ($eplug_status === TRUE) {
				$this->manage_plugin_prefs('add', 'plug_status', $eplug_folder);
			}

			if ($eplug_latest === TRUE) {
				$this->manage_plugin_prefs('add', 'plug_latest', $eplug_folder);
			}

			if (is_array($eplug_array_pref)){
				foreach($eplug_array_pref as $key => $val){
					$this->manage_plugin_prefs('add', $key, $val);
				}
			}

			if (is_array($eplug_sc)) {
				$this->manage_plugin_prefs('add', 'plug_sc', $eplug_folder, $eplug_sc);
			}

			if (is_array($eplug_bb)) {
				$this->manage_plugin_prefs('add', 'plug_bb', $eplug_folder, $eplug_bb);
			}

			if (is_array($eplug_user_prefs)) {
				$sql->db_Select("core", " e107_value", " e107_name = 'user_entended'");
				$row = $sql->db_Fetch();
				$user_entended = unserialize($row[0]);
				while (list($e_user_pref, $default_value) = each($eplug_user_prefs)) {
					$user_entended[] = $e_user_pref;
					$user_pref['$e_user_pref'] = $default_value;
				}
				save_prefs("user");
				$tmp = addslashes(serialize($user_entended));
				if ($sql->db_Select("core", "e107_value", " e107_name = 'user_entended'")) {
					$sql->db_Update("core", "e107_value = '{$tmp}' WHERE e107_name = 'user_entended' ");
				} else {
					$sql->db_Insert("core", "'user_entended', '{$tmp}' ");
				}
				$text .= EPL_ADLAN_20."<br />";
			}

			if ($eplug_link === TRUE && $eplug_link_url != '' && $eplug_link_name != '') {
				$this->manage_link('add', $eplug_link_url, $eplug_link_name);
			}

			if ($eplug_userclass) {
				$this->manage_userclass('add', $eplug_userclass, $eplug_userclass_description);
			}

			$this -> manage_search('add', $eplug_folder);

			$this -> manage_notify('add', $eplug_folder);

			if(is_array($eplug_rss)){
				foreach($eplug_rss as $key=>$values){
					$rssfeeds[] = $key;
                	$tmp = serialize($values);
					$rssmess .= EPL_ADLAN_46 . ". ($key)<br />";
                }
				$feeds = implode(",",$rssfeeds);
			}
            $plugin_rss = ($feeds) ? $feeds : "";
			$sql->db_Update('plugin', "plugin_installflag = 1, plugin_rss = '$plugin_rss' WHERE plugin_id = '{$id}'");
            if($rssmess){ $text .= $rssmess; }
			$text .= ($eplug_done ? "<br />{$eplug_done}" : "");
		} else {
			$text = EPL_ADLAN_21;
		}
		if($eplug_conffile){ $text .= "&nbsp;<a href='".e_PLUGIN."$eplug_folder/$eplug_conffile'>[".EPL_CONFIGURE."]</a>"; }
		$ns->tablerender(EPL_ADLAN_33, $text);
	}
}

?>