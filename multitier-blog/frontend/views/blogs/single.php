<?php
require_once __DIR__ . "/../../Template.php";

Template::header($this->model->blog_id);
?>




<?php if ($this->model->blog_pic_url) : ?>
    <img src="<?= $this->home . $this->model->blog_pic_url ?>" alt="" width="100">
<?php endif; ?>
<h1><?= $this->model->title ?></h1>

<img src=" <?= $this->model->blog_pic_url?>" alt="">


<p>
    <?= $this->model->title ?>
</p>

<p>
    <?= $this->model->content ?>
</p>

    <?php
    // Replace YOUR_API_KEY with your actual API key
    $apiKey = "AIzaSyDIYNXpkkxU6KAOOg_358LW4dV0eygUE_M";
    
    // Embed the Google Map using the Maps Embed API and include the place ID in the query parameter
    $placeId = $this->model->place_id;
    $mapUrl = "https://www.google.com/maps/embed/v1/place?key=$apiKey&q=place_id:$placeId";
    ?>
    <iframe src="<?= $mapUrl ?>" width="600" height="450"></iframe>

<?php if ($this->user->user_role === "admin") : ?>

    <p>
        <b>User ID: </b>
        <?= $this->model->user_id ?>
    </p>

<?php endif; ?>


<?php Template::footer(); ?>