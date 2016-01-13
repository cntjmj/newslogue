<?php
	require_once __DIR__.'/nl-database-class.php';

class User {
	private $userID;
	private $userArr = array();
	private $db;

	public function __construct($userID) {
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
		if ($user["displayName"] == "" || $user["displayName"] == "undefined") {
			if ($user["fullname"] != "" && $user["fullname"] != "undefined")
				$user["displayName"] = $user["fullname"];
			else if ($user["fbName"] != "" && $user["fbName"] != "undefined")
				$user["displayName"] = $user["fbName"];
		}
		return $user;
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
	
	public function updateUser($attrArr) {
		if (!is_array($attrArr) || count($attrArr) < 1)
			return false;

		$query = "update `user_registration` set";
		foreach ($attrArr as $key => $value) {
			$key = $this->db->real_escape_string($key);
			$value = $this->db->real_escape_string($value);
			$query .= " $key = \"$value\",";
		}
		$query = substr($query, 0, $query.length-1) . " where userID =" . $this->userID;
		
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
}
?>