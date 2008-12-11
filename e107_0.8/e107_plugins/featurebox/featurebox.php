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
|     $Source: /cvs_backup/e107_0.8/e107_plugins/featurebox/featurebox.php,v $
|     $Revision: 1.4 $
|     $Date: 2008-12-11 21:13:48 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }
if (!plugInstalled('featurebox')) 
{
	return '';
}

if($sql -> db_Select("featurebox", "*", "fb_mode=1 AND fb_class IN (".USERCLASS_LIST.") ORDER BY fb_class ASC"))
{
	while($row = $sql->db_Fetch())
	{
		if($row['fb_class'] > 0 && $row['fb_class'] < 251)
		{
			extract($row);
			continue;
		}
		else
		{
			$xentry = $row;
		}
	}
	if(!isset($fb_title))
	{
		extract($xentry);
	}
}
else if($sql -> db_Select("featurebox", "*", "fb_mode!=1 AND fb_class IN (".USERCLASS_LIST.")"))
{
	$nfArray = $sql -> db_getList();
	$entry = $nfArray[array_rand($nfArray)];
	extract($entry);
}
else
{
	return FALSE;
}

$fbcc = $fb_title;
$fb_title = $tp -> toHTML($fb_title, TRUE,'title');
$fb_text = $tp -> toHTML($fb_text, TRUE,'body');
if(!$fb_rendertype)
{
	$ns -> tablerender($fb_title, $fb_text, 'featurebox');
}
else 
{
	require_once(e_PLUGIN."featurebox/templates/".$fb_template.".php");
	echo $FB_TEMPLATE;
}
?>