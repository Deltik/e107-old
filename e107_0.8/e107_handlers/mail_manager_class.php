<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2010 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Mailout - mail database API and utility routines
 *
 * $Source: /cvs_backup/e107_0.8/e107_handlers/mail_manager_class.php,v $
 * $Revision$
 * $Date$
 * $Author$
*/

/**
 * 
 *	@package     e107
 *	@subpackage	e107_handlers
 *	@version 	$Id$;
 *
 *	@todo - consider whether to extract links in text-only emails

This class isolates the caller from the underlying database used to buffer and send emails.
Also includes a number of useful routines

This is the 'day to day' module - there's an admin class which extends this one.

There are two parts to the database:
	a) Email body (including attachments etc)
	b) Target recipients - potentially including target-specific values to substitute

There is an option to override the style information sent if the email is to include 
theme-related information. Create file 'emailstyle.css' in the current theme directory, and this
will be included in preference to the current theme style.



Event Triggers generated
------------------------
	mailbounce - when an email bounce is received
	maildone - when the sending of a complete bulk email is complete (also does 'Notify' event)


Database tables
---------------
mail_recipients			- Details of individual recipients (targets) of an email
	mail_target_id		Unique ID for this target/email combination
	mail_recipient_id	User ID (if registered user), else zero
	mail_recipient_email Email address of recipient
	mail_recipient_name	Name of recipient
	mail_status			Status of this  entry - see define() statements below
	mail_detail_id		Email body link
	mail_send_date		Earliest date/time when email may be sent. Once mail sent, actual time/date of sending (or time of failure to send)
	mail_target_info	Array of target-specific info for substitution into email. Key is the code in the email body, value is the substitution

mail_content			- Details of the email to be sent to a number of people
	mail_source_id
	mail_content_status	Overall status of mailshot record - See define() statements below
	mail_togo_count		Number of recipients to go
	mail_sent_count		Number of successful sends (including bounces)
	mail_fail_count		Number of unsuccessful sends
	mail_bounce_count	Number of bounced emails 
	mail_start_send		Time/date of sending first email
	mail_end_send		Time/date of sending last email
	mail_create_date
	mail_creator		User ID
	mail_create_app		ID string for application/plugin creating mail
	mail_e107_priority	Our internal priority - generally high for single emails, low for bulk emails
	mail_notify_complete Notify options when email complete
	mail_last_date		Don't send after this date/time
	mail_title			A description of the mailout - not sent
	mail_subject		Subject line
	mail_body			Body text
	mail_other			Evaluates to an array of misc info - cc, bcc, attachments etc


Within internal arrays, a flat structure is adopted. Variables relating to DB values all begin 'mail_' - others are internal (volatile) control variables

*/

if (!defined('e107_INIT')) { exit; }

define('MAIL_STATUS_SENT', 0);			// Mail sent. Email handler happy, but may have bounced (or may be yet to bounce)
define('MAIL_STATUS_BOUNCED', 1);
define('MAIL_STATUS_CANCELLED', 2);
define('MAIL_STATUS_PARTIAL', 3);		// A run which was abandoned - errors, out of time etc
define('MAIL_STATUS_FAILED', 5);		// Failure on initial send - rejected by selected email handler
										// This must be the numerically highest 'processing complete' code
define('MAIL_STATUS_PENDING', 10);		// Mail which is in the sending list (even if outside valid sending window)
										// This must be the numerically lowest 'not sent' code
										// E107_EMAIL_MAX_TRIES values used in here for retry counting
define('MAIL_STATUS_MAX_ACTIVE', 19);	// Highest allowable 'not sent or processed' code
define('MAIL_STATUS_SAVED', 20);		// Identifies an email which is just saved (or in process of update)
define('MAIL_STATUS_HELD',21);			// Held pending release
define('MAIL_STATUS_TEMP', 22);			// Tags entries which aren't yet in any list


class e107MailManager
{
	const	E107_EMAIL_PRIORITY_LOW = 1;		// 'E107' priorities, to determine what to do next.
	const	E107_EMAIL_PRIORITY_MED = 3;		// Distinct from the priority which can be assigned to the...
	const	E107_EMAIL_PRIORITY_HIGH = 5;		// actual email when sending. Use LOW or MED for bulk mail, HIGH for individual emails.
	
	const	E107_EMAIL_MAX_TRIES = 3;			// Maximum number of tries by us (mail server may do more)
												// - max allowable value is MAIL_STATUS_MAX_ACTIVE - MAIL_STATUS_PENDING 

	private		$debugMode = 0;
	protected	$e107;
	protected	$db = NULL;					// Use our own database object - this one for reading data
	protected	$db2 = NULL;				// Use our own database object - this one for updates
	protected	$queryActive = FALSE;		// Keeps track of unused records in currently active query
	protected	$mailCounters = array();	// Counters to track adding recipients
	protected	$queryCount = array();		// Stores total number of records if SQL_CALC_ROWS is used (index = db object #)
	protected	$currentBatchInfo = array();	// Used during batch send to hold info about current mailout
	protected	$currentMailBody = '';			// Buffers current mail body

	protected	$mailer = NULL;				// Mailer class when required

	// Array defines DB types to be used
	protected	$dbTypes = array(
		'mail_recipients' => array
		(
			'mail_target_id'  	=> 'int',
			'mail_recipient_id' => 'int',
			'mail_recipient_email' => 'todb',
			'mail_recipient_name' => 'todb',
			'mail_status' 		=> 'int',
			'mail_detail_id' 	=> 'int',
			'mail_send_date' 	=> 'int',
			'mail_target_info'	=> 'string'			// Don't want entities here!
		),
		'mail_content' => array(
			'mail_source_id' 	=> 'int',
			'mail_content_status' => 'int',
			'mail_togo_count' 	=> 'int',
			'mail_sent_count' 	=> 'int',
			'mail_fail_count' 	=> 'int',
			'mail_bounce_count' => 'int',
			'mail_start_send' 	=> 'int',
			'mail_end_send' 	=> 'int',
			'mail_create_date' 	=> 'int',
			'mail_creator' 		=> 'int',
			'mail_create_app' 	=> 'todb',
			'mail_e107_priority' => 'int',
			'mail_notify_complete' => 'int',
			'mail_last_date' 	=> 'int',
			'mail_title' 		=> 'todb',
			'mail_subject' 		=> 'todb',
			'mail_body' 		=> 'todb',
			'mail_other' 		=> 'string'			// Don't want entities here!
		)
	);
	
