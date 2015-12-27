<?php
	require_once dirname(__FILE__).'/nl-coder-class.php';
	require_once dirname(__FILE__).'/nl-database-class.php';
	require_once dirname(__FILE__).'/nl-user-class.php';

class ReplyList {
	private $newsID;
	private $withSubReplies;
	private $num_per_page, $page_num;
	private $replyArr = array();
	private $total_replies_num = -1;
	private $db;
	
	public function __construct($newsID, $withSubReplies=true, $page_num=0, $num_per_page=9999) {
		$this->newsID = $newsID;
		$this->withSubReplies = $withSubReplies;
		$this->page_num = $page_num;
		$this->num_per_page = $num_per_page;
		
		$this->db = new Database();
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}
	
	public function getArray() {
		if ($this->replyArr == null)
			$this->loadReplyList();
			
		return $this->replyArr;
	}
	
	private function loadReplyList() {
		$this->num_per_page = $this->db->real_escape_string($this->num_per_page);
		$this->page_num = $this->db->real_escape_string($this->page_num);
		$this->newsID = trim($this->db->real_escape_string($this->newsID));
		
		$start_record = $this->page_num * $this->num_per_page;
		
		$query = "select *, nr.createdDateTime as replyCreatedDateTime from `news_reply` nr 
					inner join user_registration ur on ur.userID = nr.userID
					where nr.replyStatus = 'active'
					and nr.newsID = $this->newsID and parentReplyID = 0
					order by nr.createdDateTime desc limit $start_record, $this->num_per_page";
		
		$resultArr = $this->db->query($query);
		if (is_array($resultArr) && count($resultArr) > 0) {
			$replyObj = new Reply(0, $this->withSubReplies);
			foreach ($resultArr as $id => $reply) {
				$replyResult = $replyObj->setModelReply($reply)->getArray();
				$resultArr[$id] = $replyResult["reply"];
			}
			$this->replyArr["replyList"] = $resultArr;
		}
	}
}

class Reply {
	private $replyID;
	private $withSubReplies;
	private $replyResult = array();
	private $db;

	public function __construct($replyID=0, $withSubReplies=true) {
		$this->replyID = $replyID;
		$this->withSubReplies = $withSubReplies;
		
		$this->db = new Database();
	}
	
	public function getJson() {
		return json_encode($this->getArray());
	}
	
	public function getArray() {
		if ($this->replyResult == null)
			$this->loadReply();

		return $this->replyResult;
	}
	
	public function setModelReply($model) {
		if (is_array($model) && isset($model["replyID"])) {
			$this->replyResult["reply"] = $this->model2view($model);
			$this->replyID = $this->replyResult["reply"]["replyID"];

			if ($this->replyResult["reply"]["parentReplyID"] == 0) {
				$this->loadSubReplies();
				$this->loadLikes();
			}
		} else {
			// something is wrong
			$this->replyResult = array();
			$this->replyID = 0;
		}
		
		return $this;
	}
	
	public function saveReply($parentReplyID, $newsID, $userID, $replyStatement, $replyContent, $replyType) {
		try {
			$this->replyResult["reply"]["parentReplyID"] = $parentReplyID;
			$this->replyResult["reply"]["newsID"] = $newsID;
			$this->replyResult["reply"]["userID"] = $userID;
			$this->replyResult["reply"]["replyStatement"] = $replyStatement;
			$this->replyResult["reply"]["replyContent"] = $replyContent;
			$this->replyResult["reply"]["replyType"] = $replyType;
			
			if (false == $this->validateReply()) {
				throw new Exception("failed to validate reply", -1);
			}
			
			if ($userID < 0) {
				$userObj = new User();
				$userObj->createAnonymous($userID);
			}
			
			$model = $this->view2model();
			
			if ($replyType == "like")
				$this->storeLike($model);
			else
				$this->storeReply($model);
		} catch (Exception $e) {
			$this->replyResult["errCode"] = $e->getCode();
			$this->replyResult["errMessage"] = $e->getMessage();
		}
	}
	
	public function getVoteUserID($subReplyID = 0) {
		$userID = "";

		if ($this->replyResult == null)
			$this->loadReply();
		
		if (is_array($this->replyResult) && isset($this->replyResult["reply"])) {
			if ($subReplyID == 0) {
				$userID = $this->replyResult["reply"]["userID"];
			} else if (is_array($this->replyResult["reply"]["subReplies"]) && $this->replyResult["reply"]["subReplies"]["count"] > 0) {
				foreach ($this->replyResult["reply"]["subReplies"]["list"] as $id => $arr) {
					if ($arr["replyID"] == $subReplyID)
						$userID = $arr["userID"];
				}
			}
		}

		return $userID;
	}
	
