<?php 
	require_once "../nl-init.php";
	require_once "template/template.php";
	
	$userID = _get("userID", 0);
	$ngController = "NotificationController";
	$title = "Newslogue Notification";

	htmlBegin($ngController);
	htmlHead($title);
	htmlBodyBegin();
?>
	<div id="container">
<?php
	htmlHeader();
?>
	<main>
		<section>
			<div class="debate_head_main" style="margin-bottom:20px;">
				<div class="debate_heading"><span class="debate_title">NOTIFICATION AREA</span></div>
			</div>  
		</section>
		<section>
    		<div ng-repeat="notification in notification['list']" style="border: 1px solid black">
    		  <a ng-click="readNotification(notification.replyID, notification.readflag)" 
    		  	href="/debate/{{notification.newsID}}/#c{{notification.parentReplyID}}">
      			<div class="{{notification.readflag==0 ? 'usernotify not_read_notify' : 'usernotify read_notify'}}">
      				<div class="notifytxt">
      					<span><i class="{{notification.replyType=='like' ? 'fa fa-heart' : 'fa fa-reply'}}"></i></span>
      					<span class="userdetail">{{notification.displayName}}</span> 
      					{{notification.replyType=='like' ? 'likes' : 'replied'}} your comment about <i>{{notification.newsTitle}}</i>
      				</div>
      				<div class="notificationdate">{{str2date(notification.createdDateTime) | date:'dd/MM/yy'}}</div>
      			</div>
      		  </a>
    		</div>
  		</section>
	</main>
<?php 
	htmlFooter();
?>
	</div>
	<div id='index_click_blocker' class="click_blocker"></div>
	<script>
		var userID = <?=$userID?>;
	</script>
	<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/magnific-popup.js"></script>
<?php 
	htmlBodyEnd();
?>