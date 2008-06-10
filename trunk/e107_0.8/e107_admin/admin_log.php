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
|     $Source: /cvs_backup/e107_0.8/e107_admin/admin_log.php,v $
|     $Revision: 1.15 $
|     $Date: 2008-06-10 21:43:48 $
|     $Author: e107steved $
|
| Preferences:
|	'sys_log_perpage' - number of events per page
|
|	'user_audit_opts'	- which user-related events to log
|	'user_audit_class'	- user class whose actions can be logged
|
|	'roll_log_days' (default 7) - number of days for which entries retained in rolling log
|	'roll_log_active' - set to '1' to enable
|
|
+----------------------------------------------------------------------------+
*/
require_once("../class2.php");
if (!getperms("S")) 
{
  header("location:".e_BASE."index.php");
  exit;
}

// Main language file should automatically be loaded


unset($qs);

require_once(e_ADMIN."auth.php");


if (isset($_POST['setoptions'])) 
{
  $pref['roll_log_active'] = intval($_POST['roll_log_active']);
  $pref['roll_log_days']   = intval($_POST['roll_log_days']);
  save_prefs();
  $message = RL_LAN_006 ; // "Options updated.";
}


if (isset($_POST['setcommonoptions'])) 
{
  $pref['sys_log_perpage'] = intval($_POST['sys_log_perpage']);
  save_prefs();
  $message = RL_LAN_006 ; // "Options updated.";
}



if (e_QUERY) 
{	// Must explode after calling auth.php
	$qs = explode(".", e_QUERY);
}

$action = varset($qs[0],'adminlog');

include_lan(e_LANGUAGEDIR.e_LANGUAGE.'/admin/lan_log_messages.php');



// Delete comments if appropriate
if (isset($_POST['deleteitems']) && ($action == 'comments'))
{
  $c_list = array();
  foreach ($_POST['del_item'] as $di)
  {
    if (intval($di) > 0) $c_list[] = '`comment_id`='.intval($di);
  }
  if ($count = $sql->db_Delete('comments',implode(' OR ',$c_list)))
  {
    $text = str_replace('--NUMBER--', $count,RL_LAN_112);
	$admin_log->log_event('COMMENT_01','ID: '.implode(',',$_POST['del_item']),E_LOG_INFORMATIVE,'');
  }
  else
  {
    $text = RL_LAN_113;
  }
	$ns -> tablerender(LAN_DELETE, "<div style='text-align:center'><b>".$text."</b></div>");
  unset($c_list);
}



// ****************** MAINTENANCE ******************
unset($back_count);
if (isset($_POST['deleteoldadmin']) && isset($_POST['rolllog_clearadmin']))
{
  $back_count = $_POST['rolllog_clearadmin'];
  $next_action = 'confdel';
}
elseif (isset($_POST['deleteoldaudit']) && isset($_POST['rolllog_clearaudit']))
{
  $back_count = $_POST['rolllog_clearaudit'];
  $next_action = 'auditdel';
}

if (isset($back_count) && isset($next_action))
{
  if (($back_count >= 1) && ($back_count <= 90))
  {
    $temp_date = getdate();
    $old_date = intval(mktime(0,0,0,$temp_date['mon'],$temp_date['mday']-$back_count,$temp_date['year']));
	$old_string = strftime("%d %B %Y",$old_date);
//	$message = "Back delete ".$back_count." days. Oldest date = ".$old_string;
	$action = $next_action;
	$qs[1] = $old_date;
	$qs[2] = $back_count;
  }
  else
    $message = RL_LAN_050;
}



if (!isset($admin_log)) $message .= "  Admin Log not valid";


// Actually delete back events - admin or user audit log
if (($action == "backdel") && isset($_POST['backdeltype']))
{
  if (isset($_POST['confirmdeleteold']))
  {
	$old_date = intval($qs[1]);
	$old_string = strftime("%d %B %Y",$old_date);
	$qry = "dblog_datestamp < ".$old_date;			// Same field for both logs
	switch ($_POST['backdeltype'])
	{
	  case 'confdel' :
	    $db_table = 'admin_log';
		$db_name = RL_LAN_052;
		$db_msg = 'LAN_ADMIN_LOG_002';
	    break;
	  case 'auditdel' :
	    $db_table = 'audit_log';
		$db_name = RL_LAN_053;
		$db_msg = 'LAN_ADMIN_LOG_003';
	    break;
	  default :
	    exit;				// Someone fooling around!
	}
//	$message = "Back delete, oldest date = {$old_string}  Query = {$qry}";
	if ($del_count = $sql -> db_Delete($db_table,$qry))
	{
  // Add in a log event
	  $message = $db_name.str_replace(array('--OLD--','--NUM--'),array($old_string,$del_count),RL_LAN_057);
	  $admin_log->log_event($db_msg,"db_Delete - earlier than {$old_string} (past {$qs[2]} days)<br />".$message.'<br />'.$db_table.' '.$qry, 4,'LOG_01');
	}
	else
	{
	  $message = RL_LAN_054." : ".$sql->mySQLresult;
	}
  }

  if (isset($_POST['confirmcancelold']))
  {
	$message = RL_LAN_056;
  }
  $action = "config";
  unset($qs[1]);
  unset($qs[2]);
}


// User audit prefs
if (isset($_POST['setauditoptions']))
{
  $message = RL_LAN_063;
  if (in_array((string)USER_AUDIT_LOGIN,$_POST['user_audit_opts']))
  {
	$_POST['user_audit_opts'][] = USER_AUDIT_LOGOUT;
  }
  $pref['user_audit_opts'] = implode(',',$_POST['user_audit_opts']);
  $pref['user_audit_class'] = intval($_POST['user_audit_class']);
  save_prefs();
}


