<?php
    
    include "../config.php";    
    
    if(isset($_POST))
    {
    	$key = $database->cleanXSS($_POST["search"]);
 		$searchRstArr = $news->Search(1,10000,$key);       
 		
 		
 		if(is_array(@$searchRstArr["List"]) && count(@$searchRstArr["List"]) > 0)
 		{ 	
 			echo '<div class="search-entries">'.$searchRstArr["TotalResult"].' entries of result</div>					
				<ul>';

 			foreach($searchRstArr["List"] as $sID => $sValue)
 			{
 				$createdDateTime = strtotime($sValue["nacreatedDateTime"]);
				$createdDateTime = date("F d, Y",$createdDateTime);
?>				
				<li>
					<div class="search-cat-date"><?php echo $sValue["categoryName"]." | ".$createdDateTime?></div>		
					<!-- <h4><a href="news/<?php echo $sValue["newsID"]."/".$sValue["newsPermalink"]?>"><?php echo $sValue["newsTitle"]?></a></h4>	 -->
					<h4><a href="news/<?php echo $sValue['newsID']."/".$sValue['newsPermalink'] ?>" onClick="window.location.reload(true)"><?php echo $sValue["newsTitle"]?></a></h4>
				</li>
				
<?php
 			}
 			echo '</ul>';
 		}
 		else
 		{
 			echo "<ul><li>No result found.</li></ul>";
 		}

    }
?>


