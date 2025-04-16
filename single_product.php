<?php
    include("db_connect/db_connect.php");
    if(isset($_GET['product_id'])){
        $product_id = $GET['product_id'];
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param("i",$product_id);

        $stmt->execute();

        $products = $stmt->get_result();
        //no product id
    }else{
        header('location: index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macbook - Product Details</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/easy.css">
    <link rel="stylesheet" href="assets/css/single_product.css">
</head>
<body>

    <!-- Navigation -->
    <header>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="login.php">Sign In</a>
            <a href="account.php">Account</a>
            <a href="cart.php">
                <i class="bi bi-cart-fill"></i> Cart 
                <span class="cart-count">0</span>
            </a>
        </nav>
        <a href="index.php" class="nav_logo">
            <img src="assets/images/logo.png" alt="Logo">
        </a>
    </header>

    <!-- Single Product Section -->
    <section class="container single-product my-5 pt-5">
        <div class="row mt-5">

        <?php while($row = $product->fetch_assoc()) { ?>
            <!-- Product Images -->
            <div class="col-lg-5 col-md-6 col-sm-12">
                <img class="img-fluid w-100 pb-1" src="assets/images/Product/<?php echo $row['product_image1'];?>" id="mainImg" alt="Macbook Pro">
                <div class="small-image-group">
                    <div class="small-image-col">
                        <img src="assets/images/Product/<?php echo $row['product_image2'];?>" width="100%" class="small-img" alt="Macbook Side View">
                    </div>
                    <div class="small-image-col">
                        <img src="assets/images/Product/<?php echo $row['product_image3'];?>" width="100%" class="small-img" alt="Macbook Keyboard">
                    </div>
                    <div class="small-image-col">
                        <img src="assets/images/Product/<?php echo $row['product_image4'];?>" width="100%" class="small-img" alt="Macbook Side View">
                    </div>
                    <div class="small-image-col">
                        <img src="assets/images/Product/<?php echo $row['product_image1'];?>" width="100%" class="small-img" alt="Macbook Keyboard">
                    </div>
                </div>
            </div>
<?php } ?>

            <!-- Product Details -->
            <div class="col-lg-6 col-md-12 col-sm-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="shop.php">Electronics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Macbook</li>
                    </ol>
                </nav>
                
                <h3 class="py-2">Macbook Pro M2</h3>
                <div class="rating mb-3">
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-fill text-warning"></i>
                    <i class="bi bi-star-half text-warning"></i>
                    <span class="ms-2">(42 reviews)</span>
                </div>
                
                <h2 class="price mb-4"><?php echo $row['product_price'];?> <small class="text-danger text-decoration-line-through">$799</small></h2>
                
                <div class="quantity mb-4">
                    <label for="qty" class="form-label">Quantity:</label>
                    <input type="number" id="qty" class="form-control" value="1" min="1" max="10" style="width: 80px;">
                </div>
                
                <div class="d-flex gap-3 mb-4">
                    <button class="btn btn-primary add-to-cart">
                        <i class="bi bi-cart-plus"></i> Add To Cart
                    </button>
                </div>
                
                <div class="delivery-info mb-4">
                    <p><i class="bi bi-truck"></i> <strong>Free delivery</strong> on orders over $50</p>
                    <p><i class="bi bi-arrow-repeat"></i> 30-day return policy</p>
                </div>
                
                
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section id="related-products" class="container my-5 py-5">
        <div class="text-center mb-5">
            <h2>Related Products</h2>
            <p class="text-muted">You might also like these products</p>
        </div>
        
        <div class="row">
            <!-- Product 1 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <div class="badge bg-danger position-absolute mt-2 ms-2">Sale</div>
                    <img src="assets/images/Product/Camera Full set.jpg" class="card-img-top" alt="Camera Full Set">
                    <div class="card-body">
                        <div class="category mb-1"><small class="text-muted">Photography</small></div>
                        <h5 class="card-title">Camera Full Set</h5>
                        <div class="rating mb-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                            <i class="bi bi-star text-warning"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="price mb-0">$300 <small class="text-danger text-decoration-line-through">$350</small></h5>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-cart-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product 2 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <img src="assets/images/Product/drone.png" class="card-img-top" alt="Drone">
                    <div class="card-body">
                        <div class="category mb-1"><small class="text-muted">Drones</small></div>
                        <h5 class="card-title">Drone AX RoX Series 1</h5>
                        <div class="rating mb-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="price mb-0">$990</h5>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-cart-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product 3 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <div class="badge bg-success position-absolute mt-2 ms-2">New</div>
                    <img src="assets/images/Product/book.png" class="card-img-top" alt="Coffee Guide Book">
                    <div class="card-body">
                        <div class="category mb-1"><small class="text-muted">Books</small></div>
                        <h5 class="card-title">The Coffee Guide</h5>
                        <div class="rating mb-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="price mb-0">$10</h5>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-cart-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product 4 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <img src="assets/images/Product/homeTheater.png" class="card-img-top" alt="Home Theater">
                    <div class="card-body">
                        <div class="category mb-1"><small class="text-muted">Audio</small></div>
                        <h5 class="card-title">Home Theater 2:1</h5>
                        <div class="rating mb-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-half text-warning"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="price mb-0">$490 <small class="text-danger text-decoration-line-through">$550</small></h5>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-cart-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <img src="assets/images/logo-white.png" alt="Logo" class="mb-3" width="120">
                    <p>Your one-stop shop for all electronics and gadgets. Quality products at affordable prices.</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white me-2"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white">Home</a></li>
                        <li class="mb-2"><a href="about.php" class="text-white">About Us</a></li>
                        <li class="mb-2"><a href="shop.php" class="text-white">Shop</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-white">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Customer Service</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Shipping Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Return Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> 123 Street, City, Country</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> info@example.com</li>
                        <li class="mb-2"><i class="bi bi-phone me-2"></i> +123 456 7890</li>
                        <li><i class="bi bi-clock me-2"></i> Mon-Fri: 9AM - 6PM</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2024 YourShopName. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Powered by <a href="#" class="text-white">Prisom</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <script>
        // Image gallery functionality
        var mainImg = document.getElementById("mainImg");
        var smallImg = document.getElementsByClassName("small-img");
    
        for (let i = 0; i < smallImg.length; i++) {
            smallImg[i].onclick = function() {
                mainImg.src = smallImg[i].src;
            }
        }
        
        // Add to cart functionality
        document.querySelector('.add-to-cart').addEventListener('click', function() {
            const quantity = document.getElementById('qty').value;
            // Here you would typically send this data to your server
            alert(`Added ${quantity} Macbook(s) to your cart!`);
            
            // Update cart count
            const cartCount = document.querySelector('.cart-count');
            cartCount.textContent = parseInt(cartCount.textContent) + parseInt(quantity);
            cartCount.style.display = 'inline-block';
        });
    </script>
</body>
</html>