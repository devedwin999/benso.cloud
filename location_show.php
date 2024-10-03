<?php
include("includes/connection.php");
include("includes/function.php");


    
    $jm = mysqli_query($mysqli, "SELECT * FROM location");
    
    $locations = array();
    
    while($row = mysqli_fetch_array($jm)) {
        $locations[] = array(
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'timestamp' => employee_name($row['log_user'])
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($locations);
?>
