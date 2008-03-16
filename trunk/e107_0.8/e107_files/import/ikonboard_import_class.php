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
|     $Source: /cvs_backup/e107_0.8/e107_files/import/ikonboard_import_class.php,v $
|     $Revision: 1.1 $
|     $Date: 2008-03-16 21:36:32 $
|     $Author: e107steved $
|
+----------------------------------------------------------------------------+
*/


// Each import file has an identifier which must be the same for:
//		a) This file name - add '_class.php' to get the file name
//		b) The array index of certain variables
// Array element key defines the function prefix and the class name; value is displayed in drop-down selection box
// Module based on ikonboard version current about September 2007 - may well support other versions
$import_class_names['ikonboard_import'] = 'Ikonboard';
$import_class_comment['ikonboard_import'] = 'About Sept 2007';
$import_class_support['ikonboard_import'] = array('users');
$import_default_prefix['ikonboard_import'] = 'ib_';

require_once('import_classes.php');

class ikonboard_import extends base_import_class
{
  // Set up a query for the specified task.
  // Returns TRUE on success. FALSE on error
  // If $blank_user is true, certain cross-referencing user info is to be zeroed
  function setupQuery($task, $blank_user=FALSE)
  {
    if ($this->ourDB == NULL) return FALSE;
    switch ($task)
	{
	  case 'users' :
	    $result = $this->ourDB->db_Select_gen("SELECT * FROM {$this->DBPrefix}member_profiles");
		if ($result === FALSE) return FALSE;
		break;
	  case 'forumdefs' :
	    return FALSE;
	  case 'forumposts' :
	    return FALSE;
	  case 'polls' :
	    return FALSE;
	  case 'news' :
	    return FALSE;
	  default :
	    return FALSE;
	}
	$this->copyUserInfo = !$blank_user;
	$this->currentTask = $task;
	return TRUE;
  }


  
  //------------------------------------
  //	Internal functions below here
  //------------------------------------
  
  // Copy data read from the DB into the record to be returned.
  function copyUserData(&$target, &$source)
  {
	if ($this->copyUserInfo) $target['user_id'] = $source['MEMBER_ID'];
	$target['user_name'] = $source['MEMBER_NAME'];
	$target['user_loginname'] = $source['MEMBER_NAME'];
	$target['user_password'] = $source['MEMBER_PASSWORD'];
	$target['user_email'] = $source['MEMBER_EMAIL'];
	$target['user_signature'] = $source['SIGNATURE'];
	$target['user_join'] = $source['MEMBER_JOINED'];			
	$target['user_lastvisit'] = $source['LAST_LOG_IN'];
    $target['user_image'] = $source['MEMBER_AVATAR'];
	$target['user_forums'] = $source['MEMBER_POSTS'];
	$target['user_sess'] = $source['PHOTO'];
	$target['user_hideemail'] = $source['HIDE_EMAIL'];
	$target['user_login'] = $source['MEMBER_NAME_R'];		// Guessing on this one
	$target['user_ip'] = $source['MEMBER_IP'];
	$target['user_aim'] = $source['AOLNAME'];
	$target['user_icq'] = $source['ICQNUMBER'];
	$target['user_location'] = $source['LOCATION'];
	$target['user_homepage'] = $source['WEBSITE'];
	$target['user_yahoo'] = $source['YAHOONAME'];
	$target['user_customtitle'] = $source['MEMBER_TITLE'];
	$target['user_timezone'] = $source['TIME_ADJUST'];		// May need conversion
	$target['user_language'] = $source['LANGUAGE'];			// May need conversion
	$target['user_msn'] = $source['MSNNAME'];
	$target['user_lastpost'] = $source['LAST_POST'];		// May need conversion
//	$target['user_'] = $source[''];
//	$target['user_'] = $source[''];
//	$target['user_'] = $source[''];
	// $source['MEMBER_LEVEL'] may be an admin indicator
	return $target;
  }
}


?>
