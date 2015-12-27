<?php
    
    include "../config.php";
    

    if(@$_REQUEST["send"] == "SUBMIT")
    {
        $notificationMsg = $notificationClass = "";
        $errorArray = array();
        $errorArray = $user->ValidateForgotForm($_POST);
               
        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";
             
            $notificationClass = "error";
            
            echo "false<==>".$notificationMsg;
        }
        else
        {
            $loginRstArray = $user->ForgotPassword(@$_POST);
            
            if($loginRstArray)
            {
                
                echo "true<==>An email has been sent to your email address.";
            }    
            else
                echo "false<==>Invalid details.";
        }
        
    }
?>


