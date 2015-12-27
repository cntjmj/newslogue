<div id="logo">Admin Control Panel</div>
<!-- User info -->
<div id="userinfo">
  <img src="<?=$GLOBAL_ADMIN_GRAPHIC?>avatar.png" alt="<?=$_SESSION["adminUsername"]?>" />
  <div class="intro">
    Welcome <?=$_SESSION["adminUsername"]?><br />
    <br />
        <a href="<?=$GLOBAL_ADMIN_SITE_URL?>login">Logout</a>
  </div>
</div>