	// Array defines defaults for 'NOT NULL' fields where a default can't be set in the field definition
	protected	$dbNull = array('mail_recipients' => array
		(
			'mail_target_info' => ''
		),
		'mail_content' => array(
			'mail_body' => '',
			'mail_other' => ''
		)
	);

	// List of fields which are combined into the 'mail_other' field of the email
	protected	$dbOther = array(
					'mail_sender_email' => 1,
					'mail_sender_name'	=> 1,
					'mail_copy_to'		=> 1,
					'mail_bcopy_to'		=> 1,
					'mail_attach'		=> 1,
					'mail_send_style'	=> 1,
					'mail_selectors'	=> 1,			// Only used internally
					'mail_include_images' => 1			// Used to determine whether to embed images, or link to them
		);
	
	// List of fields which are the status counts of an email, and their titles
	protected	$mailCountFields = array(
			'mail_togo_count' 	=> LAN_MAILOUT_83,
			'mail_sent_count' 	=> LAN_MAILOUT_82,
			'mail_fail_count' 	=> LAN_MAILOUT_128,
			'mail_bounce_count' => LAN_MAILOUT_144,
		);

	/**
	 * Constructor
	 * 
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->e107 = e107::getInstance();
	}


	/**
	 * Generate an array of data which can be passed directly to the DB routines.
	 * Only valid DB fields are copied
	 * Combining/splitting of fields is done as necessary
	 * (This is essentially the translation between internal storage format and db storage format. If
	 * the DB format changes, only this routine and its counterpart should need changing)
	 *
	 * @param $data - array of email-related data in internal format
	 * @param $addMissing - if TRUE, undefined fields are added
	 *
	 * @return void
	 */
	public function mailToDb(&$data, $addMissing = FALSE)
	{
		$res = array();
		$res1 = array();
		// Generate the 'mail_other' array first
		foreach ($this->dbOther as $f => $v)
		{
			if (isset($data[$f]))
			{
				$res1[$f] = $data[$f];
			}
			elseif ($addMissing)
			{
				$res1[$f] = '';
			}
		}
		
		// Now do the main email array
		foreach ($this->dbTypes['mail_content'] as $f => $v)
		{
			if (isset($data[$f]))
			{
				$res[$f] = $data[$f];
			}
			elseif ($addMissing)
			{
				$res[$f] = '';
			}
		}
		$array = new ArrayData;
		$res['mail_other'] = $array->WriteArray($res1, TRUE);	// Ready to write to DB
		return $res;
	}


	/**
	 * Given an array (row) of data retrieved from the DB table, converts to internal format.
	 * Combining/splitting of fields is done as necessary
	 * (This is essentially the translation between internal storage format and db storage format. If
	 * the DB format changes, only this routine and its counterpart should need changing)
	 *
	 * @param $data - array of DB-sourced email-related data
	 * @param $addMissing - if TRUE, undefined fields are added
	 *
	 * @return array of data
	 */
	public function dbToMail(&$data, $addMissing = FALSE)
	{
		$res = array();
		
		foreach ($this->dbTypes['mail_content'] as $f => $v)
		{
			if (isset($data[$f]))
			{
				$res[$f] = $data[$f];
			}
			elseif ($addMissing)
			{
				$res[$f] = '';
			}
		}

		if (isset($data['mail_other']))
		{
			$array = new ArrayData;
			$tmp = $array->ReadArray($data['mail_other']);
			if (is_array($tmp))
			{
				$res = array_merge($res,$tmp);
			}
			unset($res['mail_other']);
		}
		elseif ($addMissing)
		{
			foreach ($this->dbOther as $f => $v)
			{
				$res[$f] = '';
			}
		}
		return $res;
	}



	/**
	 * Generate an array of mail recipient data which can be passed directly to the DB routines.
	 * Only valid DB fields are copied
	 * Combining/splitting of fields is done as necessary
	 * (This is essentially the translation between internal storage format and db storage format. If
	 * the DB format changes, only this routine and its counterpart should need changing)
	 *
	 * @param $data - array of email target-related data in internal format
	 * @param $addMissing - if TRUE, undefined fields are added
	 *
	 * @return void
	 */
	public function targetToDb(&$data, $addMissing = FALSE)
	{	// Direct correspondence at present (apart from needing to convert potential array $data['mail_target_info']) - but could change
		$res = array();
		foreach ($this->dbTypes['mail_recipients'] as $f => $v)
		{
			if (isset($data[$f]))
			{
				$res[$f] = $data[$f];
			}
			elseif ($addMissing)
			{
				$res[$f] = '';
			}
		}
		if (isset($data['mail_target_info']) && is_array($data['mail_target_info']))
		{
			$array = new ArrayData;
			$tmp = $array->WriteArray($data['mail_target_info'], TRUE);
			$res['mail_target_info'] = $tmp;
		}
		return $res;
	}



	/**
	 * Given an array (row) of data retrieved from the DB table, converts to internal format.
	 * Combining/splitting of fields is done as necessary
	 * (This is essentially the translation between internal storage format and db storage format. If
	 * the DB format changes, only this routine and its counterpart should need changing)
	 *
	 * @param $data - array of DB-sourced target-related data
	 * @param $addMissing - if TRUE, undefined fields are added
	 *
	 * @return void
	 */
	public function dbToTarget(&$data, $addMissing = FALSE)
	{	// Direct correspondence at present - but could change
		$res = array();
		foreach ($this->dbTypes['mail_recipients'] as $f => $v)
		{
			if (isset($data[$f]))
			{
				$res[$f] = $data[$f];
			}
			elseif ($addMissing)
			{
				$res[$f] = '';
			}
		}
		if (isset($data['mail_target_info']))
		{
			$array = new ArrayData;
			$tmp = $array->ReadArray($data['mail_target_info']);
			$res['mail_target_info'] = $tmp;
		}
		return $res;
	}




