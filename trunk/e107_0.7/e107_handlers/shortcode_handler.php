<?php

/*
+ ----------------------------------------------------------------------------+
| e107 website system
|
| �Steve Dunstan 2001-2002
| http://e107.org
| jalist@e107.org
|
| Released under the terms and conditions of the
| GNU General Public License (http://gnu.org).
|
| $Source: /cvs_backup/e107_0.7/e107_handlers/shortcode_handler.php,v $
| $Revision: 1.45 $
| $Date: 2008-03-02 21:08:20 $
| $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

if (!isset($tp) || !is_object($tp -> e_sc)) {
	$tp->e_sc = new e_shortcode;
}

class e_shortcode {
	var $scList;					// The actual code - added by parsing files or when plugin codes encountered. Array key is the shortcode name.
	var $parseSCFiles;				// True if shortcode file has been parsed
	var $addedCodes;				// Apparently not used
	var $registered_codes;			// Shortcodes added by plugins

	function e_shortcode()
	{
		global $pref, $register_sc;

		if(varset($pref['shortcode_list'],'') != '')
		{
        	foreach($pref['shortcode_list'] as $path=>$namearray)
			{
				foreach($namearray as $code=>$uclass)
				{
					$code = strtoupper($code);
					$this->registered_codes[$code]['type'] = 'plugin';
                	$this->registered_codes[$code]['path'] = $path;
                    $this->registered_codes[$code]['perms'] = $uclass;			// Add this in
				}
			}
		}

		if(isset($register_sc) && is_array($register_sc))
		{
			foreach($register_sc as $code)
			{
				$this->registered_codes[$code]['type'] = 'theme';
			}
		}
	}

	function parseCodes($text, $useSCFiles = TRUE, $extraCodes = '') {
		$this->parseSCFiles = $useSCFiles;
		$ret = '';
		if (is_array($extraCodes)) {
			foreach($extraCodes as $sc => $code) {
				$this->scList[$sc] = $code;
			}
		}
		$tmp = explode("\n", $text);
		foreach($tmp as $line) {
			if (preg_match("/{.+?}/", $line, $match)) {
				$ret .= preg_replace_callback("#\{(\S[^\x02]*?\S)\}#", array($this, 'doCode'), $line);
			} else {
				$ret .= $line;
			}
		}
		return $ret;
	}

	function doCode($matches)
	{
		global $pref, $e107cache, $menu_pref, $sc_style, $parm, $sql;

		if(strpos($matches[1], E_NL) !== false)
		{
			return $matches[0];
		}

		if (strpos($matches[1], '='))
		{
			list($code, $parm) = explode("=", $matches[1], 2);
		}
		else
		{
			$code = $matches[1];
			$parm = '';
		}
		$parm = trim($parm);

		if (E107_DBG_BBSC)
		{
			global $db_debug;
			$sql->db_Mark_Time("SC $code");
			$db_debug->logCode(2, $code, $parm, "");
		}

		if (is_array($this->scList) && array_key_exists($code, $this->scList))
		{
			$shortcode = $this->scList[$code];
		}
		else
		{
		  if ($this->parseSCFiles == TRUE)
		  {
			if (is_array($this -> registered_codes) && array_key_exists($code, $this->registered_codes))
			{
			  if($this->registered_codes[$code]['type'] == 'plugin')
			  {
				if (check_class($this->registered_codes[$code]['perms']))
				{	// Use the plugin 'override' shortcode
				  $scFile = e_PLUGIN.strtolower($this->registered_codes[$code]['path']).'/'.strtolower($code).'.sc';
				}
				else
				{	// Look for a core shortcode
				  $scFile = e_FILE."shortcode/".strtolower($code).".sc";
				}
			  }
			  else
			  {
				$scFile = THEME.strtolower($code).'.sc';
			  }
			}
			else
			{
			  $scFile = e_FILE."shortcode/".strtolower($code).".sc";
			}
			if (file_exists($scFile)) 
			{
			  $shortcode = file_get_contents($scFile);
			  $this->scList[$code] = $shortcode;
			}
		  }
		}

		if (!isset($shortcode))
		{
		  if(E107_DBG_BBSC) trigger_error("shortcode not found:{".$code."}", E_USER_ERROR);
		  return $matches[0];
		}

        if(E107_DBG_SC)
		{
		  	echo ($scFile) ? "<br />sc_file= ".str_replace(e_FILE."shortcode/","",$scFile)."<br />" : "";
			echo "<br />sc= <b>$code</b>";
		}

		if(E107_DBG_BBSC)
		{
		  trigger_error("starting shortcode {".$code."}", E_USER_ERROR);
		}
		$ret = eval($shortcode);

		if($ret != '' || is_numeric($ret))
		{
			if(isset($sc_style) && is_array($sc_style) && array_key_exists($code,$sc_style))
			{
				if(isset($sc_style[$code]['pre']))
				{
					$ret = $sc_style[$code]['pre'].$ret;
				}
				if(isset($sc_style[$code]['post']))
				{
					$ret = $ret.$sc_style[$code]['post'];
				}
			}
		}
		if (E107_DBG_SC) {
			$sql->db_Mark_Time("(SC $code Done)");
		}
		return $ret;
	}

	function parse_scbatch($fname, $type = 'file') {
		$ret = array();
		if($type == 'file')
		{
			$sc_batch = file($fname);
		}
		else
		{
			$sc_batch = $fname;
		}
		$cur_sc = '';
		foreach($sc_batch as $line) {
			if (trim($line) == 'SC_END') {
				$cur_sc = '';
			}
			if ($cur_sc && !$override) {
				$ret[$cur_sc] .= $line;
			}
			if (preg_match("#^SC_BEGIN (\w*).*#", $line, $matches)) {
				$cur_sc = $matches[1];
				$ret[$cur_sc]='';
				if (is_array($this -> registered_codes) && array_key_exists($cur_sc, $this -> registered_codes)) {
					if ($this -> registered_codes[$cur_sc]['type'] == 'plugin') {
						$scFile = e_PLUGIN.strtolower($this -> registered_codes[$cur_sc]['path']).'/'.strtolower($cur_sc).'.sc';
					} else {
						$scFile = THEME.strtolower($cur_sc).'.sc';
					}
					if (is_readable($scFile)) {
						$ret[$cur_sc] = file_get_contents($scFile);
					}
					$override = TRUE;
				} else {
					$override = FALSE;
				}
			}
		}
		return $ret;
	}
}

?>