	public function removeReply($subReplyID = 0) {
		$this->replyID = $this->db->real_escape_string($this->ReplyID);
		$subReplyID = $this->db->real_escape_string($subReplyID);

		if ($subReplyID > 0) {
			$query = "delete from news_vote where replyID='".$subReplyID."'";
			$this->db->query($query);
			$this->loadReply();
		} else {
			$query = "delete from news_vote where parentReplyID='".$this->replyID."'";
			$this->db->query($query);
			$query = "delete from news_vote where replyID='".$this->replyID."'";
			$this->db->query($query);
			$this->replyResult=array();
		}
	}
	
	private function storeLike($model) {
		$query = "select replyID from news_reply where parentReplyID = '".$model["parentReplyID"]."'
				and userID = '".$model["userID"]."' and replyType='like'";

		$result = $this->db->query($query);
		if (is_array($result) && isset($result[0]["replyID"])){
			$this->removeLike($model);
		} else {
			// store Like using function storeReply
			$this->storeReply($model);
		}
	}
	
	private function removeLike($model) {
		$query = "delete from news_reply where parentReplyID = '".$model["parentReplyID"]."'
				and userID = '".$model["userID"]."' and replyType='like'";
		$affected = $this->db->query($query);
		
		$this->replyID = $model["parentReplyID"];
		$this->loadReply();
	}
	
	private function storeReply($model) {		
		$query = "insert into news_reply
				(parentReplyID,newsID,userID,replyStatement,replyContent,replyType,replyStatus,createdDateTime,updatedDateTime) values 
				('".$model["parentReplyID"]."','".$model["newsID"]."','".$model["userID"]."','".$model["replyStatement"]."',
				'".$model["replyContent"]."','".$model["replyType"]."','active',now(),now())";
		
		$newReplyID = $this->db->query($query);
		if ($newReplyID <= 0) {
			$this->replyResult["errCode"] = -1;
		} else {
			$this->replyID = $this->replyResult["reply"]["parentReplyID"]>0?$this->replyResult["reply"]["parentReplyID"]:$newReplyID;
			$this->loadReply();
		}

		return $this;
	}
	
	private function validateReply() {
		$isValid = true;
		$auth = Auth::getInstance();

		if ($this->replyResult["reply"]["newsID"] == 0 ||
			$this->replyResult["reply"]["userID"] == 0 ||
			//$this->replyResult["reply"]["replyContent"] == "" ||
			!in_array($this->replyResult["reply"]["replyType"], array('agree', 'disagree', 'like'))) {
			$isValid = false;
			$this->replyResult["errCode"] = -1;
		}

		return $isValid;
	}
	
	private function loadReply() {
		$this->replyID = $this->db->real_escape_string($this->replyID);
		
		$query = "select *, nr.createdDateTime as replyCreatedDateTime from `news_reply` nr 
					inner join user_registration ur on ur.userID = nr.userID
					where nr.replyStatus = 'active' and nr.replyID = $this->replyID";

		$resultArr = $this->db->query($query);

		if (is_array($resultArr) && count($resultArr)>0) {
			$this->setModelReply($resultArr[0]);
		}
	}
	
	private function loadLikes() {
		$query = "select *, nr.createdDateTime as subreplyCreatedDateTime from `news_reply` nr
		inner join user_registration ur on ur.userID = nr.userID
		where nr.replyType = 'like' and nr.replyStatus = 'active'
		and parentReplyID = $this->replyID order by nr.createdDateTime desc";
		$resultArr = $this->db->query($query);
		if (is_array($resultArr) && count($resultArr) > 0) {
			$this->replyResult["reply"]["likes"]["count"] = count($resultArr);
			if ($this->withSubReplies)
				$this->replyResult["reply"]["likes"]["list"] = $resultArr;
		}
	}
	
	private function loadSubReplies() {
		$query = "select *, nr.createdDateTime as subreplyCreatedDateTime from `news_reply` nr
					inner join user_registration ur on ur.userID = nr.userID
					where nr.replyType != 'like' and nr.replyStatus = 'active' 
					and parentReplyID = $this->replyID order by nr.createdDateTime desc";
		$resultArr = $this->db->query($query);
		if (is_array($resultArr) && count($resultArr) > 0) {
			foreach ($resultArr as $id => $subReply) {
				$resultArr[$id] = $this->model2view($subReply);
			}
			$this->replyResult["reply"]["subReplies"]["count"] = count($resultArr);
			if ($this->withSubReplies)
				$this->replyResult["reply"]["subReplies"]["list"] = $resultArr;
		}
	}
	
	private function model2view(&$model) {
		foreach ($model as $key => $value)
			Coder::cleanData($model[$key]);
		User::fillDisplayName($model);

		return $model;
	}
	
	private function view2model() {
		$model = $this->replyResult["reply"];

		Coder::cleanXSS($this->db, $model["parentReplyID"], "int");
		Coder::cleanXSS($this->db, $model["newsID"], "int");
		Coder::cleanXSS($this->db, $model["userID"], "int");
		Coder::cleanXSS($this->db, $model["replyStatement"]);
		Coder::cleanXSS($this->db, $model["replyContent"]);
		Coder::cleanXSS($this->db, $model["replyType"]);

		return $model;
	}
}
?>