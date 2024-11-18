<?php
session_start();
include 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust this path if necessary

if (isset($_POST['form_submit'])) {
    try {
        // Validate email input
        if (empty($_POST['email'])) {
            throw new Exception("Email cannot be empty");
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }

        // Check if the email exists in the database
        $statement = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $statement->execute([$_POST['email']]);
        
        if ($statement->rowCount() === 0) {
            throw new Exception("Email not found");
        }

        $user = $statement->fetch();
        $token = bin2hex(random_bytes(50)); // Generate a secure random token
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour')); // Set token expiration time

        // Store the token in the database
        $updateStatement = $pdo->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
        $updateStatement->execute([$token, $expires, $_POST['email']]);

        // Create the reset link
        $link = BASE_URL . 'user-reset-password.php?token=' . $token;
        $email_message = 'Please click on this link to reset your password: <br>';
        $email_message .= '<a href="' . $link . '">Click Here</a>';

        // PHPMailer setup
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '3af43279318b3d';
        $phpmailer->Password = 'f81178c2fc560f';
        $phpmailer->setFrom(SMTP_FROM);
        $phpmailer->addAddress($_POST['email']);
        $phpmailer->isHTML(true);
        $phpmailer->Subject = 'Password Reset Request';
        $phpmailer->Body = $email_message;

        if ($phpmailer->send()) {
            $success_message = 'Password reset link has been sent to your email address. Please check your inbox.';
        } else {
            throw new Exception("Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}");
        }

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center">
        <h1 class="display-4 text-white animated slideInDown mb-4">Forgot Password</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                <li class="breadcrumb-item text-primary active" aria-current="page">Forgot Password</li>
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
                            <input type="email" name="email" class="form-control" value="<?php if(isset($_POST['email'])) { echo htmlspecialchars($_POST['email']); } ?>" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website" name="form_submit">
                                Send Reset Link
                            </button>
                        </div>
                    </form>
                    <div class="mb-3">
                        <a href="<?php echo BASE_URL; ?>user-login.php" class="primary-color">Back to Login</a>
                    </div>
                </div>
                <?php if (isset($success_message)) { echo '<div class="alert alert-success">' . htmlspecialchars($success_message) . '</div>'; } ?>
                <?php if (isset($error_message)) { echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>'; } ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Include the footer ?>
