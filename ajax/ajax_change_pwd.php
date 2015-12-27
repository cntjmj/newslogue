<?php
    
    include "../config.php";
    

    if(@$_REQUEST["send"] == "SUBMIT")
    {
        $notificationMsg = $notificationClass = "";
        $errorArray = array();
        $errorArray = $user->ValidatePwdForm($_POST);
        
               
        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";
             
            $notificationClass = "error";
            
            echo "false<==>".$notificationMsg;
        }
        else
        {
            $nameRstArr = $user->ChangePwd(@$_POST);
            
            if($nameRstArr)
            {
                echo "true<==>Password is changed successfully.";
            }    
            else
                echo "false<==>Invalid details.";
        }
        
    }
?>


