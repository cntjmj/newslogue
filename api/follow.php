<?php
	require_once '../nl-init.php';
	require_once '../class/nl-user-class.php';
	require_once '../class/nl-auth-class.php';
	require_once 'api_headers.php';
	
	$auth = Auth::getInstance();
	$userID = $auth->getUserID();
	if ($userID <= 0)
		return;
	
	if (is_get()) {
		$user = new User($userID);
		echo json_encode(array("follows" => $user->follows()));
	} else {
		$adminID = _get("adminID", 0);
		if ($adminID <= 0)
			return;
		
		$user = new User($userID);
		$user->follow($adminID);
		
		echo json_encode(array("follows" => $user->follows()));
	}
?>