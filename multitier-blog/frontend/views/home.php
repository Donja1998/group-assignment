<?php
require_once __DIR__ . "/../Template.php";

HomeTemplate::HomeHeader("Home");
?>
<header style="background-image: url('<?= $home_path ?>/assets/img/header-bg.jpg')">
                <h1><?= $title; ?></h1>
            </header>

<h1>Welcome home</h1>


<?php Template::footer(); ?>