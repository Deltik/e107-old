<?php
/*
+---------------------------------------------------------------+
|        e107 website system
|        /admin/emoticon_conf.php
|
|        �Steve Dunstan 2001-2002
|        http://e107.org
|        jalist@e107.org
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
require_once("../class2.php");
if (!getperms("F")) {
	header("location:".e_BASE."index.php");
	exit;
}
$e_sub_cat = 'emoticon';

if (isset($_POST['active']))
{
	$pref['smiley_activate'] = $_POST['smiley_activate'];
	save_prefs();
}

require_once("auth.php");


/* get packs */

require_once(e_HANDLER."file_class.php");
$fl = new e_file;
$emote = new emotec;

foreach($_POST as $key => $value)
{
	if(strstr($key, "subPack_"))
	{
		$subpack = str_replace("subPack_", "", $key);
		$emote -> emoteConf($subpack);
		break;
	}

	if(strstr($key, "defPack_"))
	{
		$pref['emotepack'] = str_replace("defPack_", "", $key);
		save_prefs();
		break;
	}
}


$emote -> listPacks();




class emotec
{

	var $packArray;

	function emotec()
	{
		/* constructor */
		global $fl;
		$this -> packArray = $fl -> get_dirs(e_IMAGE."emotes");

		if(isset($_POST['sub_conf']))
		{
			$this -> saveConf();
		}

		



	}

	function listPacks()
	{

		global $ns, $fl, $pref, $sql;


		foreach($this -> packArray as $key => $value)
		{
			if(!$sql -> db_Select("core", "*", "e107_name='emote_".$value."' "))
			{
				/* pack not installed ... */
				if(file_exists(e_IMAGE."emotes/".$value."/emoteconf.php"))
				{
					echo "<b>.conf file found</b>: installing '".$value."'<br />";
					include(e_IMAGE."emotes/".$value."/emoteconf.php");
					$sql->db_Insert("core", "'emote_".$value."', '$_emoteconf' ");
				}
				else if(file_exists(e_IMAGE."emotes/".$value."/_phpBB-design.com.pak"))
				{
					echo "<b>.pak file found</b>: installing '".$value."'<br />";
					$filename = e_IMAGE."emotes/".$value."/_phpBB-design.com.pak";
					$pakconf = file ($filename);
					$confArray = array();
					foreach($pakconf as $pakline)
					{
						$tmp = explode("=+:", $pakline);
						$confIC = str_replace(".", "!", $tmp[0]);
						$confArray[$confIC] = trim(chop($tmp[2]));
					}
					$tmp = addslashes(serialize($confArray));
					$sql->db_Insert("core", "'emote_".$value."', '$tmp' ");
				}
				else if(file_exists(e_IMAGE."emotes/".$value."/phpBB-design.com.pak"))
				{
					echo "<b>.pak file found</b>: installing '".$value."'<br />";
					$filename = e_IMAGE."emotes/".$value."/phpBB-design.com.pak";
					$pakconf = file ($filename);
					$confArray = array();
					foreach($pakconf as $pakline)
					{
						$tmp = explode("=+:", $pakline);
						$confIC = str_replace(".", "!", $tmp[0]);
						$confArray[$confIC] = trim(chop($tmp[2]));
					}
					$tmp = addslashes(serialize($confArray));
					$sql->db_Insert("core", "'emote_".$value."', '$tmp' ");
				}
				else
				{
					echo "<b>No config found - manually configuration required</b> ".$variable." <br />";
				}
			}
		}






		$text = "<div style='text-align:center'>
		<form method='post' action='".e_SELF."'>
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
		<td style='width:30%' class='forumheader3'>".EMOLAN_4.": </td>
		<td style='width:70%' class='forumheader3'>".($pref['smiley_activate'] ? "<input type='checkbox' name='smiley_activate' value='1'  checked='checked' />" : "<input type='checkbox' name='smiley_activate' value='1' />")."</td>
		</tr>

		<tr>
		<td colspan='2' style='text-align:center' class='forumheader'>
		<input class='button' type='submit' name='active' value='".LAN_UPDATE."' />
		</td>
		</tr>
		</table>
		</form>
		</div>
		";

		$ns -> tablerender(EMOLAN_1, $text);


		$text = "
		<form method='post' action='".e_SELF."'>
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
		<td class='forumheader' style='width: 20%;'>".EMOLAN_2."</td>
		<td class='forumheader' style='width: 50%;'>".EMOLAN_3."</td>
		<td class='forumheader' style='width: 10%; text-align: center;'>".EMOLAN_8."</td>
		<td class='forumheader' style='width: 20%;'>".EMOLAN_9."</td>
		";

		$reject = array('^\.$','^\.\.$','^\/$','^CVS$','thumbs\.db','.*\._$', 'emoteconf*');
		foreach($this -> packArray as $pack)
		{
			$emoteArray = $fl -> get_files(e_IMAGE."emotes/".$pack, "", $reject);

			$text .= "
			<tr>
			<td class='forumheader' style='width: 20%;'>$pack</td>
			<td class='forumheader' style='width: 20%;'>
			";

			foreach($emoteArray as $emote)
			{
				$text .= "<img src='".$emote['path']."/".$emote['fname']."' alt='' /> ";
			}
			
			$text .= "</td>
			<td class='forumheader3' style='width: 10%; text-align: center;'>".($pref['emotepack'] == $pack ? EMOLAN_10 : "<input class='button' type='submit' name='defPack_".$pack."' value='".EMOLAN_11." />")."</td>
			<td class='forumheader3' style='width: 20%; text-align: center;'><input class='button' type='submit' name='subPack_".$pack."' value='".EMOLAN_12."' /></td>
			</tr>
			";
		}

		$text .= "
		</table>
		</form>
		";
		$ns -> tablerender(EMOLAN_13, $text);
	}

