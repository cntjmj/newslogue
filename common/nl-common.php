<?php
	require_once dirname(__FILE__).'/../nl-config.php';
	
	function _get($param, $default = null) {
		return isset($_GET[$param])?$_GET[$param]:$default;
	}
	
	function _post($param, $default = null) {
		return isset($_POST[$param])?$_POST[$param]:$default;
	}
	
	function is_get() {
		return $_SERVER['REQUEST_METHOD']=="GET"?true:false;
	}
	
	function is_post() {
		return $_SERVER['REQUEST_METHOD']=="POST"?true:false;
	}
	
	function is_put() {
		return $_SERVER['REQUEST_METHOD']=="PUT"?true:false;
	}
	
	function is_del() {
		return $_SERVER['REQUEST_METHOD']=="DELETE"?true:false;
	}

	function set_cookie($key, $value) {
		setcookie($key,$value, time()+365*24*60*60, "/", CONFIG_PATH::GLOBAL_DOMAIN);
	}
?>