<?php
/*
 * e107 website system
 *
 * Copyright (C) e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *	PM plugin - base class API
 *
 * $URL$
 * $Id$
 */


/**
 *	e107 Private messenger plugin
 *
 *	@package	e107_plugins
 *	@subpackage	pm
 *	@version 	$Id$;
 */

if (!defined('e107_INIT')) { exit; }

class private_message
{
	protected 	$e107;
	protected	$pmPrefs;


	/**
	 *	Constructor
	 *
	 *	@param array $prefs - pref settings for PM plugin
	 *	@return none
	 */
	public	function __construct($prefs)
	{
		$this->e107 = e107::getInstance();
		$this->pmPrefs = $prefs;
	}



	/**
	 *	Mark a PM as read
	 *	If flag set, send read receipt to sender
	 *
	 *	@param	int $pm_id - ID of PM
	 *	@param	array $pm_info - PM details
	 *
	 *	@return	none
	 *	
	 *	@todo - 'read_delete' pref doesn't exist - remove code? Or support?
	 */
	function pm_mark_read($pm_id, $pm_info)
	{
		$now = time();
		if($this->pmPrefs['read_delete'])
		{
			$this->del($pm_id);
		}
		else
		{
			$this->e107->sql->db_Select_gen("UPDATE `#private_msg` SET `pm_read` = {$now} WHERE `pm_id`=".intval($pm_id));
			if(strpos($pm_info['pm_option'], '+rr') !== FALSE)
			{
				$this->pm_send_receipt($pm_info);
			}
		}
	}


	/*
	 *	Get an existing PM
	 *
	 *	@param	int $pmid - ID of PM in DB
	 *
	 *	@return	boolean|array - FALSE on error, array of PM info on success
	 */
	function pm_get($pmid)
	{
		$qry = "
		SELECT pm.*, ut.user_image AS sent_image, ut.user_name AS sent_name, uf.user_image AS from_image, uf.user_name AS from_name, uf.user_email as from_email, ut.user_email as to_email  FROM #private_msg AS pm
		LEFT JOIN #user AS ut ON ut.user_id = pm.pm_to
		LEFT JOIN #user AS uf ON uf.user_id = pm.pm_from
		WHERE pm.pm_id='".intval($pmid)."'
		";
		if ($this->e107->sql->db_Select_gen($qry))
		{
			$row = $this->e107->sql->db_Fetch();
			return $row;
		}
		return FALSE;
	}


