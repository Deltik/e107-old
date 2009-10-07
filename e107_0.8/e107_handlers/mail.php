<?php
/*
 * e107 website system
 *
 * Copyright (C) 2001-2008 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * e107 Main
 *
 * $Source: /cvs_backup/e107_0.8/e107_handlers/mail.php,v $
 * $Revision: 1.13 $
 * $Date: 2009-09-01 19:53:07 $
 * $Author: e107steved $
*/

/*
This is a work in progress, upgrading the mailout routines to use the PHP5-compatible PHPMailer classes in a class-based structure

A further class extension will add features for mass-mailouts


Three main scenarios to handle:
	- Individual email to a number of recipients (analagous to 'manual' email sending). May be called multiple times in a run
	- One email send individually to a number of recipients (probably based on array of senders)
	- Personalised mailshots - template email modified to personalise for each recipient (based on DB list)

TODO:
1. Look at VERP - implement and check
2. Bulk mailing - look at using the batching constants with SMTPKeepAlive to reset the connection every so often
3. mail (PHP method) - note that it has parameters for additional headers and other parameters
4. Check that language support works - PHPMailer defaults to English if other files not available
		- PHPMailer expects a 2-letter code - $this->SetLanguage(CORE_LC)     - e.g. 'en', 'br'
5. Logging:
	- Use rolling log for errors - error string(s) available - sort out entry
	- Look at support of some other logging options
	- Debug option to just log, not send emails (variables in place, and supported by current bulk mailer)
6. Capability to identify and log caller (for tracking down which bit of code sent emails)
7. Look at how cc, bcc addresses are handled in bulk mailing scenarios - may need to disable them on later sends.
8. Include theme info in header if enabled?
9. Make sure SMTPDebug can be set (TRUE/FALSE)
10. Get rid of mime_content_type() - deprecated (function $this->mime_types() does something similar, given a file extension)
11. Add public/protected/private to all functions and variables
12. Check support for port number - ATM we just override for SSL. Looks as if phpmailer can take it from end of server link.
13. Possibly strip bbcode from plain text mailings
14. Note option for class constants
16. Add a routine or class which adds a list of recipients and an email to the mailout DB
18. Note object iteration - may be useful for dump of object state
19. Consider overriding error handler
20. Look at using new prefs structure
21. Should we always send an ID?


Tested so far (with PHP4 version)
------------
SMTP send (standard)
Return receipts
text or mixed
replyto
priority field
TLS to googlemail (use TLS)


Notes if problems
-----------------
1. Attachment adding call had dirname() added round path.
2. There are legacy and new methods for generating a multi-part body (HTML + plain text). Only the new method handles inline images.
		- Currently uses the new method (which is part of phpmailer)


General notes
-------------
1. Can specify a comma-separated list of smtp servers - presumably all require the same login credentials
2. qmail can be used (if available) by selecting sendmail, and setting the sendmail path to that for qmail instead
3. phpmailer does trim() on passed parameters where needed - so we don't need to.
4. phpmailer has its own list of MIME types.
5. Attachments - note method available for passing string attachments
		- AddStringAttachment($string,$filename,$encoding,$type)
6. Several email address-related methods can accept two comma-separated strings, one for addresses and one for related names
7. Its possible to send a text-only email by passing an array of parameters including 'send_html' = FALSE
8. For bulk emailing, must call the 'allSent()' method when complete to ensure SMTP mailer is properly closed.
9. For sending through googlemail (and presumably gmail), use TLS
10. Note that the 'add_html_header' option adds only the DOCTYPE bits - not the <head>....</head> section


Possible Enhancements
---------------------
1. Support other fields:
		ContentType
		Encoding    - ???. Defaults to 8-bit



Preferences used:
	$pref['mailer'] - connection type - SMTP, sendmail etc
	
	$pref['mail_options'] - NEW - general mailing options
		textonly 		- if true, defaults to plain text emails
		hostname=text	- used in Message ID and received headers, and default Helo string. (Otherwise server-related default used)
	
	$pref['mail_log_options'] - NEW. Logging options (also used in mailout_process). Comma-separated list of values
		1 - logenable		- numeric value 0..3 controlling logging to a text file
		2 - add_email		- if '1', the detail of the email is logged as well

	$pref['smtp_server']	|
	$pref['smtp_username']	| Server details. USed for POP3 server if POP before SMTP authorisation
	$pref['smtp_password']	|
	$pref['smtp_keepalive'] - deprecated in favour of option - flag
	$pref['smtp_pop3auth'] - deprecated in favour of option - POP before SMTP authorisation flag
	$pref['smtp_options'] - NEW - comma separated list:
		keepalive			- If active, bulk email send keeps the SMTP connection open, closing it every $pref['mail_pause'] emails
		useVERP				- formats return path to facilitate bounce processing
		secure=[TLS|SSL]	- enable secure authorisation by TLS or SSL
		pop3auth			- enable POP before SMTP authorisation
		helo=text			- Alternative Helo string

	$pref['sendmail'] - path to sendmail

	$pref['mail_pause'] - number of emails to send before pause
	$pref['mail_pausetime'] - time to pause

	$pref['mail_bounce_email'] - 'reply to' address
	$pref['mail_bounce_pop3']
	$pref['mail_bounce_user']
	$pref['mail_bounce_pass']

Usage
=====
1. Create new object of the correct class
2. Set up everything - to/from/email etc
3. Call create_connection()
4. Call send_mail()
+----------------------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }


//define('MAIL_DEBUG',TRUE);
define('LOG_CALLER', TRUE);

require_once(e_HANDLER.'phpmailer/class.phpmailer.php');

// Directory for log (if enabled)
define('MAIL_LOG_PATH',e_FILE.'logs/');

class e107Email extends PHPMailer
{
	private $general_opts = array();
	private $logEnable = 0;				// 0 = log disabled, 1 = 'dry run' (debug and log, no send). 2 = 'log all' (send, and log result)
	private $logHandle = FALSE;			// Save handle of log file if opened

	private $localUseVerp = FALSE;		// Use our own variable - PHPMailer one doesn't work with all mailers
	private $save_bouncepath = '';		// Used with VERP

	private $add_email = 0;				// 1 includes email detail in log (if logging enabled, of course)
	private $allow_html = 1;			// Flag for HTML conversion - '1' = default, FALSE = disable, TRUE = force.
	private $add_HTML_header = FALSE;	// If TRUE, inserts a standard HTML header at the front of the HTML part of the email (set FALSE for BC)
	private $SendCount = 0;				// Keep track of how many emails sent since last SMTP open/connect (used for SMTP KeepAlive)
	private $TotalSent = 0;				// Info might be of interest
	private $TotalErrors = 0;			// Count errors in sending emails
	private $pause_amount = 10;			// Number of emails to send before pausing/resetting (or closing if SMTPkeepAlive set)
	private $pause_time = 1;			// Time to pause after sending a block of emails


	/**
	 * Constructor sets up all the global options, and sensible defaults - it should be the only place the prefs are accessed
	 * 
	 * @var array $overrides - array of values which override mail-related prefs. Key is the same as the corresponding pref.
	 * @return none
	 */
	public function __construct($overrides = FALSE) 
	{
		parent::__construct(FALSE);		// Parent constructor - no exceptions for now

		$e107 = e107::getInstance();
		global $pref;

		$this->CharSet = 'utf-8';
		$this->SetLanguage(CORE_LC);

		if (($overrides === FALSE) || !is_array($overrides))
		{
			$overrides = array();
		}
		
		foreach (array('mailer', 'smtp_server', 'smtp_username', 'smtp_password', 'sendmail', 'siteadminemail', 'siteadmin', 'smtp_pop3auth') as $k)
		{
			if (!isset($overrides[$k])) $overrides[$k] = $pref[$k];
		}
		$this->pause_amount = varset($pref['mail_pause'], 10);
		$this->pause_time =  varset($pref['mail_pausetime'], 1);

		if (varsettrue($pref['mail_options'])) $this->general_opts = explode(',',$pref['mail_options'],'');
		if (defined('MAIL_DEBUG')) echo 'Mail_options: '.$pref['mail_options'].' Count: '.count($this->general_opts).'<br />';
		foreach ($this->general_opts as $k => $v) 
		{
			$v = trim($v);
			$this->general_opts[$k] = $v;
			if (strpos($v,'hostname') === 0)
			{
				list(,$this->HostName) = explode('=',$v);
				if (defined('MAIL_DEBUG')) echo "Host name set to: {$this->HostName}<br />";
			}
		}

		list($this->logEnable,$this->add_email) = explode(',',varset($pref['mail_log_options'],'0,0'));

		switch ($overrides['mailer'])
		{
			case 'smtp' :
				$smtp_options = array();
				$temp_opts = explode(',',varset($pref['smtp_options'],''));
				if (varsettrue($overrides ['smtp_pop3auth'])) $temp_opts[] = 'pop3auth';		// Legacy option - remove later
				if (varsettrue($pref['smtp_keepalive'])) $temp_opts[] = 'keepalive';	// Legacy option - remove later
				foreach ($temp_opts as $k=>$v) 
				{ 
					if (strpos($v,'=') !== FALSE)
					{
						list($v,$k) = explode('=',$v,2);
						$smtp_options[trim($v)] = trim($k);
					}
					else
					{
						$smtp_options[trim($v)] = TRUE;		// Simple on/off option
					}
				}
				unset($temp_opts);

				$this->IsSMTP();			// Enable SMTP functions
				if (varsettrue($smtp_options['helo'])) $this->Helo = $smtp_options['helo'];

				if (isset($smtp_options['pop3auth']))			// We've made sure this is set
				{	// Need POP-before-SMTP authorisation
					require_once(e_HANDLER.'phpmailer/class.pop3.php');
					$pop = new POP3();
					$pop->Authorise($overrides['smtp_server'], 110, 30, $overrides['smtp_username'], $overrides['smtp_password'], 1);
				}

				$this->Mailer = "smtp";
				$this->localUseVerp = isset($smtp_options['useVERP']);
				if (isset($smtp_options['secure']))
				{
					switch ($smtp_options['secure'])
					{
						case 'TLS' :
							$this->SMTPSecure = 'tls';
							$this->Port = 465;		// Can also use port 587
							break;
						case 'SSL' :
							$this->SMTPSecure = 'ssl';
							$this->Port = 465;
							break;
						default :
							echo "Invalid option: {$smtp_options['secure']}<br />";
					}
				}
				$this->SMTPKeepAlive = varset($smtp_options['keepalive'],FALSE);									// ***** Control this
				$this->Host = $overrides['smtp_server'];
				if($overrides['smtp_username'] && $overrides['smtp_password'])
				{
					$this->SMTPAuth = (!isset($smtp_options['pop3auth']));
					$this->Username = $overrides['smtp_username'];
					$this->Password = $overrides['smtp_password'];
				}
				break;
			case 'sendmail' :
				$this->Mailer = 'sendmail';
				$this->Sendmail = ($overrides['sendmail']) ? $overrides['sendmail'] : '/usr/sbin/sendmail -t -i -r '.varsettrue($pref['replyto_email'],$overrides['siteadminemail']);
				break;
			case 'php' :
				$this->Mailer = 'mail';
				break;
		}
		if (varsettrue($pref['mail_bounce_email'])) $this->Sender = $pref['mail_bounce_email'];

		$this->FromName = $e107->tp->toHTML(varsettrue($pref['replyto_name'],$overrides['siteadmin']),'','RAWTEXT');
		$this->From = $e107->tp->toHTML(varsettrue($pref['replyto_email'],$overrides['siteadminemail']),'','RAWTEXT');
		$this->WordWrap = 76;			// Set a sensible default

		// Now look for any overrides - slightly cumbersome way of doing it, but does give control over what can be set from here
		// Options are those accepted by the arraySet() method.
		foreach (array('SMTPDebug', 'subject', 'from', 'fromname', 'replyto', 'send_html', 'add_html_header', 'attachments', 'cc', 'bcc', 
						'bouncepath', 'returnreceipt', 'priority', 'extra_header', 'wordwrap', 'split') as $opt)
		{
			if (isset($overrides[$opt]))
			{
				$this->arraySet(array($opt => $overrides[$opt]));
			}
		}
	}



	/**
	 * Format 'to' address and name
	 * 
	 * @param string $email - email address of recipient
	 * @param string $to - name of recipient
	 * @return string in form: Fred Bloggs<fred.bloggs@somewhere.com>
	 */
	public function makePrintableAddress($email,$to)
	{
		$to = trim($to);
		$email = trim($email);
		return $to.' <'.$email.'>';
	}


	/**
	 * Log functions - write to a log file
	 * Each entry logged to a separate line
	 * 
	 * @return none
	 */
	protected function openLog($logInfo = TRUE)
	{
		if ($this->logEnable && ($this->logHandle === FALSE))
		{
			$logFileName = MAIL_LOG_PATH.'mailoutlog.txt';
			$this->logHandle = fopen($logFileName, 'a');      // Always append to file
		}
		if ($this->logHandle !== FALSE)
		{
			fwrite($this->logHandle,"=====".date('H:i:s y.m.d')."----------------------------------------------------------------=====\r\n");
			if ($logInfo)
			{
				fwrite($this->logHandle,'  Start of mail run by '.USERNAME." - {$count} emails to go. ID: {$mail_id}. Subject: {$mail_subject}\r\n");
				if ($this->add_email)
				{
					fwrite($this->logHandle, 'From: '.$this->From.' ('.$this->FromName.")\r\n");
					fwrite($this->logHandle, 'Sender: '.$this->Sender."\r\n");
					fwrite($this->logHandle, 'Subject: '.$this->Subject."\r\n");
					// Following are private variables ATM
//					fwrite($this->logHandle, 'CC: '.$email_info['copy_to']."\r\n");
//					fwrite($this->logHandle, 'BCC: '.$email_info['bcopy_to']."\r\n");
//					fwrite($this->logHandle, 'Attach: '.$attach."\r\n");
					fwrite($this->logHandle, 'Body: '.$this->Body."\r\n");
					fwrite($this->logHandle,"-----------------------------------------------------------\r\n");
				}
			}
			if (defined('LOG_CALLER'))
			{
				$temp = debug_backtrace();
				foreach ($temp as $t)
				{
					if (!isset($t['class']) || ($t['class'] != 'e107Email'))
					{
						fwrite($this->logHandle, print_a($t,TRUE)."\r\n");		// Found the caller
						break;
					}
				}
			}
		}
	}

	protected function logLine($text)
	{
		if ($this->logEnable && ($this->logHandle > 0))
		{
			fwrite($this->logHandle,date('H:i:s y.m.d').' - '.$text."\r\n");
		}
	}

	protected function closeLog()
	{
		if ($this->logEnable && ($this->logHandle > 0))
		{
			fclose($this->logHandle);
		}
	}



	/**
	 * Add a list of addresses to one of the address lists.
	 * @param string $list - 'to', 'replyto', 'cc', 'bcc'
	 * @param string $addresses - comma separated
	 * @param string $names - either a single name (used for all addresses) or a comma-separated list corresponding to the address list
	 * If the name field for an entry is blank, or there are not enough entries, the address is substituted
	 * @return TRUE if list accepted, FALSE if invalid list name
	 */
	public function AddAddressList($list = 'to',$addresses,$names = '')
	{
		$list = trim(strtolower($list));
		$tmp = explode(',',$addresses);

		if (strpos($names,',') === FALSE)
		{
			$names = array_fill(0,count($tmp),$names);		// Same value for all addresses
		}
		else
		{
			$names = explode(',',$names);
		}
		foreach($tmp as $k => $adr)
		{
			$to_name = ($names[$k]) ? $names[$k] : $adr;
			switch ($list)
			{
				case 'to' :
					$this->AddAddress($adr, $to_name);
					break;
				case 'replyto' :
					$this->AddReplyTo($adr, $to_name);
					break;
				case 'cc' :
					if($this->Mailer == 'mail')
					{
						$this->AddCustomHeader('Cc: '.$adr);
					}
					else
					{
						$this->AddCC($adr, $to_name);
					}
					break;
				case 'bcc' :
					if($this->Mailer == 'mail')
					{
						$this->AddCustomHeader('Bcc: '.$adr);
					}
					else
					{
						$this->AddBCC($adr, $to_name);
					}
					break;
				default :
					return FALSE;
			}
		}
		return TRUE;
	}




  // New method of making a body uses the inbuilt functionality of phpmailer
  // $want_HTML= 1 uses default setting for HTML part. Set TRUE to enable, FALSE to disable
  // $add_HTML_header - if TRUE, a standard HTML header is added to the front of the HTML part
	public function makeBody($message,$want_HTML = 1, $add_HTML_header = FALSE)
	{
		switch (varset($this->general_opts['textonly'],'off'))
		{
		  case 'pref' :		// Disable HTML as default
			if ($want_HTML == 1) $want_HTML = FALSE;
			break;
		  case 'force' :	// Always disable HTML
			$want_HTML = FALSE;
			break;
		}
	
		if ($want_HTML !== FALSE)
		{
			if (defined('MAIL_DEBUG')) echo "Generating multipart email<br />";
			if ($add_HTML_header)
			{
				$message = 	"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n
				<html xmlns='http://www.w3.org/1999/xhtml' >\n".$message;
			}
			$this->MsgHTML($message);		// Theoretically this should do everything, including handling of inline images.
		}
		else
		{
	// generate the plain text part
			if (defined('MAIL_DEBUG')) echo "Generating plain text email<br />";
			if (strpos($message,'</style>') !== FALSE)
			{
				$text = strstr($message,'</style>');
			}
			else
			{
				$text = $message;
			}

			$text = str_replace('<br />', "\n", $text);
			$text = strip_tags(str_replace('<br>', "\n", $text));
			
			// TODO: strip bbcodes here

			$this->Body = $text;
		}
	}

 
	// Legacy way of creating an HTML and a text part - as used in 0.7
	// $want_HTML= 1 uses default setting for HTML part. Set TRUE to enable, FALSE to disable
	function makeBodyLegacy($message,$want_HTML = 1, $add_HTML_header = FALSE)
	{
		switch (varset($this->general_opts['textonly'],'off'))
		{
			case 'pref' :		// Disable HTML as default
				if ($want_HTML == 1) $want_HTML = FALSE;
				break;
			case 'force' :	// Always disable HTML
				$want_HTML = FALSE;
				break;
		}

		if ($want_HTML)
		{
			if (preg_match('/<(font|br|a|img|b)/i', $message)) 
			{
				$Html = $message; // Assume html if it includes one of these tags
			} 
			else 
			{
				$Html = htmlspecialchars($message);
				$Html = preg_replace('%(http|ftp|https)(://\S+)%', '<a href="\1\2">\1\2</a>', $Html);
				$Html = preg_replace('/([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&\/\/=]+)/i', '\\1<a href="http://\\2">\\2</a>', $Html);
				$Html = preg_replace('/([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})/i', '<a href="mailto:\\1">\\1</a>', $Html);
				$Html = str_replace("\r\n","\n",$Html);		// Handle alternative newline characters
				$Html = str_replace("\n\r","\n",$Html);		// Handle alternative newline characters
				$Html = str_replace("\r","\n",$Html);		// Handle alternative newline characters
				$Html = str_replace("\n", "<br />\n", $Html);
			}
			if ($add_HTML_header)
			{
				$this->Body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n
				<html xmlns='http://www.w3.org/1999/xhtml' >\n".$Html;
			}
			else
			{
				$this->Body = $Html;
			}
		}

		// Now generate the plain text part - always have one
		if (strpos($message,"</style>") !== FALSE)
		{
			$text = strstr($message,"</style>");
		}
		else
		{
			$text = $message;
		}

		$text = str_replace("<br />", "\n", $text);
		$text = strip_tags(str_replace("<br>", "\n", $text));

		if ($want_HTML)
		{
		  $this->AltBody = $text;
		  $this->IsHTML = TRUE;
		}
		else
		{
		  $this->Body = $text;
		}
	}  
  

	// Add attachments - either a single one as a string, or an array  
	public function attach($attachments)
	{
		if (!$attachments) return;
		if (!is_array($attachments)) $attachments = array($attachments);

		foreach($attachments as $attach)
		{
			if(is_readable($attach))
			{
				$mail->AddAttachment(dirname($attach), basename($attach),"base64",$this->_mime_types($attach));
			}
		}
	}


	// Add inline images (should mostly be handled automatically)
	function addInlineImages($inline)
	{
		if(!$inline) return;
		$tmp = explode(",",$inline);
		foreach($tmp as $inline_img)
		{
			if(is_readable($inline_img) && !is_dir($inline_img))
			{
				$mail->AddEmbeddedImage($inline_img, md5($inline_img), basename($inline_img),"base64",mime_content_type($inline_img));
			}
		}
	}


	// Sets one or more parameters from an array. See send_array() for list of parameters
	// Where parameter not present, doesn't change it - so can repeatedly call this function for bulk mailing, or to build up the list
	// Return 0 on success.
	// (Note that there is no requirement to use this method for everything; parameters can be set by mixing this method with individual setting)
	public function arraySet($paramlist)
	{
		if (isset($paramlist['SMTPDebug'])) $this->SMTPDebug = $paramlist['SMTPDebug'];			// 'FALSE' is a valid value!
		if (varsettrue($paramlist['subject'])) $this->Subject = $paramlist['subject'];
		if (varsettrue($paramlist['from'])) $this->From = $paramlist['from'];
		if (varsettrue($paramlist['fromname'])) $this->FromName = $paramlist['fromname'];
		if (varsettrue($paramlist['replyto'])) $this->AddAddressList('replyto',$paramlist['replyto'],varsettrue($paramlist['replytonames'],''));	
		if (isset($paramlist['send_html'])) $this->allow_html = $paramlist['send_html'];							// 'FALSE' is a valid value!
		if (isset($paramlist['add_html_header'])) $this->add_HTML_header = $paramlist['add_html_header'];			// 'FALSE' is a valid value!
		if (varsettrue($paramlist['message'])) $this->makeBody($paramlist['message'], $this->allow_html, $this->add_HTML_header);
		if (varsettrue($paramlist['attachments'])) $this->attach($paramlist['attachments']);
		if (varsettrue($paramlist['cc'])) $this->AddAddressList('cc',$paramlist['cc'],varsettrue($paramlist['ccnames'],''));
		if (varsettrue($paramlist['bcc'])) $this->AddAddressList('bcc',$paramlist['bcc'],varsettrue($paramlist['bccnames'],''));
		if (varsettrue($paramlist['bouncepath'])) 
		{
			$this->Sender = $paramlist['bouncepath'];				// Bounce path
			$this->save_bouncepath = $paramlist['bouncepath'];		// Bounce path
		}
		if (varsettrue($paramlist['returnreceipt'])) $this->ConfirmReadingTo = $paramlist['returnreceipt'];
		if (varsettrue($paramlist['inline-images'])) $this->addInlineImages($paramlist['inline-images']);
		if (varsettrue($paramlist['priority'])) $this->Priority = $paramlist['priority'];
		if (varsettrue($paramlist['extra_header'])) 
		{
			if (is_array($paramlist['extra_header']))
			{
				foreach($paramlist['extra_header'] as $eh)
				{
					$this->addCustomHeader($eh);
				}
			}
			else
			{
				$this->addCustomHeader($paramlist['extra_header']);
			}
		}

		if (varset($paramlist['wordwrap'])) $this->WordWrap = $paramlist['wordwrap'];
		if (varsettrue($paramlist['split'])) $this->SingleTo = ($paramlist['split'] != FALSE);

		return 0;				// No error
	}


	  // Send an email where the bulk of the data is passed in an array. Returns 0 on success.
	  // (Even if the array is null, because everything previously set up, this is the preferred entry point)
	  // Where parameter not present in the array, doesn't get changed - useful for bulk mailing
	  // If doing bulk mailing with repetitive calls, set $bulkmail parameter true, and must call allSent() when completed
	  // Some of these parameters have been made compatible with the array calculated by render_email() in signup.php
	  // Possible array parameters:
	  // $eml['subject']
	  // $eml['from']
	  // $eml['fromname']
	  // $eml['replyto']		- Optional 'reply to' field 
	  // $eml['replytonames']	- Name(s) corresponding to 'reply to' field  - only used if 'replyto' used
	  // $eml['send_html']		- if TRUE, includes HTML part in messages (only those added after this flag)
	  // $eml['add_html_header'] - if TRUE, adds the 2-line DOCTYPE declaration to the front of the HTML part (but doesn't add <head>...</head>
	  // $eml['message']		- message body. May be HTML or text. Added according to the current state of the HTML enable flag
	  // $eml['attachments']	- string if one file, array of filenames if one or more.
	  // $eml['cc']				- comma-separated list
	  // $eml['bcc']			- comma-separated list
	  // $eml['bouncepath']		- Sender field (used for bounces)
	  // $eml['returnreceipt']	- email address for notification of receipt (reading)
	  // $eml['inline-images']	- array of files for inline images
	  // $eml['priority']		- Email priority (1 = High, 3 = Normal, 5 = low)
	  // $eml['extra_header']	- additional headers
	  // $eml['wordwrap']		- Set wordwrap value
	  // $eml['split']			- If true, sends an individual email to each recipient
	public function sendEmail($send_to, $to_name, $eml = '', $bulkmail = FALSE)
	{
//		$e107 = e107::getInstance();
		if (count($eml))
		{	// Set parameters from list
			$ret = $this->arraySet($eml);
			if ($ret) return $ret;
		}

		if ($bulkmail && $this->localUseVerp && $this->save_bouncepath && (strpos($this->save_bouncepath,'@') !== FALSE))
		{
			// Format where sender is owner@origin, target is user@domain is: owner+user=domain@origin
			list($our_sender,$our_domain) = explode('@', $this->save_bouncepath,2);
			if ($our_sender && $our_domain)
			{
				$this->Sender = $our_sender.'+'.str_replace($send_to,'@','=').'@'.$our_domain; 
			}
		}

		$this->AddAddressList('to',$send_to,$to_name);

		$this->openLog();		// Delay log open until now, so all parameters set up

		$result = TRUE;			// Temporary 'success' flag
		$this->SendCount++;

		if (($this->logEnable == 0) || ($this->logEnable == 2))
		{
			$result = $this->Send();		// Actually send email

			if (!$bulkmail && $this->SMTPKeepAlive && ($this->Mailer == 'smtp')) $this->SmtpClose();
		}
		else
		{	// Debug
			$result = TRUE;
			if (($logenable == 3) && (($this->SendCount % 7) == 4)) $result = FALSE;			// Fail one email in 7 for testing
		}

		$this->TotalSent++;
		if (($this->pause_amount > 0) && ($this->SendCount >= $this->pause_amount))
		{
			if ($this->SMTPKeepAlive && ($this->Mailer == 'smtp')) $this->SmtpClose();
			sleep($this->pause_time);
			$this->SendCount = 0;
		}

		$this->logLine("Send to {$to_name} at {$send_to} Mail-ID={$mail_custom} - {$result}");

		$this->ClearAddresses();			// In case we send another email
		$this->ClearCustomHeaders();

		if ($result) return TRUE;
		
		// Error sending email
		$e107 = e107::getInstance();
		$e107->admin_log->e_log_event(3,debug_backtrace(),"MAIL","Send Failed",$this->ErrorInfo,FALSE,LOG_TO_ROLLING);
		$this->TotalErrors++;
		return $this->ErrorInfo;
	}


	// Called after a bulk mailing completed, to tidy up nicely
	public function allSent()
	{
		if ($this->SMTPKeepAlive && ($this->Mailer == 'smtp') && ($this->SendCount > 0)) 
		{
			$this->SmtpClose();
			$this->SendCount = 0;
		}
	}
}



