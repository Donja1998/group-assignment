<?php
require_once __DIR__ . "/../../Template.php";

Template::header("Unauthorized");
?>

<h1>Unauthorized</h1>

<p>
    You're not authorized to view this page
</p>

<?php if ($this->user) : ?>
    <p>
        Return home:
        <a href="<?= $this->home ?>/">Home</a>
    </p>
<?php else : ?>
    <p>
        Login here:
        <a href="<?= $this->home ?>/auth/login">Login</a>
    </p>

    <p>
        Not registered?
        <a href="<?= $this->home ?>/auth/register">Register user</a>
    </p>

<?php endif; ?>

<?php Template::footer(); ?>