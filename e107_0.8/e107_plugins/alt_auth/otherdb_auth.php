<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.8/e107_plugins/alt_auth/otherdb_auth.php,v $
|     $Revision: 1.2 $
|     $Date: 2008-07-25 19:33:02 $
|     $Author: e107steved $
+----------------------------------------------------------------------------+
*/

/* 
	return values
	AUTH_NOCONNECT 		= unable to connect to db
	AUTH_NOUSER			= user not found	
	AUTH_BADPASSWORD	= supplied password incorrect

	AUTH_SUCCESS 		= valid login
*/

class auth_login
{

	var $od;
	var $Available;
	
	function auth_login()
	{
//		global $otherdb_conf, $sql;
		global $sql;
		$sql -> db_Select("alt_auth", "*", "auth_type = 'otherdb' ");
		while($row = $sql -> db_Fetch())
		{
			$otherdb_conf[$row['auth_parmname']] = base64_decode(base64_decode($row['auth_parmval']));
		}
		$class_name = "otherdb_mysql_class";

		if(class_exists($class_name))
		{
		  $this->od = new $class_name($otherdb_conf);
		  $this->Available = TRUE;
		}
		else
		{
		  $this->Available = FALSE;
		  return AUTH_NOCONNECT;
		}
	}

	function login($uname, $pword, &$newvals, $connect_only = FALSE)
	{
		global $mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb, $sql;
		$ret = $this->od->login($uname, $pword, $newvals, $connect_only);
		$sql->db_Connect($mySQLserver, $mySQLuser, $mySQLpassword, $mySQLdefaultdb);
		return $ret;
	}

}

class otherdb_mysql_class
{
	
	var $conf;
	
	function otherdb_mysql_class($otherdb_conf)
	{
//		global $otherdb_conf;
		$this->conf = $otherdb_conf;
	}
	


	function login($uname, $pword, &$newvals, $connect_only = FALSE)
	{
	  //Attempt to open connection to sql database
	  if(!$res = mysql_connect($this->conf['otherdb_server'], $this->conf['otherdb_username'], $this->conf['otherdb_password']))
	  {
		return AUTH_NOCONNECT;
	  }
	  //Select correct db
	  if(!mysql_select_db($this->conf['otherdb_database'], $res))
	  {
		mysql_close($res);
		return AUTH_NOCONNECT;
	  }
	  if ($connect_only) return AUTH_SUCCESS;		// Test mode may just want to connect to the DB
	  $sel_fields = array();
	  // Make an array of the fields we want from the source DB
	  foreach($this->conf as $k => $v)
	  {
	    if ($v && (strpos($k,'otherdb_xf_') === 0))
		{
		  $sel_fields[] = $v;
		}
	  }
	  $sel_fields[] = $this->conf['otherdb_password_field'];
	  $user_field = $this->conf['otherdb_user_field'];
	  if (isset($this->conf['otherdb_salt_field']))
	  {
		$sel_fields[] = $this->conf['otherdb_salt_field'];
	  }


	  //Get record containing supplied login name
	  $qry = "SELECT ".implode(',',$sel_fields)." FROM {$this->conf['otherdb_table']} WHERE {$user_field} = '{$uname}'";
//	  echo "Query: {$qry}<br />";
	  if(!$r1 = mysql_query($qry))
	  {
		mysql_close($res);
		return AUTH_NOCONNECT;
	  }
	  if(!$row = mysql_fetch_array($r1))
	  {
		mysql_close($res);
		return AUTH_NOUSER;
	  }
		
	  mysql_close($res);				// Finished with 'foreign' DB now

	  // Got something from the DB - see whether password valid
	  require_once(e_PLUGIN.'alt_auth/extended_password_handler.php');		// This auto-loads the 'standard' password handler as well
	  $pass_check = new ExtendedPasswordHandler();

	  $passMethod = $pass_check->passwordMapping($this->conf['otherdb_password_method']);
	  if ($passMethod === FALSE) return AUTH_BADPASSWORD;

	  $pwFromDB = $row[$this->conf['otherdb_password_field']];					// Password stored in DB
	  if ($salt_field) $pwFromDB .= ':'.$row[$salt_field];

	  if ($pass_check->checkPassword($pword, $uname, $pwFromDB, $passMethod) !== PASSWORD_VALID)
	  {
		return AUTH_BADPASSWORD;
	  }
	  // Now copy across any values we have selected
	  foreach($this->conf as $k => $v)
	  {
	    if ($v && (strpos($k,'otherdb_xf_') === 0) && isset($row[$v]))
		{
		  $newvals[substr($k,strlen('otherdb_xf_'))] = $row[$v];
		}
	  }

	  return AUTH_SUCCESS;
	}
}

?>