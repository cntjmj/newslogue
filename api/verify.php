<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once '../class/nl-user-class.php';
	require_once 'api_headers.php';

	/*
	 * this is not actually an api service.
	 * however, i still decided to put it here.
	 * 
	 */
	$emailaddress = _get("emailaddress", "");
	$uniqCode = _get("uniqCode", "");
	
	if ($emailaddress != "" && $uniqCode != "") {
		$user = new User();
		$user->VerifyCode($emailaddress, $uniqCode);
		if ($user->getUserID() > 0) {
			/*
			 * TODO:
			 * should we also provide a proper error page here?
			 * or anywhere?
			 * 
			 */
		}
	}
?>