<?php
	require_once __DIR__."/../../nl-config.php";
	
	function htmlHeader() {
?>
	<div id="container">
	<header>
		<div id='cssmenu'>
			<ul>
				<!--li><a href='javascript:;'>Articles</a></li-->
				<li><a href='/about'>About us</a></li>
				<li><a href='/contact'>Contact</a></li>
				<!--li ng-hide="userID>0"><a href='javascript:;' class="login_user">Login</a></li-->
				<li ng-show="userID>0" ng-click="user.logout()"><a href='javascript:;'>Logout</a></li>
			</ul>
		</div>
		<section>
			<div id="login_area" class="ind_question" ng-hide="userID>0">
				<form class="form" name="loginform" id="loginform" >
					<div class="txt_username"><i class="fa fa-user fa-1x"></i>
						<input name="emailaddress" type="email" required ng-model="user.emailaddress"
						 class="feedback-input" placeholder="email address" id="emailaddress" />
					</div>
					<div class="txt_password"><i style="color:#FFFFFF;float:left;margin-left:15px;padding-top:2px;" class="fa fa-lock fa-1x"></i>
						<input type="password" name="password" required ng-model="user.password"
						 class="feedback-input" id="password" placeholder="********">
					</div>
					<div class="login">
						<input type="submit" value="LOGIN" id="submit_btn_blue" ng-click="user.login()" /><br/>
						<a href="/recovery">FORGOT PASSWORD?</a><br/>
						<!--a href="javascript:;">FORGOT USERNAME?</a-->
					</div>
				</form>
				<div id="login_others">
					<p>OR <a href="/registration">SIGN UP</a> - <a id="login_with_facebook" href="javascript:;" ng-click="loginWithFacebook()">LOGIN WITH FACEBOOK</a></p>
				</div>
			</div>
		</section>
	</header>
<?php
	}
?>