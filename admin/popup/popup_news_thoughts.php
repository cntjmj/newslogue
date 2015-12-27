<? 
    
    include_once "../config.php";
    $newsID = $admin_database->cleanXSS(@$_GET["newsID"],"int");
    $thoughtsID = $admin_database->cleanXSS(@$_GET["thoughtsID"],"int");
    
    $newsRstArray = $admin_news->GetDetails($newsID);
    $thoughtRstArray = $admin_news->GetThoughts($thoughtsID);
    

?>
<form method="post" id="newsthought_form" name="newsthought_form" >
<div style="width:  700px; height: 500px; overflow:hidden; overflow-y: auto">
    <div id="popup_notification"></div>
    <h1>Add News Thoughts for News Title <br>"<?=$newsRstArray["newsTitle"]?>"</h1>
    
    <ul id="form_list">
    	<li>
    		<div class="field_label">News Thoughts</div>
    		<div class="field_input"><input type="text" name="thoughts" id="thoughts" value="<?=@$thoughtRstArray["thoughts"]?>" /></div>
    		<div class="clr"></div>
	   </li>
       <li>
    		<div class="field_label">News Thoughts Order</div>
    		<div class="field_input"><input type="text" name="thoughtsOrder" class="medium" value="<?=@$thoughtRstArray["thoughtsOrder"]?>" /></div>
    		<div class="clr"></div>
	   </li>
       <li>
            <div class="field_label">News Thoughts Status</div>
            <div class="field_input">
                <?php
                    echo CboStatus("thoughtsStatus","thoughtsStatus",@$thoughtRstArray["thoughtsStatus"],"Please select...");
                ?>
            </div>
            <div class="clr"></div>
       </li>
    </ul>
    <input type="hidden" name="newsID" value="<?=$newsID?>" />
    <input type="hidden" name="thoughtsID" value="<?=$thoughtsID?>" />
    <input type="hidden" name="action" value="<?=($thoughtsID > 0)? "setThought":"AddNewThoughts"?>" />
    <input type="submit" value="Submit" class="primary button add" id="isNeedAjax" isNeedAjax="no" cmd="news_thoughts" />
    <input type="button" onclick="$.fancybox.close()" value="Close This Popup" class="primary button danger" id="isNeedAjax" isNeedAjax="no" />
 </div>
 </form>
 <script type="text/javascript">
    $(function(){
        
        $("#newsthought_form").validate({
            rules: {
                thoughts : "required",
                thoughtsOrder : {
                    required: true,
                    number: true
                }
            },
            submitHandler: function(form) {
                var obj = $('#newsthought_form')
                var serialiseData = obj.serialize()
				$.ajax(
    			{
                    type: "POST",
                    url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_set_thoughts.php",
                    data: serialiseData,
                    cache: false,
                    success: function(response_html)
    				{
    					//$("#product_list_by_category").html(response_html)
                        if($.trim(response_html) == "true")
                        {
                            $("#popup_notification").html("<div class='success'>Thoughts has been successfully saved.</div>")
                            $("#isNeedAjax").attr("isNeedAjax","yes")
                        }
                        else
                        {
                            $("#popup_notification").html("<div class='error'>Thoughts has been successfully saved.</div>")
                        }
                    }
    
                })
			}

        })
    })
 </script>