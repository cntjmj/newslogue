<?php
   	function isTestEnv() {
		$local = array('localhost', 'www.nl.com', 'm.nl.com', "api.nl.com");

	    if(in_array($_SERVER['HTTP_HOST'], $local))
			return true;
	    else
	    	return false;
	}
	
	function isProductEnv() {
		return !isTestEnv();
	}
	
	if (isTestEnv()) {	// Test Environment
		class CONFIG_DB {
			const HOSTNAME = "127.0.0.1";
			const USERNAME = "root";
			const PASSWORD = "passw0rd";
			const INSTNAME = "newslogu_v1";
		};
		
		class CONFIG_PATH {
			const GLOBAL_WWW_BASE = "http://www.nl.com/"; //"http://localhost/newslogue/";
			const GLOBAL_M_BASE = "http://m.nl.com/"; //"http://localhost/newslogue/m/";
			const GLOBAL_API_BASE = "http://api.nl.com"; //"http://localhost/newslogue/api/";
			
			const GLOBAL_DOMAIN = "nl.com";
		};
	} else {			// Product Environment !!!
		class CONFIG_DB {
			const HOSTNAME = "localhost";
			const USERNAME = "newslogu_sizzo";
			const PASSWORD = "newslogue!123";
			const INSTNAME = "newslogu_v1";
		};
		
		class CONFIG_PATH {
			const GLOBAL_WWW_BASE = "http://www.newslogue.com/";
			const GLOBAL_M_BASE = "http://m.newslogue.com/";
			const GLOBAL_API_BASE = "http://api.newslogue.com/";
			
			const GLOBAL_DOMAIN = "newslogue.com";
		};
	}
	
	class CONFIG {
		const ALLOW_ANONYMOUS = 1;
		const GLOBAL_DEFAULTTIMEZONE = "Australia/Melbourne";
	}
?>