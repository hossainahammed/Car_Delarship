<?php
$conn = mysqli_connect("localhost", "root", "", "car_dealership");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
