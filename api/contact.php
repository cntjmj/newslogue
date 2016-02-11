<?php
	require_once '../nl-init.php';
	require_once '../class/nl-mailer-class.php';
	require_once 'api_headers.php';
	
	if (is_post()) {
		
		echo json_encode(['errCode' => 0]);
	}
?>