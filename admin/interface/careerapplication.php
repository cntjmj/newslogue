<?php

    $action = @$_GET["action"];
    $userID = $admin_database->cleanXSS(@$_GET["userID"],"int");
    
    
    
    $notificationClass = @$_SESSION[$module_name]["notificationClass"];
    $notificationMsg = @$_SESSION[$module_name]["notificationMsg"];
    unset($_SESSION[$module_name]);
    
    if(@$_POST["submit"] == "Submit")
    {
        $cmd = $admin_database->cleanXSS(@$_POST["cmd"]);
        
        
    }
    
    
    //display specific or delete
    if($userID > 0 && ($action == "View" || $action == "Delete"))
    {
        $getDetailsRstArray = $admin_user->GetDetails($userID);
        
            
        if($action == "Delete" && count($getDetailsRstArray) > 0 && is_array($getDetailsRstArray))
        {
            $admin_user->DeleteDetails($userID);
            redirect(0,$GLOBAL_ADMIN_SITE_URL.$module_name);
        }   
    }
    $pageNo = (@$_GET["pageNo"] == "")? 1:$admin_database->cleanXSS($_GET["pageNo"],"int");
    
    if($action == "")
        $displayData = $admin_user->DisplayAllDetailsCareerApp($pageNo,$GLOBAL_ITEM_PERPAGE,"");
    
    
    
?>



<div id="container">
    <div id="logo">Admin Control Panel</div>
    
          
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
                <li <?=($action == "")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>">View Talk To Us</a></li>
                
                <?=($action == "View")? "<li class='current'><a href='".$GLOBAL_ADMIN_SITE_URL.$module_name."&action=View&userID=".$userID."'>View Specific Talk</a></li>": "";?>
            </ul>
            <div class="clr"></div>
        </div>
        <div id="content">
            <?
                if($action == "Add" || $action == "View")
                {
                    
            ?>
                <h2><?=($action == "Add")? "Add A New": "View"?> Talk To Us</h2>
                <?
                    
                    if($notificationMsg != "")
                    {
                        echo "<div id='notification_panel'>
                                <div class='".$notificationClass."'>".$notificationMsg."</div>
                              </div>";
                    }
                ?>
                
            <?
                }
                else
                {
                    if($pageNo == 1)
				        $num = 1;
				    else
				        $num = ($GLOBAL_ITEM_PERPAGE * ($pageNo-1)) +1;
            ?>
                    <h2>Career Application List</h2>
                    <a href="<?=$GLOBAL_ADMIN_WEB_ROOT?>export/career_xls.php" class="button">Export All</a>
                    <br /><br />
                    <table>
                        <tr>
                            <th width="2%">No.</th>
                            <th width="20%">Full Name</th>
                            <th width="15%">Email Address</th>
                            <th width="15%">Contact No</th>
                            <th width="28%">Resume</th>
                            <th width="20%">Career Position</th>
                            
                        </tr>
                        <?
                            if(is_array(@$displayData["List"]) && count(@$displayData["List"]) > 0)
                            {
                                foreach($displayData["List"] as $id => $value)
                                {
                        ?>
                                    <tr>
                                        <td><?=($num)?>.</td>
                                        <td><?=$value["fullname"]?></td>
                                        <td><?=$value["emailaddress"]?></td>
                                        <td><?=$value["contactno"]?></td>
                                        <td><?=$value["careerFile"]?></td>
                                        <td><?=$value["careerposition"]?></td>
                                        
                                        
                                        
                                    </tr>
                        <?
                                    $num++;
                                }
                            }
                            else
                            {
                        ?>
                                <tr>
                                    <td colspan="6">There is no career application yet.</td>
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