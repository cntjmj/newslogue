<?php

    $action = @$_GET["action"];
    $categoryID = $admin_database->cleanXSS(@$_GET["categoryID"],"int");
    
    
    
    $notificationClass = @$_SESSION[$module_name]["notificationClass"];
    $notificationMsg = @$_SESSION[$module_name]["notificationMsg"];
    unset($_SESSION[$module_name]);
    
    if(@$_POST["submit"] == "Submit")
    {
        $cmd = $admin_database->cleanXSS(@$_POST["cmd"]);
        
        
        if($cmd == "Add")
        {
            $errorArray = $admin_category->ValidateForm($_POST,"Add");
            
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
                $addDetailsRstArray = $admin_category->AddDetails(@$_POST);
                if($addDetailsRstArray)
                {
                    $getDetailsRstArray = $_POST;
                    $_SESSION[$module_name]["notificationClass"] = "success";
                    $_SESSION[$module_name]["notificationMsg"] = "The category <strong>'".$_POST["categoryName"]."'</strong> has been successfully saved.";
                    redirect("",$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&categoryID=".$addDetailsRstArray);
                }    
            }
                
        }
        else if($cmd == "Edit")
        {
            $errorArray = $admin_category->ValidateForm($_POST,"Edit");
            
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
                $setDetailsRstArray = $admin_category->SetDetails(@$_POST,$categoryID);
                if($setDetailsRstArray)
                {
                    $getDetailsRstArray = $_POST;
                    $notificationClass = "success";
                    $notificationMsg = "The category <strong>'".$_POST["categoryName"]."'</strong> has been successfully updated.";
                }   
            }
            
        }
        
    }
    
    
    //display specific or delete
    if($categoryID > 0 && ($action == "Edit" || $action == "Delete"))
    {
        $getDetailsRstArray = $admin_category->GetDetails($categoryID);
            
        if($action == "Delete" && count($getDetailsRstArray) > 0 && is_array($getDetailsRstArray))
        {
            $admin_category->DeleteDetails($categoryID);
            redirect(0,$GLOBAL_ADMIN_SITE_URL.$module_name);
        }   
    }
    else
    {
        $pageNo = (@$_GET["pageNo"] == "")? 1:$admin_database->cleanXSS($_GET["pageNo"],"int");
        $displayData = $admin_category->DisplayAllDetails($pageNo,$GLOBAL_ITEM_PERPAGE,"");
    }
    
    
?>



<div id="container">      
    <?php
        include_once "includes/user_info.php";
    ?> 
    <div class="clr"></div>
    
    <div id="application">
    <?
        include_once "includes/primary_navigation.php";
    ?>
        <div id="secondary">
            <ul>
                <li <?=($action == "")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>">View Category</a></li>
                <li <?=($action == "Add")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>&action=Add">Add Category</a></li>
                <?=($action == "Edit")? "<li class='current'><a href='".$GLOBAL_ADMIN_SITE_URL.$module_name."&action=View&categoryID=".$categoryID."'>View Specific Category</a></li>": "";?>
            </ul>
            <div class="clr"></div>
        </div>
        <div id="content">
            <?
                if($action == "Add" || $action == "Edit")
                {
                    
            ?>
                <h2><?=($action == "Add")? "Add A New": "View"?> Category</h2>
                <?
                    
                    if($notificationMsg != "")
                    {
                        echo "<div id='notification_panel'>
                                <div class='".$notificationClass."'>".$notificationMsg."</div>
                              </div>";
                    }
                ?>
                <form method="post" enctype="multipart/form-data" id="user_form" name="user_form">
                    <ul id="form_list">
                        
	                   <li>
							<div class="field_label">Category Name</div>
							<div class="field_input" >
                                <?=generateInput("text","categoryName","","categoryName",@$getDetailsRstArray["categoryName"])?>
                            </div>
							<div class="clr"></div>
						</li>
                        <li>
                            <div class="field_label">Category Permalink</div>
                            <div class="field_input" >
                                <?=generateInput("text","categoryPermalink","","categoryPermalink",@$getDetailsRstArray["categoryPermalink"])?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label">Category Order</div>
                            <div class="field_input">
                                <?=generateInput("text","categoryOrder","small","categoryOrder",@$getDetailsRstArray["categoryOrder"])?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        
                        <li>
                            <div class="field_label">Category Status</div>
                            <div class="field_input" >
                                <?php
                                echo CboStatus("categoryStatus","categoryStatus",@$getDetailsRstArray["categoryStatus"],"Please select...");
                                ?>
                            </div>
                            <div class="clr"></div>
                        </li>
					</ul>
                    <input type="hidden" name="cmd" value="<?=($action == "Add")? "Add": "Edit"?>" />
                    <input type="submit" name="submit" value="Submit" class="primary button" />
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
                    <h2>Category List</h2>
                    
                    <table>
                        <tr>
                            <th width="2%">No.</th>
                            <th width="80%">Category Name</th>
                            <th width="15%">Action</th>
                            
                        </tr>
                        <?
                            if(is_array(@$displayData["List"]) && count(@$displayData["List"]) > 0)
                            {
                                foreach($displayData["List"] as $id => $value)
                                {
                        ?>
                                    <tr>
                                        <td><?=($num)?>.</td>
                                        <td><?=$value["categoryName"]?></td>
                                        <td>
                                            <span class="button-group">
                                            <a title="Edit  Category" class="button icon edit" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&categoryID=".$value["categoryID"]?>">Edit</a>
                                            <a title="Delete  Category" class="button icon remove danger list" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Delete&categoryID=".$value["categoryID"]?>">Remove</a>
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
                                    <td colspan="4">There is no category yet.</td>
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
    $(function(){
    	
    	
        $("#newsStartDate").datepicker({
            minDate: new Date(2010, 1 - 1, 1),
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd-mm-yy",
            yearRange: "2010:2017"
        })
        

        $(".button.icon.remove.danger.list").click(function(){
            return confirm("Are you sure you want to delete?")
        })        
        
		$("#artist_form").validate({
			rules: {
				
				artistName: "required",
				artistDesc: "required",
				
				artistMainImage : {
					<?=($action == "Add")? "required: true,":""?>
					accept: "jpg|jpeg|png",
				},
                artistCoverImage: {
					<?=($action == "Add")? "required: true,":""?>
					accept: "jpg|jpeg|png",
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
		})
        
        
    })
</script>