if (varsettrue($message)) 
{
  $ns->tablerender("", "<div style='text-align:center'><b>$message</b></div>");
}



// Prompt to delete back events
if (($action == "confdel") || ($action == "auditdel"))
{
	$old_string = strftime("%d %B %Y",$qs[1]);
	$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."?backdel.{$qs[1]}.{$qs[2]}'>
	<input type='hidden' name='backdeltype' value='{$action}' />
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr>
		<td class='forumheader3' colspan='2' style='width:100%;vertical-align:top;rext-align:center;'><br /><strong>";
	$text .= (($action == "confdel") ? RL_LAN_047 : RL_LAN_065).$old_string." </strong><br /><br /></td>
	</tr>
	<tr><td style='text-align:center' class='fcaption'><input class='button' type='submit' name='confirmdeleteold' value='".RL_LAN_049."' /></td>
	<td style='text-align:center' class='fcaption'><input class='button' type='submit' name='confirmcancelold' value='".RL_LAN_055."' /></td></tr>
	</table></form></div>";
	
	$ns->tablerender("<div style='text-align:center'>".RL_LAN_051."</div>", $text);
}



// Arrays of options for the various logs - the $page_title array is used to determine the allowable values for $action ('options' is a special case)
$log_db_table = array('adminlog' => 'admin_log', 'auditlog' => 'audit_log', 'rolllog' => 'dblog', 'downlog' => 'download_requests', 'comments' => 'comments', 'online'=>'online');
$back_day_count = array('adminlog' => 30, 'auditlog' => 30, 'rolllog' => max(intval($pref['roll_log_days']),1), 'downlog' => 60, 'detailed' => 20, 'comments' => 30, 'online' => 30);
$page_title = array('adminlog' => RL_LAN_030, 'auditlog' => RL_LAN_062, 'rolllog' => RL_LAN_002, 'downlog' => RL_LAN_067, 'detailed' => RL_LAN_094, 'comments' => RL_LAN_099, 'online' => RL_LAN_120);



// Set all the defaults for the data filter
$start_enabled = FALSE;
$end_enabled = FALSE;
$start_time = 0;
$end_time = 0;
$user_filter = '';
$event_filter = '';
$pri_filter_cond = "xx";
$pri_filter_val  = "";
$sort_order  = "DESC";
$downloadid_filter = '';

$last_noted_time = 0;



// Maintain the log view filter across pages
$rl_cookiename = $pref['cookie_name']."_rl_admin";
if (isset($_POST['updatefilters']) || isset($_POST['clearfilters']))
{	// Need to put the filter values into the cookie
  if (!isset($_POST['clearfilters']))
  {	// Only update filter values from S_POST[] if 'clear filters' not active
  $start_time = $_POST['starttimedate'] + $_POST['starttimehours']*3600 + $_POST['starttimemins']*60;
  $start_enabled = isset($_POST['start_enabled']);
  if (isset($_POST['timelength']))
  {
    $end_time = intval($_POST['timelength'])*60 + $start_time;
  }
  else
  {
	$end_time   = $_POST['endtimedate'] + $_POST['endtimehours']*3600 + $_POST['endtimemins']*60;
  }
  $end_enabled = isset($_POST['end_enabled']);
  $user_filter = $_POST['roll_user_filter'];
  $event_filter = $_POST['roll_event_filter'];
  $pri_filter_cond = $_POST['roll_pri_cond'];
  $pri_filter_val  = $_POST['roll_pri_val'];
  $caller_filter   = $_POST['roll_caller_filter'];
  $ipaddress_filter = $_POST['roll_ipaddress_filter'];
  $downloadid_filter = $_POST['roll_downloadid_filter'];
  }
  $cookie_string = implode("|",array($start_time,$start_enabled,$end_time,$end_enabled,$user_filter,$event_filter,$pri_filter_cond,$pri_filter_val,$caller_filter,$ipaddress_filter,$downloadid_filter));
//  echo $cookie_string."<br />";
// Create session cookie to store values
  cookie($rl_cookiename,$cookie_string,0);    // Use session cookie
}
else
{
// Now try and get the filters from the cookie
  if (isset($_COOKIE[$rl_cookiename]))
    list($start_time,$start_enabled,$end_time,$end_enabled,$user_filter,$event_filter,$pri_filter_cond,$pri_filter_val, $caller_filter,$ipaddress_filter,$downloadid_filter) = explode("|",$_COOKIE[$rl_cookiename]);
  if (isset($qs[1]) && isset($qs[2]) && ($qs[1] == 'user') && ctype_digit($qs[2]) && (intval($qs[2]) > 0))
  {
	$user_filter = intval($qs[2]);
  }
}

$timelength = 5;
if ($start_time != 0 && $end_time != 0) $timelength = intval(($end_time - $start_time)/60);

