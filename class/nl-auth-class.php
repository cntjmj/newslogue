<?php
	require_once dirname(__FILE__).'/../nl-config.php';
	require_once dirname(__FILE__).'/../common/nl-common.php';

class Auth {
	private static $instance;
	
	private $auth = array("userID" => 0, "displayName" => "");

	private function __construct() {
		if (isset($_SESSION["userID"]))
			$this->auth["userID"] = $_SESSION["userID"];
		if (isset($_SESSION["displayName"]))
			$this->auth["displayName"] = $_SESSION["displayName"];
	}
	
	private static function genInstance() {
		Auth::$instance = new Auth();
		
		if (0 == Auth::$instance->getUserID() && CONFIG::ALLOW_ANONYMOUS) {
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
			Auth::$instance->setUserID($anonymousID);
			Auth::$instance->setDisplayName($_SESSION["displayName"]);
		}
	}

	protected function setUserID($userID) {
		$this->auth["userID"] = $userID;
	}
	
	protected function setDisplayName($name) {
		$this->auth["displayName"] = $name;
	}

	public static function getInstance() {
		if (!isset(Auth::$instance))
			Auth::genInstance();
		
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