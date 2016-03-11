<?php
	require_once '../nl-init.php';
	require_once '../class/nl-auth-class.php';
	require_once 'template/template.php';

	$auth = Auth::getInstance();
	$userID = $auth->getUserID();
	
	//if ($userID <= 0)
	//	header("location: ".CONFIG_PATH::GLOBAL_M_BASE);

	htmlBegin("ContactController");
	htmlHead("Contact Us");
	htmlBodyBegin();
	htmlHeader();
?>
	<main>
		<section>
			<div class="debate_head_main" style="margin-bottom:20px;">
				<div class="debate_heading"><span class="debate_title">Contact</span></div>
			</div>  
        </section>
        <section id="user_contact">
          <form class="form" id="contactForm" name="contactForm" enctype="multipart/form-data" method="post" ng-submit="contact.submitForm()">
            <div><span class="fa fa-user fa-lg"></span>
              <input name="displayName" id="username" ng-model="contact.displayName" type="text" required class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="NAME" style="width: 210px;">
            </div>
            <div><span><i class="fa fa-envelope fa-lg"></i></span>
              <input name="emailaddress" id="emailaddress" ng-model="contact.emailaddress" type="email" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" class="feedback-input" placeholder="EMAIL" style="width: 210px;">
            </div>
            <!--div><span><i class="fa fa-phone fa-lg"></i></span>
              <input name="phone" type="text" required pattern="[\+]\d{2}[\(]\d{2}[\]\d{4}[\-]\d{4}" class="feedback-input" placeholder="PHONE" id="phone" style="width: 210px;">
            </div-->
            <div><span><i class="fa fa-pencil fa-lg"></i></span>
              <textarea name="message" required id="message" ng-model="contact.message" placeholder="Write a message..." style="height: 80px; width: 210px;"></textarea>
            </div>
            <div class="submit">
              <input type="submit" value="Submit" id="submit" ng-disabled="contact.submitting">
	        </div>	
	        <div ng-show="contact.submitted"><p>Form successfully submitted</p>
			</div>
          </form><br>
	    </section>
	    <section id="company_info">
			<h2>COMPANY INFORMATION</h2>
			<div class="comp-info">
				<div class="comp-address">NEWSLOGUE<br />912/530 LITTLE COLLINS ST.<br />MELBOURNE, VIC 3000<br />SERVICE@NEWSLOGUE.COM<br />(03) 9909 7099</div>
				<!--div class="comp-google"><i class="fa fa-map-marker fa-4x"></i><br/><a href="#">FIND US ON GOOGLE MAPS</a></div-->
			</div>
		</section>	
		<div id="map" style="width:100%; height: 200px"></div>
		<!--section id="faq">
			<div>
				<h2>FAQ</h2><br>
				<p>-QUESTION 1?<br>-QUESTION 2?<br>-QUESTION 3?<br>-QUESTION 4?<br>-QUESTION 5?</p>
			</div>
		</section--><br>
	</main>
	<script>var userID = <?=$userID?>;</script>
	<script	src="http://maps.googleapis.com/maps/api/js"></script>
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
	htmlFooter();
	htmlBodyEnd();
?>