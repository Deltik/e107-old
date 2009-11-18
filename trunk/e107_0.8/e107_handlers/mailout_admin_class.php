<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2009 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Administration - Site Maintenance
 *
 * $Source: /cvs_backup/e107_0.8/e107_handlers/mailout_admin_class.php,v $
 * $Revision: 1.4 $
 * $Date: 2009-11-18 01:04:43 $
 * $Author: e107coders $
 *
*/

/*
Various admin-related mailout functions, mostly to do with creating and handling forms. 
*/


/*
TODO:
	1. Use API to downloads plugin to get available files (when available)
	2. Fuller checking prior to send
	3. Complete dbTidy()
	4. May want more control over date display format
*/

if (!defined('e107_INIT')) { exit; }


require_once(e_HANDLER.'mail_manager_class.php');

class mailoutAdminClass extends e107MailManager
{
	protected $mode;					// So we know what the current task is
	protected $mailHandlers = array();
	protected $showFrom = 0;
	protected $showCount = 10;
	protected $sortField = 'mail_source_id';
	protected $sortOrder = 'asc';
	protected $fieldPref = array();
	protected $userCache = array();
	

	// Definitions associated with each column which might be displayed. (Compatible with forms-based display)
	// Fields are displayed in the order listed.
	// Can also have:	width
	//					type
	//					thclass
	protected $fields = array(
		'mail_recipients' => array
		(	'mail_target_id'  	=> array('title' => LAN_MAILOUT_143, 'thclass' => 'center', 'forced' => TRUE),
			'mail_recipient_id' => array('title' => LAN_MAILOUT_142, 'thclass' => 'center'),
			'mail_recipient_name' => array('title' => LAN_MAILOUT_141, 'forced' => TRUE),
			'mail_recipient_email' => array('title' => LAN_MAILOUT_140, 'thclass' => 'left', 'forced' => TRUE),
			'mail_status' 		=> array('title' => LAN_MAILOUT_138, 'thclass' => 'center', 'proc' => 'contentstatus'),
			'mail_detail_id' 	=> array('title' => LAN_MAILOUT_137),
			'mail_send_date' 	=> array('title' => LAN_MAILOUT_139, 'proc' => 'sdatetime'),
			'mail_target_info'	=> array('title' => LAN_MAILOUT_148, 'proc' => 'array'),
			'options' 			=> array('title' => LAN_OPTIONS, 'forced' => TRUE)
		),
		'mail_content' => array(
			'mail_source_id' 	=> array('title' => LAN_MAILOUT_137, 'thclass' => 'center', 'forced' => TRUE),
			'mail_title' 		=> array('title' => LAN_MAILOUT_135, 'forced' => TRUE),
			'mail_subject' 		=> array('title' => LAN_MAILOUT_06, 'forced' => TRUE),
			'mail_content_status' => array('title' => LAN_MAILOUT_136, 'thclass' => 'center', 'proc' => 'contentstatus'),
			'mail_togo_count' 	=> array('title' => LAN_MAILOUT_83),
			'mail_sent_count' 	=> array('title' => LAN_MAILOUT_82),
			'mail_fail_count' 	=> array('title' => LAN_MAILOUT_128),
			'mail_bounce_count' => array('title' => LAN_MAILOUT_144),
			'mail_start_send' 	=> array('title' => LAN_MAILOUT_131, 'proc' => 'sdatetime'),
			'mail_end_send' 	=> array('title' => LAN_MAILOUT_132, 'proc' => 'sdatetime'),
			'mail_create_date' 	=> array('title' => LAN_MAILOUT_130, 'proc' => 'sdatetime'),
			'mail_creator' 		=> array('title' => LAN_MAILOUT_85, 'proc' => 'username'),
			'mail_create_app' 	=> array('title' => LAN_MAILOUT_133),
			'mail_e107_priority' => array('title' => LAN_MAILOUT_134),
			'mail_last_date' 	=> array('title' => LAN_MAILOUT_129, 'proc' => 'sdatetime'),
			'mail_body' 		=> array('title' => LAN_MAILOUT_100, 'proc' => 'trunc200'),
		//	'mail_other' = array('title' => LAN_MAILOUT_84),
			'mail_sender_email' => array('title' => LAN_MAILOUT_149),
			'mail_sender_name'	=> array('title' => LAN_MAILOUT_150),
			'mail_copy_to'		=> array('title' => LAN_MAILOUT_151),
			'mail_bcopy_to'		=> array('title' => LAN_MAILOUT_152),
			'mail_attach'		=> array('title' => LAN_MAILOUT_153),
			'mail_send_style'	=> array('title' => LAN_MAILOUT_154),
			'mail_selectors'	=> array('title' => LAN_MAILOUT_155, 'proc' => 'selectors', 'nolist' => 'TRUE'),
			'mail_include_images' => array('title' => LAN_MAILOUT_224, 'proc' => 'yesno'),
			'options' 			=> array('title' => LAN_OPTIONS, 'forced' => TRUE)
		)
	);

	// List of fields to be hidden for each action ('nolist' attribute true)
	protected $hideFields = array(
		'orphans' => array(),
		'saved'  => 'mail_content_status,mail_togo_count,mail_sent_count,mail_fail_count,mail_bounce_count,mail_start_send,mail_end_send,mail_e107_priority,mail_last_date,mail_selectors',
		'sent'  => 'mail_togo_count,mail_last_date,mail_selectors',
//		'pending'  => 'mail_togo_count,mail_sent_count,mail_fail_count,mail_bounce_count,mail_start_send,mail_end_send,mail_e107_priority,mail_last_date,mail_selectors',
		'pending'  => 'mail_start_send,mail_end_send,mail_e107_priority,mail_last_date,mail_selectors',
		'held'  => 'mail_sent_count,mail_fail_count,mail_bounce_count,mail_start_send,mail_end_send,mail_e107_priority,mail_last_date,mail_selectors',
		'resend'  => 'mail_Selectors',
		'recipients' => 'mail_detail_id'
		);

