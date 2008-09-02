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
include_lan("languages/".e_LANGUAGE."/lan_importdb_conf.php");
include_lan("languages/".e_LANGUAGE."/lan_alt_auth_conf.php");
define("ALT_AUTH_ACTION", "importdb");
require_once(e_PLUGIN."alt_auth/alt_auth_adminmenu.php");
require_once(e_PLUGIN."alt_auth/extended_password_handler.php");


if($_POST['update'])
{
//	$message = update_importdb_prefs();
	$message = alt_auth_post_options('importdb');
}

if($message)
{
	$ns->tablerender("","<div style='text-align:center;'>".$message."</div>");
}


show_importdb_form();

function show_importdb_form()
{
	global $sql, $tp, $ns;
	
	$password_methods = ExtendedPasswordHandler::GetPasswordTypes(FALSE); 


	// Get the parameters
	$sql -> db_Select("alt_auth", "*", "auth_type = 'importdb' ");
	$parm = array();
	while($row = $sql->db_Fetch())
	{
		$parm[$row['auth_parmname']] = base64_decode(base64_decode($row['auth_parmval']));
	}


	$frm = new form;
	$text = $frm -> form_open("post", e_SELF);
	$text .= "<table style='width:96%'>";


	$text .= "<tr><td colspan='2' class='forumheader3'>".IMPORTDB_LAN_11."</td></tr>";
/*	$text .= $frm -> form_select_open("importdb_dbtype");
	foreach($db_types as $k => $v)
	{
		$sel = ($parm['importdb_dbtype'] == $k) ? " Selected" : "";
		$text .= $frm -> form_option($v, $sel, $k);
	}
	$text .= $frm -> form_select_close();
	$text .= "</td></tr>";
*/
	$text .= "<tr><td class='forumheader3'>".IMPORTDB_LAN_9."</td><td class='forumheader3'>";
	$text .= $frm -> form_select_open("importdb_password_method");
	foreach($password_methods as $k => $v)
	{
		$sel = ($parm['importdb_password_method'] == $k) ? " Selected" : "";
		$text .= $frm -> form_option($v, $sel, $k);
	}
	$text .= $frm -> form_select_close();
	$text .= "</td></tr>";

	$text .= "<tr><td class='forumheader' colspan='2' style='text-align:center;'>";
	$text .= $frm -> form_button("submit", "update", LAN_ALT_UPDATESET);
	$text .= "</td></tr>";

	$text .= "</table>";
	$text .= $frm -> form_close();

	$ns -> tablerender(IMPORTDB_LAN_10, $text);

	$ns->tablerender(LAN_ALT_40.LAN_ALT_41,alt_auth_test_form('importdb',$frm));

}

require_once(e_ADMIN."footer.php");

/*
function update_importdb_prefs()
{
	global $sql;
	foreach($_POST as $k => $v)
	{
		$v = base64_encode(base64_encode($v));

		if(preg_match("/importdb_/", $k))
		{
			if($sql -> db_Select("alt_auth", "*", "auth_type='importdb' AND auth_parmname='{$k}' "))
			{
				$sql -> db_Update("alt_auth", "auth_parmval='{$v}' WHERE  auth_type='importdb' AND auth_parmname='{$k}' ");
			}
			else
			{
				$sql -> db_Insert("alt_auth", "'importdb','{$k}','{$v}' ");
			}
		}
	}
	return "Settings Updated";
}
*/

function importdb_conf_adminmenu()
{
	alt_auth_adminmenu();
}

?>
