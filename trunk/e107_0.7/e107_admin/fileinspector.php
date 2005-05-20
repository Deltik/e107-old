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
|     $Source: /cvs_backup/e107_0.7/e107_admin/fileinspector.php,v $
|     $Revision: 1.14 $
|     $Date: 2005-05-20 11:14:13 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
require_once('../class2.php');
if (!getperms('Y')) {
	header('location:'.e_BASE.'index.php');
	exit;
}

$e_sub_cat = 'fileinspector';

require_once('auth.php');
$DOCS_DIRECTORY = str_replace('help/', '', $HELP_DIRECTORY);
require_once('core_image.php');
$fi = new file_inspector;
require_once(e_HANDLER.'form_handler.php');
$rs = new form;

if (e_QUERY == 'snapshot') {
	$fi -> snapshot_interface();
} else if (e_QUERY == 'results') {
	$fi -> scan_results();
	$fi -> scan_config();
} else {
	$fi -> scan_config();
}

class file_inspector {
	
	var $root_dir;
	var $files_text = array();
	var $image = array();
	var $parent;
	var $count = array();
	
	function file_inspector() {
		global $e107;
		$this -> root_dir = $e107 -> file_path;
		if (substr($this -> root_dir, -1) == '/') {
			$this -> root_dir = substr($this -> root_dir, 0, -1);
		}
		if ($_POST['display'] == 'fail') {
			$_POST['integrity'] = TRUE;
		}
	}
	
	function scan_config() {
		global $ns, $rs;

		$text = "<div style='text-align: center'>
		<form action='".e_SELF."?results' method='post' id='scanform'>
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>".FC_LAN_2."</td>
		</tr>";
		
		$text .= "<tr>
		<td class='forumheader3' style='width: 35%'>
		".FC_LAN_3.":
		</td>
		<td colspan='2' class='forumheader3' style='width: 65%'>
		<input type='radio' name='display' value='all'".(($_POST['display'] == 'all' || !isset($_POST['display'])) ? " checked='checked'" : "")." /> ".FC_LAN_4."&nbsp;&nbsp;
		<input type='radio' name='display' value='core'".($_POST['display'] == 'core' ? " checked='checked'" : "")." /> ".FC_LAN_5."&nbsp;&nbsp;
		<input type='radio' name='display' value='fail'".($_POST['display'] == 'fail' ? " checked='checked'" : "")." /> ".FC_LAN_6."&nbsp;&nbsp;
		<input type='radio' name='display' value='non'".($_POST['display'] == 'non' ? " checked='checked'" : "")." /> ".FC_LAN_7."&nbsp;&nbsp;
		</td>
		</tr>";
		
		$text .= "<tr>
		<td class='forumheader3' style='width: 35%'>
		".FC_LAN_8.":
		</td>
		<td class='forumheader3' style='width: 65%; vertical-align: top'>
		<input type='radio' name='integrity' value='1'".(($_POST['integrity'] == '1' || !isset($_POST['integrityy'])) ? " checked='checked'" : "")." /> ".FC_LAN_9."&nbsp;&nbsp;
		<input type='radio' name='integrity' value='0'".($_POST['integrity'] == '0' ? " checked='checked'" : "")." /> ".FC_LAN_10."&nbsp;&nbsp;
		</td></tr>
		<tr>
		<td colspan='2' style='text-align:center' class='forumheader'>".$rs -> form_button('submit', 'scan', FC_LAN_11)."</td>
		</tr>
		</table>
		</form>
		</div>";

		$ns -> tablerender(FC_LAN_1, $text);
		
	}

