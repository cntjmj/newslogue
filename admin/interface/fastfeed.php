<?php

    $action = @$_GET["action"];
    $fastFeedID = $admin_database->cleanXSS(@$_GET["fastFeedID"],"int");
    
    
    
    $notificationClass = @$_SESSION[$module_name]["notificationClass"];
    $notificationMsg = @$_SESSION[$module_name]["notificationMsg"];
    unset($_SESSION[$module_name]);
    
    if(@$_POST["submit"] == "Submit")
    {
        $cmd = $admin_database->cleanXSS(@$_POST["cmd"]);
        
        
        if($cmd == "Add")
        {
            $errorArray = $admin_fastfeed->ValidateForm($_POST,"Add");
            
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
                $addDetailsRstArray = $admin_fastfeed->AddDetails(@$_POST);
                if($addDetailsRstArray)
                {
                    $getDetailsRstArray = $_POST;
                    $_SESSION[$module_name]["notificationClass"] = "success";
                    $_SESSION[$module_name]["notificationMsg"] = "The Fast Feed <strong>'".$_POST["fastFeedTitle"]."'</strong> has been successfully saved.";
                    redirect("",$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&fastFeedID=".$addDetailsRstArray);
                }    
            }
                
        }
        else if($cmd == "Edit")
        {
            $errorArray = $admin_fastfeed->ValidateForm($_POST,"Edit");
            
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
                $setDetailsRstArray = $admin_fastfeed->SetDetails(@$_POST,$fastFeedID);
                if($setDetailsRstArray)
                {
                    $getDetailsRstArray = $_POST;
                    $notificationClass = "success";
                    $notificationMsg = "The Fast Feed <strong>'".$_POST["fastFeedTitle"]."'</strong> has been successfully updated.";
                }   
            }
            
        }
        
    }
    
    
    //display specific or delete
    if($fastFeedID > 0 && ($action == "Edit" || $action == "Delete"))
    {
        $getDetailsRstArray = $admin_fastfeed->GetDetails($fastFeedID);
            
        if($action == "Delete" && count($getDetailsRstArray) > 0 && is_array($getDetailsRstArray))
        {
            $admin_fastfeed->DeleteDetails($fastFeedID);
            redirect(0,$GLOBAL_ADMIN_SITE_URL.$module_name);
        }   
    }
    else
    {
        $pageNo = (@$_GET["pageNo"] == "")? 1:$admin_database->cleanXSS($_GET["pageNo"],"int");
        $displayData = $admin_fastfeed->DisplayAllDetails($pageNo,$GLOBAL_ITEM_PERPAGE,"");
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
                <li <?=($action == "")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>">View Fast Feed</a></li>
                <li <?=($action == "Add")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>&action=Add">Add Fast Feed</a></li>
                <?=($action == "Edit")? "<li class='current'><a href='".$GLOBAL_ADMIN_SITE_URL.$module_name."&action=View&fastFeedID=".$fastFeedID."'>View Specific Fast Feed </a></li>": "";?>
            </ul>
            <div class="clr"></div>
        </div>
        <div id="content">
            <?
                if($action == "Add" || $action == "Edit")
                {
                    
            ?>
                <h2><?=($action == "Add")? "Add A New": "View"?> Fast Feed</h2>
                <?
                    
                    if($notificationMsg != "")
                    {
                        echo "<div id='notification_panel'>
                                <div class='".$notificationClass."'>".$notificationMsg."</div>
                              </div>";
                    }
                ?>
                <form method="post" enctype="multipart/form-data" id="fastfeed_form" name="fastfeed_form">
                    <ul id="form_list">
                        
	                   <li>
							<div class="field_label">Fast Feed Title</div>
							<div class="field_input" >
                                <?=generateInput("text","fastFeedTitle","","fastFeedTitle",@$getDetailsRstArray["fastFeedTitle"])?>
                            </div>
							<div class="clr"></div>
						</li>
                        <li>
                            <div class="field_label">Fast Feed Order</div>
                            <div class="field_input">
                                <?=generateInput("text","fastFeedOrder","small","fastFeedOrder",@$getDetailsRstArray["fastFeedOrder"])?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        
                        <li>
                            <div class="field_label">Fast Feed Status</div>
                            <div class="field_input" >
                                <?php
                                echo CboStatus("fastFeedStatus","fastFeedStatus",@$getDetailsRstArray["fastFeedStatus"],"Please select...");
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
                    <h2>Fast Feed List</h2>
                    
                    <table>
                        <tr>
                            <th width="2%">No.</th>
                            <th width="80%">Fast Feed Name</th>
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
                                        <td><?=$value["fastFeedTitle"]?></td>
                                        <td>
                                            <span class="button-group">
                                            <a title="Edit  Fast Feed" class="button icon edit" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&fastFeedID=".$value["fastFeedID"]?>">Edit</a>
                                            <a title="Delete  Fast Feed" class="button icon remove danger list" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Delete&fastFeedID=".$value["fastFeedID"]?>">Remove</a>
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
                                    <td colspan="4">There is no fast feed yet.</td>
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
        
		$("#fastfeed_form").validate({
			rules: {
				
				fastFeedTitle: "required",
				fastFeedOrder: {
                    required: true,
                    number: true
                },
                fastFeedStatus: "required"
				
			},
            errorPlacement: function(error, element) 
            {
                error.insertAfter(element);
                $("#gradient").css("height",$(document).height()+"px")  
            }
		})
        
        
    })
</script>