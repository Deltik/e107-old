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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/content/templates/default/content_search_template.php,v $
|     $Revision: 1.3 $
|     $Date: 2005-05-15 12:29:04 $
|     $Author: lisa_ $
+----------------------------------------------------------------------------+
*/
global $sc_style, $content_shortcodes;
global $CONTENT_SEARCH_TABLE_SELECT, $CONTENT_SEARCH_TABLE_ORDER, $CONTENT_SEARCH_TABLE_KEYWORD;
// ##### CONTENT SEARCH LIST --------------------------------------------------
$CONTENT_SEARCH_TABLE = "";
if(!$CONTENT_SEARCH_TABLE){
				$CONTENT_SEARCH_TABLE .= "
				<table class='content_table'>
					<tr>
						<td class='content_info' style='width:30%; white-space:nowrap;'>
							{CONTENT_SEARCH_TABLE_SELECT}
						</td>
						<td class='content_info' style='width:30%; white-space:nowrap;'>
							{CONTENT_SEARCH_TABLE_ORDER}
						</td>
						<td class='content_info' style='width:30%; white-space:nowrap;'>
							{CONTENT_SEARCH_TABLE_KEYWORD}
						</td>
					</tr>
				</table>";
}
// ##### ----------------------------------------------------------------------

?>