//-----------------------------
//		Exception handler
//-----------------------------
// Overrides the phpmailer handler
// For now just work the same as the phpmailer handler - maybe add features to log to rolling log or something later
// Could throw an e107Exception
class e107MailerException extends phpmailerException 
{
	public function errorMessage() 
	{
		return parent::errorMsg();
	}
}


//--------------------------------------
//		Generic e107 Exception handler
//--------------------------------------
// Overrides the default handler - start of a more general handler
class e107Exception extends Exception 
{
    public function __construct($message = '', $code = 0) 
	{
        parent::__construct($message, $code);
		$e107 = e107::getInstance();
		$e107->admin_log->e_log_event(10,
									$this->getFile().'|@'.$this->getLine(),
									'EXCEPT',
									$this->getCode().':'.$this->getMessage(),
									$this->getTraceAsString(),
									FALSE,
									LOG_TO_ROLLING);
    }
}


//-----------------------------------------------------
//		Legacy interface for backward compatibility
//-----------------------------------------------------
// (Preferred interface is to instantiate an e107_mail object, then call sendEmail method with an array of parameters

// If $send_from is blank, uses the 'replyto' name and email if set, otherwise site admins details
// $inline is a comma-separated list of embedded images to be included
function sendemail($send_to, $subject, $message, $to_name, $send_from='', $from_name='', $attachments='', $Cc='', $Bcc='', $returnpath='', $returnreceipt='',$inline ='') 
{
	global $mailheader_e107id;


	$overrides = array();
	// TODO: Find a way of doing this which doesn't use a global (or just ditch sendemail() )
    // ----- Mail pref. template override for parked domains, site mirrors or dynamic values
    global $EMAIL_OVERRIDES;
	if (isset($EMAIL_OVERRIDES) && is_array($EMAIL_OVERRIDES))
	{
		$overrides = &$EMAIL_OVERRIDES;		// These can override many of the email-related prefs
		if (isset($EMAIL_OVERRIDES['bouncepath'])) $returnpath = $EMAIL_OVERRIDES['bouncepath'];
		if (isset($EMAIL_OVERRIDES['returnreceipt'])) $returnreceipt = $EMAIL_OVERRIDES['returnreceipt'];
	}

	// Create a mailer object of the correct type (which auto-fills in sending method, server details)
	$mail = new e107Email($overrides);

	if (varsettrue($mailheader_e107id)) $mail->AddCustomHeader("X-e107-id: {$mailheader_e107id}");

	$mail->makeBody($message);				// Add body, with conversion if required

	if($Cc) $mail->AddAddressList('cc', $Cc);

	if ($Bcc) $mail->AddAddressList('bcc', $Bcc);

	if (trim($send_from))
	{
		$mail->SetFrom($send_from, $from_name);				// These have already been defaulted to sitewide options, so no need to set again if blank
	}

	$mail->Subject = $subject;

	$mail->attach($attachments);

	// Add embedded images (should be auto-handled now)
	if ($inline) $mail->addInlineImages($inline);

	// Passed parameter overrides any system default for bounce - but should this be 'ReplyTo' address instead?
	//  if (varsettrue($returnpath)) $mail->Sender = $AddReplyToAddresses($returnpath,'');
	if (varsettrue($returnpath)) $mail->Sender = $returnpath;

	if (varsettrue($returnreceipt)) $mail->ConfirmReadingTo($returnreceipt);

	if ($mail->sendEmail($send_to,$to_name)) 
	{	// Success
		return TRUE;
	}

	// TODO: Possibly Log this somewhere (sendEmail method logs to rolling log)
	echo "Mail sending error: ".$mail->ErrorInfo."<br />";
	return FALSE;
}



?>