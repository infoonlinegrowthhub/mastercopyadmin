<?php
ob_start();
session_start();
require 'config/config.php';
include 'header.php';
include 'navbar.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

// Fetch user details
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile photo update
if (isset($_POST['update_photo']) && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

    if (in_array($photo['type'], $allowed_types)) {
        $file_extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
        $new_filename = 'user_' . $userId . '_' . time() . '.' . $file_extension;
        $upload_path = 'img/' . $new_filename;

        if (move_uploaded_file($photo['tmp_name'], $upload_path)) {
            $stmt = $pdo->prepare("UPDATE users SET photo = ? WHERE id = ?");
            $stmt->execute([$new_filename, $userId]);
            $user['photo'] = $new_filename;
            $_SESSION['success'] = "Profile photo updated successfully!";
        }
    } else {
        $_SESSION['error'] = "Invalid file type. Please upload JPG or PNG files only.";
    }
}

// Handle profile update
if (isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);

    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, mobile = ? WHERE id = ?");
        $stmt->execute([$name, $email, $mobile, $userId]);
        $_SESSION['success'] = "Profile updated successfully!";

        // Refresh user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating profile. Email might already be in use.";
    }
}

// Fetch user transactions including product info
$transactions_stmt = $pdo->prepare("SELECT cp.*, p.product_name, p.download_file 
                                     FROM cashfree_payment cp 
                                     JOIN products p ON cp.product_id = p.id 
                                     WHERE cp.customer_email = ?");
$transactions_stmt->execute([$user['email']]);
$transactions = $transactions_stmt->fetchAll(PDO::FETCH_ASSOC);

// Logout handling
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: user-login.php");
    exit();
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5">
    <div class="container text-center py-5">
        <h1 class="display-2 text-white mb-4 animated slideInDown">Profile</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="profile-sidebar">
                <div class="text-center">
                    <img src="img/<?= htmlspecialchars($user['photo'] ?? 'default.jpg'); ?>" 
                         alt="Profile Photo" 
                         class="profile-photo rounded-circle"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h5 class="mt-3"><?= htmlspecialchars($user['name']); ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($user['email']); ?></p>
                </div>
                
                <!-- Photo Upload Form -->
                <form action="" method="POST" enctype="multipart/form-data" class="mb-4 d-flex justify-content-center">
                    <div class="form-group">
                        <label for="photo" class="btn btn-outline-primary btn-sm btn-block">
                            Choose New Photo
                        </label>
                        <input type="file" id="photo" name="photo" class="d-none" onchange="this.form.submit()">
                        <input type="hidden" name="update_photo" value="1">
                    </div>
                </form>

                <!-- Logout Button -->
                <form action="" method="POST" class="text-center mb-4">
                    <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                </form>

                <!-- Updated Nav Pills -->
                <ul class="nav nav-pills flex-column" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="products-tab" data-bs-toggle="pill" href="#products" role="tab">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="security-tab" data-bs-toggle="pill" href="#security" role="tab">
                            <i class="fas fa-lock"></i> Security
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="help-tab" data-bs-toggle="pill" href="#help" role="tab">
                            <i class="fas fa-question-circle"></i> Help and Support
                        </a> <!-- New tab -->
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="profile-content">
                <div class="tab-content" id="profileTabsContent">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <h4>Profile Information</h4>
                        <form action="" method="POST">
                            <div class="form-group mb-3">
                                <label>Username</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Mobile</label>
                                <input type="text" class="form-control" name="mobile" value="<?= htmlspecialchars($user['mobile']); ?>">
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>

                    <!-- Products Tab -->
                    <div class="tab-pane fade" id="products" role="tabpanel">
                        <h4>Purchased Products</h4>
                        <?php if (empty($transactions)): ?>
                            <p>No products purchased yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Transaction ID</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Download</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($transaction['product_name']); ?></td>
                                                <td><?= htmlspecialchars($transaction['order_id']); ?></td>
                                                <td><?= htmlspecialchars($transaction['order_amount']); ?> INR</td>
                                                <td><?= htmlspecialchars($transaction['payment_status']); ?></td>
                                                <td><?= htmlspecialchars($transaction['payment_time']); ?></td>
                                                <td>
                                                    <?php if ($transaction['payment_status'] === 'SUCCESS' && !empty($transaction['download_file'])): ?>
                                                        <a href="img/<?= htmlspecialchars($transaction['download_file']); ?>" class="btn btn-success btn-sm" download>Download</a>
                                                    <?php else: ?>
                                                        <span class="text-muted">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel">
                        <h4>Reset Password</h4>
                        <p>If you have forgotten your password, please enter your email address below. You will receive a link to reset your password.</p>
                        <form action="user-forget-password.php" method="POST">
                            <div class="form-group mb-3">
                                <label>Email Address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit_email">Send Reset Link</button>
                        </form>
                        <hr>
                        <p>If you already have a reset link, please enter your new password:</p>
                        
                        <!-- Ensure the token is present before displaying the reset form -->
                        <?php if (isset($_GET['token'])): ?>
                            <form action="user-reset-password.php" method="POST">
                                <div class="form-group mb-3">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </form>
                        <?php else: ?>
                            <p class="text-danger">No reset link found. Please request a new link.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Help and Support Tab -->
                    <div class="tab-pane fade" id="help" role="tabpanel">
                        <h4>Help and Support</h4>
                        <p>If you have any questions or need assistance, feel free to reach out to us:</p>
                        <ul>
                            <li>Email: <a href="mailto:info@onlinegrowthhub.in">info@onlinegrowthhub.in</a></li>
                            <li>WhatsApp: <a href="https://wa.me/9032666855" target="_blank">9032666855</a></li> <!-- Updated link -->
                        </ul>
                        <p>We are here to help you!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php
ob_end_flush();
?>
<?php include 'footer.php'; ?>