	// Array of info associated with each task we might do
	protected $tasks = array(
			'makemail' => array('title' => LAN_MAILOUT_190, 'defaultSort' => '', 'defaultTable' => ''),
			'saved' => array('title' => LAN_MAILOUT_191, 'defaultSort' => 'mail_source_id', 'defaultTable' => 'mail_content'),
			'marksend' => array('title' => 'Internal: marksend', 'defaultSort' => 'mail_source_id', 'defaultTable' => 'mail_content'),
			'sent' => array('title' => LAN_MAILOUT_192, 'defaultSort' => 'mail_source_id', 'defaultTable' => 'mail_content'),
			'pending' => array('title' => LAN_MAILOUT_193, 'defaultSort' => 'mail_source_id', 'defaultTable' => 'mail_content'),
			'held' => array('title' => LAN_MAILOUT_194, 'defaultSort' => 'mail_source_id', 'defaultTable' => 'mail_content'),
			'recipients' => array('title' => LAN_MAILOUT_173, 'defaultSort' => 'mail_recipient_email', 'defaultTable' => 'mail_recipients'),
			'mailtargets' => array('title' => LAN_MAILOUT_173, 'defaultSort' => 'mail_recipient_email', 'defaultTable' => 'mail_recipients'),
			'prefs' => array('title' => ADLAN_40, 'defaultSort' => '', 'defaultTable' => ''),
			'maint' => array('title' => ADLAN_40, 'defaultSort' => '', 'defaultTable' => '')
			);


	// Options for mail listing dropdown
	protected $modeOptions = array(
		'saved' => array(
			'mailedit' => LAN_MAILOUT_163,
			'maildelete' => LAN_DELETE
			),
		'pending' => array(
//			'mailsendnow' => LAN_MAILOUT_158,
			'mailhold' => LAN_MAILOUT_159,
			'mailcancel' => LAN_MAILOUT_160,
			'mailtargets' => LAN_MAILOUT_181
			),
		'held' => array(
			'mailsendnow' => LAN_MAILOUT_158,
			'mailcancel' => LAN_MAILOUT_160,
			'mailtargets' => LAN_MAILOUT_181
			),
		'sent' => array(
			'mailsendnow' => LAN_EDIT,
			'maildelete' => LAN_DELETE,
			'mailtargets' => LAN_MAILOUT_181
			),
		'recipients' => array(
			'mailonedelete' => LAN_DELETE
			)
	);


	// List of fields to be included in email display for various options
	protected $mailDetailDisplay = array(
		'basic' => array('mail_source_id' => 1, 'mail_title' => 1, 'mail_subject' => 1, 'mail_body' => 200),
		'send' => array('mail_source_id' => 1, 'mail_title' => 1, 'mail_subject' => 1, 'mail_body' => 500)
	);
	

	/**
	 * Constructor
	 * 
	 *
	 * @return void
	 */
	public function __construct($mode = '')
	{
		parent::__construct();
//		$saveMode = $mode;
		$dbTable = '';
		if (isset($this->tasks[$mode]))
		{
			$dbTable = $this->tasks[$mode]['defaultTable'];
		}
		if(isset($_GET['frm']))
		{
			$temp = intval($_GET['frm']);
			if ($temp < 0) $temp = 0;
			$this->showFrom = $temp;
		}
		if(isset($_GET['count']))
		{
			$temp = min(intval($_GET['count']), 50);			// Limit to 50 per page
			$temp = max($temp, 5);								// ...and minimum 5 per page
			$this->showCount = $temp;
		}
		if (isset($_GET['fld']))
		{
			$temp = $this->e107->tp->toDB($_GET['fld']);
			if (is_array($this->fields[$dbTable][$temp]))
			{
				$this->sortField = $temp;
			}
		}
		if (isset($_GET['asc']))
		{
			$temp = strtolower($this->e107->tp->toDB($_GET['asc']));
			if (($temp == 'asc') || ($temp == 'desc'))
			{
				$this->sortOrder = $temp;
			}
		}
		$this->newMode($mode);
	}



	/**
	 * Set up new mode
	 *
	 * @param $mode - display mode
	 * @return none
	 */
	public function newMode($mode = '')
	{
		global $user_pref;
		$this->mode = $mode;
		$curTable = $this->tasks[$this->mode]['defaultTable'];
		if (is_array($user_pref['admin_mailout_columns'][$mode]))
		{	// Use saved list of fields to view if it exists
			$this->fieldPref = $user_pref['admin_mailout_columns'][$mode];
		}
		else
		{	// Default list is minimal fields only
			$this->fieldPref = array();
			foreach ($this->fields[$curTable] as $f => $v)
			{
				if (vartrue($v['forced']))
				{
					$this->fieldPref[] = $f;
				}
			}
		}

		// Possibly the sort field needs changing
		if (!isset($this->fields[$curTable][$this->sortField]))
		{
			$this->sortField = $this->tasks[$mode]['defaultSort'];
		}
		
		// Now hide any fields that need to be for this mode
		if (isset($this->hideFields[$mode]))
		{
			$hideList = array_flip(explode(',',$this->hideFields[$mode]));
			foreach ($this->fields[$curTable] as $f => $v)
			{
				$this->fields[$curTable][$f]['nolist'] = isset($hideList[$f]);
			}
			foreach ($this->fieldPref as $k => $v)		// Remove from list of active fields (shouldn't often do anything)
			{
				if (isset($hideList[$v]))
				{
					unset($this->fieldPref[$k]);
				}
			}
		}
	}



	protected function calcFieldSpec($mode, $noOptions = FALSE)
	{
		if (!isset($this->tasks[$mode]))
		{
			echo "CalcfieldSpec({$mode}) - programming bungle<br />";
			return FALSE;
		}
		$ret = array();
		$curTable = $this->tasks[$this->mode]['defaultTable'];
		foreach ($this->fields[$curTable] as $f => $v)
		{
			if ((vartrue($v['forced']) && !vartrue($v['nolist'])) || in_array($f, $this->fieldPref))
			{
				if (($f != 'options') || ($noOptions === FALSE))
				{
					$ret[] = $f;
				}
			}
		}
		return $ret;
	}


	/**
	 * Save the column visibility prefs for this mode
	 *
	 * @param $target - display mode
	 * @return none
	 */
	public function mailbodySaveColumnPref($target)
	{
		global $user_pref;
		if (!$target) return;
		if (!isset($this->tasks[$target]))
		{
			echo "Invalid prefs target: {$target}<br />";
			return;
		}
		if (isset ($_POST['etrigger_ecolumns']))
		{
			$user_pref['admin_mailout_columns'][$target] = $_POST['e-columns'];
			save_prefs('user');
			$this->fieldPref = $user_pref['admin_mailout_columns'][$target];
		}
	}


