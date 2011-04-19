<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2010 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Language handler
 *
 * $URL$
 * $Id$
 */

/**
 * @package e107
 * @subpackage	e107_handlers
 * @version $Id$
 */

class language{

// http://www.loc.gov/standards/iso639-2/php/code_list.php

// Valid Language Pack Names are shown directly below on the right. 
	public $detect = FALSE;
	public $e_language = 'English'; // replaced later with $pref
	public $_cookie_domain = '';
	
	/**
	 * Cached list of Installed Language Packs
	 * @var array
	 */
	protected $lanlist = null; // null is important!!!

	protected $list = array(
            "aa" => "Afar",
            "ab" => "Abkhazian",
            "af" => "Afrikaans",
            "am" => "Amharic",
            "ar" => "Arabic",
            "as" => "Assamese",
            "ae" => "Avestan",
            "ay" => "Aymara",
            "az" => "Azerbaijani",
            "ba" => "Bashkir",
            "be" => "Belarusian",
            "bn" => "Bengali",
            "bh" => "Bihari",
            "bi" => "Bislama",
            "bo" => "Tibetan",
            "bs" => "Bosnian",
            "br" => "Breton",
            "bg" => "Bulgarian",
            "ca" => "Catalan",
            "cs" => "Czech",
            "ch" => "Chamorro",
            "ce" => "Chechen",
            "cn" => "ChineseSimp",
            "cv" => "Chuvash",
            "kw" => "Cornish",
            "co" => "Corsican",
            "cy" => "Welsh",
            "da" => "Danish",
            "de" => "German",
            "dz" => "Dzongkha",
            "el" => "Greek",
            "en" => "English",
            "eo" => "Esperanto",
            "et" => "Estonian",
            "eu" => "Basque",
            "fo" => "Faroese",
            "fa" => "Persian",
            "fj" => "Fijian",
            "fi" => "Finnish",
            "fr" => "French",
            "fy" => "Frisian",
            "gd" => "Gaelic",
            "ga" => "Irish",
            "gl" => "Gallegan",
            "gv" => "Manx",
            "gn" => "Guarani",
            "gu" => "Gujarati",
            "ha" => "Hausa",
            "he" => "Hebrew",
            "hz" => "Herero",
            "hi" => "Hindi",
            "ho" => "Hiri Motu",
            "hr" => "Croatian",
            "hu" => "Hungarian",
            "hy" => "Armenian",
            "iu" => "Inuktitut",
            "ie" => "Interlingue",
            "id" => "Indonesian",
            "ik" => "Inupiaq",
            "is" => "Icelandic",
            "it" => "Italian",
            "jw" => "Javanese",
            "ja" => "Japanese",
            "kl" => "Kalaallisut",
            "kn" => "Kannada",
            "ks" => "Kashmiri",
            "ka" => "Georgian",
            "kk" => "Kazakh",
            "km" => "Khmer",
            "ki" => "Kikuyu",
            "rw" => "Kinyarwanda",
            "ky" => "Kirghiz",
            "kv" => "Komi",
            "ko" => "Korean",
            "ku" => "Kurdish",
            "lo" => "Lao",
            "la" => "Latin",
            "lv" => "Latvian",
            "ln" => "Lingala",
            "lt" => "Lithuanian",
            "lb" => "Letzeburgesch",
            "mh" => "Marshall",
            "ml" => "Malayalam",
            "mr" => "Marathi",
            "mk" => "Macedonian",
            "mg" => "Malagasy",
            "mt" => "Maltese",
            "mo" => "Moldavian",
            "mn" => "Mongolian",
            "mi" => "Maori",
            "ms" => "Malay",
            "my" => "Burmese",
            "na" => "Nauru",
            "nv" => "Navajo",

            "ng" => "Ndonga",
            "ne" => "Nepali",
            "nl" => "Dutch",
	        "no" => "Norwegian",

            "ny" => "Chichewa",
            "or" => "Oriya",
            "om" => "Oromo",
            "pa" => "Panjabi",
            "pi" => "Pali",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ps" => "Pushto",
            "qu" => "Quechua",
            "ro" => "Romanian",
            "rn" => "Rundi",
            "ru" => "Russian",
            "sg" => "Sango",
            "sa" => "Sanskrit",
            "si" => "Sinhalese",
            "sk" => "Slovak",
            "sl" => "Slovenian",

            "sm" => "Samoan",
            "sn" => "Shona",
            "sd" => "Sindhi",
            "so" => "Somali",

            "es" => "Spanish",
            "sq" => "Albanian",
            "sc" => "Sardinian",
            "sr" => "Serbian",
            "ss" => "Swati",
            "su" => "Sundanese",
            "sw" => "Swahili",
            "sv" => "Swedish",
            "ty" => "Tahitian",
            "ta" => "Tamil",
            "tt" => "Tatar",
            "te" => "Telugu",
            "tg" => "Tajik",
            "tl" => "Tagalog",
            "th" => "Thai",
            "ti" => "Tigrinya",

            "tn" => "Tswana",
            "ts" => "Tsonga",
            "tk" => "Turkmen",
            "tr" => "Turkish",
            "tw" => "ChineseTrad",
            "ug" => "Uighur",
            "uk" => "Ukrainian",
            "ur" => "Urdu",
            "uz" => "Uzbek",
            "vi" => "Vietnamese",

            "wo" => "Wolof",
            "xh" => "Xhosa",
            "yi" => "Yiddish",
            "yo" => "Yoruba",
            "za" => "Zhuang",
           // "zh" => "Chinese",
            "zu" => "Zulu"
        );