	/*
	 *	Send a PM
	 *
	 *	@param	array $vars	- PM information
	 *		['receipt'] - set TRUE if read receipt required
	 *		['uploaded'] - list of attachments (if any) - each is an array('size', 'name')
	 *		['to_userclass'] - set TRUE if sending to a user class
	 *		['to_array'] = array of recipients
	 *		['pm_userclass'] = target user class
	 *		['to_info'] = recipients array of array('user_id', 'user_class')
	 *
	 *		May also be an array as received from the generic table, if sending via a cron job
	 *			identified by the existence of $vars['pm_from']
	 *
	 *	@return	string - text detailing result
	 */
	function add($vars)
	{
		$tp = $this->e107->tp;
		$pmsize = 0;
		$attachlist = '';
		$pm_options = '';
		$ret = '';
		$addOutbox = TRUE;
		$maxSendNow = varset($this->pmPrefs['pm_max_send'],100);	// Maximum number of PMs to send without queueing them
		if (isset($vars['pm_from']))
		{	// Doing bulk send off cron task
			$info = array();
			foreach ($vars as $k => $v)
			{
				if (strpos($k, 'pm_') === 0)
				{
					$info[$k] = $v;
					unset($vars[$k]);
				}
			}
			$addOutbox = FALSE;			// Don't add to outbox - was done earlier
		}
		else
		{	// Send triggered by user - may be immediate or bulk dependent on number of recipients
			$vars['options'] = '';
			if(isset($vars['receipt']) && $vars['receipt']) {$pm_options .= '+rr+';	}
			if(isset($vars['uploaded']))
			{
				foreach($vars['uploaded'] as $u)
				{
					if (!isset($u['error']) || !$u['error'])
					{
						$pmsize += $u['size'];
						$a_list[] = $u['name'];
					}
				}
				$attachlist = implode(chr(0), $a_list);
			}
			$pmsize += strlen($vars['pm_message']);

			$pm_subject = trim($tp->toDB($vars['pm_subject']));
			$pm_message = trim($tp->toDB($vars['pm_message']));
			
			if (!$pm_subject && !$pm_message && !$attachlist)
			{  // Error - no subject, no message body and no uploaded files
				return LAN_PM_65;
			}
			
			// Most of the pm info is fixed - just need to set the 'to' user on each send
			$info = array(
				'pm_from' => $vars['from_id'],
				'pm_sent' => time(),					/* Date sent */
				'pm_read' => 0,							/* Date read */
				'pm_subject' => $pm_subject,
				'pm_text' => $pm_message,
				'pm_sent_del' => 1,						/* Set when can delete */
				'pm_read_del' => 0,						/* set when can delete */
				'pm_attachments' => $attachlist,
				'pm_option' => $pm_options,				/* Options associated with PM - '+rr' for read receipt */
				'pm_size' => $pmsize
				);
		}
		if(isset($vars['to_userclass']) || isset($vars['to_array']))
		{
			if(isset($vars['to_userclass']))
			{
				require_once(e_HANDLER.'userclass_class.php');
				$toclass = e107::getUserClass()->uc_get_classname($vars['pm_userclass']);
				$tolist = $this->get_users_inclass($vars['pm_userclass']);
				$ret .= LAN_PM_38.": {$vars['to_userclass']}<br />";
				$class = TRUE;
			}
			else
			{
				$tolist = $vars['to_array'];
				$class = FALSE;
			}
			// Sending multiple PMs here. If more than some number ($maxSendNow), need to split into blocks.
			if (count($tolist) > $maxSendNow)
			{
				$totalSend = count($tolist);
				$targets = array_chunk($tolist, $maxSendNow);		// Split into a number of lists, each with the maximum number of elements (apart from the last block, of course)
				unset($tolist);
				$array = new ArrayData;
				$pmInfo = $info;
				$genInfo = array(
					'gen_type' => 'pm_bulk',
					'gen_datestamp' => time(),
					'gen_user_id' => USERID,
					'gen_ip' => ''
					);
				for ($i = 0; $i < count($targets) - 1; $i++)
				{	// Save the list in the 'generic' table
					$pmInfo['to_array'] = $targets[$i];			// Should be in exactly the right format
					$genInfo['gen_intdata'] = count($targets[$i]);
					$genInfo['gen_chardata'] = $array->WriteArray($pmInfo,TRUE);
					$this->e107->sql->db_Insert('generic', array('data' => $genInfo, '_FIELD_TYPES' => array('gen_chardata' => 'string')));	// Don't want any of the clever sanitising now
				}
				$toclass .= ' ['.$totalSend.']';
				$tolist = $targets[count($targets) - 1];		// Send the residue now (means user probably isn't kept hanging around too long if sending lots)
				unset($targets);
			}
			foreach($tolist as $u)
			{
				set_time_limit(30);
				$info['pm_to'] = intval($u['user_id']);		// Sending to a single user now

				if($pmid = $this->e107->sql->db_Insert('private_msg', $info))
				{
					if($class == FALSE)
					{
						$toclass .= $u['user_name'].', ';
					}
					if(check_class($this->pmPrefs['notify_class'], $u['user_class']))
					{
						$vars['to_info'] = $u;
						$this->pm_send_notify($u['user_id'], $vars, $pmid, count($a_list));
					}
				}
				else
				{
					$ret .= LAN_PM_39.": {$u['user_name']} <br />";
				}
			}
			if ($addOutbox)
			{
				$info['pm_to'] = $toclass;		// Class info to put into outbox
				$info['pm_sent_del'] = 0;
				$info['pm_read_del'] = 1;
				if(!$pmid = $this->e107->sql->db_Insert('private_msg', $info))
				{
					$ret .= LAN_PM_41.'<br />';
				}
			}
			
		}
		else
		{	// Sending to a single person
			$info['pm_to'] = intval($vars['to_info']['user_id']);		// Sending to a single user now
			if($pmid = $this->e107->sql->db_Insert('private_msg', $info))
			{
				if(check_class($this->pmPrefs['notify_class'], $vars['to_info']['user_class']))
				{
					set_time_limit(30);
					$this->pm_send_notify($vars['to_info']['user_id'], $vars, $pmid, count($a_list));
				}
				$ret .= LAN_PM_40.": {$vars['to_info']['user_name']}<br />";
			}
		}
		return $ret;
	}



