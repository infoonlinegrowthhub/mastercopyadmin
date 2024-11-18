<?php include 'layouts/top.php'; ?>

<?php
if (isset($_POST['form_update'])) {
    try {
        // Validate Name and Email
        if (empty($_POST['full_name'])) {
            throw new Exception("Full Name cannot be empty");
        }
        if (empty($_POST['email'])) {
            throw new Exception("Email cannot be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }

        // Update Name and Email
        $statement = $pdo->prepare("UPDATE users SET username=?, email=? WHERE id=?");
        if (!$statement->execute([$_POST['full_name'], $_POST['email'], $_SESSION['admin']['id']])) {
            throw new Exception("Failed to update user details: " . implode(", ", $statement->errorInfo()));
        }

        // Update Password if provided
        if (!empty($_POST['password']) || !empty($_POST['retype_password'])) {
            if ($_POST['password'] !== $_POST['retype_password']) {
                throw new Exception("Passwords do not match");
            } else {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $statement = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
                $statement->execute([$password, $_SESSION['admin']['id']]);
            }
        }

        // Update Photo
        if ($_FILES['photo']['name'] != '') {
            $path_tmp = $_FILES['photo']['tmp_name'];
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = time() . "." . $extension;

            // Validate image type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);

            if ($mime == 'image/jpeg' || $mime == 'image/png') {
                // Delete the old photo if it exists
                if (!empty($_SESSION['admin']['photo'])) {
                    $old_photo_path = '../img/' . $_SESSION['admin']['photo'];
                    if (file_exists($old_photo_path)) {
                        unlink($old_photo_path);
                    }
                }

                // Move the new photo to the img directory
                if (move_uploaded_file($path_tmp, '../img/' . $filename)) {
                    $statement = $pdo->prepare("UPDATE users SET photo=? WHERE id=?");
                    $statement->execute([$filename, $_SESSION['admin']['id']]);
                    $_SESSION['admin']['photo'] = $filename; // Update session variable
                } else {
                    throw new Exception("Failed to move uploaded file.");
                }
            } else {
                throw new Exception("Please upload a valid photo (JPEG or PNG)");
            }
        }

        $success_message = 'Profile data updated successfully!';
        $_SESSION['admin']['full_name'] = $_POST['full_name']; // Update session variable
        $_SESSION['admin']['email'] = $_POST['email']; // Update session variable

    } catch (Exception $e) {
        $error_message = $e->getMessage(); // Store error message
        error_log("Error: " . $error_message); // Log error for debugging
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profile</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if (isset($error_message)) {
                                echo "<script>alert('$error_message')</script>"; // Show error message
                            }
                            if (isset($success_message)) {
                                echo "<script>alert('$success_message')</script>"; // Show success message
                            }
                            ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php if (empty($_SESSION['admin']['photo'])): ?>
                                            <img src="<?php echo BASE_URL; ?>img/default.png" alt="" class="profile-photo w_100_p">
                                        <?php else: ?>
                                            <img src="<?php echo BASE_URL; ?>img/<?php echo $_SESSION['admin']['photo']; ?>" alt="" class="profile-photo w_100_p">
                                        <?php endif; ?>
                                        <input type="file" class="mt_10" name="photo">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-4">
                                            <label class="form-label">Name *</label>
                                            <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($_SESSION['admin']['full_name'], ENT_QUOTES); ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Email *</label>
                                            <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($_SESSION['admin']['email'], ENT_QUOTES); ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Retype Password</label>
                                            <input type="password" class="form-control" name="retype_password">
                                        </div>
                                        <div class="mb-4">
                                            <button type="submit" class="btn btn-primary" name="form_update">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>
