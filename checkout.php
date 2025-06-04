<?php
session_start();
include 'project_components/db_connect.php';

// For ensuring a car is selected and dates are valid
if (!isset($_SESSION['selected_car_id']) || 
    !isset($_POST['start_date']) || 
    !isset($_POST['end_date']) || 
    !isset($_POST['price_per_day']) ||                                    
    !isset($_POST['days']) ||   
    !isset($_POST['days'])
) {                            
    header("Location: index.php");
    exit();
}

$car_id = $_SESSION['selected_car_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$pricePerDay = floatval($_POST['price_per_day']);
$days = max(1, intval($_POST['days']));
$quantity = max(1, intval($_POST['quantity']));
$total_cost = $pricePerDay * $days * $quantity;

// Fetch car details from php admin database
$query = "SELECT * FROM cars WHERE id = $car_id LIMIT 1";
$result = mysqli_query($conn, $query);
$car = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - UTS CarDeal</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <!--------- Header ---------->
    <div class="d-flex justify-content-between align-items-center container py-3">
        <a href="index.php"><img src="assets/car_images/cardeal_logo.png" alt="Logo" height="75"></a>
        <h1 class="h4 text-primary m-0">UTS CarDeal</h1>
        <a href="reservation.php" class="btn btn-outline-primary"><i class="fa fa-car mr-2"></i> Reservation</a>
    </div>

    <!--------Navigation bar-------->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Home</a>
            <a class="nav-link text-white" href="accountForm.php">Account</a>
            <a class="nav-link text-white" href="help.php">Help</a>
        </div>
    </nav>

    <!----- Main Booking Checkout Content ------>
    <main class="container my-5" style="flex: 1;">
        <h2 class="mb-4">Checkout</h2>
        <div class="row">
            <!------- Left column: Customer info ------>
            <div class="col-md-7">
                <form action="confirmation.php" method="post">
                     <!------ Each form field is auto-filled from cookies if available -------->
                    <div class="form-group">
                        <label>First Name*</label>
                        <input type="text" name="first_name" id="first_name" required class="form-control"
                            value="<?= isset($_COOKIE['first_name']) ? htmlspecialchars($_COOKIE['first_name']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Last Name*</label>
                        <input type="text" name="last_name" id="last_name" required class="form-control"
                            value="<?= isset($_COOKIE['last_name']) ? htmlspecialchars($_COOKIE['last_name']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Email*</label>
                        <input type="email" name="email" id="email" required class="form-control"
                            value="<?= isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone Number*</label>
                        <input type="tel" name="phone" id="phone" required class="form-control"
                            value="<?= isset($_COOKIE['phone']) ? htmlspecialchars($_COOKIE['phone']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Driver's License Number*</label>
                        <input type="text" name="license_number" id="license_number" required class="form-control"
                            value="<?= isset($_COOKIE['license_number']) ? htmlspecialchars($_COOKIE['license_number']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Number of Cars to Rent*</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="<?= $car['availability'] ?>"
                            value="<?= $quantity ?>" required class="form-control">
                    </div>

                    <!-------- Hidden fields -------->
                    <input type="hidden" name="car_id" value="<?= $car_id ?>">
                    <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                    <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                    <input type="hidden" name="price_per_day" value="<?= $pricePerDay ?>">
                    <input type="hidden" name="days" value="<?= $days ?>">

                    <p class="mt-3 small text-muted">
                        By confirming this order, you agree that you hold a valid Australian license.
                    </p>
                    <!--------Submit buttons -------->
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-danger">Cancel Order</a>
                        <button type="submit" class="btn btn-success">Confirm Order</button>
                    </div>
                </form>
            </div>

            <!------- Right column: Booking summary card -------->
            <div class="col-md-5">
                <div class="card p-3">
                    <img src="assets/car_images/<?= htmlspecialchars($car['image']) ?>" alt="Car" class="img-fluid mb-3" style="max-height: 150px; object-fit: contain;">
                    <p><strong>Vehicle Model:</strong> <?= $car['year'] . ' ' . $car['brand'] . ' ' . $car['model'] ?></p>
                    <p><strong>Pickup Date:</strong> <?= htmlspecialchars($start_date) ?></p>
                    <p><strong>Return Date:</strong> <?= htmlspecialchars($end_date) ?></p>
                    <p><strong>Rental Duration:</strong> <?= $days ?> day(s)</p>
                    <p><strong>Quantity:</strong> <span id="carQty"><?= $quantity ?></span></p>
                    <p><strong>Price per Day:</strong> $<?= number_format($pricePerDay, 2) ?></p>
                    <p><strong>Total Cost:</strong> $<span id="totalCost"><?= number_format($total_cost, 2) ?></span></p>
                </div>
            </div>
        </div>
    </main>

    <!-----Javascript for dynamically updating if car quantity is changed ------->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const qtyInput = document.querySelector('input[name="quantity"]');
            const costDisplay = document.getElementById('totalCost');
            const qtyDisplay = document.getElementById('carQty');
            const pricePerDay = <?= json_encode($car['price_per_day']) ?>;
            const rentalDays = <?= $days ?>;

            if (qtyInput && costDisplay && qtyDisplay) {
                qtyInput.addEventListener('input', () => {
                    const qty = Math.max(1, parseInt(qtyInput.value) || 1);
                    const total = qty * pricePerDay * rentalDays;

                    qtyDisplay.textContent = qty;
                    costDisplay.textContent = total.toFixed(2);
                });
            }
        });
    </script>
    <!------ Footer ---------->
    <?php include 'project_components/footer.php'; ?>
</body>
</html>
