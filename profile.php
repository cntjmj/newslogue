<?php
	include_once "config.php";

	

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
				<div class="breadcrumbs">Edit Profile</div>
				<div>
					<?php echo $notificationMsg;?>
				</div>
				<section class="profile">
					
						<div class="userdisplay-pic-col">
							<div class="row">
			
								<div class="two columns">
								<?php
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
									<h1 class="userdashboard-name"><?php echo ucwords($userRstArr['fullname']) ?></h1>
									<form method="post" name="profilepicture-form" class="profilepicture-form" enctype="multipart/form-data">			
									<?php
										// commented by Kevin on 30th July, 2015
										// if($userRstArr["fbID"] == "")
										if($userRstArr["fbID"] == "" || $userRstArr["fbID"] == "undefined")											
										{
									?>
											<label>
												<input type="file" name="userProfilePicture">
											</label>
											<input type="submit" class="primarybtn" value="Save Changes">
											<input type="hidden" name="send" value="SUBMIT">
									<?php
										}
									?>
								</form>
								</div>
							</div>
						</div>
						<div class="row">
						
							<div class="twelve columns">
							
								<div class="email-notification"></div>
								<div class="userdetails-col">
									<h4>Change Email</h4>
									<div class="email-notice">
										We're currently sending updates to 
										<span><?php echo $_SESSION["emailaddress"]?></span>
									</div>
									<form method="post" class="changeemail-form" name="changeemail-form">
										<label>
											New email
											<input type="text" name="newemail" placeholder="you@example.com" value="" id="newemail">
										</label>
										<label>
											Confirm new email
											<input type="text" name="confirmnewemail" placeholder="you@example.com" value="">
										</label>


										<input type="submit" class="primarybtn" value="Save Changes">
										<input type="hidden" name="send" value="SUBMIT">


										
									</form>
								</div>
								
							</div>
						</div>


						<div class="row">
						
							<div class="twelve columns">
							
								<div class="name-notification"></div>
								<div class="userdetails-col">
									<h4>Change Name</h4>

									<form method="post" class="displayname-form" name="displayname-form">
										<label>
											Display Name
											<input type="text" name="displayName" placeholder="Display Name" value="<?php echo $userRstArr["displayName"]?>">
										</label>
										<label>
											Full Name
											<input type="text" name="fullname" placeholder="Full Name" value="<?php echo $userRstArr["fullname"]?>">
										</label>


										<input type="submit" class="primarybtn" value="Save Changes">
										<input type="hidden" name="send" value="SUBMIT">


										
									</form>
								</div>
								
							</div>
						</div>


						<div class="row">
						
							<div class="twelve columns">
							
								<div class="password-notification"></div>
								<div class="pwd-col">
									<h4>Change Password</h4>

									<form method="post" class="pwd-form" name="pwd-form">
										<label>
											Old password
											<input type="password" name="oldpwd" placeholder="Old Password">
										</label>
										<label>
											New password
											<input type="password" name="newpwd" id="newpwd" placeholder="New Password">
										</label>
										<label>
											Confirm new password
											<input type="password" name="confirmnewpwd" placeholder="Confirm New Password">
										</label>
										<input type="submit" class="primarybtn" value="Save Changes">
										<input type="hidden" name="send" value="SUBMIT">
										
									</form>
								</div>
								
							</div>
						</div>
						
					
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