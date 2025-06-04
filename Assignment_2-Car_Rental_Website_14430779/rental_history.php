<?php
include('project_components/db_connect.php');

// Get the email submitted from the form
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental History | UTS CarDeal</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="assets/script.js"></script>
</head>
<body>

    <!---------------- Header Sectiom------------------------>
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

    <!------------------Main Section--------------------------->
    <main class="container my-5">
        <h2 class="text-center text-primary mb-4">Your Rent History</h2>

        <?php
        if (!empty($email)) {
            $safeEmail = mysqli_real_escape_string($conn, $email);
            $query = "
                SELECT rh.rent_id, rh.rent_date, rh.rent_days,
                       c.brand, c.model, c.year
                FROM renting_history rh
                JOIN cars c ON rh.car_id = c.id
                WHERE rh.user_email = '$safeEmail'
                ORDER BY rh.rent_date DESC
            ";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead class="thead-dark">';
                echo '<tr>
                        <th>Rental ID</th>
                        <th>Car</th>
                        <th>Rent Date</th>
                        <th>Days Rented</th>
                      </tr>';
                echo '</thead><tbody>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['rent_id']}</td>
                            <td>{$row['brand']} {$row['model']} ({$row['year']})</td>
                            <td>{$row['rent_date']}</td>
                            <td>{$row['rent_days']}</td>
                          </tr>";
                }

                echo '</tbody></table></div>';
            } else {
                echo "<p class='text-center text-muted'>No rental history found for <strong>$email</strong>.</p>";
            }
        } else {
            echo "<p class='text-danger text-center'>No email provided. Please go back and enter your email.</p>";
        }
        ?>
    </main>

    <?php include 'project_components/footer.php'; ?>
</body>
</html>