	function inspect($dir, $level, &$tree_end, &$parent_expand) {
		global $core_image;
		unset ($childOut);
		$parent_expand = false;
		$dir_id = dechex(crc32($dir));
		$this -> files[$dir_id]['.']['level'] = $level;
		$this -> files[$dir_id]['.']['parent'] = $this -> parent;
		$directory = $level ? basename($dir) : SITENAME;
		$level++;
		$handle = opendir($dir);
		while (false !== ($readdir = readdir($handle))) {
			if ($readdir != '.' && $readdir != '..' && $readdir != '/' && $readdir != 'CVS' && $readdir != 'Thumbs.db' && (strpos('._', $readdir) === FALSE)) {
				$this -> parent = $dir_id;
				$path = $dir.'/'.$readdir;
				$i_path = str_replace($this -> root_dir.'/', '', $path);
				if (is_dir($path)) {
					$child_open = false;
					$child_end = true;
					$childOut .= $this -> inspect($path, $level, $child_end, $child_expand);
					$tree_end = false;
					if ($child_expand) {
						$parent_expand = TRUE;
						$last_expand = true;
					}
				} else {
					 if ($_POST['display'] == 'all' || ($_POST['display'] == 'fail' && isset($core_image[$i_path]) && $readdir != 'core_image.php' && $this -> checksum($path) != $core_image[$i_path]) || ($_POST['display'] == 'core' && isset($core_image[$i_path])) || ($_POST['display'] == 'non' && !isset($core_image[$i_path]))) {
						$fid = strtolower($readdir);
						$filesize = filesize($path);
						$size = $this -> parsesize($filesize);
						if (isset($core_image[$i_path])) {
							$this -> count['core']['num']++;
							$this -> count['core']['size'] += $filesize;
						}
						if ($_POST['display'] != 'fail' && !isset($core_image[$i_path])) {
							$this -> count['unknown']['num']++;
							$this -> count['unknown']['size'] += $filesize;
							$file_icon = 'file_unknown.png';
							$dir_icon = ($dir_icon == 'folder_warning.png') ? 'folder_warning.png' : 'folder_unknown.png';
							$parent_expand = TRUE;
						} else if ($_POST['display'] != 'fail' && !$_POST['integrity']) {
							$file_icon = 'file_core.png';
							$dir_icon = ($dir_icon == 'folder_unknown.png') ? 'folder_unknown.png' : 'folder_core.png';
						} else if ($readdir != 'core_image.php') {
							if ($_POST['display'] == 'fail' || $this -> checksum($path) != $core_image[$i_path]) {
								$this -> count['fail']['num']++;
								$this -> count['fail']['size'] += $filesize;
								$file_icon = 'file_warning.png';
								$dir_icon = 'folder_warning.png';
								$parent_expand = TRUE;
							} else {
								$this -> count['pass']['num']++;
								$this -> count['pass']['size'] += $filesize;
								$file_icon = 'file_check.png';
								$dir_icon = ($dir_icon == 'folder_warning.png' || $dir_icon == 'folder_unknown.png') ? $dir_icon : 'folder_check.png';
							}
						}
						$this -> files[$dir_id][$fid]['file'] = $readdir;
						$this -> files[$dir_id][$fid]['icon'] = $file_icon;
						$this -> files[$dir_id][$fid]['size'] = $size;
					}
				}
			}
		}

		if (!$dir_icon) {
			$dir_icon = 'folder.png';
		}
		$icon = "<img src='".e_IMAGE."fileinspector/".$dir_icon."' class='i' alt='' />";
		$hide = ($parent_expand && $last_expand && $dir_icon != 'folder_core.png') ? "" : "style='display: none'";
		$text = "<div class='d' style='margin-left: ".($level * 8)."px'>";
		$text .= $tree_end ? "<img src='".e_IMAGE."fileinspector/blank.png' class='e' alt='' />" : "<span onclick=\"ec('".$dir_id."')\"><img src='".e_IMAGE."fileinspector/".($hide ? 'expand.png' : 'contract.png')."' class='e' alt='' id='e_".$dir_id."' /></span>";
		$text .= $tree_end ? "&nbsp;<span onclick=\"sh('f_".$dir_id."')\">".$icon."&nbsp;".$directory."</span>" : "&nbsp;<span onclick=\"sh('f_".$dir_id."')\">".$icon."&nbsp;".$directory."</span>";
		$text .= $tree_end ? "" : "<div ".$hide." id='d_".$dir_id."'>".$childOut."</div>";
		$text .= "</div>";

		return $text;
		closedir($handle);
	}
	
