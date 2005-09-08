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
|     $Source: /cvs_backup/e107_0.7/e107_handlers/debug_handler.php,v $
|     $Revision: 1.15 $
|     $Date: 2005-09-08 18:39:42 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/

class e107_debug {

	var $debug_level = 1;
	//
	// DEBUG SHORTCUTS
	//
	var $aDebugShortcuts = array(
		'all'		 	=> 255,     // all basics
		'basic'			=> 255,     // all basics
		'b'				=> 255,     // all basics
		'depreciated'	=> 32766,   // show if code is using depreciated functions
		'showsql'		=> 2,       // sql basics
		'counts'		=> 4,       // traffic counters
		'detail'		=> 32767,   // all details
		'd' 			=> 32767,   // all details
		'time' 			=> 256,     // time details
		'sql' 			=> 512,     // sql details
		'warn'			=> 1,       // just warnings, parse errrors, etc
		'notice'		=> 32768,   // you REALLY don't want all this, do you?
		'everything' 	=> 65535,
		'bbsc' 			=> 65536,
		'paths' 		=> 131072,
		'sc'			=> 5		// Shortcode paths only.
	);

	function e107_debug() {
		if (preg_match('/debug=(.*)/', e_MENU, $debug_param) || isset($_COOKIE['e107_debug_level'])) {
			if(isset($_COOKIE['e107_debug_level'])) {
				$dVal = substr($_COOKIE['e107_debug_level'],6);
			}
			if(preg_match('/debug=(.*)/', e_MENU)) {
				$dVal = $debug_param[1];
			}
			if(substr($dVal, -6) == ',stick')
			{
				$dVal = substr($dVal, 0, -6);
				cookie('e107_debug_level', 'level='.$dVal, time() + 86400);
			}
			if($dVal == 'unstick') {
				cookie('e107_debug_level', '', time() - 3600);
			}

			if (isset($this->aDebugShortcuts[$dVal])) {
				$this->debug_level = $this->aDebugShortcuts[$dVal];
			} else {
				$this->debug_level = $dVal;
			}
		}
	}

	function set_error_reporting() {
	}
}

if (strstr(e_MENU, "debug") || isset($_COOKIE['e107_debug_level'])) {
	$e107_debug = new e107_debug;
	require_once(e_HANDLER.'db_debug_class.php');
	$db_debug = new e107_db_debug;
	$e107_debug->set_error_reporting();
	$e107_debug_level = $e107_debug->debug_level;
	define('E107_DEBUG_LEVEL', $e107_debug_level);
} else {
	define('E107_DEBUG_LEVEL', 0);
}

// Basic levels
define('E107_DBG_BASIC',		(E107_DEBUG_LEVEL & 1));       // basics: worst php errors, sql errors, etc
define('E107_DBG_SQLQUERIES',	(E107_DEBUG_LEVEL & 2));       // display all sql queries
define('E107_DBG_TRAFFIC',		(E107_DEBUG_LEVEL & 4));       // display traffic counters
define('E107_DBG_FILLIN8',		(E107_DEBUG_LEVEL & 8));       // fill in what it is
define('E107_DBG_FILLIN16',		(E107_DEBUG_LEVEL & 16));      // fill in what it is
define('E107_DBG_FILLIN32',		(E107_DEBUG_LEVEL & 32));      // fill in what it is
define('E107_DBG_FILLIN64',		(E107_DEBUG_LEVEL & 64));      // fill in what it is
define('E107_DBG_FILLIN128',	(E107_DEBUG_LEVEL & 128));     // fill in what it is

// Gory detail levels
define('E107_DBG_TIMEDETAILS',	(E107_DEBUG_LEVEL & 256));     // detailed time profile
define('E107_DBG_SQLDETAILS',	(E107_DEBUG_LEVEL & 512));     // detailed sql analysis
define('E107_DBG_FILLIN1024',	(E107_DEBUG_LEVEL & 1024));    // fill in what it is
define('E107_DBG_FILLIN2048',	(E107_DEBUG_LEVEL & 2048));    // fill in what it is
define('E107_DBG_DEPRECIATED',	(E107_DEBUG_LEVEL & 32766));   // show used depricated funcs
define('E107_DBG_ALLERRORS',	(E107_DEBUG_LEVEL & 32768));   // show ALL errors//...
define('E107_DBG_BBSC',			(E107_DEBUG_LEVEL & 65536));   // BBCode / Shortcode
define('E107_DBG_PATH',			(E107_DEBUG_LEVEL & 131072));

?>