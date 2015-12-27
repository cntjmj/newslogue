<?php
    class FastFeed{
        
        public function DisplayAllDetails($pageNo,$itemPerPage= 1,$filter){
            global $connection,$database;
            
            
			$variable = array();
			$qry = "select * from `fastfeed` ";
			$result = $database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);
			
			$returnArray["TotalResult"] = $totalItem;
			
			$statement = "select * from `fastfeed` order by fastFeedOrder asc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
			$result2 = $database->query("select",$statement,$connection,$variable);
			
			
            if($result2 > 0)
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
        

        
        
         public function GetDetails($fastFeedID){
            global $connection,$database;
            $fastFeedID = $database->cleanXSS($fastFeedID,"int");
            
            
            unset($variable);
            $qry = "select * from `fastfeed` where fastFeedID = ?";
            $variable[] = array("i", $fastFeedID);
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
        
        
        
        
        
        
        
        
    }

?>