	/**
	 *	Delete a PM from a user's inbox/outbox.
	 *	PM is only actually deleted from DB once both sender and recipient have marked it as deleted
	 *	When physically deleted, any attachments are deleted as well
	 *
	 *	@param integer $pmid - ID of the PM
	 *	@param boolean $force - set to TRUE to force deletion of unread PMs
	 *	@return boolean|string - FALSE if PM not found, or other DB error. String if successful
	 */
	function del($pmid, $force = FALSE)
	{
		$pmid = (int)$pmid;
		$ret = '';
		$newvals = '';
		if($this->e107->sql->db_Select('private_msg', '*', 'pm_id = '.$pmid.' AND (pm_from = '.USERID.' OR pm_to = '.USERID.')'))
		{
			$row = $this->e107->sql->db_Fetch();
			if (!$force && ($row['pm_to'] == USERID))
			{
				$newvals = 'pm_read_del = 1';
				$ret .= LAN_PM_42.'<br />';
				if($row['pm_sent_del'] == 1) { $force = TRUE; }
			}
			if (!$force && ($row['pm_from'] == USERID))
			{
				if($newvals != '') { $force = TRUE; }
				$newvals = 'pm_sent_del = 1';
				$ret .= LAN_PM_43."<br />";
				if($row['pm_read_del'] == 1) { $force = TRUE; }
			}

			if($force == TRUE)
			{
				// Delete any attachments and remove PM from db
				$attachments = explode(chr(0), $row['pm_attachments']);
				$aCount = array(0,0);
				foreach($attachments as $a)
				{
					$a = trim($a);
					if ($a)
					{
						$filename = e_PLUGIN.'pm/attachments/'.$a;
						if (unlink($filename)) $aCount[0]++; else $aCount[1]++;
					}
				}
				if ($aCount[0] || $aCount[1])
				{
					$ret .= str_replace(array('--GOOD--', '--FAIL--'), $aCount, LAN_PM_71).'<br />';
				}
				$this->e107->sql->db_Delete('private_msg', 'pm_id = '.$pmid);
			}
			else
			{
				$this->e107->sql->db_Update('private_msg', $newvals.' WHERE pm_id = '.$pmid);
			}
			return $ret;
		}
		return FALSE;
	}

	/**
	 * Convinient url assembling shortcut
	 */
	public function url($action, $params = array(), $options = array())
	{
		if(strpos($action, '/') === false) $action = 'view/'.$action;
		$this->e107->url->create('pm/'.$action, $params, $options);
	}

