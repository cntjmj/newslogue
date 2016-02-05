<?php 
	require_once "../nl-init.php";
	require_once "template/template.php";
	
	$newsID = _get("newsID", 0);
	$ngController = "DebateController";
	$title = "Newslogue Debate";

	htmlBegin($ngController);
	htmlHead($title);
	htmlBodyBegin();
	htmlHeader();
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
		<div ng-repeat="reply in replyList" class="{{reply.replyType=='agree'?'debate-comments':'other_replys_section'}}" id="C{{reply.replyID}}">
			<div class="{{reply.replyType=='agree'?'top_agree_reply':'reply_top'}}">
				<div class="debate_comments_section">
					<div ng-show="reply.replyType=='agree'" class="debate_user-icon"><i class="fa fa-user fa-2x"></i></div>
					<div ng-show="reply.replyType=='agree'" class="debate_username" style="padding-top:5px;">{{reply.displayName}}</div>
					<div ng-show="reply.replyType=='agree'" class="debate_date">{{str2date(reply.replyCreatedDateTime) | date:'dd-MM-yy'}}</div>
					<div ng-show="reply.replyType!='agree'" class="debate_date_replys">{{str2date(reply.replyCreatedDateTime) | date:'dd-MM-yy'}}</div>
					<div ng-show="reply.replyType!='agree'" class="debate_username_replys">{{reply.displayName}}</div>
					<div ng-show="reply.replyType!='agree'" class="debate_user-icon_replys"><i class="fa fa-user fa-2x"></i></div>
				</div>
				<div class="debate_comments_section">
					<div ng-show="reply.replyType=='agree'" class="debate_comment-icon"><i class="fa fa-comment fa"></i></div>
					<div ng-show="reply.replyType=='agree'" class="debate_user_actual_comments"
						ng-click="toggleReplyArea($index)" ng-bind-html="reply.replyContent" 
						style="white-space:{{reply.unfold=='yes'?'normal':'nowrap'}};overflow:hidden;text-overflow:ellipsis">
					</div>
					<div ng-show="reply.replyType!='agree'" class="empty_cls">&nbsp;</div>
					<div ng-show="reply.replyType!='agree'" class="replys_debate_user_actual_comments"
						ng-click="toggleReplyArea($index)" ng-bind-html="reply.replyContent"
						style="white-space:{{reply.unfold=='yes'?'normal':'nowrap'}};overflow:hidden;text-overflow:ellipsis">
					</div>
					<div ng-show="reply.replyType!='agree'" class="replys_debate_comment-icon"><i class="fa fa-comment fa"></i></div>
				</div>
				<div class="debate_comments_section">
					<div ng-show="reply.replyType=='agree'" class="debate_reply_icon"><i class="fa fa-reply"></i></div> 
					<div ng-show="reply.replyType=='agree'" class="debate_reply_count"><a href="javascript:;" ng-click="toggleReplyArea($index)" class="agree-open-comment-box-reply-btn">REPLY {{reply.subReplies.count}}</a></div>
					<div ng-show="reply.replyType=='agree'" class="debate_likes_status"><i ng-click="submitReply('like', reply.replyID)" class="fa fa-2x {{bUserLikeThisReply(reply)?'fa-heart':'fa-heart-o'}}"></i></div>
					<div ng-show="reply.replyType!='agree'" class="empty_cls">&nbsp;</div>
					<div ng-show="reply.replyType!='agree'" class="replys_debate_user_likes_status">
						<i ng-click="submitReply('like', reply.replyID)" class="fa fa-2x {{bUserLikeThisReply(reply)?'fa-heart':'fa-heart-o'}}"></i> 
						<a href="javascript:;" ng-click="toggleReplyArea($index)" class="disagree-open-comment-box-reply-btn">REPLY {{reply.subReplies.count}}</a>
					</div>
					<div ng-show="reply.replyType!='agree'" class="replys_debate_comment-icon"><i class="fa fa-reply"></i></div>
				</div>
			</div>
			<div class="{{reply.replyType=='agree'?'agree-reply-comment-box':'disagree-reply-comment-box reply-comment'}}"
				style="display:{{reply.unfold=='yes'?'block':'none'}};text-align:center">
				<form ng-submit="submitSubreply(reply.replyType, reply.replyID, $index)" class="elegant-aero">
					<div style="font-size: 17px; font-family: 'bebasregular', sans-serif; line-height: 17px; color:{{reply.replyType=='agree'?'#2d2d2d':'#FFFFFF'}};text-align:center"> ADD A REPLY</div>
					<label>
						<textarea id="reply-comment-txtbox" ng-model="subreplyArea[$index]" STYLE="width: 80%; padding: 25px; -webkit-appearance: none; -moz-border-radius: 10px; -webkit-border-radius: 10px; -o-border-radius: 10px; border-radius: 10px; border: solid 3px #2d2d2d; margin-top: 10px; height: 50px;" required name="reply-comment-txtbox" placeholder="YOUR REPLY…"></textarea>
					</label>
					<div>
						<label>
							<input type="submit" value="Send" class="{{reply.replyType=='agree'?'submit_btn_agree-reply-comment':'submit_btn_disagree-reply-comment'}}" />
						</label>
					</div>
				</form>
				<div ng-repeat="sub in reply.subReplies.list" class="cl1" style="margin-top:10px;">
					<div style="padding-top:20px;">
						<div class="debate_comments_section">
							<div class="debate_user-icon"><i class="fa fa-user fa-2x"></i></div>
							<div class="debate_username" style="padding-top:5px;">{{sub.displayName}}</div>
							<div class="debate_date">{{str2date(sub.subreplyCreatedDateTime) | date:'dd-MM-yy'}}</div>
						</div>
						<div class="debate_comments_section">
							<div class="debate_comment-icon"><i class="fa fa-comment fa"></i></div>
							<div class="debate_user_actual_comments" ng-bind-html="sub.replyContent"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
<?php 
	htmlFooter();
?>
	<div id='index_click_blocker' class="click_blocker"></div>
	<script>
		var newsID = <?=$newsID?>;
	</script>
	<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/magnific-popup.js"></script>
<?php 
	htmlBodyEnd();
?>