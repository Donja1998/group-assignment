<?php
require_once __DIR__ . "/../../Template.php";

Template::header("Login", $this->model["error"]);
?>

<h1>Login</h1>

<form action="<?= $this->home ?>/auth/login" method="post">
    <input type="text" name="username" placeholder="Username"> <br>
    <input type="password" name="password" placeholder="Password"> <br>
    <input type="submit" value="Log in" class="btn">
</form>

<p>
    Not registered? 
    <a href="<?= $this->home ?>/auth/register">Register user</a>
</p>

<?php Template::footer(); ?>