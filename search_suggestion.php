<?php
include 'project_components/db_connect.php';

$term = mysqli_real_escape_string($conn, $_GET['term'] ?? '');

$suggestions = [];

if (!empty($term)) {
    $query = "SELECT DISTINCT brand FROM cars WHERE brand LIKE '%$term%' 
              UNION
              SELECT DISTINCT model FROM cars WHERE model LIKE '%$term%' 
              UNION
              SELECT DISTINCT car_type FROM cars WHERE car_type LIKE '%$term%' 
              LIMIT 8";
    
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_row($result)) {
            $suggestions[] = $row[0];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($suggestions);
