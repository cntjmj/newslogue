<?php

require_once __DIR__.'/nl-database-class.php';
require_once __DIR__.'/nl-coder-class.php';
require_once __DIR__.'/nl-mailer-class.php';

class Recovery
{
	private $db;

	public function __construct() 
	{
		$this->db = new Database();
	}

	public function sendRecoveryEmail($emailaddress)
	{
		$mailer = new Mailer();
		$uniqCode = Coder::createRandomCode();

		$query = "select count(1) from user_registration where emailaddress=\"".
				Coder::cleanXSS($this->db, $emailaddress)."\"";

		if ($this->db->query($query) != 1)
			throw new Exception("user does not exist", -1);
		else
		{
			$query = "update user_registration set uniqCode=\"$uniqCode\" where 
				emailaddress=\"$emailaddress\" limit 1";
			$this->db->query($query);
			// if success then continue, send mail
			// otherwise Exception
		}

		$mailer = new Mailer();
		$result = $mailer->recoveryEmail($emailaddress, " ", $uniqCode);
		if ($result == false)
			throw new Exception("failed to send change password email", -1);
	}

	// change pwd through email and uniqcode
	public function changePwd($email, $ucode, $newPwd)
	{
		$query = "select count(1) from user_registration where emailaddress=\"".
				Coder::cleanXSS($this->db, $email)."\" and uniqCode=\"$ucode\"";

		if ($this->db->query($query) != 1)
			throw new Exception("Invalid URL", -1);	
		else
		{
			$auth = Auth::getInstance();
			$newPwd = Auth::encrypt($newPwd);

			$query = "update user_registration set pwd=\"$newPwd\" where 
				emailaddress=\"$email\" and uniqCode=\"$ucode\" limit 1";
			$this->db->query($query);
		}
	}

	// chanage pwd through userID
	public function resetPwd($userID, $newPwd)
	{
			$auth = Auth::getInstance();
			$newPwd = Auth::encrypt($newPwd);

			$query = "update user_registration set pwd=\"$newPwd\" where 
				userID=\"$userID\" limit 1";
			$this->db->query($query);
	}
}

?>