	/**
	 *	Send an email to notify of a PM
	 *
	 *	@param int $uid - not used
	 *	@param array $pmInfo - PM details
	 *	@param int $pmid - ID of PM in database
	 *	@param int $attach_count - number of attachments
	 *
	 *	@return none
	 */
	function pm_send_notify($uid, $pmInfo, $pmid, $attach_count = 0)
	{
		require_once(e_HANDLER.'mail.php');
		$subject = LAN_PM_100.SITENAME;
		$pmlink = $this->url('show', 'id='.$pmid, 'full=1&encode=0');
		$txt = LAN_PM_101.SITENAME."\n\n";
		$txt .= LAN_PM_102.USERNAME."\n";
		$txt .= LAN_PM_103.$pmInfo['pm_subject']."\n";
		if($attach_count > 0)
		{
			$txt .= LAN_PM_104.$attach_count."\n";
		}
		$txt .= LAN_PM_105."\n".$pmlink."\n";
		sendemail($pmInfo['to_info']['user_email'], $subject, $txt, $pmInfo['to_info']['user_name']);
	}


	/**
	 *	Send PM read receipt
	 *
	 *	@param array $pmInfo - PM details
	 *
	 * 	@return none
	 */
	function pm_send_receipt($pmInfo)
	{
		require_once(e_HANDLER.'mail.php');
		$subject = LAN_PM_106.$pmInfo['sent_name'];
		$pmlink = $this->url('show', 'id='.$pmInfo['pm_id'], 'full=1&encode=0');
		$txt = str_replace("{UNAME}", $pmInfo['sent_name'], LAN_PM_107).date('l F dS Y h:i:s A')."\n\n";
		$txt .= LAN_PM_108.date('l F dS Y h:i:s A', $pmInfo['pm_sent'])."\n";
		$txt .= LAN_PM_103.$pmInfo['pm_subject']."\n";
		$txt .= LAN_PM_105."\n".$pmlink."\n";
		sendemail($pminfo['from_email'], $subject, $txt, $pmInfo['from_name']);
	}


	/**
	 *	Get list of users blocked from sending to a specific user ID.
	 *
	 *	@param integer $to - user ID
	 *
	 *	@return array of blocked users as user IDs
	 */
	function block_get($to = USERID)
	{
		$ret = array();
		$to = intval($to);		// Precautionary
		if ($this->e107->sql->db_Select('private_msg_block', 'pm_block_from', 'pm_block_to = '.$to))
		{
			while($row = $this->e107->sql->db_Fetch(MYSQL_ASSOC))
			{
				$ret[] = $row['pm_block_from'];
			}
		}
		return $ret;
	}


	/**
	 *	Get list of users blocked from sending to a specific user ID.
	 *
	 *	@param integer $to - user ID
	 *
	 *	@return array of blocked users, including specific user info
	 */
	function block_get_user($to = USERID)
	{
		$ret = array();
		$to = intval($to);		// Precautionary
		if ($this->e107->sql->db_Select_gen('SELECT pm.*, u.user_name FROM `#private_msg_block` AS pm LEFT JOIN `#user` AS u ON `pm`.`pm_block_from` = `u`.`user_id` WHERE pm_block_to = '.$to))
		{
			while($row = $this->e107->sql->db_Fetch(MYSQL_ASSOC))
			{
				$ret[] = $row;
			}
		}
		return $ret;
	}


	/**
	 *	Add a user block
	 *
	 *	@param int $from - sender to block
	 *	@param int $to - user doing the blocking
	 *
	 *	@return string result message
	 */
	function block_add($from, $to = USERID)
	{
		$from = intval($from);
		if($this->e107->sql->db_Select('user', 'user_name, user_perms', 'user_id = '.$from))
		{
			$uinfo = $this->e107->sql->db_Fetch();
			if (($uinfo['user_perms'] == '0') || ($uinfo['user_perms'] == '0.'))
			{  // Don't allow block of main admin
				return LAN_PM_64;
			}
		  
			if(!$this->e107->sql->db_Count('private_msg_block', '(*)', 'WHERE pm_block_from = '.$from." AND pm_block_to = '".$this->e107->tp->toDB($to)."'"))
			{
				if($this->e107->sql->db_Insert('private_msg_block', array(
						'pm_block_from' => $from,
						'pm_block_to' => $to,
						'pm_block_datestamp' => time()
					)))
				{
					return str_replace('{UNAME}', $uinfo['user_name'], LAN_PM_47);
				}
				else
				{
					return LAN_PM_48;
				}
			}
			else
			{
				return str_replace('{UNAME}', $uinfo['user_name'], LAN_PM_49);
			}
		}
		else
		{
			return LAN_PM_17;
		}
	}



