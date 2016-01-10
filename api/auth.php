<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once 'api_headers.php';
	
	if (is_get()) {
		$auth = Auth::getInstance();
		echo $auth->getJson();
	} elseif (is_post()) {
		$emailaddress = _post("emailaddress", "");
		$password = _post("password", "");
		
		try {
			$auth = Auth::getInstance();
			echo $auth->login($emailaddress, $password)->getJson();
		} catch (Exception $e) {
			$result = array("errCode" => $e->getCode(), "errMessage" => $e->getMessage());
			echo json_encode($result);
		}
	} elseif (is_del()) {
		$auth = Auth::getInstance();
		echo $auth->logout()->getJson();
	}
?>