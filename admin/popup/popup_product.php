<? 
    
    include_once "../config.php";
    $productID = $admin_database->cleanXSS(@$_GET["productID"],"int");
    $productPriceID = $admin_database->cleanXSS(@$_GET["productPriceID"],"int");
    
    if($productPriceID > 0)
    {
        $productPriceInfoRstArray = $admin_product->GetDetailsForProductPrices($productID,$productPriceID);
        if(is_array($productPriceInfoRstArray) && count($productPriceInfoRstArray) > 0 )
        {
            $productPriceInfoRstArray = $productPriceInfoRstArray[0];
        } 
        
    }
    
    $productRstArray = $admin_product->GetDetails($productID);
?>
<form method="post" id="product_price_form" name="product_price_form" >
<div style="width:  700px; height: 500px; overflow:hidden; overflow-y: auto">
    <div id="popup_notification"></div>
    <h1>Edit Product Price Info for Product "<?=$productRstArray["productName"]?>"</h1>
    
    <ul id="form_list">
    	<li>
    		<div class="field_label">Product Info</div>
    		<div class="field_input"><input type="text" name="productPricesInfo" id="productPricesInfo" value="<?=@$productPriceInfoRstArray["productPriceInfo"]?>" /></div>
    		<div class="clr"></div>
	   </li>
       <li>
    		<div class="field_label">Product Info Arabic</div>
    		<div class="field_input"><input type="text" name="productPriceInfoArabic" value="<?=@$productPriceInfoRstArray["productPriceInfoArabic"]?>" style="width: 200px;"/></div>
    		<div class="clr"></div>
	   </li>
       <li>
    		<div class="field_label">Product Price</div>
    		<div class="field_input"><input type="text" name="productPrices" value="<?=@$productPriceInfoRstArray["productPrice"]?>" style="width: 200px;"/></div>
    		<div class="clr"></div>
	   </li>
    </ul>
    <input type="hidden" name="productPriceID" value="<?=$productPriceID?>" />
    <input type="hidden" name="productID" value="<?=$productID?>" />
    <input type="hidden" name="action" value="<?=($productPriceID > 0)? "setProductPrice":""?>" />
    <input type="submit" value="Submit" class="primary button add" id="isNeedAjax" isNeedAjax="no" cmd="product_measurement" />
    <input type="button" onclick="$.fancybox.close()" value="Close This Popup" class="primary button danger" id="isNeedAjax" isNeedAjax="no" />
 </div>
 </form>
 <script type="text/javascript">
    $(function(){
        
        $("#product_price_form").validate({
            rules: {
                productPricesInfo : "required",
                productPriceInfoArabic : {
                    required : true
                },
                productWidth : {
                    required : true,
                    number: true
                },
                productPrices : {
                    required : true,
                    number: true
                }
            },
            submitHandler: function(form) {
                var obj = $('#product_price_form')
                var serialiseData = obj.serialize()
				$.ajax(
    			{
                    type: "POST",
                    url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_set_product.php",
                    data: serialiseData,
                    cache: false,
                    success: function(response_html)
    				{
    					//$("#product_list_by_category").html(response_html)
                        if($.trim(response_html) == "true")
                        {
                            $("#popup_notification").html("<div class='success'>Product price has been successfully saved.</div>")
                            $("#isNeedAjax").attr("isNeedAjax","yes")
                        }
                        else
                        {
                            $("#popup_notification").html("<div class='error'>There was an error. Could not save.</div>")
                        }
                    }
    
                })
			}

        })
    })
 </script>