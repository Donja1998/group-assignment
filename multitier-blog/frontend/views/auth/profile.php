<?php
require_once __DIR__ . "/../../Template.php";

Template::header("Profile");
?>
<div id="profileDiv">
    <div id="user">
    Logged in as <b><?= $this->user->username ?></b>
</div>
</p>

<?php if ($this->user->user_role === "admin") : ?>
    <p>(admin user)</p>
<?php endif; ?>

<h2>Set profile picture</h2>

<div id="profileBtns">
<?php if ($this->user->profile_pic_url) : ?>
    <img src="<?= $this->home . $this->user->profile_pic_url?>" alt="" width="100">
<?php endif; ?>


<form action="<?= $this->home ?>/auth/profile_pic" method="post" enctype="multipart/form-data">
    <input type="file" name="profile_pic"> <br>
    <input type="submit" value="Save" class="btn">
    
</form>

<div id="logOut">
<form action="<?= $this->home ?>/auth/logout" method="post">
    <input type="submit" value="Log out" class="btn delete-btn">
</form>
</div>
</div>
</div>
<?php Template::footer(); ?>