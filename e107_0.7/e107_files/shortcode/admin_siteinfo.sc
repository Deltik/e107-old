if (ADMIN) {
	global $sql,$ns, $themename, $themeversion, $themeauthor, $themedate, $themeinfo, $mySQLdefaultdb;

	$sql -> db_Select("core", "*", "e107_name='e107' ");
	$row = $sql -> db_Fetch();
	$e107info = unserialize($row['e107_value']);

    $e107info['e107_version'] = "";
	$e107info['e107_build'] = "";

	if(file_exists(e_ADMIN."ver.php")){ @include_once(e_ADMIN."ver.php"); }

	$obj = new convert;
	$install_date = $obj->convert_date($e107info['e107_datestamp'], "long");

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
	".FOOTLAN_3." ".$e107info['e107_version']."
	<br /><br />
	<b>".FOOTLAN_5."</b>
	<br />
	".$themename." v".$themeversion." ".($themeauthor ? FOOTLAN_6.' '.$themeauthor : '')." ".($themedate ? "(".$themedate.")" : "");

	$text .= $themeinfo ? "<br />".FOOTLAN_7.": ".$themeinfo : "";

	$text .= "<br /><br />
	<b>".FOOTLAN_8."</b>
	<br />
	".$install_date."
	<br /><br />
	<b>".FOOTLAN_9."</b>
	<br />".
	 preg_replace("/PHP.*/i", "", $_SERVER['SERVER_SOFTWARE'])."<br />(".FOOTLAN_10.": ".$_SERVER['SERVER_NAME'].")
	<br /><br />
	<b>".FOOTLAN_11."</b>
	<br />
	".phpversion()."
	<br /><br />
	<b>".FOOTLAN_12."</b>
	<br />
	".mysql_get_server_info().
	"<br />
	".FOOTLAN_16.": ".$mySQLdefaultdb."
	<br /><br />
	<b>".FOOTLAN_17."</b>
	<br />".CHARSET;
	return $ns -> tablerender(FOOTLAN_13, $text, '', TRUE);
}