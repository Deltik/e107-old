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
 * $Source: /cvs_backup/e107_0.8/e107_plugins/calendar_menu/e_rss.php,v $
 * $Revision$
 * $Date$
 * $Author$
 */

/**
 *	e107 Event calendar plugin
 *
 *	@package	e107_plugins
 *	@subpackage	event_calendar
 *	@version 	$Id$;
 */

if (!defined('e107_INIT')) { exit; }

if (!e107::isInstalled('calendar_menu')) return;

include_lan(e_PLUGIN.'calendar_menu/languages/'.e_LANGUAGE.'_admin_calendar_menu.php');		// RSS messages are in admin language file



//##### create feed for admin, return array $eplug_rss_feed --------------------------------
$feed['name']		= EC_ADLAN_A12;
$feed['url']		= 'calendar';			//the identifier for the rss feed url
$feed['topic_id']	= '';					//the topic_id, empty on default (to select a certain category)
$feed['path']		= 'calendar_menu';		//this is the plugin path location
$feed['text']		= EC_ADLAN_A157;
$feed['class']		= '0';
$feed['limit']		= '9';
//##### ------------------------------------------------------------------------------------

require_once('ecal_class.php');
$ecal_class = new ecal_class;

//##### create rss data, return as array $eplug_rss_data -----------------------------------
$current_day	= $ecal_class->cal_date['mday'];
$current_month	= $ecal_class->cal_date['mon'];
$current_year	= $ecal_class->cal_date['year'];
$current		= mktime(0,0,0,$current_month, $current_day, $current_year);

$qry = "
SELECT e.*, c.event_cat_name
FROM `#event` AS e
LEFT JOIN `#event_cat` AS c ON c.event_cat_id = e.event_category
WHERE e.event_start>='{$current}' AND c.event_cat_class REGEXP '".e_CLASS_REGEXP."'
ORDER BY e.event_start ASC LIMIT 0,".$this->limit;

$rss = array();
$sqlrss = new db;
if($items = $sqlrss->db_Select_gen($qry))
{
	$i=0;
	while($rowrss = $sqlrss -> db_Fetch())
	{
		$tmp						= explode(".", $rowrss['event_author']);
		$rss[$i]['author']			= $tmp[1];
		$rss[$i]['author_email']	= '';
		$rss[$i]['link']			= $e107->base_path.$PLUGINS_DIRECTORY."calendar_menu/event.php?".$rowrss['event_start'].".event.".$rowrss['event_id'];
		$rss[$i]['linkid']			= $rowrss['event_id'];
		$rss[$i]['title']			= $rowrss['event_title'];
		$rss[$i]['description']		= '';
		$rss[$i]['category_name']	= $rowrss['event_cat_name'];
		$rss[$i]['category_link']	= '';
		$rss[$i]['datestamp']		= $rowrss['event_start'];
		$rss[$i]['enc_url']			= "";
		$rss[$i]['enc_leng']		= "";
		$rss[$i]['enc_type']		= "";
		$i++;
	}
}
//##### ------------------------------------------------------------------------------------

$eplug_rss_feed[] = $feed;
$eplug_rss_data[] = $rss;

?>