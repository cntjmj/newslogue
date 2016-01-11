<?php
	require_once __DIR__.'/../nl-config.php';
	require_once __DIR__.'/../common/nl-common.php';
	require_once __DIR__.'/nl-coder-class.php';
	require_once __DIR__.'/nl-database-class.php';
	require_once __DIR__.'/nl-user-class.php';

class Auth {
	private static $instance;
	
	private $auth = array("userID" => 0, "displayName" => "");
	private $db;

	private function __construct() {
		if (isset($_SESSION["userID"]))
			$this->auth["userID"] = $_SESSION["userID"];
		if (isset($_SESSION["displayName"]))
			$this->auth["displayName"] = $_SESSION["displayName"];
		
		$this->db = new Database();
	}
	
	private static function buildInstance($instance) {
		if (0 == $instance->getUserID() && CONFIG::ALLOW_ANONYMOUS) {
			if (!isset($_COOKIE["tempID"])) {

				$tempID = uniqid();
				set_cookie("tempID", $tempID);
			} else {
				$tempID = $_COOKIE["tempID"];
			}
			
			if (!isset($_SESSION["displayName"])) {
				$_SESSION["displayName"] = "Anonymous User";
			}
			
			$anonymousID = "-".substr(hexdec($tempID),7);
			$instance->setUserID($anonymousID);
			$instance->setDisplayName($_SESSION["displayName"]);
		}
	}

	protected function setUserID($userID) {
		$this->auth["userID"] = $userID;
	}
	
	protected function setDisplayName($name) {
		$this->auth["displayName"] = $name;
	}
	
	public function fbLogin($fbEmail, $fbID, $fbName) {
		$fbEmail = $this->db->real_escape_string($fbEmail);
		$fbID = $this->db->real_escape_string($fbID);
		$fbName = $this->db->real_escape_string($fbName);
		
		$query = "select * from user_registration where fbID=\"$fbID\"";

		$result = $this->db->query($query);
		if (!is_array($result) || count($result) < 1)
			throw new Exception("unregistered user", -1);
		
		if ($fbEmail != $result[0]['fbEmail'] || $fbName != $result[0]['fbName']) {
			// TODO: update user profile
		}
		
		$userID = $result[0]['userID'];

		$this->setupSession($userID);
		
		return $this;
	}
	
	public function login($emailaddress, $password) {
		$emailaddress = Coder::cleanXSS($this->db, $emailaddress);
		$password = sha1($password);

		$query = "select userID from user_registration where emailaddress=\"$emailaddress\" and pwd=\"$password\" and userStatus='active'";
		
		$result = $this->db->query($query);
		
		if (!is_array($result) || count($result)<1 || !isset($result[0]["userID"]))
			throw new Exception("incorrect email address or password", -1);

		$userID = $result[0]["userID"];
		if ($userID <= 0)
			throw new Exception("current user is not allowed to login", -1);
		
		$this->setupSession($userID);
		
		return $this;
	}
	
	private function setupSession($userID) {
		$user = new User($userID);
		$userInfo = $user->getArray();
		
		if (!is_array($userInfo) || !isset($userInfo["user"]))
			throw new Exception("could not retrieve user information", -1);
		
		$_SESSION["userID"] = $userID;
		$_SESSION["displayName"] = $userInfo["user"]["displayName"];
		$_SESSION["fullname"] = $userInfo["user"]["fullname"];	// deprecated
		
		$this->setUserID($userID);
		$this->setDisplayName($_SESSION["displayName"]);
	}
	
	public function logout() {
		session_unset();
		$this->setUserID(0);
		$this->setDisplayName("");
		Auth::buildInstance($this);
		return $this;
	}

	public static function getInstance() {
		if (!isset(Auth::$instance)) {
			Auth::$instance = new Auth();
			Auth::buildInstance(Auth::$instance);
		}
		
		return Auth::$instance;
	}
	
	public function getArray() {
		return $this->auth;
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}

	public function getUserID() {
		return $this->auth["userID"];
	}
	
	public function getDisplayName() {
		return $this->auth["displayName"];
	}
	
	public function canReply() {
		return ($this->auth["userID"]!=0);
	}
	
	public function canVote() {
		return ($this->auth["userID"]!=0);
	}
}
?>