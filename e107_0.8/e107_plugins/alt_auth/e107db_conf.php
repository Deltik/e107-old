<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
+----------------------------------------------------------------------------+
*/
$eplug_admin = true;
require_once("../../class2.php");
require_once(e_ADMIN."auth.php");
require_once(e_HANDLER."form_handler.php");
include_lan("languages/".e_LANGUAGE."/lan_e107db_auth.php");
include_lan("languages/".e_LANGUAGE."/lan_alt_auth_conf.php");
define("ALT_AUTH_ACTION", "e107db");
require_once(e_PLUGIN."alt_auth/alt_auth_adminmenu.php");
require_once(e_PLUGIN."alt_auth/extended_password_handler.php");

if($_POST['update'])
{
//	$message = update_e107db_prefs();
	$message = alt_auth_post_options('e107db');
}

if($message)
{
  $ns->tablerender("","<div style='text-align:center;'>".$message."</div>");
}


show_e107db_form();

function show_e107db_form()
{
	global $sql, $tp, $ns;
	
	
	$password_methods = ExtendedPasswordHandler::GetPasswordTypes('core'); 

	$sql -> db_Select("alt_auth", "*", "auth_type = 'e107db' ");
	$parm = array();
	while($row = $sql->db_Fetch())
	{
		$parm[$row['auth_parmname']] = base64_decode(base64_decode($row['auth_parmval']));
	}

	$frm = new form;
	$text = $frm -> form_open("post", e_SELF);
	$text .= "<table style='width:96%'>";

	$text .= "<tr><td class='forumheader3'>".LAN_ALT_26."</td><td class='forumheader3'>";
	$text .= E107DB_LAN_1;
	$text .= "</td></tr>";

	$text .= alt_auth_get_db_fields('e107db', $frm, $parm, 'server|uname|pwd|db');

	$text .= "<tr><td class='forumheader3'>".E107DB_LAN_9."</td><td class='forumheader3'>";
	$text .= $frm -> form_select_open("e107db_password_method");
	foreach($password_methods as $k => $v)
	{
		$sel = ($parm['e107db_password_method'] == $k) ? " Selected" : "";
		$text .= $frm -> form_option($v, $sel, $k);
	}
	$text .= $frm -> form_select_close();
	$text .= "</td></tr>";

	$text .= "<tr><td class='forumheader2' colspan='2'>".E107DB_LAN_11."</td></tr>";

	$text .= alt_auth_get_field_list('e107db',$frm, $parm, TRUE);

	$text .= "<tr><td class='forumheader' colspan='2' style='text-align:center;'>";
	$text .= $frm -> form_button("submit", "update", LAN_ALT_UPDATESET);
	$text .= "</td></tr>";

	$text .= "</table>";
	$text .= $frm -> form_close();

	$ns -> tablerender(E107DB_LAN_10, $text);
	
	$ns->tablerender(LAN_ALT_40.LAN_ALT_41,alt_auth_test_form('e107db',$frm));
}

require_once(e_ADMIN."footer.php");



// Obsolete function update_e107db_prefs()
/*
function update_e107db_prefs()
{
	global $sql;
	foreach($_POST as $k => $v)
	{
		$v = base64_encode(base64_encode($v));

		if (substr($k,0,7) == 'e107db_')
//		if(preg_match("/e107db_/", $k))
		{
			if($sql -> db_Select("alt_auth", "*", "auth_type='e107db' AND auth_parmname='{$k}' "))
			{
				$sql -> db_Update("alt_auth", "auth_parmval='{$v}' WHERE  auth_type='e107db' AND auth_parmname='{$k}' ");
			}
			else
			{
				$sql -> db_Insert("alt_auth", "'e107db','{$k}','{$v}' ");
			}
		}
	}
	return E107DB_LAN_UPDATED;
}
*/
function e107db_conf_adminmenu()
{
	alt_auth_adminmenu();
}

?>
