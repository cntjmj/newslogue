<?php
    
    include "../config.php";
    

    if(@$_REQUEST["send"] == "SUBMIT")
    {
        $notificationMsg = $notificationClass = "";
        $errorArray = array();
        $errorArray = $user->ValidateChangeName($_POST);
        
               
        if(is_array($errorArray) && count($errorArray))
        {
            foreach($errorArray as $errID => $errValue)
                $notificationMsg .= $errValue."<br />";
             
            $notificationClass = "error";
            
            echo "false<==>".$notificationMsg;
        }
        else
        {
            $nameRstArr = $user->ChangeName(@$_POST,$_SESSION["userID"]);
            
            if($nameRstArr)
            {
                echo "true<==>Name is changed successfully.";
            }    
            else
                echo "false<==>Invalid details.";
        }
        
    }
?>


