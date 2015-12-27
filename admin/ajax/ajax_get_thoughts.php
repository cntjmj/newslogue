<?php
    include_once "../config.php";





    $action = $admin_database->cleanXSS(@$_POST["action"]);
    $newsID = $admin_database->cleanXSS(@$_POST["newsID"],"int");
    
    if($action == "GetProductMeasurement")
    {
            
        $thoughtsRstArray = $admin_news->DisplayAllThoughts(1,100,$newsID);
        if(is_array(@$thoughtsRstArray["List"]) && count(@$thoughtsRstArray["List"]) > 0)
        {
            
            foreach($thoughtsRstArray["List"] as $id => $value)
            {
?>
                <tr>
                    <td><span class="productThumbnailNumbering"><?=($id + 1)?></span>.</td>
                    <td><?=$value["thoughts"]?></td>
                    <td><?=$value["thoughtsStatus"]?></td>
                    <td>
                        <span class="button-group">
                        <a title="Edit Thoughts" class="button icon edit fancybox" href="javascript:;" linkTo="<?=$GLOBAL_ADMIN_POPUP?>popup_news_thoughts.php?thoughtsID=<?=$value["thoughtsID"]?>&newsID=<?=$newsID?>">Edit</a>
                        <a class="button icon remove danger removeThoughts" href="javascript:;" newsID="<?=$value["newsID"]?>" thoughtsID="<?=$thoughtsID?>">Remove</a>
                        </span>
                    </td>
                </tr>
<?
            }
        }
        else
        {
?>
            <tr>
                <td colspan="3">
                    No News Thoughts.
                </td>
            </tr>
<?
        }

    }
    

?>