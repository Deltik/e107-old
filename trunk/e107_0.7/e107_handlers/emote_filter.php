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
|     $Source: /cvs_backup/e107_0.7/e107_handlers/emote_filter.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-03-31 06:04:49 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/
class e_emotefilter {
	var $search;
	var $replace;
	 
	function e_emotefilter() //Constructor
	{
		global $sysprefs;
		$emotes = $sysprefs->getArray('emote');
		$c = 0;
		while (list($code, $name) = @each($emotes[$c])) {
			$this->searcha[$c] = " ".$code;
			$this->searchb[$c] = "\n".$code;
			$this->replace[$c] = (file_exists(THEME."emoticons/".$name) ? " <img src='".THEME."emoticons/$name' alt='' style='vertical-align:middle; border:0' /> " : " <img src='".e_IMAGE."emoticons/$name' alt='' style='vertical-align:middle; border:0' /> ");
			$c++;
		}
	}
	 
	function filterEmotes($text) {
		 
		$text = str_replace($this->searcha, $this->replace, $text);
		$text = str_replace($this->searchb, $this->replace, $text);
		return $text;
	}
	 
	function filterEmotesRev($text) {
		$text = str_replace($this->replace, $this->searcha, $text);
		return $text;
	}
}
	
	
	
	
	
?>