<?php include 'layouts/top.php'; ?>

<?php
// Handling Create Operation
if (isset($_POST['form_create'])) {
    $number = $_POST['number'] ?? '';  // Using null coalescing operator

    // Insert into database
    $sql = "INSERT INTO whatsapp_settings (whatsapp_number) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$number])) {
        header("Location: whatsapp_settings.php?success=" . urlencode("WhatsApp setting added successfully!"));
        exit();
    } else {
        $error_message = "Error: " . $pdo->errorInfo()[2];
    }
}

// Handling Update Operation
if (isset($_POST['form_update'])) {
    $id = $_POST['id'] ?? ''; // Using null coalescing operator
    $number = $_POST['number'] ?? ''; // Using null coalescing operator

    $sql = "UPDATE whatsapp_settings SET whatsapp_number=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$number, $id])) {
        header("Location: whatsapp_settings.php?success=" . urlencode("WhatsApp setting updated successfully!"));
        exit();
    }
}

// Handling Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM whatsapp_settings WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id])) {
        header("Location: whatsapp_settings.php?success=" . urlencode("WhatsApp setting deleted successfully!"));
        exit();
    } else {
        $error_message = "Error: " . $pdo->errorInfo()[2];
    }
}

// Fetching WhatsApp Settings for Display
$statement = $pdo->prepare("SELECT * FROM whatsapp_settings");
$statement->execute();
$whatsapp_entries = $statement->fetchAll(PDO::FETCH_ASSOC);

// Handling Edit Operation
$whatsapp_entry = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $statement = $pdo->prepare("SELECT * FROM whatsapp_settings WHERE id=?");
    $statement->execute([$id]);
    $whatsapp_entry = $statement->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header bg-light p-4 rounded-3 mb-4">
            <h1 class="h3 mb-0 text-dark">WhatsApp Settings Management</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo $error_message; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_GET['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo htmlspecialchars($_GET['success']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <h4 class="card-title mb-4">
                                <i class="fas fa-comments me-2"></i>
                                <?php echo isset($whatsapp_entry) ? 'Edit WhatsApp Setting' : 'Add WhatsApp Setting'; ?>
                            </h4>

                            <form action="" method="post" class="needs-validation" novalidate>
                                <?php if (isset($whatsapp_entry)): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($whatsapp_entry['id']); ?>">
                                <?php endif; ?>

                                <div class="mb-4">
                                    <label class="form-label fw-medium">WhatsApp Number *</label>
                                    <input type="text" class="form-control form-control-lg" name="number" 
                                           value="<?php echo isset($whatsapp_entry) ? htmlspecialchars($whatsapp_entry['whatsapp_number']) : ''; ?>" required>
                                </div>

                                <div class="mb-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2" 
                                            name="<?php echo isset($whatsapp_entry) ? 'form_update' : 'form_create'; ?>">
                                        <i class="fas <?php echo isset($whatsapp_entry) ? 'fa-save' : 'fa-plus'; ?> me-2"></i>
                                        <?php echo isset($whatsapp_entry) ? 'Update WhatsApp Setting' : 'Create WhatsApp Setting'; ?>
                                    </button>
                                </div>
                            </form>

                            <h2 class="h4 pt-4 border-top mt-5 mb-4">Existing WhatsApp Settings</h2>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 60px;">#</th>
                                            <th scope="col">WhatsApp Number</th>
                                            <th scope="col" class="text-center" style="width: 150px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($whatsapp_entries as $entry): ?>
                                            <tr>
                                                <td class="text-center"><?php echo htmlspecialchars($entry['id']); ?></td>
                                                <td><?php echo htmlspecialchars($entry['whatsapp_number']); ?></td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="whatsapp_settings.php?edit=<?php echo $entry['id']; ?>" 
                                                           class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        <a href="whatsapp_settings.php?delete=<?php echo $entry['id']; ?>" 
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