function time_box($boxname, $this_time, $day_count, $inc_tomorrow = FALSE, $all_mins = FALSE)
{  // Generates boxes for date and time for today and the preceding days
  // Appends 'date', 'hours', 'mins' to the specified boxname
  
  $all_time = getdate();				// Date/time now
  $sel_time = getdate($this_time);		// Currently selected date/time
  $sel_day = mktime(0,0,0,$sel_time['mon'],$sel_time['mday'],$sel_time['year']);
  $today = mktime(0,0,0,$all_time['mon'],$all_time['mday'] + ($inc_tomorrow ? 1 : 0),$all_time['year']);
  
// Start with day
  $ret = "<select name='{$boxname}date' class='tbox'>\n";
// Stick an extra day on the end, plus tomorrow if the flag set
  for ($i = ($inc_tomorrow ? -2 : -1); $i <= $day_count; $i++)
  {
    $day_string = date("D d M",$today);
	$sel = ($today == $sel_day) ? " selected='selected'" : "";
    $ret.= "<option value='{$today}'{$sel}>{$day_string}</option>\n";
	$today -= 86400;	// Move to previous day
  }
  $ret .= "</select>\n";
  
// Hours
  $ret .= "&nbsp;<select name='{$boxname}hours' class='tbox'>\n";
  for ($i = 0; $i < 24; $i++)
  {
    $sel = ($sel_time['hours'] == $i) ? " selected='selected'" : "";
	$ret.= "<option value='{$i}'{$sel}>{$i}</option>\n";
  }
  $ret .= "</select>\n";

// Minutes
  $ret .= "&nbsp;<select name='{$boxname}mins' class='tbox'>\n";
  for ($i = 0; $i < 60; $i+= ($all_mins ? 1 : 5))
  {
    $sel = ($sel_time['minutes'] == $i) ? " selected='selected'" : "";
	$ret.= "<option value='{$i}'{$sel}>{$i}</option>\n";
  }
  $ret .= "</select>\n";

  return $ret;
}


if(!defined("USER_WIDTH")) { define("USER_WIDTH","width:97%"); }

//====================================================================
//			CONFIGURATION OPTIONS MENU
//====================================================================

if($action == "config")
{

// Common to all logs
	$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."?config'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	
	<tr>
		<td style='width:40%;vertical-align:top;' class='forumheader3'>".RL_LAN_044."<br /></td>
		<td style='width:60%;vertical-align:top;' class='forumheader3'>
			<select name='sys_log_perpage' class='tbox'>
			<option value='10' ".($pref['sys_log_perpage']=='10'?" selected='selected' ":"")." >10</option>
			<option value='20' ".($pref['sys_log_perpage']=='20'?" selected='selected' ":"")." >20</option>
			<option value='30' ".($pref['sys_log_perpage']=='30'?" selected='selected' ":"")." >30</option>
			<option value='40' ".($pref['sys_log_perpage']=='40'?" selected='selected' ":"")." >40</option>
			<option value='50' ".($pref['sys_log_perpage']=='50'?" selected='selected' ":"")." >50</option>
			</select>
		</td>
	</tr>

	<tr><td colspan='2'  style='text-align:center' class='fcaption'><input class='button' type='submit' name='setcommonoptions' value='".RL_LAN_010."' /></td></tr>
	</table></form></div><br />";
	$ns->tablerender("<div style='text-align:center'>".RL_LAN_064."</div>", $text);


function gen_log_delete($selectname)
{
  $values = array(90,60,30,21,20,14,10,7,6,5,4,3,2,1);
  $ret = "<select name='{$selectname}' class='tbox'>\n";
  $selected = " selected='selected'";		// Always select the first (highest) value
  foreach ($values as $v)
  {
    $ret .= "<option value='{$v}'{$selected}>{$v}</option>\n";
	$selected = '';
  }
  $ret .= "</select>\n";
  return $ret;
}


// Admin log options
//==================
	$text = "
	<form method='post' action='".e_SELF."?config'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr>
		<td style='width:40%;vertical-align:top;' class='forumheader3'>".RL_LAN_045." </td>
		<td style='width:60%;vertical-align:top;' class='forumheader3'>".gen_log_delete('rolllog_clearadmin').RL_LAN_046."
		</td>
	</tr>\n
	<tr><td colspan='2'  style='text-align:center' class='fcaption'>
		<input class='button' type='submit' name='deleteoldadmin' value='".RL_LAN_049."' />
		</td></tr>\n
	</table></form>\n<br />";

	$ns->tablerender("<div style='text-align:center'>".RL_LAN_048."</div>", $text);


// User Audit log options (for info)
//=======================
//	  define('USER_AUDIT_SIGNUP',11);				// User signed up
//	  define('USER_AUDIT_EMAILACK',12);				// User responded to registration email
//	  define('USER_AUDIT_LOGIN',13);				// User logged in
//	  define('USER_AUDIT_LOGOUT',14);				// User logged out
//	  define('USER_AUDIT_NEW_DN',15);				// User changed display name
//	  define('USER_AUDIT_NEW_PW',16);				// User changed password
//	  define('USER_AUDIT_NEW_EML',17);				// User changed email
//	  define('USER_AUDIT_NEW_SET',19);				// User changed other settings (intentional gap in numbering)

$audit_checkboxes = array(
	USER_AUDIT_SIGNUP 	=> RL_LAN_071,
	USER_AUDIT_EMAILACK => RL_LAN_072,
	USER_AUDIT_LOGIN 	=> RL_LAN_073,
//	USER_AUDIT_LOGOUT 	=> RL_LAN_074,			// Logout is lumped in with login
	USER_AUDIT_NEW_DN 	=> RL_LAN_075,
	USER_AUDIT_NEW_PW 	=> RL_LAN_076,
	USER_AUDIT_PW_RES 	=> RL_LAN_078,
	USER_AUDIT_NEW_EML 	=> RL_LAN_077,
	USER_AUDIT_NEW_SET 	=> RL_LAN_079,
	USER_AUDIT_ADD_ADMIN => RL_LAN_080
);


	if (!isset($e_userclass) && !is_object($e_userclass)) 
	{
	  require_once(e_HANDLER."userclass_class.php");
	  $e_userclass = new user_class;
	}

	$user_signup_opts = array_flip(explode(',',varset($pref['user_audit_opts'],'')));
	$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."?config'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr>
		<td style='width:40%;vertical-align:top;' class='forumheader3'>".RL_LAN_026."</td>
		<td style='width:60%;vertical-align:top;' class='forumheader3'>";


	$text .= "<select class='tbox' name='class_select'>\n";
	$text .= $e_userclass->vetted_tree('user_audit_class',array($e_userclass,'select'), varset($pref['user_audit_class'],''),'nobody,admin,member,classes');
	$text .= "</select>\n";
	$text .= "</td>
	</tr>

	<tr>
	<td style='width:40%;vertical-align:top;' class='forumheader3'>".RL_LAN_031."</td>
	<td style='width:60%;vertical-align:top;' class='forumheader3'>";
	$spacer = '';
	foreach ($audit_checkboxes as $k => $t)
	{
	  $text .= $spacer."
	  <input class='tbox' type='checkbox' name='user_audit_opts[]' value='{$k}' ".(isset($user_signup_opts[$k]) ? " checked='checked' ":"")." />".$t;
	  $spacer = '<br />';
	}
	$text .= "
	</td>
	</tr>
	
	<tr><td colspan='2'  style='text-align:center' class='fcaption'><input class='button' type='submit' name='setauditoptions' value='".RL_LAN_010."' /></td></tr>
	</table></form></div>
	<br />";
	$ns->tablerender("<div style='text-align:center'>".RL_LAN_007."</div>", $text);


// Audit Trail maintenance
	$text = "
	<form method='post' action='".e_SELF."?config'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr>
		<td style='width:40%;vertical-align:top;' class='forumheader3'>".RL_LAN_066." </td>
		<td style='width:60%;vertical-align:top;' class='forumheader3'>".gen_log_delete('rolllog_clearaudit').RL_LAN_046."
		</td>
	</tr>\n
	<tr><td colspan='2'  style='text-align:center' class='fcaption'>
		<input class='button' type='submit' name='deleteoldaudit' value='".RL_LAN_049."' />
		</td></tr>\n
	</table></form>\n<br />";

	$ns->tablerender("<div style='text-align:center'>".RL_LAN_003."</div>", $text);


// Rolling log options
//====================
	$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."?config'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<tr>
		<td style='width:40%;vertical-align:top;' class='forumheader3'>".RL_LAN_008."</td>
		<td style='width:60%;vertical-align:top;' class='forumheader3'>
		<input class='tbox' type='checkbox' name='roll_log_active' value='1' ".($pref['roll_log_active']==1?" checked='checked' ":"")." />
		</td>
	</tr>

	<tr>
		<td style='width:40%;vertical-align:top;' class='forumheader3'>".RL_LAN_009."</td>
		<td style='width:60%;vertical-align:top;' class='forumheader3'>
		   <input class='tbox' type='text' name='roll_log_days' size='10' value='".$pref['roll_log_days']."' maxlength='5' />
		</td>
	</tr>\n
	
	<tr><td colspan='2'  style='text-align:center' class='fcaption'>
	<input class='button' type='submit' name='setoptions' value='".RL_LAN_010."' /></td></tr>\n
	</table></form>\n</div>";
	$ns->tablerender("<div style='text-align:center'>".RL_LAN_011."</div>", $text);
	
}




