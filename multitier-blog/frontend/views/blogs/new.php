<?php
require_once __DIR__ . "/../../Template.php";


Template::header("New Blog");

$user_id = null;

foreach ($_SESSION as $key => $value) {
    if ($value instanceof UserModel) {
        $user_id = $value->user_id;
        break; // Exit the loop since we found the user_id
    }
}


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

<div id="newBlogForm">

<form action="<?= $this->home ?>/blogs" method="post" enctype="multipart/form-data" >
   <input type="text" name="title" placeholder="Title name"> <br>
   <input type="text" name="content" placeholder="Content"> <br>
   <input type="text" name="location" id="location-input" placeholder="Location"> <br>
   <input type="file" name="blog_pic_url"> <br>
   <input type="hidden" name="place_id" id="place-id-input" value="<?php echo $place_id; ?>"> <br>
   <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> <br>


   <?php if ($this->user->user_role === "admin") : ?>
       <input type="text" name="user_id" placeholder="User ID"> <br>
   <?php endif; ?>


   <input type="submit" value="Save" class="btn">
</form>
   </div>




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
