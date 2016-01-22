<?php 
	require_once "../nl-init.php";
	require_once "template/template.php";
	
	$newsID = _get("newsID", 0);
	$ngController = "DebateController";
	$title = "Newslogue Debate";

	htmlBegin($ngController);
	htmlHead($title);
	htmlBodyBegin();
?>
	<div id="container">
<?php
	htmlHeader();
	//htmlNav();
?>
	<main>
		<section>
			<div class="debate_head_main">
				<div class="debate_heading"><span class="debate_title">DEBATE</span></div>
			</div>
			<div class="ind_question content" style="margin-top: 17px;margin-bottom: 11px;">
				<h1> {{news.newsQuestion}} </h1>
			</div>
			<div class="read_article ind_debate" style="margin-bottom:-7px;">
				<a class="popup-external-iframe" href="{{news.newsSource}}">READ THE ARTICLE</a>
			</div>
			<div class="nl-singlearticle-resultsvote" style="text-align:center;width:100%; padding-top:27px;padding-bottom:27px;">		 
				<div class="agree-vote" style="float: left; text-align: right; width: 50%;">
					<p class="agree" style="padding-right:10px;">
						<span>AGREE</span><span>{{percenty | number:0}}%</span> <i class="fa fa-plus-circle fa-2x"></i>
					</p>
				</div>
				<div class="disagree-vote" style="width: 50%; float: left; text-align: left;">
					<p class="disagree">
						<i class="fa fa-minus-circle fa-2x"></i> <span>{{percentn | number:0}}%</span><span>DISAGREE</span>
					</p>
				</div>
			</div>
			<div class="nl-singlearticle-resultsvote-btns">				 
				<div class="agree-btn" style="background-color:{{bUserVoteDisagree()?'#eeeeee':'#66d48d'}}">
					<a href="javascript:;" id="nl-singlearticle-agree-btn" ng-click="submitAgree()">AGREE</a>
				</div>
				<div class="disagree-btn" style="background-color:{{bUserVoteAgree()?'#eeeeee':'#6b8593'}};">
					<a href="javascript:;" id="nl-singlearticle-disagree-btn" ng-click="submitDisagree()">DISAGREE</a>
				</div>
			</div>
			<div class="nl-singlearticle-commentarea-agree" ng-show="bUserVoteAgree()">		
				<form name="agreeForm" id="agreeForm" ng-submit="submitReply('agree')" class="elegant-aero">
					<span style="font-size: 17px; font-family: 'bebasregular', sans-serif; line-height: 17px; color:#FFFFFF;">
						Why Agree? Write your opinion:
					</span>
					<label>
						<textarea name="agreeArea" id="agreeArea" ng-model="agreeArea"  required placeholder="WRITE YOUR COMMENT HERE…"></textarea>
					</label> 
					<div>
						<label>
							<input type="submit" value="Send" id="submit_btn_blue_agree" />
						</label>
					</div>   
				</form>
			</div>
			<div class="nl-singlearticle-commentarea-disagree" ng-show="bUserVoteDisagree()" style="background-color:rgb(107, 133, 147);">		
				<form name="disagreeForm" id="disagreeForm" ng-submit="submitReply('disagree')" class="elegant-aero">
					<span style="font-size: 17px; font-family: 'bebasregular', sans-serif; line-height: 17px; color:#FFFFFF;">
						Why Disagree? Write your opinion:
					</span>
					<label>
						<textarea name="disagreeArea" id="disagreeArea" ng-model="disagreeArea" required placeholder="WRITE YOUR COMMENT HERE…"></textarea>
					</label> 
					<div>
						<label>
							<input type="submit" value="Send" id="submit_btn_blue_disagree" />
						</label>
					</div>    
				</form>
			</div>
			<div id="debate_area">DEBATE</div>
		</section>
	</main>
<?php 
	htmlFooter();
?>
	</div>
	<div id='index_click_blocker' class="click_blocker"></div>
	<script>
		var newsID = <?=$newsID?>;
	</script>
	<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/magnific-popup.js"></script>
<?php 
	htmlBodyEnd();
?>