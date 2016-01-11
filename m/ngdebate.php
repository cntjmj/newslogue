<?php 
	require_once "../nl-init.php";
	
	$newsID = _get("newsID", 0);
	
	if ($newsID == 0) $newsID = 272;
?>
<!DOCTYPE html>
<html ng-app="nlapp">
<head>
<meta charset="UTF-8">
<title>AngularJS Debate Test</title>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/jquery.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/angular.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/angular-sanitize.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/ng-infinite-scroll.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/nl-common.js"></script>
</head>
<body ng-controller="DebateController">
  <section>
    <div>newsID: {{news.newsID}}</div>
    <div>{{str2date(news.nacreatedDateTime)|date:'dd-MM-yy'}}</div>
    <div ng-show="notification.count>0">new notifications: {{notification.count}}</div>
    <div style="float:right">{{userID}}</div>
    <div style="float:right">{{displayName}}</div>
    <div>Title: {{news.newsTitle}}</div>
    <div><img src="{{news.newsBannerSource}}"></div>
    <div>Question: {{news.newsQuestion}}</div>
    <div>
      <div> {{percenty | number:0}}% agree</div>
      <div style="float:right"> {{percentn | number:0}}% disagree</div>
    </div>
    <div>
      <button name="voteAgree" id="voteAgree" ng-click="submitAgree()">agree {{bUserVoteDisagree()?"disabled":""}}</button>
      <button name="voteDisagree" id="voteDisagree" ng-click="submitDisagree()">disagree {{bUserVoteAgree()?"disabled":""}}</button>
    </div>
    <div ng-show="bUserVoteAgree()">
      <form name="agreeForm" id="agreeForm" ng-submit="submitReply('agree')" novalidate>
      <p>tell us why yes</p>
      <textarea name="agreeArea" id="agreeArea" ng-model="agreeArea" required></textarea>
      <p style="color:red" ng-show="!agreeAreaValid">please input something here</p>
      <button type="submit">submit yes</button>
      </form>
    </div>
    <div ng-show="bUserVoteDisagree()">
      <form name="disagreeForm" id="disagreeForm" ng-submit="submitReply('disagree')" novalidate>
      <p>tell us why no</p>
      <textarea name="disagreeArea" id="disagreeArea" ng-model="disagreeArea" required></textarea>
      <p style="color:red" ng-show="!disagreeAreaValid">please input something here</p>
      <button type="submit">submit no</button>
      </form>
    </div>
    <div ng-repeat="reply in replyList" style="border: 1px solid black">
      <div>{{reply.replyID}}</div>
      <div>{{reply.replyType}}</div>
      <div>{{reply.displayName}}</div>
      <div>{{str2date(reply.replyCreatedDateTime) | date:'dd-MM-yy'}}</div>
      <!--div>{{reply.replyContent}}</div-->
      <div ng-bind-html="reply.replyContent"></div>
      <div>Reply {{reply.subReplies.count}}</div>
      <div><a href="javascript:;" ng-click="submitReply('like', reply.replyID)">{{bUserLikeThisReply(reply)?"LIKE":"like"}}</a> {{reply.likes.count}}</div>
      <div ng-show="reply.userID==userID"><a href="javascript:;" ng-click="removeReply(reply.replyID)">remove</a></div>
      <div><form ng-submit="submitSubreply(reply.replyType, reply.replyID, $index)" novalidate>
      <textarea ng-model="subreplyArea[$index]" required></textarea>
      <p style="color:red" ng-show="subreplyAreaNotValid[$index]">please input something here</p>
      <button type="submit">submit subreply</button>
      </form></div>
      <div ng-repeat="sub in reply.subReplies.list" style="border: 1px solid blue">
        <div>{{sub.replyID}}</div>
        <div>{{sub.displayName}}</div>
        <div>{{str2date(sub.subreplyCreatedDateTime) | date:'dd-MM-yy'}}</div>
        <div ng-bind-html="sub.replyContent"></div>
        <div ng-show="sub.userID==userID"><a href="javascript:;" ng-click="removeReply(reply.replyID, sub.replyID)">remove</a></div>
      </div>
    </div>
  </section>
  <script>
	var newsID = <?=$newsID?>;
  </script>
  <script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/ng-newslogue.js"></script>
</body>
</html>