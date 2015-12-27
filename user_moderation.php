<?php
	include_once "config.php";

	if(@$_SESSION["userID"] == 0)
	{
		header("location: index.php");
		exit;
	}

	$notificationClass = "";
    $notificationMsg = "";
	if(@$_POST["send"] == "SUBMIT")
	{
		$errorArray = $user->ValidateProfilePictureForm($_POST,"Add");
            
        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";    
            
            $notificationClass = "error";
        }
        else
        {
            $result = $user->ChangeProfilePicture($_POST);
            if($result > 0)
            {
                
                $notificationClass = "success";
                $notificationMsg = "Profile Picture is successfully updated.";
                
            }    
        }
		
	}
	else if(@$_POST["send2"] == "SUBMIT")
	{
		$errorArray = $user->ValidateNameForm($_POST,"Add");
            
        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";    
            
            $notificationClass = "error";
        }
        else
        {
            $result = $user->ChangeName($_POST);
            if($result > 0)
            {
                
                $notificationClass = "success";
                $notificationMsg = "Name is updated successfully.";
                
            }    
        }
		
	}
	else if(@$_POST["send3"] == "SUBMIT")
	{
		$errorArray = $user->ValidatePwdForm($_POST,"Add");
            
        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";    
            
            $notificationClass = "error";
        }
        else
        {
            $result = $user->ChangePwd($_POST);
            if($result > 0)
            {
                
                $notificationClass = "success";
                $notificationMsg = "Password is changed successfully.";
                
            }    
        }
		
	}

	$userRstArr = $user->GetDetails($_SESSION["userID"]);



	$fullname = ($userRstArr["fbID"] == "")? $userRstArr["fullname"]: $userRstArr["fbName"];

	$pageNo = (@$_GET["pageNo"] == "")? 1:$database->cleanXSS($_GET["pageNo"],"int");
	$status = (@$_GET["status"] == "" || @$_GET["status"] == "pending")? "pending": "active";
	$debateDataRstArr = $user->DisplayDebateByUser($pageNo,10,$status,$_SESSION["userID"]);
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


		if(@$_SESSION["userID"] > 0)
		{

	?>
	
		<div class="main-content">
			<div class="row">
				<div class="breadcrumbs">User Dashboard</div>
				<div>
					<?php echo $notificationMsg;?>
				</div>
				<section class="profile">
					<div class="row">
						
						<div class="two columns">
						<?php
							// commented by Kevin on 30th July, 2015
							// if($userRstArr["userProfilePicture"] != "")
							// {
							// 	echo '<img src="uploads/profile/thumbnail/'.$userRstArr["userProfilePicture"].'">';
							// }
							// else
							// 	echo '<img src="img/avatar.jpg">';

							if($userRstArr["userProfilePicture"] == "" || $userRstArr["userProfilePicture"] == "undefined")
							{
								echo '<img src="img/avatar.jpg">';
							}
							else
							{
								echo '<img src="uploads/profile/thumbnail/'.$userRstArr["userProfilePicture"].'">';
							}

						?>
						</div>
						<div class="ten columns">
							<!-- commented by Kevin on 30th July, 2015 -->
							<!-- <h1 class="userdashboard-name"><?php echo ucwords($fullname)?></h1> -->
							<h1 class="userdashboard-name"><?php echo ucwords($userRstArr['fullname'])?></h1>
							<a href="profile.php" class="userdashboard-toedit">Edit Profile</a>
						</div>
					</div>
					
					
					<div class="row">
						<div class="twelve columns">

							<nav class="debate-status-navi">
								<a href="user_moderation.php?status=pending" class="<?php echo (@$status == "pending")? "selected":""?>">Pending for approval</a>
								<a href="user_moderation.php?status=publish" class="<?php echo (@$status == "active")? "selected":""?>">Published</a>
							</nav>
						</div>
					</div>
				
					<ul class="debate-data-listing">
						<?php
							if(is_array(@$debateDataRstArr["List"]) && count($debateDataRstArr["List"]) >0 )
							{
								foreach ($debateDataRstArr["List"] as $key => $value) 
								{
									$createdDateTime = strtotime($value["nrcreatedDateTime"]);
									$createdDateTime = date("F d, Y g:i:s A",$createdDateTime);
						?>
									<li>

										<div class="row">
											<div class="eight columns">
												<div class="debate-data-date"><?php echo $createdDateTime?></div>
												<h5><a href="news/<?php echo $value["newsID"]?>/<?php echo $value["newsPermalink"]?>" class="debate-data-title"><?php echo $value["newsTitle"]?></a></h5>
												<div class="debate-data-content">
													<p>
														<span><?php echo $value["replyStatement"]?>.</span>
														<?php echo $value["replyContent"]?>
													</p>
												</div>
											</div>
											<div class="four columns text-right">
												<a href="javascript:;" class="primarybtn delete-debate-btn" data-replyID="<?php echo $value["replyID"]?>">
													Delete <i class="icomo-no"></i>
												</a>
											</div>
										</div>
									</li>
						<?php	
								}
							}
							else
							{
								echo '<li>There is no '.@$_GET["status"].' debate yet.</li>';
							}
						?>
						
					</ul>

					<?php
						if(is_array(@$debateDataRstArr["List"]) && count($debateDataRstArr["List"]) >0 )
						{
							echo '
								<div class="pagination">';
							for($pag=1;$pag<=$debateDataRstArr["LastPage"];$pag++)
							{
								echo '<a href="user_moderation.php?pageNo='.$pag.'" class="selected">'.$pag.'</a>';
								
							}
							echo '
								</div>
							';
						}
					?>
					

				</section>
			</div>
		</div>
<?php
	}
	else
	{
		echo " not allowed.";
	}
	include_once "includes/footer.php";
?>