	function scan_results() {
		global $ns, $rs;
		$scan_text = $this -> inspect($this -> root_dir, 0);
		
		$text = "<div style='text-align:center'>
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>".FR_LAN_2."</td>
		</tr>";

		$text .= "<tr style='display: none'><td style='width:50%'></td><td style='width:50%'></td></tr>";
		
		$text .= "<tr>
		<td class='forumheader3' style='width:50%'>
		<div style='height: 300px; overflow: auto'>
		".$scan_text."
		</div>
		</td>
		<td class='forumheader3' style='width:50%; vertical-align: top'><div style='height: 300px; overflow: auto'>";

		$text .= "<table class='t' id='initial'>
		<tr><td class='f' style='padding-left: 4px'>
		<img src='".e_IMAGE."fileinspector/fileinspector.png' class='i' alt='' />&nbsp;<b>".FR_LAN_3."</b></td>
		<td class='s' style='text-align: right; padding-right: 4px' onclick=\"sh('f_".dechex(crc32($this -> root_dir))."')\">
		<img src='".e_IMAGE."fileinspector/forward.png' class='i' alt='' /></td></tr>";
		if ($_POST['display'] != 'fail' && $_POST['display'] != 'non') {
			$text .= "<tr><td class='f'><img src='".e_IMAGE."fileinspector/file_core.png' class='i' alt='' />&nbsp;".FR_LAN_4.":&nbsp;".($this -> count['core']['num'] ? $this -> count['core']['num'] : 'none')."&nbsp;</td><td class='s'>".$this -> parsesize($this -> count['core']['size'], 2)."</td></tr>";
		}
		if ($_POST['display'] != 'fail' && $_POST['display'] != 'core') {
			$text .= "<tr><td class='f'><img src='".e_IMAGE."fileinspector/file_unknown.png' class='i' alt='' />&nbsp;".FR_LAN_5.":&nbsp;".($this -> count['unknown']['num'] ? $this -> count['unknown']['num'] : FR_LAN_21)."&nbsp;</td><td class='s'>".$this -> parsesize($this -> count['unknown']['size'], 2)."</td></tr>";
		}
		if ($_POST['display'] == 'all') {
			$text .= "<tr><td class='f'><img src='".e_IMAGE."fileinspector/file.png' class='i' alt='' />&nbsp;".FR_LAN_6.":&nbsp;".($this -> count['core']['num'] + $this -> count['unknown']['num'])."&nbsp;</td><td class='s'>".$this -> parsesize($this -> count['core']['size'] + $this -> count['unknown']['size'], 2)."</td></tr>";
		}
		if ($_POST['integrity'] && $_POST['display'] != 'non') {
			$integrity_icon = $this -> count['fail']['num'] ? 'integrity_fail.png' : 'integrity_pass.png';
			$integrity_text = $this -> count['fail']['num'] ? '( '.$this -> count['fail']['num'].' '.FR_LAN_19.' )' : '( '.FR_LAN_20.' )';
			$text .= "<tr><td colspan='2'>&nbsp;</td></tr>";
			$text .= "<tr><td class='f' style='padding-left: 4px' colspan='2'>
			<img src='".e_IMAGE."fileinspector/".$integrity_icon."' class='i' alt='' />&nbsp;<b>".FR_LAN_7."</b> ".$integrity_text."</td></tr>";
		
			if ($_POST['display'] != 'fail' && $_POST['display'] != 'non' && $_POST['integrity']) {
				$text .= "<tr><td class='f'><img src='".e_IMAGE."fileinspector/file_check.png' class='i' alt='' />&nbsp;".FR_LAN_8.":&nbsp;".($this -> count['pass']['num'] ? $this -> count['pass']['num'] : FR_LAN_21)."&nbsp;</td><td class='s'>".$this -> parsesize($this -> count['pass']['size'], 2)."</td></tr>";
			}
			if ($_POST['display'] != 'non' && $_POST['integrity']) {
				$text .= "<tr><td class='f'><img src='".e_IMAGE."fileinspector/file_warning.png' class='i' alt='' />&nbsp;".FR_LAN_9.":&nbsp;".($this -> count['fail']['num'] ? $this -> count['fail']['num'] : FR_LAN_21)."&nbsp;</td><td class='s'>".$this -> parsesize($this -> count['fail']['size'], 2)."</td></tr>";
			}
		
			$text .= "<tr><td colspan='2'>&nbsp;</td></tr>";

			$text .= "<tr><td class='f' colspan='2'><img src='".e_IMAGE."fileinspector/info.png' class='i' alt='' />&nbsp;".FR_LAN_10.":&nbsp;</td></tr>";

			$text .= "<tr><td style='padding-right: 4px' colspan='2'>
			<ul><li>
			<a href=\"javascript: expandit('i_corrupt')\">".FR_LAN_11."...</a><div style='display: none' id='i_corrupt'>
			".FR_LAN_12."<br /><br /></div>
			</li><li>
			<a href=\"javascript: expandit('i_date')\">".FR_LAN_13."...</a><div style='display: none' id='i_date'>
			".FR_LAN_14."<br /><br /></div>
			</li><li>
			<a href=\"javascript: expandit('i_edit')\">".FR_LAN_15."...</a><div style='display: none' id='i_edit'>
			".FR_LAN_16."<br /><br /></div>
			</li><li>
			<a href=\"javascript: expandit('i_cvs')\">".FR_LAN_17."...</a><div style='display: none' id='i_cvs'>
			".FR_LAN_18."<br /><br /></div>
			</li></ul>
			</td></tr>";
		}

		$text .= "</table>";
		
		foreach ($this -> files as $dir_id => $fid) {
			ksort($fid);
			$text .= "<table class='t' style='display: none' id='f_".$dir_id."'>";
			$initial = FALSE;
			foreach ($fid as $key => $stext) {
				if (!$initial) {
					$text .= "<tr><td class='f' style='padding-left: 4px' ".($stext['level'] ? "onclick=\"sh('f_".$stext['parent']."')\"" : "").">
					<img src='".e_IMAGE."fileinspector/".($stext['level'] ? "folder_up.png" : "folder_root.png")."' class='i' alt='' />".($stext['level'] ? "&nbsp;.." : "")."</td>
					<td class='s' style='text-align: right; padding-right: 4px' onclick=\"sh('initial')\"><img src='".e_IMAGE."fileinspector/close.png' class='i' alt='' /></td></tr>";
				} else {
					$text .= "<tr>
					<td class='f'><img src='".e_IMAGE."fileinspector/".$stext['icon']."' class='i' alt='' />&nbsp;".$stext['file']."&nbsp;</td>
					<td class='s'>".$stext['size']."</td>
					</tr>";
				}
				$initial = TRUE;
			}
			$text .= "</table>";
		}

		$text .= "</div></td></tr>";
		
		$text .= "</table>
		</div><br />";

		$ns -> tablerender(FR_LAN_1.'...', $text);
	}
	
