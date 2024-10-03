<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leaflet Map</title>
  <!-- Include Leaflet CSS -->
  <link rel="stylesheet" href="maps/leaflet.css" />

  <!-- Include Leaflet JavaScript -->
  <script src="maps/leaflet.js"></script>

  <style>
    /* Define the size of the map */
    #mapid { 
      height: 400px; 
      width: 100%; 
    }
  </style>
</head>
<body>

<!-- Create a div element to hold the map -->
<div id="mapid"></div>

<button onclick="window.location.href='dashboard.php'">Go Back</button>

<br>
<br>
<br>
<button onclick="window.location.href='location.php'">Add Location</button>

<script>
  // Initialize the map
  var mymap = L.map('mapid').setView([11.1271, 78.6569], 8);

  // Add a tile layer to the map (OpenStreetMap)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(mymap);
  
  
  
    function fetchAndDisplayLocations() {
        fetch('location_show.php')
        .then(response => response.json())
        .then(locations => {
            locations.forEach(location => {
                var marker = L.marker([location.latitude, location.longitude]).addTo(mymap);
                marker.bindPopup("User : " + location.timestamp).openPopup();
            });
        })
    }
    
    setInterval(fetchAndDisplayLocations, 500000);
    
    fetchAndDisplayLocations();


  // Add a marker to the map
//   var marker = L.marker([11.1271, 78.6569]).addTo(mymap);
</script>

</body>
</html>
    