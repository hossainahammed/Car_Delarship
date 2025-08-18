<?php
$con = mysqli_connect("localhost", "root", "", "car_dealership");

if (!$con) {
    echo "Database Connection Failed: ";
    exit(); // Optional: stop script execution
}
?>
