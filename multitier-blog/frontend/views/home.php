<?php
require_once __DIR__ . "/../Template.php";

HomeTemplate::HomeHeader("Home");
?>

<h1>Welcome home: <?= $this->home ?></h1>


<?php Template::footer(); ?>