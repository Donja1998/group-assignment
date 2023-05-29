<?php $location = $_POST['location'];

$url = "https://maps.googleapis.com/maps/api/place/autocomplete/json";
$url .= "?input=" . urlencode($location);
$url .= "&types=geocode";
$url .= "&key=AIzaSyAJedt2e6mhEqbBw9KX4AVazXiQeuswwoo&";

$response = file_get_contents($url);
$data = json_decode($response, true);

?>