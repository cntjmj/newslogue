<?php
	require_once __DIR__.'/nl-coder-class.php';
	require_once __DIR__.'/nl-database-class.php';
	require_once __DIR__.'/nl-user-class.php';
	require_once __DIR__.'/nl-news-class.php';
	
abstract class ReplyList {
	protected $replyArr = array();
	protected $num_per_page, $page_num;
	protected $db;
	
	public function __construct($page_num=0, $num_per_page=9999) {
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
	
	abstract protected function loadReplyList();
}

class NewsReplyList extends ReplyList {
	private $newsID;
	private $withSubReplies;
	
	public function __construct($newsID, $withSubReplies=true, $page_num=0, $num_per_page=9999) {
		$this->newsID = $newsID;
		$this->withSubReplies = $withSubReplies;
		
		parent::__construct($page_num, $num_per_page);
	}
	
	protected function loadReplyList() {
		$this->num_per_page = $this->db->real_escape_string($this->num_per_page);
		$this->page_num = $this->db->real_escape_string($this->page_num);
		$this->newsID = trim($this->db->real_escape_string($this->newsID));

		$start_record = $this->page_num * $this->num_per_page;
		
		$query = "select nr.*, ur.UserID, ur.displayName, ur.fullname, ur.fbName, 
					nr.createdDateTime as replyCreatedDateTime from `news_reply` nr 
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

class UserReplyList extends ReplyList {
	private $userID;
	
	public function __construct($userID, $page_num=0, $num_per_page=9999) {
		$this->userID = $userID;
		parent::__construct($page_num, $num_per_page);
	}
	
	protected function loadReplyList() {
		$this->num_per_page = $this->db->real_escape_string($this->num_per_page);
		$this->page_num = $this->db->real_escape_string($this->page_num);
		$this->userID = trim($this->db->real_escape_string($this->userID));
		
		$start_record = $this->page_num * $this->num_per_page;
		
		$query = "select newsID from news_reply where userID = $this->userID group by newsID order by newsID desc";

		$resultArr = $this->db->query($query);
		
		if (is_array($resultArr) && count($resultArr) > 0) {
			$this->replyArr['newsList'] = array();
			foreach ($resultArr as $idx => $row) {
				$newsObj = new News($row['newsID'], 0);
				$news = $newsObj->getArray();
				$this->replyArr['newsList'][$idx] = array(
						"newsID"=>$news['news']['newsID'],
						"newsTitle"=>$news['news']['newsTitle'],
						"newsQuestion"=>$news['news']['newsQuestion'],
						"newsCreatedDateTime"=>$news['news']['nacreatedDateTime']
				);
				
				$newsReplyListObj = new NewsReplyList($row['newsID']);
				$newsReplyArr = $newsReplyListObj->getArray();
				$this->replyArr['newsList'][$idx]['replyList'] = $this->filterReplyList($newsReplyArr['replyList']);
			}
		}
	}
	
	private function filterReplyList(&$replyList) {
		$filteredReplyList = array();

		foreach ($replyList as $i => $reply) {
			$filteredReply = array();
			$filteredSubReplies = array();
			$filteredLikes = array();

			if ($reply["userID"] == $this->userID) {
				$filteredReply["replyID"] = $reply["replyID"];
				$filteredReply["replyContent"] = $reply["replyContent"];
				$filteredReply["replyType"] = $reply["replyType"];
				$filteredReply["replyCreatedDateTime"] = $reply["replyCreatedDateTime"];
			}

			if (isset($reply["subReplies"]["list"])) {
				foreach ($reply["subReplies"]["list"] as $j => $subReply) {
					if ($subReply["userID"] == $this->userID)
						$filteredSubReplies[] = $subReply;
				}
				
				if ($filteredSubReplies != null)
					$filteredReply["subReplies"] = array(
							"list" => $filteredSubReplies
					);
			}

			if (isset($reply["likes"]["list"])) {
				foreach ($reply["likes"]["list"] as $k => $like) {
					if ($like["userID"] == $this->userID)
						$filteredLikes[] = $like;
				}
			
				if ($filteredLikes != null)
					$filteredReply["likes"] = array(
							"list" => $filteredLikes
					);
			}

			if ($filteredReply != null)
				$filteredReplyList[] = $filteredReply;
		}

		return $filteredReplyList;
	}
}

/**
 *  forget about it
 *  
class NoUserReplyList extends ReplyList {
	private $userID;
	
	public function __construct($userID, $page_num=0, $num_per_page=9999) {
		$this->userID = $userID;	
		parent::__construct($page_num, $num_per_page);
	}
	
	protected function loadReplyList() {
		$this->num_per_page = $this->db->real_escape_string($this->num_per_page);
		$this->page_num = $this->db->real_escape_string($this->page_num);
		$this->userID = trim($this->db->real_escape_string($this->userID));
	
		$start_record = $this->page_num * $this->num_per_page;

		$query = "select nr.*, na.newsTitle, na.newsID, na.createdDateTime as newsCreatedDateTime,
				nr.createdDateTime as replyCreatedDateTime from `news_reply` nr
				inner join newsarticle na on nr.newsID = na.newsID
				where userID=$this->userID and nr.replyStatus = 'active'
				order by nr.newsID desc, parentReplyID desc, replyID desc";
		
		$resultArr = $this->db->query($query);
		if (is_array($resultArr) && count($resultArr) > 0) {
			$replyObj = new Reply(0, false);
			$this->replyArr['userReplies'] = array();
			foreach ($resultArr as $id => $row) {
				$replyResult = $replyObj->setModelReply($row)->getArray();
				$this->groupByNewsID($replyResult['reply']);
			}
		}
	}
	
	private function insertReply(&$dst, &$src) {
		foreach ($src as $k => $v) {
			if ($k == "subReplies" || $k == "likes") {
				if (isset($dsk[$k]["list"]))
					$src[$k]["list"] = $dst[$k]["list"];
				$dst[$k] = $src[$k];
			} else {
				$dst[$k] = $v;
			}
		}
	}
	
	private function insertSubReply(&$dst, &$src) {
		if ($src['replyType'] == 'like')
			$type = "likes";
		else
			$type = "subReplies";
		
		if (!isset($dst[$type]))
			$dst[$type] = array("list" => array());
		$count = count($dst[$type]["list"]);
		$dst[$type]["list"][$count] = $src;
	}
	
	private function groupByParentReplyID(&$replyList, &$insert) {
		if ($insert['parentReplyID'] == 0) {
			foreach ($replyList as $i => $reply) {
				if ($reply['replyID'] == $insert['replyID']) {
						$this->insertReply($replyList[$i], $insert);
					return;
				}
			}

			$count = count($replyList);
			$replyList[$count] = array();
			$this->insertReply($replyList[$count], $insert);
		} else {
			foreach ($replyList as $i => $reply) {
				if ($reply['replyID'] == $insert['parentReplyID']) {
					$this->insertSubReply($replyList[$i], $insert);
					return;
				}
			}
			
			$count = count($replyList);
			$replyList[$count] = array("replyID" => $insert['parentReplyID']);
			$this->insertSubReply($replyList[$count], $insert);
		}
	}
	
	private function groupByNewsID(&$reply) {
		foreach ($this->replyArr['userReplies'] as $i => $replyList) {
			if ($replyList['newsID'] == $reply['newsID']) {
				$this->groupByParentReplyID($this->replyArr['userReplies'][$i]['replyList'], $reply);
				return;
			}
		}
		
		$count = count($this->replyArr['userReplies']);
		$this->replyArr['userReplies'][$count] = array();
		$this->replyArr['userReplies'][$count]['newsID'] = $reply['newsID'];
		$this->replyArr['userReplies'][$count]['newsTitle'] = $reply['newsTitle'];
		$this->replyArr['userReplies'][$count]['newsCreatedDateTime'] = $reply['newsCreatedDateTime'];
		$this->replyArr['userReplies'][$count]['replyList'] = array();
		$this->groupByParentReplyID($this->replyArr['userReplies'][$count]['replyList'], $reply);
	}
}*/

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
	
	public function getReplyUserID($subReplyID = 0) {
		if ($this->replyResult == null)
			$this->loadReply();
		
		if (is_array($this->replyResult) && isset($this->replyResult["reply"])) {
			if ($subReplyID == 0) {
				return $this->replyResult["reply"]["userID"];
			}
			
			if (is_array($this->replyResult["reply"]["subReplies"]) && $this->replyResult["reply"]["subReplies"]["count"] > 0) {
				foreach ($this->replyResult["reply"]["subReplies"]["list"] as $id => $arr) {
					if ($arr["replyID"] == $subReplyID)
						return $arr["userID"];
				}
			}
			
			if (is_array($this->replyResult["reply"]["likes"]) && $this->replyResult["reply"]["likes"]["count"] > 0) {
				foreach ($this->replyResult["reply"]["likes"]["list"] as $id => $arr) {
					if ($arr["replyID"] == $subReplyID)
						return $arr["userID"];
				}
			}
				
		}
	}
	
	public function removeReply($subReplyID = 0) {
		$this->replyID = $this->db->real_escape_string($this->replyID);
		$subReplyID = $this->db->real_escape_string($subReplyID);
		if ($subReplyID > 0) {
			$query = "delete from news_reply where replyID='".$subReplyID."'";
			$this->db->query($query);
			$this->loadReply();
		} else {
			$query = "delete from news_reply where parentReplyID='".$this->replyID."'";
			$this->db->query($query);
			$query = "delete from news_reply where replyID='".$this->replyID."'";
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
		if ($this->withSubReplies) {
			$query = "select nr.*, ur.userID, ur.displayName, ur.fullname, ur.fbName,
			nr.createdDateTime as subreplyCreatedDateTime from `news_reply` nr
			inner join user_registration ur on ur.userID = nr.userID
			where nr.replyType = 'like' and nr.replyStatus = 'active'
			and parentReplyID = $this->replyID order by nr.createdDateTime desc";
			$resultArr = $this->db->query($query);
			if (is_array($resultArr) && count($resultArr) > 0) {
				foreach ($resultArr as $id => $like) {
					$resultArr[$id] = $this->model2view($like);
				}
				$this->replyResult["reply"]["likes"]["count"] = count($resultArr);
				$this->replyResult["reply"]["likes"]["list"] = $resultArr;
			}
		} else {
			$query = "select count(1) from `news_reply`
					where replyType = 'like' and replyStatus = 'active'
					and parentReplyID = $this->replyID";
			$this->replyResult["reply"]["likes"]["count"] = $this->db->query($query);
		}
	}
	
	private function loadSubReplies() {
		if (true == $this->withSubReplies) {
			$query = "select nr.*, ur.userID, ur.displayName, ur.fullname, ur.fbName,
						nr.createdDateTime as subreplyCreatedDateTime from `news_reply` nr
						inner join user_registration ur on ur.userID = nr.userID
						where nr.replyType != 'like' and nr.replyStatus = 'active' 
						and parentReplyID = $this->replyID order by nr.createdDateTime desc";
			$resultArr = $this->db->query($query);
			if (is_array($resultArr) && count($resultArr) > 0) {
				foreach ($resultArr as $id => $subReply) {
					$resultArr[$id] = $this->model2view($subReply);
				}
				$this->replyResult["reply"]["subReplies"]["count"] = count($resultArr);
				$this->replyResult["reply"]["subReplies"]["list"] = $resultArr;
			}
		} else {
			$query = "select count(1) from `news_reply`
						where replyType != 'like' and replyStatus = 'active' 
						and parentReplyID = $this->replyID";
			$this->replyResult["reply"]["subReplies"]["count"] = $this->db->query($query);
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