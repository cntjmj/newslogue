<?php
	include_once "config.php";
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 9]><!--> 
<html class="no-js" lang="en" itemscope itemtype="http://schema.org/Product"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
			 More info: h5bp.com/b/378 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Newslogue</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="humans.txt">

	

	<!-- Facebook Metadata /-->
	<meta property="fb:page_id" content="" />
	<meta property="og:image" content="" />
	<meta property="og:description" content=""/>
	<meta property="og:title" content=""/>

	<!-- Google+ Metadata /-->
	<meta itemprop="name" content="">
	<meta itemprop="description" content="">
	<meta itemprop="image" content="">


	<?php
		include_once "includes/scripts.php";
	?>
</head>

<body>
	<?php
		include_once "includes/header.php";
	?>
	
	<div class="main-content">
		<div class="row">
			<div class="breadcrumbs">Contact</div>
			<div class="main-panel">
				<article class="sub contact">
					<h1>Get in touch with us</h1>
					<h4>Enquiry</h4>
					<p>
						<!-- Hello! Let's talk. If your hair's on fire and you need to email us RIGHT NOW. If you have a moment to peruse the menu, you can choose from a range of options below. -->
						We are here to anser any questions you may have about <b>our services</b>. Reach out to us and we'll respond as soon as possible.					
					</p>

					<!-- <h4>Editorial</h4>
					<p>
					Submit samples of your work (including videography) and/or your editorial pitches to submissions@newslogue.com.
					</p> -->

<!-- 					<h4>Advertising</h4>
					<p>
					To learn about advertising with Newslogue, don't hesitate to contact us. 
					</p> -->

					<h4>Contact Information</h4>
					<p>
					Tel: (03) 9909 7099 <br>
					E-mail: 					 
						<script>
					// click-able email address against email scanner...
							var first = 'service';
							var last = 'newslogue.com';
							document.write('<a href="mailto: '+first + '@' + last + '">'+first+'@'+last+'<\/a>');
						</script>
						<br>
					Office: 912/530, Little Collins St, Melbourne, Victoria 3000
					</p>
					<!-- <div id="map" style="width: 100%; height: 290px"></div> -->
					<div id="map" style="width: 100%; height: 380px;margin-bottom: 30px;"></div>
				</article>
			</div>

			<div class="rightbar">
				<?php
					include_once "includes/enquiry.php";
				?>
			</div>
		</div>
	</div>

	<!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.2&sensor=false"></script>
	<script type="text/javascript">
		google.maps.event.addDomListener(window, 'load', init)
		var theplace = new google.maps.LatLng(5.940133, 116.097756);

		function init() {
	        var mapOptions = {
	        zoom: 13,
	        maxZoom:20,
	        minZoom:10,
	        scrollwheel: false,
	        center: new google.maps.LatLng(5.940133, 116.097756), // Show Unit
	        //disableDefaultUI: true,
	        panControl: false,
		  	zoomControl: true,
		  	zoomControlOptions: {
		        style: google.maps.ZoomControlStyle.LARGE,
		        position: google.maps.ControlPosition.RIGHT_TOP
		    },
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,
	        styles: [{featureType:'water',stylers:[{color:'#46bcec'},{visibility:'on'}]	},{featureType:'landscape',stylers:[{color:'#e7ecee'}]},{featureType:'road',stylers:[{saturation:-100},{ color: '#FFFFFF' },{lightness:45}]},{featureType:'road.highway',stylers:[{visibility:'simplified'}]},{featureType:'road.arterial',elementType:'labels.icon',stylers:[{visibility:'off'}]},{featureType:'administrative',elementType:'labels.text.fill',stylers:[{color:'#444444'}]},{featureType:'transit',stylers:[{visibility:'off'}]},{featureType:'poi',stylers:[{visibility:'off'}]}]
	                };



	        var mapElement = document.getElementById('map');
	        var map = new google.maps.Map(mapElement, mapOptions);

	        marker = new google.maps.Marker({
    			map:map,
			    draggable:true,
			    animation: google.maps.Animation.DROP,
			    position: theplace
			  });
			  


	        

	        
	        var contentStringScp = '<div id="content" style="width:350px;height:232px; overflow-x: hidden"><h1>Newslogue</h1><br>101, Block D, <br /> Philieo Damansara I, <br />Jalan 16/11,<br />46350 Petaling Jaya, Malaysia <br><br><div><a href="tel:+603 76651228">T: +603 76651228</a></div><div><a href="tel:+603 7665 1171">F: +603 7665 1171</a></div><br><br><b>GPS Coordinates :</b><br>N : 5 58.184 E : 116 3.902</div>';
	        
	        var infowindowScp = new google.maps.InfoWindow({
	            content: contentStringScp
	        });
	        


	        google.maps.event.addListener(marker, 'click', function() {
	            infowindowScp.open(map,marker);
	        });
	        ;
	        

	    }
	</script> -->
	<script	src="http://maps.googleapis.com/maps/api/js">
	</script>

	<script>
		var myCenter=new google.maps.LatLng(-37.8168355, 144.9570583);

		function initialize()
		{
		var mapProp = {
		  	center:myCenter,
		  	zoom:14,
		  	panControl:true,
		    zoomControl:true,
		    mapTypeControl:true,
		    scaleControl:true,
		    streetViewControl:true,
		    overviewMapControl:true,
		    rotateControl:true,   
		    scrollwheel:false, 
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		  };

		var map=new google.maps.Map(document.getElementById("map"),mapProp);

		var marker=new google.maps.Marker({
		  position:myCenter,
		  });

		marker.setMap(map);

		var infowindow = new google.maps.InfoWindow({
		  // content:'<div id="content" style="width:220px;height:120px; overflow-x: hidden"><strong>Newslogue</strong><br/>912/530 Little Collins St,<br /> Melbourne, VIC 3000 <br /><a href="tel:+603 76651228">T: +603 76651228</a><a href="tel:+603 7665 1171">&nbsp;&nbsp;&nbsp;F: +603 7665 1171</a></div>'
		  content:'<div id="content" style="width:220px;height:120px; overflow-x: hidden"><strong>Newslogue</strong><br/>912/530 Little Collins St,<br /> Melbourne, VIC 3000 <br /><a href="tel:0399097099">T: (03) 9909 7099</a></div>'
		  });

		google.maps.event.addListener(marker, 'click', function() {
		  infowindow.open(map,marker);
		  });
		}

		google.maps.event.addDomListener(window, 'load', initialize);
	</script>

<?php
	include_once "includes/footer.php";
?>