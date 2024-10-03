<?php

include("includes/connection.php");
include("includes/function.php");

// Get search term 
$searchTerm = $_GET['term'];

// Fetch matched data from the database 

$ass = "SELECT a.* FROM itemlist a WHERE a.item_code LIKE '%" . $searchTerm . "%' OR a.item_name LIKE '%" . $searchTerm . "%' ORDER BY item_name ASC";
$asss = mysqli_query($mysqli, $ass);

// Generate array with skills data 
$skillData = array();
if (mysqli_num_rows($asss) > 0) {
    while ($row = mysqli_fetch_array($asss)) {
        $data['id'] = $row['id'];
        $data['value'] = $row['item_name'];
        $data['item_code'] = $row['item_code'];
        $data['sales1'] = $row['sales1'];
        $data['total'] = $row['sales1'];
        array_push($skillData, $data);
    }
}

// Return results as json encoded array 
echo json_encode($skillData);
?>