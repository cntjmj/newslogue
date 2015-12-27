<?php
    session_unset();
    if(@$_POST["login"] == "Login")
    {
        $loginRstArray = $admin_account->AdminLogin(@$_POST);
        
        if(is_array($loginRstArray) && count($loginRstArray) > 0)
        {
            
            $_SESSION["adminID"] = $loginRstArray["adminID"];
            $_SESSION["adminUsername"] = $loginRstArray["username"];
            
            redirect(0,$GLOBAL_ADMIN_SITE_URL."news");
            
        }
    }
?>


    <div id="login_panel">
        <div id="logo"></div>
        <div class="clr"></div>
        <div id="application">
            <div id="login_navigation_panel">
                <ul>
                    <li class="current"><a href="javascript:void(0)">Login</a></li>
                </ul>
                <div class="clr"></div>
                
            </div>
            <div id="login_content_panel">
				<form method="post">
					<ul>
						<li>
							<div class="field_label">Username</div>
							<div class="field_input"><input type="text" name="username" value="" /></div>
							<div class="clr"></div>
						</li>
						<li>
							<div class="field_label">Password</div>
							<div class="field_input"><input type="password" name="password" value=""/></div>
							<div class="clr"></div>
						</li>
					</ul>
                    <input type="hidden" />
                    <input type="submit" class="primary button" name="login" value="Login" />
                    <input type="reset" class="button" value="Reset" />
				</form>
                
			</div>
        </div>
    </div>