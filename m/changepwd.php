<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once 'template/template.php';

	$auth = Auth::getInstance();
	$userID = $auth->getUserID();

	htmlBegin("ChangepwdController");
	htmlHead("Forgot password");
	htmlBodyBegin();
	htmlHeader();
?>

<main>
	<section>
		<div class="debate_head_main" style="margin-bottom:20px;">
			<div class="debate_heading"><span class="debate_title"> Reset Password </span></div>
		</div>  
	</section>

	<section id="user_register">
		<form class="form" id="changepwdform" name="changepwdform" ng-submit="changepwd.pwdChangeForm()">

			<div style="<?php echo ($userID <= 0 ? "display: none;" : "") ?>" >
				<span class="user_label">CURRENT PASSWORD</span>
				<input type="password" name="currpwd" ng-model="changepwd.currpwd" required id="currpwd" placeholder="CURRENT PASSWORD" <?php echo ($userID <= 0 ? "disabled": "") ?> >
			</div>

			<div>
				<span class="user_label">NEW PASSWORD</span>
				<input type="password" name="password" ng-model="changepwd.password" required id="password" placeholder="NEW PASSWORD">
			</div>
				<!--
				<input type="checkbox" id="checkbox" ng-model="passwordCheckbox" ng-click="hideShowPassword()" />
				<label for="checkbox" ng-if="passwordCheckbox">Hide</label>
  				<label for="checkbox" ng-if="!passwordCheckbox">Show</label> 
  				-->
  			<div>
				<span class="user_label">CONFIRM PASSWORD:</span>
				<input type="password" name="cpwd" required id="cpwd" ng-model="changepwd.cpwd" placeholder="CONFIRM PASSWORD">
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
	<script>
		var userID=<?=$userID?>;
	</script>
<?php
	htmlFooter();
	htmlBodyEnd();
?>