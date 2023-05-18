<?php $location = $_POST['location'];

$url = "https://maps.googleapis.com/maps/api/place/autocomplete/json";
$url .= "?input=" . urlencode($location);
$url .= "&types=geocode";
$url .= "&key=YOUR_API_KEY";

$response = file_get_contents($url);
$data = json_decode($response, true);

?>