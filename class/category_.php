<?php
    class Category{
        
        

        public function DisplayAllDetails($pageNo,$itemPerPage= 1,$filter){
            global $connection,$database;
            
            
			$variable = array();
			$qry = "select * from `category` ";
			$result = $database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);
			
			$returnArray["TotalResult"] = $totalItem;
			
			$statement = "select * from `category` order by categoryOrder desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
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
        

        
        
         public function GetDetails($categoryID){
            global $connection,$database;
            $categoryID = $database->cleanXSS($categoryID,"int");
            
            
            unset($variable);
            $qry = "select * from `category` where categoryID = ?";
            $variable[] = array("i", $categoryID);
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