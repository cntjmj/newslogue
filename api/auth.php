<?php
	require_once '../class/nl-auth-class.php';
	require_once 'api_headers.php';
	
	$auth = Auth::getInstance();
	
	echo $auth->getJson();
?>