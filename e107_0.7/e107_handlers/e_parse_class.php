<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_handlers/e_parse_class.php,v $
|     $Revision: 1.110 $
|     $Date: 2005-10-29 02:58:55 $
|     $Author: streaky $
+----------------------------------------------------------------------------+
*/
define ("E_NL", chr(2));
class e_parse
{
	var $e_sc;
	var $e_bb;
	var $e_pf;
	var $e_emote;
	var $e_hook;
	var $search = array('&#39;', '&#039;', '&quot;', 'onerror', '&gt;', '&amp;#039;', '&amp;quot;');
	var $replace = array("'", "'", '"', 'one<i></i>rror', '>', "'", '"');
	var $e_query;

	function parse_toDB($text, $no_encode = FALSE, $nostrip = false)
	{
		global $pref;
		if (MAGIC_QUOTES_GPC == TRUE && $nostrip == false)
		{
			$text = stripslashes($text);
		}
		if(isset($pref['post_html']) && check_class($pref['post_html']))
		{
			$no_encode = TRUE;
		}
		if (getperms("0") || $no_encode === TRUE)
		{
			$search = array('$', '"', "'", '\\', '<?');
			$replace = array('&#036;','&quot;','&#039;', '&#092;', '&lt?');
			$text = str_replace($search, $replace, $text);
		}
		else
		{
			$text = htmlentities($text, ENT_QUOTES, CHARSET);
			$text = preg_replace("/&amp;#(\d*?);/", "&#\\1;", $text);
		}
		return $text;
	}

	// recursively run toDB (for arrays)
	function toDB($data, $no_encode = false, $nostrip = false){
		if (is_array($data)) {
			foreach ($data as $key => $var) {
				$ret[$key] = $this -> toDB($var, $no_encode, $nostrip);
			}
		} else {
			$ret = $this -> parse_toDB($data, $no_encode, $nostrip);
		}
		return $ret;
	}

	function toForm($text, $single_quotes = FALSE)
	{
		if($text == "") { return ""; }
		$mode = ($single_quotes ? ENT_QUOTES :ENT_COMPAT);
		if (MAGIC_QUOTES_GPC == TRUE)
		{
			$text = stripslashes($text);
		}
		$search = array('&#036;', '&quot;');
		$replace = array('$', '"');
		$text = str_replace($search, $replace, $text);
		if(CHARSET == 'utf-8' && function_exists("utf8_encode"))
		{
			return utf8_html_entity_decode($text);
		}
		else
		{
			return html_entity_decode($text, $mode, CHARSET);
		}
	}

	function post_toHTML($text, $modifier = true, $extra = '') {
		/*
		changes by jalist 30/01/2005:
		description had to add modifier to /not/ send formatted text on to this->toHTML at end of method, this was causing problems when MAGIC_QUOTES_GPC == TRUE.
		*/
		global $pref;

		if(isset($pref['post_html']) && check_class($pref['post_html'])) {
			$no_encode = true;
		}

		if (ADMIN === true || $no_encode === true) {
			$search = array('$', '"', "'", '\\', "'&#092;'");
			$replace = array('&#036;','&quot;','&#039;','&#092;','&#039;');
			$text = str_replace($search, $replace, $text);
			/*
			changes by jalist 30/01/2005:
			description dirty fix for servers with magic_quotes_gpc == true
			*/
			if (MAGIC_QUOTES_GPC) {
				$search = array('&#092;&#092;&#092;&#092;', '&#092;&#039;', '&#092;&quot;');
				$replace = array('&#092;&#092;','&#039;', '&quot;');
				$text = str_replace($search, $replace, $text);
			}
		} else {

			if (MAGIC_QUOTES_GPC) {
				$text = stripslashes($text);
			}
			$text = htmlentities($text, ENT_QUOTES, CHARSET);
		}
		return ($modifier ? $this->toHTML($text, true, $extra) : $text);
	}

	function post_toForm($text) {
		// ensure apostrophes are properly converted, or else the form item could break
		return str_replace(array( "'", '"'), array("&#039;", "&quot;"), $text);
	}

	function parseTemplate($text, $parseSCFiles = TRUE, $extraCodes = "")
	{
		// Start parse {XXX} codes
		if (!is_object($this->e_sc))
		{
			require_once(e_HANDLER."shortcode_handler.php");
			$this->e_sc = new e_shortcode;
		}
		return $this->e_sc->parseCodes($text, $parseSCFiles, $extraCodes);
		// End parse {XXX} codes
	}

