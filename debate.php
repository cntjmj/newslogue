<?php
	include_once "config.php";
	$newsMetaArr = $news->GetDetails($database->cleanXSS($_GET["id"]));
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 9]><!--> 
<html class="no-js" lang="en" itemscope itemtype="http://schema.org/Product"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
			 More info: h5bp.com/b/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Newslogue</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<!-- <meta name="author" content="humans.txt"> -->
	<meta name="author" content="Newslogue">

	

	<!-- Facebook Metadata /-->
    <meta property="og:title" content="<?php echo $newsMetaArr['newsTitle']?>" />
    <meta property="og:type" content="article" />
    <meta property="og:description" content="www.newslogue.com" />
    <meta property="og:site_name" content="Newslogue" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:article:author" content="Newslogue" />
    <meta property="og:article:section" content="news" />
    <meta property="og:url" content="http://www.newslogue.com/news/<?=$newsMetaArr["newsID"]?>/<?=$newsMetaArr["newsPermalink"]?>" />
    <meta property="og:image" content="http://www.newslogue.com/uploads/banner/thumbnail/<?=$newsMetaArr["newsBanner"]?>" />


	<!-- Google+ Metadata /-->
	<meta itemprop="name" content="">
	<meta itemprop="description" content="">
	<meta itemprop="image" content="">

	<?php
		include_once "includes/scripts.php";
	?>
</head>

<body>
	<script src="js/main.js"></script>
	<script src="js/nlmain.js"></script>
	<?php
		include_once "includes/popup.php";
	?>
	<!--div class="main-content"-->
	<div class="debate-slideover">
	<!--div class="debate-standalone"-->
<?php
	require_once("ajax/ajax_get_debate.php");
?>
	</div>
	</div>
	<script type="text/javascript">
	$("document").ready(debatePageTasks);
	</script>
<?php
	include_once "includes/footer.php";
?>
