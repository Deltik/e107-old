<?php

class e_file {
	function get_files($path, $fmask = '', $omit='standard', $recurse_level = 0, $current_level = 0) {
		$ret = array();
		if($recurse_level != 0 && $current_level > $recurse_level) {
			return $ret;
		}
		if(substr($path,-1) == '/') {
			$path = substr($path, 0, -1);
		}
		if(!$handle = opendir($path)) {
			echo "Unable to open: $path <br />";
			return $ret;
		}
		if($omit == 'standard') {
			$rejectArray = array('.','..','/','CVS','thumbs.db','*._$');
		} else {
			if(is_array($omit)) {
				$rejectArray = $omit;
			} else {
				$rejectArray = array($omit);
			}
		}
		while (false !== ($file = readdir($handle))) {
//			echo $file."<br />";
			if(is_dir($path.'/'.$file)) {
				if($file != '.' && $file != '..' && $file != 'CVS' && $recurse_level > 0 && $current_level < $recurse_level) {
					$xx = $this->get_files($path.'/'.$file, $fmask, $omit, $recurse_level, $current_level+1);
//					showvar($xx);
					$ret = array_merge($ret,$xx);
				}
				//				$ret = array_merge($ret, $this->get_files($path.'/'.$file, $fmask, $omit, $recurse_level, $current_level+1));
			} elseif ($fmask == '' || preg_match($fmask,$file)) {
				$rejected = FALSE;
				foreach($rejectArray as $rmask) {
					if(preg_match(preg_quote($rmask),$file)) {
						$rejected = TRUE;
						break;
					}
				}
				if($rejected == FALSE) {
					$finfo['path'] = $path;
					$finfo['fname'] = $file;
					$ret[] = $finfo;
				}
			}
		}
		return $ret;
	}
}
?>
