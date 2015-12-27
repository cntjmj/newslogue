<?php
    
    include "../config.php";
    

    if(@$_REQUEST["send"] == "SUBMIT")
    {
        $notificationMsg = $notificationClass = "";
        $errorArray = array();
        $errorArray = $user->ValidateForm($_POST);

        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";
             
            $notificationClass = "error";
            
            echo "false<==>".$notificationMsg;
        }
        else
        {
//            $loginRstArray = $user->AddDetails(@$_POST);
            $loginRstArray = $user->AddDetails($_POST);

            if($loginRstArray)
            {
                
                echo "true<==>Registration is successful.";
            }    
            else
                echo "false<==>Invalid details.";
        }
        
    }
?>


