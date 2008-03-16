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
|     $Source: /cvs_backup/e107_0.8/e107_files/import/mambo_import_class.php,v $
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
$import_class_names['mambo_import'] = 'Mambo';
$import_class_comment['mambo_import'] = 'Should also work for Joomla';
$import_class_support['mambo_import'] = array('users');
$import_default_prefix['mambo_import'] = 'mos_';

// Mambo and joomla have the same DB format apart from the default prefix - 'jos_' for Joomla

require_once('import_classes.php');

class mambo_import extends base_import_class
{
  // Set up a query for the specified task.
  // Returns TRUE on success. FALSE on error
  function setupQuery($task, $blank_user=FALSE)
  {
    if ($this->ourDB == NULL) return FALSE;
    switch ($task)
	{
	  case 'users' :
	    $result = $this->ourDB->db_Select_gen("SELECT * FROM {$this->DBPrefix}users");
		if ($result === FALSE) return FALSE;
		break;
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
	if ($this->copyUserInfo) $target['user_id'] = $source['id'];
	$target['user_name'] = $source['name'];
	$target['user_loginname'] = $source['username'];
	$target['user_password'] = $source['password'];
	$target['user_email'] = $source['email'];
//	$target['user_hideemail'] = $source['user_viewemail'];
	$target['user_join'] = $source['registerDate'];
	$target['user_admin'] = ($source['usertype'] == 'superadministrator') ? 1 : 0;
	if ($target['user_admin'] != 0) $target['user_perms'] = '0.';
	$target['user_lastvisit'] = $source['lastvisitDate'];
	$target['user_login'] = $source['name'];
	$target['user_ban'] = ($source['block'] ? 2 : 0);
	return $target;
  }

}


?>
