<?php
	require_once '../nl-init.php';
	require_once '../class/nl-user-class.php';
	require_once '../class/nl-auth-class.php';
	require_once 'api_headers.php';
	
	try {
		if (is_get()) {
			$userID = _get('userID', 0);
			
			if ($userID <= 0)
				throw new Exception("user does not exists", -1);
			
			$auth = Auth::getInstance();
			if ($auth->getUserID() != $userID)
				throw new Exception("unauthorized", -1);
			
			$user = new User($userID);
			echo $user->getJson();
		} else if (is_post()) {
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
				$cpwd = _post("cpwd", "");

				if ($emailaddress == "" || $displayName == "" || $pwd == "")
					throw new Exception("incorrect parameters", -1);

				/*
				 * cpwd field is optional, because
				 * the client should be responsible
				 * for password checking
				 */
				if ($cpwd != "" && $cpwd != $pwd)
					throw new Exception("inconsistent password", -1);

				$fields = array(
						"emailaddress" => $emailaddress,
						"displayName" => $displayName,
						"pwd" => $pwd
				);

				if (0 >= $user->createUser($fields)->getUserID())
					throw new Exception("failed to register user", -1);

				/* 
				 * Function: disable email verification
				 * Date: 2016/03/01
				 */
				//echo json_encode(array("errCode"=>0, "errMessage"=>"an verification email has been sent to your email address"));
				echo json_encode(array("errCode"=>0, "errMessage"=>"
					user registered successfully"));
			}
		} else if (is_put()) {
			$userID = _get('userID', 0);
			$displayName = _put("displayName", "");
			$fullname = _put("fullname", "");
				
			if ($userID <= 0)
				throw new Exception("user does not exists", -1);
					
			$auth = Auth::getInstance();
			if ($auth->getUserID() != $userID)
				throw new Exception("unauthorized", -1);
			
			if ($displayName == "" || $fullname == "")
				throw new Exception("missing fields", -1);
			
			$fields = array("displayName"=>$displayName, "fullname"=>$fullname);

			$user = new User($userID);
			$user->updateUser($fields);
			
			echo json_encode(array("errCode"=>0));
		}
	} catch (Exception $e) {
		$result = array("errCode" => $e->getCode(), "errMessage" => $e->getMessage());
		echo json_encode($result);
	}
?>