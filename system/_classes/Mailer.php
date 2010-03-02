<?php

class Mailer
{
	public $headers;
	public $to;
	public $from;
	public $cc;
	public $bcc;
	public $subject;
	public $body;
	public $results = array();
	public $errors = array();
		
	function postmaster()
	{				
		if ($this->validate_envelope())
		{
			$this->set_headers();
			$this->send_mail();
		}				
	}
	
	function validate_envelope()
	{
		$required = array('to','from','subject','body');
		$valid = true;
		
		foreach ($required as $value)
			if (!$this->$value)
			{
				$valid = false;
				$this->errors[] = ucwords($value).' is required for mailing.';
			}

		return $valid ? true : false;	
	}
	
	function set_headers()
	{
		$this->headers = 	'From: '.$this->from.'\r\n';
		$this->headers .=	'Reply-To: '.$this->from.'\r\n';
		#if ($this->cc) $this->headers .= 'Cc: '.$this->cc.'\r\n';
		#if ($this->bcc) $this->headers .= 'Bcc: '.$this->bcc.'\r\n';
		#$this->headers .= "Message-ID: <".time()."-".$this->from_email.">".$eol;
		#$this->headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters	
	}
	
	function send_mail()
	{
		mail($this->to, $this->subject, $this->body, $this->headers);	
	}

	##############################################################################

	function name_format($name, $email)
	{
		return $name."<".$email.">";	
	}
	
	function system_from()
	{
		return $this->from = $this->name_format(FROM_NAME, FROM_EMAIL);
	}
	
	function system_mail()
	{
		return $this->name_format(SHABDA_MANAGER, SHABDA_MAIL);
	}
	
	function contact_mail()
	{
		return $this->name_format(CONTACT_RECIPIENT, CONTACT_EMAIL);	
	}
	
	##############################################################################
	
	public function contact(contact $form)
	{
		// Set Values
		$this->to = $this->contact_mail();
		$this->from = $this->name_format($form->name, $form->email);
		$this->subject = SITE_TITLE.' Contact Form: '.$form->subject;
		$this->body = SITE_TITLE.DELIMITER.ROOT_PATH.'

From: '.$form->name.'
Email: '.$form->email.'

'.$form->message.'

Mailed by Shabda '.VERSION;

		// Mail
		$this->postmaster();
	}
	
	public function contact_recipient(contact $form)
	{
		$recipient = new contact_recipients($form->recipient_id);
		// Set Values
		$this->to = $this->name_format($recipient->real_name, $recipient->email);
		$this->from = $this->name_format($form->name, $form->email);
		$this->subject = SITE_TITLE.' Contact Form: '.$form->subject;
		$this->body = SITE_TITLE.DELIMITER.ROOT_PATH.'

From: '.$form->name.'
Email: '.$form->email.'

'.$form->message.'

Mailed by Shabda '.VERSION;

		// Mail
		$this->postmaster();
	}
	
	##############################################################################
	
	function send_email_validation(users $user)
	{
		$this->to = $user->email;
		$this->subject = SITE_TITLE." | Registration Validation";
		$this->system_from();
		$this->body = '

Dear '.$user->name().',

Your registration for '.SITE_TITLE.' was successful.

One last step.

Please click on the link below, or copy it into your
browser to active your account.

'.ROOT_PATH.$user->validation_url();

		$this->postmaster();
	}
	
	##############################################################################
	
	public function alert($subject, $message)
	{
		// Set Values
		$this->to = $this->contact_mail();
		$this->from = $this->system_from();
		$this->subject = SITE_TITLE.' '.$subject;
		$this->body = SITE_TITLE.DELIMITER.ROOT_PATH.'

'.$message.'

Mailed by Shabda '.$this->version;

		// Mail
		$this->postmaster();

	}
	
	##############################################################################
	
	static public function mail_shabda_error()
	{
		$mailer = new Mailer();
		$registry = Registry::instance();
	
		// Set Values
		$mailer->to = $mailer->system_mail();
		$mailer->from = $mailer->system_from();
		$mailer->subject = 'Shabda System Error';
		$mailer->body = SITE_TITLE.'
'.ROOT_PATH.'
TIME: '.date('Y-m-d H:i:s', time()).'
IP: '.$_SERVER['REMOTE_ADDR'].'
'.$registry->shabda_error();

		// Mail
		$mailer->postmaster();
	}
	
}

?>
