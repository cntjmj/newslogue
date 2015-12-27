<?php
    class AdminFastFeed{
        
        
        public function ValidateForm($postVar,$action = "Add")
        {
            global $admin_database;
            $fastFeedTitle = $admin_database->cleanXSS($postVar["fastFeedTitle"]);
            $fastFeedOrder = $admin_database->cleanXSS($postVar["fastFeedOrder"]);
            $fastFeedStatus = $admin_database->cleanXSS($postVar["fastFeedStatus"]);


            $errorArray = array();
            if($action == "Add" || $action == "Edit")
            {
                
                if($fastFeedTitle == "")
                    $errorArray[] = "Fast Feed Title is required.";
                if($fastFeedOrder == "")
                    $errorArray[] = "Fast Feed Order is required.";
                if($fastFeedStatus == "")
                    $errorArray[] = "Fast Feed Status is required.";
                
                
                return $errorArray;
            }
        }
        public function AddDetails($postVar){
            global $connection, $admin_database;
            
            
            $fastFeedTitle = $admin_database->cleanXSS($postVar["fastFeedTitle"]);
            $fastFeedOrder = $admin_database->cleanXSS($postVar["fastFeedOrder"]);
            $fastFeedStatus = $admin_database->cleanXSS($postVar["fastFeedStatus"]);
            $nowDateTime = date("Y-m-d H:i:s");
            
            
            
            unset($variable);
            $qry = "insert into `fastfeed` ( fastFeedTitle,fastFeedOrder,fastFeedStatus,createdDateTime,updatedDateTime ) 
                    values (?,?,?,?,?)";
            $variable[] = array("s", $fastFeedTitle);
            $variable[] = array("s", $fastFeedOrder);
            $variable[] = array("s", $fastFeedStatus);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $nowDateTime);
            $result = $admin_database->query("insert",$qry,$connection,$variable);
            
            
            
            
            
            if($result > 0){
                return $result;   
            }
            else
                return false;
        }
        
        public function SetDetails($postVar,$fastFeedID){
            global $connection, $admin_database;
            
            $fastFeedTitle = $admin_database->cleanXSS($postVar["fastFeedTitle"]);
            $fastFeedOrder = $admin_database->cleanXSS($postVar["fastFeedOrder"]);
            $fastFeedStatus = $admin_database->cleanXSS($postVar["fastFeedStatus"]);
            $fastFeedID  = $admin_database->cleanXSS($fastFeedID,"int");
            $nowDateTime = date("Y-m-d H:i:s");

            $variable = array();
            $qry = "update `fastfeed` set 
                    fastFeedTitle = ?,
                    fastFeedOrder = ?,
                    fastFeedStatus = ?,
                    updatedDateTime = ? 
                    where fastFeedID = ?";
            $variable[] = array("s", $fastFeedTitle);
            $variable[] = array("s", $fastFeedOrder);
            $variable[] = array("s", $fastFeedStatus);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("i", $fastFeedID);
            
            
            
            $result = $admin_database->query("update",$qry,$connection,$variable);
            
            if($result > 0){
                return true;   
            }
            else
                return false;
        }


        public function DisplayAllDetails($pageNo,$itemPerPage= 1,$filter){
            global $connection,$admin_database;
            
            
			$variable = array();
			$qry = "select * from `fastfeed` ";
			$result = $admin_database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);
			
			$returnArray["TotalResult"] = $totalItem;
			
			$statement = "select * from `fastfeed` order by fastFeedOrder desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
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
        

        
        
         public function GetDetails($fastFeedID){
            global $connection,$admin_database;
            $fastFeedID = $admin_database->cleanXSS($fastFeedID,"int");
            
            
            unset($variable);
            $qry = "select * from `fastfeed` where fastFeedID = ?";
            $variable[] = array("i", $fastFeedID);
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
        
        
        
        
        public function DeleteDetails($fastFeedID){
            global $connection,$admin_database;
            $fastFeedID = $admin_database->cleanXSS($fastFeedID,"int");
            
            
            unset($variable);
            $qry = "delete from `fastfeed`  where fastFeedID = ?";
            $variable[] = array("i", $fastFeedID);
            $result = $admin_database->query("delete",$qry,$connection,$variable);
            
            
            
            if($result > 0){
                return true;   
            }
            else
                return false;
        }
        

        
        
        
        
    }

?>