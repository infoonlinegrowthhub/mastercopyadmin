<?php
session_start();
require 'config/config.php';
include 'header.php';
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user and product details
$user_stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$user_stmt->execute([':id' => $user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id AND status = 1");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<h5 class='text-danger text-center'>Product not found or inactive.</h5>";
    exit;
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5 text-white text-center bg-dark">
    <h1 class="display-4 mb-3">Checkout</h1>
    <p class="lead">Complete your purchase below</p>
</div>
<!-- Page Header End -->

<!-- Checkout Start -->
<div class="container my-5">
    <div class="row">
        <!-- Product Info Start -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <img src="img/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                <div class="card-body">
                    <h3 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                    <h4 class="text-danger">Rs. <?php echo number_format($product['price'], 2); ?></h4>
                </div>
            </div>
        </div>
        <!-- Product Info End -->

        <!-- Checkout Form Start -->
        <div class="col-md-6">
            <h3 class="text-center mb-4">Customer Details</h3>
            <form action="pay.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">

                <div class="form-group mb-3">
                    <label for="cust_name">Name:</label>
                    <input type="text" id="cust_name" name="cust_name" class="form-control" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Enter your name" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Enter your email" required>
                </div>

                <div class="form-group mb-4">
                    <label for="mobile">Mobile No:</label>
                    <input type="tel" id="mobile" name="mobile" class="form-control" 
                           value="<?php echo htmlspecialchars($user['mobile']); ?>" placeholder="Enter your mobile number" required>
                </div>

                <div class="text-center">
                    <h4>Total Amount: Rs. <?php echo number_format($product['price'], 2); ?></h4>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill py-3">Proceed to Payment</button>
                </div>
            </form>
        </div>
        <!-- Checkout Form End -->
    </div>
</div>
<!-- Checkout End -->

<?php include 'footer.php'; ?>
