<?php
	require_once 'nl-init.php';
	require_once './common/nl-common.php';
	
	if (is_mobile()) {
		header("location: ".CONFIG_PATH::GLOBAL_M_BASE);
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Newslogue</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>
<style>
body {
	font-family: 'Merriweather', sans-serif;
	background-color: #132934;
}
p {
	opacity: 0.7;
	letter-spacing: 1px;
	font-size:12px;
}
a{
	text-decoration: none;
}
</style>
</head>

<body>
<div style="width:600px; height:100px; position:absolute; left:50%; top:50%; margin-left:-300px; margin-top:-50px; text-align:center;">
	<a href="mailto:joewong@newslogue.com" target="_blank">
		<img src="logo.jpg" >
		<p style="color:#FFF;">Community Sourced Debates</p>
		
		<p style="color:#FFF;">Please visit us using mobile device.<!--Newslogue is under periodical maintenance status.--></p>
	</a>
</div>
</body>
</html>