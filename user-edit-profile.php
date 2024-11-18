<?php
session_start();
include 'config/config.php'; // Include your database configuration
include 'header.php'; // Include the header
include 'navbar.php'; // Include the navigation bar

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$statement = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: user-login.php");
    exit();
}

// Handle form submission for profile update
if (isset($_POST['update_profile'])) {
    try {
        // Validate input fields
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        
        if (empty($full_name)) {
            throw new Exception("Full name cannot be empty.");
        }
        if (empty($email)) {
            throw new Exception("Email cannot be empty.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid.");
        }

        // Handle profile photo upload
        $photo = $user['photo']; // Keep existing photo by default
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['photo']['tmp_name'];
            $file_name = basename($_FILES['photo']['name']);
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_extension, $allowed_extensions)) {
                // Generate unique filename and move uploaded file
                $new_file_name = uniqid() . '.' . $file_extension;
                move_uploaded_file($file_tmp, 'uploads/profile_photos/' . $new_file_name);
                $photo = $new_file_name; // Update photo variable
            } else {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            }
        }

        // Update user information in the database
        $update_statement = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, photo = ? WHERE id = ?");
        $update_statement->execute([$full_name, $email, $photo, $user_id]);

        $success_message = "Profile updated successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center">
        <h1 class="display-4 text-white animated slideInDown mb-4">Edit Profile</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                <li class="breadcrumb-item text-primary active" aria-current="page">Edit Profile</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php include 'user-sidebar.php'; // Include user sidebar ?>
        </div>
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">Update Your Profile</div>
                <div class="card-body">
                    <?php if (isset($success_message)) { ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php } ?>
                    <?php if (isset($error_message)) { ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php } ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Profile Photo</label>
                            <?php if ($user['photo']): ?>
                                <img src="uploads/profile_photos/<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="img-thumbnail mb-2" style="max-width: 150px;">
                            <?php endif; ?>
                            <input type="file" name="photo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary bg-website" name="update_profile">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; // Include the footer ?>
