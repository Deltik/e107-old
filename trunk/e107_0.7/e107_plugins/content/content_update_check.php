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
|     $Source: /cvs_backup/e107_0.7/e107_plugins/content/content_update_check.php,v $
|     $Revision: 1.8 $
|     $Date: 2006-05-31 21:29:59 $
|     $Author: lisa_ $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$dbupdatep['content_07'] =  LAN_UPDATE_8." .617 content ".LAN_UPDATE_9." .7 content";
function update_content_07($type='') 
{
	global $sql, $mySQLdefaultdb;
	if($type == 'do')
	{
		if(!isset($_POST['updateall']))
		{	
			include_once(e_PLUGIN.'content/content_update.php');
		}
	}
	else
	{
		// FALSE = needed, TRUE = not needed.
		
		//if not installed, return FALSE = needed
		if(!$sql->db_Select("plugin", "plugin_version", "plugin_path = 'content'")){
			return FALSE; //needed
		}else{
			$row = $sql->db_Fetch();
			
			//if version < 1.22, return FALSE = needed
			if($row['plugin_version'] < 1.22){
				return FALSE; //needed
			}

			$newcontent = $sql -> db_Count("pcontent", "(*)", "");
			
			//if no rows in new table && no old content table exists, return FALSE = needed
			$exists = mysql_query("SELECT 1 FROM ".MPREFIX."content LIMIT 0");
			if($newcontent == 0 && !$exists){
				return FALSE; //needed
			}
			
			//if parent value is old style, return FALSE = needed
			if($newcontent > 0){
				if($thiscount = $sql -> db_Select("pcontent", "*", "ORDER BY content_id ", "mode=no_where" )){
					while($row = $sql -> db_Fetch()){
						if( strpos($row['content_parent'], ".") && substr($row['content_parent'],0,1) != "0"){
							//if item with old parent value exists, you need to upgrade to 1.1
							return FALSE; //needed
						}
					}
				}
			}

			//if added fields are not present, return FALSE = needed
			$field1 = $sql->db_Field("pcontent",19);
			$field2 = $sql->db_Field("pcontent",20);
			$field3 = $sql->db_Field("pcontent",21);
			if($field1 != "content_score" && $field2 != "content_meta" && $field3 != "content_layout"){
				return FALSE; //needed
			}

			//else if passing all above checks, return TRUE = not needed
			return TRUE;
		}
	}
}



?>
			