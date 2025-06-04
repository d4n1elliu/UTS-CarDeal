<?php
session_start();
include 'project_components/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form inputs
    $car_id = intval($_POST['car_id']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $license_number = mysqli_real_escape_string($conn, $_POST['license_number']);
    $quantity = intval($_POST['quantity']);
    $fullname = $first_name . ' ' . $last_name;

     // Calculating the rental duration in days
    $rent_days = ceil((strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24));

    // Check if enough stock is available
    $checkStock = "SELECT availability FROM cars WHERE id = $car_id";
    $stockResult = mysqli_query($conn, $checkStock);
    $stockRow = mysqli_fetch_assoc($stockResult);

    if ($stockRow['availability'] < $quantity) {
        echo "<p class='text-danger text-center mt-5'>Error: Only {$stockRow['availability']} car(s) available. Please go back and reduce your quantity.</p>";
        exit();
    }

    // Insert user if not exists
    $checkUser = "SELECT * FROM users WHERE email = '$email'";
    $userResult = mysqli_query($conn, $checkUser);

    if (mysqli_num_rows($userResult) == 0) {
        $insertUser = "INSERT INTO users (fullname, email, phone_number, license_number)
                       VALUES ('$fullname', '$email', '$phone', '$license_number')";
        mysqli_query($conn, $insertUser);
    }

    // Insert into renting_history
    for ($i = 0; $i < $quantity; $i++) {
        $insertRent = "INSERT INTO renting_history (car_id, user_email, rent_date, rent_days, phone_number, license_number)
                       VALUES ($car_id, '$email', '$start_date', $rent_days, '$phone', '$license_number')";
        mysqli_query($conn, $insertRent);
    }

    // Update stock
    $updateQuery = "UPDATE cars SET availability = availability - $quantity WHERE id = $car_id";
    mysqli_query($conn, $updateQuery);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <!------ Header Section------->
    <div class="d-flex justify-content-between align-items-center container py-3">
        <a href="index.php"><img src="assets/car_images/cardeal_logo.png" alt="Logo" height="75"></a>
        <h1 class="h4 text-primary m-0">UTS CarDeal</h1>
        <a href="reservation.php" class="btn btn-outline-primary d-flex align-items-center">
            <i class="fa fa-car mr-2"></i> Reservation
        </a>
    </div>

    <!------ Navigation Section ------->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Home</a>
            <a class="nav-link text-white" href="accountForm.php">Account</a>
            <a class="nav-link text-white" href="help.php">Help</a>
        </div>
    </nav>

    <!--------- Main Confirmation Section --------->
    <main class="container text-center my-5 flex-fill">
        <h2 class="mb-4">Order Confirmation</h2>
        <p>Your order for <strong><?= $quantity ?></strong> car(s) has been successfully placed!</p>
        <p>Please check your <strong>email</strong> for a confirmation receipt.</p>
        <a href="index.php" class="btn btn-primary mt-4">Back to Home</a>
    </main>

    <!--------Footer Section--------->
    <?php include 'project_components/footer.php'; ?>
</body>
</html>