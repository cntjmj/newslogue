<?php
	//require_once dirname(__FILE__).'/nl-config.php';
	require_once dirname(__FILE__).'/nl-init.php';
	require_once dirname(__FILE__).'/common/nl-common.php';
	require_once dirname(__FILE__).'/class/nl-auth-class.php';
    //session_start();

    // PHP Debugging through Firefox
//    require_once('FirePHPCore/FirePHP.class.php');
//    $firephp = FirePHP::getInstance(true);
//    require_once('FirePHPCore/fb.php');
//    ob_start();

    //$GLOBAL_DATETIME = "Asia/Kuala_Lumpur";
    $GLOBAL_DATETIME = "Australia/Melbourne";
	date_default_timezone_set($GLOBAL_DATETIME);

    $class_arr = array("image_resize_","class.phpmailer_","database_","user_","news_","esubscriber_","category_","fastfeed_");

    
    
    foreach($class_arr as $classVal)
        include_once "class/".$classVal.".php";
    
    
    
    $database = new Database();
    $mailer = new PHPMailer();
    $image = new ImgSizer();
    $user = new User();
    $news = new News();
    $esubscriber = new ESubscriber();
    $category = new Category();
    $fastfeed = new FastFeed();
    

    include_once "common/function.php";
    
    $whitelist = array('localhost', 'www.nl.com', 'm.nl.com',"api.nl.com");

    if(!in_array($_SERVER['HTTP_HOST'], $whitelist))
    {
        $GLOBAL_WEB_ROOT                = "http://". $_SERVER['HTTP_HOST'] ."/";
    
        $connection = new mysqli('localhost', 'newslogu_sizzo', 'newslogue!123', 'newslogu_v1');
    }
    else
    {
        
        //$GLOBAL_WEB_ROOT                = "http://localhost/newslogue/";
        $GLOBAL_WEB_ROOT                = CONFIG_PATH::GLOBAL_WWW_BASE;
        
        $database->databaseType = "mysqli";
        
        $connection = new mysqli('127.0.0.1', 'root', 'passw0rd', 'newslogu_v1');
        
        
        
        error_reporting(-1);
        ini_set("display_errors", 1);
		
    }
    
    
	$GLOBAL_POPUP                 = $GLOBAL_WEB_ROOT."popup/";
    $GLOBAL_PROCESS                 = $GLOBAL_WEB_ROOT."process/";
    $GLOBAL_SITE_URL                = $GLOBAL_WEB_ROOT."index.php?m=";
	$GLOBAL_GRAPHIC                 = $GLOBAL_WEB_ROOT."graphic/";
	$GLOBAL_JS                      = $GLOBAL_WEB_ROOT."js/";
	$GLOBAL_STYLE                   = $GLOBAL_WEB_ROOT."css/";
    $GLOBAL_AJAX                    = $GLOBAL_WEB_ROOT."ajax/";
    $GLOBAL_ITEM_PERPAGE            = 20;
    $GLOBAL_PRODUCT_TNB 			= $GLOBAL_WEB_ROOT."product/thumbnail/";
    $GLOBAL_POPUP 			        = $GLOBAL_WEB_ROOT."popup/";
    
    $GLOBAL_ALLOW_ANONYMOUS         = CONFIG::ALLOW_ANONYMOUS;
	
	if ($GLOBAL_ALLOW_ANONYMOUS && !isset($_SESSION["userID"])) {
/*
		if (!isset($_COOKIE["tempID"])) {
    		$uniqID = uniqid();
			//$anonymousID = "-".substr(hexdec($uniqID),7);
			//$user->createAnonymousUser($anonymousID);
    		//setcookie("tempID",$uniqID, time()+365*24*60*60);
    		set_cookie("tempID",$uniqID);
    	}
*/
$auth = Auth::getInstance();
if (isset($_COOKIE["tempID"])) {
$tid = $_COOKIE["tempID"];
setcookie("tempID", $tid, time() - 3600);
set_cookie("tempID",$tid);
}
/*
    	if (!isset($_SESSION["displayName"])) {
    		$_SESSION["displayName"] = "Anonymous User";
    	}
*/
    }
?>