//====================================================================
//					LOG VIEW MENU
//====================================================================
if (isset($page_title[$action]))
{
$from = intval(varset($qs[1], 0));				// First entry to display
$amount = max(varset($pref['sys_log_perpage'], 20),5);	// Number of entries per page

// Array decides which filters are active for each log. There are 4 columns total. All but 'datetimes' occupy 2. Must specify multiple of 4 columns - add 'blank' if necessary
$active_filters = array('adminlog' => array('datetimes'=>0,'ipfilter'=>0,'userfilter'=>0,'eventfilter'=>0,'priority'=>0),
						'auditlog' => array('datetimes'=>0,'ipfilter'=>0,'userfilter'=>0,'eventfilter'=>0,'blank'=>2),
						'rolllog'  => array('datetimes'=>0,'ipfilter'=>0,'userfilter'=>0,'eventfilter'=>0,'priority'=>0,'callerfilter'=>0,'blank'=>2),
						'downlog'  => array('datetimes'=>0,'ipfilter'=>0,'userfilter'=>0,'downloadidfilter'=>0,'blank'=>2),
						'detailed' => array('datestart'=>0, 'ipfilter'=>0,'userfilter'=>0,'eventfilter'=>0,'blank'=>2),
						'comments' => array('datetimes'=>1, 'ipfilter'=>0,'userfilter'=>0,'eventfilter'=>0,'blank'=>2),
						'online'   => array('ipfilter'=>0,'userfilter'=>0)
						);

// Arrays determine column widths, headings, displayed fields for each log
$col_fields = array('adminlog' => array('cf_datestring','dblog_type','dblog_ip','dblog_user_id','user_name','dblog_eventcode','dblog_title','dblog_remarks'),
					'auditlog' => array('cf_datestring','dblog_ip','dblog_user_id','dblog_user_name','dblog_eventcode','dblog_title','dblog_remarks'),
					'rolllog'  => array('cf_datestring','dblog_type','dblog_ip','dblog_user_id','dblog_user_name','dblog_eventcode','dblog_caller','dblog_title','dblog_remarks'),
					'downlog'  => array('cf_datestring','dblog_ip','dblog_user_id','user_name','download_request_download_id','download_name'),
					'detailed' => array('cf_microtime','cf_microtimediff','source','dblog_type','dblog_ip','dblog_user_id','user_name','dblog_eventcode','dblog_title','dblog_remarks'),
					'comments' => array('cf_datestring', 'comment_id', 'comment_pid', 'comment_item_id', 'comment_subject', 'comment_author_id', 'comment_author_name', 'comment_ip', 'comment_type', 'comment_comment', 'comment_blocked', 'comment_lock', 'del_check'),
					'online'   => array('cf_datestring', 'dblog_ip', 'dblog_user_id', 'user_name', 'online_location', 'online_pagecount', 'online_flag', 'online_active')
					);
$col_widths = array('adminlog' => array(18,4,14,7,15,8,14,20),		 // Date - Pri - IP - UID - User - Code - Event - Info
					'auditlog' => array(18,14,7,15,8,14,24),
					'rolllog'  => array(15,4,12,6,12,7,13,13,18),	 // Date - Pri - IP - UID - User - Code - Caller - Event - Info
					'downlog'  => array(18,14,7,15,8,38),
					'detailed' => array(10,8,6,4,14,6,17,7,17,21),
					'comments' => array(14,7,7,7,14,3,10,12,5,17,1,1,1),
					'online'   => array(18,15,7,14,32,6,4,4)
					);
$col_titles = array('adminlog' => array(RL_LAN_019,RL_LAN_032,RL_LAN_020,RL_LAN_104,RL_LAN_022,RL_LAN_023,RL_LAN_025,RL_LAN_033),
					'auditlog' => array(RL_LAN_019,RL_LAN_020,RL_LAN_104,RL_LAN_022,RL_LAN_023,RL_LAN_025,RL_LAN_033),
					'rolllog'  => array(RL_LAN_019,RL_LAN_032,RL_LAN_020,RL_LAN_104,RL_LAN_022,RL_LAN_023,RL_LAN_024,RL_LAN_025,RL_LAN_033),
					'downlog'  => array(RL_LAN_019,RL_LAN_020,RL_LAN_104,RL_LAN_022,RL_LAN_068,RL_LAN_069),
					'detailed' => array(RL_LAN_097,RL_LAN_096,RL_LAN_098,RL_LAN_032,RL_LAN_020,RL_LAN_104,RL_LAN_022,RL_LAN_023,RL_LAN_025,RL_LAN_033),
					'comments' => array(RL_LAN_019, RL_LAN_100, RL_LAN_101, RL_LAN_102, RL_LAN_103, RL_LAN_104, RL_LAN_105, RL_LAN_020, RL_LAN_106, RL_LAN_107, RL_LAN_108, RL_LAN_109, RL_LAN_110),
					'online'   => array(RL_LAN_019, RL_LAN_020,RL_LAN_021,RL_LAN_022,RL_LAN_116, RL_LAN_117, RL_LAN_118, RL_LAN_116)
					);

// For DB where the delete option is available, specifies the ID field
$delete_field = array(
					'comments' => 'comment_id'
					);

// Only need to define entries in this array if the base DB query is non-standard (i.e. different field names and/or joins)
$base_query = array(
		'downlog' => "SELECT SQL_CALC_FOUND_ROWS 
						dbl.download_request_id as dblog_id,
						dbl.download_request_userid as dblog_user_id,
						dbl.download_request_ip as dblog_ip,
						dbl.download_request_download_id,
						dbl.download_request_datestamp as dblog_datestamp,
						d.download_name,
						u.user_name 
					FROM #download_requests AS dbl 
					LEFT JOIN #user AS u ON dbl.download_request_userid=u.user_id 
					LEFT JOIN #download AS d ON dbl.download_request_download_id=d.download_id
				",
		'detailed' => "SELECT SQL_CALC_FOUND_ROWS cl.*, u.* FROM (
			SELECT dblog_datestamp + (dblog_microtime/1000000) AS dblog_time, dblog_user_id, dblog_eventcode, dblog_title, dblog_remarks, dblog_type, dblog_ip, 'roll' AS source FROM `#dblog`
			UNION
			SELECT dblog_datestamp + (dblog_microtime/1000000) AS dblog_time, dblog_user_id, dblog_eventcode, dblog_title, dblog_remarks, '-' AS dblog_type, dblog_ip, 'audit' AS source FROM `#audit_log`
			UNION
			SELECT dblog_datestamp + (dblog_microtime/1000000) AS dblog_time, dblog_user_id, dblog_eventcode, dblog_title, dblog_remarks, dblog_type, dblog_ip, 'admin' AS source FROM `#admin_log`) AS cl
			LEFT JOIN `#user` AS u ON cl.dblog_user_id=u.user_id ",
		'comments' => "SELECT SQL_CALC_FOUND_ROWS *, comment_datestamp AS dblog_datestamp FROM `#comments` AS c",
		'online'   => "SELECT SQL_CALC_FOUND_ROWS online_timestamp AS dblog_datestamp, 
						online_ip AS dblog_ip, 
						SUBSTRING_INDEX(online_user_id,'.',1) AS dblog_user_id, 
						SUBSTRING(online_user_id FROM LOCATE('.',online_user_id)+1) AS user_name, 
						`online_location`, `online_pagecount`, `online_flag`, `online_active`
						FROM `#online`"
					);