	function emoteConf($packID)
	{

		global $ns, $fl, $sql, $pref, $sysprefs;
		$corea = "emote_".$packID;

		$emotecode = $sysprefs -> getArray($corea);

		$reject = array('^\.$','^\.\.$','^\/$','^CVS$','thumbs\.db','.*\._$', 'emoteconf*', 'phpBB-design.com_banner*', 'readme.txt', 'phpBB-design.com.pak', '_phpBB-design.com.pak');
		$emoteArray = $fl -> get_files(e_IMAGE."emotes/".$packID, "", $reject);

		$text = "
		<form method='post' action='".e_SELF."'>
		<table style='".ADMIN_WIDTH."' class='fborder'>
		<tr>
		<td class='forumheader' style='width: 20%;'>".EMOLAN_2."</td>
		<td class='forumheader' style='width: 20%; text-align: center;'>".EMOLAN_5."</td>
		<td class='forumheader' style='width: 60%;'>".EMOLAN_6." <span class='smalltext'>( ".EMOLAN_7." )</a></td>
		</tr>
		";


		foreach($emoteArray as $emote)
		{
			$ename = $emote['fname'];
			$evalue = str_replace(".", "!", $ename);

			$text .= "
			<tr>
			<td class='forumheader3' style='width: 20%;'>".$ename."</td>
			<td class='forumheader3' style='width: 20%; text-align: center;'><img src='".$emote['path']."/".$ename."' alt='' /></td>
			<td class='forumheader3' style='width: 60%;'><input style='width: 80%' class='tbox' type='text' name='$ename' value='".$emotecode[$evalue]."' maxlength='200' /></td>
			</tr>
			";	
		}

		$text .= "
		<tr>
		<td style='text-align: center;' colspan='3' class='forumheader'><input class='button' type='submit' name='sub_conf' value='".EMOLAN_14."' /></td>
		</tr>
		
		</table>
		<input type='hidden' name='packID' value='$packID' />
		</form>";
		$ns -> tablerender(EMOLAN_15.": '".$packID."'", $text);

	}

	function saveConf()
	{
		global $ns, $sql;

		$packID = $_POST['packID'];
		unset($_POST['sub_conf'], $_POST['packID']);
		$tmp = addslashes(serialize($_POST));

		if(!$sql->db_Update("core", "e107_value='$tmp' WHERE e107_name='emote_".$packID."' "))
		{
			$sql->db_Insert("core", "'emote_".$packID."', '$tmp' ");
		}
		$ns -> tablerender("", EMOLAN_16);

	}

}







require_once("footer.php");
?>