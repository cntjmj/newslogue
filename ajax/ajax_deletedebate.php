<?php
    
    include "../config.php";
    

    if(@$_SESSION["userID"] > 0)
    {
        $replyID = $database->cleanXSS($_POST["replyID"],"int");

        $variable = array();
        $nowDateTime = date("Y-m-d H:i:s");
        $qry = "select 1 from news_reply where userID = ? and replyID = ?";
        
        $variable[] = array("i", @$_SESSION["userID"]);
        $variable[] = array("i", $replyID);
        
        $result = $database->query("select",$qry,$connection,$variable);

        if(is_array($result) && count($result) > 0)
        {
            $variable = array();
            $nowDateTime = date("Y-m-d H:i:s");
            $qry = "update news_reply set  replyStatus = 'deleted' where userID = ? and replyID = ?";
            $variable[] = array("i", @$_SESSION["userID"]);
            $variable[] = array("i", $replyID);
        
            
            $result = $database->query("delete",$qry,$connection,$variable);        
            echo "true<==>";
        }
        else
        {
            echo "false<==>";
        }
        
    }
    
    
    
?>


