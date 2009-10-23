<?php
/*
* e107 website system
*
* Copyright ( c ) 2001-2008 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Check for admin events being triggered
*
* $Source: /cvs_backup/e107_0.8/e107_plugins/forum/e_admin_events.php,v $
* $Revision: 1.2 $
* $Date: 2008-12-03 17:21:15 $
* $Author: mcfly_e107 $
*
*/
function plugin_forum_admin_events($type, $parms)
{
	//We currently only care about system cache, so just return if it's not
	if(!isset($parms['syscache']) || !$parms['syscache']) { return ''; }
	switch($type)
	{
		case 'cache_clear':
			$which = varset($parms['cachetag']);
			if('nomd5_classtree' == $which)
			{
				return 'plugin_forum_admin_events_clear_moderators';
			}
			break;
	}
}

//Called if classtree cache is cleared.  Meaning we'll need to rebuild moderator cache
function plugin_forum_admin_events_clear_moderators()
{
	$e107 = e107::getInstance();
	$e107->ecache->clear_sys('nomd5_forum_moderators');
}

?>