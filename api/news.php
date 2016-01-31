<?php
	require_once '../class/nl-news-class.php';
	require_once 'api_headers.php';

	$newsID = _get("newsID",0);
	
	if ($newsID > 0) {
		$news = new News($newsID);

		echo $news->getJson();
	} else {
		$num_per_page = _get("num_per_page",5);
		$page_num = _get("page_num",0);
		$categoryID = _get("categoryID","");
		$summary_len = _get("summary_len",0);
		$newsStatus = _get("newsStatus", "");
		
		$newsList = new NewsList($page_num, $num_per_page, $categoryID, $summary_len);
		if ($newsStatus != "")
			$newsList->setNewsStatus($newsStatus);
	
		echo $newsList->getJson();
	}
?>