	/**
	 * Given an array (row) of data retrieved from the DB table, converts to internal format.
	 * Combining/splitting of fields is done as necessary
	 * This version intended for 'Joined' reads which have both recipient and content data
	 *
	 * @param $data - array of DB-sourced target-related data
	 * @param $addMissing - if TRUE, undefined fields are added
	 *
	 * @return void
	 */
	public function dbToBoth(&$data, $addMissing = FALSE)
	{	
		$res = array();
		$oneToOne = array_merge($this->dbTypes['mail_content'], $this->dbTypes['mail_recipients']);		// List of valid elements

		// Start with simple 'one to one' fields
		foreach ($oneToOne as $f => $v)
		{
			if (isset($data[$f]))
			{
				$res[$f] = $data[$f];
			}
			elseif ($addMissing)
			{
				$res[$f] = '';
			}
		}

		// Now array fields
		$array = new ArrayData;
		if (isset($data['mail_other']))
		{
			$tmp = $array->ReadArray($data['mail_other']);
			if (is_array($tmp))
			{
				$res = array_merge($res,$tmp);
			}
			unset($res['mail_other']);
		}
		elseif ($addMissing)
		{
			foreach ($this->dbOther as $f => $v)
			{
				$res[$f] = '';
			}
		}
		if (isset($data['mail_target_info']))
		{
			$tmp = $array->ReadArray($data['mail_target_info']);
			$res['mail_target_info'] = $tmp;
		}
		return $res;
	}




	/**
	 * Set the internal debug/logging level
	 *
	 * @return void
	 */
	public function controlDebug($level = 0)
	{
		$this->debugMode = $level;
	}


	// Internal function to create a db object for our use if none exists
	protected function checkDB($which = 1)
	{
		if (($which == 1) && ($this->db == NULL))
		{
			$this->db = new db;
		}
		if (($which == 2) && ($this->db2 == NULL))
		{
			$this->db2 = new db;
		}
	}


	// Internal function to create a mailer object for our use if none exists
	protected function checkMailer()
	{
		if ($this->mailer != NULL) return;
		if (!class_exists('e107Email'))
		{
			require_once(e_HANDLER.'mail.php');
		}
		$this->mailer = new e107Email;		// Could add in overrides here
	}



	/**
	 * Convert numeric represntation of mail status to a text string
	 * 
	 * @param integer $status - numeric value of status
	 * @return string text value
	 */
	public function statusToText($status)
	{
		switch (intval($status))
		{
			case MAIL_STATUS_SENT :
				return LAN_MAILOUT_211;
			case MAIL_STATUS_BOUNCED :
				return LAN_MAILOUT_213;
			case MAIL_STATUS_CANCELLED :
				return LAN_MAILOUT_218;
			case MAIL_STATUS_PARTIAL :
				return LAN_MAILOUT_219;
			case MAIL_STATUS_FAILED :
				return LAN_MAILOUT_212;
			case MAIL_STATUS_PENDING :
				return LAN_MAILOUT_214;
			case MAIL_STATUS_SAVED :
				return LAN_MAILOUT_215;
			case MAIL_STATUS_HELD :
				return LAN_MAILOUT_217;
			default :
				if (($status > MAIL_STATUS_PENDING) && ($status <= MAIL_STATUS_ACTIVE)) return LAN_MAILOUT_214;
		}
		return LAN_MAILOUT_216.' ('.$status.')';		// General coding error
	}



	/**
	 * Select the next $count emails in the send queue
	 * $count gives the maximum number. '*' does 'select all'
	 * @return boolean|handle Returns FALSE on error.
	 * 		 Returns a 'handle' on success (actually the ID in the DB of the email)
	 */
	public function selectEmails($count = 1)
	{
		if (is_numeric($count))
		{
			if ($count < 1) $count = 1;
			$count = ' LIMIT '.$count;
		}
		else
		{
			$count = '';
		}
		$this->checkDB(1);			// Make sure DB object created
		$query = "SELECT mt.*, ms.* FROM `#mail_recipients` AS mt
							LEFT JOIN `#mail_content` AS ms ON mt.`mail_detail_id` = ms.`mail_source_id`
							WHERE ms.`mail_content_status` = ".MAIL_STATUS_PENDING." 
							AND mt.`mail_status` >= ".MAIL_STATUS_PENDING." 
							AND mt.`mail_status` <= ".MAIL_STATUS_MAX_ACTIVE." 
							AND mt.`mail_send_date` <= ".time()." 
							AND (ms.`mail_last_date` >= ".time()." OR ms.`mail_last_date`=0)
							ORDER BY ms.`mail_e107_priority` DESC {$count}";
//		echo $query.'<br />';
		$result = $this->db->db_Select_gen($query);
		if ($result !== FALSE)
		{
			$this->queryActive = $result;		// Note number of emails to go
		}
		return $result;
	}


	/**
	 * Get next email from selection
	 * @return Returns array of email data if available - FALSE if no further data, no active query, or other error
	 */
	public function getNextEmail()
	{
		if (!$this->queryActive)
		{
			return FALSE;
		}
		if ($result = $this->db->db_Fetch(MYSQL_ASSOC))
		{
			$this->queryActive--;
			return $this->dbToBoth($result);
//			return array_merge($this->dbToMail($result), $this->dbToTarget($result));
		}
		else
		{
			$this->queryActive = FALSE;		// Make sure no further attempts to read emails
			return FALSE;
		}
	}


	/**
	 * Call to see whether any emails left to try in current selection
	 * @return Returns number left unread in query - FALSE if no active query
	 */
	public function emailsToGo()
	{
		return $this->queryActive;			// Just return saved number
	}


