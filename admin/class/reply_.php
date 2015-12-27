<?php
    class AdminReply{
        
        
        public function ValidateForm($postVar,$action = "Add")
        {
            global $admin_database;
            $replyID = $admin_database->cleanXSS($postVar["replyID"]);
            $replyStatus = $admin_database->cleanXSS($postVar["replyStatus"]);
            


            $errorArray = array();
                
            if($replyID == "")
                $errorArray[] = "Reply ID is required";
            if($replyStatus == "")
                $errorArray[] = "Reply Status is required.";
            
            
                
            return $errorArray;
            
        }
        
        
        public function SetDetails($postVar,$replyID){
            global $connection, $admin_database;
            
            $replyStatus = $admin_database->cleanXSS($postVar["replyStatus"]);
            $replyID = $admin_database->cleanXSS($postVar["replyID"]);
            $nowDateTime = date("Y-m-d H:i:s");

            $variable = array();
            $qry = "update `news_reply` set 
                    replyStatus = ?,
                    updatedDateTime = ? 
                    where replyID = ?";
            $variable[] = array("s", $replyStatus);
            $variable[] = array("s", $nowDateTime);
            
            $variable[] = array("i", $replyID);
            
            
            
            $result = $admin_database->query("update",$qry,$connection,$variable);
            
            if($result > 0){
                return true;   
            }
            else
                return false;
        }


        public function DisplayAllDetails($pageNo,$itemPerPage= 1,$filter = "debate",$status){
            global $connection,$admin_database;
            
            $enhancedSQL = "select * from `news_reply` nr 
            inner join newsarticle na on na.newsID = nr.newsID 
            inner join user_registration ur on ur.userID = nr.userID";
            $variable = array();
            
            if($filter == "debate")
            {
                $enhancedSQL .= " where replyStatement !='' and replyType != 'like'";
            }
            else
                $enhancedSQL .= " where replyStatement ='' and replyType != 'like'";   


            if($status != "")
            {
                $enhancedSQL .= " and nr.replyStatus = ?";
                $variable[] = array("s",$status);

            }
            

			
			$qry = $enhancedSQL;
			$result = $admin_database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);
			
			$returnArray["TotalResult"] = $totalItem;
			
			$statement = $enhancedSQL." order by nr.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
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
        

        
        
         public function GetDetails($replyID){
            global $connection,$admin_database;
            $replyID = $admin_database->cleanXSS($replyID,"int");
            
            
            unset($variable);
            $qry = "select * from `news_reply` nr inner join user_registration ur on nr.userID = ur.userID where replyType !='like' and replyID = ?";
            $variable[] = array("i", $replyID);
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
        
        
        
        
        public function DeleteDetails($replyID){
            global $connection,$admin_database;
            $replyID = $admin_database->cleanXSS($replyID,"int");
            
            
            unset($variable);
            $qry = "delete from `news_reply`  where replyID = ?";
            $variable[] = array("i", $replyID);
            $result = $admin_database->query("delete",$qry,$connection,$variable);
            
            
            
            if($result > 0){
                return true;   
            }
            else
                return false;
        }
        

        
        
        
        
    }

?>
