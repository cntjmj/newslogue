<?php
	require_once '../nl-init.php';
	require_once 'template/template.php';

	htmlBegin("SignupController");
	htmlHead("User Registration");
	htmlBodyBegin();
	htmlHeader();
?>
	<main>
		<section>
			<div class="debate_head_main" style="margin-bottom:20px;">
				<div class="debate_heading"><span class="debate_title">REGISTER</span></div>
			</div>  
		</section>
		<section id="user_register">
			<form class="form" id="signupform" name="signupform" ng-submit="signup.submitSignupForm()">
				<!--div id="profile_img_section">
					<div id="profile_photo"><img src="images/article4.jpg" class="profile_circular" alt="Profile Photo"></div>
					<div id="add_profile_photo">
						<span>ADD YOUR PHOTO:<br/><br/></span>
						<a href="javascript:;" id="upload_link">UPLOAD YOUR FILE</a>
						<input class="uploadbtn" type="file" name="datafile" size="40">
					</div>
				</div-->
				<div>
					<span class="user_label">DISPLAY NAME:</span>
					<input name="displayName" type="text" ng-model="signup.displayName" required class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="YOUR DISPLAY NAME" id="displayName" />
				</div>
				<!--div>
					<span class="user_label">* SURNAME:</span>
					<input type="text" name="surname" required class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="YOUR SURNAME" id="surname">
				</div-->
				<div>
					<span class="user_label">EMAIL ADDRESS:</span>        
					<input type="email" name="emailaddress" ng-model="signup.emailaddress" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" class="feedback-input" placeholder="YOUR EMAIL ADDRESS" id="emailaddress">
				</div>
				<div>
					<span class="user_label">PASSWORD:</span>
					<input type="password" name="pwd" ng-model="signup.pwd" required id="pwd" placeholder="PASSWORD">
				</div>
				<div>
					<span class="user_label">CONFIRM PASSWORD:</span>
					<input type="password" name="cpwd" required id="cpwd" ng-model="signup.cpwd" placeholder="CONFIRM PASSWORD">
				</div>
				<!--div>
					<span class="user_label">* AGE:</span>
					<input type="text" name="age" required pattern="[0-9]*" id="age" placeholder="YOUR AGE">
				</div>
				<div class="txt_gender">
					<span class="user_label">* GENDER:</span>
					<div>
						<span class="user_label">MALE <input type="radio" name="gender" required></span>
						<span class="user_label">FEMALE <input type="radio" name="gender" required></span>
					</div>
				</div>
				<div>
					<span class="user_label">INTEREST:</span>
					<input type="text" name="interest" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" id="interest" placeholder="YOUR INTEREST">
				</div>
				<div>
					<span class="user_label">TOWN:</span>
					<input type="text" name="town" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" id="town" placeholder="YOUR TOWN">
				</div-->
				<div class="errTxt" ng-show="signup.errMessage!=''">{{signup.errMessage}}</div>
				<div class="succTxt" ng-show="signup.succMessage!=''">{{signup.succMessage}}</div>
				<div class="txt_submit">
					<input type="submit" value="REGISTER" id="submit_btn_blue" ng-hide="signup.succMessage!=''" {{signup.submitting?"disabled":""}}/><br/>
					<input type="button" value="HOME" id="submit_btn_blue" ng-show="signup.succMessage!=''" ng-click="goHome()"/><br/>
				</div>
			</form>
			<!--a href="javascript:;" ng-click="signUpWithFacebook()">sign up with facebook</a-->
		</section>
	</main>
<?php
	htmlFooter();
	htmlBodyEnd();
?>