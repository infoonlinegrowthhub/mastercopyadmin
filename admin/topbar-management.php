<?php include 'layouts/top.php'; ?>

<?php
// Handle form submission for creating or updating topbar entry
if (isset($_POST['form_submit'])) {
    try {
        // Validate required fields
        if (empty($_POST['company_name']) || empty($_POST['address']) || empty($_POST['email'])) {
            throw new Exception("Company Name, Address, and Email are required fields.");
        }

        // Prepare query for insert or update
        if (isset($_POST['id'])) {
            // Update operation
            $statement = $pdo->prepare("
                UPDATE topbar SET 
                company_name = ?, address = ?, email = ?, facebook_link = ?, twitter_link = ?, linkedin_link = ?, instagram_link = ? 
                WHERE id = ?
            ");
            $statement->execute([
                $_POST['company_name'],
                $_POST['address'],
                $_POST['email'],
                $_POST['facebook_link'] ?? null,
                $_POST['twitter_link'] ?? null,
                $_POST['linkedin_link'] ?? null,
                $_POST['instagram_link'] ?? null,
                $_POST['id']
            ]);
            $success_message = "Topbar entry updated successfully!";
        } else {
            // Insert operation
            $statement = $pdo->prepare("
                INSERT INTO topbar (company_name, address, email, facebook_link, twitter_link, linkedin_link, instagram_link) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $statement->execute([
                $_POST['company_name'],
                $_POST['address'],
                $_POST['email'],
                $_POST['facebook_link'] ?? null,
                $_POST['twitter_link'] ?? null,
                $_POST['linkedin_link'] ?? null,
                $_POST['instagram_link'] ?? null
            ]);
            $success_message = "Topbar entry created successfully!";
        }

        // Redirect to the same page to prevent resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error_message = "A topbar entry with this email already exists.";
        } else {
            $error_message = $e->getMessage();
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Handle delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $statement = $pdo->prepare("DELETE FROM topbar WHERE id = ?");
    if ($statement->execute([$id])) {
        $success_message = "Topbar entry deleted successfully!";
    } else {
        $error_message = "Error deleting topbar entry.";
    }
}

// Fetch existing topbar data for display
$statement = $pdo->prepare("SELECT * FROM topbar");
$statement->execute();
$topbar_entries = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch specific topbar entry for editing
$topbar_entry = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $statement = $pdo->prepare("SELECT * FROM topbar WHERE id = ?");
    $statement->execute([$id]);
    $topbar_entry = $statement->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header bg-light p-4 rounded-3 mb-4">
            <h1 class="h3 mb-0 text-dark">Topbar Management</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                        $error_message
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                      </div>";
                            }
                            if (isset($success_message)) {
                                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        $success_message
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                      </div>";
                            }
                            ?>
                            
                            <h4 class="card-title mb-4">
                                <?php echo isset($topbar_entry) ? 'Edit Topbar Entry' : 'Add Topbar Entry'; ?>
                            </h4>

                            <form action="" method="post" class="needs-validation" novalidate>
                                <?php if (isset($topbar_entry)): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($topbar_entry['id']); ?>">
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium">Company Name *</label>
                                        <input type="text" class="form-control form-control-lg" name="company_name" 
                                               value="<?php echo isset($topbar_entry) ? htmlspecialchars($topbar_entry['company_name']) : ''; ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium">Email *</label>
                                        <input type="email" class="form-control form-control-lg" name="email" 
                                               value="<?php echo isset($topbar_entry) ? htmlspecialchars($topbar_entry['email']) : ''; ?>" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">Address *</label>
                                    <input type="text" class="form-control form-control-lg" name="address" 
                                           value="<?php echo isset($topbar_entry) ? htmlspecialchars($topbar_entry['address']) : ''; ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium">Facebook Link</label>
                                        <input type="url" class="form-control" name="facebook_link" 
                                               value="<?php echo isset($topbar_entry) ? htmlspecialchars($topbar_entry['facebook_link']) : ''; ?>">
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium">Twitter Link</label>
                                        <input type="url" class="form-control" name="twitter_link" 
                                               value="<?php echo isset($topbar_entry) ? htmlspecialchars($topbar_entry['twitter_link']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium">LinkedIn Link</label>
                                        <input type="url" class="form-control" name="linkedin_link" 
                                               value="<?php echo isset($topbar_entry) ? htmlspecialchars($topbar_entry['linkedin_link']) : ''; ?>">
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-medium">Instagram Link</label>
                                        <input type="url" class="form-control" name="instagram_link" 
                                               value="<?php echo isset($topbar_entry) ? htmlspecialchars($topbar_entry['instagram_link']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2" name="form_submit">
                                        <i class="fas fa-save me-2"></i>
                                        <?php echo isset($topbar_entry) ? 'Update Topbar Entry' : 'Create Topbar Entry'; ?>
                                    </button>
                                </div>
                            </form>

                            <h2 class="h4 pt-4 border-top mt-5 mb-4">Existing Topbar Entries</h2>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col">Company Name</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Social Links</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($topbar_entries as $entry): ?>
                                            <tr>
                                                <td class="text-center"><?php echo htmlspecialchars($entry['id']); ?></td>
                                                <td><?php echo htmlspecialchars($entry['company_name']); ?></td>
                                                <td><?php echo htmlspecialchars($entry['address']); ?></td>
                                                <td><?php echo htmlspecialchars($entry['email']); ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <?php if (!empty($entry['facebook_link'])): ?>
                                                            <a href="<?php echo htmlspecialchars($entry['facebook_link']); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                                <i class="fab fa-facebook"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (!empty($entry['twitter_link'])): ?>
                                                            <a href="<?php echo htmlspecialchars($entry['twitter_link']); ?>" class="btn btn-sm btn-outline-info" target="_blank">
                                                                <i class="fab fa-twitter"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (!empty($entry['linkedin_link'])): ?>
                                                            <a href="<?php echo htmlspecialchars($entry['linkedin_link']); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                                <i class="fab fa-linkedin"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (!empty($entry['instagram_link'])): ?>
                                                            <a href="<?php echo htmlspecialchars($entry['instagram_link']); ?>" class="btn btn-sm btn-outline-danger" target="_blank">
                                                                <i class="fab fa-instagram"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="?edit=<?php echo $entry['id']; ?>" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        <a href="?delete=<?php echo $entry['id']; ?>" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Are you sure you want to delete this entry?');">
                                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>