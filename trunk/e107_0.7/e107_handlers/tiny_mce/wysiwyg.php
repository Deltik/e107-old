<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system - Tiny MCE controller file.
|
|     $Source: /cvs_backup/e107_0.7/e107_handlers/tiny_mce/wysiwyg.php,v $
|     $Revision: 1.17 $
|     $Date: 2005-12-24 00:09:57 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

function wysiwyg($formids){
define("ADMIN","");
global $pref,$HANDLERS_DIRECTORY;
$lang = e_LANGUAGE;
$tinylang = array(
	"Arabic" => "ar",
	"Danish" => "da",
	"Dutch" => "nl",
	"English" => "en",
	"Farsi" => "fa",
	"French" => "fr",
	"Greek" => "el",
	"Hebrew" => " ",
	"Hungarian" => "hu",
	"Italian" => "it",
	"Japanese" => "ja",
	"Korean" => "ko",
	"Norwegian" => "nb",
	"Polish" => "pl",
	"Russian" => "ru",
	"Slovak" => "sk",
	"Spanish" => "es",
	"Swedish" => "sv"
);

if(!$tinylang[$lang]){
 $tinylang[$lang] = "en";
}
// $thescript = (strpos($_SERVER['SERVER_SOFTWARE'],"mod_gzip")) ? "tiny_mce_gzip.php" : "tiny_mce.js";
$thescript = "tiny_mce.js";
$text = "<script type='text/javascript' src='".e_HANDLER."tiny_mce/".$thescript."'></script>\n";

$text .= "<script type='text/javascript'>\n
	tinyMCE.init({\n";
$text .= "language : '".$tinylang[$lang]."',\n";
$text .= "mode : 'exact',\n";
$text .= "elements : '".$formids."',\n";
$text .= "theme : 'advanced'\n";
$text .= ",plugins : 'table,contextmenu";

$text .= ($pref['smiley_activate']) ? ",emoticons" : "";
$text .= (ADMIN) ? ",ibrowser" : ",image";
$text .= ",iespell,zoom,flash,forecolor";
$text .= "'\n"; // end of plugins list.

$text .= ",theme_advanced_buttons1 : 'fontsizeselect,separator,bold,italic,underline,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,bullist,numlist,outdent, indent,separator, forecolor,cut,copy,paste'";

$text .= ",theme_advanced_buttons2   : 'tablecontrols,separator,undo,redo,separator,link,unlink";
$text .= ($pref['smiley_activate']) ? ",emoticons" : "";
$text .= ",charmap,iespell,flash";
$text .= (ADMIN) ? ",ibrowser," : ",image";
$text .= (ADMIN) ? ",code" : "";
$text .= "'"; // end of buttons 2

$text .= ",theme_advanced_buttons3 : ''";
$text .= ",theme_advanced_toolbar_location : 'top'";
$text .= ",extended_valid_elements : 'p[style],a[name|href|title|style],img[class|src|style|alt|title|name],hr[class],span[class|style],div[class|style],table[class|style|cellpadding|cellspacing]'";
$text .= ",invalid_elements: 'p,font,align,script,applet,iframe'\n";
// $text .= ",auto_cleanup_word: true\n";
$text .= ",convert_fonts_to_spans : true\n";
$text .= ",trim_span_elements: true\n";
$text .= ",inline_styles: true\n";
$text .= ",debug: false\n";
$text .= ",force_br_newlines: true\n";
$text .= ",force_p_newlines: false\n";
$text .= ",convert_fonts_to_styles: true\n";
$text .= ",relative_urls: true\n";
$text .= ",document_base_url: '".SITEURL."'\n";
$text .= ",theme_advanced_styles: 'border=border;fborder=fborder;tbox=tbox;caption=caption;fcaption=fcaption;forumheader=forumheader;forumheader3=forumheader3'\n";
$text .= ",popup_css: '".THEME."style.css'\n";
$text .= ",verify_css_classes : false\n";

$text .= (ADMIN) ? "\n, external_link_list_url: '../".$HANDLERS_DIRECTORY."tiny_mce/filelist.php'\n" : "";

$text .= "

	});

</script>\n
";

return $text;

}


?>