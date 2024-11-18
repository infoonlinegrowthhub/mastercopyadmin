<?php
session_start();
include 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    try {
        // Check if the token and email match a user in the database with inactive status
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = ? AND token = ? AND status = 'inactive'");
        $statement->execute([$email, $token]);

        if ($statement->rowCount() === 0) {
            throw new Exception("Invalid or expired verification link.");
        }

        // Update the user's status to active and clear the token
        $updateStatement = $pdo->prepare("UPDATE users SET status = 'active', token = NULL WHERE email = ?");
        $updateStatement->execute([$email]);

        // Success message
        $success_message = "Your account has been successfully verified. You can now log in.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center">
        <h1 class="display-4 text-white animated slideInDown mb-4">Verify Registration</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                <li class="breadcrumb-item text-primary active" aria-current="page">Verify Registration</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                <div class="login-form text-center">
                    <?php if (isset($success_message)) { ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                        <a href="<?php echo BASE_URL; ?>user-login.php" class="btn btn-primary bg-website">Login Now</a>
                    <?php } elseif (isset($error_message)) { ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php } else { ?>
                        <div class="alert alert-info">Verifying your account, please wait...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
