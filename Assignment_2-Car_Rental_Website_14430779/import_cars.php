<?php
include 'project_components/db_connect.php';

$json = file_get_contents('data/cars.json');
$data = json_decode($json, true);

// Loop through each car entry in the JSON file
foreach ($data['cars'] as $car) {
    $carType = $conn->real_escape_string($car['carType']);
    $brand = $conn->real_escape_string($car['brand']);
    $model = $conn->real_escape_string($car['carModel']);
    $year = (int)$car['yearOfManufacture'];
    $mileage = $conn->real_escape_string($car['mileage']);
    $fuelType = $conn->real_escape_string($car['fuelType']);
    $image = $conn->real_escape_string(basename($car['image']));
    $price = (float)$car['pricePerDay'];
    $description = $conn->real_escape_string($car['description']);
    $vin = $conn->real_escape_string($car['vin']);
    $availability = $car['available'] ? 1 : 0;

    // Prepare SQL INSERT query
    $sql = "INSERT INTO cars (car_type, brand, model, year, mileage, fuel_type, availability, price_per_day, image, description, vin)
            VALUES ('$carType', '$brand', '$model', $year, '$mileage', '$fuelType', $availability, $price, '$image', '$description', '$vin')";

    if (!$conn->query($sql)) {
        echo "Error importing car $model: " . $conn->error . "<br>";
    }
}
    // Final success message if all inserts are completed
    echo "Cars have successfully been imported.";
?>  