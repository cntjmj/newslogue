<?php
include_once "../config.php";


$banner = $admin_database->cleanXSS(@$_POST["banner"]);
$yAxis = $admin_database->cleanXSS(@$_POST['yAxis']);
//$banner_location = $GLOBAL_BANNER_TNB . $banner;
$banner_location = "../../uploads/banner/thumbnail/" . $banner;
$im = new ImageManipulator($banner_location);
//fb($im);
$x1 = 0;
$y1 = $yAxis;
$x2 = 780;
$y2 = 390;
$im->crop($x1,$y1,$x2,$y2);
//fb($im);
$im->save($banner_location);

