<?php
    
    include "../config.php";
    
    $userID = (@$_SESSION["userID"]>0?$_SESSION["userID"]:$user->getAnonymousID());

    if ($userID < 0) $user->createAnonymousUser($userID);
    
	if ($userID != 0) {
	    if(@$_REQUEST["send"] == "SUBMIT")
	    {
	        $notificationMsg = $notificationClass = "";
	        $errorArray = array();
	        $errorArray = $news->ValidateReplyForm($_POST);
	        
	               
	        if(is_array($errorArray) && count($errorArray))
	        {
	            foreach($errorArray as $errID => $errValue)
	                $notificationMsg .= $errValue."<br />";
	             
	            $notificationClass = "error";
	            
	            echo "false<==>".$notificationMsg;
	        }
	        else
	        {
	            $esubscriberRstArray = $news->Reply(@$_POST);
	            
	            if($esubscriberRstArray)
	            {
	                
	                echo "true<==>Reply is successful.";
	            }    
	            else
	                echo "false<==>Invalid details.";
	        }
	        
	    }
	}
?>


