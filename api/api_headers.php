<?php
	require_once dirname(__FILE__).'/../common/nl-common.php';

	if (isset($_SERVER['HTTP_ORIGIN']))
		header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, TRACE');
	header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
?>