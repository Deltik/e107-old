<?php
	
class e_profanityFilter {
	var $profanityList;
	 
	function e_profanityFilter() {
		global $pref;

		$words = explode(",", $pref['profanity_words']);
		
		foreach($words as $word) {
			$word = trim($word);
			if($word != "")
			{
				$this->profanityList .= $word."\b|\b";
			}
		}
		$this->profanityList = substr($this->profanityList, 0, -1);
		unset($words);
		return TRUE;
	}
	 
	function filterProfanities($text) {
		global $pref;
		if (!$this->profanityList) {
			return $text;
		}
		if ($pref['profanity_replace']) {
			return preg_replace("/\b".$this->profanityList."\b/is", $pref['profanity_replace'], $text);
		} else {
			return preg_replace_callback("/\b".$this->profanityList."\b/is", array($this, 'replaceProfanities'), $text);
		}
	}
	 
	function replaceProfanities($matches) {
		/*!
		@function replaceProfanities callback
		@abstract replaces vowels in profanity words with stars
		@param text string - text string to be filtered
		@result filtered text
		*/
		 
		return preg_replace("/a|e|i|o|u/i", "*" , $matches[0]);
	}
}
	
?>