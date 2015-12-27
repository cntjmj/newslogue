<?php 
    include_once "config.php";

    $module_name = (@$_GET["m"] == "")? "news":$_GET["m"];
    
    $interface_array = array(
            "news",
            "category",
            "fastfeed",
            "user",
            "reply",
            "debate",
            "login"
        );
    
    if(!in_array($module_name,$interface_array) || !file_exists("interface/".$module_name.".php"))
        $module_name = "error";
    
        
    if(@$_SESSION["adminID"] == '' && $module_name != "login" )
    {
        $module_name = "login";
        
    }
    
 

    include_once "header.php";
    include_once "interface/".$module_name.".php";
    include_once "footer.php";
?>