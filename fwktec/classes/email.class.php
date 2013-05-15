<?php
class fwktec_email {
	var $subject;
	var $from=array();
	var $to=array();
	var $cc=array();
	var $bcc=array();
	var $body;
	var $alt;
	var $attachments=array();
	var $data=array();
	var $sender;
	var $transport='mail';
	var $user;
	var $password;
	var $server;
	var $port;

	function __construct() {
		global $aphps_projects,$aphps_config;
		require_once($aphps_projects['comlib']['dir'].'addons/swift-mailer/lib/swift_required.php');
		Swift_Preferences::getInstance()->setCharset('utf-8');
		$this->transport=$aphps_config['email']['transport'];
		if (isset($aphps_config['email']['server'])) $this->server=$aphps_config['email']['server'];
		if (isset($aphps_config['email']['username'])) $this->user=$aphps_config['email']['username'];
		if (isset($aphps_config['email']['password'])) $this->password=$aphps_config['email']['password'];
		if (isset($aphps_config['email']['port'])) $this->port=$aphps_config['email']['port'];
	}

	function setSubject($subject) {
		// '=?UTF-8?B?'.base64_encode($subject).'?='
		$this->subject=$subject;
	}

	function addFrom($email,$name='') {
		if ($name) $this->from[$email]=$name;
		else $this->from[]=$email;
	}

	function addTo($email,$name='') {
		if ($name) $this->to[$email]=$name;
		else $this->to[]=$email;
	}

	function addCc($email,$name='') {
		$this->cc[$email]=$name;
	}

	function addBcc($email,$name='') {
		$this->Bcc[$email]=$name;
	}

	function setBody($body) {
		$this->body=$body;
	}

	function setAlt($body) {
		$this->alt=$body;
	}

	function setSender($email) {
		$this->sender=$email;
	}

	function setAttachment($name,$file) {
		$this->attachments[$name]=$file;
	}

	function addData($fileName,$data,$type='application/pdf') {
		$this->data[$fileName]=array('type' => $type,'data' => $data);
	}

	function send() {
		// Create the message
		$message = Swift_Message::newInstance();

		// Give the message a subject
		$message->setSubject($this->subject);

		// Set the From address with an associative array
		$message->setFrom($this->from);

		// Set the To addresses with an associative array
		$message->setTo($this->to);

		if ($this->cc) $message->setCc($this->cc);
		if ($this->bcc) $message->setBcc($this->bcc);
		if ($this->sender) $message->setSender($this->sender);

		// Give it a body
		$message->setBody($this->body,'text/html');

		// And optionally an alternative body
		if ($this->alt) $message->addPart($this->alt,'text/plain');
		else $message->addPart(strip_tags($this->body),'text/plain');

		// Optionally add any attachments
		if (is_array($this->attachments) && count($this->attachments > 0)) {
			foreach ($this->attachments as $name => $file) {
				$attachment=Swift_Attachment::fromPath($file);
				$attachment->setFilename($name);
				$message->attach($attachment);
			}
		}

		// Optional data attachments
		if (is_array($this->data) && count($this->data > 0)) {
			foreach ($this->data as $name => $data) {
				$attachment=Swift_Attachment::newInstance();
				$attachment->setFilename($name);
				$attachment->setContentType($data['type']);
				$attachment->setBody($data['data']);
				$message->attach($attachment);
			}
		}

		if ($this->transport=='smtp') {
			$transport = Swift_SmtpTransport::newInstance($this->server,$this->port);
			$transport->setUsername($this->user);
			$transport->setPassword($this->password);
		} else $transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance($transport);
		try {
			$result = $mailer->send($message);
		}
		catch (Exception $e) {
			$result=false;
			if (APHPS_DEV) echo 'Caught exception: '.$e->getMessage();
			trigger_error('Caught exception: '.$e->getMessage());
		}
		return $result;
	}
}