<?php
    class AdminCategory{
        
        
        public function ValidateForm($postVar,$action = "Add")
        {
            global $admin_database;
            $categoryName = $admin_database->cleanXSS($postVar["categoryName"]);
            $categoryPermalink = str_ireplace(" ","-",$admin_database->cleanXSS($postVar["categoryPermalink"]));
            $categoryOrder = $admin_database->cleanXSS($postVar["categoryOrder"]);
            $categoryStatus = $admin_database->cleanXSS($postVar["categoryStatus"]);


            $errorArray = array();
            if($action == "Add" || $action == "Edit")
            {
                
                if($categoryName == "")
                    $errorArray[] = "Category Name is required.";
                if($categoryPermalink == "")
                    $errorArray[] = "Category Permalink is required.";
                if($categoryOrder == "")
                    $errorArray[] = "Category Order is required.";
                if($categoryStatus == "")
                    $errorArray[] = "Category Status is required.";
                
                
                return $errorArray;
            }
        }
        public function AddDetails($postVar){
            global $connection, $admin_database;
            
            
            $categoryName = $admin_database->cleanXSS($postVar["categoryName"]);
            $categoryPermalink = str_ireplace(" ","-",$admin_database->cleanXSS($postVar["categoryPermalink"]));
            $categoryOrder = $admin_database->cleanXSS($postVar["categoryOrder"]);
            $categoryStatus = $admin_database->cleanXSS($postVar["categoryStatus"]);
            $nowDateTime = date("Y-m-d H:i:s");
            
            if($categoryPermalink == "")
                $categoryPermalink == str_ireplace(" ","-",$categoryName);
            
            
            unset($variable);
            $qry = "insert into `category` ( categoryName,categoryPermalink,categoryOrder,categoryStatus,createdDateTime,updatedDateTime ) 
                    values (?,?,?,?,?,?)";
            $variable[] = array("s", $categoryName);
            $variable[] = array("s", $categoryPermalink);
            $variable[] = array("s", $categoryOrder);
            $variable[] = array("s", $categoryStatus);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $nowDateTime);
            $result = $admin_database->query("insert",$qry,$connection,$variable);
            
            
            
            
            
            if($result > 0){
                return $result;   
            }
            else
                return false;
        }
        
        public function SetDetails($postVar,$categoryID){
            global $connection, $admin_database;
            
            $categoryID = $admin_database->cleanXSS($categoryID);
            $categoryName = $admin_database->cleanXSS($postVar["categoryName"]);
            $categoryPermalink = str_ireplace(" ","-",$admin_database->cleanXSS($postVar["categoryPermalink"]));
            $categoryOrder = $admin_database->cleanXSS($postVar["categoryOrder"]);
            $categoryStatus = $admin_database->cleanXSS($postVar["categoryStatus"]);
            if($categoryPermalink == "")
                $categoryPermalink == str_ireplace(" ","-",$categoryName);
            $nowDateTime = date("Y-m-d H:i:s");

            $variable = array();
            $qry = "update `category` set 
                    categoryName = ?,
                    categoryPermalink = ?,
                    categoryOrder = ?,
                    categoryStatus = ?,
                    updatedDateTime = ? 
                    where categoryID = ?";
            $variable[] = array("s", $categoryName);
            $variable[] = array("s", $categoryPermalink);
            $variable[] = array("s", $categoryOrder);
            $variable[] = array("s", $categoryStatus);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("i", $categoryID);
            
            
            
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
			$qry = "select * from `category` ";
			$result = $admin_database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);
			
			$returnArray["TotalResult"] = $totalItem;
			
			$statement = "select * from `category` order by categoryOrder desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
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
        

        
        
         public function GetDetails($categoryID){
            global $connection,$admin_database;
            $categoryID = $admin_database->cleanXSS($categoryID,"int");
            
            
            unset($variable);
            $qry = "select * from `category` where categoryID = ?";
            $variable[] = array("i", $categoryID);
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
        
        
        
        
        public function DeleteDetails($categoryID){
            global $connection,$admin_database;
            $categoryID = $admin_database->cleanXSS($categoryID,"int");
            
            
            unset($variable);
            $qry = "delete from `category`  where categoryID = ?";
            $variable[] = array("i", $categoryID);
            $result = $admin_database->query("delete",$qry,$connection,$variable);
            
            
            
            if($result > 0){
                return true;   
            }
            else
                return false;
        }
        

        public function CboCategory($id,$selectedValue,$firstText=""){
            global $connection,$admin_database;
            
            $qry = "select * from `category` order by categoryname desc";
            $result = $admin_database->query("select",$qry,$connection,null);
            
            if(is_array($result) && count($result) > 0)
            {
                echo "<select name='".$id."' name='".$id."'>";
                echo ($firstText != "")? "<option value=''>".$firstText."</option>":"";
                foreach($result as $id => $value)
                {
                    $selected = "";
                    if($selectedValue == $value["categoryID"])
                        $selected = "selected='selected'";
                        
                    echo "<option value='".$value["categoryID"]."' ".$selected.">".$value["categoryName"]."</option>";
                }
                echo "</select>";
            }
        } 
        
        
        
        
        
    }

?>