	/**
	 *	Delete user block
	 *
	 *	@param int $from - sender to block
	 *	@param int $to - user doing the blocking
	 *
	 *	@return string result message
	 */
	function block_del($from, $to = USERID)
	{
		$from = intval($from);
		if($this->e107->sql->db_Select('user', 'user_name', 'user_id = '.$from))
		{
			$uinfo = $this->e107->sql->db_Fetch();
			if($this->e107->sql->db_Select('private_msg_block', 'pm_block_id', 'pm_block_from = '.$from.' AND pm_block_to = '.intval($to)))
			{
				$row = $this->e107->sql->db_Fetch();
				if($this->e107->sql->db_Delete('private_msg_block', 'pm_block_id = '.intval($row['pm_block_id'])))
				{
					return str_replace('{UNAME}', $uinfo['user_name'], LAN_PM_44);
				}
				else
				{
					return LAN_PM_45;
				}
			}
			else
			{
				return str_replace('{UNAME}', $uinfo['user_name'], LAN_PM_46);
			}
		}
		else
		{
			return LAN_PM_17;
		}
	}


	/**
	 *	Get user ID matching a name
	 *
	 *	@param string var - name to match
	 *
	 *	@return boolean|array - FALSE if no match, array of user info if found
	 */
	function pm_getuid($var)
	{
		$var = strip_if_magic($var);
		$var = str_replace("'", '&#039;', trim($var));		// Display name uses entities for apostrophe
		if($this->e107->sql->db_Select('user', 'user_id, user_name, user_class, user_email', "user_name LIKE '".$this->e107->sql -> escape($var, FALSE)."'"))
		{
			$row = $this->e107->sql->db_Fetch();
			return $row;
		}
		return FALSE;
	}


	/**
	 *	Get list of users in class
	 *
	 *	@param int $class - class ID
	 *
	 *	@return boolean|array - FALSE on error/none found, else array of user information arrays
	 */
	function get_users_inclass($class)
	{
		if($class == e_UC_MEMBER)
		{
			$qry = "SELECT user_id, user_name, user_email, user_class FROM `#user` WHERE 1";
		}
		elseif($class == e_UC_ADMIN)
		{
			$qry = "SELECT user_id, user_name, user_email, user_class FROM `#user` WHERE user_admin = 1";
		}
		elseif($class)
		{
			$regex = "(^|,)(".$this->e107->tp->toDB($class).")(,|$)";
			$qry = "SELECT user_id, user_name, user_email, user_class FROM `#user` WHERE user_class REGEXP '{$regex}'";
		}
		if($this->e107->sql->db_Select_gen($qry))
		{
			$ret = $this->e107->sql->db_getList();
			return $ret;
		}
		return FALSE;
	}


	/**
	 *	Get inbox - up to $limit messages from $from
	 *
	 *	@param int $uid - user ID
	 *	@param int $from - first message
	 *	@param int $limit - number of messages
	 *
	 *	@return boolean|array - FALSE if none found or error, array of PMs if available
	 */
	function pm_get_inbox($uid = USERID, $from = 0, $limit = 10)
	{
		$ret = array();
		$total_messages = 0;
		$uid = intval($uid);
		$limit = intval($limit);
		if ($limit < 2) { $limit = 10; }
		$from = intval($from);
		$qry = "
		SELECT SQL_CALC_FOUND_ROWS pm.*, u.user_image, u.user_name FROM `#private_msg` AS pm
		LEFT JOIN `#user` AS u ON u.user_id = pm.pm_from
		WHERE pm.pm_to='{$uid}' AND pm.pm_read_del=0
		ORDER BY pm.pm_sent DESC
		LIMIT ".$from.", ".$limit."
		";
		if($this->e107->sql->db_Select_gen($qry))
		{
			$total_messages = $this->e107->sql->total_results;		// Total number of messages
			$ret['messages'] = $this->e107->sql->db_getList();
		}
		$ret['total_messages'] = $total_messages;		// Should always be defined
		return $ret;
	}