// The filters have to use the 'actual' db field names. So the following table sets the defaults and the exceptions which vary across the range of tables supported
$map_filters = array(
			'default' => array('datetimes' => '`dblog_datestamp`', 'ipfilter' => '`dblog_ip`', 'userfilter' => '`dblog_user_id`', 'eventfilter' => '`dblog_eventcode`'),
			'downlog' => array('datetimes' => '`download_request_datestamp`', 'ipfilter' => '`download_request_ip`', 'userfilter' => '`download_request_userid`'),
			'detailed' => array('datestart' => '`dblog_time`'),
			'comments'  => array('datetimes' => '`comment_datestamp`', 'ipfilter' => '`comment_ip`', 'eventfilter' => 'comment_type', 'userfilter' => '`comment_author_id`'),
			'online'	=> array('online_ip' => '`dblog_ip`', 'online_user_id' => '`dblog_user_id`')
			);

// Field to sort table on
$sort_fields = array(
			'default'	=> 'dblog_id',
			'detailed'	=> 'dblog_time',
			'comments' 	=> 'comment_datestamp',
			'online'	=> 'online_timestamp'
			);



// Check things
  if ($start_time >= $end_time)
  {  // Make end time beginning of tomorrow
    $tempdate = getdate();
	$end_time = mktime(0,0,0,$tempdate['mon'],$tempdate['mday']+1,$tempdate['year']); 	// Seems odd, but mktime will work this out OK
																						// (or so the manual says)
  }



