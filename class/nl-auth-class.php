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
			
			//$anonymousID = "-".substr(hexdec($tempID),7);
			$anonymousID = 0 - substr(hexdec($tempID),7);
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
	
	public static function encrypt($password) {
		return sha1($password);
	}
	
	public function fbLogin($fbEmail, $fbID, $fbName) {
		$fbEmail = $this->db->real_escape_string($fbEmail);
		$fbID = $this->db->real_escape_string($fbID);
		$fbName = $this->db->real_escape_string($fbName);
		
		$query = "select * from user_registration where fbID=\"$fbID\"";

		$result = $this->db->query($query);
		if (!is_array($result) || count($result) < 1)
			throw new Exception("unregistered user", -1);

		$userID = $result[0]['userID'];

		if ($fbEmail != $result[0]['fbEmail'] || $fbName != $result[0]['fbName']) {
			$attr = array("fbEmail" => $fbEmail, "fbName" => $fbName);
			$user = new User($userID);
			$user->updateUser($attr);
		}

		$this->setupSession($userID);
		
		return $this;
	}
	
	public function login($emailaddress, $password) {
		$emailaddress = Coder::cleanXSS($this->db, $emailaddress);
		//$password = Coder::cleanXSS($this->db, $password);
		$password = Auth::encrypt($password);

		$query = "select userID, userStatus from user_registration where emailaddress=\"$emailaddress\" and pwd=\"$password\""; //and userStatus='active'";
		
		$result = $this->db->query($query);
		
		if (!is_array($result) || count($result)<1 || !isset($result[0]["userID"]))
			throw new Exception("incorrect email address or password", -1);
		
		if ($result[0]["userStatus"] != 'active')
			throw new Exception("user's email address is not verified", -1);

		$userID = $result[0]["userID"];
		if ($userID <= 0)
			throw new Exception("current user is not allowed to login", -1);
		
		$this->setupSession($userID);
		
		return $this;
	}
	
	public function setupSession($userID) {
		$user = new User($userID);
		$userInfo = $user->getArray();
		
		if (!is_array($userInfo) || !isset($userInfo["user"]))
			throw new Exception("could not retrieve user information", -1);
		
		$_SESSION["userID"] = $userID;
		$_SESSION["displayName"] = $userInfo["user"]["displayName"];
		$_SESSION["fullname"] = $userInfo["user"]["fullname"];	// deprecated
		
		$this->setUserID($userID);
		$this->setDisplayName($_SESSION["displayName"]);
		
		Auth::workaroundSaveSessionID($userID);
	}
	
	public function logout() {
		session_unset();
		$this->setUserID(0);
		$this->setDisplayName("");
		Auth::workaroundDeleteSessionID();
		Auth::buildInstance($this);
		return $this;
	}

	public static function getInstance() {
		if (!isset(Auth::$instance)) {
			Auth::$instance = new Auth();
			Auth::workaroundLoadUserBasedOnSessionID(Auth::$instance);
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
	
	/**
	 * the following 5 functions should be removed
	 * once we resolve the issue with production server
	 * 
	 */
	private static function bWorkaround() {
		return isProductEnv();
	}
	
	private static function giveMeTheSessionID() {
		return session_id();
	}

	private static function workaroundSaveSessionID($userID) {
		if (Auth::bWorkaround()) {
			$sessionID = Auth::giveMeTheSessionID();
			$db = new Database();
			$query = "update user_registration set sessionID=\"$sessionID\" where userID=\"$userID\"";
			$db->query($query);
		}
	}
	
	private static function workaroundDeleteSessionID() {
		if (Auth::bWorkaround()) {
			$sessionID = Auth::giveMeTheSessionID();
			$db = $db = new Database();
			$query = "update user_registration set sessionID=\"\" where sessionID=\"$sessionID\"";
			$db->query($query);
		}
	}
	
	private static function workaroundLoadUserBasedOnSessionID($instance) {
		if (Auth::bWorkaround()) {
			if (0 == $instance->getUserID()) {
				$sessionID = Auth::giveMeTheSessionID();
				$db = new Database();
				$query = "select userID from user_registration where sessionID=\"$sessionID\"";
				$result = $db->query($query);
				if (is_array($result) && count($result)>0) {
					$userID = $result[0]['userID'];
					if ($userID > 0)
						$instance->setupSession($userID);
				}
			}
		}
	}
}
?>