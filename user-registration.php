<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'config/config.php'; 
include 'header.php'; 
include 'navbar.php'; 

if (isset($_POST['form_submit'])) {
    try {
        // Retrieve and sanitize input fields
        $username = trim($_POST['username']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $mobile = trim($_POST['mobile']);
        $password = $_POST['password'];
        $retype_password = $_POST['retype_password'];

        // Validate inputs
        if (empty($username) || empty($name) || empty($email) || empty($mobile)) {
            throw new Exception("All fields are required.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        if ($password !== $retype_password) {
            throw new Exception("Passwords do not match.");
        }
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }

        // Check if the email already exists
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute([$email]);
        if ($statement->rowCount() > 0) {
            throw new Exception("Email is already registered.");
        }

        // Secure password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';
        $status = 'inactive'; // Set status to inactive for verification
        $token = bin2hex(random_bytes(50)); // Generate a unique token for email verification
        $photo = 'default_photo.jpg'; // Placeholder for user photo

        // Insert user data into the database
        $statement = $pdo->prepare("INSERT INTO users (username, name, email, mobile, photo, password, role, status, token, created_at, updated_at) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $statement->execute([$username, $name, $email, $mobile, $photo, $hashed_password, $role, $status, $token]);

        // Send verification email
        $mail = new PHPMailer(true); 
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;

        // Recipients
        $mail->setFrom(SMTP_FROM, 'Your Company');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body    = 'Thank you for registering, ' . htmlspecialchars($name) . '! Please verify your email by clicking the link: <a href="'. BASE_URL .'user-registration-verify.php?email='. urlencode($email) .'&token='. $token .'">Verify Email</a>';

        $mail->send();

        // Display success message
        $success_message = 'Registration successful. A verification email has been sent. Please check your inbox to verify your account.';
    } catch (Exception $e) {
        // Log the error (optional)
        error_log("Registration Error: " . $e->getMessage());
        $error_message = $e->getMessage();
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header py-5">
    <div class="container text-center py-5">
        <h1 class="display-2 text-white mb-4 animated slideInDown">User Registration</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item" aria-current="page">User Registration</li>
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
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number *</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="retype_password" class="form-label">Confirm Password *</label>
                            <input type="password" name="retype_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website" name="form_submit">
                                Create Account
                            </button>
                        </div>
                    </form>
                    <div class="mb-3">
                        <a href="<?php echo BASE_URL; ?>user-login.php" class="primary-color">Existing User? Login Now</a>
                    </div>
                </div>
                <?php if (isset($success_message)) { echo '<div class="alert alert-success">' . htmlspecialchars($success_message) . '</div>'; } ?>
                <?php if (isset($error_message)) { echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>'; } ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
