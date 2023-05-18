<?php
require_once __DIR__ . "/../../Template.php";

Template::header("New Blog");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data

    $location = $_POST["location"];

    // Get place ID using Google Maps Geocoding API
    $api_key = "AIzaSyDIYNXpkkxU6KAOOg_358LW4dV0eygUE_M";
    $geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($location) . "&key=" . $api_key;
    $geocode_result = file_get_contents($geocode_url);
    $geocode_data = json_decode($geocode_result, true);
    $place_id = $geocode_data['results'][0]['place_id'];

    
    // Embed map using Google Maps Embed API
    $map_url = "https://www.google.com/maps/embed/v1/place?key=$api_key&q=place_id:$place_id";
}
?>

<h1>New Blog</h1>

<form action="<?= $this->home ?>/blogs" method="post">
    <input type="text" name="title" placeholder="Title name"> <br>
    <input type="text" name="content" placeholder="Content"> <br>
    <input type="text" name="location" placeholder="Location"> <br>

    <?php if ($this->user->user_role === "admin") : ?>
        <input type="text" name="user_id" placeholder="User ID"> <br>
    <?php endif; ?>

    <input type="submit" value="Save" class="btn">
</form>

<?php if (isset($map_url)) : ?>
<iframe width="600" height="450" frameborder="0" style="border:0" src="<?= $map_url ?>" allowfullscreen></iframe>
<?php endif; ?>

<?php Template::footer(); ?>