		protected $names = array(
			"Arabic" 		=> "العربية",
			"Bosnian"		=> "Bosanski",
			"Bulgarian"		=> "Български",
			"Croatian"		=> "Hrvatski",
			"ChineseTrad"  	=> "繁体中文",
			"ChineseSimp"  	=> "简体中文",
			"Dutch"			=> "Nederlands",
			"English"		=> "English",
			"Estonian"		=> "Eesti",
			"French"		=> "Français",
			"German"		=> "Deutsch",
			"Greek"			=> "Ελληνικά",
			"Hebrew"		=> "עִבְרִית",
			"Hungarian"		=> "Magyar",
			"Indonesian"	=> "Bahasa Indonesia",
			"Italian"		=> "Italiano",
			"Japanese"		=> "日本語",
			"Korean"		=> "한국어",
			"Lithuanian"	=> "Lietuvių",
			"Mongolian"		=> "монгол",
			"Nepali"		=> "नेपाली",
			"Persian"	   	=> "فارسي",
		    "Portuguese"	=> "Português",
			"Polish"		=> "Polski",
			"Romanian"		=> "Romanesc",
			"Russian"		=> "Pусский",
			"Serbian"		=> "Srpski",
			"Spanish"		=> "Español",
			"Slovenian"		=> "Slovensko",
			"Slovakian"		=> "Slovensky",
			"Slovak"		=> "Slovensky",
			"Swedish"		=> "Svenska",
			"Thai"			=> "ภาษาไทย",
			"Turkish"		=> "Türkçe",
			"Vietnamese"	=> "Tiếng Việt"
		);

	/**
	 * Converts iso to language-name and visa-versa.
	 * @param object $data
	 * @return 
	 */
	function convert($data){

		if(strlen($data) > 2)
		{
        	$tmp = array_flip($this->list);
			return isset($tmp[$data]) ? $tmp[$data] : FALSE;
		}
		else
		{
			return (isset($this->list[$data])) ? $this->list[$data] : FALSE;
		}
	}

// -------------------------------------------------------------------
	/**
	 * Check if a Language is installed and valid
	 * @param object $lang - Language to check. eg. 'es' or 'Spanish'
	 * @return FALSE or the name of the valid Language
	 */
	function isValid($lang='')
	{	
		global $pref;
				
		if(!$lang)
		{
			return $pref['sitelanguage'];
		}
		
		if(strpos($lang,"debug")!==FALSE)
		{
			 return FALSE;			
		}
		
		if(strlen($lang)== 2)
		{
			$iso = $lang;
			$lang = $this->convert($lang);	
		}
		else
		{
			$iso = $this->convert($lang);
		}
			
		if($iso==FALSE || $lang==FALSE)
		{
			$diz = ($lang) ? $lang : $iso;
			trigger_error("The selected language (".$diz.") is invalid. See e107_handlers/language_class.php for a list of valid languages. ", E_USER_ERROR);
			return FALSE;
		}
		
		if(is_readable(e_LANGUAGEDIR.$lang.'/'.$lang.'.php'))
		{
			return $lang;	
		}
		else
		{
			trigger_error("The selected language (".$lang.") was not found.", E_USER_ERROR);
			return FALSE;	
		}
		
		return FALSE;	
	}
	
