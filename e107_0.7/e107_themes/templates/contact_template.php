<?php
// $Id: contact_template.php,v 1.4 2006-07-16 19:56:47 e107coders Exp $

if (!defined('e107_INIT')) { exit; }
if (!defined("USER_WIDTH")){ define("USER_WIDTH","width:97%"); }

if(!$CONTACT_INFO){
  $CONTACT_INFO = "
	<table style='".USER_WIDTH."' cellpadding='1' cellspacing='7'>
	<tr>
		<td>{SITECONTACTINFO}
		<br />
		</td>
	</tr>
	</table>";
}

// This must be defined outside $CONTACT_FORM so it can be turned off depending on the $pref
if(!$CONTACT_EMAIL_COPY)
{
	$CONTACT_EMAIL_COPY = "
	<tr><td>
	<input type='checkbox' name='email_copy'  value='1'  />
	".LANCONTACT_07."
	</td></tr>
	";
}

if(!$CONTACT_FORM){
  $CONTACT_FORM = "
	<form action='".e_SELF."' method='post' id='contactForm' >
	<table style='".USER_WIDTH."' cellpadding='1' cellspacing='7'>
	<tr><td>".LANCONTACT_03."<br />
	<input type='text' name='author_name' size='30' class='tbox' value='' />
	</td></tr>
	<tr><td>".LANCONTACT_04."<br />
	<input type='text' name='email_send' size='30' class='tbox' value='".USEREMAIL."' />
	</td></tr>
	<tr><td>
	".LANCONTACT_05."<br />
	<input type='text' name='subject' size='30' class='tbox' value='' />
	</td></tr>{$CONTACT_EMAIL_COPY}
	<tr><td>
    ".LANCONTACT_06."<br />
	<textarea cols='50' rows='10' name='body' class='tbox'></textarea>
	</td></tr>
	<tr><td>
	<input type='submit' name='send-contactus' value=\"".LANCONTACT_08."\" class='button' />
	</td></tr>
	</table>
	</form>";
}


?>