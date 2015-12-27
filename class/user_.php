<?php
    class User{

        public function ValidateForm($postVar,$action = "Add")
        {
            global $connection, $database;
           
            $displayName = $database->cleanXSS(@$postVar["displayName"]);
            $fullname = $database->cleanXSS(@$postVar["fullname"]);
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $pwd = $database->cleanXSS(@$postVar["pwd"]);
            
            $errorArray = array();
            
            $variable = array();
            $qry = "select * from user_registration where emailaddress = ? or displayName = ?";
            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", $displayName);
            $result = $database->query("select",$qry,$connection,$variable);


            if($fullname == "")
                $errorArray[] = "Name is required.";
            if($displayName == "")
                $errorArray[] = "Display Name is required.";
            
            if($emailaddress == "")
                $errorArray[] = "Email Address is required.";
            if(is_array($result) && count($result) > 0)
            {
                if($result[0]["emailaddress"] == $emailaddress && $emailaddress != "")
                    $errorArray[] = "Email Address is already registered. Please try another.";
                if($result[0]["displayName"] == $displayName && $displayName != "")
                    $errorArray[] = "Display Name is already registered. Please try another.";
            }
            if($pwd == "")
                $errorArray[] = "Password is required.";
            return $errorArray;
        }

        public function ValidateFBForm($postVar,$action = "Add")
        {
            global $connection, $database; 

            $fbName = $database->cleanXSS(@$postVar["fbName"]);
            $fbEmail = $database->cleanXSS(@$postVar["fbEmail"]);
            $fbID = $database->cleanXSS(@$postVar["fbID"]);

            if($fbName == "")
                $errorArray[] = "Facebook Name is required.";
            if($fbEmail == "")
                $errorArray[] = "Facebook Email is required.";
            if($fbID == "")
                $errorArray[] = "Facebook ID is required.";
        }

        public function ValidateForgotForm($postVar,$action = "Add")
        {
            global $connection, $database;
           
            
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $errorArray = array();
            
            $variable = array();
            $qry = "select * from user_registration where emailaddress = ?";
            $variable[] = array("s", $emailaddress);
            $result = $database->query("select",$qry,$connection,$variable);


            if($emailaddress == "")
                $errorArray[] = "Email Address is required.";
            if(is_array($result) && count($result) == 0)
            {
                $errorArray[] = "The Email Address is invalid.";
            }
            


            return $errorArray;
        }
        
        public function ValidateEmail($postVar,$action = "Add")
        {
            global $connection, $database;

            $errorArray = array();
            $newemail = $database->cleanXSS(@$postVar["newemail"]);
            $confirmnewemail = $database->cleanXSS(@$postVar["confirmnewemail"]);
            
            $variable = array();
            
            $qry = "select * from user_registration where emailaddress = ? and userID != ?";
            $variable[] = array("s", $newemail);
            $variable[] = array("s", $_SESSION["userID"]);
            $result = $database->query("select",$qry,$connection,$variable);   
            
            if($newemail== "")
                $errorArray[] = "New Email Address field is required.";    
            if(is_array($result)  && count($result) > 0)
                $errorArray[] = "The email address is already registered by another user. Please try another.";    
            if($confirmnewemail== "")
                $errorArray[] = "Confirm New Email Address field is required.";    
            if($confirmnewemail== $newemail)
                $errorArray[] = "New Email Address field must match Confirm New Email Address field.";    
            
            return $errorArray;
        }


        public function ValidateChangeName($postVar,$action = "Add")
        {
            global $connection, $database;

            $errorArray = array();
            $displayName = $database->cleanXSS(@$postVar["displayName"]);
            $fullname = $database->cleanXSS(@$postVar["fullname"]);
            
            $variable = array();
            $qry = "select * from user_registration where displayName = ?";
            $variable[] = array("s", $displayName);
            $result = $database->query("select",$qry,$connection,$variable);


            if($displayName== "")
                $errorArray[] = "Display Name field is required.";    
            else if(is_array($result) && count($result) > 0)
            {
                $errorArray[] = "Display Name field is taken. Please try another.";    
            }
            if($fullname== "")
                $errorArray[] = "Full Name field is required.";    
            
            
            return $errorArray;
        }

        public function ValidateProfilePictureForm($postVar,$action = "Add")
        {
            global $connection, $database;

            $errorArray = array();
            $userProfilePicture = @$_FILES["userProfilePicture"]["name"];
            if($userProfilePicture != ""){
                $explodedMainImageArray = explode('.', $userProfilePicture);
                $ext = strtolower(end($explodedMainImageArray));
                
                if($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif"){
                    $errorArray[] = "Please upload image that has only these extensions jpg,png,jpeg or gif.";    
                }
                
                if($_FILES["userProfilePicture"]["size"] > 2097152)
                    $errorArray[] = "Please upload image that has maximum file size of 2MB.";    

            }
            if($userProfilePicture== "")
                $errorArray[] = "Profile Picture field is required.";    
            
            
            return $errorArray;
        }

        
        public function ValidateNameForm($postVar,$action = "Add")
        {
            global $connection, $database;

            $errorArray = array();
            $fullname = $database->cleanXSS(@$postVar["fullname"]);
            if($fullname == "")
                $errorArray[] = "Name field is required.";    
            
            
            return $errorArray;
        }

        public function ValidatePwdForm($postVar,$action = "Add")
        {
            global $connection, $database;

            $errorArray = array();
            $oldpwd = $database->cleanXSS(@$postVar["oldpwd"]);
            $newpwd = $database->cleanXSS(@$postVar["newpwd"]);
            $confirmnewpwd = $database->cleanXSS(@$postVar["confirmnewpwd"]);
            if($oldpwd == "" )
                $errorArray[] = "Old Password field is required.";    
            if($newpwd == "" )
                $errorArray[] = "New Password field is required.";    
            if($confirmnewpwd == "" )
                $errorArray[] = "Confirm New Password field is required.";    
            if($newpwd != $confirmnewpwd)
                $errorArray[] = "New Password field and Confirm New Password must match.";    

            if(count($errorArray) == 0)
            {
                $variable = array();
                $qry = "select * from user_registration where emailaddress = ? and pwd =? and userStatus='active'";
                $variable[] = array("s", $_SESSION["emailaddress"]);
                $variable[] = array("s", sha1($oldpwd));
                $result = $database->query("select",$qry,$connection,$variable);

                if(is_array($result) && count($result) == 0)
                    $errorArray[] = "Old Password provided is incorrect.";                        

            }
            
            
            return $errorArray;
        }
        


        public function ValidateLoginForm($postVar,$action = "Add")
        {
            global $connection, $database;
           
            
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $pwd = $database->cleanXSS(@$postVar["pwd"]);
            
            $errorArray = array();
            
            if($emailaddress == "")
                $errorArray[] = "Mobile No. is required.";
            if($pwd == "")
                $errorArray[] = "Email is required.";
            return $errorArray;
        }
        
        public function Login($postVar){
            global $connection, $database;
            
            
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $pwd = $database->cleanXSS(@$postVar["pwd"]);
            
            $nowDateTime = date("Y-m-d H:i:s");
            
            
            
        
            unset($variable);
            $qry = "select * from user_registration where emailaddress = ? and pwd =? and userStatus='active'";

            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", sha1($pwd));
            $result = $database->query("select",$qry,$connection,$variable);
            
            
            if(is_array($result) && count($result) > 0){
                $_SESSION["userID"] = $result[0]["userID"];
                $_SESSION["fullname"] = $result[0]["fullname"];
                $_SESSION["emailaddress"] = $result[0]["emailaddress"];
                $_SESSION["displayName"] = $result[0]["displayName"];
                return $result;   

            }
            else
                return false;
        }

        public function ChangeName($postVar,$userID)
        {
            global $connection, $database,$GLOBAL_WEB_ROOT;
            
            
            $displayName = $database->cleanXSS(@$postVar["displayName"]);
            $fullname = $database->cleanXSS(@$postVar["fullname"]);
            $nowDateTime = date("Y-m-d H:i:s");
            $variable = array();
            $qry = "update user_registration set displayName = ? , fullname = ? where userID = ?";
            $variable[] = array("s", $displayName);
            $variable[] = array("s", $fullname);
            $variable[] = array("s", $userID);
            $userRstArr = $database->query("update",$qry,$connection,$variable);

            if($userRstArr > 0){
                $_SESSION["fullname"] = $fullname;
                $_SESSION["displayName"] = $displayName;
                return $userRstArr;   
            }
            else
                return false;
            
        }

        public function ChangeEmail($postVar,$userID)
        {
            global $connection, $database,$GLOBAL_WEB_ROOT;
            
            
            $emailaddress = $database->cleanXSS(@$postVar["newemail"]);
            
            $nowDateTime = date("Y-m-d H:i:s");
            $variable = array();
            $qry = "update user_registration set emailaddress = ?  where userID = ?";
            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", $userID);
            $userRstArr = $database->query("update",$qry,$connection,$variable);

            if($userRstArr > 0){
                $_SESSION["emailaddress"] = $emailaddress;
                return $userRstArr;   
            }
            else
                return false;
            
        }


        public function ForgotPassword($postVar){
            global $connection, $database,$GLOBAL_WEB_ROOT ,$mailer;
            
            
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $nowDateTime = date("Y-m-d H:i:s");
            
            


            
            $variable = array();
            $qry = "select * from user_registration where emailaddress = ?";
            $variable[] = array("s", $emailaddress);
            $userRstArr = $database->query("select",$qry,$connection,$variable);
            $uniqueCode = createRandomCode();
            $password = createRandomCode().uniqid();
            

            $variable = array();
            $qry = "update forgot_password set forgotStatus = 'expire' where userID = ? and forgotStatus = 'active'";
            $variable[] = array("s", $userRstArr[0]["userID"]);
            $result2 = $database->query("update",$qry,$connection,$variable);

            $variable = array();
            $qry = "insert into forgot_password (userID,forgotUniqID,newPassword,forgotStatus,createdDateTime,updatedDateTime) values (?,?,?,?,?,?)";
            $variable[] = array("s", $userRstArr[0]["userID"]);
            $variable[] = array("s", $uniqueCode);
            $variable[] = array("s", sha1($password));
            $variable[] = array("s", "active");
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $nowDateTime);
            $result = $database->query("insert",$qry,$connection,$variable);
            
            
            if($result> 0)
            {
                $html = 
                '
                <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
                    <tr>
                        <td>
                            <div style="margin-bottom:20px;">Hi '.$userRstArr[0]["fullname"].'!</div>
                            <div style="">
                                You have reset your password to "'.$password.'" in our site at Newslogue. Please click on the link below to verify your action.
                            </div>
                            <div>
                                <a href="'.$GLOBAL_WEB_ROOT.'resetpwd.php?c='.$uniqueCode.'&emailaddress='.$emailaddress.'">'.$GLOBAL_WEB_ROOT.'resetpwd .php?c='.$uniqueCode.'&emailaddress='.$emailaddress.'</a>
                            </div>
                            <div style="margin-top: 40px;">
                                <br />
                                The Team at Newslogue
                            
                            </div>
                        </td>
                    </tr>
                </table>
                ';
                
                //$mailer->AddAddress($email, $firstName. " ".$lastName);
                $mailer->AddAddress($emailaddress);
                
                $mailer->SetFrom("verify@newslogue.com", "Newslogue");
                //$mailer->AddReplyTo($employerUsername, $firstName. " ".$lastName);
                
                $mailer->Subject = "Newslogue Password Reset";
                $mailer->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                $mailer->MsgHTML($html);
                $result= $mailer->Send();
                return $result;   

            }
            else
                return false;
        }



        public function GetDetails($userID){
            global $connection,$database;
            $userID = $database->cleanXSS($userID,"int");
            
            
            unset($variable);
            $qry = "select * from user_registration where userID = ?";
            $variable[] = array("i", $userID);
            $result = $database->query("select",$qry,$connection,$variable);
            
            if(is_array($result) && count($result) > 0)
            {
                foreach($result[0] as $id => $value)
                {
                    if(!is_numeric($id))
                    {
                        $returnArray[$id] = $database->cleanData($value);
                    }   
                }
                return $returnArray;   
            }
            else
                return false;
        }


        public function VerifyCode($emailaddress,$uniqCode){
            global $connection,$database;
            $uniqCode = $database->cleanXSS($uniqCode);
            $emailaddress = $database->cleanXSS($emailaddress);
            
            
            unset($variable);
            $qry = "select * from user_registration where emailaddress = ? and uniqCode = ? and userStatus = 'pending'";
            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", $uniqCode);
            $result = $database->query("select",$qry,$connection,$variable);
            
            if(is_array($result) && count($result) > 0)
            {
                foreach($result[0] as $id => $value)
                {
                    if(!is_numeric($id))
                    {
                        $returnArray[$id] = $database->cleanData($value);
                    }   
                }
                return $returnArray;   
            }
            else
                return false;
        }


        public function VerifyForgotLink($emailaddress,$uniqCode){
            global $connection,$database;
            $uniqCode = $database->cleanXSS($uniqCode);
            $emailaddress = $database->cleanXSS($emailaddress);
            
            
            unset($variable);
            $qry = "select * from forgot_password fp inner join user_registration ur on fp.userID = ur.userID where emailaddress = ? and forgotUniqID  = ? and forgotStatus = 'active'";
            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", $uniqCode);

            
            $result = $database->query("select",$qry,$connection,$variable);
            
            if(is_array($result) && count($result) > 0)
            {
                foreach($result[0] as $id => $value)
                {
                    if(!is_numeric($id))
                    {
                        $returnArray[$id] = $database->cleanData($value);
                    }   
                }
                return $returnArray;   
            }
            else
                return false;
        }

        public function ResetPassword($postVar){
            global $connection,$database;
            $userID = $database->cleanXSS(@$postVar["userID"]);
            $uniqCode = $database->cleanXSS(@$postVar["uniqCode"]);
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $nowDateTime = date("Y-m-d H:i:s");
            


            $variable = array();
            $qry = "select * from forgot_password where userID = ? and forgotUniqID = ?";
            $variable[] = array("s", $userID);
            $variable[] = array("s", $uniqCode);
            $forgotRstArr = $database->query("select",$qry,$connection,$variable);

            $variable = array();
            $qry = "update forgot_password set forgotStatus = 'activated', updatedDateTime = ? where forgotUniqID = ?";
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $uniqCode);
            $result = $database->query("update",$qry,$connection,$variable);


            
            $variable = array();
            $qry = "update user_registration set userStatus = 'active', pwd =?, updatedDateTime = ? where userID = ? and emailaddress =? ";
            $variable[] = array("s", $forgotRstArr[0]["newPassword"]);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $userID);
            $variable[] = array("s", $emailaddress);
            $result = $database->query("update",$qry,$connection,$variable);
            
            
            
            if($result > 0){
               

                return $result;   
            }
            else
                return false;
        }
        
        public function AddFBDetails($postVar){
            global $connection, $database;
            
            
                
            $fbName = $database->cleanXSS(@$postVar["fbName"]);
            $fbEmail = $database->cleanXSS(@$postVar["fbEmail"]);
            $fbID = $database->cleanXSS(@$postVar["fbID"]);
            
            $nowDateTime = date("Y-m-d H:i:s");
            

            $variable = array();
            $qry = "select * from user_registration where fbID = ?";
            $variable[] = array("s", $fbID);
            $result = $database->query("select",$qry,$connection,$variable);


            
            if(is_array($result) && count($result) > 0)
            {
                $variable = array();
                $qry = "update user_registration set fbName = ?,fbEmail =?,userStatus=?,updatedDateTime=? where fbID = ?";
                
                $variable[] = array("s", $fbName);
                $variable[] = array("s", $fbEmail);
                $variable[] = array("s", 'active');
                $variable[] = array("s", $nowDateTime);
                $variable[] = array("s", $fbID);
                $result2 = $database->query("update",$qry,$connection,$variable);
                $userID = $result[0]["userID"];
            }
            else
            {
                $variable = array();
                $qry = "insert into user_registration ( fbID,fbName,fbEmail,userStatus,createdDateTime,updatedDateTime) 
                        values (?,?,?,?,?,?)";
                
                $variable[] = array("s", $fbID);
                $variable[] = array("s", $fbName);
                $variable[] = array("s", $fbEmail);
                $variable[] = array("s", 'active');
                $variable[] = array("s", $nowDateTime);
                $variable[] = array("s", $nowDateTime);
                $result2 = $database->query("insert",$qry,$connection,$variable);
                $userID = $result;
            }
            
            
            
            if($result2 > 0){
                
                $_SESSION["userID"] = $userID;
                $_SESSION["fullname"] = $fbName;
                $_SESSION["emailaddress"] = $fbEmail;

                return $result;   
            }
            else
                return false;
        }

        public function AddDetails($postVar){
            global $connection, $database,$mailer,$GLOBAL_WEB_ROOT;

            $displayName = $database->cleanXSS(@$postVar["displayName"]);
            $fullname = $database->cleanXSS(@$postVar["fullname"]);
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $pwd = $database->cleanXSS(@$postVar["pwd"]);
            $fbID = $database->cleanXSS(@$postVar["fbID"]);
            if(empty($fbID)) {$fbID = 'undefined';}
            if(empty($fbName)) {$fbName = 'undefined';}
            if(empty($fbEmail)) {$fbEmail = 'undefined';}
            if(empty($userProfilePicture)) {$userProfilePicture = 'undefined';}

            $nowDateTime = date("Y-m-d H:i:s");
            
            $uniqueCode = createRandomCode();
                
        
            unset($variable);

            $qry = "insert into user_registration (fbID, fbName, fbEmail, userProfilePicture, displayName,fullname,emailaddress,pwd,uniqCode,createdDateTime,updatedDateTime)
                    values (?,?,?,?,?,?,?,?,?,?,?)";
//            $qry = "insert into user_registration (fbID, displayName,fullname,emailaddress,pwd,uniqCode,createdDateTime,updatedDateTime)
//                    values (?,?,?,?,?,?,?,?)";
            $variable[] = array("s", $fbID);
            $variable[] = array("s", $fbName);
            $variable[] = array("s", $fbEmail);
            $variable[] = array("s", $userProfilePicture);
            $variable[] = array("s", $displayName);
            $variable[] = array("s", $fullname);
            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", sha1($pwd));
            $variable[] = array("s", $uniqueCode);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $nowDateTime);
            $result = $database->query("insert",$qry,$connection,$variable);
            

            if($result > 0){
                $html = 
                '
                <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
                    <tr>
                        <td>
                            <div style="margin-bottom:20px;">Hi '.$fullname.'!</div>
                            <div style="">
                                You have registered with Newslogue. Please click on the link below to verify your account.
                            </div>
                            <div>
                                <a href="'.$GLOBAL_WEB_ROOT.'verify.php?c='.$uniqueCode.'&emailaddress='.$emailaddress.'">'.$GLOBAL_WEB_ROOT.'verify.php?c='.$uniqueCode.'&emailaddress='.$emailaddress.'</a>
                            </div>
                            <div style="margin-top: 40px;">
                                Greatly Welcomed by<br />
                                The Team at Newslogue
                            
                            </div>
                        </td>
                    </tr>
                </table>
                ';
                
                //$mailer->AddAddress($email, $firstName. " ".$lastName);
                $mailer->AddAddress($emailaddress);
                
                $mailer->SetFrom("verify@newslogue.com", "Newslogue");
                //$mailer->AddReplyTo($employerUsername, $firstName. " ".$lastName);
                
                $mailer->Subject = "Newslogue Account Verification";
                $mailer->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                $mailer->MsgHTML($html);
                $result= $mailer->Send();

                return $result;

            }
            else
                return false;
        }


        public function ActivateAccount($postVar){
            global $connection, $database;
            
            
            $userID = $database->cleanXSS(@$postVar["userID"]);
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            $uniqCode = $database->cleanXSS(@$postVar["uniqCode"]);
            $nowDateTime = date("Y-m-d H:i:s");
                
        
            unset($variable);
            $qry = "update user_registration set userStatus = 'active', updatedDateTime = ? where userID = ? and emailaddress =? and uniqCode = ?";
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $userID);
            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", $uniqCode);
            $result = $database->query("update",$qry,$connection,$variable);
            
            
            
            if($result > 0){
               

                return $result;   
            }
            else
                return false;
        }
        



        public function ChangePwd($postVar){
            global $connection, $database;
            
            $oldpwd = $database->cleanXSS($_POST["oldpwd"]);
            $newpwd = $database->cleanXSS($_POST["newpwd"]);
            $confirmnewpwd = $database->cleanXSS($_POST["confirmnewpwd"]);
            $nowDateTime = date("Y-m-d H:i:s");
            
            
        
            $variable = array();
            $qry = "update user_registration set 
                    pwd = ?,
                    updatedDateTime = ?
                    where userID = ?";

            $variable[] = array("s", sha1($newpwd));
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $_SESSION["userID"]);
            
            $result = $database->query("update",$qry,$connection,$variable);
            
            
            if($result > 0){
                return $result;   
            }
            else
                return false;
        }


        // public function ChangeName($postVar){
        //     global $connection, $database;
            
            
                
        //     $fullname = $database->cleanXSS($_POST["fullname"]);
        //     $nowDateTime = date("Y-m-d H:i:s");
            
            
        
        //     unset($variable);
        //     $qry = "update user_registration set 
        //             fullname = ?,
        //             updatedDateTime = ?
        //             where userID = ?";

        //     $variable[] = array("s", $fullname);
        //     $variable[] = array("s", $nowDateTime);
        //     $variable[] = array("s", $_SESSION["userID"]);
            
        //     $result = $database->query("update",$qry,$connection,$variable);
            
            
        //     if($result > 0){
        //         $_SESSION["fullname"] = $fullname;
        //         return $result;   
        //     }
        //     else
        //         return false;
        // }


        public function ChangeProfilePicture($postVar){
            global $connection, $database;
            
            
                
            $userProfilePicture = @$_FILES["userProfilePicture"]["name"];
            $nowDateTime = date("Y-m-d H:i:s");
            
            if($userProfilePicture != ""){
                $explodedMainImageArray = explode('.', $userProfilePicture);
                $ext = strtolower(end($explodedMainImageArray));
                
                
                
                $target_folder = "uploads/profile/";
                $folder_upload = $target_folder."original/".$userProfilePicture;
                $thumbnail_folder = $target_folder."thumbnail/";
                
                
                if(file_exists($folder_upload) && !is_dir($folder_upload)){
                    $files = explode('.', $userProfilePicture);
                    $userProfilePicture = $files[0].uniqid().".".strtolower($files[1]);
                    $folder_upload = $target_folder."original/".$userProfilePicture;
                    
                    move_uploaded_file(@$_FILES["userProfilePicture"]["tmp_name"],$folder_upload);
                }
                else{
                    move_uploaded_file(@$_FILES["userProfilePicture"]["tmp_name"],$folder_upload);
                        
                        
                }
                
                
                
                $this->CropImageIntoFolder($target_folder,$folder_upload);
            }
            
        
            unset($variable);
            $qry = "update user_registration set 
                    userProfilePicture = ?,
                    updatedDateTime = ?
                    where userID = ?";

            $variable[] = array("s", $userProfilePicture);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $_SESSION["userID"]);
            
            $result = $database->query("update",$qry,$connection,$variable);
            
            
            
            if($result > 0){
                return $result;   
            }
            else
                return false;
        }
        
        public function CropImageIntoFolder($target_folder,$folder_upload){
            global $image;

            list($width,  $height) = getimagesize($folder_upload);
                
            $popupWidth = $width;
            $popupHeight = $height;
            $profileWidth = $width;
            $profileHeight = $height;
            $thumbnailWidth = $width;
            $thumbnailHeight = $height;
            
            $resizeArray["thumbnail"] = array(
                "width" => $width,
                "height" => $height,
                "resizeWidth" => 122,
                "resizeHeight" => 122,
                "source" => $folder_upload,
                "quality" => 10,
                "square" => false,
                "destination" => $target_folder."thumbnail/"
                );
             
            foreach($resizeArray as $id => $value)
            {
                while($value["width"] > $value["resizeWidth"] || $value["height"] > $value["resizeHeight"]){
                    if($value["width"] > $value["resizeWidth"]){
                        $ratio = $value["resizeWidth"]/$value["width"];
                        $value["height"] = $value["height"] * $ratio;
                        $value["width"] = $value["resizeWidth"];
                    }
                    
                    if($value["height"] > $value["resizeHeight"]){
                        $ratio = $value["resizeHeight"]/$value["height"];
                        $value["width"] = $value["width"] * $ratio;
                        $value["height"] = $value["resizeHeight"];
                    }
                }   
                $image->settings($value["source"],$value["width"],$value["height"],$value["quality"],$value["square"],"",$value["destination"]);            
                $rst = $image->resize(); 
                
                
            }
        }


        public function DisplayDebateByUser($pageNo,$itemPerPage= 1,$status,$userID){
            global $connection,$database;
            
            $extraSQL = "";
            $variable = array();
            
            $qry = "select 1 from `news_reply` nr 
            inner join newsarticle na on nr.newsID = na.newsID
            inner join `user_registration` ur on ur.userID = nr.userID
            where  nr.replyStatus = ? and nr.userID = ? and parentReplyID = 0 and newsStatus = 'active'
            ";
            $variable[] = array("s",$status);
            $variable[] = array("s",$userID);

            $result = $database->query("select",$qry,$connection,$variable);
            $totalItem = count($result);
            $lastpage = ceil($totalItem/$itemPerPage);
            
            $returnArray["TotalResult"] = $totalItem;
            $returnArray["LastPage"] = $lastpage;


            
            $qry = "select replyID,replyStatement,replyContent,replyType,replyStatus,newsPermalink,nr.newsID,nr.createdDateTime as nrcreatedDateTime,newsTitle from `news_reply` nr 
            inner join newsarticle na on nr.newsID = na.newsID
            inner join `user_registration` ur on ur.userID = nr.userID
            where  nr.replyStatus = ? and nr.userID = ? and parentReplyID = 0 and newsStatus = 'active'
            order by nr.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
            $result2 = $database->query("select",$qry,$connection,$variable);
            
            
            if(is_array($result2) && count($result2) > 0)
            {
                for($xx=0;$xx<count($result2);$xx++)
                {
                    foreach($result2[$xx] as $id => $value)
                    {
                        if(!is_numeric($id))
                        {
                            $returnArray["List"][$xx][$id] = $database->cleanData($value);          
                        }
                    }
                }
                
                return $returnArray;   
            }
            else
                return false;
            
        }  
        
        public function getAnonymousID() {
        	global $GLOBAL_ALLOW_ANONYMOUS;
        	$anonymousID = 0;

        	if ($GLOBAL_ALLOW_ANONYMOUS && !isset($_SESSION["userID"])) {
        		if (isset($_COOKIE["tempID"])) {
        			$anonymousID = "-".substr(hexdec($_COOKIE["tempID"]),7);
        		}
        	}
        	
        	return $anonymousID;
        }
        
        public function getNotificationForUser($userID) {
        	global $connection,$database;
        	$variable = array();
        	
        	$qry = "select * from news_reply nr
				inner join user_registration ur
				on nr.userID = ur.userID
				inner join (select newsID, newsPermaLink from newsarticle) na
				on nr.newsID = na.newsID
				where nr.readflag = 0 and nr.userID != ? and parentReplyID in
				(select replyID from news_reply where userID=?)
				order by nr.createdDateTime desc;";
        	$variable[] = array("s",$userID);
        	$variable[] = array("s",$userID);
        	$result = $database->query("select",$qry,$connection,$variable);
        	$resultArray = array();
        	if (is_array($result) && count($result)>0) {
        		for ($i=0;$i<count($result);$i++) {
        			foreach ($result[$i] as $id => $value) {
        				$resultArray["list"][$i][$id] = $database->cleanData($value);
        			}
        		}
        	}
        	
        	return $resultArray;
        }
        
        public function createAnonymousUser($anonymousID) {
        	global $connection,$database;
        	$variable = array();
        	//$anonymousID = $this->getAnonymousID();
        	
        	if ($anonymousID != 0) {
        		$qry = "insert into user_registration
        				(userID, displayName, fullname, pwd, userStatus, createdDateTime, updatedDateTime)
        				values (?, 'Anonymous User', 'Anonymous User', '', 'active', now(), now())";
        		$variable[] = array("s", $anonymousID);
        		$database->query("insert",$qry,$connection,$variable);
        	}
        }

	public function getDisplayNameFromArray($nameArray) {
		$displayName = "";

		if (is_array($nameArray) && count($nameArray) > 0) {
			if (@$nameArray["displayName"] != "" && 
				@$nameArray["displayName"] != "undefined")
				$displayName = $nameArray["displayName"];
			else if (@$nameArray["fullname"] != "" && 
				@$nameArray["fullname"] != "undefined")
				$displayName = $nameArray["fullname"];
			else if (@$nameArray["fbName"] != "" && 
				@$nameArray["fbName"] != "undefined")
				$displayName = $nameArray["fbName"];
		}

		return $displayName;
	}

    }

?>