// Now work out the query - only use those filters which are displayed
	$qry = '';
	$and_array = array();
	foreach ($active_filters[$action] as $fname=>$fpars)
	{
	  $filter_field = varset($map_filters[$action][$fname],$map_filters['default'][$fname]);
	  switch ($fname)
	  {
	    case 'datetimes' :
		  if ($start_enabled && ($start_time > 0)) $and_array[] = "{$filter_field} >= ".intval($start_time);
		  if ($end_enabled && ($end_time > 0)) $and_array[] = "{$filter_field} <= ".intval($end_time);
		  break;
	    case 'datestart' :
		  if ($start_time == 0)
		  {
			$end_time = time();
		    $start_time = $end_time - 300;		// Default to last 5 mins
		  }
		  $and_array[] = "{$filter_field} >= ".intval($start_time);
		  $and_array[] = "{$filter_field} <= ".intval($end_time);
		  break;
		case 'ipfilter' :
		  if ($ipaddress_filter != "") 
		  {
			if (substr($ipaddress_filter,-1) == '*')
			{  // Wildcard to handle - mySQL uses %
			  $and_array[] = "{$filter_field} LIKE '".substr($ipaddress_filter,0,-1)."%' ";
			}
			else
			{
			  $and_array[] = "{$filter_field}= '".$ipaddress_filter."' ";
			}
		  }
		  break;
		case 'userfilter' :
		  if ($user_filter != '') $and_array[] = "{$filter_field} = ".intval($user_filter);
		  break;
		case 'eventfilter' :
		  if ($event_filter != '')
		  {
			if (substr($event_filter,-1) == '*')
			{  // Wildcard to handle - mySQL uses %
			  $and_array[] = " {$filter_field} LIKE '".substr($event_filter,0,-1)."%' ";
			}
			else
			{
			  $and_array[] = "{$filter_field}= '".$event_filter."' ";
			}
		  }
		  break;
		case 'callerfilter' :
		  if ($caller_filter != '')
		  {
			if (substr($caller_filter,-1) == '*')
			{  // Wildcard to handle - mySQL uses %
			  $and_array[] = "dblog_caller LIKE '".substr($caller_filter,0,-1)."%' ";
			}
			else
			{
			  $and_array[] = "dblog_caller= '".$caller_filter."' ";
			}
		  }
		  break;
		case 'priority' :
		  if (($pri_filter_val != "") && ($pri_filter_cond != "") && ($pri_filter_cond != "xx"))
		  {
			switch ($pri_filter_cond)
			{
			  case "lt" : 
			    $and_array[] = "dblog_type <= '{$pri_filter_val}' ";
			    break;
			  case "eq" : 
			    $and_array[] = "dblog_type = '{$pri_filter_val}' ";
				break;
			  case "gt" : 
			    $and_array[] = "dblog_type >= '{$pri_filter_val}' ";
				break;
			}
		  }
		  break;
		case 'downloadidfilter' :
		  if ($downloadid_filter != '') $and_array[] = "download_request_download_id = ".intval($downloadid_filter);
		  break;
	  }
	}


	if (count($and_array)) $qry = " WHERE ".implode(' AND ',$and_array);


	$limit_clause = " LIMIT {$from}, {$amount} ";
	$sort_field = varset($sort_fields[$action],$sort_fields['default']);

	if (isset($base_query[$action]))
	{
	  $qry = $base_query[$action].$qry." ORDER BY {$sort_field} ".$sort_order;
	}
	else
	{
	  $qry = "SELECT SQL_CALC_FOUND_ROWS dbl.*,u.user_name FROM #".$log_db_table[$action]." AS dbl LEFT JOIN #user AS u ON dbl.dblog_user_id=u.user_id".$qry." ORDER BY {$sort_field} ".$sort_order;
	}

	$num_entry = 0;
	if ($sql->db_Select_gen($qry.$limit_clause))
	{
	  $num_entry = $sql->total_results;
	}
	if ($from > $num_entry) 
	{
	  $from = 0;		// We may be on a later page
	  $limit_clause = " LIMIT {$from}, {$amount} ";
	  $sql->db_Select_gen($qry.$limit_clause);		// Re-run query with new value of $from
	  $num_entry = $sql->total_results;
	}

