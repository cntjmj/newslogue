<?php
	include "../config.php";

	if (@$_SESSION["userID"] > 0) {

		if (@$_GET["replyID"] > 0) {
			global $connection,$database;
			$variable = array();
			
			$qry = "update news_reply set readflag=1 where replyID=?;";
			$variable[] = array("s",$_GET["replyID"]);
			$database->query("update",$qry,$connection,$variable);
			echo "<!--done-->";
		} else {
			$resultArray = $user->getNotificationForUser($_SESSION["userID"]);
	
			if (is_array($resultArray) && is_array(@$resultArray["list"]) && count(@$resultArray["list"])>0) {
				$replyIDArray = array();
				$count = count(@$resultArray["list"]);
	
				foreach ($resultArray["list"] as $row) {
					$newsID = $row["newsID"];
					//if (in_array($newsID, $newsIDArray))
					//	continue;
	
					$newsPermaLink = $row["newsPermaLink"];
					$replyType = $row["replyType"];
					$prid = $row["parentReplyID"];
					$rid = 0;
					$lid = 0;
					$displayName = $user->getDisplayNameFromArray($row);
					$displayContent = "";
					//$param = "";
	
					if ($replyType == "like") {
						$displayContent = "Likes your comments";
						//$param = "?prid=".$row["parentReplyID"];
						$lid = $row["replyID"];
					} else {
						$replyContent = $row["replyContent"];
						$displayContent = "Replied: ".$replyContent;
						if (strlen($displayContent) > 110)
							$displayContent = substr($displayContent, 0, 107)."...";
						//$param = "?prid=".$row["parentReplyID"]."&rid=".$row["replyID"];
						$rid = $row["replyID"];
					}
	?>
					<li <?php if (count($replyIDArray)==0) echo 'id="total-new-notification-count" ntfcount="'.$count.'"'?>>
						<a href="debate/<?php echo $newsID?>/<?php echo $newsPermaLink?>?prid=<?php echo $prid?>&rid=<?php echo $rid?>&lid=<?php echo $lid?>">
							<h5><?php echo $displayName ?></h5>
							<p><?php echo $displayContent ?></p>
						</a>
					</li>
	<?php
					$replyIDArray[] = $row["replyID"];
					if (count($replyIDArray) >= 5)
						break;
				}
			} else {
				echo "<li>no message</li>";
			}
		}
	}
?>
