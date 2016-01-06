<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once '../class/nl-notification-class.php';
	require_once 'api_headers.php';
	
	try {
		if (is_get()) {
			$num_per_page = _get("num_per_page",0);
			$page_num = _get("page_num",0);
			
			$userID = _get("userID", "");
			
			if ($userID == 0)
				throw new Exception("incorrect user ID", -1);
			
			$auth = Auth::getInstance();
			
			if ($userID != $auth->getUserID())
				throw new Exception("unauthorized operation", -1);
			
			$ntfObj = new Notification($userID, $page_num, $num_per_page);
			
			echo $ntfObj->getJson();
		}
	} catch (Exception $e) {
		$result = array("errCode" => $e->getCode(), "errMessage" => $e->getMessage());
		echo json_encode($result);
	}

?>