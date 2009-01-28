<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * List Admin Config
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/list_new/admin_list_config.php,v $
 * $Revision: 1.9 $
 * $Date: 2009-01-28 08:47:37 $
 * $Author: lisa_ $
 *
*/

//include and require several classes
require_once("../../class2.php");
if(!getperms("1") || !plugInstalled('list_new'))
{
	header("location:".e_BASE."index.php");
	exit ;
}
require_once(e_ADMIN."auth.php");
require_once(e_HANDLER."form_handler.php");
$rs = new form;
require_once(e_HANDLER."file_class.php");
$fl = new e_file;

require_once(e_PLUGIN."list_new/list_class.php");
$rc = new listclass('admin');

//get all sections to use (and reload if new e_list.php files are added)
$rc->getSections();

//update preferences in database
if(isset($_POST['update_menu']))
{
	$message = $rc->admin->db_update_menu();
}

//check preferences from database
$rc->list_pref = $rc->getListPrefs();

//render message if set
if(isset($message))
{
	$MESSAGE = $message;
	$t = preg_replace("/\{(.*?)\}/e", '$\1', $rc->template['ADMIN_MESSAGE']);
	$rc->e107->ns->tablerender('', $t);
}

//display admin page
$text = $rc->admin->display();

$rc->e107->ns->tablerender(LIST_ADMIN_1, $text);

/**
 * Display admin menu
 *
 * @return string menu
 */
function admin_list_config_adminmenu()
{
	$act = "";
	unset($var);
	$var=array();
	//$var['general']['text'] = LIST_ADMIN_OPT_1;
	$var['list-new-recent-page']['text'] = LIST_ADMIN_OPT_2;
	$var['list-new-recent-menu']['text'] = LIST_ADMIN_OPT_3;
	$var['list-new-new-page']['text'] = LIST_ADMIN_OPT_4;
	$var['list-new-new-menu']['text'] = LIST_ADMIN_OPT_5;
	e_admin_menu(LIST_ADMIN_OPT_6.'--id--list_new', 'list-new-recent-page', $var);
}

require_once(e_ADMIN."footer.php");

/**
 * Handle page DOM within the page header
 *
 * @return string JS source
 */
function headerjs()
{
	require_once(e_HANDLER.'js_helper.php');
	$ret = "
		<script type='text/javascript'>
			//add required core lan - delete confirm message
			(".e_jshelper::toString(LAN_JSCONFIRM).").addModLan('core', 'delete_confirm');
		</script>
		<script type='text/javascript' src='".e_FILE_ABS."jslib/core/admin.js'></script>
	";

	return $ret;
}
?>