<?php
include 'project_components/db_connect.php';

$json = file_get_contents('data/orders.json');
$data = json_decode($json, true);

foreach ($data['orders'] as $order) {
    $user = $order['customer'];
    $rental = $order['rental'];
    $carData = $order['car'];
    $vin = $conn->real_escape_string($carData['vin']);

    // 1. Insert into users table
    $name = $conn->real_escape_string($user['name']);
    $email = $conn->real_escape_string($user['email']);
    $phone = isset($user['phone']) ? $conn->real_escape_string($user['phone']) : '0000000000';
    $license = isset($user['license']) ? $conn->real_escape_string($user['license']) : 'UNKNOWN';
    $address = "Not Provided";
    $state = "NSW";
    $country = "Australia";

    $conn->query("INSERT IGNORE INTO users (fullname, email, phone_number, license_number, address, state, country)
                  VALUES ('$name', '$email', '$phone', '$license', '$address', '$state', '$country')");

    // 2. Find car ID by exact VIN match
    $result = $conn->query("SELECT id FROM cars WHERE vin = '$vin'");
    if (!$result || $result->num_rows == 0) {
        echo "Car not found for VIN: $vin<br>";
        continue;
    }

    $car = $result->fetch_assoc();
    $car_id = $car['id'];

    // 3. Insert into renting_history
    $start = $conn->real_escape_string($rental['startDate']);
    $days = (int)$rental['rentalPeriod'];

    $sql = "INSERT INTO renting_history (car_id, user_email, rent_date, rent_days, phone_number, license_number)
            VALUES ($car_id, '$email', '$start', $days, '$phone', '$license')";

    if (!$conn->query($sql)) {
        echo "Error inserting rental for $email: " . $conn->error . "<br>";
    }
}
echo "<strong> Orders have been successfully imported.</strong>";
?>
