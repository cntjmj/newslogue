<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once '../class/nl-reply-class.php';
	require_once 'api_headers.php';

	try {
		if (is_get()) {
			$newsID = _get("newsID", 0);
			$replyID = _get("replyID", 0);
			$userID = _get("userID", 0);
			$withSubReplies = _get("withSubReplies", true);
		
			if ($replyID > 0) {
				$reply = new Reply($replyID, $withSubReplies);
				echo $reply->getJson();
			} else if ($newsID > 0) {
				$replies = new NewsReplyList($newsID, $withSubReplies);	
				echo $replies->getJson();
			} else if ($userID > 0) {
				$auth = Auth::getInstance();
				if ($userID != $auth->getUserID())
					throw new Exception("unauthorised operation", -1);
				
				$replies = new UserReplyList($userID);
				echo $replies->getJson();
			}
		} else if (is_post()){
			$auth = Auth::getInstance();
			$result = array("errCode" => "-1");
			
			if ($auth->canReply()) {
				$userID = $auth->getUserID();
				
				$newsID = _get("newsID", 0);	// here, we have to GET params in POST method
				$replyID = _get("replyID", 0);	// this issue would be gone if use any framework
				
				$replyStatement = _post("replyStatement", ""); // deprecated in current design
				$replyContent = _post("replyContent", "");
				$replyType = _post("replyType", "");
				
				if ($newsID != 0) {
					$replyObj = new Reply();
					$replyObj->saveReply($replyID, $newsID, $userID, $replyStatement, $replyContent, $replyType);
					$result = $replyObj->getArray();
				}
			}
			
			echo json_encode($result);
		} else if (is_del()) {
			$newsID = _get("newsID", 0);
			$replyID = _get("replyID", 0);
			$subReplyID = _get("subReplyID", 0);
			
			if ($replyID == 0)
				throw new Exception("no such replyID", -1);
			
			$auth = Auth::getInstance();
			
			$replyObj = new Reply($replyID);
			$userID = $replyObj->getReplyUserID($subReplyID);
			
			if ($userID != $auth->getUserID())
				throw new Exception("unauthorized deletion", -1);
			
			$replyObj->removeReply($subReplyID);
			
			if ($subReplyID > 0) {
				echo $replyObj->getJson();
			} else {
				echo json_encode(array("errCode"=>0, "errMessage"=>"deletion succeeded", "replyID"=>$replyID));
			}
		}
	} catch (Exception $e) {
		$result = array("errCode" => $e->getCode(), "errMessage" => $e->getMessage());
		echo json_encode($result);
	}
?>