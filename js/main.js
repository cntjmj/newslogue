//window.fbAsyncInit = function() {
//	FB.init({
//	  appId      : '270886663035696',
//	  xfbml      : true,
//	  version    : 'v2.2'
//	});

// TEST VERSION

$(function() {

// window.fbAsyncInit = function() {
//     FB.init({
//         appId      : '785845834839020',
//         xfbml      : true,
//         version    : 'v2.3'
//     });
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '540497959441744',
      xfbml      : true,
      version    : 'v2.4'
    });
//};


//	$(function(){
		$(".sharefb-link").click(function(){
			var obj = $(this);
            // alert(obj.attr('data-question'));
			FB.ui(
			{
			    method: 'feed',
			    name: obj.attr("data-question"),
			    link: obj.attr("data-newslink"),
			    picture: obj.attr("data-newspic"),	    
			    caption: obj.attr("data-newstitle"),
			    description: "www.newslogue.com",
			    width: 180,					    
			},
			function(response) {
			})
		});


		$(".fbregister-btn").click(function(){
	      	FB.login(function(response) {
		       if (response.authResponse) {
		        
		        FB.api('/me', {fields: 'id,name,email'}, function(meresponse) {
            	 	$(".registration-form input[name=fullname]").val(meresponse.name);
            	 	$(".registration-form input[name=emailaddress]").val(meresponse.email);
            	 	$(".registration-form input[name=fbID]").val(meresponse.id);

            	 	// console.log(meresponse);

            	 	$('.fbregistration').fadeOut();
		            $.ajax({
						type:"POST",
						url: "ajax/ajax_insertfbinfo.php",
						data: {
							send: "SUBMIT",
							fbName:meresponse.name,
							fbEmail: meresponse.email,
							fbID: meresponse.id
						},
						cache:true,
						success:function(data){
							location.reload();
						}
					})
		         });
		       } else {
		      
		       }
		     },{scope: 'email'}); 
	    })	
//	});
};

// (function(d, s, id){
//  var js, fjs = d.getElementsByTagName(s)[0];
//  if (d.getElementById(id)) {return;}
//  js = d.createElement(s); js.id = id;
//  js.src = "//connect.facebook.net/en_US/sdk.js";
//  fjs.parentNode.insertBefore(js, fjs);
// }(document, 'script', 'facebook-jssdk'));

