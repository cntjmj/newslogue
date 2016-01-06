<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once '../class/nl-vote-class.php';
	require_once 'api_headers.php';

	try {
		if (is_get()) {
			if (0 >= ($newsID = _get("newsID", 0)))
				throw new Exception("could not find news by ID", -1);

			$voteObj = new Vote($newsID);

			echo $voteObj->getJson();
		} else if (is_post()) {
			if (0 >= ($newsID = _get("newsID", 0)))
				throw new Exception("could not find news by ID", -1);

			$voteType = _post("voteType", null);
			if ($voteType != "agree" && $voteType != "disagree")
				throw new Exception("unknown vote type ".$voteType, -1);
			
			$auth = Auth::getInstance();
			if (!$auth->canVote())
				throw new Exception("current user cannot vote", -1);
			
			$voteObj = new Vote($newsID);
			
			echo $voteObj->vote($voteType, $auth->getUserID())->getJson();
		}
	} catch (Exception $e) {
		$result = array("errCode" => $e->getCode(), "errMessage" => $e->getMessage());
		echo json_encode($result);
	}
?>