<?php

    $action = @$_GET["action"];
    $replyID = $admin_database->cleanXSS(@$_GET["replyID"],"int");
    $filter = $admin_database->cleanXSS(@$_GET["status"]);
    
    
    $notificationClass = @$_SESSION[$module_name]["notificationClass"];
    $notificationMsg = @$_SESSION[$module_name]["notificationMsg"];
    unset($_SESSION[$module_name]);
    
    if(@$_POST["submit"] == "Submit")
    {
        $cmd = $admin_database->cleanXSS(@$_POST["cmd"]);
        
        
        if($cmd == "Add")
        {
            $errorArray = $admin_reply->ValidateForm($_POST,"Add");
            
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
                $addDetailsRstArray = $admin_reply->AddDetails(@$_POST);
                if($addDetailsRstArray)
                {
                    $getDetailsRstArray = $_POST;
                    $_SESSION[$module_name]["notificationClass"] = "success";
                    $_SESSION[$module_name]["notificationMsg"] = "The  Reply  has been successfully saved.";
                    redirect("",$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&replyID=".$addDetailsRstArray);
                }    
            }
                
        }
        else if($cmd == "Edit")
        {
            $errorArray = $admin_reply->ValidateForm($_POST,"Edit");
            
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
                $setDetailsRstArray = $admin_reply->SetDetails(@$_POST,$replyID);
                if($setDetailsRstArray)
                {
                    $getDetailsRstArray = $_POST;
                    $notificationClass = "success";
                    $notificationMsg = "The Reply  has been successfully updated.";
                }   
            }
            
        }
        
    }
    
    
    //display specific or delete
    if($replyID > 0 && ($action == "Edit" || $action == "Delete"))
    {
        $getDetailsRstArray = $admin_reply->GetDetails($replyID);
        
        if($action == "Delete" && count($getDetailsRstArray) > 0 && is_array($getDetailsRstArray))
        {
            $admin_reply->DeleteDetails($replyID);
            redirect(0,$GLOBAL_ADMIN_SITE_URL.$module_name);
        }   
    }
    else
    {
        $pageNo = (@$_GET["pageNo"] == "")? 1:$admin_database->cleanXSS($_GET["pageNo"],"int");
        $displayData = $admin_reply->DisplayAllDetails($pageNo,$GLOBAL_ITEM_PERPAGE,"reply",$filter);
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
                <li <?=($action == "")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>">View  Reply</a></li>
                
                <?=($action == "Edit")? "<li class='current'><a href='".$GLOBAL_ADMIN_SITE_URL.$module_name."&action=View&replyID=".$replyID."'>View Specific  Reply </a></li>": "";?>
            </ul>
            <div class="clr"></div>
        </div>
        <div id="content">
            <?
                if($action == "Add" || $action == "Edit")
                {
                    
            ?>
                <h2><?=($action == "Add")? "Add A New": "View"?> Reply</h2>
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
	                   
                        <?php
                            if($getDetailsRstArray["parentReplyID"] ==  0)
                            {
                                $debateOrReply = "Debate";
                            }
                            else
                            {
                                $debateOrReply = "Reply";
                            }
                        ?>

                        <li>
                            <div class="field_label"><?php echo $debateOrReply?></div>
                            <div class="field_input" style="padding-top: 8px">
                                <?php echo $getDetailsRstArray["replyContent"]?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label">Display Name</div>
                            <div class="field_input" style="padding-top: 8px">
                                <?php echo $getDetailsRstArray["displayName"]?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label"><?php echo $debateOrReply?> Date</div>
                            <div class="field_input" style="padding-top: 8px">
                                <?php
                                    $createdDateTime = strtotime($getDetailsRstArray["createdDateTime"]);
                                    $createdDateTime = date("Y-m-d H:i:s",$createdDateTime);

                                    echo $createdDateTime

                                ?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label"><?php echo $debateOrReply?> Status</div>
                            <div class="field_input" >
                                <?php
                                echo CboStatus("replyStatus","replyStatus",@$getDetailsRstArray["replyStatus"],"Please select...");
                                ?>
                            </div>
                            <div class="clr"></div>
                        </li>
					</ul>
                    
                    <input type="hidden" name="replyID" value="<?=($action == "Add")? "": $replyID;?>" />
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
                    <h2>Reply List</h2>
                    <form method="get">
                        <input type="hidden" name="m" value="debate">
                        Filter:  
                        <select name="status" style=" margin-left: 20px">
                            <option value="">Select one...</option>
                            <option value="pending">Pending...</option>
                            <option value="active">Active...</option>
                            <option value="hide">Hide...</option>
                        </select>
                        <input type="submit" class="primary button">
                    </form>      
                    <table>
                        <tr>
                            <th width="2%">No.</th>
                            <th width="50%">Reply</th>
                            <th width="10%">Status</th>
                            <th width="20%">Display Name</th>
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
                                        <td><?php
                                            echo $value["newsTitle"];
                                            if($value["parentReplyID"] > 0)
                                            {
                                                $parentRstArr = $admin_reply->GetDetails($value["parentReplyID"]);
                                                echo "<br>&#8627 ".$parentRstArr["replyContent"];
                                                echo "<br>&nbsp;&nbsp;&nbsp; &#8627;".$value["replyContent"];
                                            }
                                            else
                                                echo "<br>&#8627;".$value["replyContent"];
                                            ?></td>
                                        <td>
                                            <?php
                                                echo $value["replyStatus"];
                                            ?>
                                        </td>
                                        <td><?php echo $value["displayName"]?></td>
                                        <td>
                                            <span class="button-group">
                                            <a title="Edit  Reply" class="button icon edit" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Edit&replyID=".$value["replyID"]?>">Edit</a>
                                            <a title="Delete  Reply" class="button icon remove danger list" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=Delete&replyID=".$value["replyID"]?>">Remove</a>
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
                                    <td colspan="4">There is no reply yet.</td>
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