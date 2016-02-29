(function($) {

  $.fn.menumaker = function(options) {
      
      var cssmenu = $(this), settings = $.extend({
        title: "Menu",
        format: "dropdown",
        sticky: false
      }, options);

      return this.each(function() {
        //cssmenu.prepend('<div id="menu-button">' + settings.title + '</div>');
    	var pathname = $(location).attr('pathname');
    	var href = "/home";
    	if (pathname == '/' || pathname.indexOf("/index") == 0 || pathname.indexOf("/home") == 0) {
    		href = "javascript:;"
    	}
        cssmenu.prepend(
          '<a href="'+href+'" class="newslogue_logo">' + 
            '<img src="/images/logo.jpg" alt="Newslogue Logo" />' +
          '</a>'+
          '<a href="/profile" title="User Profile" class="userprofile_icon show_after_logon" style="display:none">'+
            '<i class="fa fa-user"></i>'+
          '</a>'+
          '<a href="/notification" title="Notification" class="notification_icon show_after_logon" style="display:none">'+
            '<i class="fa fa-comments"></i>'+
            '<span class="top_notification" id="top_notification_number"></span>'+
          '</a><div id="menu-button"></div>');
        $(this).find("#menu-button").on('click', function(){
          $(this).toggleClass('menu-opened');
          var mainmenu = $(this).next('ul');
          if (mainmenu.hasClass('open')) { 
            mainmenu.hide().removeClass('open');
          }
          else {
            mainmenu.show().addClass('open');
            if (settings.format === "dropdown") {
              mainmenu.find('ul').show();
            }
          }
        });

        cssmenu.find('li ul').parent().addClass('has-sub');

        multiTg = function() {
          cssmenu.find(".has-sub").prepend('<span class="submenu-button"></span>');
          cssmenu.find('.submenu-button').on('click', function() {
            $(this).toggleClass('submenu-opened');
            if ($(this).siblings('ul').hasClass('open')) {
              $(this).siblings('ul').removeClass('open').hide();
            }
            else {
              $(this).siblings('ul').addClass('open').show();
            }
          });
        };

        if (settings.format === 'multitoggle') multiTg();
        else cssmenu.addClass('dropdown');

        if (settings.sticky === true) cssmenu.css('position', 'fixed');

        resizeFix = function() {
			/*
          if ($( window ).width() > 1080) {
            cssmenu.find('ul').hide();
          }

          if ($(window).width() <= 1080) {
            cssmenu.find('ul').hide().removeClass('open');
          }
		  */
        };
        resizeFix();
        return $(window).on('resize', resizeFix);

      });
  };
})(jQuery);

(function($){
$(document).ready(function(){

$("#cssmenu").click(function() {
	if ($("#menu-button").hasClass("menu-opened"))
	{
		//$("#container .fa-comments").css("z-index","0");
		//$(".top_notification").css("z-index","0");
		//$("#container .newslogue_logo").addClass("hide");
		/*
		$("#container .newslogue_logo").css("opacity","0");
		
		$("#container .notification_icon").css("opacity","0");
		$("#container .userprofile_icon").css("opacity","0");
		*/
		
		//$("#login_area").hide();
		$("#category_list").css("margin-top","37px");	
				$(".debate_head_main").css("margin-top","37px");
	}
	else
	{
		//	$("#container .newslogue_logo").removeClass("show");
		//	$("#container .notification_icon").removeClass("hide");
		/*
		$("#container .newslogue_logo").css("opacity","1");
		
		$("#container .notification_icon").css("opacity","1");
		$("#container .userprofile_icon").css("opacity","1");
		*/
		//$("#container .fa-comments").css("z-index","300");
		//$(".top_notification").css("z-index","300");
	}
});

$(".login_user" ).click(function() {
	$("#cssmenu #menu-button").removeClass("menu-opened");
	$("#cssmenu ul").removeClass("open");
	$("#cssmenu ul").css("display","none");
	$("#login_area").slideDown("slow");
	$("#category_list").css("margin-top","0px");
	$(".debate_head_main").css("margin-top","0px");
	
});

$(document).mouseup(function (e)
{
	/*
    var container = $("#login_area");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
		//container.hide();
		//$("#cssmenu #menu-button").removeClass("menu-opened");
		//$("#cssmenu ul").removeClass("open");
		//$("#cssmenu ul").css("display","none");
		$(container).slideUp("fast");
		$("#category_list").css("margin-top","37px");
		$(".debate_head_main").css("margin-top","37px");
    }
    */
    
	var cssmenu = $("#cssmenu");

	if (!cssmenu.is(e.target)
		&& cssmenu.has(e.target).length === 0
		&& $("#cssmenu ul").hasClass("open")) {
		$("#menu-button").trigger('click');
	}
});

$("#cssmenu").menumaker({
   title: "Newslogue",
   format: "multitoggle"
});

});
})(jQuery);
