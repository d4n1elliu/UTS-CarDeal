<?php
    include('project_components/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | UTS CarDeal</title>

    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="assets/script.js"></script>
</head>
<body>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center container py-3">
        <a href="index.php"><img src="assets/car_images/cardeal_logo.png" alt="" height="75"></a>
        <h1 class="h4 text-primary m-0"> UTS CarDeal </h1>
        <a href="reservation.php" class="btn btn-outline-primary d-flex align-items-center">
            <i class="fa fa-car mr-2"></i> Reservation
        </a>
    </div>

    <!-----------------Navigation Bar----------------------->
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Home</a>
            <a class="navbar-brand" href="accountForm.php">Account</a>
            <a class="navbar-brand" href="help.php">Help</a>
        </div>
    </nav>

    <!-------- Main Content ----------->
    <main class="container my-5">
        <div class="card px-5 py-5 shadow account-form w-100" style="max-width: 550px;">
        <h2 class="text-center text-primary mb-4">Access Your Rent History</h2>
        <p class="text-center" style="font-size: 23px;">Type your email to check your rent histories!</p>
            <form action="rental_history.php" method="POST" class="mt-4">
                <div class="form-group">
                    <label for="email" class="font-weight-bold">Enter your email address:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-dark">View Rent History</button>
                </div>
            </form>
        </div>
    </main>
    <!-------- Footer ----------->
    <?php include 'project_components/footer.php'; ?>
</body>
</html>