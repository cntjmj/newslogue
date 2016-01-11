<?php 
	require_once "../nl-init.php";
?>
<!DOCTYPE html>
<html ng-app="nlapp">
<head>
<meta charset="UTF-8">
<title>AngularJS Test</title>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/jquery.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/angular.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/angular-sanitize.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/ng-infinite-scroll.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/nl-common.js"></script>
</head>
<body ng-controller="IndexController">
  <div ng-hide="userID>0">
    <form id="loginform">
      <p><label>email address:</label><input type="email" id="emailaddress" ng-model="user.emailaddress" required></p>
      <p><label>password:</label><input type="password" id="password" ng-model="user.password" required></p>
      <p><button ng-click="user.login()">submit</button></p>
    </form>
  </div>
  <div ng-show="userID>0">
    <p>hi {{displayName}} <a href="javascript:;" ng-click="user.logout()">logout</a></p>
  </div>
  <div ng-show="notification.count>0">new notifications: {{notification.count}}</div>
  <section infinite-scroll='loadmore()' infinite-scroll-disabled='!ready2scroll'>
    <div style="position:fixed;top:20px;right:20px">
      <p>{{"Total "+newsMetaList.length+" items"}}</p>
    </div>
    <div ng-repeat="news in newsMetaList" style="height:200px">
      <p><a href="ngdebate.php?newsID={{news.newsID}}">{{news.newsID}}</a></p>
    </div>
  </section>
  <script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/ng-newslogue.js"></script>
</body>
</html>