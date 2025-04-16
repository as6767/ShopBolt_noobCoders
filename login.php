<?php
session_start(); // Start session to track user data

// Include database connection
include("db_connect/db_connect.php"); 

// Redirect to account page if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header("Location: account.php");
    exit();
}

// Registration process
if (isset($_POST['register'])) {
    // Clean input data to avoid XSS and other issues
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required!"); // You can also redirect to an error page here
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query for inserting new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query and check if insertion was successful
    if ($stmt->execute()) {
        // Success, redirect to login page
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Login process
if (isset($_POST['login'])) {
    // Clean input data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        die("All fields are required!"); // Redirect to an error page or show message
    }

    // SQL query to check if the user exists
    $sql = "SELECT id, username, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    // Bind the email parameter and execute the query
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, now verify password
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Set session variables upon successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            // Redirect to the page user tried to access, or default to account page
            if (isset($_SESSION['redirect_to'])) {
                $redirect_page = $_SESSION['redirect_to'];
                unset($_SESSION['redirect_to']); // Clear the redirect
                header("Location: $redirect_page");
            } else {
                header("Location: account.php");
            }
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email!";
    }

    $stmt->close();
}

$conn->close();
?>


