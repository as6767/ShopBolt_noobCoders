<?php
session_start();
include("db_connect/db_connect.php");

// Redirect to login if the user is not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$sql = "SELECT cart_id, product_id, quantity, added_at FROM cart WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// If no items in the cart, display a message
if ($result->num_rows == 0) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

// Fetch product details for each cart item
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="about.html">About</a>
        <a href="shop.php">Shop</a>
        <a href="contact.html">Contact</a>
        <a href="account.php">Account</a>
    </nav>
</header>

<section class="cart container my-5 py-5">
    <h2>Your Cart</h2>
    <table class="table mt-5">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Added At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_price = 0;

            foreach ($cart_items as $item) {
                $product_id = $item['product_id'];
                $quantity = $item['quantity'];

                // Fetch product details
                $product_sql = "SELECT name, price FROM products WHERE id = ?";
                $product_stmt = $conn->prepare($product_sql);
                $product_stmt->bind_param("i", $product_id);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();
                $product = $product_result->fetch_assoc();

                $product_name = $product['name'];
                $product_price = $product['price'];
                $subtotal = $product_price * $quantity;
                $total_price += $subtotal;

                $product_stmt->close();
            ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="assets/images/Product/<?php echo $product_id; ?>.png" alt="<?php echo $product_name; ?>">
                            <div>
                                <p><?php echo $product_name; ?></p>
                                <small>$<?php echo number_format($product_price, 2); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form action="update_cart.php" method="POST">
                            <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                    <td>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </td>
                    <td>
                        <span><?php echo date('Y-m-d H:i:s', strtotime($item['added_at'])); ?></span>
                    </td>
                    <td>
                        <a href="remove_from_cart.php?cart_id=<?php echo $item['cart_id']; ?>" class="btn btn-danger">Remove</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="cart-total">
        <table class="table">
            <tr>
                <td><strong>Subtotal</strong></td>
                <td>$<?php echo number_format($total_price, 2); ?></td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td>$<?php echo number_format($total_price, 2); ?></td>
            </tr>
        </table>
    </div>

    <div class="checkout-container">
        <a href="checkout.php"><button class="btn btn-success">Checkout</button></a>  
    </div>
</section>

<footer id="contact">
    <div class="container">
        <div class="footer-hr">
            <div class="flex gap-1">
                <hr>
                <h6>Newsletter</h6>
            </div>
            <h3>Join Our Mailing List</h3>
            <form action="#" id="footer-form" class="flex flex-sb gap-2">
                <input type="email" required placeholder="Enter your email">
                <button type="submit" class="btn btn-primary">Get Started</button>
            </form>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>



