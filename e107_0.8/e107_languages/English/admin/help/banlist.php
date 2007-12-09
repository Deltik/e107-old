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
|     $Source: /cvs_backup/e107_0.8/e107_languages/English/admin/help/banlist.php,v $
|     $Revision: 1.3 $
|     $Date: 2007-12-09 16:42:23 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

$caption = "Banning users from your site";
if (e_QUERY) list($action,$junk) = explode('.',e_QUERY); else $action = 'list';

switch ($action)
{
case 'options' :
  $text = "This page sets the default behaviour for various types of ban.<br />
  If a message is specified, this will be shown to the user (where appropriate); otherwise they will most likely get a blank screen<br />
  The ban will persist for the time specified; after which it will be cleared next time they access the site.";
  break;
case 'edit' :
case 'add' :
$text = "You can ban users from your site at this screen.<br />
Either enter their full IP address or use a wildcard to ban a range of IP addresses. You can also enter an email address to stop a user registering as a member on your site.<br /><br />
<b>Banning by IP address:</b><br />
Entering the IP address 123.123.123.123 will stop the user with that address visiting your site.<br />
Entering the IP address 123.123.123.* will stop anyone in that IP range from visiting your site.<br /><br />
<b>Banning by email address</b><br />
Entering the email address foo@bar.com will stop anyone using that email address from registering as a member on your site.<br />
Entering the email address *@bar.com will stop anyone using that email domain from registering as a member on your site.<br /><br />
<b>Banning by user name</b><br />
This is done from the user administration page.<br /><br />";
  break;
case 'list' :
default :
$text = "This page shows a list of all IP addresses, hostnames and email addresses which are banned. 
(Banned users are shown on the user administration page)<br /><br />
<b>Automatic Bans</b><br />
E107 automatically bans individual IP addresses if they attempt to flood the site, as well as addresses with failed logins.<br />
These bans also appear in this list. You can select (on the options page) what to do for each type of ban.<br /><br />
<b>Removing a ban</b><br />
You can set an expiry period for each type of ban, in which case the entry is removed once the ban period expires. Otherwise the
ban remains until you remove it.<br />
You can modify the ban period from this page - times are calculated from now.";
}
$ns -> tablerender($caption, $text);
?>