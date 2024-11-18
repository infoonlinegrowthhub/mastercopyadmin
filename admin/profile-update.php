<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the configuration file
require_once 'config/config.php'; 

// Fetch user ID from the session
$user_id = $_SESSION['user_id'];

// Handle form submission for updating the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));

    // Handle profile photo upload if provided
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($photo);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES['photo']['size'] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        // Move uploaded file to target directory if valid
        if ($uploadOk == 1) {
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
        }
    } else {
        // If no new photo is uploaded, keep the existing one
        $photo = $user['photo'];
    }

    // Update the user's profile in the database
    try {
        $query = "UPDATE users SET username = :username, email = :email, photo = :photo WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':photo', $photo);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        header("Location: profile.php?success=1");
        exit();
    } catch (PDOException $e) {
        error_log("Profile update error: " . $e->getMessage());
        header("Location: profile.php?error=1");
        exit();
    }
}

// If the request method is not POST, redirect to profile.php
header("Location: profile.php");
exit();
?>
