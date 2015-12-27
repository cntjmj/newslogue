<?php
    include_once "../config.php";

    $action = $admin_database->cleanXSS(@$_POST["action"]);
    $thoughtsID = $admin_database->cleanXSS(@$_POST["thoughtsID"],"int");
    
    if($action == "DeleteThoughts")
    {
            
        $thoughtsRstArray = $admin_news->DeleteThoughts($thoughtsID);
        
        echo "true";
    }
    

?>