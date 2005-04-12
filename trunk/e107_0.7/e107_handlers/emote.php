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
|     $Source: /cvs_backup/e107_0.7/e107_handlers/emote.php,v $
|     $Revision: 1.5 $
|     $Date: 2005-04-12 23:13:00 $
|     $Author: streaky $
+----------------------------------------------------------------------------+
*/
function r_emote() {
	global $sysprefs;
	$emote = $sysprefs -> getArray('emote');
	$str = "<div class='spacer'>";
	$c = 0;
	$orig = array();
	while(list($code, $name) = @each($emote[$c])){
		if(!array_key_exists($name, $orig) && (strpos($code, '&') === false)){
			$str .= "\n<a href=\"javascript:addtext(' $code', true)\"><img src=\"".e_IMAGE."emoticons/{$name}\" style=\"border:0; padding-top:2px;\" alt=\"\" /></a> ";
			$orig[$name] = true;
		}
		$c++;
	}
	 
	$str .= "</div>";
	return $str;
}
?>