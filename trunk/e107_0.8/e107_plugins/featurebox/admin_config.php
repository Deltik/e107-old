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
|     $Source: /cvs_backup/e107_0.8/e107_plugins/featurebox/admin_config.php,v $
|     $Revision: 1.5 $
|     $Date: 2008-12-10 22:41:39 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/
require_once("../../class2.php");
if (!getperms("P")) {
	header("location:".e_BASE."index.php");
	 exit;
}

require_once(e_ADMIN."auth.php");
require_once(e_HANDLER."userclass_class.php");
require_once(e_HANDLER."file_class.php");
$fl = new e_file;
$templatelist = $fl->get_files(e_PLUGIN."featurebox/templates/");

if (e_QUERY) 
{
	$qs = explode(".", e_QUERY);
	$action = varset($qs[0],FALSE);
	$id = intval(varset($qs[1],0));
}
else
{
	$action = FALSE;
	$id = FALSE;
}

if(isset($_POST['createFB']) || isset($_POST['updateFB']))
{
	if ($_POST['fb_title'] && $_POST['fb_text']) 
	{
		$fbInfo = array();
		$fbInfo['fb_title'] = $tp -> toDB($_POST['fb_title']);
		$fbInfo['fb_text'] = $tp -> toDB($_POST['fb_text']);
		$fbInfo['fb_mode'] = intval($_POST['fb_mode']);
		$fbInfo['fb_class'] = intval($_POST['fb_class']);
		$fbInfo['fb_rendertype'] = intval($_POST['fb_rendertype']);
		$fbInfo['fb_template'] = $tp -> toDB($_POST['fb_template']);
		if(isset($_POST['createFB']))
		{
			$sql->db_Insert("featurebox", $fbInfo);
			$admin_log->logArrayAll('FBLAN_01',$fbInfo);
			$message = FBLAN_15;
		}
		if(isset($_POST['updateFB']))
		{
			$sql->db_UpdateArray('featurebox',$fbInfo, 'WHERE `fb_id`='.intval($_POST['fb_id']));
			$admin_log->logArrayAll('FBLAN_02',$fbInfo);
			$message = FBLAN_16;
		}
	} 
	else 
	{
		$message = FBLAN_17;
	}
}


if (($action == "delete")  && $id)
{
	$sql->db_Delete("featurebox", "fb_id=".$id);
	$admin_log->log_event('FBLAN_03',$id,E_LOG_INFORMATIVE,'');
	$message = FBLAN_18;
}


if (isset($message)) 
{
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}


if($headline_total = $sql->db_Select("featurebox"))
{
	$nfArray = $sql -> db_getList();

	$text = "<div style='text-align:center'>
	<table class='fborder' style='".ADMIN_WIDTH.";'>
	<tr>
	<td class='forumheader' style='width: 5%; text-align: center;'>ID</td>
	<td class='forumheader' style='width: 50%;'>".FBLAN_07."</td>
	<td class='forumheader' style='width: 10%; text-align: center;'>".FBLAN_19."</td>
	</tr>\n";

	foreach($nfArray as $entry)
	{
		$text .= "
		<tr>
		<td class='forumheader' style='width: 5%; text-align: center;'>".$entry['fb_id']."</td>
		<td class='forumheader' style='width: 50%;'>".$entry['fb_title']."</td>
		<td class='forumheader' style='width: 10%; text-align: center;'>
		<a href='".e_SELF."?edit.".$entry['fb_id']."'>".FBLAN_20."</a> - <a href='".e_SELF."?delete.".$entry['fb_id']."'>".FBLAN_21."</a>
		</td>
		</tr>
		";
	}



	$text .= "</table>\n</div>";
}
else
{
	$text = FBLAN_05;
}
$ns->tablerender(FBLAN_06, $text);

if($action == "edit")
{
	if($sql->db_Select("featurebox", "*", "fb_id=".$id))
	{
		$row = $sql->db_Fetch();
		extract($row);
	}
}
else
{
	unset($fb_name, $fb_text, $fb_mode, $fb_class);
}

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."'>\n
<table style='".ADMIN_WIDTH."' class='fborder'>

<tr>
<td style='width:50%' class='forumheader3'>".FBLAN_07."</td>
<td style='width:50%; text-align: left;' class='forumheader3'>
<input class='tbox' type='text' name='fb_title' style='width: 80%' value='{$fb_title}' maxlength='200' />
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>".FBLAN_08."</td>
<td style='width:50%; text-align: left;' class='forumheader3'>
<textarea class='tbox' name='fb_text' style='width: 90%'  rows='6'>{$fb_text}</textarea>
</td>
</tr>
	 
<tr>
<td style='width:50%' class='forumheader3'>".FBLAN_09."</td>
<td style='width:50%; text-align: left;' class='forumheader3'>
".r_userclass("fb_class", $fb_class, 'off', "public, guests, nobody, member, admin, classes")."
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>".FBLAN_12."</td>
<td style='width:50%; text-align: left;' class='forumheader3'>
<input type='radio' name='fb_mode' value='0'".(!$fb_mode ? " checked='checked'" : "")." /> ".FBLAN_13."&nbsp;<br />
<input type='radio' name='fb_mode' value='1'".($fb_mode == 1 ? " checked='checked'" : "")." /> ".FBLAN_14."
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>".FBLAN_22."</td>
<td style='width:50%; text-align: left;' class='forumheader3'>
<input type='radio' name='fb_rendertype' value='0'".(!$fb_rendertype ? " checked='checked'" : "")." /> ".FBLAN_23."&nbsp;<br />
<input type='radio' name='fb_rendertype' value='1'".($fb_rendertype == 1 ? " checked='checked'" : "")." /> ".FBLAN_24."
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>".FBLAN_25."<br /><span class='smalltext'>".FBLAN_26."</span></td>
<td style='width:50%; text-align: left;' class='forumheader3'>
<select class='tbox' name='fb_template'>
";

foreach($templatelist as $value)
{
	$file = str_replace(".php", "", $value['fname']);
	$text .= "<option".($file == $fb_template ? " selected='selected'" : "").">$file</option>\n";
}

$text .= "</select>
</td>
</tr>

<tr style='vertical-align:top'>
<td colspan='2' style='text-align:center' class='forumheader'>
<input class='button' type='submit' name='".($action == "edit" ? "updateFB" : "createFB")."' value='".($action == "edit" ? FBLAN_11 : FBLAN_10)."' />
</td>
</tr>
	 
</table>
".($action == "edit" ? "<input type='hidden' name='fb_id' value='{$fb_id}' />" : "")."
</form>
</div>";

$caption = ($action == "edit" ? FBLAN_11 : FBLAN_10);

$ns->tablerender($caption, $text);

require_once(e_ADMIN."footer.php");
?>