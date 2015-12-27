	<script src="js/main.js"></script>
	<script src="js/nlmain.js"></script>
<?php
//fastfeed
	$fastfeedRstArr = $fastfeed->DisplayAllDetails(1,4,"");
?>
<div class="cage">
<div class="moveme-wrapper">
	<div class="moveme" >
		<div class="overlay">
		</div>

<?php
	include_once "popup.php";
?>

		<header>
			<div class="row">
				<div class="three columns logocol">
					
					<a href="index.php"><img class="main-logo" src="img/mainlogo.png" alt="newslogue logo"></a>
					
				</div>
				<div class="four columns fastfeeds">
<!-- 					<?php
						if(is_array($fastfeedRstArr["List"]) && count($fastfeedRstArr["List"]))
						{
							echo '<span>Quick Links</span>';
							foreach($fastfeedRstArr["List"] as $fID => $fvalue)
							{
								echo '<a href="fastfeed/'.$fvalue["fastFeedID"].'/'.$fvalue["fastFeedTitle"].'" class="ff-item">'.$fvalue["fastFeedTitle"].'</a>';										
							}
						}
					?> -->
				</div>
				<div class="five columns menu">
					<nav class="clearfix">
						<?php
							
							if(@$_SESSION["userID"] > 0)
							{
								// echo '
								// 	<span class="greetuser">Hi <a href="user_moderation.php">'.$_SESSION["displayName"].'</a></span> 
								// 	<a href="javascript:;" class="logout-btn" title="logout"><i class="icomo-logout"></i></a>
								// ';
								echo '
									<span class="greetuser">Hi
										<a href="javascript:;" style="text-decoration: none !important;">'.$_SESSION["fullname"].'&nbsp;&nbsp;&#9660;
										</a>
									</span>									
									<ul class="userinfo outer-click-hide" >
										<li><a href="user_moderation.php" >User Profile</a></li>
										<li><a href="javascript:;" class="logout-btn-debate" title="logout" >Logout</a></li>
									</ul>
									
								';
							}
							else
							{
								echo '
								<a href="javascript:;" class="switchpopup" data-to="registration-popup">Join Now</a>
								<span>/</span>
								<a href="javascript:;" class="switchpopup" data-to="login-popup">Login</a>
								';
							}
						?>
						<?php	if(@$_SESSION["userID"] > 0) { ?>
								<a href="javascript:;" class="notification-link" title="Notifications"><i class="icomo-notification"></i></a>
								<ul id="notification-dropdown-menu" class="notificationinfo outer-click-hide" >
								</ul>
						<?php	} ?>
						<a href="javascript:;" class="search-link" title="Search"><i class="icomo-search"></i></a>
						<a href="javascript:;" class="ham-link" title="Menu"><i class="icomo-menu"></i></a>
					</nav>
				</div>
			</div>
		</header>

<!-- Nav Menu addtion for toggle button -->
		<div class="nav-toggle-menu">
			<div class="row nav-toggle-menu-inner outer-click-hide">
				<div class="nav-toggle-xbutton-div">
					<span class="nav-toggle-xbutton" ><i class="icomo-close" id="close-toggle-menu"></i></span>
				</div>
				<div class="nav-toggle-menu-content">
					<span class="nav-toggle-menu-title">Category</span>
					<?php
								$categoryRstArr = $category->DisplayAllDetails(1,200,"");
								echo "<ul>";
								if(is_array(@$categoryRstArr["List"]) && count(@$categoryRstArr["List"]) >0)
								{
									foreach($categoryRstArr["List"] as $cid => $cvalue)
									{
										echo "<li><a href='filter/".$cvalue["categoryID"]."/".$cvalue["categoryPermalink"]."'>".$cvalue["categoryName"]."</a></li>";
									}
								}
								echo "</ul>";
							?>
				</div>		
				<div class="nav-toggle-menu-content">
					<span class="nav-toggle-menu-title">Quick Links</span>
					<?php
						if(is_array($fastfeedRstArr["List"]) && count($fastfeedRstArr["List"]))
						{		
							echo "<ul>";			
							foreach($fastfeedRstArr["List"] as $fID => $fvalue)
							{
								echo '<li><a href="fastfeed/'.$fvalue["fastFeedID"].'/'.$fvalue["fastFeedTitle"].'" class="ff-item">'.$fvalue["fastFeedTitle"].'</a></li>';			
							}
							echo "</ul>";
						}
					?>
				</div>	
				<div class="nav-toggle-menu-content">
					<span class="nav-toggle-menu-title">About</span>
					<ul>
						<li><a href="about.php">About</a></li>
					</ul>
				</div>	
				<div class="nav-toggle-menu-content">
					<span class="nav-toggle-menu-title">Contact</span>
					<ul>
						<li><a href="contact.php">Contact</a></li>
					</ul>
				</div>	
			</div>
		</div>

<!-- Nav Menu addtion for search button -->
		<div class="nav-search-menu">
			<div class="row nav-toggle-menu-inner outer-click-hide">
				<div class="nav-toggle-xbutton-div">
					<span class="nav-toggle-xbutton" ><i class="icomo-close" id="close-search-menu"></i></span>
				</div>
				<div class="nav-search-menu-content">
					<div class="nav-search-menu-frame">
						<div class="nav-toggle-menu-title">Search Articles &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 </div>
					</div>	
					<form method="post" name="search-form" class="search-form" onsubmit="return false">					
						<div class="nav-search-menu-field" >
							<input type="text" name="search" placeholder="Search" class="search" id="search-input"/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 <span id='search-comment'>(Presss Enter to seasrch)</span>
						</div>
					</form>
					<div class="search-result">
						<img src="img/loading.gif"/>					
					</div>
				</div>	
			</div>
		</div>	

