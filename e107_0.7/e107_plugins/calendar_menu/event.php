<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website sy.em
|
|     �Steve Dun.an 2001-2002
|     http://e107.org
|     jali.@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_plugins/calendar_menu/event.php,v $
|     $Revision: 1.8 $
|     $Date: 2005-03-30 17:47:33 $
|     $Author: mcfly_e107 $
+----------------------------------------------------------------------------+
*/
require_once("../../class2.php");

$ec_dir = e_PLUGIN . "calendar_menu/";
$lan_file = $ec_dir . "languages/" . e_LANGUAGE . ".php";
include_once(file_exists($lan_file) ? $lan_file : e_PLUGIN . "calendar_menu/languages/English.php");
define("PAGE_NAME", EC_LAN_80);
// *BK*
// *BK*  Set up userclass list that the person belongs to. 0 for everyone, if logged in then also in 254
// *BK*  Members only 253
// *BK*  Guest only is 252
// *BK*  Inactive is 255
if (USER)
{
    $cal_class .= "0,253," . USERCLASS;
} 
else
{
    $cal_class = "0,252";
} 
$cal_super = check_class($pref['eventpost_super']);
if (isset($_POST['viewallevents']))
{
    Header("Location: " . e_PLUGIN . "calendar_menu/calendar.php?" . $_POST['enter_new_val']);
} 

if (isset($_POST['doit']))
{
    Header("Location: " . e_PLUGIN . "calendar_menu/event.php?ne." . $_POST['enter_new_val']);
} 

// enter new category into db ------------------------------------------------------------------------
if (isset($_POST['ne_cat_create']))
{
    if ($_POST['ne_new_category'] != "")
    {
        $sql->db_Insert("event_cat", " 0, '" . $tp->toDB($_POST['ne_new_category']) . "', '" . $tp->toDB($_POST['ne_new_category_icon']) . "',0 ");
        header("location:event.php?" . $_POST['qs'] . ".m1");
    } 
    else
    {
        header("location:event.php?" . $_POST['qs'] . ".m3");
    } 
} 
// enter new event into db ----------------------------------------------------------------------------
if (isset($_POST['ne_insert']) && USER == true)
{
    if ($_POST['ne_event'] != "")
    {
        $ev_start = mktime($_POST['ne_hour'], $_POST['ne_minute'], 0, $_POST['ne_month'], $_POST['ne_day'], $_POST['ne_year']);
        $ev_end = mktime($_POST['end_hour'], $_POST['end_minute'], 0, $_POST['end_month'], $_POST['end_day'], $_POST['end_year']);
        $ev_title = $tp->toDB($_POST['ne_title']);
        $ev_location = $tp->toDB($_POST['ne_location']);
        $ev_event = $tp->toDB($_POST['ne_event']);

        if ($_POST['recurring'] == 1)
        {
            $rec_m = $_POST['ne_day'];
            $rec_y = $_POST['ne_month'];
        } 
        else
        {
            $rec_m = "";
            $rec_y = "";
        } 

        $sql->db_Insert("event", " 0, '$ev_start', '$ev_end', '" . $_POST['allday'] . "', '" . $_POST['recurring'] . "', '" . time() . "', '$ev_title', '$ev_location', '$ev_event', '" . USERID . "." . USERNAME . "', '" . $_POST['ne_email'] . "', '" . $_POST['ne_category'] . "', '" . $_POST['ne_thread'] . "', '$rec_m', '$rec_y' ");
        $qs = eregi_replace("ne.", "", $_POST['qs']);
        header("location:event.php?" . $qs . ".m4");
    } 
    else
    {
        header("location:event.php?" . $ev_start . ".m3");
    } 
} 
// ----------------------------------------------------------------------------------------------------------
// update event in db ----------------------------------------------------------------------------------
if (isset($_POST['ne_update']) && USER == true)
{
    if ($_POST['ne_event'] != "")
    {
        $ev_start = mktime($_POST['ne_hour'], $_POST['ne_minute'], 0, $_POST['ne_month'], $_POST['ne_day'], $_POST['ne_year']);
        $ev_end = mktime($_POST['end_hour'], $_POST['end_minute'], 0, $_POST['end_month'], $_POST['end_day'], $_POST['end_year']);

        $ev_title = $tp->toDB($_POST['ne_title']);
        $ev_location = $tp->toDB($_POST['ne_location']);
        $ev_event = $tp->toDB($_POST['ne_event']);

        if ($_POST['recurring'] == 1)
        {
            $rec_m = $_POST['ne_day'];
            $rec_y = $_POST['ne_month'];
        } 
        else
        {
            $rec_m = "";
            $rec_y = "";
        } 

        $sql->db_Update("event", "event_start='$ev_start', event_end='$ev_end', event_allday='" . $_POST['allday'] . "', event_recurring='" . $_POST['recurring'] . "', event_datestamp= '" . time() . "', event_title= '$ev_title', event_location='$ev_location', event_details='$ev_event', event_contact='" . $_POST['ne_email'] . "', event_category='" . $_POST['ne_category'] . "', event_thread='" . $_POST['ne_thread'] . "', event_rec_m='$rec_m', event_rec_y='$rec_y' WHERE event_id='" . $_POST['id'] . "' ");
        $qs = eregi_replace("ed.", "", $_POST['qs']);

        header("location:event.php?" . $ev_start . "." . $qs . ".m5");
    } 
    else
    {
        header("location:event.php?" . $ev_start . ".m3");
    } 
} 
// ----------------------------------------------------------------------------------------------------------
require_once(HEADERF);

