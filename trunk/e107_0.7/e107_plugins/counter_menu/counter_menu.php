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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/counter_menu/counter_menu.php,v $
|     $Revision: 1.5 $
|     $Date: 2005-02-07 12:52:39 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/
$text = "";
if ($pref['log_activate']) {
	$logfile = e_PLUGIN."log/logs/log_".date("z.Y", time()).".php";
	if(!is_readable($logfile)) {
		echo $logfile;
		$total = 1;
		$unique = 1;
		$siteTotal = 1;
		$siteUnique = 1;
	} else {
		require_once($logfile);
		$pageName = preg_replace("/(\?.*)|(\_.*)|(\.php)/", "", basename (e_SELF));
		$total = ($pageInfo[$pageName]['ttl'] ? $pageInfo[$pageName]['ttl'] : 0);
		$unique = ($pageInfo[$pageName]['unq'] ? $pageInfo[$pageName]['unq'] : 0);
		$totalever = ($pageInfo[$pageName]['ttlv'] ? $pageInfo[$pageName]['ttlv'] : 0);
		$uniqueever = ($pageInfo[$pageName]['unqv'] ? $pageInfo[$pageName]['unqv'] : 0);
	}
	$text = "<b>This page today</b><br />Total: $total, unique: $unique<br /><br />
	<b>This page ever</b><br />Total: $totalever, unique: $uniqueever<br /><br />
	<b>Site</b><br />Total: $siteTotal, unique: $siteUnique";
	$ns->tablerender(COUNTER_L1, $text, 'counter');
}

if (!$pref['log_activate'] && ADMIN) {
	$text .= "<br /><br /><span class='smalltext'>".COUNTER_L5."</span><br />
		<a href='".e_ADMIN."log.php'>".COUNTER_L6."</a>";
	 
	$ns->tablerender(COUNTER_L1, $text, 'counter');
}
	
?>