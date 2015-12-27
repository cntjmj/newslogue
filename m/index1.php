<?php 
	require_once "../nl-init.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<link rel="shortcut icon" href="http://www.newslogue.com/favicon.ico" type="image/x-icon" />

<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css">
<script src="js/jquery-1.11.1.min.js"></script>
<script>
$(document).on("mobileinit", function() {
	$.support.cors = true;
	$.mobile.allowCrossDomainPages = true;
	$.mobile.pushState = false;
	$.mobile.changePage.defaults.changeHash = false;
	$.mobile.hashListeningEnabled = false;
	$.mobile.pushStateEnabled = false;
});
</script>

<script src="js/jquery.mobile-1.4.5.min.js"></script>

<link rel="stylesheet" href="css/style.css">	
<link rel="stylesheet" href="css/menu.css">
<link rel="stylesheet" href="css/responsiveslides.css">
<link rel="stylesheet" href="css/gfont.css">
<link rel="stylesheet" href="css/font-awesome.min.css">

<?php
	/*
	if (isTestEnv())
		echo '<script src="http://www.nl.com/js/nl-common.js"></script>';
	else
		echo '<script src="http://www.newslogue.com/js/nl-common.js"></script>';
	*/
	echo '<script src="'. CONFIG_PATH::GLOBAL_WWW_BASE .'js/nl-common.js"></script>';
?>
</head>
<body>

<div data-role="page" id="pageone">
  <div data-role="header" data-theme="b"  data-position="fixed">
    <a href="" data-role="button" data-icon="home">Home</a>
    <h1>Newslogue Home</h1>
    <a href="#pagetwo" data-rel="dialog" data-role="button" data-icon="user">Login</a>
  </div>
  <div data-role="content">
    <div id="nl-home-container">
    </div>
    <div id="nl-home-loadmore">
        <section style="border-bottom:none;">
          <div class="loadmore">
              <a id="nl-home-loadmore-link" href="#">  LOAD MORE ARTICLES  </a>
          </div>
      </section>
    </div>
  </div>

  <div data-role="footer" data-theme="b"  data-position="fixed">
    <div data-role="navbar">
      <ul>
        <li><a href="#" data-rel="dialog" data-icon="action" data-theme="b"></a></li>
        <li><a href="#" data-rel="dialog" data-icon="grid" data-theme="b"></a></li>
        <li><a href="#" data-rel="dialog" data-icon="info" data-theme="b"></a></li>
        <li><a href="#" data-rel="dialog" data-icon="comment" data-theme="b"></a></li>
        <li><a href="#pagethree" data-rel="dialog" data-transition="pop" data-icon="search" data-theme="b"></a></li>
      </ul>
    </div>
  </div>
  <!--div data-role="footer" data-theme="b"  data-position="fixed">
  <h1>newslogue.com</h1>
  </div-->
</div> 

<div data-role="page" id="pagetwo">
  <div data-role="header" data-theme="b">
    <h1>Login Dialog</h1>
  </div>

  <div data-role="content" data-theme="b">
    <!--p>Login Form...</p-->
    <div data-role="fieldcontain">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email">
      <label for="pwd">Password:</label>
      <input type="password" name="pwd" id="pwd">
      <label for="switch">Remember me:</label>
      <select name="switch" id="switch" data-role="slider">
        <option value="on">On</option>
        <option value="off" selected>Off</option>
      </select>
    </div>
    <a href="#pageone" data-role="button" data-inline="true" data-icon="back">Back</a>
    <a href="" data-role="button" data-inline="true" data-icon="arrow-u">Login</a>
  </div>

  <div data-role="footer" data-theme="b">
  <h1>newslogue.com</h1>
  </div>
</div> 

<div data-role="dialog" id="pagethree">
  <div data-role="header" data-theme="b">
    <h1>Dialog</h1>
  </div>

  <div data-role="content" data-theme="b">
    <p>Just A Demo</p>
    <a href="#pageone" data-role="button" data-inline="true" data-icon="back">Back</a>
  </div>

  <div data-role="footer" data-theme="b">
  <h1>newslogue.com</h1>
  </div>
</div> 

<script>
	var apiNewsUrl = CONFIG.GLOBAL_API_BASE + "news.php";
	var num_per_page = CONSTS.DEFAULT_NEWS_NUM_PER_PAGE;
	var page_num = 0;

	$(document).on("pageinit","#pageone", loadNewsList);

	$(document).on("click", "#nl-home-loadmore-link", loadNewsList);

	function loadNewsList() {
		jqAjaxGet(apiNewsUrl, {
			num_per_page: num_per_page,
			page_num: page_num,
			category: "",
			summary_len: 0
			}, parseNewsMeta);
	}

	function parseNewsMeta(jsonMeta) {
		var meta = JSON.parse(jsonMeta);
		var htmlList = "";

		if (meta.newsMetaList != 'undefined' && meta.newsMetaList.length > 0) {
			for (var i=0; i<meta.newsMetaList.length; i++) {
				htmlList += parseOneNews(meta.newsMetaList[i]);
			}
		}

		if (htmlList != "") {
			page_num++;
			//alert(htmlList);
			$("#nl-home-container").append(htmlList);
		}
	}

	function parseOneNews(newsMeta) {
		//alert(newsMeta.newsID);
		var htmlNews = '\n\
			<section>\n\
				<div class="article">\n\
<a href="newsdemo.php?newsID=' + newsMeta.newsID + '" target="_blank">\n\
					<h1>' + newsMeta.newsTitle + '</h1>\n\
					<div class="photo_article">\n\
						<img src="' + CONFIG.GLOBAL_THUMBNAIL_BASE + newsMeta.newsBanner + '">\n\
					</div>\n\
					<div class="question_bar">\n\
						<div class="question_article">\n\
                    		<h1> "' + newsMeta.newsQuestion + '" </h1>\n\
                		</div>\n\
             			<div class="clear"></div>\n\
        			</div>\n\
            		<div class="question_btn">\n\
                		<ul>\n\
                   			<li><a href="#"> DEBATE </a></li>\n\
                		</ul>\n\
             		</div>\n\
             		<div class="clear"> </div>\n\
</a>\n\
        		</div>\n\
    		</section>';

    	return htmlNews;
	}
</script>

</body>
</html>