	/**
	 * Check if the specified domain has multi-language subdomains enabled.
	 * @return 
	 */
	function isLangDomain($domain='')
	{
		if(!$domain)
		{
			return FALSE;
		}
		
		global $pref;
		$mtmp = explode("\n", $pref['multilanguage_subdomain']);
        foreach($mtmp as $val)
		{
        	if($domain == trim($val))
			{
            	return TRUE;
			}
		}
		
		return FALSE;
		
	}
	

	/**
	 * Return a list of Installed Language Packs
	 * 
	 * @return array
	 */
	function installed()
	{
		if(null == $this->lanlist)
		{
			$handle = opendir(e_LANGUAGEDIR);
			$lanlist = array();
			while ($file = readdir($handle))
			{
				if ($file != '.' && $file != '..' && is_readable(e_LANGUAGEDIR.$file.'/'.$file.'.php'))
				{
					$lanlist[] = $file;
				}
			}
			closedir($handle);
			
			$this->lanlist = array_intersect($lanlist,$this->list);
		}
		
		return $this->lanlist;
	}
	
	
	/**
	 * Convert a Language to its Native title. eg. 'Spanish' becomes 'Español'
	 * @param string $lang
	 * @return string
	 */
	function toNative($lang)
	{
		return ($this->names[$lang]) ? $this->names[$lang] : $lang;
	}

	/**
	 * Convert the current URL to a multi-lang for the specified language. 
	 * eg. 'http://www.mydomain.com' becomes 'http://es.mydomain.com'
	 * @param string $language eg. 'Spanish'
	 * @return URL
	 */
	function subdomainUrl($language)
	{
		global $pref;
		$codelnk = ($language == $pref['sitelanguage']) ? "www" : $this->convert($language);
		
      //  $urlval = str_replace($_SERVER['HTTP_HOST'],$codelnk.".".e_DOMAIN,e_SELF);
		
		$urlval = (e_QUERY)
		        ? str_replace($_SERVER['HTTP_HOST'], $codelnk.'.'.e_DOMAIN, e_SELF).'?'.e_QUERY
		        : str_replace($_SERVER['HTTP_HOST'], $codelnk.'.'.e_DOMAIN, e_SELF);
		
        return $urlval;
	}
	
	/**
 	* Detect a Language Change
 	* 1. Parked (sub)Domain		eg. http://es.mydomain.com (Preferred for SEO)
 	* 2. e_MENU Query			eg. /index.php?[es]
 	* 3. $_GET['elan']			eg. /index.php?elan=es
 	* 4. $_POST['sitelanguage']	eg. <input type='hidden' name='sitelanguage' value='Spanish' /> 
 	* 5. $GLOBALS['elan']		eg. <?php $GLOBALS['elan']='es' (deprecated) 
 	* 
 	* @param boolean $force force detection, don't use cached value
 	*/
	function detect($force = false)
	{
		global $pref;
		
		
		if(false !== $this->detect && !$force) return $this->detect;
		$this->_cookie_domain = '';
		if(varsettrue($pref['multilanguage_subdomain']) && $this->isLangDomain(e_DOMAIN) && (defset('MULTILANG_SUBDOMAIN') !== FALSE)) 
		{
			$detect_language = (e_SUBDOMAIN) ? $this->isValid(e_SUBDOMAIN) : $pref['sitelanguage'];
			// Done in session handler now, based on MULTILANG_SUBDOMAIN value
			//e107_ini_set("session.cookie_domain", ".".e_DOMAIN); // Must be before session_start()
			$this->_cookie_domain = ".".e_DOMAIN;
			define('MULTILANG_SUBDOMAIN',TRUE);
		}
		elseif(e_MENU && ($detect_language = $this->isValid(e_MENU))) // 
		{
			define("e_LANCODE",TRUE);	

		}
		elseif(isset($_GET['elan']) && ($detect_language = $this->isValid($_GET['elan']))) // eg: /index.php?elan=Spanish
		{
			// Do nothing			
		}
		elseif(isset($_POST['setlanguage']) && ($detect_language = $this->isValid($_POST['sitelanguage'])))
		{
			// Do nothing	
		}
		
		elseif(isset($GLOBALS['elan']) && ($detect_language = $this->isValid($GLOBALS['elan'])))
		{
			// Do nothing		
		}
		else
		{
			$detect_language = FALSE; // ie. No Change. 
		}
		
		// Done in session handler now
		// e107_ini_set("session.cookie_path", e_HTTP);
		
		$this->detect = $detect_language;	
		return $detect_language;
	}

