<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system  �Steve Dunstan 2001-2002
|     http://e107.org jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_files/shortcode/batch/contact_shortcodes.php,v $
|     $Revision: 1.1 $
|     $Date: 2006-07-27 01:42:22 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }
include_once(e_HANDLER.'shortcode_handler.php');
$contact_shortcodes = $tp -> e_sc -> parse_scbatch(__FILE__);
/*
SC_BEGIN CONTACT_EMAIL_COPY
global $pref;
if(!isset($pref['contact_emailcopy']) || !$pref['contact_emailcopy'])
{
	return;
}
return "<input type='checkbox' name='email_copy'  value='1'  />";
SC_END

SC_BEGIN CONTACT_PERSON
global $sql,$tp,$pref;
if($pref['sitecontacts'] == e_UC_ADMIN){
	$query = "user_admin =1";
}elseif($pref['sitecontacts'] == e_UC_MAINADMIN){
    $query = "user_admin = 1 AND (user_perms = '0' OR user_perms = '0.') ";
}else{
	$query = $pref['sitecontacts'] . " IN (user_class) ";
}

$text = "<select name='contact_person' class='tbox contact_person'>\n";
$count = $sql -> db_Select("user", "user_id,user_name", $query . " ORDER BY user_name");
if($count > 1){
    while($row = $sql-> db_Fetch())
	{
    	$text .= "<option value='".$row['user_id']."'>".$row['user_name']."</option>\n";
    }
}else{
	return;
}
$text .= "</select>";
return $text;
SC_END



*/

?>