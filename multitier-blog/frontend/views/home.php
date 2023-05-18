<?php
require_once __DIR__ . "/../Template.php";

Template::header("Home");
?>

<h1>Welcome home: <?= $this->home ?></h1>

<p>
    We are delighted to have you visit our website. Here you will find a selection of products and services that we provide.
    From our high quality apparel and accessories to our professional services, we are committed to providing our users
    with only the best. Whether you are looking for a new wardrobe or need help with a project, we have the perfect solution for you.
</p>

<p>
    Take a look around and explore our selection of products and services. We have something for everyone, from fashion-forward
    apparel to creative services. We are confident that you will find something that you love.

</p>

<p>
    Thank you for visiting our website. We look forward to serving you and helping you find the perfect product or service that you need.
</p>

<?php Template::footer(); ?>