if (isset($_POST['jump']))
{
    $smarray = getdate(mktime(0, 0, 0, $_POST['jumpmonth'], 1, $_POST['jumpyear']));
    $month = $smarray['mon'];
    $year = $smarray['year'];
} 
else
{
    $qs = explode(".", e_QUERY);
    $action = $qs[0];
    $ds = $qs[1];
    $eveid = $qs[2];
    if ($action == "")
    {
        $nowarray = getdate();
        $month = $nowarray['mon'];
        $year = $nowarray['year'];
    } 
    else
    {
        $smarray = getdate($action);
        $month = $smarray['mon'];
        $year = $smarray['year'];
    } 
} 

if (isset($_POST['confirm']))
{
    if ($sql->db_Delete("event", "event_id='" . $_POST['existing'] . "' "))
    {
        $message = EC_LAN_51; //Event Deleted   
    } 
    else
    {
        $message = EC_LAN_109; //Unable to Delete event for some my.erious reason
    } 
} 

if ($action == "de")
{
    $text = "<div style='text-align:center'>
		<b>" . EC_LAN_48 . "</b>
		<br /><br />
		<form method='post' action='" . e_SELF . "' id='calformz' >
		<input class='button' type='submit' name='cancel' value='" . EC_LAN_49 . "' />
		<input class='button' type='submit' name='confirm' value='" . EC_LAN_50 . "' />
		<input type='hidden' name='existing' value='" . $qs[1] . "' />
		</form>
		</div>";
    $ns->tablerender(EC_LAN_46, $text); // Confirm Delete Event
    require_once(FOOTERF);
    exit;
} 
if (isset($_POST['cancel']))
{
    $message = EC_LAN_47; 
    // Delete Cancelled
} 
// set up data arrays ----------------------------------------------------------------------------------
if($pref['eventpost_weekstart'] == 'sun')
{
	$days = Array(EC_LAN_25, EC_LAN_19, EC_LAN_20, EC_LAN_21, EC_LAN_22, EC_LAN_23, EC_LAN_24);
}
else
{
	$days = Array(EC_LAN_19, EC_LAN_20, EC_LAN_21, EC_LAN_22, EC_LAN_23, EC_LAN_24, EC_LAN_25);
}	
$dayslo = array('1.', '2.', '3.', '4.', '5.', '6.', '7.', '8.', '9.', '10.', '11.', '12.', '13.', '14.', '15.', '16.', '17.', '18.', '19.', '20.', '21.', '22.', '23.', '24.', '25.', '26.', '27.', '28.', '29.', '30.', '31.');
$monthabb = Array(EC_LAN_JAN, EC_LAN_FEB, EC_LAN_MAR, EC_LAN_APR, EC_LAN_MAY, EC_LAN_JUN, EC_LAN_JUL, EC_LAN_AUG, EC_LAN_SEP, EC_LAN_OCT, EC_LAN_NOV, EC_LAN_DEC);
$months = array(EC_LAN_0, EC_LAN_1, EC_LAN_2, EC_LAN_3, EC_LAN_4, EC_LAN_5, EC_LAN_6, EC_LAN_7, EC_LAN_8, EC_LAN_9, EC_LAN_10, EC_LAN_11);
// ----------------------------------------------------------------------------------------------------------
if ($qs[2] == "m1")
{
    $message = EC_LAN_41; //"New category created.";
} 
else if ($qs[2] == "m2")
{
    $message = EC_LAN_42; //"Event cannot end before it starts.";
} 
else if ($qs[2] == "m3")
{
    $message = EC_LAN_43; //"You left required field(s) blank.";
} 
else if ($qs[2] == "m4")
{
    $message = EC_LAN_44; //"New event created and entered into database.";
} elseif ($qs[2] == "m5")
{
    $message = EC_LAN_45; // "Event updated in database.";
} 

