<?php
/*
+---------------------------------------------------------------+
|	e107 website system
|	/admin/footer.php
|
|	�Steve Dunstan 2001-2002
|	http://e107.org
|	jalist@e107.org
|
|	Released under the terms and conditions of the
|	GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
@include(e_LANGUAGEDIR.$language."/admin/lan_footer.php");
@include(e_LANGUAGEDIR."English/admin/lan_footer.php");
echo "\n</td>
<td style='width:20%; vertical-align:top'>";

if(ADMIN){

$sql -> db_Select("core", "*", "e107_name='e107' ");
$row = $sql -> db_Fetch();
$e107info = unserialize($row['e107_value']);

if(file_exists(e_ADMIN."ver.php")){ require_once(e_ADMIN."ver.php"); }

$obj = new convert;
$install_date = $obj->convert_date($e107info['e107_datestamp'], "long");

$plugindir = (str_replace("/","",str_replace("..","",e_PLUGIN))."/");
$plugpath = e_PLUGIN.str_replace(basename(e_SELF),"",str_replace($plugindir,"",strstr(e_SELF,$plugindir)))."admin_menu.php";
if(file_exists($plugpath)){
	require_once($plugpath);
}

$text = "<b>".FOOTLAN_1."</b>
<br />".
SITENAME." 
<br /><br />
<b>".FOOTLAN_2."</b>
<br />
<a href=\"mailto:".SITEADMINEMAIL."\">".SITEADMIN."</a>
<br />
<br />
<b>e107</b>
<br />
".FOOTLAN_3." ".$e107info['e107_version']. ($e107info['e107_build'] ? " ".FOOTLAN_4." ".$e107info['e107_build'] : "")."
<br /><br />
<b>".FOOTLAN_5."</b>
<br />
".$themename." v".$themeversion." ".FOOTLAN_6." ".$themeauthor." (".$themedate.")
<br />
".FOOTLAN_7.": ".$themeinfo."
<br /><br />
<b>".FOOTLAN_8."</b>
<br />
".$install_date."
<br /><br />
<b>".FOOTLAN_9."</b>
<br />".
 eregi_replace("PHP.*", "", $_SERVER['SERVER_SOFTWARE'])."<br />(".FOOTLAN_10.": ".$_SERVER['SERVER_NAME'].")
<br /><br />
<b>".FOOTLAN_11."</b>
<br />
".phpversion()."
<br /><br />
<b>".FOOTLAN_12."</b>
<br />
".mysql_get_server_info().
"<br />
".FOOTLAN_16.": ".$mySQLdefaultdb;
$ns -> tablerender("Site Info", $text);

$c=1;
$handle=opendir(e_DOCS);
while ($file = readdir($handle)){
	if($file != "." && $file != ".."){
		$helplist[$c] = $file;
		$c++;
	}
}
closedir($handle);

if($pref['cachestatus']){
	if(!$sql -> db_Select("tmp", "*", " tmp_ip='var_store' && tmp_time='1' ")){		// var_store 1 == cache empty time
		$sql -> db_Insert("tmp", "'var_store', 1, '".$e107info['e107_datestamp']."' ");
	}else{
		$row = $sql -> db_Fetch(); extract($row);
		if(($tmp_info+604800) < time()){
			$sql -> db_Delete("cache");
			$sql -> db_Update("tmp", "tmp_info='".time()."' WHERE tmp_ip='var_store' AND tmp_time=1 ");
		}
	}
}


$text = "� <a style='cursor: pointer; cursor: hand' onclick=\"expandit(this)\">".FOOTLAN_14."</a>
<div style='display: none;'>
<br />";
while(list($key, $value) = each($helplist)){ 
	$text .= "<img src='".THEME."images/bullet2.gif' alt='' /> <a href='".e_ADMIN."docs.php?$key'>$value</a><br />"; 
}
$text .= "</div>";
$ns -> tablerender(FOOTLAN_15, $text);


}
?>
</td>
</tr>
</table>
</div>
</div>
<br />
<br />
</body>
</html>

<?php
$sql -> db_Close();
?>