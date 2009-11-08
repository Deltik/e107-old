<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Administration Area - User classes
 *
 * $Source: /cvs_backup/e107_0.8/e107_plugins/log/e_meta.php,v $
 * $Revision: 1.5 $
 * $Date: 2009-11-08 19:46:44 $
 * $Author: e107steved $
 *
*/
if (!defined('e107_INIT')) { exit; }

if (isset($pref['statActivate']) && $pref['statActivate'])
{
	if(!$pref['statCountAdmin'] && ADMIN)
	{
		/* don't count admin visits */
		return;
	}
	// Page to log here
	require_once(e_PLUGIN.'log/consolidate.php');
	$err_flag = '';
	if (defined("ERR_PAGE_ACTIVE"))
	{	// We've got an error - set a flag to log it
		$err_flag = "&err_direct=".ERR_PAGE_ACTIVE;
		if (is_numeric(e_QUERY)) $err_flag .= '/'.substr(e_QUERY,0,10);		// This should pick up the error code - and limit numeric length to upset the malicious
		$err_flag .= "&err_referer=".$_SERVER['HTTP_REFERER'];
	}
	$logJS = "
function rstr2b64(input)
{
	var b64pad  = \"=\"; /* base-64 pad character. \"=\" for strict RFC compliance   */
	var tab = \"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/\";
	var output = \"\";
	var len = input.length;
	for(var i = 0; i < len; i += 3)
	{
		var triplet = (input.charCodeAt(i) << 16)
                | (i + 1 < len ? input.charCodeAt(i+1) << 8 : 0)
                | (i + 2 < len ? input.charCodeAt(i+2)      : 0);
		for(var j = 0; j < 4; j++)
		{
			if(i * 8 + j * 6 > input.length * 8) output += b64pad;
			else output += tab.charAt((triplet >>> 6*(3-j)) & 0x3F);
		}
	}
	return output;
}
var ref=\"\"+escape(top.document.referrer);
var colord = window.screen.colorDepth;
var res = window.screen.width + \"x\" + window.screen.height;
var logString = 'referer=' + ref + '&colour=' + colord + '&eself=' + document.location + '&res=' + res + '".$err_flag."';
logString = rstr2b64(logString);
document.write( '<link rel=\"stylesheet\" type=\"text/html\" href=\"".e_PLUGIN_ABS."log/log.php?'+logString + '\">' );
";
	$e107 = e107::getInstance();
	$e107->getJs()->headerInline($logJS, 5);

}


?>