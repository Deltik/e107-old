<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * List Menu Recent
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/list_new/list_recent_menu.php,v $
 * $Revision: 1.5 $
 * $Date: 2009-01-27 21:33:52 $
 * $Author: lisa_ $
 *
*/

if (!defined('e107_INIT')) { exit; }

if (!plugInstalled('list_new'))
{
	return;
}

unset($text);

global $rc;
if (!is_object($rc))
{
    require_once(e_PLUGIN."list_new/list_class.php");
    $rc = new listclass;
}

//set mode
$rc->mode = "recent_menu";

//parse menu
$text = $rc->displayMenu();

$caption = varsettrue($rc->list_pref[$rc->mode."_caption"], LIST_MENU_1);
$rc->e107->ns->tablerender($caption, $text, 'list_recent');
unset($text);

?>