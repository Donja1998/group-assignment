<?php
require_once __DIR__ . "/../Template.php";

Template::header("404: Not found");
?>

    <p>
        Sorry, the page you are looking for cannot be found. Please check the URL for mistakes and try again.
        If you think something is broken, please let us know so we can fix it as soon as possible.
    </p>
<?php Template::footer(); ?>