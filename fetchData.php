<?php

include("includes/connection.php");
include("includes/function.php");

// Get search term 
$searchTerm = $_GET['term'];

// Fetch matched data from the database 

$ass = "SELECT a.*, b.state_name, c.cities_name FROM customer a LEFT JOIN states b ON a.state = b.id LEFT JOIN cities c ON a.city = c.id WHERE customer_name LIKE '%" . $searchTerm . "%' OR customer_code LIKE '%" . $searchTerm . "%' ORDER BY customer_name ASC";
$asss = mysqli_query($mysqli, $ass);

// Generate array with skills data 
$skillData = array();
if (mysqli_num_rows($asss) > 0) {
    while ($row = mysqli_fetch_array($asss)) {
        $data['id'] = $row['id'];
        $data['value'] = $row['customer_name'] . ' - ' . $row['customer_code'];
        $data['address1'] = $row['address1'];
        $data['address2'] = $row['address2'];
        $data['area'] = $row['area'];
        $data['state_name'] = $row['state_name'];
        $data['cities_name'] = $row['cities_name'];
        $data['gst_no'] = $row['gst_no'];
        array_push($skillData, $data);
    }
}

// Return results as json encoded array 
echo json_encode($skillData);
?>