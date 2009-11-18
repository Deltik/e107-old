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
 * $Source: /cvs_backup/e107_0.8/e107_plugins/links_page/search/search_advanced.php,v $
 * $Revision: 1.3 $
 * $Date: 2009-11-18 01:05:47 $
 * $Author: e107coders $
 */

if (!defined('e107_INIT')) { exit; }

$advanced['cat']['type'] = 'dropdown';
$advanced['cat']['text'] = LAN_SEARCH_63.':';
$advanced['cat']['list'][] = array('id' => 'all', 'title' => LAN_SEARCH_51);

$advanced_caption['id'] = 'cat';
$advanced_caption['title']['all'] = LNK_SCH_LAN_2;

if ($sql -> db_Select("links_page_cat", "link_category_id, link_category_name")) {
	while ($row = $sql -> db_Fetch()) {
		$advanced['cat']['list'][] = array('id' => $row['link_category_id'], 'title' => $row['link_category_name']);
		$advanced_caption['title'][$row['link_category_id']] = LNK_SCH_LAN_1.' -> '.$row['link_category_name'];
	}
}

$advanced['match']['type'] = 'dropdown';
$advanced['match']['text'] = LAN_SEARCH_52.':';
$advanced['match']['list'][] = array('id' => 0, 'title' => LNK_SCH_LAN_3);
$advanced['match']['list'][] = array('id' => 1, 'title' => LAN_SEARCH_54);

?>