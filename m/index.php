<?php 
	require_once "../nl-init.php";
	
	$categoryID = _get("categoryID", 0);
?>
<!doctype html>
<html ng-app="nlapp">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<title>Newslogue Home</title>

<link rel="stylesheet" href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/style.css"> <!--CSS-->
<link href="<?=CONFIG_PATH::GLOBAL_M_BASE?>font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet"> <!-- FONT AWESOME -->
<link href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/gfont.css" rel='stylesheet' type='text/css'><!-- GOOGLE FONT -->
<link rel="stylesheet" href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/menu.css"><!--CSS MENU-->
<link rel="stylesheet" href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/magnific-popup.css"> <!--CSS POPUP-->


<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/jquery.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/angular.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/angular-sanitize.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/ng-infinite-scroll.min.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/nl-common.js"></script>


</head>

<body ng-controller="IndexController">


	<div id="container">
        
        <div id='cssmenu'>
            <ul>
               <li><a href='javascript:;'>Articles</a></li>
               <li><a href='javascript:;'>About us</a></li>
               <li><a href='javascript:;'>Contact</a></li>
               <li ng-hide="userID>0"><a href='javascript:;' class="login_user">Login</a></li>
               <li ng-show="userID>0" ng-click="user.logout()"><a href='javascript:;'>Logout</a></li>
            </ul>
		</div>

<section>
<div id="login_area" class="ind_question">
    <form class="form" name="loginform" id="loginform" >
      <div class="txt_username"><i class="fa fa-user fa-1x"></i>
        <input name="emailaddress" type="email" required ng-model="user.emailaddress"
        class="feedback-input" placeholder="email address" id="emailaddress" />
      </div>
      
      <div class="txt_password"><i style="color:#FFFFFF;float:left;margin-left:15px;padding-top:2px;" class="fa fa-lock fa-1x"></i>
        <input type="password" name="password" required ng-model="user.password"
        class="feedback-input" id="password" placeholder="********">
     </div>
      <div class="login">
        <input type="submit" value="LOGIN" id="submit_btn_blue" ng-click="user.login()" /><br/>
        <a href="javascript:;">FORGOT PASSWORD?</a><br/>
        <a href="javascript:;">FORGOT USERNAME?</a>
        </div>
        
    </form>

<div id="login_others">
        <p>OR <a href="javascript:;">SIGN UP</a> - <a id="login_with_facebook" href="javascript:;">LOGIN WITH FACEBOOK</a></p>
</div>

</div>




</section>

<!--NAV-->
<section>
<div id="category_list"><ul>
<li><a href="{{selectedCategoryID<=0?'javascript:;':'/home'}}">All</a></li>
<li ng-repeat-start="category in categoryList">-</li>
<li ng-repeat-end><a href="{{selectedCategoryID==category.categoryID?'javascript:;':'/home/'+category.categoryID}}" ><span ng-bind-html="category.categoryName"></span></a></li>

<!-- 
<li>-</li>
<li><a href="javascript:;">AUSTRALIA</a></li>
<li>-</li>
<li><a href="javascript:;">WORLD</a></li>
<li>-</li>
<li><a href="javascript:;">TECHNOLOGY</a></li>
<li>-</li>
<li><a href="javascript:;">LIFESTYLE</a></li>
 -->
</ul></div>
</section>
        
