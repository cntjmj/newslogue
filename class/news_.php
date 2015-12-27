<?php
    class News{
        public function ValidateReplyForm($post)
        {

        }
        
        public function DisplayFastFeedDetails($pageNo,$itemPerPage= 1,$filter)
        {
            global $connection,$database;
            
            $extraSQL = "";
            $variable = array();
            
            
            
            $qry = "select * from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID
            where newsTag like ? and na.newsStatus = 'active' limit 5";
            $variable[] = array("s","%".$filter."%");
            $result = $database->query("select",$qry,$connection,$variable);
            $totalItem = count($result);
            $lastpage = ceil($totalItem/$itemPerPage);
            
            $returnArray["TotalResult"] = $totalItem;
            $returnArray["LastPage"] = $lastpage;
            
            $statement = "select *,na.createdDateTime as nacreatedDateTime from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID 
            where newsTag like ? and na.newsStatus = 'active'
            order by na.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
            $result2 = $database->query("select",$statement,$connection,$variable);
            
            
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

        public function DisplayMoreDetails($filter,$exceptNewsID){
            global $connection,$database;
            
            $extraSQL = "";
            $variable = array();
            if($filter != "")
            {
                $extraSQL = "  c.categoryID =? and";                
                $variable[] = array("s",$filter);
            }
            
            
            $qry = "select * from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID
            where ".$extraSQL." na.newsStatus = 'active' and newsID != ? 
            order by na.updatedDateTime desc limit 6
            ";

            
            $variable[] = array("s",$exceptNewsID);
            $result = $database->query("select",$qry,$connection,$variable);
            
            
            if(is_array($result) && count($result) > 0)
            {
                for($xx=0;$xx<count($result);$xx++)
                {
                    foreach($result[$xx] as $id => $value)
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



        public function DisplayAllDetails($pageNo,$itemPerPage= 1,$filter){
            global $connection,$database;
            
            $extraSQL = "";
            $variable = array();
            if($filter != "")
            {
                $extraSQL = "  c.categoryID =? and";                
                $variable[] = array("s",$filter);
            }
            
			
			$qry = "select * from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID
            where ".$extraSQL." na.newsStatus = 'active' 
            ";
			$result = $database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);
			
			$returnArray["TotalResult"] = $totalItem;
            $returnArray["LastPage"] = $lastpage;
			
			$statement = "select *,na.createdDateTime as nacreatedDateTime from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID 
            where ".$extraSQL." na.newsStatus = 'active'
            order by na.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
			$result2 = $database->query("select",$statement,$connection,$variable);
			
			
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


        public function DisplayAllVotedDetails($pageNo,$itemPerPage= 1,$newsID)
        {
            global $connection,$database;
            
            
            $variable = array();
            $qry = "select * from `news_vote` nv 
            inner join `newsarticle` na on na.newsID = nv.newsID
            inner join user_registration ur on ur.userID = nv.userID
            where na.newsStatus = 'active' and na.newsID = ? ";
            
            $variable[] = array("s",$newsID);
            $result = $database->query("select",$qry,$connection,$variable);
            
            $returnArray = array();
            
            if(is_array($result) && count($result) > 0)
            {
                for($xx=0;$xx<count($result);$xx++)
                {
                    foreach($result[$xx] as $id => $value)
                    {
                        if(!is_numeric($id))
                        {
                            $returnArray["List"][$xx][$id] = $database->cleanData($value);          
                        }
                    }
                } 
            }
            
            $magic = hexdec(substr(sha1($newsID), -8));
            $magic_base = 5 + $magic % 15;
            $magic_offset = $magic / 100 % 15;
            $magic_select = $magic / 1000 % 2;
            
            if ($magic_select == 0) {
           		$magic_agree = $magic_base + $magic_offset;
            	$magic_disag = $magic_base;
            } else {
            	$magic_agree = $magic_base;
            	$magic_disag = $magic_base + $magic_offset;
            }            
            
            for ($idx=0;$idx<$magic_agree;$idx++)
            	$returnArray["List"][]["voteType"] = "agree";
            for ($idx=0;$idx<$magic_disag;$idx++)
            	$returnArray["List"][]["voteType"] = "disagree";

            return $returnArray;
        }


        public function DisplayAllReplyDetails($pageNo,$itemPerPage= 1,$newsID)
        {
            global $connection,$database;
            
            
            $variable = array();
            $qry = "select * from `news_reply` nr 
            inner join `newsarticle` na on na.newsID = nr.newsID
            inner join user_registration ur on ur.userID = nr.userID
            where na.newsStatus = 'active' and nr.replyStatus = 'active' and na.newsID = ? and parentReplyID = 0";
            
            $variable[] = array("s",$newsID);
            $result = $database->query("select",$qry,$connection,$variable);
            $totalItem = count($result);
            $lastpage = ceil($totalItem/$itemPerPage);
            
            $returnArray["TotalResult"] = $totalItem;
            $returnArray["LastPage"] = $lastpage;
            
            $statement = "select *,nr.createdDateTime as replyCreatedDateTime from `news_reply` nr 
            inner join `newsarticle` na on na.newsID = nr.newsID
            inner join user_registration ur on ur.userID = nr.userID
            where na.newsStatus = 'active' and nr.replyStatus = 'active'  and na.newsID = ? and parentReplyID = 0
            order by nr.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
            $result2 = $database->query("select",$statement,$connection,$variable);
            
            
            if(is_array($result2) && count($result2) > 0)
            {
                for($xx=0;$xx<count($result2);$xx++)
                {
                    foreach($result2[$xx] as $id => $value)
                    {
                        if(!is_numeric($id))
                        {
                            $returnArray["List"][$xx][$id] = $database->cleanData($value);          
                            $statement = "select *,nr.createdDateTime as subreplyCreatedDateTime from `news_reply` nr 
                            inner join `newsarticle` na on na.newsID = nr.newsID
                            inner join user_registration ur on ur.userID = nr.userID
                            where na.newsStatus = 'active' and nr.replyStatus = 'active'  and na.newsID = ? and parentReplyID = ".$result2[$xx]["replyID"]."
                            order by na.createdDateTime asc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
                            //echo $statement."<br><br>";
                            $result3 = $database->query("select",$statement,$connection,$variable);
                            if(is_array($result3) && count($result3) > 0)
                            {

                                for($kk=0;$kk<count($result3);$kk++)
                                {
                                    foreach($result3[$kk] as $id2 => $value2)
                                    {
                                        if(!is_numeric($id2))
                                        {
                                            $returnArray["List"][$xx]["subreply"][$kk][$id2] = $database->cleanData($value2);
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
                
                return $returnArray;   
            }
            else
                return false;
        }


        public function DisplayReplyDetails($pageNo,$itemPerPage= 1,$newsID,$agreement){
            global $connection,$database;
            
            
            $variable = array();
            $qry = "select * from `news_reply` nr 
            inner join `newsarticle` na on na.newsID = nr.newsID
            inner join user_registration ur on ur.userID = nr.userID
            where nr.replyType = ? and na.newsStatus = 'active' and nr.replyStatus = 'active' and na.newsID = ? and parentReplyID = 0";
            $variable[] = array("s",$agreement);
            $variable[] = array("s",$newsID);
            $result = $database->query("select",$qry,$connection,$variable);
            $totalItem = count($result);
            $lastpage = ceil($totalItem/$itemPerPage);
            
            $returnArray["TotalResult"] = $totalItem;
            $returnArray["LastPage"] = $lastpage;
            
            $statement = "select *,nr.createdDateTime as replyCreatedDateTime from `news_reply` nr 
            inner join `newsarticle` na on na.newsID = nr.newsID
            inner join user_registration ur on ur.userID = nr.userID
            where nr.replyType = ?  and na.newsStatus = 'active' and nr.replyStatus = 'active'  and na.newsID = ? and parentReplyID = 0
            order by nr.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
            $result2 = $database->query("select",$statement,$connection,$variable);
            
            
            if(is_array($result2) && count($result2) > 0)
            {
                for($xx=0;$xx<count($result2);$xx++)
                {
                    foreach($result2[$xx] as $id => $value)
                    {
                        if(!is_numeric($id))
                        {
                            $returnArray["List"][$xx][$id] = $database->cleanData($value);          
                            $statement = "select *,nr.createdDateTime as subreplyCreatedDateTime from `news_reply` nr 
                            inner join `newsarticle` na on na.newsID = nr.newsID
                            inner join user_registration ur on ur.userID = nr.userID
                            where nr.replyType = ? and na.newsStatus = 'active' and nr.replyStatus = 'active'  and na.newsID = ? and parentReplyID = ".$result2[$xx]["replyID"]."
                            order by nr.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
                            //echo $statement."<br><br>";
                            $result3 = $database->query("select",$statement,$connection,$variable);
                            if(is_array($result3) && count($result3) > 0)
                            {

                                for($kk=0;$kk<count($result3);$kk++)
                                {
                                    foreach($result3[$kk] as $id2 => $value2)
                                    {
                                        if(!is_numeric($id2))
                                        {
                                            $returnArray["List"][$xx]["subreply"][$kk][$id2] = $database->cleanData($value2);
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
                
                return $returnArray;   
            }
            else
                return false;
            
        }  

        public function DisplayHotTopicDetails($pageNo,$itemPerPage= 1,$filter){
            global $connection,$database;
            
            $nowDateTime = date("F");
            $variable = array();
            // $qry = "select * from `newsarticle` na 
            // inner join `category` c on na.categoryID = c.categoryID
            // where na.newsStatus = 'active' and na.createdDateTime and  DATE_FORMAT(na.createdDateTime,'%m-%Y') BETWEEN '10-14' AND '12-14' limit  5 

            // ";

            $qry = "select count(1) as totalCommentNo,nr.newsID,newsTitle,newsQuestion,newsPermalink from news_reply nr ".
            		"inner join newsarticle na on na.newsID = nr.newsID where newsStatus = 'active' and ".
            		//"MONTHNAME(nr.createdDateTime) =  '".$nowDateTime."' ".
            		"na.createdDateTime>DATE_SUB(NOW(),INTERVAL 100 DAY) ".
            		"group by nr.newsID limit ".$itemPerPage;

            $result = $database->query("select",$qry,$connection,$variable);
            
            
            if(is_array($result) && count($result) > 0)
            {
                for($xx=0;$xx<count($result);$xx++)
                {
                    foreach($result[$xx] as $id => $value)
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

        public function DisplayTrendingDetails($pageNo,$itemPerPage= 1,$filter){
            global $connection,$database;
            
            $variable = array();
            /*
            $qry = "select * from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID
            where na.newsStatus = 'active' order by newsVisit desc limit ".$itemPerPage."
            ";
            $result = $database->query("select",$qry,$connection,$variable);
            $totalItem = count($result);
            $lastpage = ceil($totalItem/$itemPerPage);
            
            $returnArray["TotalResult"] = $totalItem;
            */
            
            $statement = "select *,na.createdDateTime as nacreatedDateTime from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID 
            where na.newsStatus = 'active' and na.createdDateTime>DATE_SUB(NOW(),INTERVAL 100 DAY) 
            order by newsVisit desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
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
        
        public function DisplayAllThoughts($newsID)
        {
            global $connection,$database;
            $variable = array();
            $statement = "select * from newsarticle na inner join newsarticle_thoughts nat on na.newsID = nat.newsID where newsStatus = 'active' and nat.newsID = ? and nat.thoughtsStatus = 'active' order by thoughtsOrder asc";
            $variable[] = array("s",$newsID);
            $result2 = $database->query("select",$statement,$connection,$variable);

            if($result2 > 0)
            {
                $returnArray = array();
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
        public function Search($pageNo,$itemPerPage= 1,$filter)
        {
            global $connection,$database;

            $variable = array();
            $qry = "select * from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID
            where na.newsStatus = 'active' and (newsTitle like ?) ";
            $variable[] = array("s",'%'.$filter.'%');
            $result = $database->query("select",$qry,$connection,$variable);
            $totalItem = count($result);
            $lastpage = ceil($totalItem/$itemPerPage);
            
            $returnArray["TotalResult"] = $totalItem;
            
            $statement = "select *,na.createdDateTime as nacreatedDateTime from `newsarticle` na 
            inner join `category` c on na.categoryID = c.categoryID
            where na.newsStatus = 'active' and (newsTitle like ?) 
            order by na.createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
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
        
        public function ViewPage($newsID){
            global $connection,$database;
            $newsID = $database->cleanXSS($newsID,"int");

            unset($variable);
            $qry = "update newsarticle set newsVisit = newsVisit+1  where newsID = ?";
            $variable[] = array("i", $newsID);
            $result = $database->query("update",$qry,$connection,$variable);
            
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
        public function GetDetails($newsID){
            global $connection,$database;
            $newsID = $database->cleanXSS($newsID,"int");
            
            
            unset($variable);
            $qry = "select * from newsarticle na inner join `category` c on na.categoryID = c.categoryID where newsID = ?";
            $variable[] = array("i", $newsID);
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

        public function Reply($postvar){
            global $connection,$database,$user;
            $newsID = $database->cleanXSS($postvar["newsID"],"int");
            $replyID = $database->cleanXSS(@$postvar["replyID"],"int");
            $userID = (isset($_SESSION["userID"])?$_SESSION["userID"]:$user->getAnonymousID());
            $userID = $database->cleanXSS($userID,"int");
            $replyStatement = $database->cleanXSS(@$postvar["replyStatement"]);
            $replyDesc = $database->cleanXSS($postvar["replyDesc"]);
            $replyType = $database->cleanXSS($postvar["replyType"]);
            
            $newsRstArr = $this->GetDetails($newsID);
            $nowDateTime = date("Y-m-d H:i:s");
            if(is_array($newsRstArr) && count($newsRstArr) > 0)
            {
                // $status = "pending";
                // if($replyStatement == "")
                // {
                    $status = "active";
                // }
                $variable = array();
                $qry = "insert into news_reply(parentReplyID,newsID,userID,replyStatement,replyContent,replyType,replyStatus,createdDateTime,updatedDateTime) values (?,?,?,?,?,?,?,?,?)";
                $variable[] = array("i", $replyID);
                $variable[] = array("i", $newsID);
                $variable[] = array("i", $userID);
                
                $variable[] = array("s", $replyStatement);
                $variable[] = array("s", $replyDesc);
                $variable[] = array("s", $replyType);
                $variable[] = array("s", $status);
                $variable[] = array("s", $nowDateTime);
                $variable[] = array("s", $nowDateTime);
                
                $result = $database->query("insert",$qry,$connection,$variable);
                
                if($result > 0)
                    return true;
                else
                    return false;
            }            
            else
                return false;
        }
        
        public function HasUserReply($newsID)
        {
            global $connection,$database;

            $newsID = $database->cleanXSS($newsID,"int");
            

            $variable = array();
            $qry = "select * from news_reply where newsID = ?";
            $variable[] = array("i", $replyID);
            $result = $database->query("select",$qry,$connection,$variable);

            if(is_array($result) && count($result) > 0)
                return true;
            else
                return false;
        }
        
        public function UserVoted($newsID)
        {
         
            global $connection,$database,$user;

            $newsID = $database->cleanXSS($newsID,"int");
            $userID = @$_SESSION["userID"];
            $anonymousID = $user->getAnonymousID();

            if ($userID != 0 || $anonymousID != 0) {
	            $variable = array();
	            $qry = "select * from news_vote where newsID = ? and userID = ?";
	            $variable[] = array("i", $newsID);
	            $variable[] = array("i", ($userID!=0?$userID:$anonymousID));

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
            }
            return false;
        }
        public function UserDebated($newsID)
        {
            global $connection,$database;

            $newsID = $database->cleanXSS($newsID,"int");
            

            $variable = array();
            $qry = "select * from news_reply where newsID = ? and parentReplyID = 0 and userID = ?";
            $variable[] = array("i", $newsID);
            $variable[] = array("i", @$_SESSION["userID"]);

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
        

        public function ReplyLikes($replyID)
        {
            global $connection,$database,$_SESSION;

            $replyID = $database->cleanXSS($replyID,"int");
            

            $variable = array();
            $qry = "select * from news_reply where replyType='like' and parentReplyID = ?";
            $variable[] = array("i", $replyID);
            $result = $database->query("select",$qry,$connection,$variable);


            $returnArray = array();
            if(is_array($result) && count($result) > 0)// && @$_SESSION["userID"] > 0)
            {
                foreach($result as $id => $value)
                {

                    
                    $returnArray["usercontent"][$id] = $database->cleanData($value["userID"]);
                    

                }
                
                return $returnArray;   
            }
            else
                return false;
        }        
        
        
    }

?>
