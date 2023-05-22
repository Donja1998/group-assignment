<?php
require_once __DIR__ . "/../../Template.php";

Template::header("Edit " . $this->model->blog_id);

$user_id = $this->model->user_id;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
   // Retrieve form data
   $place_id = $_POST["place_id"];

   // Get place details using Google Places Details API
   $api_key = "AIzaSyAJedt2e6mhEqbBw9KX4AVazXiQeuswwoo";
   $details_url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $place_id . "&key=" . $api_key;
   $details_result = file_get_contents($details_url);
   $details_data = json_decode($details_result, true);

   if (!empty($details_data['result'])) {
       $formatted_address = $details_data['result']['formatted_address'];
       $latitude = $details_data['result']['geometry']['location']['lat'];
       $longitude = $details_data['result']['geometry']['location']['lng'];


       // Embed map using Google Maps Embed API
       $map_url = "https://www.google.com/maps/embed/v1/place?key=$api_key&q=place_id:$place_id";
   }
}
?>

<h1>Edit <?= $this->model->blog_id ?></h1>

<form action="<?= $this->home ?>/blogs/<?= $this->model->blog_id ?>/edit" method="post">
    <input type="text" name="title" value="<?= $this->model->title ?>" placeholder="title"> <br>
    <input type="text" name="content" value="<?= $this->model->content ?>" placeholder="content"> <br>
    <input type="text" name="location" id="location-input" placeholder="Location"> <br>
    <input type="hidden" name="place_id" id="place-id-input" value="<?php echo $place_id; ?>"> <br>

    <input type="hidden" name="user_id" value="<?= $user_id ?>">

    <input type="text" name="blog_pic_url"  placeholder="image url"> <br>
    
    <input type="submit" value="Save" class="btn">
</form>

<form action="<?= $this->home ?>/blogs/<?= $this->model->blog_id ?>/delete" method="post">
    <input type="submit" value="Delete" class="btn delete-btn">
</form>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJedt2e6mhEqbBw9KX4AVazXiQeuswwoo&libraries=places"></script>
<script>
   // Initialize the Autocomplete functionality
   var locationInput = document.getElementById('location-input');
   var autocomplete = new google.maps.places.Autocomplete(locationInput);


   // Update hidden input field with place ID
   autocomplete.addListener('place_changed', function() {
       var place = autocomplete.getPlace();
       if (place.place_id) {
           document.getElementById('place-id-input').value = place.place_id;
       }
   });
</script>

<?php Template::footer(); ?>