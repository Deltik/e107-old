<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Steve Dunstan 2001-2002
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

function alt_auth_get_authlist()
{
	$authlist = array('e107');
	$handle   = opendir(e_PLUGIN.'alt_auth');
	
	while ($file = readdir($handle))
	{
		if (preg_match('/^(.*)_auth\.php/', $file, $match))  $authlist[] = $match[1];
	}
	
	closedir($handle);
	
	return $authlist;
}

function alt_auth_adminmenu()
{
	$authlist = alt_auth_get_authlist();
	
	if (!defined('ALT_AUTH_ACTION'))  define('ALT_AUTH_ACTION', 'main');

	$var['main']['text'] = LAN_ALT_AUTH_02;
	$var['main']['link'] = e_PLUGIN.'alt_auth/alt_auth_conf.php';
	
	show_admin_menu(LAN_ALT_AUTH_01, ALT_AUTH_ACTION, $var);
	
	$var = array();
	
	foreach ($authlist as $a)
	{
		if ('e107' != $a)
		{
			$var[$a]['text'] = sprintf(LAN_ALT_AUTH_04, $a);
			$var[$a]['link'] = e_PLUGIN.'alt_auth/'.$a.'_conf.php';
		}
	}
	
	show_admin_menu(LAN_ALT_AUTH_03, ALT_AUTH_ACTION, $var);
}


#
#	Admin menus for the different pages
#
function alt_auth_conf_adminmenu()
{
	alt_auth_adminmenu();
}

function ldap_conf_adminmenu()
{
	alt_auth_adminmenu();
}

function otherdb_conf_adminmenu()
{
	alt_auth_adminmenu();
}