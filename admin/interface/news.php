<?php

    //require_once('../FirePHPCore/fb.php');
    $action = @$_GET["action"];
    $newsID = $admin_database->cleanXSS(@$_GET["newsID"],"int");


    $notificationClass = @$_SESSION[$module_name]["notificationClass"];
    $notificationMsg = @$_SESSION[$module_name]["notificationMsg"];
    unset($_SESSION[$module_name]);
    
    if(@$_POST["submit"] == "Submit")
    {
        $cmd = $admin_database->cleanXSS(@$_POST["cmd"]);
        
        
        if($cmd == "Add")
        {
            $errorArray = $admin_news->ValidateForm($_POST,"Add");
            
            if(is_array($errorArray) && count($errorArray))
            {
                foreach($errorArray as $errID => $errValue)
                {
                    $notificationMsg .= $errValue."<br />";    
                }
                 
                $notificationClass = "error";
            }
            else
            {
                $addDetailsRstArray = $admin_news->AddDetails(@$_POST);
                if($addDetailsRstArray)
                {
                    $getProductRstArray = $_POST;
                    $_SESSION[$module_name]["notificationClass"] = "success";
                    $_SESSION[$module_name]["notificationMsg"] = "The news <strong>'".$_POST["newsTitle"]."'</strong> has been successfully saved.";
                    redirect("",$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&newsID=".$addDetailsRstArray);
                }    
            }
                
        }
        else if($cmd == "Edit")
        {
            $errorArray = $admin_news->ValidateForm($_POST,"Edit");
            
            if(is_array($errorArray) && count($errorArray))
            {
                foreach($errorArray as $errID => $errValue)
                {
                    $notificationMsg .= $errValue."<br />";    
                }
                 
                $notificationClass = "error";
            }
            else
            {
                $setDetailsRstArray = $admin_news->SetDetails(@$_POST,$newsID);
                if($setDetailsRstArray)
                {
                    $getProductRstArray = $_POST;
                    $notificationClass = "success";
                    $notificationMsg = "The news <strong>'".$_POST["newsTitle"]."'</strong> has been successfully updated.";
                }   
            }
            
        }
        
    }

    $pageNo = (@$_GET["pageNo"] == "")? 1:$admin_database->cleanXSS($_GET["pageNo"],"int");
    //display specific or delete
    if($newsID > 0 && ($action == "Edit" || $action == "Delete"))
    {
        $getDetailsRstArray = $admin_news->GetDetails($newsID);
        $pos1 = strpos($getDetailsRstArray["newsBannerSource"],'www.');
        $pos2 = strpos($getDetailsRstArray["newsSource"],'www.');
        if ($pos1 !== false) {
            $newsBannerSource = 'http://' . $getDetailsRstArray["newsBannerSource"];
        } else {
            $newsBannerSource = $getDetailsRstArray["newsBannerSource"];
        }
        if ($pos2 !== false) {
            $newsSource = 'http://' . $getDetailsRstArray["newsSource"];
        } else {
            $newsSource = $getDetailsRstArray["newsSource"];
        }

        if (false === strpos($newsBannerSource, "://"))
        	$newsBannerSource = 'http://' . $newsBannerSource;

        if($action == "Delete" && count($getDetailsRstArray) > 0 && is_array($getDetailsRstArray))
        {
            $admin_news->DeleteDetails($newsID);
            redirect(0,$GLOBAL_ADMIN_SITE_URL.$module_name);
        }   
    }
    else
    {
        
        $displayData = $admin_news->DisplayAllDetails($pageNo,$GLOBAL_ITEM_PERPAGE,"");
    }
    
    
?>



<div id="container">      
    <?php
        include_once "includes/user_info.php";
    ?> 
    <div class="clr"></div>
    <?= isset($_SESSION['notice'])? $_SESSION['notice'] : null;?>
    <div id="application">
    <?
        include_once "includes/primary_navigation.php";
    ?>
        <div id="secondary">
            <ul>
                <li <?=($action == "")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>">View News</a></li>
                <li <?=($action == "Add")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>&action=Add">Add News</a></li>
                <?=($action == "Edit")? "<li class='current'><a href='".$GLOBAL_ADMIN_SITE_URL.$module_name."&action=View&newsID=".$newsID."'>View Specific News</a></li>": "";?>
            </ul>
            <div class="clr"></div>
        </div>
        <div id="content">
            <?
                if($action == "Add" || $action == "Edit")
                {
                    
            ?>
                <h2><?=($action == "Add")? "Add A New": "View"?> News</h2>
                <div id='notification_panel'>
                <?php
                    if($notificationMsg != "")
                        echo "<div class='".$notificationClass."'>".$notificationMsg."</div>";
                ?>
                </div>
                <form method="post" enctype="multipart/form-data" id="user_form" name="user_form" ng-app="nlapp" ng-controller="adminController"
                	action="<?=$_SERVER["REQUEST_URI"]?>">
                    <ul id="form_list" >
                        <li>
                            <div class="field_label">Category Name</div>
                            <div class="field_input" >
                                
                                <?php
                                    $admin_category->CboCategory("categoryID",@$getDetailsRstArray["categoryID"],"Please select category...");
                                ?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                        	<div class="field_label">News Banner</div>
                        	<div class="field_input" >
                        	    <div style="width:480px; height:240px; overflow:hidden; background-size:cover; background-position: center center; background-image:url({{newsBannerSource}})"></div>
                        		<!--img src="{{newsBannerSource}}"-->
                        		<!-- 
                                <input type="file" size="8" name="banner" />
                                <?php
									/*
                                    if(@$getDetailsRstArray["newsBanner"] != "")
                                    {
                                        $bannerFileLocation = '../uploads/banner/thumbnail/' . $getDetailsRstArray["newsBanner"];
                                        if (file_exists($bannerFileLocation)) {

                                        list($banner_width,  $banner_height) = getimagesize($bannerFileLocation);
                                        if ($banner_height > 250) {
                                            echo "<br/><a id='crop-banner' draggable='false' href='javascript:;'>Crop Banner</a><span class='banner-needs-cropping' style='color:red; '>The banner requires cropping.</span>";

                                            echo "<div class=banner-cropper ><img class='banner-cropper' src='../uploads/banner/thumbnail/".$getDetailsRstArray["newsBanner"]."' /></div>";
                                        } else {

                                        echo "<br><img src='../uploads/banner/thumbnail/".$getDetailsRstArray["newsBanner"]."'>";
                                        }
                                        } else {
                                            echo "file doesn't exist";
                                        }
                                    }
									*/
                                ?>
                                -->
                            </div>
                        	<div class="clr"></div>
                        </li>

                        <li>
                        	<div class="field_label">News Banner Source</div>
                        	<div class="field_input" >
                                <?=generateInput("text","newsBannerSource","","newsBannerSource",@$newsBannerSource,"ng-model=\"newsBannerSource\"")?>
                            </div>
                        	<div class="clr"></div>
                        </li>

                        <li>
                        	<div class="field_label">News Source</div>
                        	<div class="field_input" >
                                <?=generateInput("text","newsSource","","newsSource",@$newsSource)?>
                            </div>
                        	<div class="clr"></div>
                        </li>
	                   <li>
							<div class="field_label">News Title</div>
							<div class="field_input" >
                                <?=generateInput("text","newsTitle","","newsTitle",@$getDetailsRstArray["newsTitle"])?>
                            </div>
							<div class="clr"></div>
						</li>
                        <li>
                            <div class="field_label">News Short Description</div>
                            <div class="field_input" >
                                <?=generateInput("textarea","newsDesc","","newsDesc",@$getDetailsRstArray["newsDesc"])?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label">News Permalink</div>
                            <div class="field_input" >
                                <?=generateInput("text","newsPermalink","","newsPermalink",@$getDetailsRstArray["newsPermalink"])?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label">News Tag</div>
                            <div class="field_input">
                                <?=generateInput("text","newsTag","","newsTag",@$getDetailsRstArray["newsTag"])?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        
						<li>
							<div class="field_label">News Content</div>
							<div class="field_input" >
                                <textarea class="newsContent" name="newsContent"><?=@$getDetailsRstArray["newsContent"]?></textarea>
                                <script type="text/javascript">
                                //<![CDATA[
                        
                                    // Replace the <textarea id="editor"> with an CKEditor
                                    // instance, using default configurations.
                                    
                                    CKEDITOR.replace( 'newsContent',
                                        {
                                            height: '600px'

                                        });
                                    CKEDITOR.font_names =
                                    'Helvetica, Arial, sans-serif;' +
                                    'Times New Roman/Times New Roman, Times, serif;' +
                                    'Verdana';
                                    

                                    CKEDITOR.on('instanceReady',function(){
                                       $("#gradient").css("height",$(document).height()+"px")  
                                    });
                                //]]>
                                </script>

                            </div>
							<div class="clr"></div>
						</li>
                        <li>
                            <div class="field_label">Question</div>
                            <div class="field_input">
                                <?php
                                    echo generateInput("text","newsQuestion","","newsQuestion",@$getDetailsRstArray["newsQuestion"],"");
                                ?>

                                <div class="" style="border-bottom:1px solid #ccc; border-top:1px solid #eee; margin: 20px 0"></div>
                                <div title="Add News Thoughts" id="AddNewsThoughts" class="button icon add fancybox" href="#" newsID="<?php echo $newsID; ?>" linkTo="<?=$GLOBAL_ADMIN_POPUP?>popup_news_thoughts.php?newsID=<?=$newsID?>">Add News Thoughts</div>
                                <table>
                                    <tr>
                                        <th width="3%">No.</th>
                                        <th width="63%">Other Question</th>
                                        <th width="17%">Status</th>
                                        <th width="17%">Action</th>
                                    </tr>
                                    <tbody class="thoughtsHTML">
                                    <?php
                                        $thoughtsRstArray = $admin_news->DisplayAllThoughts(1,100,$newsID);
                                        if(is_array(@$thoughtsRstArray["List"]) && count(@$thoughtsRstArray["List"]) > 0)
                                        {
                                            foreach($thoughtsRstArray["List"] as $tID => $tValue)
                                            {
                                    ?>
                                                <tr>
                                                    <td><span class="productThumbnailNumbering"><?=($tID + 1)?></span>.</td>
                                                    <td><?=$tValue["thoughts"]?></td>
                                                    <td><?=$tValue["thoughtsStatus"]?></td>
                                                    <td>
                                                        <span class="button-group">
                                                        <a title="Edit Thoughts" class="button icon edit fancybox" href="javascript:;" linkTo="<?=$GLOBAL_ADMIN_POPUP?>popup_news_thoughts.php?thoughtsID=<?=$tValue["thoughtsID"]?>&newsID=<?=$newsID?>">Edit</a>
                                                        <a class="button icon remove danger removeThoughts" href="javascript:;" newsID="<?=$tValue["newsID"]?>" thoughtsID="<?=$tValue["thoughtsID"]?>">Remove</a>
                                                        </span>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clr"></div>
                        </li>
						<li>
                            <div class="field_label">News Start Date</div>
                            <div class="field_input">
                                <?php
                                    if(@$getDetailsRstArray["newsStartDate"] != "")
                                    {
                                        $newsStartDate = strtotime($getDetailsRstArray["newsStartDate"]);
                                        $newsStartDate = date("d-m-Y",$newsStartDate);
                                        echo generateInput("text","newsStartDate","small","newsStartDate",@$newsStartDate,"readonly='readonly'");
                                    }
                                    else
                                        echo generateInput("text","newsStartDate","small","newsStartDate","","readonly='readonly'");


                                ?>
                            </div>
                            <div class="clr"></div>
                        </li>
						
                        <li>
                            <div class="field_label">News Status</div>
                            <div class="field_input" >
                                <?php
                                echo CboStatus("newsStatus","newsStatus",@$getDetailsRstArray["newsStatus"],"Please select...");
                                ?>
                            </div>
                            <div class="clr"></div>
                        </li>
					</ul>
                    <input type="hidden" name="cmd" value="<?=($action == "Add")? "Add": "Edit"?>" />
                    <input type="submit" name="submit" value="Submit" class="primary button" onclick="MakeLinkSafe();"/>
                    <input type="reset" value="Reset" class="button"/>
                </form>
            <?
                }
                else
                {
                    if($pageNo == 1)
				        $num = 1;
				    else
				        $num = ($GLOBAL_ITEM_PERPAGE * ($pageNo-1)) +1;
            ?>
                    <h2>News List</h2>
                    
                    <table>
                        <tr>
                            <th width="2%">No.</th>
                            <th width="30%">News Title</th>
                            <th width="30%">Question</th>
                            <th width="10%">Start Date</th>
                            <th width="5%">Status</th>
                            <th width="7%">Author</th>
                            <th width="15%">Action</th>
                            
                        </tr>
                        <?
                            if(is_array(@$displayData["List"]) && count(@$displayData["List"]) > 0)
                            {
                                foreach($displayData["List"] as $id => $value)
                                {
                                    $newsStartDate = @strtotime($value["newsStartDate"]);
                                    $newsStartDate = @date("d-m-Y",$newsStartDate);

                        ?>
                                    <tr>
                                        <td><?=($num)?>.</td>
                                        <td><?=$value["newsTitle"]?></td>
                                        <td><?=$value["newsQuestion"]?></td>
                                        <td><?=$newsStartDate?></td>
                                        <td><?=$value['newsStatus']?></td>
                                        <td><?=$value['adminName']?>
                                        <td>
                                            <span class="button-group">
                                            <a title="Edit News" class="button icon edit" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&newsID=".$value["newsID"]?>">Edit</a>
                                            <a title="Delete News" class="button icon remove danger list" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Delete&newsID=".$value["newsID"]?>">Remove</a>
                                            </span>
                                        </td>
                                        
                                        
                                    </tr>
                        <?
                                    $num++;
                                }
                            }
                            else
                            {
                        ?>
                                <tr>
                                    <td colspan="4">There is no news yet.</td>
                                </tr>
                        <?
                            }
                        ?>
                    </table>
            <?
                    if(@$displayData["TotalResult"]  > $GLOBAL_ITEM_PERPAGE)
                    {
                        include_once "includes/pagination.php";  
                    }
                }
            ?>
        </div>
    </div>
</div>


<script type="text/javascript">

	angular.module("nlapp", ['ngSanitize']).controller("adminController", function($scope, $sce){
			$scope.trustedBanner = function() {
				var src = $scope.newsBannerSource;
				alert(src);
				return $sce.trustAsResourceUrl(src);
			};
			$scope.newsBannerSource = "<?=@$newsBannerSource?>";//"http://www.theage.com.au/content/dam/images/g/l/9/5/m/q/image.related.articleLeadwide.620x349.gl8xfw.png/1448630104271.jpg";
		});


    $(function(){
    	
    	
        $("#newsStartDate").datepicker({
            minDate: new Date(2010, 1 - 1, 1),
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd-mm-yy",
            yearRange: "2010:2017"
        })
        

        $(".button.icon.remove.danger.list").live("click",function(){
            return confirm("Are you sure you want to delete?")
        })        
        
		$("#artist_form").validate({
			rules: {
				
				artistName: "required",
				artistDesc: "required",
				
				artistMainImage : {
					<?=($action == "Add")? "required: true,":""?>
					accept: "jpg|jpeg|png"
				},
                artistCoverImage: {
					<?=($action == "Add")? "required: true,":""?>
					accept: "jpg|jpeg|png"
				},
				artistOrder : {
					required: true,
					number: true
				},
				artistStatus: "required"
			},
            errorPlacement: function(error, element) 
            {
                error.insertAfter(element);
                $("#gradient").css("height",$(document).height()+"px")  
            }
		});



var cropYAxis = 0;

            $('.banner-cropper > img').cropper({
                strict: true,
                guides: true,
                dragCrop: false,
                movable: true,
                resizable: false,
                zoomable: false,
                autoCropArea: 0,
                aspectRatio: 'free',
                // minCropBoxWidth: 780,
                // minCropBoxHeight: 390,
                minCropBoxWidth: 500,
                minCropBoxHeight: 250,                
                built: function() {
                    $('.banner-cropper > img').cropper('setCropBoxData',{left:0, top: 0, width: 500, height: 220});
                    var height, width,image;
                    image = $('.banner-cropper > img').cropper('getImageData');
                    height = image.naturalHeight;
                    width = image.naturalWidth;
                    $('.banner-cropper > img').cropper('setCanvasData',{left:0, top: 0, width: width, height: height});


                },
                crop: function(data) {
                    cropYAxis = data.y
                }
            });

    $("#crop-banner").on("click", function() {
        <?php if(!isset($getDetailsRstArray)){$getDetailsRstArray["newsBanner"] = "";} ?>
        $.ajax(
            {
                type: "POST",
                url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_crop_banner.php",
                data:
                {
                    yAxis : cropYAxis,
                    banner: '<?=$getDetailsRstArray["newsBanner"]?>'
                },
                cache: false,
                success: function()
                {
                   location.href = "newslogue8999.php?m=news&action=Edit&newsID=" + <?=$newsID?>;
                },
                error: function(xhr, textStatus, errorThrown){
                    alert('request failed');
                }


            });


        });

        $(".fancybox").live("click",function(){

            var obj = $(this);
            var linkTo = obj.attr('linkTo');
            var title = obj.attr('title');
            if (obj.attr('newsid') == 0) {
                alert("Please submit news before adding thoughts.");
                return false;
            } else {
            $.fancybox({
                'hideOnOverlayClick' : false,
                'transitionIn'      : 'elastic',
                'transitionOut'     : 'none',
                'overlayColor'      : '#000',
                'overlayOpacity'    : 0.5,
                'href'              : linkTo,
                'modal'             : true,
                'autoScale'         : true,
                'onClosed'          : function()
                {
                    
                    if(($("#isNeedAjax").attr("isNeedAjax") == "yes"))
                    {
                        if($("#isNeedAjax").attr("cmd") == "news_thoughts" )
                        {
                            $.ajax(
                            {
                                type: "POST",
                                url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_get_thoughts.php",
                                data: 
                                {
                                    action: "GetProductMeasurement",
                                    newsID : <?=$newsID?>
                                },
                                cache: false,
                                success: function(response_html)
                                {
                                    //$("#product_list_by_category").html(response_html)
                                    $(".thoughtsHTML").hide().html(response_html).fadeIn("slow")
                                    $("#gradient").css("height",$(document).height()+"px")
                                }
                    
                            })
                        }
                    }

                }
                
            });
            }
        })
        
        $("#user_form").submit(function() {
            var errormsg = "";
            //$("#notification_panel").html("<div class='error'>you shall not pass</div>");
            //var editor_data = CKEDITOR.instances.newsContent.getData();
            try {
            	if (document.getElementsByName('categoryID')[0].value == "")
                	errormsg += "Category Name is required.<br>";
                if (document.getElementsByName('newsBannerSource')[0].value == "")
                    errormsg += "News Banner Source is required.<br>";
            	if (document.getElementsByName('newsTitle')[0].value == "")
                	errormsg += "News Title is required.<br>";
                if (document.getElementsByName('newsPermalink')[0].value == "")
                    errormsg += "News Permalink is required.<br>";
                if (document.getElementsByName('newsTag')[0].value == "")
                    errormsg += "News Tag is required.<br>";
                if (CKEDITOR.instances.newsContent.getData() == "")
                    errormsg += "News Content is required.<br>";
                if (document.getElementsByName('newsQuestion')[0].value == "")
                    errormsg += "News Question is required.<br>";
                if (document.getElementsByName('newsStartDate')[0].value == "")
                    errormsg += "News Start Date is required.<br>";
                if (document.getElementsByName('newsStatus')[0].value == "")
                    errormsg += "News Status is required.<br>";
                /*
                if ($("#AddNewsThoughts").attr("newsID") == 0) {
	                var banner_file = document.getElementsByName('banner')[0].value;
	                if (banner_file == "")
	                    errormsg += "News Banner is required.<br>";
	                else {
	                    var ext = banner_file.substr(banner_file.lastIndexOf("."));
	                    if(ext != ".jpg" && ext != ".jpeg" && ext != ".png" && ext != ".gif")
	    	        		errormsg += "News Banner field must be in jpg, jpeg, gif or png.<br>";
	                }
                }
                */
            } catch(e) {
                ;
            }

			if (errormsg != "") {
				$("#notification_panel").html("<div class='error'>"+errormsg+"</div>");
				$('body').scrollTop(0);
				return false;
			} else {            
            	return true;
			}
        });
        


        $(".removeThoughts").live("click",function(){
            if(!confirm("Are you sure you want to delete?"))
                return false
                
                
            var obj = $(this)
            var thoughtsID = obj.attr("thoughtsID")
            var newsID = obj.attr("newsID")
            $.ajax(
            {
                type: "POST",
                url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_delete_thoughts.php",
                data: 
                {
                    action: "DeleteThoughts",
                    newsID : newsID,
                    thoughtsID : thoughtsID
                },
                cache: false,
                success: function(response_html)
                {
                    //$("#product_list_by_category").html(response_html)
                    if($.trim(response_html) == "true")
                    {
                        obj.parent().parent().fadeOut("slow",function(){ 
                            //$(this).remove()
                            $(this).parent().remove()
                            $(".productThumbnailNumbering").each(function(i,e){
                                $(e).html(i+1)
                            })
                        })
                           
                    }
                }
    
            })
        })
        
    })
</script>