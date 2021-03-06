<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Copyright (C) 2001-2002 Steve Dunstan (jalist@e107.org)
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

global $sc_style, $content_shortcodes;

$sc_style['CONTENT_TOP_TABLE_ICON']['pre'] = "<td class='forumheader3' rowspan='3' style='width:5%; white-space:nowrap;'>";
$sc_style['CONTENT_TOP_TABLE_ICON']['post'] = "</td>";

$sc_style['CONTENT_TOP_TABLE_HEADING']['pre'] = "";
$sc_style['CONTENT_TOP_TABLE_HEADING']['post'] = "";

$sc_style['CONTENT_TOP_TABLE_AUTHOR']['pre'] = "<tr><td class='forumheader3' colspan='2'>".CONTENT_LAN_11." ";
$sc_style['CONTENT_TOP_TABLE_AUTHOR']['post'] = "</td></tr>";

$sc_style['CONTENT_TOP_TABLE_RATING']['pre'] = "<td class='fcaption' style='width:20%; white-space:nowrap; text-align:right;'>";
$sc_style['CONTENT_TOP_TABLE_RATING']['post'] = "</td>";

// ##### CONTENT TOP --------------------------------------------------
if(!isset($CONTENT_TOP_TABLE_START)){
	$CONTENT_TOP_TABLE_START = "";
}
if(!isset($CONTENT_TOP_TABLE)){
	$CONTENT_TOP_TABLE = "
	<table class='fborder' style='width:98%; text-align:left; margin-bottom:5px;'>
	<tr>
		{CONTENT_TOP_TABLE_ICON}
		<td class='fcaption'>{CONTENT_TOP_TABLE_HEADING}</td>
		{CONTENT_TOP_TABLE_RATING}
	</tr>
	{CONTENT_TOP_TABLE_AUTHOR}
	</table>\n";
}
if(!isset($CONTENT_TOP_TABLE_END)){
	$CONTENT_TOP_TABLE_END = "";
}
// ##### ----------------------------------------------------------------------

?>