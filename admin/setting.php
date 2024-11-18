<?php include 'layouts/top.php'; ?>

<?php
// Handle Update Operation
if (isset($_POST['form_update'])) {
    $id = 1; // Assuming there's only one settings record

    // Initialize file upload variable for site icon
    $site_icon = $_FILES['site_icon']['name'] ? $_FILES['site_icon']['name'] : $_POST['current_site_icon'];
    
    // Get the site settings from the form
    $site_title = $_POST['site_title'];
    $site_description = $_POST['site_description'];
    $site_keywords = $_POST['site_keywords'];

    // If a new site icon is uploaded, process the upload
    if ($_FILES['site_icon']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'img/'; // Adjust this path as necessary
        move_uploaded_file($_FILES['site_icon']['tmp_name'], $upload_dir . $site_icon);
    }

    // Update query to match the current table structure
    $sql = "UPDATE settings SET site_title=?, keywords=?, description=?, site_icon=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    
    // Execute the update and handle success/error
    if ($stmt->execute([$site_title, $site_keywords, $site_description, $site_icon, $id])) {
        header("Location: setting.php?success=" . urlencode("Settings updated successfully!")); // Ensure correct redirect to setting.php
        exit();
    } else {
        $error_message = "Error: " . $pdo->errorInfo()[2];
    }
}

// Fetch Current Settings
$statement = $pdo->prepare("SELECT * FROM settings WHERE id=1");
$statement->execute();
$settings = $statement->fetch(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Website Settings</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (isset($error_message)): ?>
                                <div class='alert alert-danger'><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <?php if (isset($_GET['success'])): ?>
                                <div class='alert alert-success'><?php echo htmlspecialchars($_GET['success']); ?></div>
                            <?php endif; ?>
                            <h4>Update Site Settings</h4>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label class="form-label">Current Site Icon</label><br>
                                    <img src="<?php echo 'img/' . htmlspecialchars($settings['site_icon']); ?>" alt="Current Site Icon" style="max-width: 150px; max-height: 100px;"><br>
                                    <label class="form-label">Upload New Site Icon (if any)</label>
                                    <input type="file" class="form-control" name="site_icon" accept="image/*">
                                    <input type="hidden" name="current_site_icon" value="<?php echo htmlspecialchars($settings['site_icon']); ?>">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Site Title *</label>
                                    <input type="text" class="form-control" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Site Description *</label>
                                    <textarea class="form-control" name="site_description" required><?php echo htmlspecialchars($settings['description']); ?></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Site Keywords</label>
                                    <input type="text" class="form-control" name="site_keywords" value="<?php echo htmlspecialchars($settings['keywords']); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary" name="form_update">Update Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>