	protected function getUserName($uid)
	{
		if (!isset($this->userCache[$uid]))
		{
			// Look up user
			$this->checkDB(2);			// Make sure DB object created
			if ($this->db2->db_Select('user','user_name, user_loginname', 'user_id='.intval($uid)))
			{
				$row = $this->db2->db_Fetch(MYSQL_ASSOC);
				$this->userCache[$uid] = $row['user_name'].' ('.$row['user_loginname'].')';
			}
			else
			{
				$this->userCache[$uid] = 'UID: '.$uid;
			}
		}
		return $this->userCache[$uid];
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
	 * Generate the HTML for displaying actions box for emails
	 * 
	 * Options given depend on $mode, and also values in the email data.
	 *
	 * @param $mailData - array of email-related info
	 * @return HTML for display
	 */
	public function makeMailOptions($mode,$mailData)
	{
		if (!is_numeric($mailData['mail_source_id']) || ($mailData['mail_source_id'] == 0))
		{
			echo "makeMailOptions ({$mode}): Programming bungle!";
			print_a($mailData);
			return 'Error';
		}
		$text .= "<select name='mailaction[{$mailData['mail_source_id']}]' onchange='this.form.submit()' class='tbox' style='width:90%'>\n
				<option selected='selected' value=''>&nbsp;</option>\n";
		foreach ($this->modeOptions[$mode] as $key => $val)
		{
			$text .= "<option value='{$key}'>{$val}</option>\n";
		}
		$text .= "</select>\n";
		return $text;
	}


	/**
	 * Generate the HTML for displaying actions box for emails
	 * 
	 * Options given depend on $mode, and also values in the email data.
	 *
	 * @param $mailData - array of email-related info
	 * @return HTML for display
	 */
	public function makeTargetOptions($mode,$targetData)
	{
		if (!is_numeric($targetData['mail_target_id']) || ($targetData['mail_target_id'] == 0))
		{
			echo "makeTargetOptions ({$mode}): Programming bungle!";
			print_a($targetData);
			return 'Error';
		}
		$text .= "<select name='targetaction[{$targetData['mail_target_id']}]' onchange='this.form.submit()' class='tbox' style='width:90%'>\n
				<option selected='selected' value=''>&nbsp;</option>\n";
		foreach ($this->modeOptions[$mode] as $key => $val)
		{
			$text .= "<option value='{$key}'>{$val}</option>\n";
		}
		$text .= "</select>\n";
		return $text;
	}


	/**
	 * Generate the HTML for displaying email selection fields
	 * 
	 * @param $options - comma-separate string of handlers to load
	 *	'core' - core handler
	 *	plugin name - obvious!
	 *	'all' - obvious!
	 * @return Number of handlers loaded
	 */
	public function loadMailHandlers($options = 'all')
	{
		global $pref;



		$ret = 0;
		$toLoad = explode(',', $options);
		if (in_array('core', $toLoad) || ($options == 'all'))
		{
			require_once(e_HANDLER.'mailout_class.php');
			$this->mailHandlers[] = new core_mailout($this);		// Start by loading the core mailout class
			$ret++;
		}

		$active_mailers = explode(',',varset($pref['mailout_enabled'],''));

		// Load additional configured handlers
		foreach ($pref['e_mailout_list'] as $mailer => $v)
		{
			if (isset($pref['plug_installed'][$mailer]) && in_array($mailer,$active_mailers) && (($options == 'all') || in_array('core', $toLoad)))
			{  // Could potentially use this handler - its installed and enabled
				if (!is_readable(e_PLUGIN.$mailer.'/e_mailout.php'))
				{
					echo 'Invalid mailer selected: '.$mailer.'<br />';
					exit;
				}
				require_once(e_PLUGIN.$mailer.'/e_mailout.php');
				if (varset($mailerIncludeWithDefault,TRUE))
				{	// Definitely need this plugin
					$mailClass = $mailer.'_mailout';
					$temp = new $mailClass;
					if ($temp->mailerEnabled)
					{
						$this->mailHandlers[] = &$temp;
						$ret++;
						if (varset($mailerExcludeDefault,FALSE) && isset($this->mailHandlers[0]) && ($this->mailHandlers[0]->mailerSource == 'core'))
						{
							$this->mailHandlers[0]->mailerEnabled = FALSE;			// Don't need default (core) handler
							$ret--;
						}
					}
					else
					{
						unset($temp);
					}
				}
			}
		}

		return $ret;
	}


	/**
	 * Generate the HTML for displaying email selection fields
	 * 
	 * @param $options - comma-separate string of areas to display:
	 *		plugins - selectors from any available plugins
	 *		cc - field for 'cc' options
	 *		bcc -  field for 'bcc' options
	 *		src=plugname - selector from the specified plugin
	 *		'all' - all available fields
	 * @return text for display
	 */
	public function emailSelector($options = 'all', $selectorInfo = FALSE)
	{
		$ret = '';
		foreach ($this->mailHandlers as $m)
		{
			if ($m->mailerEnabled)
			{
				$ret .= "<tr><td>".$m->mailerName."</td><td>".$m->showSelect(TRUE, varset($selectorInfo[$m->mailerSource], FALSE))."</td></tr>";
			}
		}
		return $ret;
	}


	/**
	 * Get the selector details from each mail plugin (to add to mail data)
	 * 
	 * @return array of selectors - key is the plugin name, value is the selector data (often itself an array)
	 */
	public function getAllSelectors()
	{
		$ret = array();
		foreach ($this->mailHandlers as $m)
		{
			if ($m->mailerEnabled)
			{
				$ret[$m->mailerSource] = $m->returnSelectors();
			}
		}
		return $ret;
	}


	// Generate list of userclasses, including the number of members in each class.
	// Returns a userclass selection dropdown
	public function userClassesTotals($name, $curSel) 
	{
		$fixedClasses = array('all' => LAN_MAILOUT_12,'unverified' => LAN_MAILOUT_13, 'admin' => LAN_MAILOUT_53, 'self' => LAN_MAILOUT_54);

		$ret = '';
		$this->checkDB(2);			// Make sure DB object created
		$ret .= "<select style='width:80%' class='tbox' name='{$name}' >\n";
		foreach ($fixedClasses as $k => $v)
		{
			$sel = ($k == $curSel) ? " selected='selected'" : '';
			$ret .= "<option value='{$k}'{$sel}>{$v}</option>\n";
		}
		$query = "SELECT uc.*, count(u.user_id) AS members
				FROM #userclass_classes AS uc
				LEFT JOIN #user AS u ON u.user_class REGEXP concat('(^|,)',uc.userclass_id,'(,|$)')
				GROUP BY uc.userclass_id
						";

		$this->db2->db_Select_gen($query);
		while ($row = $this->db2->db_Fetch()) 
		{
			$public = ($row['userclass_editclass'] == e_UC_PUBLIC)? "(".LAN_MAILOUT_10.")" : "";
			$selected = ($row['userclass_id'] == $curSel) ? " selected='selected'" : '';
			$ret .= "<option value='{$row['userclass_id']}'{$selected} >".LAN_MAILOUT_55." - {$row['userclass_name']}  {$public} [{$row['members']}]</option>\n";
		}
		$ret .= " </select>\n";

		return $ret;
	}



	// Return a list of extended user fields
	// TODO: Exclude system fields, and maybe others.
	public function ret_extended_field_list($list_name, $curval = '', $add_blank = FALSE)
	{
		$this->checkDB(2);			// Make sure DB object created
		$ret = "<select name='{$list_name}' class='tbox'>\n";
		if ($add_blank) $ret .= "<option value=''>&nbsp;</option>\n";

		$this->db2->db_Select("user_extended_struct");
		while($row = $this->db2->db_Fetch())
		{
			$value = 'ue.user_'.$row['user_extended_struct_name'];
			$selected = ($value == $curval) ? " selected='selected'" : '';
			$ret .= "<option value='".$value."' {$selected}>".ucfirst($row['user_extended_struct_name'])."</option>\n";
		}
		$ret .= "</select>\n";
		return $ret;
	}


	/**
	 * Creates an array of data from standard $_POST fields
	 * 
	 * @param $newMail - set TRUE for initial creation, FALSE when updating
	 * @return array of data
	 */
	public function parseEmailPost($newMail = TRUE)
	{
		$ret = array(	'mail_title'		=> $this->e107->tp->toDB($_POST['email_title']),
						'mail_subject'		=> $this->e107->tp->toDB($_POST['email_subject']),
						'mail_body' 		=> $this->e107->tp->toDB($_POST['email_body']),
						'mail_sender_email' => $this->e107->tp->toDB($_POST['email_from_email']),
						'mail_sender_name'	=> $this->e107->tp->toDB($_POST['email_from_name']),
						'mail_copy_to'		=> $this->e107->tp->toDB($_POST['email_cc']),
						'mail_bcopy_to'		=> $this->e107->tp->toDB($_POST['email_bcc']),
						'mail_attach'		=> $this->e107->tp->toDB(trim($_POST['email_attachment'])),
						'mail_send_style'	=> $this->e107->tp->toDB(varset($_POST['send_style'],'textonly')),
						'mail_include_images' => (isset($_POST['mail_include_images']) ? 1 : 0)
					); 
		if (isset($_POST['mail_source_id']))
		{
			$ret['mail_source_id'] = intval($_POST['mail_source_id']);
		}
		if ($newMail)
		{
			$ret['mail_creator'] = USERID;
			$ret['mail_create_date'] = time();
		}
		return $ret;
	}



	/**
	 * Does some basic checking on email data.
	 * 
	 * @param $email - array of data in parseEmailPost() format
	 * @param $fullCheck - TRUE to check all fields that are required (immediately prior to sending); FALSE to just check a few basics (prior to save)
	 * @return TRUE if OK. Array of error messages if any errors found
	 */
	public function checkEmailPost($email, $fullCheck = FALSE)
	{
		$errList = array();
		if (count($email) < 3)
		{
			$errList[] = LAN_MAILOUT_201;
			return $errList;
		}
		if (!trim($email['mail_subject'])) $errList[] = LAN_MAILOUT_200;
		if (!trim($email['mail_body'])) $errList[] = LAN_MAILOUT_202;
		if (!trim($email['mail_sender_name'])) $errList[] = LAN_MAILOUT_203;
		if (!trim($email['mail_sender_email'])) $errList[] = LAN_MAILOUT_204;
		switch ($email['mail_send_style'])
		{
			case 'textonly' :
			case 'texthtml' :
			case 'texttheme' :
				break;
			default :
				$errList[] = LAN_MAILOUT_205;
		}
		if (count($errList) == 0)
		{
			return TRUE;
		}
		return $errList;
	}



	/**
	 * Generate a table which shows some information about an email.
	 * Intended to be part of a 2-column table - includes the row detail, but not the surrounding table definitions
	 * 
	 * @param $mailSource - array of mail information
	 * @param $options - controls how much information is displayed
	 * @return text for display
	 */
	public function showMailDetail(&$mailSource, $options='basic')
	{
		if (!isset($this->mailDetailDisplay[$options]))
		{
			return "<tr><td colspan='2'>Programming bungle - invalid option value: {$options}</td></tr>";
		}

		$res = '';
		foreach ($this->mailDetailDisplay[$options] as $k => $v)
		{
			$res .= '<tr><td>'.$this->fields['mail_content'][$k]['title'].'</td><td>';
			$res .= ($v > 1) ? $this->e107->tp->text_truncate($mailSource[$k], $v, '...') : $mailSource[$k];
			$res .= '</td></tr>'."\n";
		}
		return $res;
	}



	/**
	 * Generate the HTML for dropdown to select mail sending style (text/HTML/styled
	 * 
	 * @param $curval - current value
	 * @param $name name of item
	 * @return text for display
	 */
	public function sendStyleSelect($curval = '', $name = 'send_style')
	{

		$emFormat = array(
			'textonly' => LAN_MAILOUT_125,
			'texthtml' => LAN_MAILOUT_126,
			'texttheme' => LAN_MAILOUT_127
		);

		$text = "<select class='tbox' name='{$name}'>\n";

		foreach ($emFormat as $key=>$val)
		{
			$selected = ($key == $curval) ? " selected='selected'" : '';
			$text .= "<option value='".$key."'{$selected}>".$val."</option>\n";
		}
		$text .="</select>\n";
		return $text;
	}


	/**
	 * Generate the HTML to show the mailout form. Used for both sending and editing
	 * 
	 * @param $mailSource - array of mail information
	 * @return text for display
	 */
	function show_mailform(&$mailSource)
	{
		global $pref,$HANDLERS_DIRECTORY;
		global $mailAdmin;

		if (!is_array($mailSource))
		{
			$this->e107->ns->tablerender('ERROR!!', 'Coding error - mail not array (521)');
			exit;
		}
		
		$email_subject = varset($mailSource['mail_subject'], '');
		$email_body = $this->e107->tp->toForm(varset($mailSource['mail_body'],''));
		$email_id = varset($mailSource['mail_source_id'],'');
		$text = '';

		if(strpos($_SERVER['SERVER_SOFTWARE'],'mod_gzip') && !is_readable(e_HANDLER.'phpmailer/.htaccess'))
		{
			$warning = LAN_MAILOUT_40.' '.$HANDLERS_DIRECTORY.'phpmailer/ '.LAN_MAILOUT_41;
			$this->e107->ns->tablerender(LAN_MAILOUT_42, $warning);
		}

		$debug = (e_MENU == "debug") ? "?[debug]" : "";
		$text .= "<div>
			<form method='post' action='".e_SELF."?mode=makemail' id='mailout_form'>
			<table cellpadding='0' cellspacing='0' class='adminform'>
			<colgroup span='2'>
				<col class='col-label' />
				<col class='col-control' />
			</colgroup>
			<tr>
			<td>".LAN_MAILOUT_111.": </td>
			<td>
			<input type='text' name='email_title' class='tbox' style='width:80%' size='10' value=\"".varset($mailSource['mail_title'],'')."\" />
			</td>
			</tr>

			<tr>
			<td>".LAN_MAILOUT_01.": </td>
			<td>
			<input type='text' name='email_from_name' class='tbox' style='width:80%' size='10' value=\"".varset($mailSource['mail_from_name'],USERNAME)."\" />
			</td>
			</tr>";


		$text .="
			<tr>
			<td>".LAN_MAILOUT_02.": </td>
			<td >
			<input type='text' name='email_from_email' class='tbox' style='width:80%' value=\"".varset($mailSource['mail_from_email'],USEREMAIL)."\" />
			</td>
			</tr>";


	// Add in the core and any plugin selectors here
		$text .= $this->emailSelector('all', varset($mailSource['mail_selectors'], FALSE));



	// CC, BCC
		$text .= "
			<tr>
			<td>".LAN_MAILOUT_04.": </td>
			<td >
			<input type='text' name='email_cc' class='tbox' style='width:80%' value=\"".$mailSource['mail_cc']."\" />
			</td>
			</tr>

			<tr>
			<td>".LAN_MAILOUT_05.": </td>
			<td >
			<input type='text' name='email_bcc' class='tbox' style='width:80%' value='".$mailSource['mail_bcc']."' />
			</td>
			</tr>";



	// Close one table, open another - to give a boundary between addressees and content
		$text .= "</table>
			<table cellpadding='0' cellspacing='0' class='adminform'>
			<colgroup span='2'>
				<col class='col-label' />
				<col class='col-control' />
			</colgroup>";

	// Subject
		$text .= "
			<tr>
			<td>".LAN_MAILOUT_51.": </td>
			<td>
			<input type='text' name='email_subject' class='tbox' style='width:80%' value='{$email_subject}' />
			</td>
			</tr>";


	// Attachment.
		if ($this->e107->isInstalled('download'))
		{
			// TODO - use download plugin API
			$text .= "<tr>
				<td>".LAN_MAILOUT_07.": </td>
				<td >";
			$text .= "<select class='tbox' name='email_attachment' >
				<option value=''>&nbsp;</option>\n";
			$this->e107->sql->db_Select("download", "download_url,download_name", "download_id !='' ORDER BY download_name");
			while ($row = $this->e107->sql->db_Fetch()) 
			{
				$selected = ($mailSource['mail_attach'] == $row['download_url']) ? "selected='selected'" : '';
//				$text .= "<option value='".urlencode($row['download_url'])."' {$selected}>".htmlspecialchars($row['download_name'])."</option>\n";
				$text .= "<option value='".$row['download_url']."' {$selected}>".htmlspecialchars($row['download_name'])."</option>\n";
			}
			$text .= " </select>";

			$text .= "</td>
			</tr>";
		}


		$text .= "
			<tr>
			<td>".LAN_MAILOUT_09.": </td>
			<td >\n";

		$text .= $this->sendStyleSelect($mailSource['mail_send_style']);
		$checked = (isset($mailSource['mail_include_images']) && $mailSource['mail_include_images']) ? " checked='checked'" : '';
		$text .= "&nbsp;&nbsp;<input type='checkbox' name='mail_include_images' value='1' {$checked} />".LAN_MAILOUT_225;
		$text .="
		</td></tr>\n
			<tr>
			<td colspan='2' >
			<textarea rows='10' cols='20' id='email_body' name='email_body'  class='e-wysiwyg tbox' style='width:80%;height:200px' onselect='storeCaret(this);' onclick='storeCaret(this);' onkeyup='storeCaret(this);'>".$email_body."</textarea>
			</td>
			</tr>";

		$text .="
			<tr>
			<td colspan='2'>
			<div>";

		global $eplug_bb;

		$eplug_bb[] = array(
				'name'		=> 'shortcode',
				'onclick'	=> 'expandit',
				'onclick_var' => 'sc_selector',
				'icon'		=> e_IMAGE.'generic/bbcode/shortcode.png',
				'helptext'	=> LAN_MAILOUT_11,
				'function'	=> array($this,'sc_Select'),
				'function_var'	=> 'sc_selector'
		);

		$text .= display_help('helpb','mailout');

		if(e_WYSIWYG) 
		{
			$text .="<span style='vertical-align: super;margin-left:5%;margin-bottom:auto;margin-top:auto'><input type='button' class='button' name='usrname' value=\"".LAN_MAILOUT_16."\" onclick=\"tinyMCE.selectedInstance.execCommand('mceInsertContent',0,'|USERNAME|')\" />
			<input type='button' class='button' name='usrlink' value=\"".LAN_MAILOUT_14."\" onclick=\"tinyMCE.selectedInstance.execCommand('mceInsertContent',0,'|DISPLAYNAME|')\" />
			<input type='button' class='button' name='usrlink' value=\"".LAN_MAILOUT_17."\" onclick=\"tinyMCE.selectedInstance.execCommand('mceInsertContent',0,'|SIGNUP_LINK|')\" />
			<input type='button' class='button' name='usrid' value=\"".LAN_MAILOUT_18."\" onclick=\"tinyMCE.selectedInstance.execCommand('mceInsertContent',0,'|USERID|')\" /></span>";
		}

		$text .="
			</div></td>
			</tr>
			</table> ";


		$text .= "<div class='buttons-bar center'>";
		if($email_id)
		{
			$text .= "<input type='hidden' name='mail_source_id' value='".$email_id."' />";
			$text .= "<input class='button' type='submit' name='update_email' value=\"".LAN_UPDATE."\" />";
		}
		else
		{
			$text .= "<input class='button' type='submit' name='save_email' value=\"".LAN_SAVE."\" />";
		}

		$text .="&nbsp;<input class='button' type='submit' name='send_email' value=\"".LAN_MAILOUT_08."\" />

		</div>

		</form>
		</div>";

		$this->e107->ns->tablerender(LAN_MAILOUT_15, $text);		// Render the complete form
	}


	// Helper function manages the shortcodes which can be inserted
	function sc_Select($container='sc_selector') 
	{
		$text ="
		<!-- Start of Shortcode selector -->\n
			<div style='margin-left:0px;margin-right:0px; position:relative;z-index:1000;float:right;display:none' id='{$container}'>
			<div style='position:absolute; bottom:30px; right:125px'>
			<table class='fborder' style='background-color: #fff'>
			<tr><td>
			<select class='tbox' name='sc_sel' onchange=\"addtext(this.value); this.selectedIndex= 0; expandit('{$container}')\">
			<option value=''> -- </option>\n";

			$sc = array(
				'|DISPLAYNAME|' => LAN_MAILOUT_14,
				'|USERNAME|' => LAN_MAILOUT_16,
				'|SIGNUP_LINK|' => LAN_MAILOUT_17,
				'|USERID|' => LAN_MAILOUT_18,
				'|USERLASTVISIT|' => LAN_MAILOUT_178
			);

			foreach($sc as $key=>$val)
			{
				$text .= "<option value='".$key."'>".$val."</option>\n";
			}
			$text .="
			</select></td></tr>	\n </table></div>
			</div>
		\n<!-- End of SC selector -->
		";

		return $text;
	}




	/**
	 * Return dropdown for arithmetic comparisons
	 * 
	 * @param $name  string name of select structure
	 * @param $curval string current value
	 * @return text for display
	 */
	public function comparisonSelect($name, $curval = '')
	{
		$compVals = array(' ' => ' ', '<' => LAN_MAILOUT_175, '=' => LAN_MAILOUT_176, '>' => LAN_MAILOUT_177);
		$ret = "<select name='{$name}' class='tbox'>\n";
		foreach ($compVals as $k => $v)
		{
			$selected = ($k == $curval) ? " selected='selected'" : '';
			$ret .= "<option value='".$k."' {$selected}>".$v."</option>\n";
		}
		$ret .= "</select>\n";
		return $ret;
	}


	/**
	 * Show a screen to confirm deletion of an email
	 * 
	 * @param $mailid - number of email
	 * @param $nextPage - 'mode' specification for page to return to following delete
	 * @return text for display
	 */
	public function showDeleteConfirm($mailID, $nextPage = 'saved')
	{
		$mailData = $this->retrieveEmail($mailID);
		
		$text = "<div style='text-align:center'>";

		if ($mailData === FALSE)
		{
			$text = "<div class='forumheader2' style='text-align:center'>".LAN_MAILOUT_79."</div>";
			$this->e107->ns-> tablerender("<div style='text-align:center'>".LAN_MAILOUT_171."</div>", $text);
			exit;
		}

		$text .= "
			<form action='".e_SELF.'?mode=maildeleteconfirm&amp;m='.$mailID.'&amp;savepage='.$nextPage."' id='email_delete' method='post'>
			<fieldset id='email-delete'>
			<table cellpadding='0' cellspacing='0' class='adminlist'>
			<colgroup span='2'>
				<col class='col-label' />
				<col class='col-control' />
			</colgroup>
			
			<tbody>";

		$text .= $this->showMailDetail($mailData, 'basic');
		$text .= '<tr><td>'.LAN_MAILOUT_172.'</td><td>'.$this->statusToText($mailData['mail_content_status'])."<input type='hidden' name='mailIDConf' value='{$mailID}' /></td></tr>";
		if ($mailData['mail_content_status'] != MAIL_STATUS_SAVED)
		{
			$text .= '<tr><td>'.LAN_MAILOUT_173.'</td><td>'.($mailData['mail_togo_count'] + $mailData['mail_sent_count'] + $mailData['mail_fail_count']).'</td></tr>';
		}

		$text .= "</tbody></table>\n</fieldset>";

		$text .= "<div class='buttons-bar center'>
			<input class='button' type='submit' name='email_delete' value=\"".LAN_DELETE."\" />
			&nbsp;<input class='button' type='submit' name='email_cancel' value=\"".LAN_CANCEL."\" />
		</div>";

		$text .= "</form></div>";
		$this->e107->ns->tablerender("<div style='text-align:center'>".ADLAN_136." :: ".LAN_MAILOUT_171."</div>", $text);
	}



	/**
	 * Generate the HTML to show a list of emails of a particular type, in tabular form
	 * 
	 * @param $type - type of email to display
	 * @param $from - offset into table of candidates
	 * @param $amount - number to return
	 * @return text for display
	 */
	public function showEmailList($type, $from = 0, $amount = 10)
	{
		// Need to select main email entries; count number of addresses attached to each
		$gen = new convert;
		$frm = e107::getForm();

		if ($from < 0) { $from = $this->showFrom; }
		if ($amount < 0) { $amount = $this->showCount; }
		// in $_GET, so = sort order, sf = sort field
		$count = $this->selectEmailStatus($from, $amount, '*', $type, $this->sortField, $this->sortOrder);
		$totalCount = $this->getEmailCount();
	  
		$emails_found = array();			// Log ID and count for later

		$text = "<div style='text-align:center'>";

		if (!$count)
		{
			$text = "<div class='forumheader2' style='text-align:center'>".LAN_MAILOUT_79."</div>";
			$this->e107->ns-> tablerender("<div style='text-align:center'>".$this->tasks[$type]['title']."</div>", $text);
			require_once(e_ADMIN."footer.php");
			exit;
		}

		$text .= "
			<form action='".e_SELF.'?'.e_QUERY."' id='email_list' method='post'>
			<fieldset id='emails-list'>
			<table cellpadding='0' cellspacing='0' class='adminlist'>";

		$fieldPrefs = $this->calcFieldSpec($type, TRUE);			// Get columns to display

		// Must use '&' rather than '&amp;' in query pattern
		$text .= $frm->colGroup($this->fields['mail_content'],$this->fieldPref).$frm->thead($this->fields['mail_content'],$this->fieldPref,'mode='.$type."&fld=[FIELD]&asc=[ASC]&frm=[FROM]")."<tbody>";

		while ($row = $this->getNextEmailStatus(FALSE))
		{
//			print_a($row);
			$text .= '<tr>';
			foreach ($fieldPrefs as $fieldName)
			{	// Output column data value
				$text .= '<td>';
				if (isset($row[$fieldName]))
				{
					$proctype = varset($this->fields['mail_content'][$fieldName]['proc'], 'default');
					switch ($proctype)
					{
						case 'username' :
							$text .= $this->getUserName($row[$fieldName]);
							break;
						case 'sdatetime' :
							$text .= $gen->convert_date($row[$fieldName], 'short');
							break;
						case 'trunc200' :
							$text .= $this->e107->tp->text_truncate($row[$fieldName], 200, '...');
							break;
						case 'contentstatus' :
							$text .= $this->statusToText($row[$fieldName]);
							break;
						case 'selectors' :
							$text .= 'cannot display';
							break;
						case 'yesno' :
							$text .= $row[$fieldName] ? LAN_YES : LAN_NO;
							break;
						case 'default' :
						default :
							$text .= $row[$fieldName];
					}
				}
				else
				{	// Special stuff
				}
				$text .= '</td>';
			}
			// Add in options here
			$text .= '<td>'.$this->makeMailOptions($type,$row).'</td>';
			$text .= '</tr>';
		}
		$text .= "</tbody></table><br /><br />\n";

		if ($totalCount > $count)
		{
			$parms = "{$totalCount},{$amount},{$from},".e_SELF."?mode={$type}&amp;count={$amount}&amp;frm=[FROM]&amp;fld={$this->sortField}&amp;asc={$this->sortOrder}";
			$text .= $this->e107->tp->parseTemplate("{NEXTPREV={$parms}}");
		}

		$text .= '</fieldset></form><br /></div>';
		$this->e107->ns->tablerender("<div style='text-align:center'>".ADLAN_136." :: ".$this->tasks[$type]['title']."</div>", $text);
	}




	/**
	 * Generate a list of emails to send
	 * Returns various information to display in a confirmation screen
	 *
	 * The email and its recipients are stored in the DB with a tag of 'MAIL_STATUS-TEMP'
	 * 
	 * @param $mailData array Details of the email, selection criteria etc
	 * @return text for display
	 */
	public function sendEmailCircular($mailData)
	{
		// Start by saving the email
		$mailData['mail_content_status'] = MAIL_STATUS_TEMP;
		$mailData['mail_create_app'] = 'core';
		$result = $this->saveEmail($mailData, TRUE);
		if (is_numeric($result))
		{
			$mailMainID = $mailData['mail_source_id'] = $result;
		}
		else
		{
				// TODO: Handle error
		}
  

		$this->mailInitCounters($mailMainID);			// Initialise counters for emails added

		foreach ($this->mailHandlers as $m)
		{	// Get email addresses from each handler in turn. Do them one at a time, so that all can use the $sql data object
			if ($m->mailerEnabled && isset($mailData['mail_selectors'][$m->mailerSource]))
			{
				// Initialise
				$mailerCount = $m->selectInit($mailData['mail_selectors'][$m->mailerSource]);
				if ($mailerCount > 0)
				{
					// Get email addresses - add to list, strip duplicates
					while ($row = $m->selectAdd()) 
					{	// Add email addresses to the database ready for sending (the body is never saved in the DB - it gets passed as a $_POST value)
						$result = $this->mailAddNoDup($mailMainID, $row, MAIL_STATUS_TEMP);
						if ($result === FALSE)
						{
							// Error
						}
					}
				}
				$m->select_close();	// Close
				// Update the stats after each handler
				$this->mailUpdateCounters($mailMainID);
			}
		}

		$counters = $this->mailRetrieveCounters($mailMainID);
//	$this->e107->admin_log->log_event('MAIL_02','ID: '.$mailMainID.' '.$counters['add'].'[!br!]'.$_POST['email_from_name']." &lt;".$_POST['email_from_email'],E_LOG_INFORMATIVE,'');



// We've got all the email addresses here - display a confirmation form
// Include start/end dates for send
		$text = "<div style='text-align:center'>";

		$text .= "
			<form action='".e_SELF.'?mode=marksend&amp;m='.$mailMainID.'&amp;savepage='.$nextPage."' id='email_send' method='post'>
			<fieldset id='email-send'>
			<table cellpadding='0' cellspacing='0' class='adminlist'>
			<colgroup span='2'>
				<col class='col-label' />
				<col class='col-control' />
			</colgroup>
			
			<tbody>";

		$text .= $this->showMailDetail($mailData, 'send');


		// Add in core and any plugin selectors here
		foreach ($mail_handlers as $m)
		{
			if ($m->mailer_enabled)
			{
				$text .= '<tr><td>'.LAN_MAILOUT_180.'<br />'.$m->mailer_name.'</td><td>'.$m->show_select(FALSE).'</td></tr>';
			}
		}

		// Figures - number of emails to send, number of duplicates stripped
		$text .= '<tr><td>'.LAN_MAILOUT_173.'</td><td>'.($mailData['mail_togo_count'])."<input type='hidden' name='mailIDConf' value='{$mailID}' /></td></tr>";
		$text .= '<tr><td>'.LAN_MAILOUT_71.'</td><td> '.$counters['add'].' '.LAN_MAILOUT_69.$counters['dups'].LAN_MAILOUT_70.'</td></tr>';
		
		$text .= "<tr><td colspan='2'>Add in start/end dates here</td></tr>";
		$text .= "</tbody></table>\n</fieldset>";

		$text .= "<div class='buttons-bar center'>
			<input class='button' type='submit' name='email_send' value=\"".LAN_SEND."\" />
			&nbsp;<input class='button' type='submit' name='email_hold' value=\"".LAN_HOLD."\" />
			&nbsp;<input class='button' type='submit' name='email_cancel' value=\"".LAN_CANCEL."\" />
		</div>";

		$this->e107->ns->tablerender("<div style='text-align:center'>".ADLAN_136." :: ".LAN_MAILOUT_179."</div>", $text);

//	$text .= "</table></div>";

	}	// End of previewed email





	/**
	 * Show recipients of an email
	 * 
	 * @param $mailid - number of email
	 * @param $nextPage - 'mode' specification for page to return to following delete
	 * @return text for display
	 */
	public function showmailRecipients($mailID, $nextPage = 'saved')
	{
		$gen = new convert;
		$frm = e107::getForm();

		$mailData = $this->retrieveEmail($mailID);
		
		$text = "<div style='text-align:center'>";

		if ($mailData === FALSE)
		{
			$text = "<div class='forumheader2' style='text-align:center'>".LAN_MAILOUT_79."</div>";
			$this->e107->ns-> tablerender("<div style='text-align:center'>".LAN_MAILOUT_171."</div>", $text);
			exit;
		}

		$text .= "
			<form action='".e_SELF.'?'.e_QUERY."' id='email_recip_header' method='post'>
			<fieldset id='email-recip_header'>
			<table cellpadding='0' cellspacing='0' class='adminlist'>
			<colgroup span='2'>
				<col class='col-label' />
				<col class='col-control' />
			</colgroup>
			
			<tbody>";

		$text .= $this->showMailDetail($mailData, 'basic');
		$text .= '<tr><td>'.LAN_MAILOUT_172.'</td><td>'.$this->statusToText($mailData['mail_content_status'])."<input type='hidden' name='mailIDConf' value='{$mailID}' /></td></tr>";
		if ($mailData['mail_content_status'] != MAIL_STATUS_SAVED)
		{
			$text .= '<tr><td>'.LAN_MAILOUT_173.'</td><td>'.($mailData['mail_togo_count'] + $mailData['mail_sent_count'] + $mailData['mail_fail_count']).'</td></tr>';
		}

		$text .= "</tbody></table>\n</fieldset></form>";


// List of recipients
		// in $_GET, asc = sort order, fld = sort field
		$count = $this->selectTargetStatus($mailID, $this->showFrom, $this->showCount, '*', FALSE, $this->sortField, $this->sortOrder);
		$totalCount = $this->getTargetCount();

		$text .= "
			<form action='".e_SELF."?mode=recipients&amp;m={$mailID}&amp;count={$count}&amp;frm={$this->showFrom}&amp;fld={$this->sortField}&amp;asc={$this->sortOrder}&amp;savepage={$nextPage}' id='email_recip_body' method='post'>
			<fieldset id='email-recip_body'>
			<table cellpadding='0' cellspacing='0' class='adminlist'>";


		$fieldPrefs = $this->calcFieldSpec('recipients', TRUE);			// Get columns to display

		// Must use '&' rather than '&amp;' in query pattern
		$text .= $frm->colGroup($this->fields['mail_recipients'],$this->fieldPref).$frm->thead($this->fields['mail_recipients'],$this->fieldPref,'mode='.'recipients&amp;m='.$mailID."&fld=[FIELD]&asc=[ASC]&frm=[FROM]")."<tbody>";

		while ($row = $this->getNextTargetStatus(FALSE))
		{
//			print_a($row);
			$text .= '<tr>';
			foreach ($fieldPrefs as $fieldName)
			{	// Output column data value
				$text .= '<td>';
				if (isset($row[$fieldName]))
				{
					$proctype = varset($this->fields['mail_recipients'][$fieldName]['proc'], 'default');
					switch ($proctype)
					{
						case 'username' :
							$text .= $this->getUserName($row[$fieldName]);
							break;
						case 'sdatetime' :
							$text .= $gen->convert_date($row[$fieldName], 'short');
							break;
						case 'trunc200' :
							$text .= $this->e107->tp->text_truncate($row[$fieldName], 200, '...');
							break;
						case 'contentstatus' :
							$text .= $this->statusToText($row[$fieldName]);
							break;
						case 'selectors' :
							$text .= 'cannot display';
							break;
						case 'array' :
							if (is_array($row[$fieldName]))
							{
								$nl = '';
								foreach ($row[$fieldName] as $k => $v)
								{
									if ($v)
									{
										$text .= $nl.$k.' => '.$v;
										$nl = '<br />';
									}
								}
							}
							else
							{
								$text .= 'bad data: ';
							}
							break;
						case 'default' :
						default :
							$text .= $row[$fieldName];
					}
				}
				else
				{	// Special stuff
					$text .= 'special';
				}
				$text .= '</td>';
			}
			// Add in options here
			$text .= '<td>'.$this->makeTargetOptions('recipients',$row).'</td>';
			$text .= '</tr>';
		}

		$text .= "</tbody></table>\n</fieldset></form><br /><br />";

		if ($totalCount > $count)
		{
			$parms = "{$totalCount},{$this->showCount},{$this->showFrom},".e_SELF."?mode=recipients&amp;m={$mailID}&amp;count={$this->showCount}&amp;frm=[FROM]&amp;fld={$this->sortField}&amp;asc={$this->sortOrder}&amp;savepage={$nextPage}";
			$text .= $this->e107->tp->parseTemplate("{NEXTPREV={$parms}}");
		}

		$text .= "</div>";

		$this->e107->ns->tablerender("<div style='text-align:center'>".ADLAN_136." :: ".LAN_MAILOUT_181."</div>", $text);
	}


	public function dbTidy()
	{
		$this->checkDB(2);			// Make sure DB object created
		if ($this->db2->db_Delete('mail_content', '`mail_content_status` = '.MAIL_STATUS_TEMP) === FALSE)
		{
			return FALSE;
		}
		if ($this->db2->db_Delete('mail_recipients', '`mail_status` = '.MAIL_STATUS_TEMP) === FALSE)
		{
			return FALSE;
		}
		return TRUE;
	}
}


?>
