<?php
session_start();
ob_start(); // Start output buffering
error_reporting(E_ALL);
ini_set('display_errors', 1); // Display errors for debugging

include 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar

$error_message = ''; // Initialize error message variable

if (isset($_POST['login_submit'])) {
    try {
        // Validate input fields
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email)) {
            throw new Exception("Email cannot be empty");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }
        if (empty($password)) {
            throw new Exception("Password cannot be empty");
        }

        // Check if the user exists
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute([$email]);

        if ($statement->rowCount() === 0) {
            throw new Exception("Invalid email or account not found.");
        }

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if the account is verified
        if ($user['status'] != 'active') { // Change here
            throw new Exception("Account not verified. Please check your email to verify.");
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            throw new Exception("Incorrect password.");
        }

        // Successful login
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        $_SESSION['user_name'] = $user['username']; // Store username in session
        header("Location: profile.php"); // Redirect to user dashboard
        exit(); // Stop further execution

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5">
    <div class="container text-center py-5">
        <h1 class="display-2 text-white mb-4 animated slideInDown">Login</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item" aria-current="page">Login</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                <div class="login-form">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website" name="login_submit">
                                Login
                            </button>
                        </div>
                    </form>
                    <div class="mb-3">
                        <a href="<?php echo BASE_URL; ?>user-registration.php" class="primary-color">New User? Register Now</a>
                    </div>
                    <div class="mb-3">
                        <a href="<?php echo BASE_URL; ?>user-forget-password.php" class="primary-color">Forgot Password?</a>
                    </div>
                </div>
                <?php if (!empty($error_message)) { echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>'; } ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Include the footer ?>
