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

if (!defined('e107_INIT')) { exit; }

$text = "Notify sends email notifications when e107 events occur.<br /><br />
For example, set 'IP banned for flooding site' to user class 'Admin' and all admins will be sent an email when your 
site is being flooded.<br /><br />
You can also, as another example, set 'News item posted by admin' to user class 'Members' and all your users will be 
sent news items you post to the site in an email.<br /><br />
If you would like the email notifications to be sent to an alternative email address - select the 'Email' option and 
enter in the email address in the field provided.";

$ns -> tablerender("Notify Help", $text);
?>