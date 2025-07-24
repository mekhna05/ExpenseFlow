<?php
$host = "localhost";
$username = "root";
$password = ""; // default password for XAMPP
$database = "daily_expense_tracker";
// Create connection
$conn = new mysqli($host, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

