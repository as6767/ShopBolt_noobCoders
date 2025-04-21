<?php
// db_connect.php

$servername = "localhost";
$username = "root"; // Your database username
$password = "";     // Your database password (leave empty for default local setup)
$database = "shopbolt"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