	function htmlwrap($str, $width, $break = "\n", $nobreak = "", $nobr = "pre", $utf = false)
	{
		/*
		* htmlwrap() function - v1.1
		* Copyright (c) 2004 Brian Huisman AKA GreyWyvern
		* http://www.greywyvern.com/code/php/htmlwrap_1.1.php.txt
		*
		* This program may be distributed under the terms of the GPL
		*   - http://www.gnu.org/licenses/gpl.txt
		*/

		$content = preg_split("/([<>])/", $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		$nobreak = explode(" ", $nobreak);
		$nobr = explode(" ", $nobr);
		$intag = false;
		$innbk = array();
		$innbr = array();
		$drain = "";
		$utf = ($utf || CHARSET == 'utf-8') ? "u" : "";
		$lbrks = "/?!%)-}]\\\"':;";
		if ($break == "\r")
		{
			$break = "\n";
		}
		while (list(, $value) = each($content))
		{
			switch ($value)
			{
				case "<": $intag = true; break;
				case ">": $intag = false; break;
				default:
				if ($intag)
				{
					if ($value{0} != "/")
					{
						preg_match('/^(.*?)(\s|$)/'.$utf, $value, $t);
						if ((!count($innbk) && in_array($t[1], $nobreak)) || in_array($t[1], $innbk)) $innbk[] = $t[1];
						if ((!count($innbr) && in_array($t[1], $nobr)) || in_array($t[1], $innbr)) $innbr[] = $t[1];
					} else {
						if (in_array(substr($value, 1), $innbk)) unset($innbk[count($innbk)]);
						if (in_array(substr($value, 1), $innbr)) unset($innbr[count($innbr)]);
					}
				} else if ($value)
				{
					if (!count($innbr)) $value = str_replace("\n", "\r", str_replace("\r", "", $value));
					if (!count($innbk))
					{
						do
						{
							$store = $value;
							if (preg_match("/^(.*?\s|^)(([^\s&]|&(\w{2,5}|#\d{2,4});){".$width."})(?!(".preg_quote($break, "/").'|\s))(.*)$/s'.$utf, $value, $match))
							{
								for ($x = 0, $ledge = 0; $x < strlen($lbrks); $x++) $ledge = max($ledge, strrpos($match[2], $lbrks{$x}));
								if (!$ledge) $ledge = strlen($match[2]) - 1;
								$value = $match[1].substr($match[2], 0, $ledge + 1).$break.substr($match[2], $ledge + 1).$match[6];
							}
						}
						while ($store != $value);
					}
					if (!count($innbr)) $value = str_replace("\r", E_NL, $value);
				}
			}
			$drain .= $value;
		}
		return $drain;
	}

	function html_truncate ($text, $len = 200, $more = "[more]")
	{
		$pos = 0;
		$curlen = 0;
		$tmp_pos = 0;
		while($curlen < $len && $curlen < strlen($text))
		{
			switch($text{$pos})
			{
				case "<" :
								if($text{$pos+1} == "/")
								{
									$closing_tag = TRUE;
								}
								$intag = TRUE;
								$tmp_pos = $pos-1;
								$pos++;
								break;
				case ">" :
								if($text{$pos-1} == "/")
								{
									$closing_tag = TRUE;
								}
								if($closing_tag == TRUE)
								{
									$tmp_pos = 0;
									$closing_tag = FALSE;
								}
								$intag = FALSE;
								$pos++;
								break;
				case "&" :
					if($text{$pos+1} == "#")
					{
						$end = strpos(substr($text, $pos, 7), ";");
						if($end !== FALSE)
						{
							$pos+=($end+1);
							if(!$intag) {$curlen++;}
							break;
						}
					}
					else
					{
						$pos++;
						if(!$intag) {$curlen++;}
						break;
					}
				default:
					$pos++;
					if(!$intag) {$curlen++;}
					break;
			}
		}
		$ret = ($tmp_pos > 0 ? substr($text, 0, $tmp_pos) : substr($text, 0, $pos));
		if($pos < strlen($text))
		{
			$ret = $ret.$more;
		}
		return $ret;
	}

	function textclean ($text, $wrap=100)
	{
		$text = str_replace ("\n\n\n", "\n\n", $text);
		$text = $this -> htmlwrap($text, $wrap);
		$text = str_replace (array ("<br /> ", " <br />", " <br /> "), "<br />", $text);
		/* we can remove any linebreaks added by htmlwrap function as any \n's will be converted later anyway */
		return $text;
	}

	function toHTML($text, $parseBB = FALSE, $modifiers = "", $postID = "", $wrap=FALSE) {
		if ($text == '')
		{
			return $text;
		}
		global $pref;


		$text = str_replace(array("&#092;&quot;", "&#092;&#039;", "&#092;&#092;"), array("&quot;", "&#039;", "&#092;"), $text);


		// support for converting defines(constants) within text. eg. Lan_XXXX
		if(strpos($modifiers,"defs") !== FALSE && strlen($text) < 20 && defined(trim($text))){
			return constant(trim($text));
		}

		// replace all {e_XXX} constants with their e107 value
		if(strpos($modifiers, "constants") !== FALSE)
		{
			$text = $this->replaceConstants($text);
		}

		if(!$wrap) $wrap = $pref['main_wordwrap'];
		$text = " ".$text;

		if (strpos($modifiers, 'nobreak') === FALSE) {
			$text = preg_replace("#>\s*[\r]*\n[\r]*#", ">", $text);
		}

		if($pref['make_clickable'] && strpos($modifiers, 'no_make_clickable') === FALSE) {
			if($pref['link_replace'] && strpos($modifiers, 'no_replace') === FALSE) {
				$text = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<,]*)#is", "\\1<a href=\"\\2\" rel=\"external\">".$pref['link_text']."</a>", $text);
				$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<,]*)#is", "\\1<a href=\"http://\\2\" rel=\"external\">".$pref['link_text']."</a>", $text);
				if(CHARSET != "utf-8" && CHARSET != "UTF-8"){
					$email_text = ($pref['email_text']) ? $pref['email_text'] : "\\1\\2&copy;\\3";
				}else{
                    $email_text = ($pref['email_text']) ? $pref['email_text'] : "\\1\\2©\\3";
				}
				$text = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a rel='external' href='javascript:window.location=\"mai\"+\"lto:\"+\"\\2\"+\"@\"+\"\\3\";self.close();' onmouseover='window.status=\"mai\"+\"lto:\"+\"\\2\"+\"@\"+\"\\3\"; return true;' onmouseout='window.status=\"\";return true;'>".$email_text."</a>", $text);
			} else {
				$text = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<,]*)#is", "\\1<a href=\"\\2\" rel=\"external\">\\2</a>", $text);
				$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<,]*)#is", "\\1<a href=\"http://\\2\" rel=\"external\">\\2</a>", $text);
				$text = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a rel='external' href='javascript:window.location=\"mai\"+\"lto:\"+\"\\2\"+\"@\"+\"\\3\";self.close();' onmouseover='window.status=\"mai\"+\"lto:\"+\"\\2\"+\"@\"+\"\\3\"; return true;' onmouseout='window.status=\"\";return true;'>-email-</a>", $text);
			}
		}

		if (strpos($modifiers, 'emotes_off') === FALSE) {
			if ($pref['smiley_activate'] || strpos($modifiers,'emotes_on') !== FALSE) {
				if (!is_object($this->e_emote)) {
					require_once(e_HANDLER.'emote_filter.php');
					$this->e_emote = new e_emoteFilter;
				}
				$text = $this->e_emote->filterEmotes($text);
			}
		}
		$text = str_replace($this -> search, $this -> replace, $text);
		if (strpos($modifiers, 'nobreak') === FALSE) {
			$text = preg_replace("#[\r]*\n[\r]*#", E_NL, $text);
		}

		// Start parse [bb][/bb] codes
		if ($parseBB === TRUE) {
			if (!is_object($this->e_bb)) {
				require_once(e_HANDLER.'bbcode_handler.php');
				$this->e_bb = new e_bbcode;
			}
			$text = $this->e_bb->parseBBCodes($text, $postID);
		}
		// End parse [bb][/bb] codes

		if ($pref['profanity_filter']) {
			if (!is_object($this->e_pf)) {
				require_once(e_HANDLER."profanity_filter.php");
				$this->e_pf = new e_profanityFilter;
			}
			$text = $this->e_pf->filterProfanities($text);
		}

		if (strpos($modifiers,'parse_sc') !== FALSE)
		{
			$text = $this->parseTemplate($text, TRUE);
		}

		//Run any hooked in parsers
		if(isset($pref['tohtml_hook']) && $pref['tohtml_hook'])
		{
			foreach(explode(",",$pref['tohtml_hook']) as $hook)
			{
				if (strpos($modifiers, 'no_hook') === FALSE)
				{
					if (!is_object($this->e_hook[$hook]))
					{
						require_once(e_PLUGIN.$hook."/".$hook.".php");
						$hook_class = "e_".$hook;
						$this->e_hook[$hook] = new $hook_class;
					}
					$text = $this->e_hook[$hook]->$hook($text);
				}
			}
		}

		if (strpos($modifiers, 'nobreak') === FALSE) {
			$text = $this -> textclean($text, $wrap);
		}

		// Search Highlight
		if (strpos($modifiers, 'emotes_off') === FALSE) {
			$shr = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "");
			if ($pref['search_highlight'] && (strpos(e_SELF, 'search.php') === FALSE) && ((strpos($shr, 'q=') !== FALSE) || (strpos($shr, 'p=') !== FALSE))) {
				if (!isset($this -> e_query)) {
					$query = preg_match('#(q|p)=(.*?)(&|$)#', $shr, $matches);
					$this -> e_query = str_replace(array('+', '*', '"', ' '), array('', '.*?', '', '\b|\b'), trim(urldecode($matches[2])));
				}
				$text = $this -> e_highlight($text, $this -> e_query);
			}
		}

		$nl_replace = "<br />";
		if (strpos($modifiers, 'nobreak') !== FALSE)
		{
			$nl_replace = '';
		}
		elseif (strpos($modifiers, 'retain_nl') !== FALSE)
		{
			$nl_replace = "\n";
		}
