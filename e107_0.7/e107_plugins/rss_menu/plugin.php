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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/rss_menu/plugin.php,v $
|     $Revision: 1.3 $
|     $Date: 2006-06-20 06:50:09 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

$lan_file = e_PLUGIN."rss_menu/languages/".e_LANGUAGE.".php";
require_once(file_exists($lan_file) ? $lan_file : e_PLUGIN."rss_menu/languages/English.php");

// Plugin info -------------------------------------------------------------------------------------------------------
$eplug_name				= RSS_PLUGIN_LAN_1;
$eplug_version			= "1.1";
$eplug_author			= "e107dev";
$eplug_logo				= "";
$eplug_url				= "http://e107.org";
$eplug_email			= "";
$eplug_description		= RSS_PLUGIN_LAN_2;
$eplug_compatible		= "e107v0.7+";
$eplug_readme			= "";		// leave blank if no readme file
$eplug_latest			= FALSE;	//Show reported threads in admin (use latest.php)
$eplug_status			= FALSE;	//Show post count in admin (use status.php)

// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder			= "rss_menu";

// Name of menu item for plugin ----------------------------------------------------------------------------------
$eplug_menu_name		= "rss_menu";

// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile			= "admin_prefs.php";

// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon				= $eplug_folder."/images/rss_32.png";
$eplug_icon_small		= $eplug_folder."/images/rss_16.png";
$eplug_caption			= RSS_PLUGIN_LAN_3;

// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefs			= array();

// List of comment_type ids used by this plugin. -----------------------------
$eplug_comment_ids	= array();

// List of bbcode -----------------------------------------------------------------------------------------------
//$eplug_bb				= array('');

// List of table names -----------------------------------------------------------------------------------------------
$eplug_table_names		= array("rss");

// List of sql requests to create tables -----------------------------------------------------------------------------
$eplug_tables = array(
	"CREATE TABLE ".MPREFIX."rss (
	rss_id int(10) unsigned NOT NULL auto_increment,
	rss_name varchar(255) NOT NULL default '',
	rss_url text NOT NULL,
	rss_topicid varchar(255) NOT NULL default '',
	rss_path varchar(255) NOT NULL default '',
	rss_text longtext NOT NULL,
	rss_datestamp int(10) unsigned NOT NULL default '0',
	rss_class tinyint(1) unsigned NOT NULL default '0',
	rss_limit tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY (rss_id)
	) TYPE=MyISAM;",

	"INSERT INTO ".MPREFIX."rss VALUES
	(0, 'News', 'news', '', 'news', 'The rss feed of the news', '".time()."', 0, 9),
	(0, 'Downloads', 'download', '', 'download', 'The rss feed of the downloads', '".time()."', 0, 9),
	(0, 'Comments', 'comments', '', 'comments', 'The rss feed of the comments', '".time()."', 0, 9)
	"
);

// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link				= FALSE;
$eplug_link_name		= '';
$eplug_link_url			= '';

// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done			= RSS_PLUGIN_LAN_4;

// upgrading ... //
$upgrade_add_prefs		= "";
$upgrade_remove_prefs	= "";

$upgrade_alter_tables	= array(
	"CREATE TABLE ".MPREFIX."rss (
	rss_id int(10) unsigned NOT NULL auto_increment,
	rss_name varchar(255) NOT NULL default '',
	rss_url text NOT NULL,
	rss_topicid varchar(255) NOT NULL default '',
	rss_path varchar(255) NOT NULL default '',
	rss_text longtext NOT NULL,
	rss_datestamp int(10) unsigned NOT NULL default '0',
	rss_class tinyint(1) unsigned NOT NULL default '0',
	rss_limit tinyint(3) unsigned NOT NULL default '0',
	PRIMARY KEY (rss_id)
	) TYPE=MyISAM;",

	"INSERT INTO ".MPREFIX."rss VALUES
	(0, 'news', 'news', '', 'news', 'this is the rss feed for the news entries', '".time()."', 0, 9),
	(0, 'download', 'download', '', 'download', 'this is the rss feed for the download entries', '".time()."', 0, 9),
	(0, 'comments', 'comments', '', 'comments', 'this is the rss feed for the comments entries', '".time()."', 0, 9)
	"
);

$eplug_upgrade_done		= RSS_PLUGIN_LAN_5;

?>