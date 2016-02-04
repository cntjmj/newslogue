<?php
	require_once __DIR__.'/nl-coder-class.php';
	require_once __DIR__.'/nl-database-class.php';
	require_once __DIR__.'/nl-user-class.php';
	
class Notification {
	private $userID;
	private $num_per_page, $page_num;
	private $resultArr = array();
	private $db;
	
	public function __construct($userID, $page_num=0, $num_per_page=0) {
		$this->userID = $userID;
		$this->page_num = $page_num;
		$this->num_per_page = $num_per_page;
		
		$this->db = new Database();
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}
	
	public function getArray() {
		if ($this->resultArr == null) 
			$this->loadNotification();
		return $this->resultArr;
	}
	
	private function loadNotification() {
		$this->userID = $this->db->real_escape_string($this->userID);
		$this->page_num = $this->db->real_escape_string($this->page_num);
		$this->num_per_page = $this->db->real_escape_string($this->num_per_page);

		$query = "select count(1) from news_reply where readflag = 0
				and userID != $this->userID and parentReplyID in
				(select replyID from news_reply where userID=$this->userID)";

		$this->resultArr["notification"]["count"] = $this->db->query($query);

		if ($this->num_per_page > 0) {
			$query = "select nr.*, na.*, ur.userID, ur.displayName, ur.fullname, ur.fbName,
					nr.createdDateTime as nrcreatedDateTime from news_reply nr
					inner join user_registration ur
					on nr.userID = ur.userID
					inner join (select newsID, newsPermaLink,newsTitle from newsarticle) na
					on nr.newsID = na.newsID
					where nr.readflag in (0,1) and nr.userID != $this->userID and parentReplyID in
					(select replyID from news_reply where userID=$this->userID)
					order by nr.createdDateTime desc;";
			
			$result = $this->db->query($query);
			if (is_array($result) && count($result) > 0) {
				$this->setModelNotification($result);
			}
		}
	}
	
	public function setModelNotification($model) {
		if (is_array($model) && count($model)>0) {
			foreach ($model as $i => $row) {
				$this->resultArr["notification"]["list"][$i] = $this->model2view($row);
			}
		}
		
		return $this;
	}
	
	private function model2view(&$model) {
		foreach ($model as $key => $value)
			Coder::cleanData($model[$key]);
			User::fillDisplayName($model);
	
			return $model;
	}

	public function updateReadflag($replyID) {
		$replyID = $this->db->real_escape_string($replyID);

		$query = "update news_reply set readflag=1 where replyID='".$replyID."' limit 1";

		$this->db->query($query);
	}
}
?>