	/**
	 * Get domain to be used in cookeis (e.g. .domain.com), or empty
	 * if multi-language subdomain settings not enabled
	 * Available after self::detect() 
	 * @return string
	 */
	public function getCookieDomain()
	{
		return $this->_cookie_domain;
	}

	/**
	 * Set the Language (Constants, $_SESSION and $_COOKIE) for the current page. 
	 * @param string $language force set
	 * @return void
	 */
	function set($language = null)
	{
		$pref = e107::getPref();
		$session = e107::getSession(); // default core session namespace
		if($language && ($language = $this->isValid($language))) // force set
		{
			$this->detect = $language;
		}
		if($this->detect) // Language-Change Trigger Detected. 
		{
			// new - e_language moved to e107 namespace - $_SESSION['e107']['e_language']
			if(!$session->has('e_language') || (($session->get('e_language') != $this->detect) && $this->isValid($session->get('e_language'))))
			{
				$session->set('e_language', $this->detect);	
			}
			
			if(varset($_COOKIE['e107_language'])!=$this->detect && (defset('MULTILANG_SUBDOMAIN') != TRUE))
			{
				setcookie('e107_language', $this->detect, time() + 86400, e_HTTP);
				$_COOKIE['e107_language'] = $this->detect; // Used only when a user returns to the site. Not used during this session. 
			}
			else // Multi-lang SubDomains should ignore cookies and remove old ones if they exist. 
			{
				if(isset($_COOKIE['e107_language']))
				{
					unset($_COOKIE['e107_language']);
				}
			}
			
			$user_language = $this->detect;		
		}
		else // No Language-change Trigger Detected. 
		{	
			if($session->has('e_language'))
			{
				$user_language = $session->get('e_language');
			}
			elseif(isset($_COOKIE['e107_language']) && ($user_language = $this->isValid($_COOKIE['e107_language']))) 
			{
				$session->set('e_language', $user_language);	 		
			}
			else
			{	
				$user_language = $pref['sitelanguage'];	
				
				if($session->is('e_language'))
				{
					$session->clear('e_language');
				}
			
				if(isset($_COOKIE['e107_language']))
				{
					unset($_COOKIE['e107_language']);
				}	
			}	
		}
		
		$this->e_language = $user_language;
		$this->setDefs();
		return;
	}


	
	/**
	 * Set Language-specific Constants
	 * FIXME - language detection is a mess - db handler, mysql handler, session handler and language handler + constants invlolved,
	 * SIMPLIFY, test, get feedback
	 * @param string $language
	 * @return 
	 */
	function setDefs()
	{
		global $pref;
		
		$language = $this->e_language;
		//$session = e107::getSession();

		// SecretR - don't register lanlist in session, confusions, save it as class property (lan class is singleton) 
		e107::getSession()->set('language-list', null); // cleanup test installs, will be removed soon
		
		/*if(!$session->is('language-list'))
		{
			$session->set('language-list', implode(',',$this->installed()));
		}*/
		
		//define('e_LANLIST', $session->get('language-list'));
		define('e_LANLIST',  implode(',', $this->installed()));
		define('e_LANGUAGE', $language);
		define('USERLAN', $language); // Keep USERLAN for backward compatibility
		$iso = $this->convert($language);	
		define("e_LAN", $iso);
		
		// Below is for BC
		if(defined('e_LANCODE') && varset($pref['multilanguage']) && ($language != $pref['sitelanguage']))
		{			
			define("e_LANQRY", "[".$iso."]");
		}
		else
		{
			define("e_LANCODE", '');		
			define("e_LANQRY", FALSE);	
		} 	
	}
	
	public function getLanSelectArray($force = false)
	{
		if($force ||null === $this->_select_array)
		{
			$lanlist = explode(',', e_LANLIST);
			$this->_select_array = array();
			foreach ($lanlist as $lan) 
			{
				$this->_select_array[$this->convert($lan)] = $this->toNative($lan);
			}
		}
		return $this->_select_array;
	}
}
