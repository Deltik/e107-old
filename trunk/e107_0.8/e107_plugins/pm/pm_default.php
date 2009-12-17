<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *	Private messenger plugin - default preferences (used if no stored values)
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/pm/pm_default.php,v $
 * $Revision: 1.3 $
 * $Date: 2009-12-17 22:47:20 $
 * $Author: e107steved $
 */


/**
 *	e107 Private messenger plugin
 *
 *	default preferences (used if no stored values)
 *
 *	@package	e107_plugins
 *	@subpackage	pm
 *	@version 	$Id: pm_default.php,v 1.3 2009-12-17 22:47:20 e107steved Exp $;
 */

if (!defined('e107_INIT')) { exit; }

function pm_set_default_prefs()
{
	$ret = array(
		'title' 			=> 'PMLAN_PM',
		'animate' 			=> '1',
		'dropdown' 			=> '0',
		'read_timeout' 		=> '0',
		'unread_timeout'	=> '0',
		'popup'				=> '0',
		'popup_delay'		=> '',
		'perpage'			=> '10',
		'pm_class'			=> e_UC_MEMBER,
		'notify_class'		=>	e_UC_ADMIN,
		'receipt_class'		=> e_UC_MEMBER,
		'attach_class'		=>	e_UC_ADMIN,
		'attach_size'		=> 500,
		'sendall_class'		=>	e_UC_ADMIN,
		'multi_class'		=> e_UC_ADMIN,
		'allow_userclass'	=> '1'
	);
	return $ret;
}
?>