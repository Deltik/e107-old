<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
|     Copyright (C) 2008-2010 e107 Inc (e107.org)
|
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $URL$
|     $Revision$
|     $Id$
|     $Author$
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

// advanced 
$advanced_where = "";
if (isset($_GET['time']) && is_numeric($_GET['time'])) {
	$advanced_where .= " page_datestamp ".($_GET['on'] == 'new' ? '>=' : '<=')." '".(time() - $_GET['time'])."' AND";
}

// basic
$return_fields = 'page_id, page_title, page_text, page_datestamp';
$search_fields = array('page_title', 'page_text');
$weights = array('1.2', '0.6');
$no_results = LAN_198;

$where = "page_class IN (".USERCLASS_LIST.") AND `page_theme` = '' AND".$advanced_where;
$order = array('page_datestamp' => DESC);
$table = "page";

$ps = $sch -> parsesearch($table, $return_fields, $search_fields, $weights, 'search_pages', $no_results, $where, $order);
$text .= $ps['text'];
$results = $ps['results'];

function search_pages($row) {
	global $con;
	$res['link'] = "page.php?".$row['page_id'];
	$res['pre_title'] = "";
	$res['title'] = $row['page_title'];
	$res['summary'] = $row['page_text'];
	$res['detail'] = LAN_SEARCH_3.$con -> convert_date($row['page_datestamp'], "long");
	return $res;
}

?>