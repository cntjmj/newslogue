<?php
	require_once '../nl-init.php';
	require_once '../class/nl-recovery-class.php';
	require_once '../class/nl-user-class.php';
	require_once '../class/nl-auth-class.php';
	require_once 'api_headers.php';	

	try 
	{
		if (is_post())
		{
			$step = _post("step", "");

			if ($step == 1)
			{
				$emailaddress = _post("emailaddress", "");
				if ($emailaddress == "")
					throw new Exception("incorrect parameter", -1);
				else
				{
					$re = new Recovery();
					$re->sendRecoveryEmail($emailaddress);
					echo json_encode(array("errCode"=>0, "errMessage"=>"an email has 
						been sent to your email address"));
				}
			}
			else if ($step == 2)
			{
				$email = _post("emailaddress", "");
				$ucode = _post("uniqCode", "");
				$newPwd = _post("password", "");

				if (strlen($newPwd) < 6 || strlen($newPwd) > 20)
					throw new Exception("The length of new password shuould be between 6 and 20", -1);

				if ($email == "" || $ucode == "")
					throw new Exception("Invalid url", -1);
				else
				{
					$re = new Recovery();
					$re->changePwd($email, $ucode, $newPwd);
					echo json_encode(array("errCode"=>0, "errMessage"=>"You has changed your password"));
				}
			}
		}
	}
	catch(Exception $e)
	{
		$result = array("errCode" => $e->getCode(), "errMessage" => $e->getMessage());
		echo json_encode($result);
	}

?>
