<?php
	include_once "config.php";
	$newsID = $database->cleanXSS($_GET["id"]);
	$newsRstArr = $news->GetDetails($newsID);
	// $newsReplyRstArr = $news->DisplayReplyDetails(1,10,$newsID);
	//$newsAllReplyRstArr = $news->DisplayAllReplyDetails(1,10000000000,$newsID);
	$newsAllVotedRstArr = $news->DisplayAllVotedDetails(1,10000000000,$newsID);
	$newsThoughtRstArr = $news->DisplayAllThoughts($newsID);

	$userVoted = $news->UserVoted($newsID);

	if(is_array($newsRstArr) && count($newsRstArr) > 0)
	{
		$news->ViewPage($newsID);
	}

	$agreeAmt = $disagreeAmt = 0;

	if(is_array($newsAllVotedRstArr["List"]) && count($newsAllVotedRstArr["List"]) > 0)
	{

		foreach($newsAllVotedRstArr["List"] as $r2ID => $r2Value)
		{
			if($r2Value["voteType"] == "agree")
				$agreeAmt++;
			else
				$disagreeAmt++;

		}
	}
	$totalagreement = $agreeAmt + $disagreeAmt;

	$agreePercent = 0;
	if($totalagreement > 0)
		$agreePercent = ceil(($agreeAmt/$totalagreement) * 100);


	$disagreePercent = 100 - $agreePercent;
	if(!$newsAllVotedRstArr)
		$disagreePercent = 0;

	$novotes = false;
	if($disagreePercent == 0 && $agreePercent == 0)
	{
		$novotes = true;
	}

	$moreNewsRst = $news->DisplayMoreDetails($newsRstArr["categoryID"],$newsID);


	//$commentedRst = $news->HasUserReply($newsID);
	
	// for twitter meta data
	$Get_img_host = "http://" . $_SERVER['HTTP_HOST'];
	$newsBannerName = $newsRstArr["newsBanner"];
	$Get_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
    <meta property="og:title" content="<?php echo $newsRstArr['newsTitle']?>" />
    <meta property="og:type" content="article" />
    <meta property="og:description" content="www.newslogue.com" />
    <meta property="og:site_name" content="Newslogue" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:article:author" content="Newslogue" />
    <meta property="og:article:section" content="news" />
    <meta property="og:url" content="http://www.newslogue.com/news/<?=$newsRstArr["newsID"]?>/<?=$newsRstArr["newsPermalink"]?>" />
    <meta property="og:image" content="http://www.newslogue.com/uploads/banner/thumbnail/<?=$newsRstArr["newsBanner"]?>" />


	<!-- Google+ Metadata /-->
	<meta itemprop="name" content="">
	<meta itemprop="description" content="">
	<meta itemprop="image" content="">

	<!-- Twitter Metadata -->
	<meta name="twitter:card" content="photo" />
	<meta name="twitter:site" content="@newslogue" />
	<meta name="twitter:title" content="<?php echo $newsRstArr['newsTitle']?>" />
	<meta name="twitter:description" content="<?php echo $newsRstArr['newsDesc']?>" />
	<meta name="twitter:image" content="<?php echo $Get_img_host.'/uploads/banner/thumbnail/'.$newsBannerName ?>"/>	
	<meta name="twitter:url" content="<?php echo $Get_url ?>" />
	

	<?php
		include_once "includes/scripts.php";
	?>
</head>

