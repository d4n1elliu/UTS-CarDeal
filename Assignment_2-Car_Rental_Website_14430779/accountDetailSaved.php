<?php
include('project_components/db_connect.php');

// Check if POST data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and assign inputs
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);

    // Insert into users table
    $query = "INSERT INTO users (fullname, email, address, state, country)
              VALUES ('$fullname', '$email', '$address', '$state', '$country')";

    if (mysqli_query($conn, $query)) {
        // Save email to session to link later in renting_history
        session_start();
        $_SESSION['user_email'] = $email;
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: accountForm.php");
    exit();
}
?>