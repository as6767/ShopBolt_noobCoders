<?php
session_start();
include("db_connect/db_connect.php");

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Store current page URL
    header("Location: login.php"); // Redirect to login page
    exit();
}

$user_id = $_SESSION['user_id'];

// Get product details from the form submission
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];

// Check if the product is already in the cart
$check_cart_sql = "SELECT cart_id FROM cart WHERE id = ? AND product_id = ?";
$check_cart_stmt = $conn->prepare($check_cart_sql);
$check_cart_stmt->bind_param("ii", $user_id, $product_id);
$check_cart_stmt->execute();
$check_cart_result = $check_cart_stmt->get_result();

if ($check_cart_result->num_rows > 0) {
    // If the product already exists in the cart, update the quantity
    $update_quantity_sql = "UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND product_id = ?";
    $update_quantity_stmt = $conn->prepare($update_quantity_sql);
    $update_quantity_stmt->bind_param("ii", $user_id, $product_id);
    $update_quantity_stmt->execute();
} else {
    // If the product doesn't exist in the cart, insert it
    $insert_cart_sql = "INSERT INTO cart (id, product_id, quantity) VALUES (?, ?, 1)";
    $insert_cart_stmt = $conn->prepare($insert_cart_sql);
    $insert_cart_stmt->bind_param("ii", $user_id, $product_id);
    $insert_cart_stmt->execute();
}

// Close the database connection
$check_cart_stmt->close();
$insert_cart_stmt->close();
$update_quantity_stmt->close();

header("Location: cart.php"); // Redirect to cart page after adding to cart
exit();
?>