<main id="index_main_section" infinite-scroll='loadmore()' infinite-scroll-disabled='!ready2scroll'>
<?php if (1) { ?>
		<section ng-repeat="news in newsMetaList" class="article_content">
        
        
        	<div class="ind_question content">
           	<h1> {{news.newsQuestion}} </h1>
            </div>

<a href="{{news.newsSource}}" class="popup-external-iframe"><div class="ind_photo" style="background-image:url('{{news.newsBannerSource}}');">
</div></a>
<div class="ind_title_article">
                                                <div class="ind_article_bg"></div>
                                                <h4> - {{news.newsTitle}} - <a class="popup-external-iframe" href="{{news.newsSource}}">READ MORE</a></h4>
                                                <h5 style="margin-top:5px;">{{news.newsSite}}</h5>
                                        </div>
                        
            <div class="ind_debate"> <a href="javascript:;"> DEBATE </a> </div>
            <div class="border"></div>
            <div class="clear"></div>
        </section>
</main>
<?php } else { ?>


<!--SECTION 2--> 
		<section class="article_content">
        	<div class="ind_question content">
            	<h1>" If you were a member of Anonymous, would you try to change their tactics?<br>
What would you do differently? " </h1>
            </div>

<a href="http://www.pphe.com" class="popup-external-iframe"><div class="ind_photo" style="background-image:url(images/article.jpg);">
</div></a>

            

                
            <div class="ind_title_article">
            <div class="ind_article_bg"></div>
                <h4> - Amanda Vanstone lashes out at Bindi Irwin: 'She's not the only kid whose father has died' -  <a class="popup-external-iframe" href="http://www.pphe.com">READ MORE</a> </h4>
                <h5 style="margin-top:5px;"> CNN.COM </h5>
            </div>

            
            <div class="ind_debate"> <a href="javascript:;"> DEBATE </a> </div>
            <div class="border"></div>
            <div class="clear"></div>
        </section>
        
<!--SECTION 3--> 
		<section class="article_content">
        	<div class="ind_question content">
            	<h1> " Could things have panned out differently for Sara and Faigy if they had sought a professional therapist rather than the family's amateur? " </h1>
            </div>
            <div class="ind_photo">
<a class="popup-external-iframe" href="http://www.saffery.com"><img class="b-lazy" src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
data-src="images/article4.jpg"
alt="Article 4"/></a>
            <div class="ind_title_article">
            <div class="ind_article_bg"></div>
                <h4> - Amanda Vanstone lashes out at Bindi Irwin: 'She's not the only kid whose father has died' - <a class="popup-external-iframe" href="http://www.saffery.com">READ MORE</a> </h4> </h4>
                <h5 style="margin-top:5px;"> CNN.COM </h5>
            </div>
            </div>
            
            <div class="ind_debate"> <a href="#"> DEBATE </a> </div>
            <div class="border"></div>
            <div class="clear"></div>
        </section>
        
        
        <section class="article_content">
        	<div class="ind_question content">
            	<h1> " Could things have panned out differently for Sara and Faigy if they had sought a professional therapist rather than the family's amateur? " </h1>
            </div>
            <div class="ind_photo">
<a class="popup-external-iframe" href="http://www.saffery.com"><img class="b-lazy" src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
data-src="images/article4.jpg"
alt="Article 4"/></a>
            <div class="ind_title_article">
            <div class="ind_article_bg"></div>
                <h4> - Amanda Vanstone lashes out at Bindi Irwin: 'She's not the only kid whose father has died' - <a class="popup-external-iframe" href="http://www.investis.com">READ MORE</a> </h4> </h4>
                <h5 style="margin-top:5px;"> CNN.COM </h5>
            </div>
            </div>
            
            <div class="ind_debate"> <a href="#"> DEBATE </a> </div>
            <div class="border"></div>
            <div class="clear"></div>
        </section>
        <section class="article_content">
        	<div class="ind_question content">
            	<h1> " Could things have panned out differently for Sara and Faigy if they had sought a professional therapist rather than the family's amateur? " </h1>
            </div>
            <div class="ind_photo">
<a class="popup-external-iframe" href="http://www.saffery.com"><img class="b-lazy" src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
data-src="images/article4.jpg"
alt="Article 4"/></a>

            <div class="ind_title_article">
            <div class="ind_article_bg"></div>
                <h4> - Amanda Vanstone lashes out at Bindi Irwin: 'She's not the only kid whose father has died' - <a class="popup-external-iframe" href="http://www.newslogue.com">READ MORE</a> </h4> </h4>
                <h5 style="margin-top:5px;"> CNN.COM </h5>
            </div>
            </div>
            
            <div class="ind_debate"> <a href="#"> DEBATE </a> </div>
            <div class="border"></div>
            <div class="clear"></div>
        </section><section class="article_content">
        	<div class="ind_question content">
            	<h1> " Could things have panned out differently for Sara and Faigy if they had sought a professional therapist rather than the family's amateur? " </h1>
            </div>
            <div class="ind_photo">
<a class="popup-external-iframe" href="http://www.saffery.com"><img class="b-lazy" src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
data-src="images/article4.jpg"
alt="Article 4"/></a>
<div class="ind_title_article">
<div class="ind_article_bg"></div>
<h4> - Amanda Vanstone lashes out at Bindi Irwin: 'She's not the only kid whose father has died' - <a class="popup-external-iframe" href="http://www.mygov.au">READ MORE</a> </h4>
                <h5 style="margin-top:5px;"> CNN.COM </h5>
            </div>
            </div>
            
            <div class="ind_debate"> <a href="#"> DEBATE </a> </div>
            <div class="border"></div>
            <div class="clear"></div>
        </section>
        
        
<?php } ?>        
        
        
        
        
        
        
        
        
        
        
    
<!--FOOTER-->         
        <footer>


            <div class="box_footer">
                <ul class="social-media-icons">
                   <li><a href="#"><i class="fa fa-facebook fa-1x"></i></a></li>
                   <li><a href="#"><i class="fa fa-twitter fa-1x"></i></a></li>
                   <li><a href="#"><i class="fa fa-instagram fa-1x"></i></a></li>
                </ul>



<div id="most-viewed-imp"><ul>
<li><a href="#">MOST VIEWED</a></li>
<li>-</li>
<li><a href="#">MOST DEBATED</a></li>
<li>-</li>
<li><a href="#">PRIVACY POLICY</a></li>
<li>-</li>
<li><a href="#">TERMS OF USE</a></li>
</ul></div>
                                <div class="copyright">
                                <h6>Copyright 2016 &copy; NEWSLOGUE - All rights reserved.</h6>
                                </div>
            </div>
        </footer>
    
    </div>
    
<!--<div class="loading"> <span></span> </div>-->
    
<div id='index_click_blocker' class="click_blocker"></div>
<script>
	var selectedCategoryID = <?=$categoryID?>;
</script>

<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/menu.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/lazy-loading-js.js" type="text/javascript"></script>
<script src="<?=CONFIG_PATH::GLOBAL_M_BASE?>js/magnific-popup.js"></script>
<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>/js/ng-newslogue.js"></script>


</body>
</html>
