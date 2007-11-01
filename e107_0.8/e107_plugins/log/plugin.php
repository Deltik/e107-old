<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.8/e107_plugins/log/plugin.php,v $
|     $Revision: 1.4 $
|     $Date: 2007-11-01 20:28:21 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

@include_once(e_PLUGIN."log/languages/admin/".e_LANGUAGE.".php");
@include_once(e_PLUGIN."log/languages/admin/English.php");

// Plugin info -------------------------------------------------------------------------------------------------------
$eplug_name = ADSTAT_L3;
$eplug_version = "2.1";
$eplug_author = "jalist";
$eplug_url = "http://e107.org";
$eplug_email = "jalist@e107.org";
$eplug_description = ADSTAT_L1;
$eplug_compatible = "e107v0.7";
$eplug_readme = "";
// leave blank if no readme file
	
// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = "log";
	
// Name of menu item for plugin ----------------------------------------------------------------------------------
$eplug_menu_name = "";
	
// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile = "admin_config.php";
	
// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder."/images/stats_32.png";
$eplug_icon_small = $eplug_folder."/images/stats_16.png";
$eplug_caption = ADSTAT_L33;
	
// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefTable = "menu_pref";
$eplug_prefs = array(
		"statActivate" => 0, 
		"statUserclass" => "", 
		"statBrowser" => 1, 
		"statOs" => 1, 
		"statScreen" => 1, 
		"statDomain" => 1, 
		"statRefer" => 1, 
		"statQuery" => 1, 
		"statRecent" => 1,
		"statPrevMonth" => 0
		);
	
// List of table names -----------------------------------------------------------------------------------------------
$eplug_table_names = array("logstats");
	
// List of sql requests to create tables -----------------------------------------------------------------------------
$eplug_tables = array(
"CREATE TABLE ".MPREFIX."logstats (
  log_uniqueid int(11) NOT NULL auto_increment,
  log_id varchar(50) NOT NULL default '',
  log_data longtext NOT NULL,
  PRIMARY KEY  (log_uniqueid),
  UNIQUE KEY log_id (log_id)
) TYPE=MyISAM ");
	
	
// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link = TRUE;
$eplug_link_name = ADSTAT_L34;
$eplug_link_url = e_PLUGIN."log/stats.php?1";
	
	
// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = ADSTAT_L2;

$upgrade_add_prefs = array("statPrevMonth" => 0);
$upgrade_remove_prefs = array("statClass");

	
?>