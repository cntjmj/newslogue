<?php
	require_once __DIR__."/../../nl-config.php";
	require_once __DIR__."/head.php";
	require_once __DIR__."/header.php";
	require_once __DIR__."/nav.php";
	require_once __DIR__."/footer.php";
	
	function htmlBegin($ngController = "") {
		if ($ngController != "")
			$ng = "ng-app=\"nlapp\" ng-controller=\"$ngController\"";
		echo "<!doctype html>\n<html $ng>\n";
	}
	
	function htmlBodyBegin() {
		echo "<body>\n";
	}
	
	function htmlBodyEnd() {
		echo "\t<script src=\"".CONFIG_PATH::GLOBAL_M_BASE."js/menu.js\"></script>\n";
		echo "\t<script src=\"".CONFIG_PATH::GLOBAL_WWW_BASE."js/ng-newslogue.js\"></script>\n";
		echo "</body>\n</html>";
	}
?>