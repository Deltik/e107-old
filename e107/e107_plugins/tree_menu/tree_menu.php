<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/tree_menu.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

/* Modification to keep menu status during navigation on the site
- Call the language file (only used for title !!! Maybe this title can be included in the default language file)
- Add a HTML id to the span tags (menus) : span_$link_name
- Add a javascript function to write a cookie when menu is opened (updatecook)
- Add a javascript function if menu is closed or no subitem (clearcook)
- Add event onclick for div without subitem, and modify the existing events for items WITH subitems
- Add a PHP function to read cookie (if existing) when page is loaded and restore menu status (writing or not window.onload js function)
*/

include(e_LANGUAGEDIR.e_LAN."/lan_sitelinks.php");

// Many thanks to Lolo Irie for fixing the javascript that drives this menu item
unset($text);
($_COOKIE["treemenustatus"]?$treemenustatus = $_COOKIE["treemenustatus"]:$treemenustatus="0");
$sql2 = new db;
$sql -> db_Select("links", "*", "link_category='1' AND link_name NOT REGEXP('submenu') ORDER BY link_order");
while($row = $sql -> db_Fetch()){
	extract($row);
	$link_name=strip_tags($link_name);
	if($sql2 -> db_Select("links", "*", "link_name REGEXP('submenu.".$link_name."') ORDER BY link_order")){
		if(!$link_class || check_class($link_class) || ($link_class==254 && USER)){
			$mlink_name = $link_name;
			$text .= "
			<div class='spacer'>
			<div class='button' style='width:100%; cursor: pointer; cursor: hand' onClick='expandit(this);updatecook(\"".$link_name."\");' >&raquo; ".$link_name."</div>
			<span style=\"display:none\" style=&{head}; id=\"span_".$link_name."\">";			
			while($row = $sql2 -> db_Fetch()){
				extract($row);
				if(!$link_class || check_class($link_class) || ($link_class==254 && USER)){
					$link_name = str_replace("submenu.".$mlink_name.".", "", $link_name);
					$text .= "&middot; ".(strstr($link_url, "http") ? setlink($link_name, $link_url, $link_open) : setlink($link_name, $link_url, $link_open))."\n<br />";
				}
			}
			$text .= "</span></div>";
		}
			}else{		
				if(!$link_class || check_class($link_class) || ($link_class==254 && USER)){
					$text .= "<div class='spacer'><div class='button' style='width:100%; cursor: pointer; cursor: hand' onClick=\"clearcook();\">&middot; ".
					(strstr($link_url, "http") ? setlink($link_name, $link_url, $link_open) : setlink($link_name, $link_url, $link_open))."
					</div></div>";
				}
			}
		}

$text .= "
<sc"."ript>
<!--
function updatecook(itemmenu){
	cookitem='span_'+itemmenu;
	if(document.getElementById(cookitem).style.display!='none'){
		var expireDate = new Date;
		expireDate.setMinutes(expireDate.getMinutes()+10);
		document.cookie = \"treemenustatus=\" + itemmenu + \"; expires=\" + expireDate.toGMTString();
	}
	else{
		clearcook();
	}
}\n

function clearcook(){
	var expireDate = new Date;
	expireDate.setMinutes(expireDate.getMinutes()+10);
	document.cookie = \"treemenustatus=\" + \"0\" + \"; expires=\" + expireDate.toGMTString();
}\n

//-->\n
";

(($treemenustatus!="0")?$text .= "window.onload=document.getElementById('span_".$treemenustatus."').style.display=''":"");

$text .= "</sc"."ript>
";
$ns -> tablerender(LAN_183, $text);


function setlink($link_name, $link_url, $link_open){
		switch ($link_open){ 
			case 1:
				$link_append = " onclick=\"window.open('$link_url'); return false;\"";
			break; 
			case 2:
				$link_append = " target=\"_parent\"";
			break;
			case 3:
				$link_append = " target=\"_top\"";
			break;
			default:
				unset($link_append);
		}
		if(!strstr($link_url, "http:")){ $link_url = e_BASE.$link_url; }
		if($link_open == 4){
			$link =  "<a style='text-decoration:none' href=\"javascript:open_window('".$link_url."')\">".$link_name."</a>\n";
		}else{
			$link =  "<a style='text-decoration:none' href=\"".$link_url."\"".$link_append.">".$link_name."</a>\n";
		}
	return $link;
}





?>