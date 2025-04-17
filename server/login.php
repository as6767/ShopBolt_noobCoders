<?php
session_start();
include(__DIR__ . '/db_connect/db_connect.php'); // Make sure this path is correct

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['admin_email']);
    $password = trim($_POST['admin_password']);

    // Check DB connection
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM admin WHERE admin_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['admin_password'])) {
        // Start session and redirect
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['admin_name'];
        $_SESSION['admin_email'] = $admin['admin_email'];

        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        }
        .btn-login {
            background-color: #495057;
            color: white;
        }
        .btn-login:hover {
            background-color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Admin Login</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="admin_email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                </div>
                <div class="mb-3">
                    <label for="admin_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-login">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

