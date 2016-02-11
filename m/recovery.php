<?php
	require_once '../nl-init.php';
	require_once 'template/template.php';

	htmlBegin("ForgetpwdController");
	htmlHead("Forgot password");
	htmlBodyBegin();
	htmlHeader();
?>

<main>
	<section>
		<div class="debate_head_main" style="margin-bottom:20px;">
			<div class="debate_heading"><span class="debate_title">Forgot Password</span></div>
		</div>  
	</section>

	<section id="user_register">
		<form class="form" id="forgetpwdform" name="forgetpwdform" ng-submit="forgetpwd.pwdRecovaryForm()">
			
			<div id="email">
				<span class="user_label">EMAIL ADDRESS</span>        
				<input type="email" name="emailaddress" ng-model="forgetpwd.emailaddress" 
					required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" class="feedback-input" 
					placeholder="YOUR EMAIL ADDRESS" id="emailaddress">
			</div>

			<div class="errTxt" ng-show="forgetpwd.errMessage!=''">{{forgetpwd.errMessage}}</div>
			<div class="succTxt" ng-show="forgetpwd.succMessage!=''">{{forgetpwd.succMessage}}</div>
			<div class="txt_submit">
				<input type="submit" value="continue" id="submit_btn_blue" ng-hide="forgetpwd.succMessage!=''" {{forgetpwd.submitting?"disabled":""}}/><br/>
			</div>
		</form>
	</section>
</main>

<?php
	htmlFooter();
	htmlBodyEnd();
?>