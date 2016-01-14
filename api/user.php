<?php
	require_once '../nl-init.php';
	require_once '../class/nl-user-class.php';
	require_once '../class/nl-auth-class.php';
	require_once 'api_headers.php';
	
	try {
		if (is_post()) {
			$fields = array();

			$fbID = _post("fbID", "");
			
			$user = new User();
			$auth = Auth::getInstance();
			
			if ($fbID != "") {
				$fbName = _post("fbName", "");
				$fbEmail = _post("fbEmail", "");

				if ($fbName == "" || $fbEmail == "")
					throw new Exception("incorrect parameters", -1);
				
				$fields = array(
						"fbID" => $fbID,
						"fbName" => $fbName,
						"fbEmail" => $fbEmail
				);
				
				$user->createUser($fields);
				$auth->fbLogin($fbEmail, $fbID, $fbName);
				echo json_encode(array("errCode"=>0, "errMessage"=>"user registered successfully"));
			} else {
				$emailaddress = _post("emailaddress", "");
				$displayName = _post("displayName", "");
				$pwd = _post("pwd", "");

				if ($emailaddress == "" || $displayName == "" || $pwd == "")
					throw new Exception("incorrect parameters", -1);

				$fields = array(
						"emailaddress" => $emailaddress,
						"displayName" => $displayName,
						"pwd" => $pwd
				);

				$user->createUser($fields);
				echo json_encode(array("errCode"=>0, "errMessage"=>"an verification email has been sent to your email address"));
			}
		}
	} catch (Exception $e) {
		$result = array("errCode" => $e->getCode(), "errMessage" => $e->getMessage());
		echo json_encode($result);
	}
?>