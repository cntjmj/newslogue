<?php
class Coder {
	/**
	 * I hate the function name "cleanData",
	 * but had to use it for backward compatibility
	 */
	public static function cleanData(&$input) {
		$input = stripslashes($input);
		$input = str_replace("\xA0", '&nbsp;', $input );

		return $input;
	}
	
	public static function cleanXSS($db, &$input, $type = "html") {
		trim($input);
		$db->real_escape_string($input);
		if ($type == "html")
			$input = htmlentities($input);
		else if ($type = "int")
			$input = intval($input);
		
		return $input;
	}
	
	public static function dbstr2date(&$dbstr, $format = "F d, Y") {
		$dbstr = strtotime($dbstr);
		$dbstr = date($format,$dbstr);
		
		return $dbstr;
	}
	
	public static function htmldecode(&$encoded) {
		$encoded = $decoded = html_entity_decode($encoded);

		return $decoded;
	}
	
	public static function summarize(&$content, $len) {
		if ($len == 0) {
			$content = "";
		} else if ($len > 0 && strlen($content) > $len) {
			$content = substr($content, 0, $len);
			if (false !== ($lp = strrpos($content, "<")) &&
				false === strpos($content, ">", $lp)) {
				$content = substr($content,0,$lp-1);
			}
			$content = substr($content, 0, strrpos($content, ' ')).' ...';
		} // if len < 0, do NOT summarize content
		
		return $content;
	}
}
?>