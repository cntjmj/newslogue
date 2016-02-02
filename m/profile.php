<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once 'template/template.php';

	$auth = Auth::getInstance();
	$userID = $auth->getUserID();
	
	if ($userID <= 0)
		header("location: ".CONFIG_PATH::GLOBAL_M_BASE);

	htmlBegin("ProfileController");
	htmlHead("User Profile");
	htmlBodyBegin();
	htmlHeader();
?>
	<main>
		<section>
			<div class="debate_head_main" style="margin-bottom:20px;">
				<div class="debate_heading"><span class="debate_title">PROFILE</span></div>
			</div>  
		</section>
		<section id="user_register">
			<form class="form" id="profileform" name="profileform" ng-submit="profile.submitProfileForm()">
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
					<span ng-show="profile.editing==0" class="user_label">{{profile.displayName}}</span>
					<input ng-hide="profile.editing==0" name="displayName" type="text" ng-model="profile.displayName" required class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="YOUR DISPLAY NAME" id="displayName" />
				</div>
				<!--div>
					<span class="user_label">* SURNAME:</span>
					<input type="text" name="surname" required class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="YOUR SURNAME" id="surname">
				</div-->
				<div>
					<span class="user_label">FULL NAME:</span>        
					<span ng-show="profile.editing==0" class="user_label">{{profile.fullname}}</span>
					<input ng-hide="profile.editing==0" type="text" name="fullname" ng-model="profile.fullname" required class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="YOUR FULL NAME" id="fullname">
				</div>
				<!--div>
					<span class="user_label">PASSWORD:</span>
					<input type="password" name="pwd" ng-model="signup.pwd" required id="pwd" placeholder="PASSWORD">
				</div>
				<div>
					<span class="user_label">CONFIRM PASSWORD:</span>
					<input type="password" name="cpwd" required id="cpwd" ng-model="signup.cpwd" placeholder="CONFIRM PASSWORD">
				</div-->
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
				<div class="errTxt" ng-show="profile.errMessage!=''">{{profile.errMessage}}</div>
				<div class="succTxt" ng-show="profile.succMessage!=''">{{profile.succMessage}}</div>
				<div class="txt_submit" ng-hide="profile.editing==0">
					<input type="submit" value="SAVE" id="submit_btn_blue" ng-show="profile.editing" {{profile.submitting?"disabled":""}}/><br/>
				</div>
			</form>
			<!--a href="javascript:;" ng-click="signUpWithFacebook()">sign up with facebook</a-->
		</section>
		<section id="user_edit_section"> 		
			<div><a href="javascript:;" ng-click="profile.editing=1">EDIT YOUR PROFILE</a><br/></div>
			<div><a href="javascript:;">CHANGE YOUR PASSWORD</a><br/></div>
			<div><a href="javascript:;">DEBATE TRACKING PAGE</a><br/></div>
		</section>
		<!--section id="question_section"> 		
			<div class="que_heading">ANY QUESTION or suggestion?</div>
			<div class="que_second_heading">do not hesitate to write us</div>
			<form name="question-form">
				<textarea id="add_a_reply_message" required="" name="message" placeholder="WRITE YOUR QUESTION HERE…"></textarea>
				<div><input type="submit" value="Send" id="question_submit_btn"></div>
			</form>
		</section-->
	</main>
	<script>var userID=<?=$userID?>;</script>
<?php
	htmlFooter();
	htmlBodyEnd();
?>