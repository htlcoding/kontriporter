<p>code funktioniert, SQL fehlt</p>

<?php
// Connect to the database
$db = new mysqli('hostname', 'username', 'password', 'database_name');

// Check for errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Query the database for coordinates
$sql = "SELECT latitude, longitude FROM coordinates";
$result = $db->query($sql);

// Initialize an array to store the coordinates
$coordinates = array();

// Fetch the coordinates from the database
while ($row = $result->fetch_assoc()) {
    $coordinates[] = array($row['latitude'], $row['longitude']);
}

// Close the database connection
$db->close();

// Output the map with the coordinates marked
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h3>My Google Maps Demo</h3>
    <div id="map"></div>
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: 48.2082, lng: 16.3738}
            });

            // Add markers to the map
            var coordinates = <?php echo json_encode($coordinates); ?>;
            for (var i = 0; i < coordinates.length; i++) {
                var marker = new google.maps.Marker({
                    position: {lat: coordinates[i][0], lng: coordinates[i][1]},
                    map: map
                });
            }
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
</body>
</html>