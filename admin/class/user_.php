<?php
    class AdminUser{
        
        public function ValidateForm($postVar,$action = "Add")
        {
            global $admin_database;
            $fullname = $admin_database->cleanXSS(@$postVar["fullname"]);
            $fbName = $admin_database->cleanXSS(@$postVar["fbName"]);
            $emailaddress = $admin_database->cleanXSS(@$postVar["emailaddress"]);
            $fbEmail = $admin_database->cleanXSS(@$postVar["fbEmail"]);
            $userStatus = $admin_database->cleanXSS($postVar["userStatus"]);


            $errorArray = array();


            if($fullname == "" && $fbName == "")
                $errorArray[] = "Full Name is required.";
            if($emailaddress == "" && $fbEmail == "")
                $errorArray[] = "Email Address is required.";
            if($userStatus == "")
                $errorArray[] = "User Status is required.";


            return $errorArray;
        }

        public function ValidateAdminForm($postVar,$action = "Add")
        {
            global $admin_database;
            $username = $admin_database->cleanXSS($postVar["username"]);
            $accessLevel = $admin_database->cleanXSS($postVar["accessLevel"]);
            $password = $admin_database->cleanXSS($postVar["password"]);


            $errorArray = array();


            if($username == "")
                $errorArray[] = "Full Name is required.";
            if($accessLevel == "")
                $errorArray[] = "Email Address is required.";
            if($password == "")
                $errorArray[] = "User Status is required.";



            return $errorArray;
        }
        
        public function DisplayAllDetails($pageNo,$itemPerPage= 1,$filter, $userType = 'User'){
            global $connection,$admin_database;
            
			$variable = array();
            switch($userType) {
                case 'User':
                    $qry = "select * from user_registration where userID>=0 ";
                    break;
                case 'Admin':
                    $qry = "select * from administrator ";

                    break;
                default:
                    break;
            }
			$result = $admin_database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);
			
			$returnArray["TotalResult"] = $totalItem;
			
			$statement = $qry . "order by createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
			$result2 = $admin_database->query("select",$statement,$connection,$variable);
			
			
            if($result2 > 0)
            {
                for($xx=0;$xx<count($result2);$xx++)
                {
                    foreach($result2[$xx] as $id => $value)
                    {
                        if(!is_numeric($id))
                        {
                            $returnArray["List"][$xx][$id] = $admin_database->cleanData($value);          
                        }
                    }
                }
                
                return $returnArray;   
            }
            else
                return false;
            
        }   
        
        
        public function AddDetails($postVar,$userType){
            global $connection, $admin_database;
            
            $variable = array();

            $password = $admin_database->cleanXSS($postVar["password"]);
            $password = sha1($password);
            $nowDateTime = date("Y-m-d H:i:s");



            switch($userType) {
                case 'user':
                    $fullname = $admin_database->cleanXSS($postVar["fullname"]);
                    $emailaddress = $admin_database->cleanXSS($postVar["emailaddress"]);
                    $userStatus = $admin_database->cleanXSS($postVar["userStatus"]);
                    $qry = "insert into user_registration (fullname,emailaddress,userStatus,pwd,createdDateTime,updatedDateTime )
                    values (?,?,?,?,?,?)";
                    $variable[] = array("s", $fullname);
                    $variable[] = array("s", $emailaddress);
                    $variable[] = array("s", $userStatus);
                    $variable[] = array("s", $password);
                    $variable[] = array("s", $nowDateTime);
                    $variable[] = array("s", $nowDateTime);
                    break;
                case 'admin':
                    $username = $admin_database->cleanXSS($postVar['username']);
                    $accessLevel = $admin_database->cleanXSS($postVar['accessLevel']);

                    $qry = "insert into administrator (username,password,accessLevel,createdDateTime,updatedDateTime )
                    values (?,?,?,?,?)";
                    $variable[] = array("s", $username);
                    $variable[] = array("s", $password);
                    $variable[] = array("s", $accessLevel);
                    $variable[] = array("s", $nowDateTime);
                    $variable[] = array("s", $nowDateTime);

                    break;
                default:
                    break;
            }

            $result = $admin_database->query("insert",$qry,$connection,$variable);

            if($result > 0){
                return $result;   
            }
            else
                return false;
        }
        
        public function SetDetails($postVar,$userID){
            global $connection, $admin_database;
            
            $fullname = $admin_database->cleanXSS(@$postVar["fullname"]);
            $fbName = $admin_database->cleanXSS(@$postVar["fbName"]);
            $emailaddress = $admin_database->cleanXSS(@$postVar["emailaddress"]);
            $fbEmail = $admin_database->cleanXSS(@$postVar["fbEmail"]);
            $userStatus = $admin_database->cleanXSS(@$postVar["userStatus"]);
            $userID = $admin_database->cleanXSS($userID);
            $nowDateTime = date("Y-m-d H:i:s");
            $pwd = $admin_database->cleanXss($postVar['password']);

            $variable = array();
            if(empty($pwd)) {

                $qry = "update `user_registration` set
                    fullname = ?,
                	fbName = ?,
                    emailaddress = ?,
                	fbEmail = ?,
                    userStatus = ?,
                    updatedDateTime = ?
                    where userID = ?";
            } else {
                $qry = "update `user_registration` set
                    pwd = ?,
                    fullname = ?,
                	fbName = ?,
                    emailaddress = ?,
                	fbEmail = ?,
                    userStatus = ?,
                    updatedDateTime = ?
                    where userID = ?";
                $pwd = sha1($pwd);
                $variable[] = array("s", $pwd);
            }

            $variable[] = array("s", $fullname);
            $variable[] = array("s", $fbName);
            $variable[] = array("s", $emailaddress);
            $variable[] = array("s", $fbEmail);
            $variable[] = array("s", $userStatus);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("i", $userID);

            $result = $admin_database->query("update",$qry,$connection,$variable);
            
            if($result > 0){
                return true;   
            }
            else
                return false;
        }

        public function SetAdminDetails($postVar,$adminID){
            global $connection, $admin_database;

            $username = $admin_database->cleanXSS(@$postVar["username"]);
            $accessLevel = $admin_database->cleanXSS(@$postVar["accessLevel"]);
            $adminID = $admin_database->cleanXSS($adminID);
            $nowDateTime = date("Y-m-d H:i:s");
            $pwd = $admin_database->cleanXss($postVar['password']);

            $variable = array();
            if(empty($pwd)) {

                $qry = "update administrator set
                    username = ?,
                    accessLevel = ?,
                    updatedDateTime = ?
                    where adminID = ?";
            } else {
                $qry = "update administrator set
                    password = ?,
                    username = ?,
                    accessLevel = ?,
                    updatedDateTime = ?
                    where adminID = ?";
                $pwd = sha1($pwd);
                $variable[] = array("s", $pwd);
            }

            $variable[] = array("s", $username);
            $variable[] = array("s", $accessLevel);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("i", $adminID);

            $result = $admin_database->query("update",$qry,$connection,$variable);

            if($result > 0){
                return true;
            }
            else
                return false;
        }


         public function GetDetails($userID){
            global $connection,$admin_database;
            $userID = $admin_database->cleanXSS($userID,"int");
            
            
            unset($variable);
            $qry = "select * from user_registration where userID = ?";
            $variable[] = array("i", $userID);
            $result = $admin_database->query("select",$qry,$connection,$variable);
            
            if(is_array($result) && count($result) > 0)
            {
                foreach($result[0] as $id => $value)
                {
                    if(!is_numeric($id))
                    {
                        $returnArray[$id] = $admin_database->cleanData($value);
                    }   
                }
                return $returnArray;   
            }
            else
                return false;
        }
        public function GetAdminDetails($adminID){
            global $connection,$admin_database;
            $adminID = $admin_database->cleanXSS($adminID,"int");


            unset($variable);
            $qry = "select * from administrator where adminID = ?";
            $variable[] = array("i", $adminID);
            $result = $admin_database->query("select",$qry,$connection,$variable);

            if(is_array($result) && count($result) > 0)
            {
                foreach($result[0] as $id => $value)
                {
                    if(!is_numeric($id))
                    {
                        $returnArray[$id] = $admin_database->cleanData($value);
                    }
                }
                return $returnArray;
            }
            else
                return false;
        }
        
        
        
        public function DeleteDetails($userID){
            global $connection,$admin_database;
            $userID = $admin_database->cleanXSS($userID,"int");
            
            
            unset($variable);
            $qry = "delete from `user_registration`  where userID = ?";
            $variable[] = array("i", $userID);
            $result = $admin_database->query("delete",$qry,$connection,$variable);
            
            
            
            if($result > 0){
                return true;   
            }
            else
                return false;
        }

        public function DeleteAdminDetails($adminID){
            global $connection,$admin_database;
            $adminID = $admin_database->cleanXSS($adminID,"int");


            unset($variable);
            $qry = "delete from administrator  where adminID = ?";
            $variable[] = array("i", $adminID);
            $result = $admin_database->query("delete",$qry,$connection,$variable);

            if($result > 0){
                return true;
            }
            else
                return false;
        }
        
        
        
        
        
    }

?>