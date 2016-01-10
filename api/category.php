<?php
	require_once '../nl-init.php';
	require_once '../class/nl-category-class.php';
	require_once 'api_headers.php';
	
	if (is_get()) {
		$categoryID = _get("categoryID", 0);
		
		if ($categoryID <= 0) {
			$categoryList = new CategoryList();
			echo $categoryList->getJson();
		}
	}
?>