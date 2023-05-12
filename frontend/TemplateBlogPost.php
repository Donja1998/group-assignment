

<!DOCTYPE html>
<html>
  <head>
    <title>Google Maps embed</title>
  </head>
  <body>
    <?php
    // Replace YOUR_API_KEY with your actual API key
    $apiKey = "AIzaSyDIYNXpkkxU6KAOOg_358LW4dV0eygUE_M";
    $searchUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=restaurant&location=37.7749,-122.4194&radius=5000&key=$apiKey";

    $response = file_get_contents($searchUrl);
    $data = json_decode($response);

    $placeId = $data->results[0]->place_id;
    // Embed the Google Map using the Maps Embed API and include the place ID in the query parameter
    $mapUrl = "https://www.google.com/maps/embed/v1/place?key=$apiKey&q=place_id:$placeId";
    ?>
    <iframe src="<?= $mapUrl ?>" width="600" height="450"></iframe>
  </body>
</html>