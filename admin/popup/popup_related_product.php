<? 
    
    include_once "../config.php";
    $productID = $admin_database->cleanXSS(@$_GET["productID"],"int");
    
    
?>
<div style="width:  700px; height: 500px; overflow:hidden; overflow-y: auto">
    <h1>Add Related Product For Product     </h1>
    
    <h4 style="padding: 0; margin: 0;">Category</h4>
    <div class="button-group minor-group">
        <?
            $categoryRstArray = $admin_category->DisplayAllCategory(1,1,"all");
            
            foreach($categoryRstArray as $categoryID => $categoryValue){
                echo '<a href="javascript:;" class="button categoryButton" categoryID="'.$categoryValue["categoryID"].'">'.$categoryValue["categoryName"].'</a>';
            }
        ?>
    </div>
    <div class="clr"></div>
    <div id="product_list_by_category">
    
    
    </div>
    
    <input type="button" onclick="$.fancybox.close()" value="Close This Popup" class="primary button danger" id="isNeedAjax" isNeedAjax="no" cmd="related_product"/>
 </div>
 
 <script type="text/javascript">
    $(function(){
        $(".categoryButton").click(function(){
            var obj = $(this)
            var categoryID = obj.attr("categoryID")
            var productID = <?=$productID?>
            
            $.ajax(
			{
                type: "POST",
                url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_get_product_by_category.php",
                data: 
				{
                    action: "GetProductByCategoryID",
                    categoryID : categoryID,
                    productID : productID
                },
                cache: false,
                success: function(response_html)
				{
					$("#product_list_by_category").html(response_html)
                }

            })
        })
        
        $(".relate").live('click',function(){
            var obj = $(this)
            var relatedProductID = obj.attr("relatedProductID")
            $.ajax(
			{
                type: "POST",
                url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_set_related_product.php",
                data: 
				{
                    action: "SetRelatedProduct",
                    productID : <?=$productID?>,
                    relatedProductID : relatedProductID
                },
                cache: false,
                success: function(response_html)
				{
					//$("#product_list_by_category").html(response_html)
                    if($.trim(response_html) == "true")
                    {
                        obj.after('<a class="button icon remove danger unrelate" href="javascript:;" relatedProductID = "'+relatedProductID+'">Unrelate</a>')
                        obj.remove()
                        $("#isNeedAjax").attr("isNeedAjax","yes")
                    }
                }

            })
        })
        
        $(".unrelate").live('click',function(){
            var obj = $(this)
            var relatedProductID = obj.attr("relatedProductID")
            $.ajax(
			{
                type: "POST",
                url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_set_related_product.php",
                data: 
				{
                    action: "UnsetRelatedProduct",
                    productID : <?=$productID?>,
                    relatedProductID : relatedProductID
                },
                cache: false,
                success: function(response_html)
				{
					//$("#product_list_by_category").html(response_html)
                    if($.trim(response_html) == "true")
                    {
                        obj.after('<a class="button icon favorite relate" href="javascript:;" relatedProductID = "'+relatedProductID+'" >Relate</a>')
                        obj.remove();
                        $("#isNeedAjax").attr("isNeedAjax","yes")
                    }
                }

            })
        })
    })
 </script>