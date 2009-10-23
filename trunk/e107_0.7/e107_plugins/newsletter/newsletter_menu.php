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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/newsletter/newsletter_menu.php,v $
|     $Revision: 1.4 $
|     $Date: 2006-01-15 01:47:41 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

if(!USER || !$sql -> db_Select("newsletter", "*", "newsletter_parent='0' "))
{
	// no newsletters defined yet //
	return FALSE;
}

$newsletterArray = $sql -> db_getList();
$requery = false;

foreach($_POST as $key => $value)
{
	if(strstr($key, "nlUnsubscribe_"))
	{
		$subid = str_replace("nlUnsubscribe_", "", $key);
		$newsletterArray[$subid]['newsletter_subscribers'] = str_replace(chr(1).USERID, "", $newsletterArray[$subid]['newsletter_subscribers']);
		$sql -> db_Update("newsletter", "newsletter_subscribers='".$newsletterArray[$subid]['newsletter_subscribers']."' WHERE newsletter_id='".intval($subid)."' ");
		$requery = true;
	}
	else if(strstr($key, "nlSubscribe_"))
	{
		$subid = str_replace("nlSubscribe_", "", $key);
		$newsletterArray[$subid]['newsletter_subscribers'] .= chr(1).USERID;
		$sql -> db_Update("newsletter", "newsletter_subscribers='".$newsletterArray[$subid]['newsletter_subscribers']."' WHERE newsletter_id='".intval($subid)."' ");
		$requery = true;
	}
}

global $tp;

if($requery)
{
	if($sql -> db_Select("newsletter", "*", "newsletter_parent='0' "))
	{
		$newsletterArray = $sql -> db_getList();
	}
}	

$text = "";
foreach($newsletterArray as $nl)
{
	$text .= "<div style='text-align: center; margin-left: auto; margin-right: auto;'>
	<form method='post' action='".e_SELF."'>
	<b>".
	$tp -> toHTML($nl['newsletter_title'], TRUE)."</b><br />
	<span class='smalltext'>".
	$tp -> toHTML($nl['newsletter_text'], TRUE)."</span><br /><br />
	";

	if(preg_match("#".chr(1).USERID."(".chr(1)."|$)#si", $nl['newsletter_subscribers']))
	{
		$text .= NLLAN_48."<br /><br />
		<input class='button' type='submit' name='nlUnsubscribe_".$nl['newsletter_id']."' value='".NLLAN_51."' onclick=\"return jsconfirm('".$tp->toJS(NLLAN_49)."') \" />
		";
	}
	else
	{
		$text .= NLLAN_50." <b>".USEREMAIL."</b> ) ...<br /><br />
		<input class='button' type='submit' name='nlSubscribe_".$nl['newsletter_id']."' value='".NLLAN_52."' onclick=\"return jsconfirm('".$tp->toJS(NLLAN_53)."') \" />
		";
	}
	$text .= "</form>
	</div>
	<br />
	";
}

$ns -> tablerender(NLLAN_MENU_CAPTION, $text);

	
?>