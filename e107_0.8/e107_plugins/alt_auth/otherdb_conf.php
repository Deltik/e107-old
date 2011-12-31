<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2012 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Alt_auth plugin - 'otherdb' configuration
 *
 * $URL$
 * $Id$
 * 
 */
 
/**
 *	e107 Alternate authorisation plugin
 *
 *	@package	e107_plugins
 *	@subpackage	alt_auth
 *	@version 	$Id$;
 */
$eplug_admin = true;
require_once('../../class2.php');
require_once(e_ADMIN.'auth.php');
require_once(e_HANDLER.'form_handler.php');
include_lan(e_PLUGIN.'alt_auth/languages/'.e_LANGUAGE.'/admin_otherdb_conf.php');
include_lan(e_PLUGIN.'alt_auth/languages/'.e_LANGUAGE.'/admin_alt_auth.php');
define('ALT_AUTH_ACTION', 'otherdb');
require_once(e_PLUGIN.'alt_auth/alt_auth_adminmenu.php');
require_once(e_PLUGIN.'alt_auth/extended_password_handler.php');


if($_POST['update'])
{
	$message = alt_auth_post_options('otherdb');
}


if($message)
{
	e107::getRender()->tablerender('',"<div style='text-align:center;'>".$message.'</div>');
}



show_otherdb_form();

function show_otherdb_form()
{
	$ns = e107::getRender();

	$parm = altAuthGetParams('otherdb');

	$frm = new form;
	$text = $frm -> form_open("post", e_SELF);
	$text .= "<table style='width:96%' class='fborder'>";

	$text .= "<tr><td class='forumheader3'>".LAN_ALT_26."</td><td class='forumheader3'>";
	$text .= OTHERDB_LAN_15;
	$text .= "</td></tr>";

	$text .= alt_auth_get_db_fields('otherdb', $frm, $parm, 'server|uname|pwd|db|table|ufield|pwfield|salt');
	$text .= "<tr><td class='forumheader3'>".OTHERDB_LAN_9."</td><td class='forumheader3'>";
	
	$text .= altAuthGetPasswordSelector('otherdb_password_method', $frm, $parm['otherdb_password_method'], TRUE);

	$text .= "</td></tr>";

	$text .= "<tr><td class='forumheader2' colspan='2'>".LAN_ALT_27."</td></tr>";

	$text .= alt_auth_get_field_list('otherdb',$frm, $parm, FALSE);

	$text .= "<tr><td class='forumheader' colspan='2' style='text-align:center;'>";
	$text .= $frm -> form_button('submit', 'update', LAN_ALT_UPDATESET);
	$text .= '</td></tr>';

	$text .= '</table>';
	$text .= $frm -> form_close();

	$ns -> tablerender(OTHERDB_LAN_10, $text);
	
	$ns->tablerender(LAN_ALT_40.LAN_ALT_41,alt_auth_test_form('otherdb',$frm));
}

require_once(e_ADMIN.'footer.php');



function otherdb_conf_adminmenu()
{
	alt_auth_adminmenu();
}

?>
