<?php
	include_once "config.php";

	$filterCategory = $database->cleanXSS(@$_GET["id"]);
	$type = $database->cleanXSS(@$_GET["type"]);
	$fastfeedName = $database->cleanXSS(@$_GET["fastfeedName"]);
	
	$user_agent = @$_SERVER["HTTP_USER_AGENT"];

	//if (stristr($user_agent,"iphone") || stristr($user_agent, "android")) {
	//	header("Location: http://localhost/newslogue/m");
	//	exit();
	//}
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
	<meta property="fb:page_id" content="" />
	<meta property="og:image" content="" />
	<meta property="og:description" content=""/>
	<meta property="og:title" content=""/>

	<!-- Google+ Metadata /-->
	<meta itemprop="name" content="">
	<meta itemprop="description" content="">
	<meta itemprop="image" content="">


	<?php
		include_once "includes/scripts.php";
	?>
</head>

<body>
	<?php
		include_once "includes/header.php";

		$hottopicRstArr = $news->DisplayHotTopicDetails(1,5,"");

	?>


	<!--div class="debate-slideover"-->
		<!-- <div class="" style="width: 65px; height: 65px; position: absolute; top: 50%; left: 50%; margin: -33px 0 0 -33px">		
			<svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
			   <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
			</svg>
		</div> -->
	<!--/div-->
	
	<div class="main-content">
		<div class="row">
			<div class="main-panel">				
		
				<div class="feed-listing">
				<?php
					if($type == "fastfeed")
					{
						//echo "<h2>".ucwords($_GET["fastfeedName"])."</h2>";

							echo "<h2>".$_GET["fastfeedName"]."</h2>";
						$newsRstArr = $news->DisplayFastFeedDetails(1,5,$fastfeedName);
					}
					else
					{
						if($filterCategory  != "")
							echo "<h2>".ucwords($_GET["permalink"])."</h2>";

						$newsRstArr = $news->DisplayAllDetails(1,5,$filterCategory);
						
					}
					
					if(is_array($newsRstArr["List"]) && count($newsRstArr["List"]) > 0)
					{
						foreach($newsRstArr["List"] as $id => $val)
						{
							
							$createdDateTime = strtotime($val["nacreatedDateTime"]);
							$createdDateTime = date("F d, Y",$createdDateTime);
							$newsAllReplyRstArr = $news->DisplayAllReplyDetails(1,10000000000,$val["newsID"]);
							$agreeAmt = $disagreeAmt = 0;
							if(is_array($newsAllReplyRstArr["List"]) && count($newsAllReplyRstArr["List"]) > 0)
							{
								foreach($newsAllReplyRstArr["List"] as $r2ID => $r2Value)
								{
									if($r2Value["replyType"] == "agree")
										$agreeAmt++; 	
									else
										$disagreeAmt++; 	

								}
							}
							//print_r($val);
				?>
							<div class="feed-item">

								<div class="feed-item-title"><a href="news/<?php echo $val["newsID"]."/".$val["newsPermalink"]?>"><?php echo $val["newsTitle"]?></a></div>														

								<a href="news/<?php echo $val["newsID"]."/".$val["newsPermalink"]?>">
									<div class="feed-item-image" style="background-image: url(<?php echo 'uploads/banner/thumbnail/'.$val['newsBanner']?>);"></div>
									<div class="feed-item-newstip">										
										<?php 
											$newsContent = html_entity_decode($val["newsContent"]);
											if(strlen($newsContent) > 300){
												$stringCut = substr($newsContent, 0, 300);
												if (false !== ($lp = strrpos($stringCut, "<")) &&
													 false === strpos($stringCut, ">", $lp)) {
													$stringCut = substr($stringCut,0,$lp-1);
												}
												$newsContent = substr($stringCut, 0, strrpos($stringCut, ' ')).' ...';
											}
											
											echo $newsContent;
										 ?>										
									</div>
								</a>	
								<div class="feed-cat-date"><?php echo $val["categoryName"]." | ".$createdDateTime?> </div>
								<div class="feed-question clearfix">
									<div class="feed-quote quote-open"><img src="img/quote-mark-open.png"></div>
									<div class="feed-question-text"><?php echo $val["newsQuestion"]?></div>
									<div class="feed-quote"><img src="img/quote-mark-close.png"></div>
									<div class="feed-debate-cta">
										<!--a href="javascript:;" class="dispute-link" data-newsID="<?php echo $val["newsID"]?>">Dispute <i class="icomo-dispute"></i></a-->
										<a href="debate/<?php echo $val["newsID"]."/".$val["newsPermalink"]?>" class="dispute-link" data-newsID="<?php echo $val["newsID"]?>">Dispute <i class="icomo-dispute"></i></a>
									</div>
								</div>
							</div>
				<?php			
						}

				?>

				<?php

						
					}
					else
					{

						
						echo '<div class="feed-item12">

									There is no resulted news.

								</div>';
					}
				?>
				</div>
				<?php
					
					if($newsRstArr["LastPage"] > 1)
						{
				?>
							<div class="loadmore-col">
								<a href="javascript:;" class="feed-loadmore-btn" data-pageNo="1" data-maxPageNo="<?=$newsRstArr["LastPage"]?>" data-id="<?php echo $filterCategory?>" data-type="<?php echo $type?>" data-fastfeed="<?=$fastfeedName?>">
									<i class="icomo-refresh"></i>
									Load More Stories
								</a>
							
							</div>
				<?php
						}
				?>

				
			</div>
			<div class="rightbar">
				<!-- hottopic-->
				<div class="hottopic-col">
					<div class="hottopic-bubblearrow"></div>
					<div class="small-compo-title"><i class="icomo-hot"></i>Hot Topics</div>
					<div class="hottopic-arrows">
						<a href="#htrarrow" class="htarrow" data-nav="prev"><i class="icon-left-open"></i></a>
						<a href="#htlarrow" class="htarrow" data-nav="next"><i class="icon-right-open"></i></a>
					</div>
					<?php
						

						if(is_array(@$hottopicRstArr["List"]) && count(@$hottopicRstArr["List"]) > 0)
						{

							echo '
								<div class="flexslider hottopicslider">
									<ul class="slides">
							';
							foreach($hottopicRstArr["List"] as $hID => $hValue)
							{
								
								$newsHotTopicReplyRstArr = $news->DisplayAllReplyDetails(1,10000000000,$hValue["newsID"]);
								$agreeAmt = $disagreeAmt = 0;
								if(is_array($newsHotTopicReplyRstArr["List"]) && count($newsHotTopicReplyRstArr["List"]) > 0)
								{
									foreach($newsHotTopicReplyRstArr["List"] as $r3ID => $r3Value)
									{
										if($r3Value["replyType"] == "agree")
											$agreeAmt++; 	
										else
											$disagreeAmt++; 	

									}
								}



								echo '
									<li>
										<h5><a href="news/'.$hValue["newsID"].'/'.$hValue["newsPermalink"].'">'.$hValue["newsQuestion"].'</a></h5>
										<a href="debate/'.$hValue["newsID"].'/'.$hValue["newsPermalink"].'" class="dispute-link" data-newsID="'.$hValue["newsID"].'"> Dispute <i class="icomo-dispute"></i></a>
										
										
									</li>

								';
							}

							echo '
								</ul>
								</div>
							';
						}
					?>
					
							
				</div>

				<?php
					$trendingRstArr = $news->DisplayTrendingDetails(1,5,"");

					if(is_array(@$trendingRstArr["List"]) && count(@$trendingRstArr["List"]) > 0)
					{

						echo '
							<!-- trending-->
							<div class="trendingstories-col">
								<div class="small-compo-title"><i class="icomo-trending"></i>Trending Stories</div>
						';
						foreach($trendingRstArr["List"] as $tID => $tValue)
						{
							$tcreatedDateTime = strtotime($tValue["nacreatedDateTime"]);
							$tcreatedDateTime = date("F d, Y",$tcreatedDateTime);

							echo '
								<div class="trending-item">
									<a href="news/'.$tValue["newsID"].'/'.$tValue["newsPermalink"].'"><img src="uploads/banner/thumbnail/'.$tValue["newsBanner"].'" alt="trending thumbnail"></a>
									
									
									<h3><a href="news/'.$tValue["newsID"].'/'.$tValue["newsPermalink"].'">'.$tValue["newsTitle"].'</a></h3>
								</div>
							';
						}

						echo '
							</div>
						';
					}
				?>
				
					


				<!-- newsletter-->
				<div class="newsletter-col">
					<div class="small-compo-title"><i class="icomo-email"></i>Subscribe To Our Mailing List</div>

					<p>
						Subscribe now! Be the first to hear about updated news and debates from Newslogue.
					</p>

					<form method="post" name="subscription-form" class="subscription-form">
						<input type="text" class="subscribe-input" name="emailaddress" placeholder="Write your email here...">
						<button><i class="icomo-submit"></i></button>
						<input type="hidden" name="send" value="SUBMIT">
					</form>
					<div class="subscription-notification"></div>
				</div>
				<!-- google AdSense -->
				<div>
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- First Choice -->
					<ins class="adsbygoogle"
					     style="display:block"
					     data-ad-client="ca-pub-1570657114135011"
					     data-ad-slot="4295952686"
					     data-ad-format="auto"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
			</div>
		</div>
	</div>
	
<?php
	include_once "includes/footer.php";
?>
