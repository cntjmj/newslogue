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

	/**
	 * name: resetReadflag
	 * function: reset the value of notification readflag in table 'news_reply'
	 */
	var resetReadflag = function($scope, $http, replyID) {
		var postData = {
				ReplyID: replyID,
				//UserID:	userID
				//ReplyID: 308,
				//UserID: -46282339
			};
			
		var urlNotification = CONFIG.GLOBAL_API_BASE+"/user/"+$scope.userID+"/notification";

		$http({	method: 'post', url: urlNotification, data: $.param(postData)}).success(
			function(data, status, headers, config, statusText){;
				getNotificationInfo($scope, $http);
			});
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
				//$scope.user.password = "";
				window.location.href="/";
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
					// TO DEL: var postData = {fbID:"12345",fbName:"FB User",fbEmail:"fbuser@12345.com"}
					// TO DEL: submitAuthInfo($scope, $http, postData);
				}
			},{scope: 'email'});
		};
		
		$scope.signUpWithFacebook = function(){
			$scope.signup.errMessage = "";
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
					// TO DEL: var postData = {fbID:"12345",fbName:"FB User",fbEmail:"fbuser@12345.com"}
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
	
	var goHome = function() {
		window.location.href = '/';
	}
	
	/**
	 * 1.2 Footer Functions
	 */
	
	// if any
	
	/**
	 * 2. Index Page Function Area
	 */
	
	var initLazyLoading = function() {
		if (typeof(Blazy) != "function")
			return;

		//Lazy loading images plugin
		(function() {
			// Initialize
			var bLazy = new Blazy();
		})();
	};
	
	var initMagnificPopup = function() {
		var scrollTop = 0;
		//$('#index_main_section').magnificPopup({
		$('main').magnificPopup({
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
				categoryID: $scope.selectedCategoryID,
				newsStatus: newsStatus
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
		if (angular.isDefined($scope.vote))
		for (i in $scope.vote.agree.votes) {
			if ($scope.userID == $scope.vote.agree.votes[i].userID) {
				return true;
			}
		}
		
		return false;
	};

	var bUserVoteDisagree = function($scope) {
		if (angular.isDefined($scope.vote))
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
					data.reply.unfold = $scope.replyList[i].unfold;
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
	
	var toggleReplyArea = function($scope, $http, index) {
		if ($scope.replyList[index].unfold != "yes")
			$scope.replyList[index].unfold = "yes";
		else
			$scope.replyList[index].unfold = "no";
	};

	var setupScroll2Reply = function($scope, $http) {
		var anchor = '#C' + document.location.href.split("#")[1];
		var scroll2Reply = function() {
			if ($($scope.anchor).length > 0) {
				$('body').scrollTop($($scope.anchor).offset().top);
				clearInterval($scope.interval);
			}
		};

		if (anchor != '#C') {
			$scope.anchor = anchor;
			$scope.interval = setInterval(scroll2Reply, 1000);
		}
	};
	/**
	 * 4. Sign Up Function Area
	 */
	var signUpUser = function($scope, $http, postData) {
		var urlUser = CONFIG.GLOBAL_API_BASE+'/user';

		$http({method: 'post', url: urlUser, data: $.param(postData)}).success(
				function(data){
					if (angular.isDefined(data.errCode) && data.errCode == 0) {
						$scope.signup.succMessage = data.errMessage;
					} else {
						var errCode = angular.isDefined(data.errCode)?data.errCode:-1;
						var errMesg = angular.isDefined(data.errMessage)?data.errMessage:"service is temporarily unavailable";

						$scope.signup.errMessage = errMesg;
					}
					$scope.signup.submitting = 0;
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
			$scope.signup.submitting = 1;
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
	 * 5. Profile Function Area
	 */
	var loadProfile = function($scope, $http) {
		var urlUser = CONFIG.GLOBAL_API_BASE+'/user/'+userID;
		$http({method: 'get', url: urlUser}).success(function(data) {
			if (angular.isDefined(data.user)) {
				$scope.profile.displayName = data.user.displayName;
				$scope.profile.fullname = data.user.fullname;
				if ($scope.profile.fullname == "")
					$scope.profile.fullname = $scope.profile.displayName;
			}
		});
	};
		
		
	var submitProfileForm = function($scope, $http) {
		$scope.profile.errMessage = '';
		$scope.profile.succMessage = '';

		if (false == $scope.profileform.displayName.$valid ||
			false == $scope.profile.fullname.$valid) {
			$scope.profile.errMessage = "please fill out all required fields";
		} else {
			$scope.profile.submitting = 1;
			var urlUser = CONFIG.GLOBAL_API_BASE+'/user/'+userID;
			var userData = {
				displayName: $scope.profile.displayName,
				fullname: $scope.profile.fullname
			};

			$http({method: 'put', url: urlUser, data: userData}).success(function(data) {
				if (angular.isDefined(data.errCode)) {
					if (data.errCode == 0) {
						$scope.profile.succMessage = "user profile updated";
						$scope.profile.editing = 0;
					} else {
						$scope.profile.errMessage = data.errMessage;
					}
				} else {
					$scope.profile.errMessage = "service not available";
				}
				$scope.profile.submitting = 0;
			});
		}
	};
	
	/**
	 * 6. Debate Tracking Functions
	 */
	var loadUserDebate = function($scope, $http, userID) {
		var urlUserDebate = CONFIG.GLOBAL_API_BASE+'/user/'+userID+"/reply";
		$http({method: 'get', url: urlUserDebate}).success(function(data){
			if (angular.isDefined(data.newsList)) {
				for (var i in $scope.track.newsList) {
					if ($scope.track.newsList[i].showComment != true)
						continue;
					
					for (var j in data.newsList) {
						if ($scope.track.newsList[i].newsID == data.newsList[j].newsID) {
							data.newsList[j].showComment = true;
							break;
						}
					}
				}
				$scope.track.newsList = data.newsList;
			} else {
				// TODO: error handling
			}
		});
	}

	//forgot pwd: step one, send recovary email
	var pwdRecovaryForm = function($scope, $http) {
		if ($scope.forgetpwdform.emailaddress.$valid == false) 
		{
			$scope.forgetpwd.errMessage = "please fill out this field";
		} 
		else 
		{
			$scope.forgetpwd.submitting = 1;
			$scope.forgetpwd.errMessage = "";
			var postData = {
					step : 1,
					emailaddress:	$scope.forgetpwd.emailaddress
			};

			var urlUser = CONFIG.GLOBAL_API_BASE+'/recovery';

			$http({method: 'post', url: urlUser, data: $.param(postData)}).success(
				function(data){
					if (angular.isDefined(data.errCode) && data.errCode == 0) {
						$scope.forgetpwd.succMessage = data.errMessage;
					} else {
						var errCode = angular.isDefined(data.errCode)?data.errCode:-1;
						var errMesg = angular.isDefined(data.errMessage)?data.errMessage:"service is temporarily unavailable";

						$scope.forgetpwd.errMessage = errMesg;
					}
					$scope.forgetpwd.submitting = 0;
				}
			);
		}		
	};

	//forgot pwd: step two, reset new password
	var pwdChangeForm = function($scope, $http) {
		if ($scope.changepwdform.password.$valid == false || $scope.changepwdform.cpwd.$valid == false) 
			$scope.changepwd.errMessage = "please fill out these fields";
		else if ($scope.changepwd.password != $scope.changepwd.cpwd)
			$scope.changepwd.errMessage = "inconsistant password";
		else 
		{
			$scope.changepwd.submitting = 1;
			$scope.changepwd.errMessage = "";

			var postData = {
					step : ($scope.userID <= 0 ? 2 : 3),
					currpwd : $scope.changepwd.currpwd,
					password:	$scope.changepwd.password,
					emailaddress:   $scope.changepwd.emailaddress,
					uniqCode: 		$scope.changepwd.uniqCode,
					uid : $scope.userID
			};

			var urlUser = CONFIG.GLOBAL_API_BASE+'/recovery';

			$http({method: 'post', url: urlUser, data: $.param(postData)}).success(
				function(data){
					if (angular.isDefined(data.errCode) && data.errCode == 0) {
						$scope.changepwd.succMessage = data.errMessage;
					} else {
						var errCode = angular.isDefined(data.errCode)?data.errCode:-1;
						var errMesg = angular.isDefined(data.errMessage)?data.errMessage:"service is temporarily unavailable";

						$scope.changepwd.errMessage = errMesg;
					}
					$scope.changepwd.submitting = 0;
				}
			);
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

	// enable location privider service
	nlapp.config(function($locationProvider) {
        $locationProvider.html5Mode(true);
    });
	
	/**
	 * 50.2 Controller for Index Page
	 */

	// Enable location privider service
	nlapp.config(function($locationProvider) {
        $locationProvider.html5Mode(true);
    });
	
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
		/**
		 * setup head, header, login/logout
		 */
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		setupFaceBook($scope, $http);

		/**
		 * setup scope models specific to debate page
		 */
		initMagnificPopup();

		getNews($scope, $http, newsID);
		getCommentsByNewsID($scope, $http, newsID);
		getVoteByNewsID($scope, $http, newsID);
		
		$scope.replyList = [];
		$scope.str2date = str2date;
		
		setupScroll2Reply($scope, $http);

		/**
		 * setup vote functions
		 */
		$scope.agreeAreaValid = true;
		$scope.disagreeAreaValid = true;

		$scope.submitAgree = function() {
			submitVote($scope, $http, "agree");
		};
		
		$scope.submitDisagree = function() {
			submitVote($scope, $http, "disagree");
		};

		$scope.bUserVoteAgree = function() {
			return bUserVoteAgree($scope);
		};
		
		$scope.bUserVoteDisagree = function() {
			return bUserVoteDisagree($scope);
		};

		/**
		 * setup reply functions
		 */
		$scope.bUserLikeThisReply = function(reply) {
			return bUserLikeThisReply($scope, reply);
		};

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
		
		$scope.toggleReplyArea = function(index) {
			return toggleReplyArea($scope, $http, index);
		};
	});
	
	/**
	 * 50.4 Controller for Sign Up Page
	 */
	nlapp.controller("SignupController", function($scope, $http){
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		setupFaceBook($scope, $http);
		
		$scope.goHome = goHome;

		$scope.signup = {
				displayName: "",
				emailaddress: "",
				pwd: "",
				cpwd: "",
				errMessage: "",
				succMessage: "",
				submitting: 0,
				submitSignupForm: function() {
					submitSignupForm($scope, $http);
				}
		};
	});
	
	/**
	 * 50.5 Controller for User Profile Page
	 */
	nlapp.controller("ProfileController", function($scope, $http){
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);

		$scope.profile = {
			editing: 0,
			displayName: '',
			fullname: '',
			succMessage: '',
			errMessage: '',
			submitting: 0,
			loadProfile: function() {
				return loadProfile($scope, $http);
			},
			submitProfileForm: function() {
				return submitProfileForm($scope, $http);
			}
		};
		
		$scope.profile.loadProfile();
	});
	
	/**
	 * 50.6 Controller for Notification Page
	 */
	nlapp.controller("NotificationController", function($scope, $http){
		/**
		 * setup head, header, login/logout
		 */
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		setupFaceBook($scope, $http);

		getNotificationInfo($scope, $http);
		$scope.str2date = str2date;

		$scope.readNotification = function(replyID, readflag) {
			if (readflag == 0)
			{
				resetReadflag($scope, $http, replyID);
			}
		};
	});
	
	/**
	 * 50.7 Controller for Debate Tracking
	 */

	nlapp.controller("TrackController", function($scope, $http){
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		
		$scope.track = {
			newsList: [],
			str2date: str2date,
			loadUserDebate: function() {
				return loadUserDebate($scope, $http, userID);
			},
			removeReply: function(newsID, replyID, subReplyID) {
				var urlReply = CONFIG.GLOBAL_API_BASE+"/news/"+newsID+"/reply/"+replyID;
				if (angular.isDefined(subReplyID) && subReplyID > 0)
					urlReply += "/"+subReplyID;
				
				$http({method: 'delete', url: urlReply}).success(
					function(data, status, headers, config, statusText) {
						loadUserDebate($scope, $http, userID);
					});
			}
		};

		$scope.track.loadUserDebate();
	});
	
	/**
	 * 50.8 Controller for Conteact Us
	 */
	var loadUserContactInfo = function($scope, $http) {
		if (userID > 0) {
			var urlUser = CONFIG.GLOBAL_API_BASE+'/user/'+userID;
			$http({method: 'get', url: urlUser}).success(function(data) {
				if (angular.isDefined(data.user)) {
					$scope.contact.displayName = data.user.displayName;
					if (data.user.emailaddress != "")
						$scope.contact.emailaddress = data.user.emailaddress;
					else
						$scope.contact.emailaddress = data.user.fbEmail;
				}
			});
		}
	};

	var submitContactForm = function($scope, $http) {
		$scope.contact.submitted = false;
		if ($scope.contactForm.$valid) {
			$scope.contact.submitting = true;
			var urlContact = CONFIG.GLOBAL_API_BASE+'/contact';
			var postData = {
				displayName: $scope.contact.displayName,
				emailaddress: $scope.contact.emailaddress,
				message: $scope.contact.message
			};
			$http({method:'post', url: urlContact, data: $.param(postData)}).success(function(data){
				$scope.contact.submitting = false;
				$scope.contact.submitted = true;
				$scope.contact.message = '';
			});
		}
	};

	nlapp.controller("ContactController", function($scope, $http){
	    getAuthInfo($scope, $http);
	    setupLoginForm($scope, $http);
	    
	    $scope.contact = {
	        displayName: '',
	        emailaddr: '',
	        submitting: false,
	        submitted: false,
	        loadUserContactInfo: function() {
	        	return loadUserContactInfo($scope, $http);
	        },
	        submitForm: function() {
	            return submitContactForm($scope, $http);
	        }
	    };
	    
	    $scope.contact.loadUserContactInfo();
	});

	/**
	 * 50.10 Controller for user find password
	 */
	nlapp.controller("ForgetpwdController", function($scope, $http){
		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		setupFaceBook($scope, $http);

		$scope.forgetpwd = {
				emailaddress: "",
				errMessage: "",
				succMessage: "",
				submitting: 0,
				pwdRecovaryForm: function() {
					pwdRecovaryForm($scope, $http);
				}
		};
	});

	/**
	 * 50.11 Controller for user change password
	 */
	nlapp.controller("ChangepwdController", function($scope, $http, $location){

		getAuthInfo($scope, $http);
		setupLoginForm($scope, $http);
		setupFaceBook($scope, $http);

		$scope.goHome = goHome;

		$scope.changepwd = {
				password: "",
				cpwd: "",
				currpwd: "",
				errMessage: "",
				succMessage: "",
				submitting: 0,
				emailaddress: $location.search().emailaddress,
				uniqCode: $location.search().uniqCode,
				pwdChangeForm: function() {
					pwdChangeForm($scope, $http);
				}
		};

		/*
  		// Set the default value of inputType
  		$scope.inputType = 'password';
  		// Hide & show password function
  		$scope.hideShowPassword = function(){
    	if ($scope.inputType == 'password')
      		$scope.inputType = 'text';
    	else
      		$scope.inputType = 'password';
  		};
		*/
	});

