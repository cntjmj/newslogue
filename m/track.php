<?php
	require_once '../nl-init.php';
	require_once '../class/nl-user-class.php';
	require_once '../class/nl-auth-class.php';
	require_once 'template/template.php';

	$auth = Auth::getInstance();
	$userID = $auth->getUserID();

	if ($userID <= 0)
		header("location: ".CONFIG_PATH::GLOBAL_M_BASE);

	htmlBegin("TrackController");
	htmlHead("Debate Tracking");
	htmlBodyBegin();
	htmlHeader();
?>
	<main>
		<section>
			<div class="debate_head_main" style="margin-bottom:20px;">
				<div class="debate_heading"><span class="debate_title">DEBATE TRACKING AREA</span></div>
			</div>  
		</section>
		<section ng-repeat="news in track.newsList" style="padding-top: 0px">
			<div class="usernotify read_notify">
				<div class="debate_tracking_container">
				    <a href="javascript:;" ng-click="news.showComment=!news.showComment">
					<div class="debate_tracking_txt" ng-bind-html="news.newsTitle"></div>
					<div class="notificationdate" style="padding-right:0px;">
						<div class="debate_tracking_replys_count"><!--30 Replys - 15 ><i class="fa fa-heart"></i--></div>
						<div class="debate_tracking_date">{{track.str2date(news.newsCreatedDateTime) | date:'dd-MM-yy'}}</div>
					</div>
					</a>
					<div class="debate_comments_txt" ng-repeat="comment in news.replyList" ng-show="news.showComment==true">
						<div class="debate_comments_txt_main" ng-show="comment.replyID>0">
							<div>
								<a href="/debate/{{news.newsID}}#C{{comment.replyID}}" target="_blank">
									<div class="debate_comments_left_txt">
										<span class="debate_comments_txt_style">Comment: </span>{{comment.replyContent}}
									</div>
								</a>
								<div class="debate_remove">
									<a href="javascript:;" ng-click="track.removeReply(news.newsID, comment.replyID, 0)"><i class="fa fa-times fa-1x"></i></a>
								</div>
							</div>
							<div class="debate_comments_dt">{{track.str2date(comment.replyCreatedDateTime)|date:'dd-MM-yy'}}</div>
						</div>

						<div class="debate_reply_txt" ng-repeat="like in comment.likes.list">
							<div>
								<a href="/debate/{{news.newsID}}#C{{like.parentReplyID}}" target="_blank">
									<div class="debate_comments_left_txt">
										<span style="color:#EAEAB6;">Like: </span><i class="fa fa-heart"></i>
									</div>
								</a>
								<div class="debate_remove">
									<a href="javascript:;"  ng-click="track.removeReply(news.newsID, comment.replyID, like.replyID)"><i class="fa fa-times fa-1x"></i></a>
								</div>
							</div>
							<div class="debate_reply_dt">{{track.str2date(like.subreplyCreatedDateTime)|date:'dd-MM-yy'}}</div>
						</div>

						<div class="debate_reply_txt" ng-repeat="subReply in comment.subReplies.list">
							<div>
								<a href="/debate/{{news.newsID}}#C{{subReply.parentReplyID}}" target="_blank">
									<div class="debate_comments_left_txt">
										<span style="color:#EAEAB6;">Reply: </span>{{subReply.replyContent}}
									</div>
								</a>
								<div class="debate_remove">
									<a href="javascript:;" ng-click="track.removeReply(news.newsID, comment.replyID, subReply.replyID)"><i class="fa fa-times fa-1x"></i></a>
								</div>
							</div>
							<div class="debate_reply_dt">{{track.str2date(subReply.subreplyCreatedDateTime)|date:'dd-MM-yy'}}</div>
						</div>

						<!--
						<div class="debate_reply_txt" ng-repeat="like in comment.likes.list">
							<a href="/debate/{{news.newsID}}#C{{like.parentReplyID}}" target="_blank">
								<div><span style="color:#EAEAB6;">Like: </span><i class="fa fa-heart"></i></div>
							</a>
							<div class="debate_reply_dt">{{track.str2date(like.subreplyCreatedDateTime)|date:'dd-MM-yy'}}</div>
						</div>
						<div class="debate_reply_txt" ng-repeat="subReply in comment.subReplies.list">
							<a href="/debate/{{news.newsID}}#C{{subReply.parentReplyID}}" target="_blank">
								<div><span style="color:#EAEAB6;">Reply: </span>{{subReply.replyContent}}</div>
							</a>
							<div class="debate_reply_dt">{{track.str2date(subReply.subreplyCreatedDateTime)|date:'dd-MM-yy'}}</div>
						</div>
						-->
					</div>
				</div>
			</div>
		</section>
	</main>
	<script>var userID=<?=$userID?>;</script>
<?php
	htmlFooter();
	htmlBodyEnd();
?>