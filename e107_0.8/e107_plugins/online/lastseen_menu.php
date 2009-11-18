<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/online/lastseen_menu.php,v $
 * $Revision: 1.3 $
 * $Date: 2009-11-18 01:05:53 $
 * $Author: e107coders $
 */

if (!defined('e107_INIT')) { exit; }

include_lan(e_PLUGIN."online/languages/".e_LANGUAGE.".php");
require_once(e_PLUGIN.'online/online_shortcodes.php');
if (is_readable(THEME.'online_menu_template.php')) {
	require_once(THEME.'online_menu_template.php');
} else {
	require_once(e_PLUGIN.'online/online_menu_template.php');
}

global $tp, $row, $gen;
if (!is_object($gen)) { $gen = new convert; }

$num = varsettrue($menu_pref['online_ls_amount'],10);

$sql -> db_Select("user", "user_id, user_name, user_currentvisit", "ORDER BY user_currentvisit DESC LIMIT 0,".intval($num), "nowhere");
$lslist = $sql -> db_getList();

$text = $tp -> parseTemplate($TEMPLATE_LASTSEEN['START'], FALSE, $online_shortcodes);
foreach($lslist as $row)
{
	$text .= $tp -> parseTemplate($TEMPLATE_LASTSEEN['ITEM'], FALSE, $online_shortcodes);
}
$text .= $tp -> parseTemplate($TEMPLATE_LASTSEEN['END'], FALSE, $online_shortcodes);

$caption = varsettrue($menu_pref['online_ls_caption'],LAN_LASTSEEN_1);
$ns->tablerender($caption, $text, 'lastseen');

?>