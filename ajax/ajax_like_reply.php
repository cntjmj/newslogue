<?php
    
    include "../config.php";
    $userID = (@$_SESSION["userID"]>0?$_SESSION["userID"]:$user->getAnonymousID());
    
    if ($userID < 0) $user->createAnonymousUser($userID);

    if($userID != 0)
    {
        $replyID = $database->cleanXSS($_POST["replyID"],"int");
        $newsID = $database->cleanXSS($_POST["newsID"],"int");

        $variable = array();
        $nowDateTime = date("Y-m-d H:i:s");
        $qry = "select 1 from news_reply where replyType='like' and userID = ? and parentReplyID = ?";
        
        $variable[] = array("i", $userID);
        $variable[] = array("i", $replyID);
        
        $result = $database->query("select",$qry,$connection,$variable);

        if(is_array($result) && count($result) > 0)
        {
            $variable = array();
            $nowDateTime = date("Y-m-d H:i:s");
            $qry = "delete from news_reply where replyType='like' and parentReplyID = ? and userID = ?";
            $variable[] = array("i", $replyID);
            $variable[] = array("i", $userID);
            
            $result = $database->query("delete",$qry,$connection,$variable);        
            echo "removed<==>";
        }
        else
        {
            $variable = array();
            $nowDateTime = date("Y-m-d H:i:s");
            $qry = "insert into news_reply (parentReplyID, newsID, userID, replyStatement, 
			replyContent, replyType, replyStatus, createdDateTime, updatedDateTime)
			values (?, ?, ?, '', '', 'like', 'active', ?, ?)";
			// replyType,replyStatus,parentReplyID,userID,createdDateTime,updatedDateTime) values (?,?,?,?)";

            $variable[] = array("i", $replyID);
            $variable[] = array("i", $newsID);
            $variable[] = array("i", $userID);
            $variable[] = array("s",$nowDateTime);
            $variable[] = array("s",$nowDateTime);
            
            $result = $database->query("insert",$qry,$connection,$variable);        
            echo "true<==>";    
      


            
        }
        
    }
    
    
    
?>


