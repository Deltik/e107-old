//<?php
$class = e107::getBB()->getClass('code');



global $pref, $e107cache, $tp;

if($pref['smiley_activate']) 
{
	if (!is_object($tp->e_emote))
	{
		require_once(e_HANDLER.'emote_filter.php');
		$tp->e_emote = new e_emoteFilter;
	}
	$code_text = $tp->e_emote->filterEmotesRev($code_text);
}

$search = array(E_NL,'&#092;','&#036;', '&lt;');
$replace = array("\r\n","\\",'$', '<');
$code_text = str_replace($search, $replace, $code_text);



if(isset($pref['useGeshi']) && $pref['useGeshi'] && file_exists(e_PLUGIN."geshi/geshi.php")) {



	$code_md5 = md5($code_text);
	if(!$CodeCache = $e107cache->retrieve('GeshiParsed_'.$code_md5)) {
		require_once(e_PLUGIN."geshi/geshi.php");
		if($parm) {
			$geshi = new GeSHi($code_text, $parm, e_PLUGIN."geshi/geshi/");
		} else {
			$geshi = new GeSHi($code_text, ($pref['defaultLanGeshi'] ? $pref['defaultLanGeshi'] : 'php'), e_PLUGIN."geshi/geshi/");
		}
		$geshi->line_style1 = "font-family: 'Courier New', Courier, monospace; font-weight: normal; font-style: normal;";
		$geshi->set_encoding('utf-8');
		$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
		$geshi->set_header_type(GESHI_HEADER_DIV);
		$CodeCache = $geshi->parse_code();
		$e107cache->set('GeshiParsed_'.$code_md5, $CodeCache);
	}
	$ret = "<code class='code_highlight code-box {$class}' style='unicode-bidi: embed; direction: ltr'>".str_replace("&amp;", "&", $CodeCache)."</code>";
}
else
{
		
	$code_text = html_entity_decode($code_text, ENT_QUOTES, 'utf-8');	
	$highlighted_text = highlight_string($code_text, TRUE);
	$highlighted_text = str_replace(array("<code>","</code>"),"",$highlighted_text);
	$divClass = ($parm) ? $parm : 'code_highlight';
	$ret = "<code class='".$tp -> toAttribute($divClass)." code-box {$class}' style='unicode-bidi: embed; direction: ltr'>{$highlighted_text}</code>";
}
$ret = str_replace("[", "&#091;", $ret);
return $ret;