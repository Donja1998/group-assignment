<?php 


// Retrieve the location information for the current blog post
$post_id = 1; // Replace with the ID of the current blog post
$query = "SELECT latitude, longitude, place_id FROM blog_posts WHERE id = $post_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$api_key = "AIzaSyDIYNXpkkxU6KAOOg_358LW4dV0eygUE_M";

// Generate the map URL using the Google Maps Embed API
$latitude = $row['latitude'];
$longitude = $row['longitude'];
$place_id = $row['place_id'];
$map_url = "https://www.google.com/maps/embed/v1/place?key=$api_key&q=place_id:$place_id";

// Embed the map in your HTML code
echo "<iframe src='$map_url' width='100%' height='400' frameborder='0' style='border:0' allowfullscreen></iframe>";
?>