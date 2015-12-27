<?php

    $action = @$_GET["action"];
    $userID = isset($_GET['userID']) ? $admin_database->cleanXSS(@$_GET["userID"],"int") : null;
    $adminID = isset($_GET['adminID']) ? $admin_database->cleanXSS(@$_GET["adminID"],"int") : null;

    
    
    $notificationClass = @$_SESSION[$module_name]["notificationClass"];
    $notificationMsg = @$_SESSION[$module_name]["notificationMsg"];
    unset($_SESSION[$module_name]);
    
    if(@$_POST["submit"] == "Submit") {
        $cmd = $admin_database->cleanXSS(@$_POST["cmd"]);

        switch ($cmd) {
            case 'AddUser':
                $errorArray = $admin_user->ValidateForm($_POST, "Add");
                if (is_array($errorArray) && count($errorArray)) {
                    foreach ($errorArray as $errID => $errValue) {
                        $notificationMsg .= $errValue . "<br />";
                    }

                    $notificationClass = "error";
                } else {
                    $addDetailsRstArray = $admin_user->AddDetails(@$_POST,'user');
                    if ($addDetailsRstArray) {
                        $getDetailsRstArray = $_POST;
                        $_SESSION[$module_name]["notificationClass"] = "success";
                        $_SESSION[$module_name]["notificationMsg"] = "The User <strong>'" . $_POST["fullname"] . "'</strong> has been successfully saved.";
                        redirect("", $GLOBAL_ADMIN_SITE_URL . $module_name . "&action=Edit&userID=" . $addDetailsRstArray);
                    }
                }
                break;

            case 'EditUser':

                $errorArray = $admin_user->ValidateForm($_POST, "Edit");

                if (is_array($errorArray) && count($errorArray)) {
                    foreach ($errorArray as $errID => $errValue) {
                        $notificationMsg .= $errValue . "<br />";
                    }

                    $notificationClass = "error";
                } else {

                    $setDetailsRstArray = $admin_user->SetDetails(@$_POST, $userID);
                    if ($setDetailsRstArray) {
                        $getDetailsRstArray = $_POST;
                        $notificationClass = "success";
                        $username = @$_POST["fullname"];
                        if ($username == "")
                        	$username = @$_POST["fbName"];
                        $notificationMsg = "The User <strong>'" . $username . "'</strong> has been successfully updated.";
                    }
                }
                break;

            case 'AddAdmin':
                $errorArray = $admin_user->ValidateAdminForm($_POST, "Add");
                if (is_array($errorArray) && count($errorArray)) {
                    foreach ($errorArray as $errID => $errValue) {
                        $notificationMsg .= $errValue . "<br />";
                    }

                    $notificationClass = "error";
                } else {
                    $addDetailsRstArray = $admin_user->AddDetails(@$_POST,'admin');
                    if ($addDetailsRstArray) {
                        $getDetailsRstArray = $_POST;
                        $_SESSION[$module_name]["notificationClass"] = "success";
                        $_SESSION[$module_name]["notificationMsg"] = "The Admin <strong>'" . $_POST["username"] . "'</strong> has been successfully saved.";
                        redirect("", $GLOBAL_ADMIN_SITE_URL . $module_name . "&action=EditAdmin&adminID=" . $addDetailsRstArray);
                    }
                }


                break;

            case 'EditAdmin':
                $errorArray = $admin_user->ValidateAdminForm($_POST, "Edit");

                if (is_array($errorArray) && count($errorArray)) {
                    foreach ($errorArray as $errID => $errValue) {
                        $notificationMsg .= $errValue . "<br />";
                    }

                    $notificationClass = "error";
                } else {

                    $setDetailsRstArray = $admin_user->SetAdminDetails(@$_POST, $adminID);
                    if ($setDetailsRstArray) {
                        $getDetailsRstArray = $_POST;
                        $notificationClass = "success";
                        $notificationMsg = "The Admin <strong>'" . $_POST["username"] . "'</strong> has been successfully updated.";
                    }
                }

                break;


        }
    }
    
    //display specific or delete
    if($userID > 0 && ($action == "EditUser" || $action == "DeleteUser"))
    {
        $getDetailsRstArray = $admin_user->GetDetails($userID);
            
        if($action == "DeleteUser" && count($getDetailsRstArray) > 0 && is_array($getDetailsRstArray))
        {
            $admin_user->DeleteDetails($userID);
            redirect(0,$GLOBAL_ADMIN_SITE_URL.$module_name);
        }   
    } elseif($adminID > 0 && ($action == "EditAdmin" || $action == "DeleteAdmin")) {
        $getDetailsRstArray = $admin_user->GetAdminDetails($adminID);
        if($action == "DeleteAdmin" && count($getDetailsRstArray) > 0 && is_array($getDetailsRstArray))
        {
            $admin_user->DeleteAdminDetails($adminID);
            redirect(0,$GLOBAL_ADMIN_SITE_URL.$module_name);
        }


    } else  {
        $pageNo = (@$_GET["pageNo"] == "")? 1:$admin_database->cleanXSS($_GET["pageNo"],"int");
        $displayData = $admin_user->DisplayAllDetails($pageNo,$GLOBAL_ITEM_PERPAGE,"");
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
                <li <?=($action == "")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>">View Users</a></li>
                <li <?=($action == "AddUser")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>&action=AddUser">Add User</a></li>
                <li <?=($action == "AddAdmin")? "class='current'":""?>><a href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name?>&action=AddAdmin">Add Admin</a></li>
                <?=($action == "Edit")? "<li class='current'><a href='".$GLOBAL_ADMIN_SITE_URL.$module_name."&action=View&userID=".$userID."'>Edit Specific User </a></li>": "";?>
            </ul>
            <div class="clr"></div>
        </div>
        <div id="content">
            <?
                if($action == "AddUser" || $action == "EditUser")
                {
                    
            ?>
                <h2><?=($action == "AddUser")? "Add A New": "Edit"?> User</h2>
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
							<div class="field_label">Full Name</div>
							<div class="field_input" >
<?php
if (isset($getDetailsRstArray["fbName"]) 
		&& $getDetailsRstArray["fbName"] != "" 
		&& $getDetailsRstArray["fbName"] != "undefined")
	echo generateInput("text","fbName","","fbName",@$getDetailsRstArray["fbName"]);
else
	echo generateInput("text","fullname","","fullname",@$getDetailsRstArray["fullname"]);
?>

                            </div>
							<div class="clr"></div>
						</li>
                        <li>
                            <div class="field_label">Email Address</div>
                            <div class="field_input">
<?php
if (isset($getDetailsRstArray["fbEmail"]) 
		&& $getDetailsRstArray["fbEmail"] != ""
		&& $getDetailsRstArray["fbEmail"] != "undefined")
	echo generateInput("text","fbEmail","medium","fbEmail",@$getDetailsRstArray["fbEmail"]);
else
	echo generateInput("text","emailaddress","medium","emailaddress",@$getDetailsRstArray["emailaddress"]);
?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label">Password</div>
                            <div class="field_input">
                                <?=generateInput("password","password","medium","password")?>
                            </div>
                            <div class="clr"></div>
                        </li>
                        <li>
                            <div class="field_label">User Status</div>
                            <div class="field_input" >
                                <?php
                                echo CboStatus("userStatus","userStatus",@$getDetailsRstArray["userStatus"],"Please select...");
                                ?>
                            </div>
                            <div class="clr"></div>
                        </li>
					</ul>
                    <input type="hidden" name="cmd" value="<?=($action == "AddUser")? "AddUser": "EditUser"?>" />
                    <input type="submit" name="submit" value="Submit" class="primary button" />
                    <input type="reset" value="Reset" class="button"/>
                </form>
            <?
                }
                elseif($action == "AddAdmin" || $action == "EditAdmin") {

                    ?>
                    <h2><?=($action == "AddAdmin")? "Add A New": "Edit"?> Admin</h2>
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
                                <div class="field_label">Username</div>
                                <div class="field_input" >
                                    <?=generateInput("text","username","","username",@$getDetailsRstArray["username"])?>
                                </div>
                                <div class="clr"></div>
                            </li>
                            <li>
                                <div class="field_label">Access Level</div>
                                <div class="field_input">
                                    <?=generateInput("text","accessLevel","medium","accessLevel",@$getDetailsRstArray["accessLevel"])?>
                                </div>
                                <div class="clr"></div>
                            </li>
                            <li>
                                <div class="field_label">Password</div>
                                <div class="field_input">
                                    <?=generateInput("password","password","medium","password")?>
                                </div>
                                <div class="clr"></div>
                            </li>
                        </ul>
                        <input type="hidden" name="cmd" value="<?=($action == "AddAdmin")? "AddAdmin": "EditAdmin"?>" />
                        <input type="submit" name="submit" value="Submit" class="primary button" />
                        <input type="reset" value="Reset" class="button"/>
                    </form>
                <?





                } else {

                    if($pageNo == 1)
				        $num = 1;
				    else
				        $num = ($GLOBAL_ITEM_PERPAGE * ($pageNo-1)) +1;
            ?>
                    <h2 id="user-list-heading">User List</h2>
                    <div>
                    <label>User Type:</label>
                    <select id="select-user-type">
                        <option>User</option>
                        <option>Admin</option>
                    </select>
                    </div>

                    <table id="user-results">
                        <thead>

                        <tr>
                            <th width="2%">No.</th>
                            <th width="50%">Name</th>
                            <th width="20%">Email Address</th>
                            <th width="13%">Status</th>
                            <th width="15%">Action</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?
                            if(is_array(@$displayData["List"]) && count(@$displayData["List"]) > 0)
                            {
                                foreach($displayData["List"] as $id => $value)
                                {
                        ?>
                                    <tr>
                                        <td><?=($num)?>.</td>
                                        <td><?=(isset($value["fullname"])&&$value["fullname"]!="")?$value["fullname"]:$value["fbName"]?></td>
                                        <td><?=(isset($value["emailaddress"])&&$value["emailaddress"]!="")?$value["emailaddress"]:$value["fbEmail"]?></td>
                                        <td><?=$value["userStatus"]?></td>
                                        <td>
                                            <span class="button-group">
                                            <a title="Edit User" class="button icon edit" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=EditUser&userID=".$value["userID"]?>">Edit</a>
                                            <a title="Delete User" class="button icon remove danger list" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=DeleteUser&userID=".$value["userID"]?>">Remove</a>
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
                        </tbody>
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
        
		$("#user_form").validate({
			rules: {
				
				fullname: "required",
				emailaddress: {
                    required: true,
                    email: true
                },
                userStatus: "required"
				
			},
            errorPlacement: function(error, element) 
            {
                error.insertAfter(element);
                $("#gradient").css("height",$(document).height()+"px")  
            }
		});

        $("#select-user-type").change(function() {
            var obj = $(this);
            var userType = obj.val();
            $.ajax(
                {
                    type: "POST",
                    url: "<?=$GLOBAL_ADMIN_AJAX?>ajax_get_admin.php",
                    data:
                    {
                        userType: userType
                    },
                    cache: false,
                    success: function(response_html)
                    {
//                        console.log(response_html);
                        $('#user-results').html(response_html);
                    }

                })


        });
        
        
    });
</script>