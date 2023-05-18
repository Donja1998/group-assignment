<?php
require_once __DIR__ . "/../../Template.php";

Template::header("Register user", $this->model["error"]);
?>

<h1>Register user</h1>

<form action="<?= $this->home ?>/auth/register" method="post">
    <input type="text" name="username" placeholder="Username"> <br>
    <input type="password" name="password" placeholder="Password"> <br>
    <input type="password" name="confirm_password" placeholder="Confirm password"> <br>
    <input type="submit" value="Save" class="btn">
</form>

<?php Template::footer(); ?>