<?php
	require_once __DIR__.'/nl-database-class.php';

class User {
	private $userID;
	private $userArr = array();
	private $db;

	public function __construct($userID=0) {
		$this->userID = $userID;
		$this->db = new Database();
	}

	public function getArray() {
		if ($this->userArr == null)
			$this->loadUser();

		return $this->userArr;
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}

	public static function fillDisplayName(&$user) {
		if (!isset($user["displayName"]) || $user["displayName"] == "" || $user["displayName"] == "undefined") {
			if (isset($user["fullame"]) && $user["fullname"] != "" && $user["fullname"] != "undefined")
				$user["displayName"] = $user["fullname"];
			else if (isset($user["fbName"]) && $user["fbName"] != "" && $user["fbName"] != "undefined")
				$user["displayName"] = $user["fbName"];
		}
		return $user;
	}
	
	public function createUser($fields) {
		$userID = 0;

		if (isset($fields["fbID"])) {
			$userID = $this->createFBUser($fields);
		} else if ($field["emailaddress"]) {
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
			throw new Exception("incorrect parameters", -1);
		
		$keys = $values = "(";
		foreach ($fields as $key => $value) {
			$key = Coder::cleanXSS($this->db, $key);
			$value = Coder::cleanXSS($this->db, $value);
			if ($key == 'pwd')
				$value = Auth::encrypt($value);
			$fields[$key] = $value;
			$keys .= "$key, ";
			$values .= "\"$value\", ";
		}
		$keys .= "userStatus, createdDateTime, updatedDateTime)";
		$values .= ", \"pending\", now(), now())";
		
		// TODO: a looooooooot of things
	}
}
?>