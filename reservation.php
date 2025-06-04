<?php
session_start();
include 'project_components/db_connect.php';

$carReserved = false;
$car = null;
    
// Checking for car_id was found or not (Using POST form)
if (isset($_POST['car_id'])) {
    $car_id = intval($_POST['car_id']);
    $_SESSION['selected_car_id'] = $car_id;

    $query = "SELECT * FROM cars WHERE id = $car_id";
    $connection_result = mysqli_query($conn, $query);

    if ($connection_result && mysqli_num_rows($connection_result) > 0) {
        $car = mysqli_fetch_assoc($connection_result);
        $carReserved = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>

    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

    <!---------=-Header Section---------------->
    <div class="d-flex justify-content-between align-items-center container py-3">
        <a href="index.php"><img src="assets/car_images/cardeal_logo.png" alt="" height="75"></a>
        <h1 class="h4 text-primary m-0">UTS CarDeal</h1>
        <a href="reservation.php" class="btn btn-outline-primary d-flex align-items-center">
            <i class="fa fa-car mr-2"></i> Reservation
        </a>
    </div>

    <!--------Navigation Section--------------->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Home</a>
            <a class="nav-link text-white" href="accountForm.php">Account</a>
            <a class="nav-link text-white" href="help.php">Help</a>
        </div>
    </nav>

    <!-------- Main Section ------->
    <main class="container my-5">
        <?php if ($carReserved): ?>
            <div class="text-center">
                <?php
                    $picturePath = 'assets/car_images/' . $car['image'];
                    if (!file_exists($picturePath)) {
                        echo "<p class='text-danger'>Image file not found: $picturePath</p>";
                    }
                ?>
                <img src="<?= $picturePath ?>" alt="Car Image" class="img-fluid mb-4" style="max-width: 400px;">
                <h2>Reservation for: <?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</h2>
                <br>
                <p><strong>Fuel:</strong> <?= htmlspecialchars($car['fuel_type']) ?></p>
                <p><strong>Mileage:</strong> <?= htmlspecialchars($car['mileage']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($car['description']) ?></p>
                <p><strong>Price:</strong> $<?= number_format($car['price_per_day'], 2) ?> per day</p>

                <!-------- Reservation Form ------>
                <form action="checkout.php" method="post" class="mt-5" id="reservationForm">
                    <input type="hidden" name="price_per_day" value="<?= $car['price_per_day'] ?>">
                    <input type="hidden" name="days" id="rentalDays">

                    <div class="form-group">
                        <label for="start_date"><strong>Start Date</strong></label>
                        <input type="date" id="start_date" name="start_date" required class="form-control w-50 mx-auto mb-3">

                        <label for="end_date"><strong>End Date</strong></label>
                        <input type="date" id="end_date" name="end_date" required class="form-control w-50 mx-auto mb-3">

                        <label for="quantity"><strong>Number of Cars to Rent</strong></label>
                        <input type="number" id="quantity" name="quantity" class="form-control w-50 mx-auto" min="1" value="1" required data-max="<?= $car['availability'] ?>">
                    </div>
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-danger mr-2">Cancel Order</a>
                        <button type="submit" class="btn btn-success">Proceed To Checkout</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="text-center mt-5">
                <h2 class="text-danger">Must reserve a car before you can proceed with rent order.</h2>
                <a href="index.php" class="btn btn-success mt-3">Return to main page</a>
            </div>
        <?php endif; ?>
    </main>
    <script src="assets/script.js">
    </script>
    <!-------------Footer Section----------------->
    <?php 
        include 'project_components/footer.php'; 
    ?>
</body>
</html>