	function image_scan($dir) {
		$handle = opendir($dir);
		while (false !== ($readdir = readdir($handle))) {
			if ($readdir != '.' && $readdir != '..' && $readdir != '/' && $readdir != 'CVS' && $readdir != 'Thumbs.db' && (strpos('._', $readdir) === FALSE)) {
				$path = $dir.'/'.$readdir;
				if (is_dir($path)) {
					$this -> image_scan($path);
				} else {
					$this -> image[$path] = $this -> checksum($path, TRUE);
				}
			}
		}
		return FALSE;
		closedir($handle);
	}
	
	function create_image($dir) {
		global $ADMIN_DIRECTORY, $FILES_DIRECTORY, $IMAGES_DIRECTORY, $THEMES_DIRECTORY, $PLUGINS_DIRECTORY, $HANDLERS_DIRECTORY, $LANGUAGES_DIRECTORY, $HELP_DIRECTORY, $DOWNLOADS_DIRECTORY, $DOCS_DIRECTORY;
		$this -> image_scan($dir);
		$data = "<?php\n";
		$data .= "\$core_image = array(
		";
		foreach($this -> image as $path_key => $path_value) {
			$root = str_replace($dir."/", "'", $path_key);
			$search = array("'".$ADMIN_DIRECTORY, "'".$FILES_DIRECTORY, "'".$IMAGES_DIRECTORY, "'".$THEMES_DIRECTORY, "'".$PLUGINS_DIRECTORY, "'".$HANDLERS_DIRECTORY, "'".$LANGUAGES_DIRECTORY, "'".$HELP_DIRECTORY, "'".$DOWNLOADS_DIRECTORY, "'".$DOCS_DIRECTORY);
			$replace = array("\$ADMIN_DIRECTORY.'", "\$FILES_DIRECTORY.'", "\$IMAGES_DIRECTORY.'", "\$THEMES_DIRECTORY.'", "\$PLUGINS_DIRECTORY.'", "\$HANDLERS_DIRECTORY.'", "\$LANGUAGES_DIRECTORY.'", "\$HELP_DIRECTORY.'", "\$DOWNLOADS_DIRECTORY.'", "\$DOCS_DIRECTORY.'");
			$root = str_replace($search, $replace, $root);
			$core_array[] = $root."' => '".$path_value."'";
		}
		$data .= implode(', 
		', $core_array);
		$data .= "\n);\n";
		$data .= "?>";
		$fp = fopen(e_ADMIN.'core_image.php', 'w');
		fwrite($fp, $data);
	}
	
	function checksum($filename, $create = FALSE) {
		$checksum = md5(str_replace(chr(13).chr(10), chr(10), file_get_contents($filename)));
		return $checksum;
	}
	
	function snapshot_interface() {
		global $ns, $rs;
		$text = "";
		if (isset($_POST['create_snapshot'])) {
			$this -> create_image($_POST['snapshot_path']);
			$text = "<div style='text-align:center'>
			<form action='".e_SELF."' method='post' id='main_page'>
			<table style='".ADMIN_WIDTH."' class='fborder'>
			<tr>
			<td class='fcaption'>Snapshot Created</td>
			</tr>";
		
			$text .= "<tr>
			<td class='forumheader3' style='text-align:center'>
			The snapshot (".e_ADMIN."core_image.php) was successfully created.
			</td>
			</tr>
			<tr>
			<td style='text-align:center' class='forumheader'>".$rs -> form_button('submit', 'main_page', 'Return To Main Page')."</td>
			</tr>
			</table>
			</form>
			</div><br />";
		}
		
		$text .= "<div style='text-align:center'>
		<form action='".e_SELF."?".e_QUERY."' method='post' id='snapshot'>
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
		<td class='fcaption' colspan='2'>Create Snapshot</td>
		</tr>";
		
		$text .= "<tr>
		<td class='forumheader3' style='width:50%'>
		Absolute path of root directory to create image from:
		</td>
		<td class='forumheader3' style='width:50%'>
		<input class='tbox' type='text' name='snapshot_path' size='60' value='".$this -> root_dir."' />
		</td></tr>
		<tr>
		<td class='forumheader' style='text-align:center' colspan='2'>".$rs -> form_button('submit', 'create_snapshot', 'Create Snapshot')."</td>
		</tr>
		</table>
		</form>
		</div>";

		$ns -> tablerender('Snapshot', $text);

	}
	
	function parsesize($size = 0, $dec = 0) {
		if (!$size) { return FALSE; }
		$kb = 1024;
		$mb = 1024 * $kb;
		$gb = 1024 * $mb;
		$tb = 1024 * $gb;
		if ($size < $kb) {
			return $size." b";
		}
		else if($size < $mb) {
			return round($size/$kb)." kb";
		}
		else if($size < $gb) {
			return round($size/$mb, $dec)." mb";
		}
		else if($size < $tb) {
			return round($size/$gb, $dec)." gb";
		} else {
			return round($size/$tb, $dec)." tb";
		}
	}
}

require_once('footer.php');

function headerjs() {
global $e107;
$text = "<script type='text/javascript'>
<!--
c = new Image(); c = '".$e107 -> http_abs_location("IMAGES_DIRECTORY", "fileinspector/contract.png")."';
e = '".$e107 -> http_abs_location("IMAGES_DIRECTORY", "fileinspector/expand.png")."';
function ec(ecid) {
	icon = document.getElementById('e_' + ecid).src;
	if (icon == e) {
		document.getElementById('e_' + ecid).src = c;
	} else {
		document.getElementById('e_' + ecid).src = e;
	}

	div = document.getElementById('d_' + ecid).style;
	if (div.display == 'none') {
		div.display = '';
	} else {
		div.display = 'none';
	}
}

var hideid = 'initial';
function sh(showid) {
	if (hideid != showid) {
		show = document.getElementById(showid).style;
		hide = document.getElementById(hideid).style;
		show.display = '';
		hide.display = 'none';
		hideid = showid;
	}
}
//-->
</script>
<style type='text/css'>
<!--
.f { padding: 1px 0px 1px 8px; vertical-align: bottom; width: 90%; white-space: nowrap }
.d { margin: 2px 0px 1px 8px; cursor: default; white-space: nowrap }
.s { padding: 1px 8px 1px 0px; vertical-align: bottom; width: 10%; white-space: nowrap }
.t { margin-top: 1px; width: 100%; border-collapse: collapse; border-spacing: 0px }
.i { width: 16px; height: 16px }
.e { width: 9px; height: 9px }
-->
</style>";
		
return $text;
}

?>