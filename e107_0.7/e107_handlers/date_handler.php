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
|     $Source: /cvs_backup/e107_0.7/e107_handlers/date_handler.php,v $
|     $Revision: 1.7 $
|     $Date: 2005-12-14 17:37:34 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/
if (!defined('e107_INIT')) { exit; }

if (is_readable(e_LANGUAGEDIR.e_LANGUAGE."/lan_date.php")) {
	@include_once(e_LANGUAGEDIR.e_LANGUAGE."/lan_date.php");
} else {
	@include_once(e_LANGUAGEDIR."English/lan_date.php");
}

class convert
{

	function convert_date($datestamp, $mode = "long") {
		/*
		# Date convert
		# - parameter #1:  string $datestamp, unix stamp
		# - parameter #2:  string $mode, date format, default long
		# - return         parsed text
		# - scope          public
		*/
		global $pref;

		$datestamp += TIMEOFFSET;

		if ($mode == "long") {
			return strftime($pref['longdate'], $datestamp);
		} else if ($mode == "short") {
			return strftime($pref['shortdate'], $datestamp);
		} else {
			return strftime($pref['forumdate'], $datestamp);
		}
	}

	function computeLapse($older_date, $newer_date = FALSE, $mode = FALSE, $show_secs = TRUE, $format = 'long') 
	{

		/*
		$mode = TRUE :: return array
		$mode = FALSE :: return string
		*/

		$newer_date = ($newer_date == FALSE ? (time()) : $newer_date);
		$since = $newer_date - $older_date;
		if($format == 'short')
		{
			$sec = LANDT_09;
			$secs = LANDT_09s;
			$min = LANDT_08;
			$mins = LANDT_08s;
		}
		else
		{
			$sec = LANDT_07;
			$secs = LANDT_07s;
			$min = LANDT_06;
			$mins = LANDT_06s;
		}

		$timings = array(
			array(31536000 , LANDT_01,LANDT_01s),
			array(2592000 , LANDT_02, LANDT_02s),
			array(604800, LANDT_03, LANDT_03s),
			array(86400 , LANDT_04, LANDT_04s),
			array(3600 , LANDT_05, LANDT_05s),
			array(60 , $min, $mins)
		);
		if($show_secs)
		{
			$timings[] = array(1 , $sec, $secs);
		}
		$newer_date = ($newer_date == FALSE ? (time()) : $newer_date);
		$since = $newer_date - $older_date;

		$outputArray = array();
		$total = $since;
		$value = FALSE;
		foreach($timings as $time)
		{
			$seconds = floor($total / $time[0]);
			if($seconds || $value)
			{
				$outputArray[] = $seconds." ".($seconds == 1 ? $time[1] : $time[2]);
				$value = TRUE;
			}
			$total = fmod($total, $time[0]);
		}
		return ($mode ? $outputArray : implode(", ", $outputArray));
	}

}