//		$text = preg_replace("#\[\s*?E_NL\s*?\]#s", $nl_replace, $text);
		$text = str_replace(E_NL, $nl_replace, $text);
		//		$text = str_replace("&amp;", "&", $text);
		//		$text = str_replace(array("&", "&pound;"), array("&amp;", ""), $text);
		return trim($text);
	}

	function toJS($stringarray) {
		$stringarray = str_replace("\r\n", "\\n", $stringarray);
		$stringarray = str_replace("\r", "", $stringarray);
		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);
		return strtr ($stringarray, $trans_tbl);
	}

	function toRss($text)
	{
		$search = array("&amp;#039;", "&amp;#036;", "&#039;", "&#036;", "&", "<");
		$replace = array("'", '$', "'", '$', "&amp;", "&lt;");
		$text = str_replace($search, $replace, $text);
		//$text = htmlspecialchars($text);
		return $text;
	}

	//Convert specific characters back to original form, for use in storing code (or regex) values in the db.
	function toText($text)
	{
		$search = array("&amp;#039;", "&amp;#036;", "&#039;", "&#036;", "&#092;", "&amp;#092;");
		$replace = array("'", '$', "'", '$', "\\", "\\");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	function replaceConstants($text)
	{
		$text = preg_replace_callback("#\{(e_[A-Z]*)\}#s", array($this, 'doReplace'), $text);
		return $text;
	}

	function doReplace($matches)
	{
		if(defined($matches[1]) && ($matches[1] != 'e_ADMIN' || ADMIN))
		{
			return constant($matches[1]);
		}
		return $matches[1];
	}

	function spell_check($text) {
		$skip_len = 2;
		$mode = PSPELL_NORMAL;

		$pspell_handle;
		$pspell_cfg_handle;

		if (function_exists("pspell_config_create")) {
			$pspell_cfg_handle = pspell_config_create(CORE_LC);

			pspell_config_ignore($pspell_cfg_handle, $skip_len);
			pspell_config_mode($pspell_cfg_handle, $mode);
			$pspell_handle = pspell_new_config($pspell_cfg_handle);

			$words = array_unique(split("[^[:alpha:]']+", $text));

			foreach($words as $val) {
				if(!pspell_check($pspell_handle, $val)) {
					/*$sug="Suggested spellings:\n";
					foreach(pspell_suggest($pspell_handle, $val) as $suggestion) {
						$sug.=$suggestion."\n";
					}*/
					//$text=str_replace($val,'<span style="color:red" title="'.$sug.'">'.$val.'</span>',$text);
					$text = str_replace($val, "<span style='color:red; text-decoration: underline;'>{$val}</span>", $text);
				}
			}
		}
		return $text;
	}

	function e_highlight($text, $match) {
		preg_match_all("#<[^>]+>#", $text, $tags);
		$text = preg_replace("#<[^>]+>#", "<|>", $text);
		$text = preg_replace("#(\b".$match."\b)#i", "<span class='searchhighlight'>\\1</span>", $text);
		foreach ($tags[0] as $tag) {
			$text = preg_replace("#<\|>#", $tag, $text, 1);
		}
		return $text;
	}
}

?>