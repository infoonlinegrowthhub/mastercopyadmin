<?php include 'layouts/top.php'; ?>

<?php
// Redirect to dashboard if the admin is already logged in
if (isset($_SESSION['admin'])) {
    header('location: ' . ADMIN_URL . 'dashboard.php');
    exit(); // Ensure no further code is executed after redirect
}

// Initialize error message variable
$error_message = '';

// Check if the login form is submitted
if (isset($_POST['form_login'])) {
    try {
        // Trim and validate email
        $email = trim($_POST['email']);
        if ($email == '') {
            throw new Exception("Email cannot be empty");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }
        
        // Trim and validate password
        $password = trim($_POST['password']);
        if ($password == '') {
            throw new Exception("Password cannot be empty");
        }

        // Prepare and execute the query to find the user
        $q = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $q->execute([$email]);
        $user = $q->fetch(PDO::FETCH_ASSOC); // Fetch the user data

        // Check if user exists
        if (!$user) {
            throw new Exception("Email not found");
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            throw new Exception("Password does not match");
        }

        // Store the user info in session
        $_SESSION['admin'] = $user; // Store relevant user details as needed
        header('location: ' . ADMIN_URL . 'dashboard.php');
        exit(); // Ensure no further code is executed after redirect

    } catch (Exception $e) {
        $error_message = $e->getMessage(); // Store error message for display
    }
}
?>

<section class="section">
    <div class="container container-login">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="card card-primary border-box">
                    <div class="card-header card-header-auth">
                        <h4 class="text-center">Admin Panel Login</h4>
                    </div>
                    <div class="card-body card-body-auth">
                        <?php
                        // Display error message if exists
                        if (!empty($error_message)) {
                            echo '<script>alert("' . htmlspecialchars($error_message) . '")</script>';
                        }
                        ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" autocomplete="off" autofocus required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg w_100_p" name="form_login">
                                    Login
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?php echo ADMIN_URL; ?>forget-password.php">
                                        Forgot Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'layouts/footer.php'; ?>