	/**
	 *	Get outbox - up to $limit messages from $from
	 *
	 *	@param int $uid - user ID
	 *	@param int $from - first message
	 *	@param int $limit - number of messages
	 *
	 *	@return boolean|array - FALSE if none found or error, array of PMs if available
	 */
	function pm_get_outbox($uid = USERID, $from = 0, $limit = 10)
	{
		$ret = array();
		$total_messages = 0;
		$uid = intval($uid);
		$limit = intval($limit);
		if ($limit < 2) { $limit = 10; }
		$from = intval($from);
		$qry = "
		SELECT SQL_CALC_FOUND_ROWS pm.*, u.user_image, u.user_name FROM #private_msg AS pm
		LEFT JOIN #user AS u ON u.user_id = pm.pm_to
		WHERE pm.pm_from='{$uid}' AND pm.pm_sent_del=0
		ORDER BY pm.pm_sent DESC
		LIMIT ".$from.', '.$limit;
		if($this->e107->sql->db_Select_gen($qry))
		{
			$total_messages = $this->e107->sql->total_results;		// Total number of messages
			$ret['messages'] = $this->e107->sql->db_getList();
		}
		$ret['total_messages'] = $total_messages;
		return $ret;
	}


	/**
	 *	Send a file down to the user
	 *
	 *	@param	int $pmid - PM ID
	 *	@param	string $filenum - attachment number within the list associated with the PM
	 *
	 *	@return none
	 *
	 *	@todo Can we use core send routine?
	 */
	function send_file($pmid, $filenum)
	{
		$pm_info = $this->pm_get($pmid);
		$attachments = explode(chr(0), $pm_info['pm_attachments']);
		if(!isset($attachments[$filenum]))
		{
			return FALSE;
		}
		$fname = $attachments[$filenum];
		list($timestamp, $fromid, $rand, $file) = explode("_", $fname, 4);
		$filename = getcwd()."/attachments/{$fname}";

		if($fromid != $pm_info['pm_from'])
		{
			return FALSE;
		}
		if(!is_file($filename))
		{
			return FALSE;
		}
		@set_time_limit(10 * 60);
		@e107_ini_set("max_execution_time", 10 * 60);
		while (@ob_end_clean()); // kill all output buffering else it eats server resources
		if (connection_status() == 0)
		{
			if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
				$file = preg_replace('/\./', '%2e', $file, substr_count($file, '.') - 1);
			}
			if (isset($_SERVER['HTTP_RANGE']))
			{
				$seek = intval(substr($_SERVER['HTTP_RANGE'] , strlen('bytes=')));
			}
			$bufsize = 2048;
			ignore_user_abort(true);
			$data_len = filesize($filename);
			if ($seek > ($data_len - 1)) $seek = 0;
			$res =& fopen($filename, 'rb');
			if ($seek)
			{
				fseek($res , $seek);
			}
			$data_len -= $seek;
			header("Expires: 0");
			header("Cache-Control: max-age=30" );
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename={$file}");
			header("Content-Length: {$data_len}");
			header("Pragma: public");
			if ($seek)
			{
				header("Accept-Ranges: bytes");
				header("HTTP/1.0 206 Partial Content");
				header("status: 206 Partial Content");
				header("Content-Range: bytes {$seek}-".($data_len - 1)."/{$data_len}");
			}
			while (!connection_aborted() && $data_len > 0)
			{
				echo fread($res , $bufsize);
				$data_len -= $bufsize;
			}
			fclose($res);
		}
	}
}