	/**
	 * Call to send next email from selection
	 * 
	 * @return Returns TRUE if successful, FALSE on fail (or no more to go)
	 */
	public function sendNextEmail()
	{
		$counterList = array('mail_source_id','mail_togo_count', 'mail_sent_count', 'mail_fail_count', 'mail_start_send');

		if (($email = $this->getNextEmail()) === FALSE)
		{
			return FALSE;
		}

		if (count($this->currentBatchInfo))
		{
			//print_a($this->currentBatchInfo);
			if ($this->currentBatchInfo['mail_source_id'] != $email['mail_source_id'])
			{	// New email body etc started
				//echo "New email body: {$this->currentBatchInfo['mail_source_id']} != {$email['mail_source_id']}<br />";
				$this->currentBatchInfo = array();		// New source email - clear stored info
				$this->currentMailBody = '';			// ...and clear cache for message body
			}
		}
		if (count($this->currentBatchInfo) == 0)
		{
			//echo "First email of batch: {$email['mail_source_id']}<br />";
			foreach ($counterList as $k)
			{
				$this->currentBatchInfo[$k] = $email[$k];		// This copies across all the counts
			}
		}

		if (($this->currentBatchInfo['mail_sent_count'] > 0) || ($this->currentBatchInfo['mail_fail_count'] > 0))
		{	// Only send these on first email - otherwise someone could get inundated!
			unset($email['mail_copy_to']);
			unset($email['mail_bcopy_to']);
		}

		$targetData = array();		// Arrays for updated data

		$this->checkMailer();		// Make sure we have a mailer object to play with

		if ($this->currentBatchInfo['mail_start_send'] == 0)
		{
			$this->currentBatchInfo['mail_start_send'] = time();			// Log when we started processing this email
		}

		if (!$this->currentMailBody)
		{
			$this->currentMailBody = $this->makeEmailBody($email['mail_body'], $email['mail_send_style'], varset($email['mail_include_images'], FALSE));
		}
		// Do any substitutions
		$search = array();
		$replace = array();
		foreach ($email['mail_target_info'] as $k => $v)
		{
			$search[] = '|'.$k.'|';
			$replace[] = $v;
		}
		$email['mail_body'] = str_replace($search, $replace, $this->currentMailBody);
		$email['send_html'] = ($email['mail_send_style'] != 'textonly');
		
		// Set up any extra mailer parameters that need it
		if (!vartrue($email['e107_header']))
		{
			$temp = intval($email['mail_recipient_id']).'/'.intval($email['mail_source_id']).'/'.intval($email['mail_target_id']).'/';
			$email['e107_header'] = $temp.md5($temp);		// Set up an ID
		}
		if (isset($email['mail_attach']) && (trim($email['mail_attach']) || is_array($email['mail_attach'])))
		{
			$downDir = realpath(e_ROOT.$this->e107->getFolder('downloads'));
			if (is_array($email['mail_attach']))
			{
				foreach ($email['mail_attach'] as $k => $v)
				{
					$email['mail_attach'][$k] = $downDir.$v;
				}
			}
			else
			{
				$email['mail_attach'] = $downDir.$email['mail_attach'];
			}
		}

//		print_a($email);

		// Try and send
		$result = $this->mailer->sendEmail($email['mail_recipient_email'], $email['mail_recipient_name'], $email, TRUE);

//		return;			// ************************************************** Temporarily stop DB being updated when line active *****************************
		
		$this->checkDB(2);			// Make sure DB object created

		// Now update email status in DB. We just create new arrays of changed data
		if ($result === TRUE)
		{	// Success!
			$targetData['mail_status'] = MAIL_STATUS_SENT;
			$targetData['mail_send_date'] = time();
			$this->currentBatchInfo['mail_togo_count']--;
			$this->currentBatchInfo['mail_sent_count']++;
		}
		else
		{	// Failure
		// If fail and still retries, downgrade priority
			if ($targetData['mail_status'] > MAIL_STATUS_PENDING)
			{
				$targetData['mail_status'] = max($targetData['mail_status'] - 1, MAIL_STATUS_PENDING);		// One off retry count
				$targetData['mail_e107_priority'] = max($email['mail_e107_priority'] - 1, 1); 	// Downgrade priority to avoid clag-ups
			}
			else
			{
				$targetData['mail_status'] = MAIL_STATUS_FAILED;
				$this->currentBatchInfo['mail_togo_count'] = max($this->currentBatchInfo['mail_togo_count'] - 1, 0);
				$this->currentBatchInfo['mail_fail_count']++;
				$targetData['mail_send_date'] = time();
			}
		}
		
		if (isset($this->currentBatchInfo['mail_togo_count']) && ($this->currentBatchInfo['mail_togo_count'] == 0))
		{
			$this->currentBatchInfo['mail_end_send'] = time();
			$this->currentBatchInfo['mail_content_status'] = MAIL_STATUS_SENT;
		}

		// Update DB record, mail record with status (if changed). Must use different sql object
		if (count($targetData))
		{
			//print_a($targetData);
			$this->db2->db_Update('mail_recipients', array('data' => $targetData, 
														'_FIELD_TYPES' => $this->dbTypes['mail_recipients'], 
														'WHERE' => '`mail_target_id` = '.intval($email['mail_target_id'])));
		}
		if (count($this->currentBatchInfo))
		{
			//print_a($this->currentBatchInfo);
			$this->db2->db_Update('mail_content', array('data' => $this->currentBatchInfo, 
														'_FIELD_TYPES' => $this->dbTypes['mail_content'], 
														'WHERE' => '`mail_source_id` = '.intval($email['mail_source_id'])));
		}

		if (($this->currentBatchInfo['mail_togo_count'] == 0) && ($email['mail_notify_complete'] > 0))
		{	// Need to notify completion
			$email = array_merge($email, $this->currentBatchInfo);		// This should ensure the counters are up to date
			$mailInfo = LAN_MAILOUT_247.'<br />'.LAN_MAILOUT_135.': '.$email['mail_title'].'<br />'.LAN_MAILOUT_248.$this->statusToText($email['mail_content_status']).'<br />';
			$mailInfo .= '<br />'.LAN_MAILOUT_249.'<br />';
			foreach ($this->mailCountFields as $f => $t)
			{
				$mailInfo .= $t.' => '.$email[$f].'<br />';
			}
			$mailInfo .= LAN_MAILOUT_250;
			$message = array(				// Use same structure for email and notify
					'mail_subject' => LAN_MAILOUT_244.$email['mail_subject'],
					'mail_body' => $mailInfo.'<br />'
				);

			if ($email['mail_notify_complete'] & 1)
			{	// Notify email initiator
				if ($this->db2->db_Select('user', 'user_name, user_email', '`user_id`='.intval($email['mail_creator'])))
				{
					$row = $this->db2->db_Fetch(MYSQL_ASSOC);
					require_once(e_HANDLER.'mail.php');
					$mailer = new e107Email();
					$mailer->sendEmail($row['user_name'], $row['user_email'], $message,FALSE);
				}
			}
			if ($email['mail_notify_complete'] & 2)
			{	// Do e107 notify
				require_once(e_HANDLER."notify_class.php");
				notify_maildone($message);
			}
			e107::getEvent()->trigger('maildone', $email);
		}

		return $result;
	}



