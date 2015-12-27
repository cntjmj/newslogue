<?
require_once('../config.php');


$pageNo = (@$_GET["pageNo"] == "")? 1:$admin_database->cleanXSS($_GET["pageNo"],"int");
$displayData = $admin_user->DisplayAllDetails($pageNo,$GLOBAL_ITEM_PERPAGE,"",$_POST['userType']);
$module_name = (@$_GET["m"] == "")? "user":$_GET["m"];
if($pageNo == 1) {
    $num = 1;
} else {
    $num = ($GLOBAL_ITEM_PERPAGE * ($pageNo - 1)) + 1;
}
if($_POST['userType'] == 'User') { ?>

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
                                        <td><?=$value["fullname"]?></td>
                                        <td><?=$value["emailaddress"]?></td>
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

<? } else { ?>
                    <thead>

                        <tr>
                            <th width="2%">No.</th>
                            <th width="63%">Name</th>
                            <th width="20%">Access Level</th>
<!--                            <th width="13%">Status</th>-->
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
                                        <td><?=$value["username"]?></td>
                                        <td><?=$value["accessLevel"]?></td>
<!--                                        <td>--><?//=$value["userStatus"]?><!--</td>-->
                                        <td>
                                            <span class="button-group">
                                            <a title="Edit User" class="button icon edit" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=EditAdmin&adminID=".$value["adminID"]?>">Edit</a>
                                            <a title="Delete User" class="button icon remove danger list" href="<?=$GLOBAL_ADMIN_SITE_URL.$module_name."&action=DeleteAdmin&adminID=".$value["adminID"]?>">Remove</a>
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


<?}?>