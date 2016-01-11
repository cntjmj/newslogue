<?php
   	function isTestEnv() {
		return !isProductEnv();
   	}
	
	function isProductEnv() {
		$pro = array('www.newslogue.com', 'm.newslogue.com', "api.newslogue.com");
		
		if(in_array($_SERVER['HTTP_HOST'], $pro))
			return true;
		else if (strpos($_SERVER['HTTP_HOST'], ".newslogue.com"))
			return true;
		else
			return false;
	}
	
	if (isTestEnv()) {	// Test Environment
		/**
		 * MUST CHANGE THESE SETTINGS
		 * ACCORDING TO YOUR TEST ENV,
		 * SO THAT YOUR SYSTEM COULD BE UP AND RUNNING.
		 *                        AFTER THAT, HAVE FUN!
		 */
		class CONFIG_DB {
			const HOSTNAME = "127.0.0.1";
			const USERNAME = "root";
			const PASSWORD = "passw0rd";
			const INSTNAME = "newslogu_v1";
		};
		
		class CONFIG_PATH {
			const GLOBAL_WWW_BASE = "http://www.nl.com/";
			const GLOBAL_M_BASE = "http://m.nl.com/";
			const GLOBAL_API_BASE = "http://api.nl.com/";
			
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