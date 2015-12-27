<?php
	require_once dirname(__FILE__).'/nl-coder-class.php';
	require_once dirname(__FILE__).'/nl-database-class.php';
	
class Vote {
	private $newsID;
	//private $voteType;
	private $db;
	private $voteArr = array();
	
	public function __construct($newsID) {
		$this->newsID = $newsID;
		$this->db = new Database();
	}
	
	public function getArray() {
		if ($this->voteArr == null)
			$this->loadVote();
		
		return $this->voteArr;
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}
	
	public function vote($voteType, $userID) {
		$this->newsID = $this->db->real_escape_string($this->newsID);
		$userID = $this->db->real_escape_string($userID);

		$query = "delete from news_vote where newsID='".$this->newsID."' and userID='".$userID."'";
		
		$this->db->query($query);
		
		$query = "insert into news_vote (newsID,userID,voteType,createdDateTime,updatedDateTime)
				values ('".$this->newsID."', '".$userID."', '".$voteType."', now(), now())";
		
		$this->db->query($query);
		
		return $this;
	}
	
	private function loadVote() {
		$this->newsID = $this->db->real_escape_string($this->newsID);
		
		$magic = hexdec(substr(sha1($this->newsID), -8));
		$magic_base = 5 + $magic % 15;
		$magic_offset = $magic / 100 % 15;
		$magic_select = $magic / 1000 % 2;
		
		if ($magic_select == 0) {
			$magic_agree = $magic_base + $magic_offset;
			$magic_disag = $magic_base;
		} else {
			$magic_agree = $magic_base;
			$magic_disag = $magic_base + $magic_offset;
		}
		
		$query = "select userID from news_vote where newsID = $this->newsID and voteType='agree'";
		
		$this->voteArr["vote"]["newsID"] = $this->newsID;
		
		$this->voteArr["vote"]["agree"]["votes"] = $this->db->query($query);
		$this->voteArr["vote"]["agree"]["count"] = $magic_agree + count($this->voteArr["vote"]["agree"]["votes"]);
		
		$query = "select userID from news_vote where newsID = $this->newsID and voteType='disagree'";
		
		$this->voteArr["vote"]["disagree"]["votes"] = $this->db->query($query);
		$this->voteArr["vote"]["disagree"]["count"] = $magic_disag + count($this->voteArr["vote"]["disagree"]["votes"]);
	}
}
?>