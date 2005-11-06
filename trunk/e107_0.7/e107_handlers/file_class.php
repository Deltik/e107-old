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
|     $Source: /cvs_backup/e107_0.7/e107_handlers/file_class.php,v $
|     $Revision: 1.10 $
|     $Date: 2005-11-06 18:18:59 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/

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
			return $ret;
		}
		if($omit == 'standard') {
			$rejectArray = array('^\.$','^\.\.$','^\/$','^CVS$','thumbs\.db','.*\._$');
		} else {
			if(is_array($omit)) {
				$rejectArray = $omit;
			} else {
				$rejectArray = array($omit);
			}
		}
		while (false !== ($file = readdir($handle)))
		{
			if(is_dir($path.'/'.$file))
			{
				if($file != '.' && $file != '..' && $file != 'CVS' && $recurse_level > 0 && $current_level < $recurse_level)
				{
					$xx = $this->get_files($path.'/'.$file, $fmask, $omit, $recurse_level, $current_level+1);
					$ret = array_merge($ret,$xx);
				}
			}
			elseif ($fmask == '' || preg_match("#".$fmask."#", $file))
			{
				$rejected = FALSE;

				foreach($rejectArray as $rmask)
				{
					if(preg_match("#".$rmask."#", $file))
					{
						$rejected = TRUE;
						break;
					}
				}
				if($rejected == FALSE)
				{
					$finfo['path'] = $path."/";  // important: leave this slash here and update other file instead. 
					$finfo['fname'] = $file;
					$ret[] = $finfo;
				}
			}
		}
		return $ret;
	}

	function get_dirs($path, $fmask = '', $omit='standard') {
		$ret = array();
		if(substr($path,-1) == '/')
		{
			$path = substr($path, 0, -1);
		}

		if(!$handle = opendir($path))
		{
			return $ret;
		}
		if($omit == 'standard')
		{
			$rejectArray = array('^\.$','^\.\.$','^\/$','^CVS$','thumbs\.db','.*\._$');
		}
		else
		{
			if(is_array($omit))
			{
				$rejectArray = $omit;
			}
			else
			{
				$rejectArray = array($omit);
			}
		}
		while (false !== ($file = readdir($handle)))
		{
			if(is_dir($path.'/'.$file) && ($fmask == '' || preg_match("#".$fmask."#", $file)))
			{
				$rejected = FALSE;
				foreach($rejectArray as $rmask)
				{
					if(preg_match("#".$rmask."#", $file))
					{
						$rejected = TRUE;
						break;
					}
				}
				if($rejected == FALSE)
				{
					$ret[] = $file;
				}
			}
		}
		return $ret;
	}
}
?>