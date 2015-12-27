<?php
    class AdminAccount{
        public $pageNo;
        public $postArrays;
        public $itemPerPage = 20;
        public $lastpage = 1;
        
        
        public function AdminLogin($postVar){
            global $connection,$admin_database;    
            
            
            
            $username = $admin_database->cleanXSS($postVar["username"]);
            $pwd = $admin_database->cleanXSS($postVar["password"]);
            $pwd = sha1($pwd);

            unset($variable);
            $qry = "select * from administrator where username = ? and password = ?";
            $variable[] = array("s", $username);
            $variable[] = array("s", $pwd);
            $result = $admin_database->query("select",$qry,$connection,$variable);
            
            if(is_array($result) && count($result) > 0){
                return $result[0];   
            }
            else
                return false;
        }   
    }

?>