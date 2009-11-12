<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_files/shortcode/batch/contact_shortcodes.php,v $
 * $Revision: 1.2 $
 * $Date: 2009-11-12 14:37:12 $
 * $Author: marj_nl_fr $
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
	$query = "FIND_IN_SET(".$pref['sitecontacts'].",user_class) ";
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


SC_BEGIN CONTACT_IMAGECODE
global $sec_img;
return "<input type='hidden' name='rand_num' value='".$sec_img->random_number."' />".$sec_img->r_image();
SC_END

SC_BEGIN CONTACT_IMAGECODE_INPUT
return "<input class='tbox' type='text' name='code_verify' size='15' maxlength='20' />";
SC_END

*/

?>