<?php
	require_once __DIR__."/../nl-config.php";
    session_start();

//    PHP DEGUBING
//    require_once('FirePHPCore/FirePHP.class.php');
//    $firephp = FirePHP::getInstance(true);
    require_once ('ImageManipulator.php');

//    require_once('FirePHPCore/fb.php');
//    ob_start();

    //$whitelist = array('localhost','www.nl.com', '127.0.0.1');

    //if(!in_array($_SERVER['HTTP_HOST'], $whitelist))
    if (isProductEnv())
    {
        $GLOBAL_WEB_ROOT                = "http://". $_SERVER['HTTP_HOST'] . "/";
        $connection = new mysqli('localhost', 'newslogu_sizzo', 'newslogue!123', 'newslogu_v1');
    }
    else
    {

        $GLOBAL_WEB_ROOT                = CONFIG_PATH::GLOBAL_WWW_BASE; //"http://www.nl.com/";
        $connection = new mysqli(CONFIG_DB::HOSTNAME, CONFIG_DB::USERNAME, CONFIG_DB::PASSWORD, CONFIG_DB::INSTNAME);
        
        error_reporting(-1);
    	ini_set("display_errors", 1);
    }
    $connection->set_charset("utf8");
    
    
    
    $GLOBAL_CURRENCY_SIGN          = "USD";
    $GLOBAL_ADMIN_WEB_ROOT          = $GLOBAL_WEB_ROOT."admin/";
    $GLOBAL_ADMIN_PROCESS           = $GLOBAL_ADMIN_WEB_ROOT."process/";
//    $GLOBAL_ADMIN_SITE_URL          = $GLOBAL_ADMIN_WEB_ROOT."index.php?m=";
    $GLOBAL_ADMIN_SITE_URL          = $GLOBAL_ADMIN_WEB_ROOT."newslogue8999.php?m=";    
	$GLOBAL_ADMIN_GRAPHIC           = $GLOBAL_ADMIN_WEB_ROOT."graphic/";
	$GLOBAL_ADMIN_JS                = $GLOBAL_ADMIN_WEB_ROOT."js/";
	$GLOBAL_ADMIN_STYLE             = $GLOBAL_ADMIN_WEB_ROOT."style/";
    $GLOBAL_ADMIN_AJAX              = $GLOBAL_ADMIN_WEB_ROOT."ajax/";
    $GLOBAL_ITEM_PERPAGE            = 20;
    $GLOBAL_PRODUCT_TNB 			= $GLOBAL_WEB_ROOT."upload/product/thumbnail/";
    $GLOBAL_ADMIN_POPUP 			= $GLOBAL_ADMIN_WEB_ROOT."popup/";
    
    
    
    $adminClass_arr = array("database_","account_","image_resize_","user_","news_","category_","fastfeed_","reply_");
    
    foreach($adminClass_arr as $classVal){
        include_once "class/".$classVal.".php";    
    }
    include_once "common/function.php";
    
    $admin_database = new AdminDatabase();
    $admin_account = new AdminAccount();
    $admin_image = new ImgSizer();
    $admin_user = new AdminUser();
    $admin_news = new AdminNews();
    $admin_category = new AdminCategory();
    $admin_fastfeed = new AdminFastFeed();
    $admin_reply = new AdminReply();
    
    
    
    
    
    
    
    
?>