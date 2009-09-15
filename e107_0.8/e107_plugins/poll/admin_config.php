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
|     $Source: /cvs_backup/e107_0.8/e107_plugins/poll/admin_config.php,v $
|     $Revision: 1.8 $
|     $Date: 2009-07-10 14:25:22 $
|     $Author: e107coders $
+----------------------------------------------------------------------------+
*/
require_once("../../class2.php");
if (!is_object($tp)) $tp = new e_parse;
if (!getperms("P") || !plugInstalled('poll')) 
{
	header("location:".e_BASE."index.php");
	exit;
}
$e_sub_cat = 'poll';

include_lan(e_PLUGIN.'poll/languages/English_admin_poll.php');
require_once(e_ADMIN."auth.php");
require_once(e_PLUGIN."poll/poll_class.php");
require_once(e_HANDLER."form_handler.php");
require_once(e_HANDLER."userclass_class.php");

if(isset($_POST)) 
{
	$_POST = strip_if_magic($_POST);
}


$rs = new form;
$poll = new poll;

if (isset($_POST['reset']))
{
	unset($poll_id, $_POST['poll_title'], $_POST['poll_option'], $_POST['activate'], $_POST['multipleChoice'], $_POST['showResults'], $_POST['startday'], $_POST['startmonth'], $_POST['startyear'], $_POST['endday'], $_POST['endmonth'], $_POST['endyear']);
	define("RESET", TRUE);
} 
elseif (e_QUERY)
{
	list($action, $id) = explode(".", e_QUERY);
	define("POLLACTION", $action);
	define("POLLID", $id);
}
else
{
	define("POLLACTION", FALSE);
	define("POLLID", FALSE);
}


if ($action == "delete") 
{
	$message = $poll->delete_poll($id);
	unset($poll_id, $_POST['poll_title'], $_POST['poll_option'], $_POST['activate']);
}


if (isset($_POST['submit']))
{
	if($_POST['poll_title'])
	{
		$message = $poll -> submit_poll();
		unset($_POST['poll_title'], $_POST['poll_option'], $_POST['activate'], $_POST['poll_comment']);
	}
	else
	{
		$message = POLLAN_46;
	}
}


if (POLLACTION == "edit" && !$_POST['preview'] && !$_POST['submit'])
{

	if ($sql->db_Select("polls", "*", "poll_id=".POLLID)) 
	{
		$row = $sql->db_Fetch();
		extract($row);

		$tmpArray = explode(chr(1), $poll_options);

		foreach($tmpArray as $option)
		{
			$_POST['poll_option'][] = $option;
		}

		$_POST['activate'] = $poll_active;
		$_POST['option_count'] = count($_POST['poll_option']);
		$_POST['poll_title'] = $poll_title;
		$_POST['poll_comment'] = $poll_comment;

		if ($poll_start_datestamp)
		{
			$tmp = getdate($poll_start_datestamp);
			$_POST['startmonth'] = $tmp['mon'];
			$_POST['startday'] = $tmp['mday'];
			$_POST['startyear'] = $tmp['year'];
		}
		if ($poll_end_datestamp)
		{
			$tmp = getdate($poll_end_datestamp);
			$_POST['endmonth'] = $tmp['mon'];
			$_POST['endday'] = $tmp['mday'];
			$_POST['endyear'] = $tmp['year'];
		}

		$_POST['multipleChoice'] = $poll_allow_multiple;
		$_POST['showResults'] = $poll_result_type;
		// Can't have everyone voting if tracking method is user ID
		$_POST['pollUserclass'] = (($poll_vote_userclass == e_UC_PUBLIC) && $poll_storage_method == 2) ? e_UC_MEMBER : $poll_vote_userclass;
		$_POST['storageMethod'] = $poll_storage_method;
	}
}

if (isset($_POST['preview']))
{
	// Can't have everyone voting if tracking method is user ID
	if (($_POST['pollUserclass'] == e_UC_PUBLIC) && ($_POST['storageMethod'] == 2)) $_POST['pollUserclass'] = e_UC_MEMBER;
	$poll->render_poll($_POST, "preview");
}

if (isset($message))
{
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$text = "<div style='text-align:center'><div style='padding : 1px; ".ADMIN_WIDTH."; height : 200px; overflow : auto; margin-left: auto; margin-right: auto;'>
	<form action='".e_SELF."' method='post' id='del_poll'>";

if ($poll_total = $sql->db_Select("polls", "*", "poll_type=1")) 
{
	$text .= "<table class='adminlist' cellpadding='0' cellspacing='0'>
        	<colgroup span='3'>
        		<col style='width:5%' />
        		<col style='width:75%' />
				<col style='width:20%' />
        	</colgroup>
			<thead>

		<tr>
		<th>ID
		<input type='hidden' name='del_poll_confirm' id='del_poll_confirm' value='1' />
		</th>
		<th>".POLLAN_3."</th>
		<th class='center'>".POLLAN_4."</th>
		</tr>
		</thead>
		<tbody>";
	while ($row = $sql->db_Fetch()) {
		extract($row);
		$text .= "<tr>
			<td style='width:5%' >$poll_id</td>
			<td style='width:75%' class='forumheader3'>".$tp -> toHTML($poll_title, TRUE,"no_hook, emotes_off, defs")."</td>
			<td style='width:20%; text-align:center' class='forumheader3'><div>". $rs->form_button("button", "main_edit_{$poll_id}", POLLAN_5, "onclick=\"document.location='".e_SELF."?edit.$poll_id'\""). $rs->form_button("submit", "main_delete_{$poll_id}", POLLAN_6, "onclick=\"confirm_($poll_id)\"")."
			</div></td>
			</tr>";
	}
	$text .= "</tbody></table>";
}
else 
{
  $text .= "<div style='text-align:center'>".POLLAN_7."</div>";
}
$text .= "</form></div></div>";
$ns->tablerender(POLLAN_1, $text);

$poll_total = $sql->db_Select("polls");

$text = $poll -> renderPollForm();

$ns->tablerender(POLLAN_2, $text);
require_once(e_ADMIN."footer.php");


function headerjs() 
{
	global $tp;
	$headerjs = "<script type=\"text/javascript\">
function confirm_(poll_id)
{
  var x=confirm(\"Delete this poll? [ID: \" + poll_id + \"]\");
  if (x)
  {
	document.getElementById('del_poll').action='".e_SELF."?delete.' + poll_id;
	document.getElementById('del_poll').submit();
  }
}
</script>";
	return $headerjs;
}
?>