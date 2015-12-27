<?php
    class ESubscriber{
        
        public function ValidateForm($postVar,$action = "Add")
        {
            global $connection, $database;
           
            
            
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            
            
            $errorArray = array();
            
            
            
            if($emailaddress == "")
                $errorArray[] = "Email Address.";
            
            return $errorArray;
        }



        public function AddDetails($postVar){
            global $connection, $database,$mailer;
            
            
                
            
            $emailaddress = $database->cleanXSS(@$postVar["emailaddress"]);
            
            
            $nowDateTime = date("Y-m-d H:i:s");
            
            
            
            $variable = array();
            $qry = "select * from esubscriber where emailaddress = ?";
            $variable[] = array("s", $emailaddress);
            $result = $database->query("select",$qry,$connection,$variable);


            if(is_array($result) && count($result)  > 0)
            {
                if($result[0]["esubsribeStatus"] != "active")
                {
                    $variable = array();
                    $qry = "update esubscriber set esubsribeStatus ='active', updatedDateTime = ? where emailaddress = ?";
                    $variable[] = array("s", $nowDateTime);
                    $variable[] = array("s", $emailaddress);
                    $result = $database->query("update",$qry,$connection,$variable);    
                }
                else
                    $result = true;
                
            }
            else
            {


                $variable = array();
                $qry = "insert into esubscriber ( emailaddress,esubsribeStatus,createdDateTime,updatedDateTime) 
                        values (?,?,?,?)";
                
                $variable[] = array("s", $emailaddress);
                $variable[] = array("s", 'active');
                $variable[] = array("s", $nowDateTime);
                $variable[] = array("s", $nowDateTime);
                $result = $database->query("insert",$qry,$connection,$variable);
            }

            
            
            
            if($result > 0){
                return $result;   
            }
            else
                return false;
        }
        
        
    }

?>