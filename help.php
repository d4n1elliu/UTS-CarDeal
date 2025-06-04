
<?php include('project_components/db_connect.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Help Page </title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <!-------------- Header -------------->
    <div class="d-flex justify-content-between align-items-center container py-3">
        <a href="index.php"><img src="assets/car_images/cardeal_logo.png" alt="UTS CarDeal Logo" height="75"></a>
        <h1 class="h4 text-primary m-0">UTS CarDeal</h1>
        <a href="reservation.php" class="btn btn-outline-primary d-flex align-items-center">
            <i class="fa fa-car mr-2"></i> Reservation
        </a>
    </div>

    <!------------- Navigation Bar------------>
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Home</a>
            <a class="navbar-brand" href="accountForm.php">Account</a>
            <a class="navbar-brand" href="help.php">Help</a>
        </div>
    </nav>

    <!----------- Main Help Content ----------->
    <main class="container my-5">
        <div class="card p-4 shadow">
            <h2 class="text-center text-primary mb-4">Welcome to UTS CarDeal Help</h2>
            <p class="lead text-center">
                We're here to help you get the most out of your car rental experience.
            </p>
            <hr>
            <p class="text-center">
                Renting at <strong>UTS CarDeal</strong> is very simple. Just pick a car, choose your dates and submit your order.
            </p>
            <p class="text-center">
                Pricing is calculated per day. Your rental duration is based on the selected start and end dates.
            </p>
            <p class="text-center">
                Car stock is updated in real time, so book early to avoid missing out!
            </p>
        </div>
    </main>

    <!----------Javascript -------------->
    <script src="script.js"></script>

    <!-------- Footer Section ------------>
    <?php include 'project_components/footer.php'; ?>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</body>
</html>