// Start by putting up the filter boxes
	$text = "<div style='text-align:center'>
	<form method='post' action='".e_SELF."?{$action}.{$from}'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<colgroup>
	<col style = 'width:20%;vertical-align:top;' />
	<col style = 'width:30%;vertical-align:top;' />
	<col style = 'width:20%;vertical-align:top;' />
	<col style = 'width:30%;vertical-align:top;' />
	</colgroup>
	<tr><td colspan='4' class='fcaption'>".RL_LAN_012." </td></tr>";
	$filter_cols = 0;
	foreach ($active_filters[$action] as $fname=>$fpars)
	{
	  if ($filter_cols == 0) $text .= '<tr>';
	  switch ($fname)
	  {
	    case 'datetimes' :
		  $text .= "
			<td class='forumheader3'><input class='tbox' type='checkbox' name='start_enabled' value='1' ".($start_enabled==1?" checked='checked' ":"").
			"/>&nbsp;".RL_LAN_013."</td><td class='forumheader3'>".time_box("starttime",$start_time,$back_day_count[$action],FALSE)."</td>
			<td class='forumheader3'><input class='tbox' type='checkbox' name='end_enabled' value='1' ".($end_enabled==1?" checked='checked' ":"").
			"/>&nbsp;".RL_LAN_014."</td><td class='forumheader3'>".time_box("endtime",$end_time,$back_day_count[$action],TRUE).
			"</td>";
		  $filter_cols = 4;
		  break;
	    case 'datestart' :
		  $text .= "
			<td class='forumheader3'>".RL_LAN_013."</td><td class='forumheader3'>".time_box("starttime",$start_time,$back_day_count[$action],FALSE,TRUE)."</td>
		    <td class='forumheader3'>".RL_LAN_092."</td>
		    <td class='forumheader3'><select name='timelength' class='tbox'>\n";
//			for ($i = 1; $i <= 10; $i++)
			foreach (array(1,2,3,4,5,7,10,15,20,30) as $i)
			{
			  $selected = ($timelength == $i) ? " selected='selected'" : '';
			  $text .= "<option value={$i}{$selected}>{$i}</option>\n";
			}
			$text .= "</select>\n".RL_LAN_093."</td>";
		  $filter_cols = 4;
		  break;
		case 'priority' :
		  $text .= "		<td class='forumheader3'>".RL_LAN_058."</td>
		<td class='forumheader3'><select name='roll_pri_cond' class='tbox'>
			<option value='xx' ".($pri_filter_cond=='xx'?" selected='selected' ":"")." >&nbsp;</option>
			<option value='gt' ".($pri_filter_cond=='gt'?" selected='selected' ":"")." >&gt;=</option>
			<option value='eq' ".($pri_filter_cond=='eq'?" selected='selected' ":"")." >==</option>
			<option value='lt' ".($pri_filter_cond=='lt'?" selected='selected' ":"")." >&lt;=</option>
			</select>
			&nbsp;
			<input class='tbox' type='text' name='roll_pri_val' size='20' value='".$pri_filter_val."' maxlength='10' /></td>";
		  $filter_cols += 2;
		  break;
		case 'ipfilter' :
		  $text .= "<td class='forumheader3'>".RL_LAN_060."<br /><span class='smalltext'><em>".RL_LAN_061."</em></span></td>
			<td class='forumheader3'><input class='tbox' type='text' name='roll_ipaddress_filter' size='20' value='".$ipaddress_filter."' maxlength='20' /></td>";
		  $filter_cols += 2;
		  break;
		case 'userfilter' :
		  $text .= "<td class='forumheader3'>".RL_LAN_015."<br /><span class='smalltext'><em>".RL_LAN_016."</em></span></td>
			<td class='forumheader3'><input class='tbox' type='text' name='roll_user_filter' size='20' value='".$user_filter."' maxlength='10' /></td>";
		  $filter_cols += 2;
		  break;
		case 'eventfilter' :
		  $text .= "<td class='forumheader3'>".RL_LAN_029."<br /><span class='smalltext'><em>".RL_LAN_061."</em></span></td>
			<td class='forumheader3'><input class='tbox' type='text' name='roll_event_filter' size='20' value='".$event_filter."' maxlength='10' /></td>";
		  $filter_cols += 2;
		  break;
		case 'callerfilter' :
		  $text .= "<td class='forumheader3'>".RL_LAN_059."<br /><span class='smalltext'><em>".RL_LAN_061."</em></span></td>
			<td class='forumheader3'><input class='tbox' type='text' name='roll_caller_filter' size='40' value='".$caller_filter."' maxlength='40' /></td>";
		  $filter_cols += 2;
		  break;
		case 'downloadidfilter' :
		  $text .= "<td class='forumheader3'>".RL_LAN_090."</td>
			<td class='forumheader3'><input class='tbox' type='text' name='roll_downloadid_filter' size='20' value='".$downloadid_filter."' maxlength='10' /></td>";
		  $filter_cols += 2;
		  break;
		case 'blank' :	// Any number of blank cells
		  $text .= Str_repeat("<td class='forumheader3'>&nbsp;</td>",$fpars);
		  $filter_cols += $fpars;
		  break;
	  }
	  if ($filter_cols >= 4) { $text .= '</tr>'; $filter_cols = 0; }
	}

//	$text .= "<tr><td colspan='4'>Query = {$qry}<br />{$_COOKIE[$rl_cookiename]}</td></tr>";
	$text .= "
	<tr>
	<td colspan='1'  style='text-align:center' class='forumheader3'><input class='button' type='submit' name='clearfilters' value='".RL_LAN_114."' /></td>
	<td colspan='3'  style='text-align:center' class='forumheader3'><input class='button' type='submit' name='updatefilters' value='".RL_LAN_028."' /></td></tr>
	</table>
	</form>
	</div><br />";