(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


//$(function(){
	/*
	$("body").on("click",".dispute-link",function(){
		// $(".debate-slideover").html('<div class="" style="width: 65px; height: 65px; position: absolute; top: 50%; left: 50%; margin: -33px 0 0 -33px"><svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div>');
		var obj = $(this);
		$(".moveme").animate({
			left: "-100%"
		},"fast");
		$(".debate-slideover").css({display:'block'}).animate({
			left: "0"
		},"fast",function(){
			$.ajax({
				type:"GET",
				url: "ajax/ajax_get_debate.php",
				data: {id: obj.attr("data-newsID")},
				cache:true,
				success:function(data){
					$(".debate-slideover").html(data);
					$(".timeline-vertical").height($(".timeline").height());
					replyValidate();
					validateDebate();
					$('.agree-opinion').limit('2000','.agree-opinion-count');
					$('.disagree-opinion').limit('2000','.disagree-opinion-count');					
				}
			})	
		});
		$("html,body").css("overflow","hidden")
	});


	$(".join-discussion-dispute-now").click(function(){
		$(".moveme").animate({
			left: "-100%"
		},"fast");
		$(".debate-slideover").css({display:'block'}).animate({
			left: "0"
		},"fast");
		$("html,body").css("overflow","hidden");
		$(".timeline-vertical").height($(".timeline").height());
	});
	*/

	//$("body").on("click",".debate-slideover-back",function(){
		/*
		$(".moveme").animate({
			left: "0"
		});
		$("html,body").css("overflow","auto");
		*/
	//	history.back();
	//});


	$("body").on("click",".debate-box-userdetails-likes",function(){
	
		var obj = $(this);
		if(!obj.hasClass("disabled") && !obj.hasClass("sending"))
		{
			obj.addClass("sending");
			$.ajax({
				type:"POST",
				url: "ajax/ajax_like_reply.php",
				data: {replyID: obj.attr("data-replyID"),
					newsID: obj.attr("data-newsID")},
				cache:true,
				success:function(data){
					var resultArr = data.split("<==>");
					obj.removeClass("sending");

					if(resultArr[0] == "true" || data.indexOf("true<==>") >= 0)
					{
						likesNo = parseInt($("span",obj).text()) + 1;
						obj.html('<i class="icomo-love selected"></i><span>'+likesNo+'</span>');
					}
					else if(resultArr[0] == "removed" || data.indexOf("removed<==>") >= 0)
					{
						likesNo = parseInt($("span",obj).text()) - 1;
						obj.html('<i class="icomo-love "></i><span>'+likesNo+'</span>');
					}
					
				}
				, error: function(data) {alert(data);}
			})	
		}
		
	});

	$("body").on("click",".debate-sliderover-decision .agree-btn",function(){
	
		var obj = $(this);
		//if(!obj.hasClass("disabled"))
		if (obj.attr("id") != "login-btn-agree")
		{
			$(".disagree-box").hide();
			$(".disagree-box form")[0].reset();
			$(".agree-box").fadeIn();
			$(".agree-btn").removeClass("disabled");
			$(".disagree-btn").addClass("disabled");
			$(".disagree-btn").removeClass("selected");
			$(".timeline-vertical").animate({
				height: $(".timeline").height()
			})	;
			if(!obj.hasClass("sending") && !obj.hasClass("selected"))
			{
				obj.addClass("sending");
				$.ajax({
					type:"POST",
					url: "ajax/ajax_vote.php",
					data: {newsID: obj.attr("data-newsID"),voteType: "agree", overRide: "1"},
					//cache:true,
					success:function(data){
						//$(".debate-sliderover-decision a").addClass("disabled");
						obj.removeClass("sending").addClass("selected");

						var result = data.split("<==>");
						$(".join-discussion-ratings").hide().html(result[1]).fadeIn();
					}
				})
			}
		}
		
	});

	$("body").on("click",".debate-sliderover-decision .disagree-btn",function(){
		var obj = $(this);
		//if(!obj.hasClass("disabled"))
		if (obj.attr("id") != "login-btn-disagree")
		{
			$(".agree-box").hide();
			$(".agree-box form")[0].reset();
			$(".disagree-box").fadeIn();
			$(".disagree-btn").removeClass("disabled");
			$(".agree-btn").addClass("disabled");
			$(".agree-btn").removeClass("selected");
			$(".timeline-vertical").animate({
				height: $(".timeline").height()
			});

			if(!obj.hasClass("sending") && !obj.hasClass("selected"))
			{
				obj.addClass("sending");
				$.ajax({
					type:"POST",
					url: "ajax/ajax_vote.php",
					data: {newsID: obj.attr("data-newsID"),voteType: "disagree", overRide: "1"},
					//cache:true,
					success:function(data){
						//$(".debate-sliderover-decision a").addClass("disabled");
						obj.removeClass("sending").addClass("selected");
						var result = data.split("<==>");
						
						//$(".join-discussion-ratings").fadeIn(result[1])
						$(".join-discussion-ratings").hide().html(result[1]).fadeIn()	;

					}
				})
			}
		}
	});

	replyValidate();



	function replyValidate(){
		$('.reply-form ').each(function () {
			var formObj = $(this);
		    formObj.validate({
		    	rules: {
					
					replyDesc: "required"
				},
				submitHandler: function(){
					

					if(!formObj.hasClass("disabled"))
					{
						formObj.addClass("disabled");
						var serializedData = formObj.serialize();
						$.ajax({
							type:"POST",
							url: "ajax/ajax_reply.php",
							data: serializedData,
							cache:true,
							success:function(){
								formObj.next().prepend('<div class="reply-box-item"><div class="reply-box-name">'+$("input[name=displayName]",formObj).val()+'</div><div class="reply-box-usercontent">'+$("textarea[name='replyDesc']",formObj).val()+'</div></div>');
								formObj.removeClass("disabled");
								formObj[0].reset();
								$(".reply-notification").html('<div class="success">Thank you for submitting. We will monitor and publish your opinion selectively.</div>');
								setTimeout(function(){
									$(".reply-notification div").slideUp();	
								},5000)
							}
						})
					}

				}
		    });
		});
	}


	$(".debate-box-userdetails-opinion").click(function(){		
		var obj = $(this);	
		$(".debate-usercontent-opinion"+obj.attr("data-id")).slideToggle("fast",function(){
			$('.timeline-vertical').css('height', 'auto').height();
			$(".timeline-vertical").animate({
				height: $(".timeline").height()
				}, 0);	
		})
	});





	$("body").on("click",".debate-box-userdetails-comment",function(){
	
		var obj = $(this);
		$(".reply"+obj.attr("data-id")).slideToggle("fast",function(){
			//$(".timeline-vertical").height($(".timeline").height())
			//var haha = $(".timeline-vertical").height("auto")
			// $(".timeline-vertical").animate({
			// 	height: $(".timeline").height()
			// })
		$('.timeline-vertical').css('height', 'auto').height();
		$(".timeline-vertical").animate({
			height: $(".timeline").height()
			}, 0);	
		})
	});
	$(".timeline-vertical").height($(".timeline").height());

    resizePopup();

	$(window).resize(function(){
		resizePopup()
		
	});

	function resizePopup()
	{
		var winHeight = window.innerHeight;
		$(".popup").css("max-height",(window.innerHeight -60)+"px")
	}
	var hottopicslider = $(".flexslider.hottopicslider").flexslider({
		animation: "slide",
		controlNav: false,
		directionNav: false,
		start: function(slider){

			if(slider.count == 1)
			{
				$(".hottopic-arrows").hide();
			}
		}
	});




	// $(".ham-link").click(function(){
	// 	$(".overlay,.category-popup").fadeIn();
	// });
	$(".ham-link").click(function(){
		$(".nav-toggle-menu").toggle();
		$(".nav-search-menu").hide();
		$(".userinfo").hide();
		$(".notificationinfo").hide();
	});

	// to close(display:none) toggle-menu
	$('#close-toggle-menu').click(function(){
		$('.nav-toggle-menu').hide();
	});

	// $(".search-link").click(function(){
	// 	$(".overlay,.search-popup").fadeIn();
	// });

	$(".search-link").click(function(){
		$(".nav-search-menu").toggle();
		$(".nav-toggle-menu").hide();
		$(".userinfo").hide();
		$(".notificationinfo").hide();
		$(".search-result").html("");
		$("#search-input").val("");
	});

	// to close(display:none) search-menu
	$('#close-search-menu').click(function(){
		$('.nav-search-menu').hide();
		$(".search-result").html("");
		$("#search-input").val("");
	});

	$('.greetuser').click(function(){
		$(".userinfo").toggle();
		$(".nav-toggle-menu").hide();
		$(".nav-search-menu").hide();
		$(".notificationinfo").hide();
	});

	$('.notification-link').click(function(){
		$(".notificationinfo").toggle();
		$(".nav-toggle-menu").hide();
		$(".nav-search-menu").hide();
		$(".userinfo").hide();
	});	
	
	// when click other area over the toggle nav menu(or search menu), hide the menu
	$(document).mouseup(function(e){
		var container = $('.outer-click-hide');
		if(!container.is(e.target) && container.has(e.target).length === 0)
		{			
			if($('.nav-toggle-menu').css('display') == 'block'){				
				$(".nav-toggle-menu").hide();				
			}				
			

			if($('.nav-search-menu').css('display') == 'block'){
				$(".nav-search-menu").hide();
			}

			if($('.userinfo').css('display') == 'block' ){
				$('.userinfo').hide();	
			}
			$(".nav-toggle-menu").hide();	
			$(".nav-search-menu").hide();
			$('.userinfo').hide();	
			$('.notificationinfo').hide();	
		}
	});

	// fire the search function with enter key
	$(".search").keyup(function (event) {
        var key = event.keyCode || event.which;

        if (key === 13) 
        {
        	$(".search-result").html('<img src="img/loading.gif"/>');
        	var obj = $(".search-form");
			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					data: serializedData,
					url: "ajax/ajax_search.php",
					cache:true,
					success:function(data){
						obj.removeClass("disabled");
						$(".search-result").html(data).hide().fadeIn();

					}
				})
			}    
        }
        return false;
    });

	$(".closecat-btn").click(function(){
		$(".overlay,.category-popup,.search-popup").fadeOut();
		$("body").css("overflow","auto");
	});
	$(".closepopup-btn").click(function(){
		$(".popup,.overlay").fadeOut();
		$("body").css("overflow","auto");
	});



	$(".htarrow").click(function(){
		var obj = $(this);
		hottopicslider.flexslider(obj.attr("data-nav"));
	});
	
	$(".changeemail-form").validate({
		rules: {
			newemail: {
				required:true,
				email:true
			},
			confirmnewemail: {
				required:true,
				email:true,
				equalTo: "#newemail"
			}
		},
		submitHandler: function(){
			var obj = $(".changeemail-form");

			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					url: "ajax/ajax_change_email.php",
					data: serializedData,
					cache:true,
					success:function(data){
						
						obj.removeClass("disabled");
						var resultArr = data.split("<==>");
						var notiObj = $(".email-notification");
						if(resultArr[0] == "true")
						{
							
							notiObj.html("<div class='success'>"+resultArr[1]+"</div>");

							$(".email-notice span").html($("input[name=newemail]").val());
						}
						else
						{
							notiObj.html("<div class='errornoti'>"+resultArr[1]+"</div>");
							
						}
						obj[0].reset();
						setTimeout(function(){
							//$("div",notiObj).slideUp();	
						},3000)

					}
				})
			}
		}
	});


	$(".displayname-form").validate({
		rules: {
			displayName: "required",
			fullname: "required"
		},
		submitHandler: function(){
			var obj = $(".displayname-form");

			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					url: "ajax/ajax_change_displayname.php",
					data: serializedData,
					cache:true,
					success:function(data){
						
						obj.removeClass("disabled");
						var notiObj = $(".name-notification");
						var resultArr = data.split("<==>");
						if(resultArr[0] == "true")
						{
							
							notiObj.html("<div class='success'>"+resultArr[1]+"</div>");
							$(".greetuser a").html($("input[name=displayName]").val())
							
						}
						else
						{
							notiObj.html("<div class='errornoti'>"+resultArr[1]+"</div>");
							obj[0].reset();
						}
						setTimeout(function(){
							$("div",notiObj).slideUp();	
						},3000)

					}
				})
			}
		}
	});


	$(".forgot-form").validate({
		rules: {
			emailaddress: "required"
		},
		submitHandler: function(){
			
			var obj = $(".forgot-form");

			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					url: "ajax/ajax_forgotpassword.php",
					data: serializedData,
					cache:true,
					success:function(data){
						
						obj.removeClass("disabled");
						var resultArr = data.split("<==>");
						if(resultArr[0] == "true")
						{
							$(".forgot-notification").html("<div class='success'>"+resultArr[1]+"</div>");

							setTimeout(function(){
								$(".forgot-notification div").slideUp();	
							},3000)
						}
						else
						{
							$(".forgot-notification").html("<div class='errornoti'>"+resultArr[1]+"</div>");

						}

					}
				})
			}
		}
	});

	$(".delete-debate-btn").click(function(){
		var obj = $(this);

		if(!obj.hasClass("disabled"))
		{
			obj.addClass("disabled");
			obj.html("Deleting...");
			$.ajax({
				type:"POST",
				url: "ajax/ajax_deletedebate.php",
				data: { replyID : obj.attr("data-replyID")},
				cache:true,
				success:function(data){
					
					obj.removeClass("disabled");
					var resultArr = data.split("<==>");
					if(resultArr[0] == "true")
					{
						obj.parent().parent().parent().fadeOut();
					}
					else
					{
						

					}

				}
			})
		}
	});

	$(".editprofile-form").validate({
		rules:{
			fullname:"required"
		}
	});

	$(".pwd-form").validate({
		rules:{
			oldpwd:"required",
			newpwd:"required",
			confirmnewpwd:{
				required: true,
				equalTo: "#newpwd"
			}
		},
		submitHandler: function(){
			
			var obj = $(".pwd-form");

			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					url: "ajax/ajax_change_pwd.php",
					data: serializedData,
					cache:true,
					success:function(data){
						var resultArr = data.split("<==>");
						var notiObj = $(".password-notification");
						if(resultArr[0] == "true")
						{
							notiObj.html("<div class='success'>"+resultArr[1] +"</div>");

							
						}
						else
						{
							notiObj.html("<div class='errornoti'>"+resultArr[1] +"</div>");

						}
						obj[0].reset();
						setTimeout(function(){
							$("div",notiObj).slideUp();	
						},3000);
						obj.removeClass("disabled");

					}
				})
			}
			return false
		}
	});

	$(".login-form").validate({
		rules: {
			
			emailaddress: "required",
			pwd: "required"
		},
		submitHandler: function(){
			var obj = $(".login-form");

			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					url: "ajax/ajax_login.php",
					data: serializedData,
					cache:true,
					success:function(data){
						var resultArr = data.split("<==>");

						if(resultArr[0] == "true")
						{
							// $(".login-notification").html("<div class='success'>Successfully logged in. You will be redirected in 3 seconds</div>");

							// setTimeout(function(){

								// window.top.location.href="user_moderation.php";
								if(window.location.reload(true)){
									$('.overlay, .login-popup').hide();								
								}
								


							// },3000)
							// })
						}
						else
						{
							$(".login-notification").html("<div class='errornoti'>Invalid login details.</div>");

						}
						obj.removeClass("disabled");

					}
				})
			}
		}
	});

	// For enquiry email sending
	$('.enquiry-form').validate({
		rules:{
			fullname: "required",
			emailaddress: "required",
			enquirycontent: "required"
		},		
		submitHandler:function(){			
			var obj = $('.enquiry-form');
			if(!obj.hasClass('disabled'))
			{				
				obj.addClass('disabled');
				var serializedData = obj.serialize();				
				$.ajax({
					type:"POST",
					url: "ajax/ajax_enquiry.php",
					data: serializedData,
					cache:true,
					success:function(data){		
					console.log(data);				
						obj.removeClass('disabled');
						var resultArr = data.split("<==>");
						
						if(resultArr[0] == "true")
						{
							$(".enquiry-notification").html("<div class='success'>Your inquiry email has been sent to the webmaster.</div>");

							setTimeout(function(){
								$(".enquiry-notification div").slideUp();	
								$("#fullname, #emailaddress, #enquirycontent").val("");
							},3000)
						}
						else
						{
							$(".enquiry-notification").html("<div class='errornoti'>"+resultArr[1]+"</div>");
							setTimeout(function(){
								$(".enquiry-notification div").slideUp();	
							},5000);
						}
					}
				})
			}
		}
	});

	$(".registration-form").validate({
		rules: {
			fullname: "required",
			displayName: "required",
			emailaddress: "required",
			pwd: "required",
			cpwd: {
				required: true,
				equalTo: "#pwd"
			}
		},
		submitHandler: function(){
			var obj = $(".registration-form");			
			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					url: "ajax/ajax_register.php",
					data: serializedData,
					cache:true,
					success:function(data){
						obj.removeClass("disabled");
						//$(".registration-popup").hide()
						//$(".registration-notification").fadeIn()

						var resultArr = data.split("<==>");

						if(resultArr[0] == "true")
						{
							$(".register-notification").html("<div class='success'>A verification email has been sent to your mailbox.</div>");

							setTimeout(function(){
								$(".register-notification div").slideUp();	
							},3000)
						}
						else
						{
							$(".register-notification").html("<div class='errornoti'>"+resultArr[1]+"</div>");

						}
						setTimeout(function(){
							$(".subscription-notification div").slideUp();	
						},5000);
					}
				})
			}

		}
	});

	$(".subscription-list button").click(function(){
		
		$(".subscription-list").submit();
	});


	$(".subscription-form").validate({
		rules: {
			emailaddress: {
				required: true,
				email: true
			}
		},
		submitHandler: function(){
			var obj = $(".subscription-form");


			
			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				var serializedData = obj.serialize();
				$.ajax({
					type:"POST",
					url: "ajax/ajax_esubscribe.php",
					data: serializedData,
					cache:true,
					success:function(data){
						obj.removeClass("disabled");
						obj[0].reset();
						var result = data.split("<==>");
						if(result[0] == "true")
							$(".subscription-notification").html("<div class='success'>Thank you for subcribing to us.</div>");
						else
							$(".subscription-notification").html("<div class='errornoti'>Thank you for subcribing to us.</div>");
						

						setTimeout(function(){
							$(".subscription-notification div").slideUp();	
						},5000);
					}
				})
			}

		}
	});

	if($(".agree-opinion").length > 0)
	{
		$('.agree-opinion').limit('2000','.agree-opinion-count');
		$('.disagree-opinion').limit('2000','.disagree-opinion-count');
		
	}

	validateDebate();


	function validateDebate(){
		$('.debate-reply').validate({
			rules: {
				//replyStatement: "required",
				replyDesc: "required"
			},
			submitHandler: function(){
				var obj = $(".debate-reply");
				var objId = obj.attr("data-newsID");

				if(!obj.hasClass("disabled"))
				{
					obj.addClass("disabled");
					var serializedData = obj.serialize();
					$.ajax({
						type:"POST",
						url: "ajax/ajax_reply.php",
						data: serializedData,
						cache:true,
						success:function(){
							obj.removeClass("disabled");
							obj[0].reset();
							
							$(".debate-reply,.debate-reply2").hide();
							$(".agree-box").hide();
							// $(".debate-notification").fadeIn();
							
							// To display opinion on submission
							$.ajax({
								type:"GET",
								url: "ajax/ajax_get_debate.php",
								data: {id: objId},
								cache:true,
								success:function(data){
									$(".debate-slideover").html(data);
									$(".timeline-vertical").height($(".timeline").height());
									replyValidate();
									validateDebate();
									$('.agree-opinion').limit('2000','.agree-opinion-count');
									$('.disagree-opinion').limit('2000','.disagree-opinion-count');					
								}
							})
							// To display opinion on submission (end)
						}
					})
				}

			}
		});


		$('.debate-reply2').validate({
			rules: {
				//replyStatement: "required",
				replyDesc: "required"
			},
			submitHandler: function(){
				var obj = $(".debate-reply2");
				var objId = obj.attr("data-newsID");

				if(!obj.hasClass("disabled"))
				{
					obj.addClass("disabled");
					var serializedData = obj.serialize();
					$.ajax({
						type:"POST",
						url: "ajax/ajax_reply.php",
						data: serializedData,
						cache:true,
						success:function(){
							obj.removeClass("disabled");
							obj[0].reset();
							
							$(".debate-reply,.debate-reply2").hide();
							$(".disagree-box").hide();
							// $(".debate-notification").fadeIn();

							// To display opinion on submission
							$.ajax({
								type:"GET",
								url: "ajax/ajax_get_debate.php",
								data: {id: objId},
								cache:true,
								success:function(data){
									$(".debate-slideover").html(data);
									$(".timeline-vertical").height($(".timeline").height());
									replyValidate();
									validateDebate();
									$('.agree-opinion').limit('2000','.agree-opinion-count');
									$('.disagree-opinion').limit('2000','.disagree-opinion-count');					
								}
							})
							// To display opinion on submission (end)
						}
					})
				}

			}
		})
	}
	$(".agree-box .writeagain").click(function(){
		$(".debate-reply").fadeIn();
		$(".debate-notification").hide();
	});


	$(".disagree-box .writeagain").click(function(){
		$(".debate-reply2").fadeIn();
		$(".debate-notification").hide();
		$(".timeline-vertical").animate({
			height: $(".timeline").height()
		});
	});

	$("#login-btn-agree, #login-btn-disagree").click(function(){
		$(".login-popup").show();
	});

	// $('.reply-list form').each(function () {
	// 	var formObj = $(this)
	//     formObj.validate({
	//     	rules: {
	// 			replyStatement: "",
	// 			replyDesc: "required"
	// 		},
	// 		submitHandler: function(){
				

	// 			if(!formObj.hasClass("disabled"))
	// 			{
	// 				formObj.addClass("disabled")
	// 				var serializedData = formObj.serialize()
	// 				$.ajax({
	// 					type:"POST",
	// 					url: "ajax/ajax_reply.php",
	// 					data: serializedData,
	// 					cache:true,
	// 					success:function(data){
	// 						formObj.removeClass("disabled")
	// 						formObj[0].reset()
	// 						$(".reply-notification").html('<div class="success">Thank you for submitting. We will monitor and publish your opinion selectively.</div>')
	// 						setTimeout(function(){
	// 							$(".reply-notification div").slideUp();	
	// 						},5000)
	// 					}
	// 				})
	// 			}

	// 		}
	//     });
	// });

	$(".profilepicture-form").validate({
		rules: {
			userProfilePicture: {
				required: true,
				accept: "jpg|png|jpeg|gif"
			}
		},
		messages:{
			userProfilePicture: {
				
				accept: "Please upload image that has only these extensions jpg,png,jpeg or gif."
			}	
		},
		submitHandler: function(){
			

			if(!formObj.hasClass("disabled"))
			{
				// formObj.addClass("disabled")
				// var serializedData = formObj.serialize()
				// $.ajax({
				// 	type:"POST",
				// 	url: "ajax/ajax_reply.php",
				// 	data: serializedData,
				// 	cache:true,
				// 	success:function(data){
				// 		formObj.removeClass("disabled")
				// 		formObj[0].reset()
				// 	}
				// })
			}

		}
	});


	$(".feed-loadmore-btn").click(function(){
		var obj = $(this);
		var curPageNo = parseInt(obj.attr("data-pageNo"));
		var maxPageNo = parseInt(obj.attr("data-maxPageNo"));
		var nextPageNo = curPageNo + 1;
        var linkTo = obj.attr('linkTo');
		if(!obj.hasClass("disabled"))
		{
			obj.addClass("disabled");
			var serializedData = obj.serialize();
			$.ajax({
				type:"POST",
				url: "ajax/ajax_feeds.php",
				data: {pageNo: (nextPageNo), filterCategory: obj.attr("data-id"),type:obj.attr("data-type"),fastfeedName:obj.attr("data-fastfeed") },
				cache:true,
				success:function(data){
					obj.removeClass("disabled");
					$(".feed-listing").append(data);
					obj.attr("data-pageNo",nextPageNo);
					if(nextPageNo == maxPageNo)
						$(".loadmore-col").remove();

				}
			})
		}

	});

	$(".reply-user").click(function(){
		var obj = $(this);
		obj.next().slideDown("fast");
	});

	$(".debate-cta a").click(function(){
		var obj = $(this);

		$(".debate-cta a").removeClass("selected");
		obj.addClass("selected");

		if(obj.hasClass("agree-btn"))
		{
			$(".debate-instruction").html("You chose <i class='icomo-circle-yes'></i>. What is your say?");
			$(".replyType").val("agree");
		}
		else
		{
			$(".debate-instruction").html("You chose <i class='icomo-circle-no'></i>. What is your say?");
			$(".replyType").val("disagree");
		}
		$(".debate").removeClass("disabled");
		$(".debate-reply input,.debate-reply textarea").removeAttr("disabled");
	});

	// for logout from main index page
	$(".logout-btn-debate").click(function(){
		
		if(confirm("Are you sure to logout?"))
		{
			var obj = $(this);
			if(!obj.hasClass("disabled"))
			{
				obj.addClass("disabled");
				$.ajax({
					type:"POST",
					url: "ajax/ajax_logout.php",
					cache:true,
					success:function(){
						// alert("Logout successfully.");
						window.location.href = "index.php"	
					}
				});
			}	
		}		

    });

$("body").on("click", ".switchpopup" ,function(){
	// if any menu(toggle-menu or search menu pop up) is on, hide them
	$(".nav-toggle-menu").hide();
	$(".nav-search-menu").hide();

    var obj = $(this);
    $(".popup").hide();
    $(".overlay").fadeIn();
    $("."+obj.attr("data-to")).fadeIn();
    $("body").css("overflow","hidden");
});

 // Website content copy protection
	document.oncopy = function(){
    	var bodyEl = document.body;
    	var selection = window.getSelection();
    	selection.selectAllChildren( document.createElement( 'div' ) );
	};

//
//$(".switchpopup").on("click" ,function(){
//    alert("join now!");

//    var obj = $(this);
//    $(".popup").hide();
//    $(".overlay").fadeIn();
//    $("."+obj.attr("data-to")).fadeIn();
//    $("body").css("overflow","hidden");
//});




}); // end of $(function()) {}

// ***** outside of $(function() { } *******************



