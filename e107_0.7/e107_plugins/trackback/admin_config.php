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
require_once("../../class2.php");
if (!isset($pref['plug_installed']['trackback']) || !getperms("P")) {
	header("location:".e_BASE."index.php");
	 exit ;
}

include_lan(e_PLUGIN."trackback/languages/".e_LANGUAGE.".php");
	
require_once(e_ADMIN."auth.php");
	
if (isset($_POST['updatesettings'])) 
{
  if ($pref['trackbackEnabled'] != $_POST['trackbackEnabled'])
  {
	$pref['trackbackEnabled'] = $_POST['trackbackEnabled'];
	$e107cache->clear("news.php");
  }
  $pref['trackbackString'] = $tp->toDB($_POST['trackbackString']);
  save_prefs();
  $message = TRACKBACK_L4;
}

	
if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}
	

$text = "
<div style='text-align:center'>
<form method='post' action='".e_SELF."'>
<table style='".ADMIN_WIDTH."' class='fborder'>
<tr>
<td style='width:50%' class='forumheader3'>".TRACKBACK_L7."</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='radio' name='trackbackEnabled' value='1'".($pref['trackbackEnabled'] ? " checked='checked'" : "")." /> ".TRACKBACK_L5."&nbsp;&nbsp;
<input type='radio' name='trackbackEnabled' value='0'".(!$pref['trackbackEnabled'] ? " checked='checked'" : "")." /> ".TRACKBACK_L6."
</td>
</tr>

<tr>
<td style='width:50%' class='forumheader3'>".TRACKBACK_L8."</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input  size='50' class='tbox' type='text' name='trackbackString' value='".$pref['trackbackString']."' />
</td>
</tr>

<td colspan='2' style='text-align:center' class='forumheader'>
<input class='button' type='submit' name='updatesettings' value='".TRACKBACK_L9."' />
</td>
</tr>

</table>
</form>
</div>
";



$ns->tablerender(TRACKBACK_L10, $text);
	
require_once(e_ADMIN."footer.php");
?>