<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once 'template/template.php';

	$auth = Auth::getInstance();
	$userID = $auth->getUserID();

	//if ($userID <= 0)
	//	header("location: ".CONFIG_PATH::GLOBAL_M_BASE);

	htmlBegin("AboutController");
	htmlHead("About Us");
	htmlBodyBegin();
	htmlHeader();
?>
<div id="aboutus">
        <h1>ABOUT US</h1>
</div>

<div style="height:75px; text-align: center";>
    <img src="images/Newslogue_logo.jpg" alt="Newslogue Logo" width="300";>
</div>


<div id="about-text">
        <h1>THE NEW WAY FOR DEBATE</h1>
       <div><p>Newslogue empowers its users to view the world from another perspective. By using social media we create mutual understandings where people share their thoughts and perspectives.</p><p>We aim to arm users with informed and educated viewpoints from diverse backgrounds, ethnicities, cultures and nationalities.</p></div>
</div>

<div>
    <img src="images/about_us2.jpg" class="about_us_img">
</div>
<?php
	htmlFooter();
	htmlBodyEnd();
?>