
<!DOCTYPE html>
<html>
  <head>
    <title>Google Maps embed</title>
  </head>
  <body>
    <?php
    // Replace YOUR_API_KEY with your actual API key
    $apiKey = "AIzaSyDIYNXpkkxU6KAOOg_358LW4dV0eygUE_M";
    
    // Embed the Google Map using the Maps Embed API and include the place ID in the query parameter
    $placeId = "ChIJeRpOeF67j4AR9ydy_PIzPuM";
    $mapUrl = "https://www.google.com/maps/embed/v1/place?key=$apiKey&q=place_id:$placeId";
    ?>
    <iframe src="<?= $mapUrl ?>" width="600" height="450"></iframe>
  </body>
</html>