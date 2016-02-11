<?php
	require_once '../nl-init.php';
	require_once '../class/nl-mailer-class.php';
	require_once 'api_headers.php';
	
	if (is_post()) {
		$emailaddress = _post("emailaddress", "");
		$displayName = _post("displayName", "");
		$message = _post("message", "");
		
		echo $emailaddress.$displayName.$message;

		$mailer = new Mailer();
		$mailer->sendEnquiry($emailaddress, $displayName, $message);
		echo json_encode(['errCode' => 0]);
	}
?>