if (isset($message))
{
    $ns->tablerender("", "<div style='text-align:center'><b>" . $message . "</b></div>");
} 
// enter new event form---------------------------------------------------------------------------------
if ($action == "ne" || $action == "ed")
{
    if ($cal_super || check_class($pref['eventpost_admin']))
    {
        if ($action == "ed")
        {
            $sql->db_Select("event", "*", "event_id='" . $qs[1] . "' ");
            list($null, $ne_start, $ne_end, $allday, $recurring, $ne_datestamp, $ne_title, $ne_location, $ne_event, $ne_author, $ne_email, $ne_category, $ne_thread) = $sql->db_Fetch();

            $smarray = getdate($ne_start);
            $ne_day = $smarray['mday'];
            $ne_month = $smarray['mon'];
            $ne_year = $smarray['year'];

            $ne_hour = $smarray['hours'];
            $ne_minute = $smarray['minutes'];

            $smarray = getdate($ne_end);
            $end_day = $smarray['mday'];
            $end_month = $smarray['mon'];
            $end_year = $smarray['year'];

            $end_hour = $smarray['hours'];
            $end_minute = $smarray['minutes'];
        } 
        else
        {
            $smarray = getdate($qs[1]);
            $month = $smarray['mon'];
            $year = $smarray['year'];

            $ne_day = $smarray['mday'];
            $ne_month = $smarray['mon'];
            $ne_year = $smarray['year'];

            $ne_hour = $smarray['hours'];
            $ne_minute = $smarray['minutes'];
            $end_day = $smarray['mday'];
            $end_month = $smarray['mon'];
            $end_year = $smarray['year'];

            $end_hour = $smarray['hours'];
            $end_minute = $smarray['minutes'];
        } 

        $text = "
				<script type=\"text/javascript\">
		<!--
			function calcheckform(thisform)
			{
				var testresults=true;
				var sdate=new Date(thisform.ne_year.value,thisform.ne_month.value-1,thisform.ne_day.value,thisform.ne_hour.value,thisform.ne_minute.value,0);
				var edate=new Date(thisform.end_year.value,thisform.end_month.value-1,thisform.end_day.value,thisform.end_hour.value,thisform.end_minute.value,0);
				if (thisform.ne_new_category.value!='')
				{
					testresults=true;
				}
				else
				{
					if (edate <= sdate && !thisform.allday.checked && testresults )
					{
						alert('" . EC_LAN_99 . "');
						testresults=false;
					}
					if ((thisform.ne_title.value=='' || thisform.ne_event.value=='') && testresults)
					{
						alert('" . EC_LAN_98 . "');
						testresults=false;
					}
				}
				if (testresults)
				{
					if (thisform.subbed.value=='no')
	  				{
						thisformm.subbed.value='yes';
				   		testresults=true;
					}
					else 
					{
		   				alert('" . EC_LAN_113 . "');
				   		return false;
			   		}
				}
				return testresults;
			}
			-->
		</script>		
		<form method='post' action='" . e_SELF . "' id='linkform' onsubmit='return calcheckform(this)'>
		<table style='width:98%' class='fborder' >";

        if ($action == "ed")
        {
            $caption = EC_LAN_66; 
            // edit Event
        } elseif ($action == "ne")
        {
            $caption = EC_LAN_28; // Enter New Event
        } 
        else
        {
            $caption = EC_LAN_83;
        } 

        $text .= "
			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_72 . " </td>
			<td class='forumheader3' style='width:80%'>
			" . EC_LAN_67 . " <select name='ne_day' class='tbox'>";
        for($count = 1; $count <= 31; $count++)
        {
            if ($count == $ne_day)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select>
			<select name='ne_month' class='tbox'>";
        for($count = 1; $count <= 12; $count++)
        {
            if ($count == $ne_month)
            {
                $text .= "<option value='$count' selected='selected'>" . $months[($count-1)] . "</option>";
            } 
            else
            {
                $text .= "<option value='$count'>" . $months[($count-1)] . "</option>";
            } 
        } 
        $text .= "</select>
			<select name='ne_year' class='tbox'>";
        for($count = 2002; $count <= 2022; $count++)
        {
            if ($count == $ne_year)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select><br />&nbsp;" . EC_LAN_73 . "
			<select name='end_day' class='tbox'>";
        for($count = 1; $count <= 31; $count++)
        {
            if ($count == $end_day)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select>
			<select name='end_month' class='tbox'>";
        for($count = 1; $count <= 12; $count++)
        {
            if ($count == $end_month)
            {
                $text .= "<option value='$count' selected='selected'>" . $months[($count-1)] . "</option>";
            } 
            else
            {
                $text .= "<option value='$count'>" . $months[($count-1)] . "</option>";
            } 
        } 
        $text .= "</select>
			<select name='end_year' class='tbox'>";
        for($count = 2002; $count <= 2022; $count++)
        {
            if ($count == $end_year)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select>
			</td>
			</tr>
			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_71 . " </td>
			<td class='forumheader3' style='width:80%'>
			" . EC_LAN_67 . " <select name='ne_hour' class='tbox'>";
        for($count = "00"; $count <= "23"; $count++)
        {
            if ($count == $ne_hour)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select>
			<select name='ne_minute' class='tbox'>";
        for($count = "00"; $count <= "59"; $count++)
        {
            if ($count == $ne_minute)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select>

			&nbsp;&nbsp;" . EC_LAN_73 . " <select name='end_hour' class='tbox'>";
        for($count = "00"; $count <= "23"; $count++)
        {
            if ($count == $end_hour)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select>
			<select name='end_minute' class='tbox'>";
        for($count = "00"; $count <= "59"; $count++)
        {
            if ($count == $end_minute)
            {
                $text .= "<option selected='selected'>" . $count . "</option>";
            } 
            else
            {
                $text .= "<option>" . $count . "</option>";
            } 
        } 
        $text .= "</select>";

        if ($allday == 1)
        {
            $text .= "<br /><input type='checkbox' name='allday' value='1'  checked='checked' />";
        } 
        else
        {
            $text .= "<br /><input type='checkbox' name='allday' value='1' />";
        } 

        $text .= EC_LAN_64 . "

			</td>
			</tr>
			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_65 . "</td>
			<td class='forumheader3' style='width:80%'>";

        if ($recurring == 1)
        {
            $text .= "<input type='checkbox' name='recurring' value='1'  checked='checked' />";
        } 
        else
        {
            $text .= "<input type='checkbox' name='recurring' value='1' />";
        } 

        $text .= EC_LAN_63 . "
			</td>
			</tr>
			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_70 . " *</td>
			<td class='forumheader3' style='width:80%'>
			<input class='tbox' type='text' name='ne_title' size='75' value='$ne_title' maxlength='200' style='width:95%' />
			</td>
			</tr>
			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_52 . " </td>
			<td class='forumheader3' style='width:80%'>
			<select name='ne_category' class='tbox'>"; 
        // Check if supervisor, if so get all categories, otherwise ju. get those the user is allowed to see
        if ($cal_super)
        {
            $cal_arg = "";
        } 
        else
        {
            $cal_arg = "find_in_set(event_cat_class,'" . $cal_class . "')";
        } 
        if ($sql->db_Select("event_cat", "*", $cal_arg))
        {
            while ($row = $sql->db_Fetch())
            {
                if ($ne_category == $row['event_cat_id'])
                {
                    $text .= "<option value='{$row['event_cat_id']}' selected='selected' >" . $row['event_cat_name'] . "</option>";
                } 
                else
                {
                    $text .= "<option value='{$row['event_cat_id']}'>" . $row['event_cat_name'] . "</option>";
                } 
            } 
        } 
        else
        {
            $text .= "<option value=''>" . EC_LAN_91 . "</option>";
        } 

        $text .= "</select>
			</td>
			</tr>"; 
        // * *BK* Check if the add class is appropriate for adding new categories
        // * *BK* It will default to everybody class when created.  Need to go in to admin categories if
        // * *BK* you want to change read class.
        if ($cal_super || check_class($pref['eventpost_addcat']) && $action != "ed")
        {
            require_once(e_HANDLER . "file_class.php");
            $fi = new e_file;
            $imagelist = $fi->get_files(e_PLUGIN . "calendar_menu/images", "\.\w{3}$");
            $text .= "<tr>
				<td class='forumheader3' style='width:20%' rowspan='2'>" . EC_LAN_53 . " </td>
				<td class='forumheader3' style='width:80%'>" . EC_LAN_54 . " 
				<input class='tbox' type='text' name='ne_new_category' size='30' value='$ne_new_category' maxlength='100' style='width:95%' /> ";
            $text .= "</td></tr>
			<tr><td class='forumheader3' style='width:80%'>" . EC_LAN_55;
            $text .= " <input class='tbox' style='width:150px' type='text' id='ne_new_category_icon' name='ne_new_category_icon' />";
            $text .= " <input class='button' type='button' style='width: 45px; cursor:hand;' value='" . EC_LAN_90 . "' onclick='expandit(\"cat_icons\")' />";
            $text .= "<div style='display:none' id='cat_icons'>";

            foreach($imagelist as $img)
            {
                if ($img['fname'])
                {
                    $text .= "<a href='javascript:insertext(\"" . $img['fname'] . "\", \"ne_new_category_icon\")'><img src='" . e_PLUGIN . "calendar_menu/images/" . $img['fname'] . "' style='border:0px' alt='' /></a> ";
                } 
            } 

            $text .= "</div>";
            $text .= "<div style='text-align:center'>
			<input class='button' type='submit' name='ne_cat_create' value='" . EC_LAN_56 . "' /></div>";
        } 

        $text .= "</td>
			</tr>

			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_32 . " </td>
			<td class='forumheader3' style='width:80%'>
			<input class='tbox' type='text' name='ne_location' size='60' value='$ne_location' maxlength='200' style='width:95%' />

			</td>
			</tr>

			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_57 . " *</td>
			<td class='forumheader3' style='width:80%'>
			<textarea class='tbox' name='ne_event' cols='59' rows='8' style='width:95%'>$ne_event</textarea>

			</td>
			</tr>"; 
        // * *BK*
        // * *BK* Only display for forum thread if it is required.  No point in being in if not wanted
        // * *BK* or if forums are inactive
        // * *BK*
        if ($pref[eventpost_forum] == 1)
        {
            $text .= "
			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_58 . " </td>
			<td class='forumheader3' style='width:80%'>
			<input class='tbox' type='text' name='ne_thread' size='60' value='$ne_thread' maxlength='100' style='width:95%' />

			</td>
			</tr>";
        } 
        // * *BK*
        // * *BK* If the user is logged in and has their email set plus the field is empty then put in
        // * *BK* their email address.  They can always take it out if they want, its not a required field
        if (empty($ne_email) && defined('USEREMAIL'))
        {
            $ne_email = USEREMAIL;
        } 
        $text .= "
			<tr>
			<td class='forumheader3' style='width:20%'>" . EC_LAN_59 . " </td>
			<td class='forumheader3' style='width:80%'>
			<input class='tbox' type='text' name='ne_email' size='60' value='$ne_email' maxlength='150' style='width:95%' />

			</td></tr>
			<tr>
			<td class='forumheader3' colspan='2' >" . EC_LAN_105 . " </td>
			</tr>

			<tr>
			<td class='forumheader' colspan='2' style='text-align:center'>
			
			";
        if ($action == "ed")
        {
            $text .= "<input class='button' type='submit' name='ne_update' value='" . EC_LAN_60 . "' />
				<input type='hidden' name='id' value='" . $qs[1] . "' />";
        } 
        else
        {
            $text .= "<input class='button' type='submit' name='ne_insert' value='" . EC_LAN_28 . "' />";
        } 

        $text .= "<input type='hidden' name='qs' value='" . e_QUERY . "' /></td>
			</tr>
			</table>			
			</form>";

        $ns->tablerender($caption, $text);
        require_once(FOOTERF);
        exit;
    } 
    else
    {
        header("location:" . e_PLUGIN . "calendar_menu/event.php");
        exit;
    } 
} 
// ----------------------------------------------------------------------------------------------------------
// show events-------------------------------------------------------------------------------------------
// get fir. and la. days of month in unix format---------------------------------------------------
$monthstart = mktime(0, 0, 0, $month, 1, $year);
$firstdayarray = getdate($monthstart);
$monthend = mktime(0, 0, 0, $month + 1, 0, $year);
$lastdayarray = getdate($monthend);
// ----------------------------------------------------------------------------------------------------------
// echo current month with links to previous/next months ----------------------------------------
$prevmonth = ($month-1);
$prevyear = $year;
if ($prevmonth == 0)
{
    $prevmonth = 12;
    $prevyear = ($year-1);
} 
$previous = mktime(0, 0, 0, $prevmonth, 1, $prevyear);

$nextmonth = ($month + 1);
$nextyear = $year;
if ($nextmonth == 13)
{
    $nextmonth = 1;
    $nextyear = ($year + 1);
} 
$next = mktime(0, 0, 0, $nextmonth, 1, $nextyear);

$todayarray = getdate();
$current_month = $todayarray['mon'];
$current_year = $todayarray['year'];
$current = mktime(0, 0, 0, $current_month, 1, $current_year);

$prop = mktime(0, 0, 0, $month, 1, $year);

$next = mktime(0, 0, 0, $nextmonth, 1, $nextyear);
$py = $year-1;
$prevlink = mktime(0, 0, 0, $month, 1, $py);
$ny = $year + 1;
$nextlink = mktime(0, 0, 0, $month, 1, $ny);

$text2 .= "<table style='width:98%' class='fborder'>
	<tr>
	<td class='forumheader' style='width:18%; text-align:left;'><span class='defaulttext'><a href='" . e_SELF . "?" . $previous . "'>&lt;&lt; " . $months[($prevmonth-1)] . "</a></span></td>";
if($pref['eventpost_dateformat'] == 'my')
{	
	$text2 .= "<td class='fcaption' style='width:64%; text-align:center'><b>" . $months[($month-1)] . " " . $year . "</b></td>";
}
else
{
	$text2 .= "<td class='fcaption' style='width:64%; text-align:center'><b>" . $year . " " . $months[($month-1)] . "</b></td>";
}
// end
$text2 .= "<td class='forumheader' style='width:185%; text-align:right;'><span class='defaulttext'><a href='" . e_SELF . "?" . $next . "'> " . $months[($nextmonth-1)] . " &gt;&gt;</a></span> </td>
	</tr>
	<tr><td colspan='3'></td></tr>
	<tr>
	<td class='forumheader' style='text-align:left;'><a href='event.php?" . $prevlink . "'>&lt;&lt; " . $py . "</a></td>
	<td class='fcaption' style='text-align:center;'>";
for ($ii = 0; $ii < 13; $ii++)
{
    $m = $ii + 1;
    $monthjump = mktime(0, 0, 0, $m, 1, $year);
    $text2 .= "<a href='event.php?" . $monthjump . "'>" . $monthabb[$ii] . "</a> &nbsp;";
} 
$text2 .= "</td>
<td class='forumheader' style='text-align:right'><a href='event.php?" . $nextlink . "'>" . $ny . " &gt;&gt;</a></td>
</tr></table>";

$text2 .= "<div style='text-align:center'>";

$text2 .= "<br /><form method='post' action='" . e_SELF . "?" . e_QUERY . "' id='calformc'><table border='0' cellpadding='2' cellspacing='3' class='forumheader3'>
	<tr><td style='text-align:right;'>
	
	<select name='event_cat_ids' class='tbox' style='width:140px;' onchange='this.form.submit()'>
	<option class='tbox' value='all'>" . EC_LAN_97 . "</option>";

$event_cat_id = !isset($_POST['event_cat_ids']) ? null : $_POST['event_cat_ids'];
// * *BK* If supervisor then can do anything and see all
if ($cal_super)
{
    $cal_arg = "";
} 
else
{
    $cal_arg = " find_in_set(event_cat_class,'$cal_class') ";
} 

$sql->db_Select("event_cat", "*", $cal_arg);

while ($row = $sql->db_Fetch())
{
    if ($row['event_cat_id'] == $_POST['event_cat_ids'])
    {
        $text2 .= "<option class='tbox' value='$event_cat_id' selected='selected'>" . $row['event_cat_name'] . "</option>";
    } 
    else
    {
        $text2 .= "<option value='{$row['event_cat_id']}'>" . $row['event_cat_name'] . "</option>";
    } 
} 

$text2 .= "</select></td><td style='text-align:center;'>
	<input class='button' type='submit' style='width:140px;' name='viewallevents' value='" . EC_LAN_96 . "' title='" . EC_LAN_96 . "' />
	</td></tr>
	<tr><td align='right'><input type='hidden' name='do' value='vc' />
	<input class='button' type='submit' style='width:140px;' name='viewcat' value='" . EC_LAN_92 . "' />
	</td><td style='text-align:center;'><input type='hidden' name='enter_new_val' value='" . $prop . "' /> ";

if (check_class($pref['eventpost_admin']) || getperms('0'))
{ 
    // start no admin preference
    $text2 .= "<input class='button' type='submit' style='width:140px;' name='doit' value='" . EC_LAN_94 . "' />";
} 

$text2 .= "</td></tr></table></form></div><br />";
// --------------------------------------------------------------------------------
// extra stuff for Category.
$sql2 = new db;
if ($ds == "event")
{ 
    // * *BK*
    // * *BK* Added by Barry to show one event when clicked on
    // * *BK*
    $sql2->db_Select("event", "*", "event_id='$eveid'");
    $row = $sql2->db_Fetch();
    $event[] = $row;
    $text2 .= "<table style='width:98%' class='fborder'>
	<tr><td class='fcaption' colspan='2'><strong>" . EC_LAN_110 . " $eveid</strong></td></tr>";
    $text2 .= show_event($event);
    $text2 .= "</table>";
} 
else
{
    $sql2->db_Select("event_cat", "*", "event_cat_id='" . $event_true[($c)] . "' ");
    $event_cat = $sql2->db_Fetch();
    extract($event_cat);
    if ($ds == 'one')
    {
        $tmp = getdate($action);
        $selected_day = $tmp['mday'];
        $selected_mon = $tmp['mon'];
        $start_time = $action;
        $end_time = $action + 86399;
        $cap_title = " - " . $months[$selected_mon-1] . " " . $selected_day;
    } 
    else
    {
        $start_time = $monthstart;
        $end_time = $monthend + 86399;
        $cap_title = '';
    } 
    $extra = " OR e.event_rec_y = {$month} ";

    if ($cal_super)
    {
        $qry = "
SELECT e.*, ec.*
FROM #event as e
LEFT JOIN #event_cat as ec ON e.event_category = ec.event_cat_id
WHERE (e.event_start >= {$start_time} AND e.event_start <= {$end_time}) OR (e.event_end >= {$start_time} AND e.event_end <= {$end_time}) {$extra}
ORDER BY e.event_start ASC";
    } 
    else
    {
        $qry = "
SELECT e.*, ec.*
FROM #event as e
LEFT JOIN #event_cat as ec ON e.event_category = ec.event_cat_id
WHERE find_in_set(event_cat_class,'$cal_class') and ((e.event_start >= {$start_time} AND e.event_start <= {$end_time}) OR (e.event_end >= {$start_time} AND e.event_end <= {$end_time}) {$extra})
ORDER BY e.event_start ASC";
    } 
    if ($sql->db_Select_gen($qry))
    {
        while ($row = $sql->db_Fetch())
        {
            if ($row['event_rec_y'] == $month)
            {
                if(!in_array($row['event_id'], $idArray))
                {
                	$events[$row['event_rec_m']][] = $row;
                	$idArray[]=$row['event_id'];
                }
            } 
            else
            {
                $tmp = getdate($row['event_start']);
                if ($tmp['year'] == $year)
                {
                    $start_day = $tmp['mday'];
                } 
                else
                {
                    $start_day = 1;
                } 
                $tmp = getdate($row['event_end']);
                if ($tmp['year'] == $year)
                {
                    $end_day = $tmp['mday'];
                } 
                else
                {
                    $end_day = 31;
                } 
                for ($i = $start_day; $i <= $end_day; $i++)
                {
	                if(!in_array($row['event_id'], $idArray))
   	             {
							$events[$i][] = $row;
         	       	$idArray[]=$row['event_id'];
            	    }
                } 
            } 
        } 
    } 
} 
$text2 .= "<table style='width:98%' class='fborder'>";

if ($ds == 'one')
{
    $text2 .= "<tr><td class='fcaption' colspan='2'><strong>" . EC_LAN_111 . $months[$selected_mon-1] . " " . $dayslo[$selected_day-1] . "</strong></td></tr>";
} 
else
{
    $text2 .= "<tr><td class='fcaption' colspan='2'><strong>" . EC_LAN_112 . " " . $months[date("m", $monthstart)-1] . "</strong></td></tr>";
} 
// echo "<pre>".print_r($events, TRUE)."</pre>";
foreach ($events as $dom => $event)
{ 
//     echo "selected day = $selected_day, dom = $dom <br />";
    if ($ds == 'one')
    {
        if ($dom == $selected_day)
        {
            $text2 .= show_event($event);
        } 
    } 
    else
    {
        $text2 .= show_event($event);
    } 
} 

$text2 .= "</table>";
// -----------------------------------------------------------------------------------------------------------
$nextmonth = mktime(0, 0, 0, $month + 1, 1, $year);
$sql->db_Select("event", "*", "event_start>='$nextmonth' ORDER BY event_start ASC LIMIT 0,10");
$num = $sql->db_Rows();
// added by rezso
$gen = new convert;
// end
$text2 .= "<br /><table style='width:98%' class='fborder'>
	<tr>
	<td colspan='2' class='forumheader'><span class='defaulttext'>" . EC_LAN_62 . "</span></td></tr>";
if ($num != 0)
{
    while ($events = $sql->db_Fetch())
    {
        extract($events);
        // changed by rezso
        //$startds = ereg_replace(" 0", " ", date("l d F Y @ H:i:s", $event_start));
        $startds = $gen -> convert_date($event_start, "long");
        $text2 .= "<tr><td style='width:35%; vertical-align:top' class='forumheader3'><a href='event.php?" . $event_start . "'>" . $startds . "</a></td>
			<td style='width:65%' class='forumheader3'>" . $event_details . "</td></tr>";
    } 
} 
else
{
    $text2 .= "<tr><td colspan='2' class='forumheader3'>" . EC_LAN_37 . "</td></tr>";
} 

$text2 .= "</table>";
$caption = EC_LAN_80; // "Event List";
$ns->tablerender($caption . $cap_title, $text2);
require_once(FOOTERF);

function show_event($day_events)
{
	//echo "<pre>".print_r($day_events, true)."</pre>";
    foreach($day_events as $event)
    {
        
        global $tp, $cal_super;
        $gen = new convert;
        if (($_POST['do'] == null || $_POST['event_cat_ids'] == "all") || ($_POST['event_cat_ids'] == $event['event_cat_id']))
        {
            $evf = getdate($event['event_start']);
            $tmp = $evf['mday'];
            if ($event['event_allday'] == 0)
            {
                if ($event['event_start'] > $event['event_end'])
                {
                    $event['event_end'] = $event['event_start'];
                } 
            } 
				
				$startds = cal_landate($event['event_start'], $event['event_recurring'], $event['event_allday']);
				$endds = cal_landate($event['event_end'], $event['event_recurring'], $event['event_allday']);

            $lp = explode(".", $event['event_author']);
            if (ereg("[0-9]+", $lp[0]))
            {
                $event_author_id = $lp[0];
                $event_author_name = $lp[1];
            } 
            if ($event['event_cat_icon'])
            {
                $text2 .= "<tr>
				<td colspan='2' class='fcaption'><img style='border:0' src='" . e_PLUGIN . "calendar_menu/images/" . $event['event_cat_icon'] . "' alt='' /> " . $event['event_title'] . "</td>";
            } 
            else
            {
                $text2 .= "<tr>
				<td colspan='2' class='fcaption'>" . $event['event_title'] . "</td>";
            } 

            $text2 .= "</tr>
			<tr>";
            if ($event['event_allday'])
            {
                $text2 .= "<td colspan='2' class='forumheader'><b>" . EC_LAN_68 . "</b>: $startds</td>";
            } 
            else if ($startds == $endds)
            {
                $text2 .= "<td colspan='2' class='forumheader'><b>" . EC_LAN_29 . "</b>: " . $startds . "</td>";
            } 
            else
            {
                $text2 .= "<td style='width:50%' class='forumheader'><b>" . EC_LAN_29 . "</b>: " . $startds . "</td>
				<td style='width:50%' class='forumheader'><b>" . EC_LAN_69 . "</b>: " . $endds . "</td>";
            } 
            $text2 .= "</tr>
			<tr>
			<td colspan='2' class='forumheader3'>" . $tp->toHTML($event['event_details'], true) . "
			</td>
			</tr>

			<tr>";

            if ($event['event_cat_icon'])
            {
                $text2 .= "
				<td style='width:50%' class='forumheader3'><b>" . EC_LAN_30 . "</b> <img style='border:0' src='" . e_PLUGIN . "calendar_menu/images/" . $event['event_cat_icon'] . "' alt='' width='12' height='12' /> " . $event['event_cat_name'] . "</td>";
            } 
            else
            {
                $text2 .= "
				<td style='width:50%' class='forumheader3'><b>" . EC_LAN_30 . "</b> " . $event['event_cat_name'] . "</td>";
            } 

            $text2 .= "<td style='width:50%' class='forumheader3'><b>" . EC_LAN_32 . "</b> ";
            if ($event['event_location'] == "")
            {
                $text2 .= EC_LAN_38;
            } 
            else
            {
                $text2 .= $event['event_location'];
            } 
            $text2 .= "</td></tr>
			<tr>
			<td style='width:33%' class='forumheader3'><b>" . EC_LAN_31 . "</b> <a href='" . e_BASE . "user.php?id." . $event_author_id . "'>" . $event_author_name . "</a></td>
			<td style='width:33%' class='forumheader3'><b>" . EC_LAN_33 . "</b> ";
            if ($event['event_contact'] == "")
            {
                $text2 .= EC_LAN_38; // Not Specified ;
            } 
            else
            {
                $smailaddr = explode("@", $event['event_contact']);

                $text2 .= "<script type='text/javascript'>
			<!--
var contact='" . $smailaddr[0] . " at " . $smailaddr[1] . "';
var email='" . $smailaddr[0] . "';
var emailHost='" . $smailaddr[1] . "';
document.write(\"<a href=\" + \"mail\" + \"to:\" + email + \"@\" + emailHost+ \">\" + contact + \"</a>\" + \"\")
			//-->
			</script>"; 
                // $text2 .= "<a href='mailto:" . $event['event_contact'] . "'>" . $event['event_contact'] . "</a>";
            } 

            $text2 .= "</td></tr>
			<tr>
			<td style='width:50%' class='forumheader'>" .
            ($event['event_thread'] ? "<span class='smalltext'><a href='{$event['event_thread']}'><img src='" . e_PLUGIN . "forum/images/e.png' alt='' style='border:0' width='16' height='16' align='absmiddle'> " . EC_LAN_39 . "</a></span>" : "&nbsp;") . "

			</td>
			<td style='width:50%;text-align:right' class='forumheader' >";

            if (USERNAME == $event_author_name || $cal_super)
            {
                $text2 .= "<span class='smalltext'>
				[ <a href='event.php?ed." . $event['event_id'] . "'>" . EC_LAN_35 . "</a> ] [ <a href='" . e_PLUGIN . "calendar_menu/event.php?de." . $event['event_id'] . "'>" . EC_LAN_36 . "</a> ]
				</span>";
            } 

            $text2 .= "</td>
			</tr>";
        } 
    } 
    return $text2;
} 

function cal_landate($dstamp, $recurring = FALSE, $allday = FALSE)
{
	$long_month_start = 0;
	$long_day_start = 12;
	$now = getdate($dstamp);

	if($now['wday'] == 0)
	{
		$now['wday'] = 7;
	}
	$dow = constant("EC_LAN_".($long_day_start+$now['wday']-1));
	$moy = constant("EC_LAN_".($long_month_start+$now['mon']-1));
	
	if($recurring == TRUE)
	{
		$today = getdate();
		$now['year'] = $today['year'];
	}
		
	if($allday == TRUE)
	{
		return sprintf("%s %02d %s %d", $dow, $now['mday'], $moy, $now['year']);
	}
	else
	{
		return sprintf("%s %02d %s %d - %02d:%02d", $dow, $now['mday'], $moy, $now['year'], $now['hours'], $now['minutes']);
	}
}	

?>