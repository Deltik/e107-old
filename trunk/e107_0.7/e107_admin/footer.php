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
|     $Source: /cvs_backup/e107_0.7/e107_admin/footer.php,v $
|     $Revision: 1.3 $
|     $Date: 2004-09-28 12:52:27 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
if(!defined("e_HTTP")){ exit; }
@include(e_LANGUAGEDIR.e_LANGUAGE."/admin/lan_footer.php");
@include(e_LANGUAGEDIR."English/admin/lan_footer.php");

if(ADMIN == TRUE)
{
	if($pref['cachestatus'])
	{
		if(!$sql -> db_Select("tmp", "*", " tmp_ip='var_store' && tmp_time='1' "))     // var_store 1 == cache empty time
		{
			$sql -> db_Insert("tmp", "'var_store', 1, '0' ");
		}
		else
		{
			$row = $sql -> db_Fetch(); extract($row);
			if(($tmp_info+604800) < time())  // If cache not cleared in last 7 days, clear it.
			{
				require_once(e_HANDLER."cache_handler.php");
				$ec = new ecache;
				$ec -> clear();
				$sql -> db_Update("tmp", "tmp_info='".time()."' WHERE tmp_ip='var_store' AND tmp_time=1 ");
			}
		}
	}
}
parse_admin($ADMIN_FOOTER);
?>

</body>
</html>

<?php
$sql -> db_Close();
?>
