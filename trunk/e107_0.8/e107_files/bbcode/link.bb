global $pref;

$parm     = trim($parm);
$external = (($pref['links_new_window'] || strpos($parm, 'external') === 0) && substr($parm,0,1) != "#") ? " rel='external'" : "";

if(strpos($parm,"{e_")!==FALSE){
	$external = "";
}

if(substr($parm,0,6) == "mailto")
{
	list($pre,$email) = explode(":",$parm);
	list($p1,$p2) = explode("@",$email);
	return "<a class='bbcode' rel='external' href='javascript:window.location=\"mai\"+\"lto:\"+\"$p1\"+\"@\"+\"$p2\";self.close();' onmouseover='window.status=\"mai\"+\"lto:\"+\"$p1\"+\"@\"+\"$p2\"; return true;' onmouseout='window.status=\"\";return true;'>".$code_text."</a>";
}

if ($parm && $parm != 'external' && strpos($parm, ' ') === FALSE)
{
	$parm = preg_replace('#^external.#is', '', $parm);
	if (strtolower(substr($parm,0,11)) == 'javascript:') return '';
	return "<a class='bbcode' href='".$tp -> toAttribute($parm)."'".$external.">".$code_text."</a>";
}
else
{
  if (strtolower(substr($code_text,0,11)) == 'javascript:') return '';
	return "<a class='bbcode' href='".$tp -> toAttribute($code_text)."'".$external.">".$code_text."</a>";
}
