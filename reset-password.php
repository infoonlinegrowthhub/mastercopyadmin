<?php
session_start();
include 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar

// Initialize messages
$success_message = '';
$error_message = '';

// Handle password reset request
if (isset($_POST['form_submit'])) {
    $email = $_POST['email'];

    // Validate email
    if (empty($email)) {
        $error_message = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        try {
            // Check if the email exists in the database
            $statement = $pdo->prepare("SELECT * FROM users WHERE email=?");
            $statement->execute([$email]);
            if ($statement->rowCount() === 0) {
                throw new Exception("Email not found.");
            }

            // Generate token
            $token = bin2hex(random_bytes(50));
            $updateStatement = $pdo->prepare("UPDATE users SET token=? WHERE email=?");
            $updateStatement->execute([$token, $email]);

            // Prepare the password reset link
            $link = BASE_URL . 'user-reset-password-verify.php?email=' . urlencode($email) . '&token=' . $token;
            $email_message = 'Please click on this link to reset your password: <br>';
            $email_message .= '<a href="' . $link . '">Reset Password</a>';

            // Send email using PHPMailer
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 2525;
            $phpmailer->Username = '3af43279318b3d';
            $phpmailer->Password = 'f81178c2fc560f';
            $phpmailer->setFrom(SMTP_FROM);
            $phpmailer->addAddress($email);
            $phpmailer->isHTML(true);
            $phpmailer->Subject = 'Password Reset Request';
            $phpmailer->Body = $email_message;

            if ($phpmailer->send()) {
                $success_message = 'A password reset link has been sent to your email address.';
            } else {
                throw new Exception("Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}");
            }

        } catch (Exception $e) {
            $error_message = $e->getMessage();
        }
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center">
        <h1 class="display-4 text-white animated slideInDown mb-4">Reset Password</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                <li class="breadcrumb-item text-primary active" aria-current="page">Reset Password</li>
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
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website" name="form_submit">
                                Send Reset Link
                            </button>
                        </div>
                    </form>
                    <?php if ($success_message) { echo '<div class="alert alert-success">' . htmlspecialchars($success_message) . '</div>'; } ?>
                    <?php if ($error_message) { echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>'; } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Include the footer ?>
