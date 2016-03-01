<?php
	require_once __DIR__.'/nl-database-class.php';
	require_once __DIR__.'/nl-coder-class.php';
	require_once __DIR__.'/nl-mailer-class.php';

class User {
	private $userID;
	private $userArr = array();
	private $db;

	public function __construct($userID=0) {
		$this->userID = $userID;
		$this->db = new Database();
	}

	public function getArray() {
		if ($this->userArr == null && $this->userID != 0)
			$this->loadUser();

		return $this->userArr;
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}
	
	public function getUserID() {
		return $this->userID;
	}

	public static function fillDisplayName(&$user) {
		if (!isset($user["displayName"]) || $user["displayName"] == "" || $user["displayName"] == "undefined") {
			if (isset($user["fullname"]) && $user["fullname"] != "" && $user["fullname"] != "undefined")
				$user["displayName"] = $user["fullname"];
			else if (isset($user["fbName"]) && $user["fbName"] != "" && $user["fbName"] != "undefined")
				$user["displayName"] = $user["fbName"];
		}
		return $user;
	}
	
	public function createUser($fields) {
		$userID = 0;

		if (isset($fields["fbID"])) {
			$query = "select count(1) from user_registration where fbID=\"".
						Coder::cleanXSS($this->db, $fields["fbID"])."\"";
			if (0 < $this->db->query($query))
				throw new Exception("user already exists", -1);
			$userID = $this->createFBUser($fields);
		} else if ($fields["emailaddress"]) {
			$query = "select count(1) from user_registration where emailaddress=\"".
						Coder::cleanXSS($this->db, $fields["emailaddress"])."\"";
			if (0 < $this->db->query($query))
				throw new Exception("user already exists", -1);
			$userID = $this->createLocalUser($fields);
		} else {
			throw new Exception("incorrect parameters", -1);
		}

		if ($userID <= 0) {
			$this->userID = 0;
			$this->userArr = array();
			throw new Exception("error occurred when creating user", -1);
		}
		
		$this->userID = $userID;
		$this->loadUser();
		return $this;
	}
	
	public function createAnonymous($anonymousID) {
		if (CONFIG::ALLOW_ANONYMOUS && $anonymousID < 0) {
			$this->userID = $anonymousID;
			$this->loadUser();

			if (!isset($this->userArr["user"])) {
				$query = "insert into user_registration (userID, displayName, pwd, createdDateTime, updatedDateTime) 
						values (".$this->userID.", 'Anonymous User', '', now(), now())";
				$this->db->query($query);
			}
		}
	}
	
	public function updateUser($fields) {
		if (!is_array($fields) || count($fields) < 1)
			return false;

		$query = "update `user_registration` set";
		foreach ($fields as $key => $value) {
			$key = Coder::cleanXSS($this->db, $key);
			$value = Coder::cleanXSS($this->db, $value);
			$query .= " $key = \"$value\",";
		}
		$query .=" updatedDateTime=now() where userID =" . $this->userID;
		
		return 0 < $this->db->query($query);
	}
	
	public function VerifyCode($emailaddress,$uniqCode) {
		$emailaddress = Coder::cleanXSS($this->db, $emailaddress);
		$uniqCode = Coder::cleanXSS($this->db, $uniqCode);
		
		$query = "select userID from user_registration where emailaddress=\"$emailaddress\" and uniqCode=\"$uniqCode\" and userStatus=\"pending\"";
		$result = $this->db->query($query);

		if (is_array($result) && count($result) > 0 && isset($result[0]["userID"])) {
			$userID = $result[0]["userID"];
			$query = "update user_registration set userStatus=\"active\", uniqCode=\"\" where userID=$userID";
			$this->db->query($query);
			$this->userID = $userID;
			$this->loadUser();
		}
		
		return $this;
	}
	
	private function loadUser() {
		$this->userID = $this->db->real_escape_string($this->userID);
		$query = "select * from user_registration where userid=$this->userID";
		$result = $this->db->query($query);

		if (is_array($result) && isset($result[0]["userID"])) {
			$this->userArr["user"] = $this->model2view($result[0]);
		}
	}
	
	private function model2view(&$model) {
		foreach ($model as $key => $value)
			Coder::cleanData($model[$key]);
			User::fillDisplayName($model);

		return $model;
	}
	
	private function createFBUser($fields) {
		if (!isset($fields["fbID"]) || !isset($fields["fbName"]) || !isset($fields["fbEmail"]))
			throw new Exception("incorrect parameters", -1);

		User::fillDisplayName($fields);
		$keys = $values = "(";
		foreach ($fields as $key => $value) {
			$key = Coder::cleanXSS($this->db, $key);
			$value = Coder::cleanXSS($this->db, $value);
			$fields[$key] = $value;
			$keys .= "$key, ";
			$values .= "\"$value\", ";
		}
		$keys .= "pwd, userStatus, createdDateTime, updatedDateTime)";
		$values .= "\"\", \"active\", now(), now())";
		
		$query = "select count(*) from user_registration where fbEmail=\"".$fields['fbEmail']."\"";
		if (0 < $this->db->query($query))
			throw new Exception("user exsits", -1);
		
		$query = "insert into user_registration $keys values $values";
		return $this->db->query($query);
	}
	
	private function createLocalUser($fields) {
		if (!isset($fields['emailaddress']) || !isset($fields['displayName']) || !isset($fields['pwd']))
			throw new Exception("incorrect parameters", -11);
		
		$keys = $values = "(";
		foreach ($fields as $key => $value) {
			$key = Coder::cleanXSS($this->db, $key);
			if ($key == 'pwd')
				$value = Auth::encrypt($value);
			else
				$value = Coder::cleanXSS($this->db, $value);
			$fields[$key] = $value;
			$keys .= "$key, ";
			$values .= "\"$value\", ";
		}
		
		/*
		 * Function: disable email verification
		 * Date: 2016/03/01

		$uniqCode = Coder::createRandomCode();

		$mailer = new Mailer();
		$result = $mailer->sendVerification($fields['emailaddress'], $fields['displayName'], $uniqCode);

		if ($result == false)
			throw new Exception("failed to send verification email", -1);

		$keys .= "uniqCode, userStatus, createdDateTime, updatedDateTime)";
		$values .= "\"$uniqCode\", \"pending\", now(), now())";
		*/

		$keys .= "uniqCode, userStatus, createdDateTime, updatedDateTime)";
		$values .= "\"$uniqCode\", \"active\", now(), now())";

		$query = "insert into user_registration $keys values $values";
		return $this->db->query($query);
	}
}
?>