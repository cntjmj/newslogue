<?php
	require_once dirname(__FILE__).'/nl-config.php';
	require_once dirname(__FILE__).'/common/nl-common.php';
	
	function start_session() {
		if (session_id() == "") {
			ini_set('session.cookie_domain', CONFIG_PATH::GLOBAL_DOMAIN);
			session_start();
		}
	}
	
	start_session();
?>