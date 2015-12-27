<?php
	include_once "config.php";

	$emailaddress = $database->cleanXSS(@$_GET["emailaddress"]);
	$uniqCode = $database->cleanXSS(@$_GET["c"]);
	$messageHTML = "";
	if( $emailaddress != "" && $uniqCode != "")
	{

		$userRstArr = $user->VerifyForgotLink($emailaddress,$uniqCode);

		if(is_array($userRstArr)  && count($userRstArr) > 0) 
		{
			$userRstArr["uniqCode"] = $uniqCode;
			$userRstArr["emailaddress"] = $emailaddress;
			$user->ResetPassword($userRstArr);
			
			$messageHTML = "You have successfully reset your account.";
		}
		else
		{
			$messageHTML = "The link is no longer active.";
		}
	}
	else
	{
		$messageHTML = "Sorry but we were not able to fetch the page that you requested.";
	}
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
	<meta name="author" content="humans.txt">

	

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
	?>
	
	
	<div class="main-content">
		<div class="row">
			<?php
				echo $messageHTML
			?>
		</div>
	</div>
	
<?php
	include_once "includes/footer.php";
?>