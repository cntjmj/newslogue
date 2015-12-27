<?php

	class AdminDatabase{
		
		
		private function bindVars($stmt, $params) {
		    if ($params != null) {
		        $types = '';                 
		        
		        foreach($params as $param) { 
		        	
              		$types .= $param[0];
		        }
		 
		        $bind_names[] = $types; 
		 
		        for ($i=0; $i<count($params);$i++) {
		            $bind_name = 'bind' . $i;
					       
		            $$bind_name = $params[$i][1];   
		            $bind_names[] = &$$bind_name;   
		        }
		 		
		                                            
		        call_user_func_array(array($stmt, 'bind_param'), $bind_names);
		    }
		    return $stmt;                           
		}
		
		
		
		function query($queryType="select", $query, $connection=null, $varArray=null){
			$queryType = trim(strtolower($queryType));
			
			$database_connect = $connection;
			$stmt = $database_connect->stmt_init();
			
			$stmt->prepare($query);
			
			if(count($varArray) > 0){
				AdminDatabase::bindVars($stmt, $varArray);
			}
			
			$rst = $stmt->execute();

			if ($stmt->error) {
                try {    
                    throw new Exception("MySQL error $stmt->error <br> Query br> $query", $stmt->errno);
                } catch(Exception $e ) {
                    echo "Error No: ".$e->getCode(). " - ". $e->getMessage() . "<br >";
                    echo nl2br($e->getTraceAsString());
                }
            }
			
			if( $queryType == "select"){
				$resultArray = AdminDatabase::mysqliFetchData($stmt);		
			}
			else if($queryType == "insert" || $queryType == "delete" || $queryType == "update"){
				if(($stmt->affected_rows > 0 && $queryType == "insert"))
					$resultArray = $stmt->insert_id;
				else if($stmt->affected_rows > 0 || $queryType == "update")
					$resultArray = true;
				else 
					$resultArray = false;
			}
			
			$stmt->close();
			
			return $resultArray;

		}
		
		
		function setPreparedStatement($query, $connection){
			$stmt = $connection->stmt_init();
			$stmt->prepare($query);
			return $stmt;
		}
		
		function closePreparedStatement($preparedStatement){
			$preparedStatement->close();
		}
		
		function queryForLoop($queryType="select", $varArray, $preparedStatement){
			$queryType = trim(strtolower($queryType));
			
			
			if(count($varArray) > 0)
				AdminDatabase::bindVars($preparedStatement, $varArray);
				
			$rst = $preparedStatement->execute();
			
			if( $queryType == "select")
				$resultArray = AdminDatabase::mysqliFetchDataForLoop($preparedStatement);
			else if($queryType == "insert" || $queryType == "delete" || $queryType == "update"){
				if($preparedStatement->affected_rows > 0)
					$resultArray = true;
				else 
					$resultArray = false;
			}
			
			
			return $resultArray;

		}
		
		
		function mysqliFetchData($preparedStatement)
        {   
            $array = array();
           
            if($preparedStatement instanceof mysqli_stmt)
            {
                $preparedStatement->store_result();
               	
                $variables = array();
                $data = array();
                $meta = $preparedStatement->result_metadata();
               
                while($field = $meta->fetch_field())
                {
                    $variables[] = &$data[$field->name]; // pass by reference
                }
               
                call_user_func_array(array($preparedStatement, 'bind_result'), $variables);
               
                $i=0;
                while($preparedStatement->fetch())
                {
                    $array[$i] = array();
                    $n=0;
					foreach($data as $k=>$v)
	                {
	                    $array[$i][$k] = $v;
	                    $array[$i][$n] = $v;
	                    $n++;
	                }
	                $i++;
               
            	}
	        }
	        elseif($preparedStatement instanceof mysqli_result)
	        {
	            while($row = $preparedStatement->fetch_assoc())
	            {
	                $array[] = $row;
	            }
	        }
	       
	        return $array;
    	}
    	
    	function mysqliFetchDataForLoop($preparedStatement)
        {   
            $array = array();
           
            if($preparedStatement instanceof mysqli_stmt)
            {
                $preparedStatement->store_result();
               	
                $variables = array();
                $data = array();
                $meta = $preparedStatement->result_metadata();
               
                while($field = $meta->fetch_field())
                {
                    $variables[] = &$data[$field->name]; // pass by reference
                }
               
                call_user_func_array(array($preparedStatement, 'bind_result'), $variables);
               
                
                while($preparedStatement->fetch())
                {
                    $n=0;
					foreach($data as $k=>$v)
	                {
	                    $array[$k] = $v;
	                    $array[$n] = $v;
	                    $n++;
	                }
            	}
	        }
	        elseif($preparedStatement instanceof mysqli_result)
	        {
	            while($row = $preparedStatement->fetch_assoc())
	            {
	                $array[] = $row;
	            }
	        }
	       
	        return $array;
    	}
	   
       
        public function cleanXSS($input,$type = "mysql"){
            /*
                $input = str_ireplace("�","'",$input);
            $input = str_ireplace("�","'",$input);
            $input = str_ireplace("�",'"',$input);
            $input = str_ireplace("�",'"',$input);
            $input = str_ireplace("�","-",$input);
            */
            
            $input = preg_replace("/�/","-", $input);
            $input = preg_replace("/�/","'", $input);
            $input = preg_replace("/�/","'", $input);
            $input = preg_replace("/�/",'"', $input);
            $input = preg_replace("/�/",'"', $input);

            
            if (get_magic_quotes_gpc()!=1) 
            {   
                $input = addslashes($input);
            }
            if($type == "mysql"){
                $input = htmlentities($input);
                
            }
            else if($type == "wysiwyg"){
                
            }
            else if($type == "int"){
                $input = intval($input);
            }
            else if($type == "double"){
                $input = number_format($input,2);
            }




                
            return $input;
        }
        
        
        public function cleanData($input)
        {
            if (get_magic_quotes_gpc()!=1) 
            {
                $input = stripslashes($input);
            }
            
            //$input = stripslashes($input);
            
            return $input;   
        }	
        
        public function FormatPermalink($permalink,$name)
        {
            if($permalink == "")
                $permalink = str_ireplace(" ","-",$name);
            else
                $permalink = str_ireplace(" ","-",$permalink);
            
            /*
            $permalink = str_ireplace("'","",$permalink);
            $permalink = str_ireplace("/","",$permalink);
            $permalink = stripslashes($permalink);
             */
            
            $permalink = str_ireplace("'","",$permalink);
            $permalink = str_ireplace('"',"",$permalink);
            $permalink = str_ireplace("/","",$permalink);
            $permalink = str_ireplace("{","",$permalink);
            $permalink = str_ireplace("}","",$permalink);
            $permalink = str_ireplace("|","",$permalink);
            $permalink = str_ireplace("?","",$permalink);
            $permalink = str_ireplace("(","",$permalink);
            $permalink = str_ireplace(")","",$permalink);  
            
            return $permalink;
        }
	}

?>