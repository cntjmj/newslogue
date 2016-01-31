<?php 
	require_once "../nl-init.php";
	require_once "template/template.php";
	
	$categoryID = _get("categoryID", 0);
	$newsStatus = _get("newsStatus", "");
	$ngController = "IndexController";
	$title = "Newslogue Home";

	htmlBegin($ngController);
	htmlHead($title);
	htmlBodyBegin();
?>
	<div id="container">
<?php
	htmlHeader();
	if ($newsStatus == "")
		htmlNav();
?>
	<main id="index_main_section" infinite-scroll='loadmore()' infinite-scroll-disabled='!ready2scroll'>
		<section ng-repeat="news in newsMetaList" class="article_content">
			<div class="ind_question content">
				<h1> {{news.newsQuestion}} </h1>
			</div>
			<a href="{{news.newsSource}}" class="popup-external-iframe">
				<div class="ind_photo" style="background-image:url('{{news.newsBannerSource}}');">
				</div>
			</a>
			<div class="ind_title_article">
				<div class="ind_article_bg"></div>
				<h4> - {{news.newsTitle}} - <a class="popup-external-iframe" href="{{news.newsSource}}">READ MORE</a></h4>
				<h5 style="margin-top:5px;">{{news.newsSite}}</h5>
			</div>
			<div class="ind_debate">
				<a href="/debate/{{news.newsID}}" target="_blank"> DEBATE </a>
			</div>
			<div class="border"></div>
			<div class="clear"></div>
		</section>
	</main>
<?php 
	htmlFooter();
?>
	</div>
	<div id='index_click_blocker' class="click_blocker"></div>
	<script>
		var selectedCategoryID = <?=$categoryID?>;
		var newsStatus = "<?=$newsStatus?>";
	</script>
	<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/lazy-loading-js.js" type="text/javascript"></script>
	<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/magnific-popup.js"></script>
<?php 
	htmlBodyEnd();
?>