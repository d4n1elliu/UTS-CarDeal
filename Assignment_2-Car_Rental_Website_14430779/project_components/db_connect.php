<?php
$name = "localhost";
$username = 'root'; 
$password = '';
$database = 'assignment2';

$conn = mysqli_connect($name, $username, $password, $database);
if (!$conn) {
    die("Could not connect to the server " . mysqli_connect_error());
}
?>