	/**
	 * Call to do a number of 'units' of email processing - from a cron job, for example
	 * Each 'unit' sends one email from the queue - potentially it could do some other task.
	 * @param $limit - number of units of work to do - zero to clear the queue (or do maximum allowed by a hard-coded limit)
	 * @return None
	 */
	public function doEmailTask($limit = 0)
	{
		if ($count = $this->selectEmails($limit))
		{
			while ($count > 0)
			{
				$this->sendNextEmail();
				$count--;
			}
			if ($this->mailer)
			{
				$this->mailer->allSent();		// Tidy up on completion
			}
		}
	}



	/**
	 * Saves an email to the DB
	 * @param $emailData
	 * @param $isNew - TRUE if a new email, FALSE if editing
	 *
	 *
	 * @return mail ID for success, FALSE on error
	 */
	public function saveEmail($emailData, $isNew = FALSE)
	{
		$this->checkDB(2);						// Make sure we have a DB object to use

		$dbData = $this->mailToDB($emailData, FALSE);		// Convert array formats
//		print_a($dbData);

		if ($isNew)
		{
			unset($dbData['mail_source_id']);				// Just in case - there are circumstances where might be set
			$result = $this->db2->db_Insert('mail_content', array('data' => $dbData, 
														'_FIELD_TYPES' => $this->dbTypes['mail_content'], 
														'_NOTNULL' => $this->dbNull['mail_content']));
		}
		else
		{
			if (isset($dbData['mail_source_id']))
			{
				$result = $this->db2->db_Update('mail_content', array('data' => $dbData, 
																	'_FIELD_TYPES' => $this->dbTypes['mail_content'], 
																	'WHERE' => '`mail_source_id` = '.intval($dbData['mail_source_id'])));
				if ($result !== FALSE) { $result = $dbData['mail_source_id']; }
			}
			else
			{
				echo "Programming bungle! No mail_source_id in function saveEmail()<br />";
				$result = FALSE;
			}
		}
		return $result;
	}


	/**
	 * Retrieve an email from the DB
	 * @param $mailID - number for email (assumed to be integral)
	 * @param $addMissing - if TRUE, any unset fields are added
	 *
	 * @return FALSE on error. Array of data on success.
	 */
	public function retrieveEmail($mailID, $addMissing = FALSE)
	{
		if (!is_numeric($mailID) || ($mailID == 0))
		{
			return FALSE;
		}
		$this->checkDB(2);						// Make sure we have a DB object to use
		if ($this->db2->db_Select('mail_content', '*', '`mail_source_id`='.$mailID) === FALSE)
		{
			return FALSE;
		}
		$mailData = $this->db2->db_Fetch(MYSQL_ASSOC);
		return $this->dbToMail($mailData, $addMissing);				// Convert to 'flat array' format
	}


	/**
	 * Delete an email from the DB, including (potential) recipients
	 * @param $mailID - number for email (assumed to be integral)
	 * @param $actions - allows selection of which DB to delete from
	 *
	 * @return FALSE on code error. Array of results on success.
	 */
	public function deleteEmail($mailID, $actions='all')
	{
		$result = array();
		if ($actions == 'all') $actions = 'content,recipients';
		$actArray = explode(',', $actions);
		if (!is_numeric($mailID) || ($mailID == 0))
		{
			return FALSE;
		}

		$this->checkDB(2);						// Make sure we have a DB object to use
		if (isset($actArray['content']))
		{
			$result['content'] = $this->db2->db_Delete('mail_content', '`mail_source_id`='.$mailID);
		}
		if (isset($actArray['recipients']))
		{
			$result['recipients'] = $this->db2->db_Delete('mail_recipients', '`mail_detail_id`='.$mailID);
		}
		return $result;
	}



	/**
	 * Initialise a set of counters prior to adding 
	 * @param $handle - as returned by makeEmail()
	 * @return none
	 */
	public function mailInitCounters($handle)
	{
		$this->mailCounters[$handle] = array('add' => 0, 'dups' => 0, 'dberr' => 0);
	}



	/**
	 * Add a recipient to the DB, provide that email not already on the list. 
	 * @param $handle - as returned by makeEmail()
	 * @param $mailRecip is an array of relevant info
	 * @param $priority - 'E107' priority for email (different to the priority included in the email)
	 * @return mixed - FALSE if error
	 *                 'dup' if duplicate of existing email
	 *                 integer - number of email recipient in DB
	 */
	public function mailAddNoDup($handle, $mailRecip, $initStatus = MAIL_STATUS_TEMP, $priority = self::E107_EMAIL_PRIORITY_LOW)
	{
		if (($handle <= 0) || !is_numeric($handle)) return FALSE;
		if (!isset($this->mailCounters[$handle])) return 'nocounter';
		$this->checkDB(1);			// Make sure DB object created
		$result = $this->db->db_Select('mail_recipients', 'mail_target_id', "`mail_detail_id`={$handle} AND `mail_recipient_email`='{$mailRecip['mail_recipient_email']}'");
		if ($result === FALSE)
		{
			return FALSE;
		}
		elseif ($result != 0)
		{
			$this->mailCounters[$handle]['dups']++;
			return 'dup';
		}
		$mailRecip['mail_status'] = $initStatus;
		$mailRecip['mail_detail_id'] = $handle;
		$mailRecip['mail_send_date'] = time();
		$data = $this->targetToDb($mailRecip);							// Convert internal types
		if ($this->db->db_Insert('mail_recipients', array('data' => $data, '_FIELD_TYPES' => $this->dbTypes['mail_recipients'])))
		{
			$this->mailCounters[$handle]['add']++;
		}
		else
		{
			$this->mailCounters[$handle]['dberr']++;
			return FALSE;
		}
	}


	/**
	 * Update the mail record with the number of recipients as per counters
	 * @param $handle - as returned by makeEmail()
	 * @return mixed - FALSE if error
	 *					- number set into counter if success
	 */
	public function mailUpdateCounters($handle)
	{
		if (($handle <= 0) || !is_numeric($handle)) return FALSE;
		if (!isset($this->mailCounters[$handle])) return 'nocounter';
		$this->checkDB(2);			// Make sure DB object created
		$query = '`mail_togo_count`='.intval($this->mailCounters[$handle]['add']).' WHERE `mail_source_id`='.$handle;
		if ($this->db2->db_Update('mail_content', $query))
		{
			return $this->mailCounters[$handle]['add'];
		}
		return FALSE;
	}
	
	
	
