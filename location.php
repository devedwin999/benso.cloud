<!DOCTYPE html>
<html>
<head>
    <title>Get Current Location</title>
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Send location data to PHP script
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "save_location.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    
                    console.log(xhr.responseText);
                    var contentElement = document.getElementById("resp");
                    // Change the content
                    contentElement.textContent = xhr.responseText;
                    
                    window.location.href="location_view.php";
                }
            };
            var data = JSON.stringify({latitude: latitude, longitude: longitude});
            xhr.send(data);
        }
    </script>
</head>
<body>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <button onclick="getLocation()">Enter Location</button>
    <br>
    <br>
    <br>
    <button onclick="window.location.href='location_view.php'">View Location</button>
    <br>
    <br>
    <br>
    <button onclick="window.location.href='dashboard.php'">Go Back</button>
    
    
    <p id="resp"></p>
</body>
</html>
