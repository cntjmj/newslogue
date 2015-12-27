<?php
    
    include "../config.php";
    
    $userID = (@$_SESSION["userID"]>0?$_SESSION["userID"]:$user->getAnonymousID());

    if ($userID < 0) $user->createAnonymousUser($userID);

    if($userID != 0)
    {
        $newsID = $database->cleanXSS($_POST["newsID"],"int");
        $voteType = $database->cleanXSS($_POST["voteType"]);
        $overRide = $database->cleanXSS($_POST["overRide"],"int");
        $doInsert = 1;

        $variable = array();
        $nowDateTime = date("Y-m-d H:i:s");
        $qry = "select 1 from news_vote where userID = ? and newsID = ?";
        
        $variable[] = array("i", $userID);
        $variable[] = array("i", $newsID);
        
        $result = $database->query("select",$qry,$connection,$variable);

        if(is_array($result) && count($result) > 0)
        {
            $variable = array();
            $nowDateTime = date("Y-m-d H:i:s");
            $qry = "delete from news_vote where newsID = ? and userID = ?";
            $variable[] = array("i", $newsID);
            $variable[] = array("i", $userID);
            
            $result = $database->query("delete",$qry,$connection,$variable);        
            if ($overRide == 0)
            {
            	echo "removed<==>";
            	$doInsert = 0;
            }
        }

        if ($doInsert == 1)
        {
            $variable = array();
            $nowDateTime = date("Y-m-d H:i:s");
            $qry = "insert into news_vote (newsID,userID,voteType,createdDateTime,updatedDateTime) values (?,?,?,?,?)";
            $variable[] = array("i", $newsID);
            $variable[] = array("i", $userID);
            $variable[] = array("s", $voteType);
            $variable[] = array("s",$nowDateTime);
            $variable[] = array("s",$nowDateTime);
            
            $result = $database->query("insert",$qry,$connection,$variable);        
            echo "true<==>";    
        }

        $newsAllVotedRstArr = $news->DisplayAllVotedDetails(1,10000000000,$newsID);
                
        

        $agreeAmt = $disagreeAmt = 0;
        
        if(is_array($newsAllVotedRstArr["List"]) && count($newsAllVotedRstArr["List"]) > 0)
        {

            foreach($newsAllVotedRstArr["List"] as $r2ID => $r2Value)
            {
                if($r2Value["voteType"] == "agree")
                    $agreeAmt++;    
                else
                    $disagreeAmt++;     

            }
        }
        $totalagreement = $agreeAmt + $disagreeAmt;
        
        $agreePercent = 0;
        if($totalagreement > 0)
            $agreePercent = ceil(($agreeAmt/$totalagreement) * 100);
        

        $disagreePercent = 100 - $agreePercent;
        

        echo '
        <div class="row">
			<div class="four columns "><h3>'.$agreePercent.'% says yes</h3></div>
			<div class="four columns text-center"><h3>Total '.($agreeAmt+$disagreeAmt).' people voted</h3></div>
			<div class="four columns text-right"><h3>'.$disagreePercent.'% says no</h3></div>
        </div>
        <div class="join-discussion-filler clearfix">
            <div class="join-discussion-filler-yes" style="width:'.$agreePercent.'%"></div>
            <div class="join-discussion-filler-no" style="width:'.$disagreePercent.'%"></div>
        </div>

        ';   
    }
    
    
    
?>


