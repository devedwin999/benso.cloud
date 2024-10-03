<?php
include("includes/connection.php");
include("includes/function.php");

// Get location data from POST request
$data = json_decode(file_get_contents('php://input'), true);

// Validate data
if (isset($data['latitude']) && isset($data['longitude'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    
    $inp = array(
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'log_user' => $logUser
    );
    
    $ins = Insert('location', $inp);
    
    if($ins) {
        print 'Loacation Added!';
    } else {
        print 'error';
    }
}


else if(isset($_REQUEST['show_map'])) {
    
    $jm = mysqli_query($mysqli, "SELECT * FROM location");
    
    $locations = array();
    while($row = mysqli_fetch_array($jm)) {
        $locations[] = array(
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'timestamp' => $row['timestamp']
        );
    }
}
?>
