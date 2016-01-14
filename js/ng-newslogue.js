	/**
	 * 1. Common Function Area
	 * 
	 * 1.1 Header Functions
	 */
	var getNotificationInfo = function($scope, $http) {
		var urlNotification = CONFIG.GLOBAL_API_BASE+"/user/"+$scope.userID+"/notification";
		$http({method: 'get', url: urlNotification}).success(
			function(data, status, headers, config, statusText){
				if (angular.isDefined(data.notification)) {
					$scope.notification = data.notification;
					updateNotificationCount($scope);
				}
			});
	};
	
	var updateNotificationCount = function($scope) {
		if (angular.isDefined($scope.notification.count)) {
			var num = $scope.notification.count;
			if (num <= 0)
				num = "";
			else if (num > 99)
				num = "(99)";
			else
				num = "("+num+")";
			$("#top_notification_number").text(num);
		}
	};

	var parseAuthInfo = function($scope, $http, data) {
		if (angular.isDefined(data.userID)) {
			$scope.userID = data.userID;
			$scope.displayName = data.displayName;
				
			if ($scope.userID > 0) {
				getNotificationInfo($scope, $http);
				$(".show_after_logon").show();
			} else {
				$(".show_after_logon").hide();
			}
		}
	}
	
	var getAuthInfo = function($scope, $http) {
		$http({method: 'GET', url: CONFIG.GLOBAL_API_BASE+'/auth'}).success(
			function (data, status, headers, config, statusText) {
				parseAuthInfo($scope, $http, data);
			}
		);
	};
	
	var submitAuthInfo = function($scope, $http, postData) {
		var urlAuth = CONFIG.GLOBAL_API_BASE+'/auth';
		
		$http({method: 'post', url: urlAuth, data: $.param(postData)}).success(
				function(data){
					if (angular.isDefined(data.userID)) {
						parseAuthInfo($scope, $http, data);
						$scope.user.password = "";

						$("#login_area").slideUp("fast");
						$("#category_list").css("margin-top","37px");				
					} else {
						var errCode = angular.isDefined(data.errCode)?data.errCode:-1;
						var errMesg = angular.isDefined(data.errMessage)?data.errMessage:"service is temporarily unavailable";
						
						// TODO:
						alert(errCode+" "+errMesg);
					}
				});
	};

	var userLogin = function($scope, $http) {

		if (!$scope.loginform.emailaddress.$valid ||
			!$scope.loginform.password.$valid) {
			//TODO:
			return;
		}
		
		var postData = {
				emailaddress: $scope.user.emailaddress,
				password: $scope.user.password
			};

		submitAuthInfo($scope, $http, postData);
	};
	
	var userLogout = function($scope, $http) {
		var urlAuth = CONFIG.GLOBAL_API_BASE+'/auth';
		$http({method: 'delete', url: urlAuth}).success(
			function(data){
				parseAuthInfo($scope, $http, data);
				$scope.user.password = "";
			});
		$("#cssmenu #menu-button").removeClass("menu-opened");
		$("#cssmenu ul").removeClass("open");
		$("#cssmenu ul").css("display","none");
	};
	
	var setupLoginForm = function($scope, $http) {
		$scope.user = {
			emailaddress: "",
			password: "",
			login: function() {
				return userLogin($scope, $http);
			},
			logout: function() {
				return userLogout($scope, $http);
			}
		};
	};
	
	var setupFaceBook = function($scope, $http) {
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '540497959441744',
				xfbml      : true,
				version    : 'v2.4'
				});
		};

		$scope.loginWithFacebook = function(){
			FB.login(function(response) {
				if (response.authResponse) {
					FB.api('/me', {fields: 'id,name,email'}, function(meresponse) {
						var postData = {
								fbName:		meresponse.name,
								fbEmail:	meresponse.email,
								fbID:		meresponse.id
							};
						submitAuthInfo($scope, $http, postData);
					});
				} else {
					// TODO: error handler
					// TO DEL: var postData = {fbID:"954430261282373",fbName:"Minghua Lu",fbEmail:"minghua.lu@163.com"}
					// TO DEL: submitAuthInfo($scope, $http, postData);
				}
			},{scope: 'email'});
		};
		
		$scope.signUpWithFacebook = function(){
			FB.login(function(response) {
				if (response.authResponse) {
					FB.api('/me', {fields: 'id,name,email'}, function(meresponse) {
						var postData = {
								fbName:		meresponse.name,
								fbEmail:	meresponse.email,
								fbID:		meresponse.id
							};
						signUpUser($scope, $http, postData);
					});
				} else {
					// TODO: error handler
					// TO DEL: var postData = {fbID:"12345",fbName:"Minghua Lu",fbEmail:"minghua.lu@263.com"}
					// TO DEL: signUpUser($scope, $http, postData);
				}
			},{scope: 'email'});
		};

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	};

	var loadCategoryList = function($scope, $http) {
		var urlCategory = CONFIG.GLOBAL_API_BASE+'/category';
		$http({method: 'get', url: urlCategory}).success(
			function(data){
				if (angular.isDefined(data.categoryList)) {
					$scope.categoryList = data.categoryList;
				}
			});
		$scope.selectedCategoryID = selectedCategoryID;
	};
	
	/**
	 * 1.2 Footer Functions
	 */
	
	// if any
	
	/**
	 * 2. Index Page Function Area
	 */
	
	var initLazyLoading = function() {
		//Lazy loading images plugin
		(function() {
			// Initialize
			var bLazy = new Blazy();
		})();
	};
	
	var initMagnificPopup = function() {
		var scrollTop = 0;
		$('#index_main_section').magnificPopup({
			delegate: '.popup-external-iframe',
		    type: 'iframe',
		    callbacks: {
		        beforeOpen: function() {
		        	scrollTop = $(window).scrollTop();
		            $('#container').hide();
		            $('#index_click_blocker').show();
		        },
		        close: function() {
		            $('#container').show();
		            if (scrollTop > 0 && scrollTop != $(window).scrollTop()) {
		            	$(window).scrollTop(scrollTop);
		            }
		            $('#index_click_blocker').hide();
		            initLazyLoading();
		        }
		    }
		});
	};
	
	var loadArticles = function($scope, $http) {
		$scope.ready2scroll = false;
		var param = {
				page_num: $scope.nextPage,
				categoryID: $scope.selectedCategoryID
			};
		$http({method: 'GET', url: CONFIG.GLOBAL_API_BASE+'/news', params: param}).success(
			function (data, status, headers, config, statusText) {
				if (angular.isDefined(data.newsMetaList)) {
					for (i in data.newsMetaList) {
						if (data.newsMetaList[i].newsBannerSource.indexOf("://") < 0)
							data.newsMetaList[i].newsBannerSource = "//"+data.newsMetaList[i].newsBannerSource;
						if (data.newsMetaList[i].newsSource.indexOf("://") < 0)
							data.newsMetaList[i].newsSource = "//"+data.newsMetaList[i].newsSource;
						
						var newsSite = data.newsMetaList[i].newsSource;
						newsSite = newsSite.substr(newsSite.indexOf('//')+2);
						newsSite = newsSite.substr(0, newsSite.indexOf('/'));
						//newsSite = newsSite.substr(newsSite.indexOf('.')+1);
						data.newsMetaList[i].newsSite = newsSite.toUpperCase();
					}
					$.merge($scope.newsMetaList, data.newsMetaList);
					$scope.nextPage++;
					$scope.ready2scroll = true;
				}
			}
		);
	};
	
	/**
	 * 3. Debate Page Function Area
	 */

	var getNews = function($scope, $http, newsID) {
		$http({method: 'GET', url: CONFIG.GLOBAL_API_BASE+'/news/'+newsID}).success(
			function (data, status, headers, config, statusText) {
				if (angular.isDefined(data.news)) {
					if (data.news.newsBannerSource.indexOf("://") < 0)
						data.news.newsBannerSource = "//"+data.news.newsBannerSource;
					if (data.news.newsSource.indexOf("://") < 0)
						data.news.newsSource = "//"+data.news.newsSource;
					$scope.news = data.news;
				}
			}
		);
	};

	var getCommentsByNewsID = function($scope, $http, newsID) {
		$http({method: 'GET', url: CONFIG.GLOBAL_API_BASE+'/news/'+newsID+'/reply'}).success(
			function (data, status, headers, config, statusText) {
				if (angular.isDefined(data.replyList)) {
					$scope.replyList = data.replyList;
				}
			}
		);
	};

	var updateVoteResult = function($scope, data) {
		if (angular.isDefined(data.vote)) {
			var vy = data.vote.agree.count;
			var vn = data.vote.disagree.count;

			if (vy+vn > 0) {
				$scope.percenty = vy * 100 / (vy+vn);
				$scope.percentn = vn * 100 / (vy+vn);
			} else {
				$scope.percenty = 0;
				$scope.percentn = 0;
			}
			
			$scope.vote = data.vote;
		}			
	};

	var getVoteByNewsID = function($scope, $http, newsID) {
		$http({method: 'GET', url: CONFIG.GLOBAL_API_BASE+'/news/'+newsID+'/vote'}).success(
			function (data, status, headers, config, statusText) {
				updateVoteResult($scope, data);
			}
		);
	};

	var submitVote = function($scope, $http, vote) {
		var urlVote = CONFIG.GLOBAL_API_BASE+"/news/"+$scope.news.newsID+"/vote";
		var postData = {voteType: vote};
		$http({	method: 'post', url: urlVote, data: $.param(postData)}).success(
				function(data, status, headers, config, statusText){
					updateVoteResult($scope, data);
				});
	};

	var bUserVoteAgree = function($scope) {
		for (i in $scope.vote.agree.votes) {
			if ($scope.userID == $scope.vote.agree.votes[i].userID) {
				return true;
			}
		}
		
		return false;
	};

	var bUserVoteDisagree = function($scope) {
		for (i in $scope.vote.disagree.votes) {
			if ($scope.userID == $scope.vote.disagree.votes[i].userID)
				return true;
		}
		return false;
	};

	var bUserLikeThisReply = function($scope, reply) {
		if (angular.isDefined(reply.likes)) {
			for (i in reply.likes.list) {
				if ($scope.userID == reply.likes.list[i].userID)
					return true;
			}
		}

		return false;
	};

	var str2date = function(str) {
		return new Date(str);
	};

	var updateReply = function($scope, data) {
		if (angular.isDefined(data.reply) && data.reply.replyID > 0) {
			for (i in $scope.replyList) {
				if ($scope.replyList[i].replyID == data.reply.replyID) {
					$scope.replyList[i] = data.reply;
					return true;
				}
			}

			var replyList = [data.reply];
			$.merge(replyList, $scope.replyList);
			$scope.replyList = replyList;
			
			return true;
		} else if (angular.isDefined(data.errCode) && data.errCode == 0 &&
				angular.isDefined(data.replyID) && data.replyID > 0) {
			for (i in $scope.replyList) {
				if ($scope.replyList[i].replyID == data.replyID) {
					$scope.replyList.splice(i, 1);
					return true;
				}
			}
		}
		return false;
	}

	var submitReply = function($scope, $http, replyType, replyID) {
		$scope.agreeAreaValid = true;
		$scope.disagreeAreaValid = true;
		
		if (replyType == "agree") {
			$scope.agreeAreaValid = $scope.agreeForm.agreeArea.$valid;
			
			if ($scope.agreeForm.$valid) {
				var urlReply = CONFIG.GLOBAL_API_BASE+"/news/"+$scope.news.newsID+"/reply";
				if (replyID > 0)
					urlReply += "/"+replyID;
				var postData = {
						replyType: replyType,
						replyContent: $scope.agreeArea
					};
				$http({method: 'post', url: urlReply, data: $.param(postData)}).success(
						function(data, status, headers, config, statusText) {
							if (updateReply($scope, data))
								$scope.agreeArea = "";
						});
			}
		} else if (replyType == "disagree") {
			$scope.disagreeAreaValid = $scope.disagreeForm.disagreeArea.$valid;
			
			if ($scope.disagreeForm.$valid) {
				var urlReply = CONFIG.GLOBAL_API_BASE+"/news/"+$scope.news.newsID+"/reply";
				if (replyID > 0)
					urlReply += "/"+replyID;
				var postData = {
						replyType: replyType,
						replyContent: $scope.disagreeArea
					};
				$http({method: 'post', url: urlReply, data: $.param(postData)}).success(
						function(data, status, headers, config, statusText) {
							if (updateReply($scope, data))
								$scope.disagreeArea = "";
						});
			}
		} else if (replyType == "like") {
			if (replyID > 0) {
				var urlReply = CONFIG.GLOBAL_API_BASE+"/news/"+$scope.news.newsID+"/reply/"+replyID;
				var postData = {
						replyType: replyType
					};
				$http({method: 'post', url: urlReply, data: $.param(postData)}).success(
						function(data, status, headers, config, statusText) {
							updateReply($scope, data);
						});
			}
		} 
	};

	var submitSubreply = function($scope, $http, replyType, replyID, index) {
		//$scope.subreplyArea[index] = $scope.subreplyArea[index].trim();
		if (!angular.isDefined($scope.subreplyArea[index]) || $scope.subreplyArea[index] == "") {
			$scope.subreplyAreaNotValid[index] = true;
		} else {
			$scope.subreplyAreaNotValid[index] = false;
			var urlReply = CONFIG.GLOBAL_API_BASE+"/news/"+$scope.news.newsID+"/reply/"+replyID;
			var postData = {
					replyType: $scope.replyList[index].replyType,
					replyContent: $scope.subreplyArea[index]
				};
			$http({method: 'post', url: urlReply, data: $.param(postData)}).success(
					function(data, status, headers, config, statusText) {
						if (updateReply($scope, data)) {
							$scope.subreplyArea = [];
							$scope.subreplyAreaNotValid = [];
						}
					});
		}
	};

	var removeReply = function($scope, $http, replyID, subReplyID) {
		var urlReply = CONFIG.GLOBAL_API_BASE+"/news/"+$scope.news.newsID+"/reply/"+replyID;
		if (angular.isDefined(subReplyID) && subReplyID > 0)
			urlReply += "/"+subReplyID;
		
		$http({method: 'delete', url: urlReply}).success(
				function(data, status, headers, config, statusText) {
					//alert(JSON.stringify(data));
					updateReply($scope, data);
				});
	};
	
	/**
	 * 4. Sign Up Function Area
	 */
	var signUpUser = function($scope, $http, postData) {
		var urlUser = CONFIG.GLOBAL_API_BASE+'/user';

		$http({method: 'post', url: urlUser, data: $.param(postData)}).success(
				function(data){
					if (angular.isDefined(data.errCode) && data.errCode == 0) {
						// TODO:
						alert(data.errMessage);
					} else {
						var errCode = angular.isDefined(data.errCode)?data.errCode:-1;
						var errMesg = angular.isDefined(data.errMessage)?data.errMessage:"service is temporarily unavailable";
						
						// TODO:
						$scope.signup.errMessage = errMesg;
					}
				});
	};
	
	var submitSignupForm = function($scope, $http) {
		if (false == $scope.signupform.displayName.$valid ||
			false == $scope.signupform.emailaddress.$valid ||
			false == $scope.signupform.pwd.$valid ||
			false == $scope.signupform.cpwd.$valid) {
			$scope.signup.errMessage = "please fill out all required fields";
		} else if ($scope.signup.pwd != $scope.signup.cpwd) {
			$scope.signup.errMessage = "inconsistant password";
		} else {
			$scope.signup.errMessage = "";
			var postData = {
				emailaddress:	$scope.signup.emailaddress,
				displayName:	$scope.signup.displayName,
				pwd:			$scope.signup.pwd
			};
			signUpUser($scope, $http, postData);
		}
	};

	/**
	 * 50.1 Newslogue Angular Application
	 */

	var nlapp = angular.module("nlapp", ['ngSanitize', 'infinite-scroll']);

	nlapp.config(function($httpProvider) {
	    //Enable cross domain calls
	    $httpProvider.defaults.useXDomain = true;
	    $httpProvider.defaults.withCredentials = true;
	    $httpProvider.defaults.headers.post = {
			    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
		  };
	});
	
	/**
	 * 50.2 Controller for Index Page
	 */
	
	nlapp.controller("IndexController", function($scope, $http){
		/**
		 * setup head, header, login/logout
		 */
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		setupFaceBook($scope, $http);
		
		/**
		 * setup scope models specific to index page
		 */
		loadCategoryList($scope, $http);
		initLazyLoading();
		initMagnificPopup();

		$scope.ready2scroll = false;
		$scope.nextPage = 0;
		$scope.newsMetaList = [];
		$scope.loadmore = function() {
			loadArticles($scope, $http);
		};
		
		loadArticles($scope, $http);
	});
	
	/**
	 * 50.3 Controller for Debate Page
	 */

	nlapp.controller("DebateController", function($scope, $http){

		getAuthInfo($scope, $http);
		getNews($scope, $http, newsID);
		getCommentsByNewsID($scope, $http, newsID);
		getVoteByNewsID($scope, $http, newsID);
		
		$scope.replyList = [];

		$scope.bUserVoteAgree = function() {
			return bUserVoteAgree($scope);
		};
		
		$scope.bUserVoteDisagree = function() {
			return bUserVoteDisagree($scope);
		};
		
		$scope.bUserLikeThisReply = function(reply) {
			return bUserLikeThisReply($scope, reply);
		};
		
		$scope.str2date = str2date;
		
		$scope.submitAgree = function() {
			submitVote($scope, $http, "agree");
		};
		
		$scope.submitDisagree = function() {
			submitVote($scope, $http, "disagree");
		};
		
		$scope.agreeAreaValid = true;
		$scope.disagreeAreaValid = true;

		$scope.submitReply = function(replyType, replyID) {
			return submitReply($scope, $http, replyType, replyID);
		}; 
		
		$scope.subreplyArea = [];
		$scope.subreplyAreaNotValid = [];
		
		$scope.submitSubreply = function(replyType, replyID, index) {
			return submitSubreply($scope, $http, replyType, replyID, index);
		};
		
		$scope.removeReply = function(replyID, subReplyID) {
			return removeReply($scope, $http, replyID, subReplyID);
		};
	});
	
	/**
	 * 50.4 Controller for Sign Up Page
	 */
	nlapp.controller("SignupController", function($scope, $http){
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		setupFaceBook($scope, $http);

		$scope.signup = {
				displayName: "",
				emailaddress: "",
				pwd: "",
				cpwd: "",
				errMessage: "",
				submitSignupForm: function() {
					submitSignupForm($scope, $http);
				}
		};
	});