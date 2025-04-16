<?php
session_start();
include("db_connect/db_connect.php");

// Handle the Add to Cart functionality via Ajax
if (isset($_POST['product_id'])) {
    // Add the product to the cart
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_image = $_POST['product_image'];
    $product_price = $_POST['product_price'];

    // Check if cart session is already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
        'name' => $product_name,
        'image' => $product_image,
        'price' => $product_price,
        'quantity' => isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id]['quantity'] + 1 : 1
    ];

    // Return a response
    echo 'added';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/easy.css">
    <link rel="stylesheet" href="assets/css/shop.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

    <!-- Navigation -->
    <header>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.html">About</a>
            <a href="service.html">Services</a>
            <a href="shop.php">Shop</a>
            <a href="contact.html">Contact</a>
            <a href="cart.php">Cart <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span></a>
        </nav>
        <a href="index.html" class="nav_logo">
          <img src="assets/images/logo.png" alt="Logo">
        </a>
    </header>

    <!-- Shop Page -->
    <section id="shop" class="my-5 py-5">
        <div class="container">
            <div class="row">
                <!-- Filter Column -->
                <div class="col-lg-3 col-md-4">
                    <div class="filter-section">
                        <h4 class="mb-4">Filter Products</h4>
                        
                        <!-- Search -->
                        <div class="mb-4">
                            <h6>Search</h6>
                            <input type="text" id="search-input" class="form-control" placeholder="Search products...">
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-4">
                            <h6>Categories</h6>
                            <ul class="list-group list-group-flush">
                                <?php
                                // Get unique categories from products table
                                $category_query = "SELECT DISTINCT product_category FROM products";
                                $stmt = $conn->prepare($category_query);
                                $stmt->execute();
                                $categories = $stmt->get_result();
                                ?>
                                <li class="list-group-item">
                                    <a href="shop.php" class="text-dark">All Products</a>
                                </li>
                                <?php while($row = $categories->fetch_assoc()) { ?>
                                <li class="list-group-item">
                                    <a href="shop.php?category=<?php echo urlencode($row['product_category']); ?>" class="text-dark">
                                        <?php echo $row['product_category']; ?>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6>Price Range</h6>
                            <div class="price-range-slider">
                                <input type="range" class="form-range" min="0" max="1000" step="10" id="price-range">
                                <div class="d-flex justify-content-between mt-2">
                                    <span id="min-price">$0</span>
                                    <span id="max-price">$1000</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort By -->
                        <div class="mb-4">
                            <h6>Sort By</h6>
                            <select class="form-select" id="sort-by">
                                <option value="default">Default</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                                <option value="name_asc">Name: A to Z</option>
                                <option value="name_desc">Name: Z to A</option>
                            </select>
                        </div>
                        
                        <button class="btn btn-danger w-100" id="filter-btn">Apply Filters</button>
                    </div>
                </div>
                
                <!-- Products Column -->
                <div class="col-lg-9 col-md-8">
                    <div class="row">
                        <div class="col-12">
                            <h2>Our Products</h2>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="row" id="products-container">
                        <?php
                        // Default query to get all products
                        $product_query = "SELECT * FROM products";
                        
                        // Check if category filter is applied
                        if(isset($_GET['category'])) {
                            $product_category = $_GET['category'];
                            $product_query .= " WHERE product_category=?";
                            $stmt = $conn->prepare($product_query);
                            $stmt->bind_param('s', $product_category);
                        } else {
                            $stmt = $conn->prepare($product_query);
                        }
                        
                        $stmt->execute();
                        $products = $stmt->get_result();
                        
                        if($products->num_rows > 0) {
                            while($row = $products->fetch_assoc()) {
                                // Calculate sale price (10% off as example)
                                $sale_price = $row['product_price'] * 0.9;
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product-card">
                                <div class="product-img position-relative">
                                    <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>">
                                        <img src="assets/images/<?php echo $row['product_image1']; ?>" alt="<?php echo $row['product_name']; ?>" class="img-fluid">
                                    </a>
                                    <?php if($row['product_special_offer'] == 1) { ?>
                                    <div class="sale">SALE</div>
                                    <?php } ?>
                                </div>
                                <div class="product-info">
                                    <h5 class="product-name"><?php echo $row['product_name']; ?></h5>
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                    <div class="price">
                                        <?php if($row['product_special_offer'] == 1) { ?>
                                        <span class="text-danger"><del>$<?php echo number_format($row['product_price'], 2); ?></del></span>
                                        <span>$<?php echo number_format($sale_price, 2); ?></span>
                                        <?php } else { ?>
                                        <span>$<?php echo number_format($row['product_price'], 2); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="mt-3">
                                        <form class="add-to-cart-form">
                                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                            <input type="hidden" name="product_image" value="<?php echo $row['product_image1']; ?>">
                                            <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                                            <input type="hidden" name="product_price" value="<?php echo ($row['product_special_offer'] == 1) ? $sale_price : $row['product_price']; ?>">
                                            <button type="submit" class="btn btn-danger w-100 add-to-cart-btn">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo "<div class='col-12'><div class='alert alert-info'>No products found.</div></div>";
                        }
                        ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle Add to Cart via Ajax
            $('.add-to-cart-form').on('submit', function(event) {
                event.preventDefault();

                var form = $(this);
                $.ajax({
                    url: 'shop.php',
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if(response == 'added') {
                            var currentCount = parseInt($('.cart-count').text());
                            $('.cart-count').text(currentCount + 1);
                        }
                    }
                });
            });

            // Handle Search functionality
            $('#search-input').on('input', function() {
                var query = $(this).val();
                $.ajax({
                    url: 'shop.php',
                    method: 'GET',
                    data: { search: query },
                    success: function(response) {
                        $('#products-container').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>