<body>
	<?php
		include_once "includes/header.php";		
	?>
	
	<?php
		if(is_array($newsRstArr) && count($newsRstArr) > 0)
		{
			$startDateTime = strtotime($newsRstArr["newsStartDate"]);
			$startDateTime = date("d F Y",$startDateTime);
	?>
			<div class="main-content">
				<div class="row">					
					<div class="article-cat-date"><?php echo $newsRstArr["categoryName"]." | ".$startDateTime?></div>
					<article class="specific-article">
						<h2>
							<?php echo $newsRstArr["newsTitle"]?>
						</h2>
                        <?php if (!empty($newsRstArr['newsSource'])) {
                            $pos2 = strpos($newsRstArr["newsSource"],'www.');
                            if ($pos2 !== false) {
                                $newsSource = 'http://' . $newsRstArr["newsSource"];
                            } else {
                                $newsSource = $newsRstArr["newsSource"];
                            }

                            echo "<div class='news-source'>
                                <span>Source: " . $newsSource ." </span>
                            </div>";

                        }
                            ?>
  
						<div class="specific-article-socialmedia">
                            <a href="javascript:;" class="sharefb-link" data-newslink="<?php echo $GLOBAL_WEB_ROOT?>news/<?php echo $newsRstArr["newsID"]."/".$newsRstArr["newsPermalink"]?>" data-newstitle="<?php echo $newsRstArr["newsTitle"]?>" data-newspic="<?php echo $GLOBAL_WEB_ROOT ?>uploads/banner/forsns/<?php echo $newsRstArr['newsBanner'] ?>" data-question="<?php echo $newsRstArr["newsQuestion"] ?>" ><i class="icon-facebook"></i>Share</a>
                            <!-- <a href="https://twitter.com/intent/tweet?text=Checkout newslogue" class="sharetwit-link"><i class="icon-twitter"></i>Tweet</a> -->
                            <a href="https://twitter.com/intent/tweet?url=<? echo $Get_url ?>&text=<?php echo $newsRstArr['newsTitle']?>" class="sharetwit-link"><i class="icon-twitter"></i>Tweet</a>
                            <!-- <a href=""><i class="icon-mail"></i>Email</a> -->
						</div>


						<div class="row" id="centered-content">
							<div class="eight columns" id="centered-item">								
								 <div class="article-desc">
									<?php echo nl2br($newsRstArr["newsDesc"]); ?>
								</div>
								<?php
									echo "<img src='uploads/banner/thumbnail/".$newsRstArr["newsBanner"]."' class='banner-image' >";
                                    if(!empty($newsRstArr['newsBannerSource'])){
                                        $pos1 = strpos($newsRstArr["newsBannerSource"],'www.');
                                        if ($pos1 !== false) {
                                            $newsBannerSource = 'http://' . $newsRstArr["newsBannerSource"];
                                        } else {
                                            $newsBannerSource = $newsRstArr["newsBannerSource"];
                                        }

                                    $wordWrappedString = wordwrap($newsBannerSource, 60, "<br>\n", true);
                                    // echo "<div class='banner-source'><span>" . $newsBannerSource . "</span></div>";
                                    echo "<div class='banner-source'><span>" . $wordWrappedString . "</span></div>";
                                    }
								?>

								<div>
								<?php 
									// $contentWithoutCss = preg_replace('~\<style(.*)\>(.*)\<\/style\>~', '', $newsRstArr["newsContent"]);
									echo html_entity_decode($newsRstArr["newsContent"])
									// $contentStripTags = strip_tags($contentWithoutCss);
									// echo html_entity_decode($contentStripTags);
								?>
								</div>
								<div class="article-date">
									Last updated, 
									<?php

										
										echo $startDateTime;
									?>
									
								</div>
							</div>
							<div class="four columns discussion-response">
								<div class="join-discussion">
									<div class="hottopic-bubblearrow"></div>
									<div class="join-discussion-title">Join The Discussion</div>
									<div class="join-discussion-question">
										<h4><?=$newsRstArr["newsQuestion"]?></h4>
									</div>
									<div class="join-discussion-ratings <?php echo ($novotes)? "empty":""?>">
										<div class="row">
											<div class="six columns "><h3><?php echo $agreeAmt?> voted yes</h3></div>
											<div class="six columns text-right"><h3><?php echo $disagreeAmt?> voted no</h3></div>
										</div>
										<div class="join-discussion-filler clearfix">
											<div class="join-discussion-filler-yes" style="width:<?php echo $agreePercent?>%"></div>
											<div class="join-discussion-filler-no" style="width:<?php echo $disagreePercent?>%"></div>
										</div>
									</div>
									<div class="text-center">
										<a href="debate/<?php echo $newsRstArr["newsID"]."/".$newsRstArr["newsPermalink"]?>" class="join-discussion-dispute-now">Dispute <i class="icomo-dispute"></i></a>
									</div>
								</div>
							</div>
						</div>
						<?php
							if(is_array(@$moreNewsRst["List"]) && count(@$moreNewsRst["List"]) > 0)
							{
						?>
								<div class="row">
									<div class="eight columns" id="centered-content">
										<div class="more-news-listing">
											<h3>More from Newslogue</h3>
											<?php
												echo "<div class='row'>";
												foreach($moreNewsRst["List"] as $id => $val)
												{
													if ( $id % 3 == 0 && $id > 0 )
														echo "</div><div class='row'>";

													echo "<div class='four columns more-news'>
														<a href='news/".$val["newsID"]."/".$val["newsPermalink"]."' class='more-news-item'>
															<img class='more-news-img' src='uploads/banner/thumbnail/".$val["newsBanner"]."'>
															<div class='more-news-title'>
															<span>".$val["newsTitle"]."</span>
															</div>
														</a>
													</div>";
												}
												echo "</div>";
											
											?>
											<ul>
												<li></li>
											</ul>
										</div>
									</div>
								</div>
						<?php
							}
						?>
					</article>					
				</div>
			</div>
	<?php
		}
		else
		{
			
		}
	?>
	<script type="text/javascript" async src="//platform.twitter.com/widgets.js"></script>
<?php
	include_once "includes/footer.php";
?>
