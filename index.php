<?php
    session_start();
    include ('project_components/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> UTS CarDeal </title>

    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <!-------------Header Section--------------------->
    <div class="d-flex justify-content-between align-items-center container py-3">
        <a href="index.php"><img src="assets/car_images/cardeal_logo.png" alt="" height="75"></a>
        <h1 class="h4 text-primary m-0"> UTS CarDeal </h1>
        <a href="reservation.php" class="btn btn-outline-primary d-flex align-items-center">
            <i class="fa fa-car mr-2"></i> Reservation
        </a>
    </div>

    <!----------Navigation Section------------->
    <div class="bg-primary text-white py-2">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <nav class="navbar navbar-expand-sm navbar-dark bg-primary w-100">
                    <a class="navbar-brand" href="index.php">Home</a>
                    <a class="navbar-brand" href="accountForm.php">Account</a>
                    <a class="navbar-brand" href="help.php">Help</a>


                <div class="ml-auto d-flex align-items-center">
                    <form method="GET" class="form-inline d-flex">
                        <select name="type" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="Sedan" <?= ($_GET['type'] ?? '') === 'Sedan' ? 'selected' : '' ?>>Sedan</option>
                            <option value="Coupe" <?= ($_GET['type'] ?? '') === 'Coupe' ? 'selected' : '' ?>>Coupe</option>
                            <option value="Hatchback" <?= ($_GET['type'] ?? '') === 'Hatchback' ? 'selected' : '' ?>>Hatchback</option>
                            <option value="SUV" <?= ($_GET['type'] ?? '') === 'SUV' ? 'selected' : '' ?>>SUV</option>
                            <option value="Van" <?= ($_GET['type'] ?? '') === 'Van' ? 'selected' : '' ?>>Van</option>
                            <option value="Pickup" <?= ($_GET['type'] ?? '') === 'Pickup' ? 'selected' : '' ?>>Pickup</option>
                            <option value="Convertible" <?= ($_GET['type'] ?? '') === 'Convertible' ? 'selected' : '' ?>>Convertible</option>
                            <option value="Electric" <?= ($_GET['type'] ?? '') === 'Electric' ? 'selected' : '' ?>>Electric</option>
                        </select>
                        <!------- Real-time Search Input -------->
                        <div class="suggestion-container" style="position: relative;">
                            <input type="text" id="searchInput" name="search" class="form-control mr-2" style="width: 280px;" placeholder="What are you searching for?" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <div id="suggestions" class="bg-white border rounded mt-1 position-absolute" style="z-index: 1000; width: 280px;"></div>
                        </div>
                        <button type="submit" class="btn search-btn">Search</button>
                    </form>
                </div>
            </nav>
        </div>
    </div>

    <!-----------Main Section -------------->
    <main class="container my-4">
        <h2 class="mb-4">Available Cars</h2>
        <div class="row">
            <?php
            // Setting filter values
            $carFilter = isset($_GET['type']) ? trim($_GET['type']) : '';
            $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
            $query = "SELECT * FROM cars WHERE 1";

            // Add type filter
            if (!empty($carFilter)) {
                $safeType = mysqli_real_escape_string($conn, $carFilter);
                $query .= " AND car_type = '$safeType'";
            }

            // Add search filter
            if (!empty($searchQuery)) {
                $safeSearch = mysqli_real_escape_string($conn, $searchQuery);
                $query .= " AND (
                    brand LIKE '%$safeSearch%' OR
                    model LIKE '%$safeSearch%' OR
                    fuel_type LIKE '%$safeSearch%' OR
                    car_type LIKE '%$safeSearch%' OR
                    mileage LIKE '%$safeSearch%' OR
                    description LIKE '%$safeSearch%' OR
                    CAST(price_per_day AS CHAR) LIKE '%$safeSearch%'
                )";
            }

            $result = mysqli_query($conn, $query);
            ?>

            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <!------ Loop through and display each car ------>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 mb-4 d-flex">
                        <div class="card flex-fill d-flex flex-column shadow-sm">
                            <img src="assets/car_images/<?= htmlspecialchars($row['image']) ?>" class="card-img-top car-image" alt="Car Image">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($row['brand']) ?> <?= htmlspecialchars($row['model']) ?> (<?= htmlspecialchars($row['year']) ?>)</h5>
                                <p class="card-text"><strong>Type:</strong> <?= htmlspecialchars($row['car_type']) ?></p>
                                <p class="card-text"><strong>Fuel:</strong> <?= htmlspecialchars($row['fuel_type']) ?></p>
                                <p class="card-text"><strong>Mileage:</strong> <?= htmlspecialchars($row['mileage']) ?></p>
                                <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
                                <p class="card-text"><strong>Price:</strong> $<?= number_format($row['price_per_day'], 2) ?> per day</p>
                                <form action="reservation.php" method="POST" class="mt-auto">
                                    <input type="hidden" name="car_id" value="<?= htmlspecialchars($row['id']) ?>">
                                    <?php if ($row['availability'] > 0): ?>
                                        <button type="submit" class="btn btn-success btn-block">Rent Now</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-secondary btn-block" disabled>This car is currently unavailable</button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!------- If no cars found ------->
                <p class="text-muted">No cars available at the moment.</p>
            <?php endif; ?>
        </div>
    </main>
    <script src="assets/script.js"></script>
    <!---------Footer Section--------------->
    <?php include 'project_components/footer.php'; ?>
</body>
</html> 