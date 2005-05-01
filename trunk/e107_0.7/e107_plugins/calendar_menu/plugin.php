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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/calendar_menu/plugin.php,v $
|     $Revision: 1.6 $
|     $Date: 2005-05-01 04:37:02 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
// Plugin info -------------------------------------------------------------------------------------------------------
$lan_file = e_PLUGIN."calendar_menu/languages/".e_LANGUAGE.".php";
@require_once(file_exists($lan_file) ? $lan_file : e_PLUGIN."calendar_menu/languages/English.php");
$eplug_name = "Event Calendar";
$eplug_version = "3.5";
$eplug_author = "jalist / cameron / McFly / Barry";
$eplug_url = "http://e107.org";
$eplug_email = "jalist@e107.org";
$eplug_description = EC_LAN_107;
$eplug_compatible = "e107v7";
$eplug_readme = "readme.rtf";
// leave blank if no readme file
$eplug_compliant = TRUE; 
	
// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = "calendar_menu";
	
// Mane of menu item for plugin ----------------------------------------------------------------------------------
$eplug_menu_name = "calendar_menu";
	
// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile = "admin_config.php";
	
// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder."/images/calendar_32.png";
$eplug_icon_small = $eplug_folder."/images/calendar_16.png";
$eplug_caption = EC_LAN_81; // "Configure Event Calendar";
	
// List of preferences -----------------------------------------------------------------------------------------------
$eplug_prefs = array(
"eventpost_admin" => 0,
"eventpost_headercss" => "forumheader",
"eventpost_daycss" => "forumheader3",
"eventpost_todaycss" => "indent",
"eventpost_addcat" => 0,
"eventpost_forum" => 1,
"eventpost_evtoday" => "indent",
"eventpost_mailsubject" => "Calendar",
"eventpost_mailfrom" => "e107 Web Site",
"eventpost_mailaddress" => "calendar@yoursite.com",
"eventpost_lenday" => 1,
"eventpost_asubs" => 1,
"eventpost_weekstart" => "sun" );
	
// List of table names -----------------------------------------------------------------------------------------------
$eplug_table_names = array("event","event_cat","event_subs" );
	
// List of sql requests to create tables -----------------------------------------------------------------------------
$eplug_tables = array(
"CREATE TABLE ".MPREFIX."event (
	event_id int(11) unsigned NOT NULL auto_increment,
	event_start int(10) NOT NULL default '0',
	event_end int(10) NOT NULL default '0',
	event_allday tinyint(1) unsigned NOT NULL default '0',
	event_recurring tinyint(1) unsigned NOT NULL default '0',
	event_datestamp int(10) unsigned NOT NULL default '0',
	event_title varchar(200) NOT NULL default '',
	event_location text NOT NULL,
	event_details text NOT NULL,
	event_author varchar(100) NOT NULL default '',
	event_contact varchar(200) NOT NULL default '',
	event_category smallint(5) unsigned NOT NULL default '0',
	event_thread varchar(100) NOT NULL default '',
	event_rec_m tinyint(2) unsigned NOT NULL default '0',
	event_rec_y tinyint(2) unsigned NOT NULL default '0',
	PRIMARY KEY  (event_id)
	) TYPE=MyISAM;",
	"CREATE TABLE ".MPREFIX."event_cat (
	event_cat_id smallint(5) unsigned NOT NULL auto_increment,
	event_cat_name varchar(100) NOT NULL default '',
	event_cat_icon varchar(100) NOT NULL default '',
	event_cat_class int(10) unsigned NOT NULL default '0',
	event_cat_subs tinyint(3) unsigned NOT NULL default '0',
	event_cat_force tinyint(3) unsigned NOT NULL default '0',
	event_cat_ahead tinyint(3) unsigned NOT NULL default '0',
	event_cat_msg1 text,
	event_cat_msg2 text,
	event_cat_notify  tinyint(3) unsigned NOT NULL default '0',
	event_cat_last int(10) unsigned NOT NULL default '0',
	event_cat_today int(10) unsigned NOT NULL default '0',
	event_cat_lastupdate int(10) unsigned NOT NULL default '0',
	event_cat_addclass int(10) unsigned NOT NULL default '0',
	PRIMARY KEY  (event_cat_id)
	) TYPE=MyISAM;"
	,
	"CREATE TABLE ".MPREFIX."event_subs (
	event_subid int(10) unsigned NOT NULL auto_increment,
	event_userid  int(10) unsigned NOT NULL default '0',
	event_cat  int(10) unsigned NOT NULL default '0',
	PRIMARY KEY  (event_subid)
	) TYPE=MyISAM;");
	
	
// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link = TRUE;
$eplug_link_name = EC_LAN_83; // "Calendar";
$ec_dir = e_PLUGIN."calendar_menu/";
$eplug_link_url = "".$ec_dir."calendar.php";
	
	
// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = EC_LAN_82; // "To activate please go to your menus screen and select the calendar_menu into one of your menu areas.";
	
	
// upgrading ... //
	
$upgrade_add_prefs = "";
	
$upgrade_remove_prefs = "";
	
$upgrade_alter_tables = array(
"alter table ".MPREFIX."event_cat add event_cat_subs tinyint(3) unsigned NOT NULL default '0'",
"alter table ".MPREFIX."event_cat add event_cat_force tinyint(3) unsigned NOT NULL default '0'",
"alter table ".MPREFIX."event_cat add event_cat_ahead tinyint(3) unsigned NOT NULL default '0'",
"alter table ".MPREFIX."event_cat add event_cat_msg1 text",
"alter table ".MPREFIX."event_cat add event_cat_msg2 text",
"alter table ".MPREFIX."event_cat add event_cat_notify  tinyint(3) unsigned NOT NULL default '0'",
"alter table ".MPREFIX."event_cat add event_cat_last int(10) unsigned NOT NULL default '0'",
"alter table ".MPREFIX."event_cat add event_cat_today int(10) unsigned NOT NULL default '0'",
"alter table ".MPREFIX."event_cat add event_cat_lastupdate int(10) unsigned NOT NULL default '0'",
"alter table ".MPREFIX."event_cat add event_cat_addclass int(10) unsigned NOT NULL default '0'",
"CREATE TABLE ".MPREFIX."event_subs (
	event_subid int(10) unsigned NOT NULL auto_increment,
	event_userid  int(10) unsigned NOT NULL default '0',
	event_cat  int(10) unsigned NOT NULL default '0',
	PRIMARY KEY  (event_subid)
	) TYPE=MyISAM;");
	
$eplug_upgrade_done = EC_LAN_108;
	
	
	
	
	
?>