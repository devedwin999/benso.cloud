<?php

include("includes/connection.php");
include("includes/function.php");

// Get search term 
$searchTerm = $_GET['term'];

// Fetch matched data from the database 

$ass = "SELECT a.* FROM sales_order a WHERE a.order_code LIKE '%" . $searchTerm . "%' ORDER BY order_code ASC";
$asss = mysqli_query($mysqli, $ass);

// Generate array with skills data 
$skillData = array();
if (mysqli_num_rows($asss) > 0) {
    while ($row = mysqli_fetch_array($asss)) {
        $data['id'] = $row['id'];
        $data['value'] = $row['order_code'];
        
        array_push($skillData, $data);
    }
}

// Return results as json encoded array 
echo json_encode($skillData);
?>