// Next bit is the actual log display - the arrays define column widths, titles, fields etc for each log
	$column_count = count($col_widths[$action]);
	$text .= "<div style='text-align:center'>
	<form method='post' action='".e_SELF."?{$action}.{$from}'>
	<table class='fborder' style='".ADMIN_WIDTH."'>
	<colgroup>";
	foreach($col_widths[$action] as $i)
	{
	  $text .= "<col style='width:{$i}%;vertical-align:top;' />\n";
	}
	$text .= "</colgroup>\n";

	if ($num_entry == 0)
	{
	  $text .= "<tr><td colspan='{$column_count}'>".RL_LAN_017."</td></tr>";
	}
	else
	{// Start with header
	  $text .= '<tr>';
	  foreach ($col_titles[$action] as $ct)
	  {
	    $text .= "	<td class='forumheader'>{$ct}</td>";
	  }
	  $text .= "</tr>\n";
	

// Routine to handle the simple bbcode-like codes for log body text
function log_process($matches)
{
  switch ($matches[1])
  {
    case 'br' :
	  return '<br />';
	case 'link' :
	  $temp = substr($matches[2],1);
	  return "<a href='{$temp}'>{$temp}</a>";
	case 'test' :
	  return '----TEST----';
	default :
	  return $matches[0];			// No change
  }
}
// Now put up the events
	  $delete_button = FALSE;
	  while ($row = $sql->db_Fetch())
	  {
		$text .= '<tr>';
		foreach ($col_fields[$action] as $cf)
		{
		  switch ($cf)
		  {
		  case 'cf_datestring' :
			$val = date("d-m-y  H:i:s",$row['dblog_datestamp']);  
			break;
		  case 'cf_microtime' :
			$val = date("H:i:s",intval($row['dblog_time']) % 86400).'.'.str_pad(100000*round($row['dblog_time']-floor($row['dblog_time']),6),6,'0');  
			break;
		  case 'cf_microtimediff' :
		    $val = '&nbsp;';
		    if ($last_noted_time > 0)
			{
			  $val = number_format($last_noted_time - $row['dblog_time'],6,'.','');  
			}
			$last_noted_time = $row['dblog_time'];
			break;
		  case 'cf_eventcode' :
		    $val = 'ADMIN'.$row['dblog_eventcode'];
		    break;
		  case 'dblog_title' :		// Look up constants to give multi-language viewing
		    $val = trim($row['dblog_title']);
			if (defined($val)) $val = constant($val);
		    break;
		  case 'dblog_user_name' :
		    $val = $row['dblog_user_id'] ? $row['dblog_user_name'] : LAN_ANONYMOUS;
			break;
		  case 'user_name' :
		    $val = $row['dblog_user_id'] ? $row['user_name'] : LAN_ANONYMOUS;
			break;
		  case 'dblog_caller' :
		    $val = $row['dblog_caller'];
		    if ((strpos($val,'|') !== FALSE) && (strpos($val,'@') !== FALSE))
			{
			  list($file,$rest) = explode('|',$val);
			  list($routine,$rest) = explode('@',$rest);
			  $val = $file.'<br />Function: '.$routine.'<br />Line: '.$rest;
			}
		    break;
		  case 'dblog_remarks' :
		    // Look for pseudo-code for newlines, link insertion
		    $val = preg_replace_callback("#\[!(\w+?)(=.+?){0,1}!]#",'log_process',$row['dblog_remarks']);
		    break;
		  case 'comment_ip' :
		    $val = $row['comment_ip'];
		    if (strlen($val) == 8)
			{
			  $hexip = explode('.', chunk_split($val, 2, '.'));
			  $val = hexdec($hexip[0]). '.' . hexdec($hexip[1]) . '.' . hexdec($hexip[2]) . '.' . hexdec($hexip[3]);
			}
		    break;
		  case 'comment_comment' :
		    $val =$tp->text_truncate($row['comment_comment'],100,'...');	// Just display first bit of comment
		    break;
		  case 'online_location' :
		    $val = str_replace($e107->base_path,'',$row['online_location']);			// Just display site-specific bit of path
			break;
		  case 'del_check' :		// Put up a 'delete' checkbox
		    $val = "<input class='tbox' type='checkbox' name='del_item[]' value='{$row['comment_id']}' >";
			$delete_button = TRUE;
		    break;
		  default :
		    $val = $row[$cf];
		  }
		  $text .= "<td class='forumheader3'>{$val}</td>";
		}
		$text .= "</tr>\n";
	  }
	}
	$text .= "
	<tr><td colspan='{$column_count}'  style='text-align:center' class='fcaption'>
	     <input class='button' type='submit' name='refreshlog' value='".RL_LAN_018."' />";
	if ($delete_button) $text .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class='button' type='submit' name='deleteitems' value='".RL_LAN_111."' />";
	$text .= "</td></tr>
	</table>
	</form>
	</div>";

	
// Next-Previous. ==========================

	if ($num_entry > $amount) 
	{
	  $parms = "{$num_entry},{$amount},{$from},".e_SELF."?".$action.".[FROM]";
	  $text .= "<br />".$tp->parseTemplate("{NEXTPREV={$parms}}");
	}

	$text .= "&nbsp;&nbsp;&nbsp;Total {$num_entry} entries matching search condition";
	$ns->tablerender("<div style='text-align:center'>{$page_title[$action]}</div>", $text);
}


function admin_log_adminmenu()
{
		if (e_QUERY) {
			$tmp = explode(".", e_QUERY);
			$action = $tmp[0];
		}
		if ($action == "") {
			$action = "adminlog";
		}
		$var['adminlog']['text'] = RL_LAN_030;
		$var['adminlog']['link'] = "admin_log.php?adminlog";
			
		$var['auditlog']['text'] = RL_LAN_062;
		$var['auditlog']['link'] = "admin_log.php?auditlog";
			
		$var['rolllog']['text'] = RL_LAN_002;
		$var['rolllog']['link'] = "admin_log.php?rolllog";
			
		$var['downlog']['text'] = RL_LAN_067;
		$var['downlog']['link'] = "admin_log.php?downlog";
			
		$var['detailed']['text'] = RL_LAN_091;
		$var['detailed']['link'] = "admin_log.php?detailed";
			
		$var['comments']['text'] = 'Comments';
		$var['comments']['link'] = "admin_log.php?comments";
			
		$var['config']['text'] = RL_LAN_027;
		$var['config']['link'] ="admin_log.php?config";

		if ($action == 'comments')
		{
		  $var['users']['text'] = RL_LAN_115;
		  $var['users']['link'] ="users.php";
		}
		
		show_admin_menu(RL_LAN_005, $action, $var);
}


require_once(e_ADMIN."footer.php");

?>