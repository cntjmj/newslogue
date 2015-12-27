
<div class="paging_two_button">
    <?
        $categoryIDText = (@$_GET["categoryID"] != "")? "&categoryID=".$_GET["categoryID"]: "";
        
        $lastPage = ceil($displayData["TotalResult"]/$GLOBAL_ITEM_PERPAGE);
        if($pageNo == 1 )
        {
            echo '<div class="paginate_disabled_previous" title="Previous"></div>';
            echo '<a href="'.$GLOBAL_ADMIN_SITE_URL.$module_name.$categoryIDText."&pageNo=".($pageNo + 1).'" class="paginate_enabled_next" title="Next"></a>';    
        }
        else if($pageNo > 0 && $pageNo < $lastPage)
        {
            echo '<a href="'.$GLOBAL_ADMIN_SITE_URL.$module_name.$categoryIDText."&pageNo=".($pageNo - 1).'" class="paginate_enabled_previous" title="Previous"></a>';
            echo '<a href="'.$GLOBAL_ADMIN_SITE_URL.$module_name.$categoryIDText."&pageNo=".($pageNo + 1).'" class="paginate_enabled_next" title="Next"></a>';
            
        }
        else{
            echo '<a href="'.$GLOBAL_ADMIN_SITE_URL.$module_name.$categoryIDText."&pageNo=".($pageNo - 1).'" class="paginate_enabled_previous" title="Previous"></a>';
            echo '<div href="'.$GLOBAL_ADMIN_SITE_URL.$module_name.$categoryIDText."&pageNo=".($pageNo + 1).'" class="paginate_disabled_next" title="Next"></div>';
        }
        
        echo "Page ".$pageNo. " out of ".$lastPage;
    ?>
    
</div>