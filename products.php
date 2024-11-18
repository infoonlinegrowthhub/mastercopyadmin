<?php
session_start();
require 'config/config.php'; // Database configuration
include 'header.php'; // Header inclusion
include 'navbar.php'; // Navigation bar

// Database connection
try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Fetch products from the database
$stmt = $pdo->prepare("SELECT * FROM products WHERE status = 1 ORDER BY created DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5">
    <div class="container text-center py-5">
        <h1 class="display-2 text-white mb-4 animated slideInDown">Our Products</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                <li class="breadcrumb-item" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<!-- Products Section Start -->
<div class="container py-5">
    <div class="text-center mx-auto pb-5 wow fadeIn" data-wow-delay=".3s" style="max-width: 600px;">
        <h5 class="text-primary fw-bold mb-3">Explore Our Exclusive Theme Collection</h5>
    </div>
    <div class="row g-5">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6 mb-4 wow fadeIn" data-wow-delay=".5s">
                    <div class="card h-100 shadow rounded border-0">
                        <!-- Product Image -->
                        <img src="<?php echo BASE_URL . 'admin/img/' . htmlspecialchars($product['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                             style="width:100%; height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($product['price'], 2); ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo htmlspecialchars($product['live_preview_url']); ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-info">
                                    Live Preview
                                </a>
                                <?php if (isset($_SESSION['user_id'])): // User is logged in ?>
                                    <a href="<?php echo BASE_URL; ?>checkout.php?product_id=<?php echo $product['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        Buy Now
                                    </a>
                                <?php else: // User is not logged in ?>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        Buy Now
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No products available at the moment.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Products Section End -->

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Log in to Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo BASE_URL; ?>user-login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Log In</button>
                </form>
                <p class="mt-3">Don't have an account? <a href="<?php echo BASE_URL; ?>user-registration.php">Sign up here</a></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Footer inclusion ?>