	/**
	 * Retrieve the counters for a mail record
	 * @param $handle - as returned by makeEmail()
	 * @return boolean - FALSE if error
	 *					- array of counters if success
	 */
	public function mailRetrieveCounters($handle)
	{
		if (isset($this->mailCounters[$handle]))
		{
			return $this->mailCounters[$handle];
		}
		return FALSE;
	}



	/**
	 * Update status for email, including all recipient entries (called once all recipients added)
	 * @param int $handle - as returned by makeEmail()
	 * @param $hold boolean - TRUE to set status to held, false to release for sending
	 * @param $notify - value to set in the mail_notify_complete field:
	 *			0 - no action on run complete
	 *			1 - notify admin who sent email only
	 *			2 - notify through e107 notify system only
	 *			3 - notify both
	 * @param $firstTime int - only valid if $hold === FALSE - earliest time/date when email may be sent
	 * @param $lastTime int - only valid if $hold === FALSE - latest time/date when email may be sent
	 * @return boolean TRUE on no errors, FALSE on errors
	 */
	public function activateEmail($handle, $hold = FALSE, $notify = 0, $firstTime = 0, $lastTime = 0)
	{
		if (($handle <= 0) || !is_numeric($handle)) return FALSE;
		$this->checkDB(1);			// Make sure DB object created
		$ft = '';
		$lt = '';
		if (!$hold)
		{		// Sending email - set sensible first and last times
			if ($lastTime < (time() + 3600))				// Force at least an hour to send emails
			{
				if ($firstTime < time())
				{
					$lastTime = time() + 86400;			// Standard delay - 24 hours
				}
				else
				{
					$lastTime = $firstTime + 86400;
				}
			}
			if ($firstTime > 0) $ft = ', `mail_send_date` = '.$firstTime;
			$lt = ', `mail_end_send` = '.$lastTime;
		}
		$query = '';
		if (!$hold) $query = '`mail_creator` = '.USERID.', `mail_create_date` = '.time().', ';		// Update when we send - might be someone different
		$query .= '`mail_notify_complete`='.intval($notify).', `mail_content_status` = '.($hold ? MAIL_STATUS_HELD : MAIL_STATUS_PENDING).$lt.' WHERE `mail_source_id` = '.intval($handle);
		//	echo "Update mail body: {$query}<br />";
		// Set status of email body first
		if (!$this->db->db_Update('mail_content',$query))
		{
			$this->e107->admin_log->e_log_event(10,-1,'MAIL','Activate/hold mail','mail_content: '.$query.'[!br!]Fail: '.$this->db->mySQLlastErrText,FALSE,LOG_TO_ROLLING);
			return FALSE;
		}
		// Now set status of individual emails
		$query = '`mail_status` = '.($hold ? MAIL_STATUS_HELD : (MAIL_STATUS_PENDING + e107MailManager::E107_EMAIL_MAX_TRIES)).$ft.' WHERE `mail_detail_id` = '.intval($handle);
		//	echo "Update individual emails: {$query}<br />";
		if (FALSE === $this->db->db_Update('mail_recipients',$query))
		{
			$this->e107->admin_log->e_log_event(10,-1,'MAIL','Activate/hold mail','mail_recipient: '.$query.'[!br!]Fail: '.$this->db->mySQLlastErrText,FALSE,LOG_TO_ROLLING);
			return FALSE;
		}
		return TRUE;
	}


	/**
	 * Cancel sending of an email, including marking all unsent recipient entries
	 * $handle - as returned by makeEmail()
	 * @return boolean - TRUE on success, FALSE on failure
	 */
	public function cancelEmail($handle)
	{
		if (($handle <= 0) || !is_numeric($handle)) return FALSE;
		$this->checkDB(1);			// Make sure DB object created
		// Set status of individual emails first, so we can get a count
		if (FALSE === ($count = $this->db->db_Update('mail_recipients','`mail_status` = '.MAIL_STATUS_CANCELLED.' WHERE `mail_detail_id` = '.intval($handle).' AND `mail_status` >'.MAIL_STATUS_FAILED)))
		{
			return FALSE;
		}
		// Now do status of email body - no emails to go, add those not sent to fail count
		if (!$this->db->db_Update('mail_content','`mail_content_status` = '.MAIL_STATUS_PARTIAL.', `mail_togo_count`=0, `mail_fail_count` = `mail_fail_count` + '.intval($count).' WHERE `mail_source_id` = '.intval($handle)))
		{
			return FALSE;
		}
		return TRUE;
	}


	/**
	 * Put email on hold, including marking all unsent recipient entries
	 * @param integer $handle - as returned by makeEmail()
	 * @return boolean - TRUE on success, FALSE on failure
	 */
	public function holdEmail($handle)
	{
		if (($handle <= 0) || !is_numeric($handle)) return FALSE;
		$this->checkDB(1);			// Make sure DB object created
		// Set status of individual emails first, so we can get a count
		if (FALSE === ($count = $this->db->db_Update('mail_recipients','`mail_status` = '.MAIL_STATUS_HELD.' WHERE `mail_detail_id` = '.intval($handle).' AND `mail_status` >'.MAIL_STATUS_FAILED)))
		{
			return FALSE;
		}
		if ($count == 0) return TRUE;		// If zero count, must have held email just as queue being emptied, so don't touch main status

		if (!$this->db->db_Update('mail_content','`mail_content_status` = '.MAIL_STATUS_HELD.' WHERE `mail_source_id` = '.intval($handle)))
		{
			return FALSE;
		}
		return TRUE;
	}


