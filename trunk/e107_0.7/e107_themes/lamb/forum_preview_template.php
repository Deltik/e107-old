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
|     $Source: /cvs_backup/e107_0.7/e107_themes/lamb/forum_preview_template.php,v $
|     $Revision: 1.1 $
|     $Date: 2005-01-30 20:16:01 $
|     $Author: stevedunstan $
+----------------------------------------------------------------------------+
*/

$FORUM_PREVIEW = "<div style='text-align:center'>
<table style='width:100%' class='fborder'>
<tr>
<td colspan='2' class='nforumcaption2' style='vertical-align:top'>".LAN_323.
($action != "nt" ? "</td>" : " ( ".LAN_62.$tsubject." )</td>")."
<tr>
<td class='nforumreply' style='width:20%' style='vertical-align:top'><b>".$poster."</b></td>
<td class='nforumreply' style='width:80%'>
<div class='smallblacktext' style='text-align:right'>".IMAGE_post2." ".LAN_322.$postdate."</div>".$tpost."</td>
</tr>
</table>
</div>";
	
?>