<?php
	require_once __DIR__."/../../nl-config.php";

	function htmlHead($title = "Newslogue", $metaArray = null) {
?>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

	<title><?=$title?></title>

	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/style.css"> <!--CSS-->
	<link rel="stylesheet" href="<?=CONFIG_PATH::GLOBAL_M_BASE?>font-awesome-4.4.0/css/font-awesome.min.css"> <!-- FONT AWESOME -->
	<link rel='stylesheet' href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/gfont.css"><!-- GOOGLE FONT -->
	<link rel="stylesheet" href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/menu.css"><!--CSS MENU-->
	<link rel="stylesheet" href="<?=CONFIG_PATH::GLOBAL_M_BASE?>css/magnific-popup.css"> <!--CSS POPUP-->

	<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>js/jquery.min.js"></script>
	<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>js/angular.min.js"></script>
	<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>js/angular-sanitize.min.js"></script>
	<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>js/ng-infinite-scroll.min.js"></script>
	<script src="<?=CONFIG_PATH::GLOBAL_WWW_BASE?>js/nl-common.js"></script>

	<base href="/" />    <!-- use anjuarjs location service, have to add this line -->
</head>
<?php
	}
?>