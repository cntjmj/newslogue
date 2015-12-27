<?php
    
    include "../config.php";
    

    if(@$_REQUEST["pageNo"] != "")
    {

        $pageNo = $database->cleanXSS($_REQUEST["pageNo"],"int");

        $filterCategory = $database->cleanXSS(@$_GET["id"]);
        $type = $database->cleanXSS(@$_GET["type"]);
        $fastfeedName = $database->cleanXSS(@$_GET["fastfeedName"]);
        if($type == "fastfeed")
            $newsRstArr = $news->DisplayFastFeedDetails($pageNo,5,$fastfeedName);
        else
            $newsRstArr = $news->DisplayAllDetails($pageNo,5,$filterCategory);
        
        if(is_array($newsRstArr["List"]) && count($newsRstArr["List"]) > 0)
        {
            foreach($newsRstArr["List"] as $id => $val)
            {                
                $createdDateTime = strtotime($val["nacreatedDateTime"]);
                $createdDateTime = date("F d, Y",$createdDateTime);
                
                $newsAllReplyRstArr = $news->DisplayAllReplyDetails(1,10000000000,$val["newsID"]);
                $agreeAmt = $disagreeAmt = 0;
                if(is_array($newsAllReplyRstArr["List"]) && count($newsAllReplyRstArr["List"]) > 0)
                {
                    foreach($newsAllReplyRstArr["List"] as $r2ID => $r2Value)
                    {
                        if($r2Value["replyType"] == "agree")
                            $agreeAmt++;    
                        else
                            $disagreeAmt++;     

                    }
                }
    ?>
                <div class="feed-item">
                    <div class="feed-item-title"><a href="news/<?php echo $val["newsID"]."/".$val["newsPermalink"]?>"><?php echo $val["newsTitle"]?></a></div>                                                      
                    <a href="news/<?php echo $val["newsID"]."/".$val["newsPermalink"]?>">
                        <div class="feed-item-image" style="background-image: url(<?php echo 'uploads/banner/thumbnail/'.$val['newsBanner']?>);">
                        </div>
                        <div class="feed-item-newstip">
                            <p>
                                <?php 
                                   $newscontent = strip_tags(html_entity_decode($val["newsContent"]));
                                   if(strlen($newscontent) > 300){
                                       $stringCut = substr($newscontent, 0, 300);
                                       $newsContent = substr($stringCut, 0, strrpos($stringCut, ' ')).' ...';
                                   }
                                   echo $newsContent;
                                ?>
                             </p>
                        </div>
                    </a>
                    <div class="feed-cat-date"><?php echo $val["categoryName"]." | ".$createdDateTime?> </div>
                    
                    <div class="feed-question clearfix">                    
                        <div class="feed-quote quote-open"><img src="img/quote-mark-open.png"></div>
                        <div class="feed-question-text"><?php echo $val["newsQuestion"]?></div>
                        <div class="feed-quote"><img src="img/quote-mark-close.png"></div>
                        <div class="feed-debate-cta">
                            <a href="debate/<?php echo $val["newsID"]."/".$val["newsPermalink"]?>" class="dispute-link" data-newsID="<?php echo $val["newsID"]?>">Dispute <i class="icomo-dispute"></i></a>
                        </div>
                    </div>
                </div>
<?php
            }
        }

    }
?>


