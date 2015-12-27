<?php
    
    include "../config.php";
    

    if(@$_REQUEST["send"] == "SUBMIT")
    {
        $notificationMsg = $notificationClass = "";
        $errorArray = array();
        $errorArray = $esubscriber->ValidateForm($_POST);
               
        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";
             
            $notificationClass = "error";
            
            echo "false<==>".$notificationMsg;
        }
        else
        {
            $esubscriberRstArray = $esubscriber->AddDetails(@$_POST);
            
            if($esubscriberRstArray)
            {           
                echo "true<==>Thank you for subcribing to us.";
            }    
            else
                echo "false<==>Invalid details.";
        }
        
    }
?>


