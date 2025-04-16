<?php
session_start();
include("db_connect/db_connect.php"); 

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch user info from DB
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user's orders
$order_result = null;
$stmt2 = $conn->prepare("SELECT product_name, order_date FROM orders WHERE user_id = ?");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$order_result = $stmt2->get_result();

// Handle password change
if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    if (empty($password) || empty($confirm_password)) {
        header("Location: account.php?error=empty_fields");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: account.php?error=password_mismatch");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt3 = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    if (!$stmt3) {
        header("Location: account.php?error=sql_error");
        exit();
    }

    $stmt3->bind_param("si", $hashed_password, $user_id);
    if ($stmt3->execute()) {
        header("Location: account.php?success=password_changed");
        exit();
    } else {
        header("Location: account.php?error=update_failed");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Shop</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/easy.css">
  <link rel="stylesheet" href="assets/css/account.css">
</head>
<body>

<header>
  <nav class="navbar">
    <a href="index.php">Home</a>
    <a href="about.html">About</a>
    <a href="shop.php">Shop</a>
    <a href="contact.html">Contact</a>
    <a href="cart.html">Cart</a>
  </nav>
  <a href="index.html" class="nav_logo">
    <img src="assets/images/logo.png" alt="Logo">
  </a>
</header>

<section class="my-5 py-5">
  <div class="row container mx-auto">
    <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
      <h3 class="font-weight-bold">Account Info</h3>
      <hr class="mx-auto">
      <div class="account-info">
        <p>Name: <span><?php echo htmlspecialchars($user['username'] ?? ''); ?></span></p>
        <p>Email: <span><?php echo htmlspecialchars($user['email'] ?? ''); ?></span></p>
        <p><a href="#orders" id="order-btn">Your Orders</a></p>
        <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
      </div>
    </div>

    <div class="col-lg-6 col-md-12 col-sm-12">
      <form action="" id="account-form" method="POST">
        <h3>Change Password</h3>
        <hr class="mx-auto">

        <!-- Show success/error messages -->
        <?php if (isset($_GET['error'])): ?>
          <div class="alert alert-danger">
            <?php
              switch ($_GET['error']) {
                case 'empty_fields': echo "Please fill in both fields."; break;
                case 'password_mismatch': echo "Passwords do not match."; break;
                case 'sql_error': echo "Database error occurred."; break;
                case 'update_failed': echo "Password update failed."; break;
              }
            ?>
          </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] === 'password_changed'): ?>
          <div class="alert alert-success">Password changed successfully!</div>
        <?php endif; ?>

        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Confirm Password" required>
        </div>
        <div class="form-group">
          <input type="submit" value="Change Password" name="change_password" class="btn btn-primary mt-3" id="change-pass-btn">
        </div>
      </form>
    </div>
  </div>
</section>

<section id="orders" class="orders container my-5 py-3">
  <div class="container mt-2">
    <h2 class="font-weight-bold text-center">Your Orders</h2>
    <hr class="mx-auto" style="width: 80%;">
  </div>

  <div class="table-responsive mt-4">
    <table class="table table-hover table-bordered text-center">
      <thead class="thead-light">
        <tr>
          <th scope="col" class="py-3">Product</th>
          <th scope="col" class="py-3">Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($order_result && $order_result->num_rows > 0): ?>
          <?php while($row = $order_result->fetch_assoc()): ?>
            <tr>
              <td class="align-middle"><?php echo htmlspecialchars($row['product_name']); ?></td>
              <td class="align-middle"><?php echo htmlspecialchars($row['order_date']); ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="2">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<footer id="contact">
        <div class="container">
            <div class="footer-hr flex flex-col">
                <div class="flex gap-1">
                    <hr>
                    <h6>Newsletter</h6>
                </div>
                <h3>Join Our Mailing List</h3>
                <p class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum nam iure quis ea iste suscipit.</p>
            </div>
            <form action="#" id="footer-form" class="flex flex-sb gap-2">
                <div id="footer-message"></div>
                <input type="email" required placeholder="Enter  your email">
                <button type="submit" class="btn_hover1 ">Get Started</button>

            </form>
        </div>
        <div class="footer-menu">
            <div class="container">
                <div class="flex flex-start footer-center">
                    <div class="w-33 mt-2 flex-col gap-2 flex-start">
                        <a href="/"><img src="assets/images/logo.png" alt="footer-logo"></a>
                        <p class="mt-2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, adipisci?</p>
                        <span class="flex gap-20 mt-15">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </span>
                    </div>
                <div class="w-16 mt-1">
                    <h4>Quick Links</h4>
                    <ul class="flex flex-col gap-20 flex-start">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Portfolio</a></li>
                        <li><a href="#">Blogs</a></li>
                    </ul>
                </div>    
                <div class="w-16 mt-45 flex-end">
                    
                    <ul class="flex flex-col gap-20 flex-start">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Team</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="w-33 mt-1 flex flex-col flex-start">
                    <h4>Get Connected</h4>
                    <ul class="flex flex-col gap-2 flex-start">
                        <li>
                            <a href="#"><i class="bi bi-envelope"></i></a>
                            <a href="#" class="text-lowercase">shopbolt@gmail.com</a>
                        </li>
                        <li>
                            <a href="#"><i class="bi bi-telephone"></i></a>
                            <a href="#" >+8801100000000</a>
                        </li>
                        <li>
                            <a href="#"><i class="bi bi-clock"></i></a>
                            <a href="#">Office-Hours : 8AM - 11PM
                                        Sunday - Weekend Day
                            </a>
                        </li>
                    </ul>
                </div>    
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container flex flex-sb gap-20 flex-warp">
                <h6>Copyright &copy; 2024 Coded by <a href="/" class="p-0">NoobCoders</a></h6>
                <h6>Powerd By <b>Prisom</b></h6>
            </div>
        </div>
    </footer>

<!-- Bootstrap + JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script> 

</body>
</html>

