<?php
	require_once '../nl-init.php';
	require_once 'template/template.php';
	
	htmlBegin("SignupController");
	htmlHead("Test Signup");
	htmlBodyBegin();
	htmlHeader();
?>
	<br><br><br><br>
	<form name="signupform" id="signupform" ng-submit="signup.submitSignupForm()">
		<input type="email" name="emailaddress" id="emailaddress" placeholder="Email Address" ng-model="signup.emailaddress" required><br>
		<input type="text" name="displayName" id="displayName" placeholder="Display Name" ng-model="signup.displayName" required><br>
		<input type="password" name="pwd" id="pwd" placeholder="Password" ng-model="signup.pwd" required><br>
		<input type="password" name="cpwd" id="cpwd" placeholder="Confirm" ng-model="signup.cpwd" required><br>
		<button type="submit">submit</button><br>
		<p style="color:red" ng-show="signup.errMessage!=''">{{signup.errMessage}}</p>
	</form>
	<a href="javascript:;" ng-click="signUpWithFacebook()">sign up with facebook</a>
<?php
	htmlFooter();
	htmlBodyEnd();
?>