<?php
	require_once '../class/nl-news-class.php';

	$newsID = $_GET["newsID"];

	$newsObj = new News($newsID);
	$newsRst = $newsObj->getArray();
	$news = $newsRst["news"];
?>



<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<link rel="shortcut icon" href="http://www.newslogue.com/favicon.ico" type="image/x-icon" />
<script src="js/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="css/style.css">	
<link rel="stylesheet" href="css/menu.css">
<link rel="stylesheet" href="css/responsiveslides.css">
<link rel="stylesheet" href="css/gfont.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<title>demo</title>
<script>
$(document).on("click", "#demoit", function(){
	$("#newsframe").attr("src", "http://<?=$news["newsSource"]?>");
	$("#loading").css({"display":"block"});
	$("#framediv").css({"display":"block"});
	$("#blocker").css({"display":"block"});
	$("#closeit").css({"display":"block"});
});
$(document).on("click", "#closeit", function(){
	$("#framediv").css({"display":"none"});
	$("#loading").css({"display":"none"});
	$("#blocker").css({"display":"none"});
	$("#closeit").css({"display":"none"});
});
//$(document).mouseup(function(e){
//});
</script>
</head>
<body>
<h1 style="font-size:40px;text-align:center">NEWSLOG DEBATE</h1>
<br><br>
<div class="article" style="border: solid 5px greenyellow">
<a href="#" id="demoit">
					<h1><?=$news["newsTitle"]?></h1>
					<div class="photo_article">
						<img src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>uploads/banner/thumbnail/<?=$news["newsBanner"]?>">
					</div>
					<div class="question_bar">
						<div class="question_article">
                    		<h1> "<?=$news["newsQuestion"]?>" </h1>
                		</div>
             			<div class="clear"></div>
        			</div>
            		</a>
        		</div>
<br>
<br>
<br>
            		<div>
<?=$news["newsContent"]?>
             		</div>


<div id="loading" class="pop-up" style="background-color:RGBA(0,0,0, 0.8);height:100%;width:100%;border:5px solid #fff054;position:absolute;left:0;top:0;display:none;z-index:2;padding-top:200px;">
<p style="font-size:24px; text-align:center; font-family:Impact, Haettenschweiler, 'Franklin Gothic Bold', 'Arial Black', sans-serif; color:#fff;"> LOADING </p>
</div>
<div id="framediv" class="pop-up" style="height:100%;width:100%;border:5px solid #fff054;position:absolute;left:0;top:0;display:none;z-index:3">
<iframe id="newsframe" style="height:100%;width:100%;">
</iframe>
</div>
<div id="blocker" style="height:100%;width:100%;border:0p;position:absolute;left:0;top:0;display:none;z-index:4;">
</div>
<div id="closeit" style="height:40px;width:40px;position:absolute;right:0;top:0;z-index:5;display:none">
<a href="#"></a><h1 style="font:40px red;background:yellow">X</h1></a>
</div>
</body>
</html>