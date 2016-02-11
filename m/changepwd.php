<?php
	require_once '../nl-init.php';
	require_once 'template/template.php';

	htmlBegin("ChangepwdController");
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
		<form class="form" id="changepwdform" name="changepwdform" ng-submit="changepwd.pwdChangeForm()">

			<div id="newpwd">
				<span class="user_label">NEW PASSWORD</span>
				<input type="password" name="password" ng-model="changepwd.password" required id="password" placeholder="NEW PASSWORD">
			</div>

			<div class="errTxt" ng-show="changepwd.errMessage!=''">{{changepwd.errMessage}}</div>
			<div class="succTxt" ng-show="changepwd.succMessage!=''">{{changepwd.succMessage}}</div>
			<div class="txt_submit">
				<input type="submit" value="continue" id="submit_btn_blue" ng-hide="changepwd.succMessage!=''" {{changepwd.submitting?"disabled":""}}/><br/>
				<input type="button" value="HOME" id="submit_btn_blue" ng-show="changepwd.succMessage!=''" ng-click="goHome()"/><br/>
			</div>
		</form>
	</section>
</main>
<?php
	htmlFooter();
	htmlBodyEnd();
?>