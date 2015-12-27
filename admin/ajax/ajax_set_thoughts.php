<?php
    include_once "../config.php";

    $action = $admin_database->cleanXSS(@$_POST["action"]);
    $newsID = $admin_database->cleanXSS(@$_POST["newsID"],"int");
    $thoughtsID = $admin_database->cleanXSS(@$_POST["thoughtsID"],"int");
    if(isset($_POST))
    {
        if($action == "setThought")
        {
            $thoughtsRst = $admin_news->SetThoughts($_POST,$thoughtsID);
            
            if($thoughtsRst)
            {
                echo "true";
            }
            else
            {
                echo "false";
            }
        }
        else if($action == "AddNewThoughts")
        {
            $thoughtsRst = $admin_news->AddThoughts($_POST);
            
            if($thoughtsRst)
            {
                echo "true";
            }
            else
            {
                echo "false";
            }
        }
    }
    
?>