	/**
	 * Handle a bounce report. 
	 * @param string $bounceString - the string from header X-e107-id
	 * @param string $emailAddress - optional email address string for checks
	 * @return boolean - TRUE on success, FALSE on failure
	 */
	public function markBounce($bounceString, $emailAddress = '')
	{
		$bounceInfo = array('mail_bounce_string' => $bounceString, 'mail_recipient_email' => $emailAddress);		// Ready for event data
		$errors = array();						// Log all errors, at least until proven
		$vals = explode('/',$bounceString);		// Should get one or four fields
		if (!is_numeric($vals[0])) 				// Email recipient user id number (may be zero)
		{
			$errors[] = 'Bad user ID: '.$vals[0];
		}
		$uid = intval($vals[0]);				// User ID (zero is valid)
		if (count($vals) == 4) 
		{
			if (md5($vals[0].'/'.$vals[1].'/'.$vals[2].'/') != $vals[3])
			{		// 'Extended' ID has md5 validation
				$errors[] = 'Bad md5';
			}
			if (!is_numeric($vals[1])) 		// Email body record number
			{
				$errors[] = 'Bad body record: '.$vals[1];
			}
			if (!is_numeric($vals[2])) 		// Email recipient table record number
			{
				$errors[] = 'Bad recipient record: '.$vals[2];
			}
			$vals[1] = intval($vals[1]);
			$vals[2] = intval($vals[2]);
			if (count($errors) == 0)
			{	// Look up in mailer DB if no errors so far
				$this->checkDB(1);
				if (FALSE === ($this->db->db_Select_gen(
					"SELECT mr.`mail_recipient_id`, mr.`mail_recipient_email`, mr.`mail_recipient_name` FROM `#mail_recipients` AS mr 
						LEFT JOIN `#mail_content` as mc ON mr.`mail_detail_id` = mc.`mail_source_id`
						WHERE mr.`mail_target_id` = {$vals[2]} AND mc.`mail_source_id` = {$vals[1]}")))
				{	// Invalid mailer record
					$errors[] = 'Not found in DB: '.$vals[1].'/'.$vals[2];
				}
				$row = $this->db->db_Fetch(MYSQL_ASSOC);
				if ($emailAddress && ($emailAddress != $row['mail_recipient_email']))
				{	// Email address mismatch
					$errors[] = 'Email address mismatch: '.$emailAddress.'/'.$row['mail_recipient_email'];
				}
				if ($uid != $row['mail_recipient_id'])
				{	// User ID mismatch
					$errors[] = 'User ID mismatch: '.$uid.'/'.$row['mail_recipient_id'];
				}
				if (count($errors) == 0)
				{	// All passed - can update mailout databases
					$this->db->db_Update('mail_content', '`mail_bounce_count` = `mail_bounce_count` + 1 WHERE `mail_source_id` = '.$vals[1]);
					$this->db->db_Update('mail_recipients', '`mail_status` = '.MAIL_STATUS_BOUNCED.' WHERE `mail_target_id` = '.$vals[2]);
					$bounceInfo['mail_source_id'] = $vals[1];
					$bounceInfo['mail_target_id'] = $vals[2];
					$bounceInfo['mail_recipient_id'] = $uid;
					$bounceInfo['mail_recipient_name'] = $row['mail_recipient_name'];
				}
			}
		}

		if ((count($vals) != 1) && (count($vals) != 4))
		{
			$errors[] = 'Bad element count: '.count($vals);
		}
		elseif ($uid || $emailAddress)
		{	// Now log the bounce against the user  (user handler will do any required logging)
			require_once(e_HANDLER.'user_handler.php');
			$result = userHandler::userStatusUpdate('bounce', $uid, $emailAddress);
			if ($result)	// Returns FALSE if update successful
			{
				$errors[] = $result;
			}
		}
		if (count($errors))
		{
			$logString = $bounceString.' ('.$emailAddress.')[!br!]'.implode('[!br!]',$errors);
			$this->e107->admin_log->e_log_event(10,-1,'BOUNCE','Bounce receive error',$logString,FALSE,LOG_TO_ROLLING);
			return FALSE;
		}
		$this->e107->admin_log->e_log_event(10,-1,'BOUNCE','Bounce received/logged',$bounceString.' ('.$emailAddress.')',FALSE,LOG_TO_ROLLING);
		e107::getEvent()->trigger('mailbounce', $bounceInfo);
		return TRUE;
	}



	/**
	 * Does a query to select one or more emails for which status is required.
	 * @param $start - sets the offset of the first email to return based on the search criteria
	 * @param $count - sets the maximum number of emails to return
	 * @param $fields - allows selection of which db fields are returned in each result
	 * @param $filters - array contains filter/selection criteria - basically setting limits on each field
	 * @return Returns number of records found (maximum $count); FALSE on error
	 */
	public function selectEmailStatus($start = 0, $count = 0, $fields = '*', $filters = FALSE, $orderField = 'mail_source_id', $sortOrder = 'asc')
	{
		$this->checkDB(1);			// Make sure DB object created
		if (!is_array($filters) && $filters)
		{	// Assume a textual email type
			switch ($filters)
			{
				case 'pending' :
					$filters = array('`mail_content_status` = '.MAIL_STATUS_PENDING);
					break;
				case 'held' :
					$filters = array('`mail_content_status` = '.MAIL_STATUS_HELD);
					break;
				case 'pendingheld' :
					$filters = array('((`mail_content_status` = '.MAIL_STATUS_PENDING.') OR (`mail_content_status` = '.MAIL_STATUS_HELD.'))');
					break;
				case 'sent' :
					$filters = array('`mail_content_status` = '.MAIL_STATUS_SENT);
					break;
				case 'allcomplete' :
					$filters = array('((`mail_content_status` = '.MAIL_STATUS_SENT.') OR (`mail_content_status` = '.MAIL_STATUS_PARTIAL.') OR (`mail_content_status` = '.MAIL_STATUS_CANCELLED.'))');
					break;
				case 'failed' :
					$filters = array('`mail_content_status` = '.MAIL_STATUS_FAILED);
					break;
				case 'saved' :
					$filters = array('`mail_content_status` = '.MAIL_STATUS_SAVED);
					break;
			}
		}
		if (!is_array($filters))
		{
			$filters = array(); 
		}
		$query = "SELECT SQL_CALC_FOUND_ROWS {$fields} FROM `#mail_content`";
		if (count($filters))
		{
			$query .= ' WHERE '.implode (' AND ', $filters);
		}
		if ($orderField)
		{
			$query .= " ORDER BY `{$orderField}`";
		}
		if ($sortOrder)
		{
			$sortOrder = strtoupper($sortOrder);
			$query .= ($sortOrder == 'DESC') ? ' DESC' : ' ASC';
		}
		if ($count)
		{
			$query .= " LIMIT {$start}, {$count}";
		}
		//echo "{$start}, {$count} Mail query: {$query}<br />";
		$result = $this->db->db_Select_gen($query);
		if ($result !== FALSE)
		{
			$this->queryCount[1] = $this->db->total_results;			// Save number of records found
		}
		else
		{
			$this->queryCount[1] = 0;
		}
		return $result;
	}


	/**
	 * Returns the total number of records matching the search done in the most recent call to selectEmailStatus()
	 * @return integer - number of emails matching criteria
	 */
	public function getEmailCount()
	{
		return $this->queryCount[1];
	}



	/**
	 * Returns the detail of the next email which satisfies the query done in selectEmailStatus()
	 * @return Returns an array of data relating to a single email if available (in 'flat' format). FALSE on no data or error
	 */
	public function getNextEmailStatus()
	{
		$result = $this->db->db_Fetch();
		if (is_array($result)) { return $this->dbToMail($result); }
		return FALSE;
	}



	/**
	 * Does a query to select from the list of email targets which have been used
	 * @param $start - sets the offset of the first email to return based on the search criteria
	 * @param $count - sets the maximum number of emails to return
	 * @param $fields - allows selection of which db fields are returned in each result
	 * @param $filters - array contains filter/selection criteria
	 *				'handle=nn' picks out a specific email
	 * @return Returns number of records found; FALSE on error
	 */
	public function selectTargetStatus($handle, $start = 0, $count = 0, $fields = '*', $filters = FALSE, $orderField = 'mail_target_id', $sortOrder = 'asc')
	{
		$handle = intval($handle);
		if ($filters === FALSE) { $filters = array(); }		// Might not need this line
		
		$this->checkDB(2);			// Make sure DB object created

		// TODO: Implement filters if needed
		$query = "SELECT SQL_CALC_FOUND_ROWS {$fields} FROM `#mail_recipients` WHERE `mail_detail_id`={$handle}";
		if ($orderField)
		{
			$query .= " ORDER BY `{$orderField}`";
		}
		if ($sortOrder)
		{
			$sortOrder = strtoupper($sortOrder);
			$query .= ($sortOrder == 'DESC') ? ' DESC' : ' ASC';
		}
		if ($count)
		{
			$query .= " LIMIT {$start}, {$count}";
		}
//		echo "{$start}, {$count} Target query: {$query}<br />";
		$result = $this->db2->db_Select_gen($query);
		if ($result !== FALSE)
		{
			$this->queryCount[2] = $this->db2->total_results;			// Save number of records found
		}
		else
		{
			$this->queryCount[2] = 0;
		}
//		echo "Result: {$result}.  Total: {$this->queryCount[2]}<br />";
		return $result;
	}


	/**
	 * Returns the total number of records matching the search done in the most recent call to selectTargetStatus()
	 * @return integer - number of emails matching criteria
	 */
	public function getTargetCount()
	{
		return $this->queryCount[2];
	}



	/**
	 * Returns the detail of the next recipient which satisfies the query done in selectTargetStatus()
	 * @return Returns an array of data relating to a single email if available (in 'flat' format). FALSE on no data or error
	 */
	public function getNextTargetStatus()
	{
		$result = $this->db2->db_Fetch(MYSQL_ASSOC);
		if (is_array($result)) { return $this->dbToTarget($result); }
		return FALSE;
	}



	/**
	 * Creates email body text according to options
	 * @param $text string - text to process
	 * @param $format string - options:
	 *				textonly - generate plain text email
	 *				texthtml - HTML format email, no theme info
	 *				texttheme - HTML format email, including current theme stylesheet etc
	 * @param boolean $incImages - valid only with HTML output; 
	 *					if true any 'absolute' format images are embedded in the source of the email.
	 *					if FALSE, absolute links are converted to URLs on the local server		
	 * @return string - updated body
	 */
	protected function makeEmailBody($text, $format = 'textonly', $incImages = TRUE)
	{
		global $pref;
		if ($format == 'textonly')
		{	// Plain text email - strip bbcodes etc
			$temp = $this->e107->tp->toHTML($text, TRUE, 'E_BODY_PLAIN');		// Decode bbcodes into HTML, plain text as far as possible etc
			return stripslashes(strip_tags($temp));								// Have to do strip_tags() again in case bbcode added some
		}

		$consts = $incImages ? ',consts_abs' : 'consts_full';			// If inline images, absolute constants so we can change them

		// HTML format email here
		$mail_head = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
		$mail_head .= "<html xmlns='http://www.w3.org/1999/xhtml' >\n";
		$mail_head .= "<head><meta http-equiv='content-type' content='text/html; charset=utf-8' />\n";
		if ($format == 'texttheme') 
		{
			$styleFile = THEME.'emailstyle.css';
			if (!is_readable($styleFile)) { $styleFile = e_THEME.$pref['sitetheme']."/style.css"; }
			$style_css = file_get_contents($styleFile);
			$mail_head .= "<style>\n".$style_css."\n</style>";
		}
		$mail_head .= "</head>\n";


		$message_body = $mail_head."<body>\n";
		if ($format == 'texttheme') 
		{
			$message_body .= "<div style='padding:10px;width:97%'><div class='forumheader3'>\n";
			$message_body .= $this->e107->tp->toHTML($text, TRUE, 'E_BODY'.$consts)."</div></div></body></html>";
		}
		else
		{
			$message_body .= $this->e107->tp->toHTML($text, TRUE, 'E_BODY'.$consts)."</body></html>";
			$message_body = str_replace("&quot;", '"', $message_body);
		}

		$message_body = stripslashes($message_body);


		if (!$incImages)
		{
			// Handle internally generated 'absolute' links - they need the full URL
			$message_body = str_replace("src='".e_HTTP, "src='".SITEURL, $message_body);
			$message_body = str_replace('src="'.e_HTTP, 'src="'.SITEURL, $message_body);
			$message_body = str_replace("href='".e_HTTP, "src='".SITEURL, $message_body);
			$message_body = str_replace('href="'.e_HTTP, 'src="'.SITEURL, $message_body);
		}

//		print_a($message_body);
		return $message_body;
	}
	
}

?>