<?php include 'layouts/top.php'; ?>

<?php
// Fetch footer content
function fetchFooterContent($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM footer_content LIMIT 1");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch footer links
function fetchFooterLinks($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM footer_links ORDER BY display_order");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch help links
function fetchHelpLinks($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM help_links ORDER BY display_order");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Initialize variables for footer content
$footer_content = fetchFooterContent($pdo);
$footer_links = fetchFooterLinks($pdo);
$help_links = fetchHelpLinks($pdo);

$footer_errors = [];

// Handle footer content form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['footer_content'])) {
    $company_name = trim($_POST['company_name']);
    $description = trim($_POST['description']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $copyright_text = trim($_POST['copyright_text']);
    $designer_name = trim($_POST['designer_name']);
    $designer_url = trim($_POST['designer_url']);

    // Validate input
    if (str_word_count($company_name) < 2) {
        $footer_errors[] = "Company name must contain at least two parts.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $footer_errors[] = "Please enter a valid email address.";
    }

    // If no errors, insert/update footer content
    if (empty($footer_errors)) {
        $stmt = $pdo->prepare("INSERT INTO footer_content 
            (company_name, description, address, phone, email, copyright_text, designer_name, designer_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
            company_name = VALUES(company_name),
            description = VALUES(description),
            address = VALUES(address),
            phone = VALUES(phone),
            email = VALUES(email),
            copyright_text = VALUES(copyright_text),
            designer_name = VALUES(designer_name),
            designer_url = VALUES(designer_url)");
        $stmt->execute([$company_name, $description, $address, $phone, $email, $copyright_text, $designer_name, $designer_url]);

        header("Location: footer-management.php");
        exit();
    }
}

// Handle footer links form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['footer_link'])) {
    $link_text = trim($_POST['link_text']);
    $link_url = trim($_POST['link_url']);
    $link_type = trim($_POST['link_type']);
    $display_order = trim($_POST['display_order']);
    $link_id = $_POST['link_id'] ?? null;

    // Validate link text and URL
    if (empty($link_text) || empty($link_url)) {
        $footer_errors[] = "Link text and URL cannot be empty.";
    }

    // If no errors, insert/update footer link
    if (empty($footer_errors)) {
        if ($link_id) {
            // Update existing link
            $stmt = $pdo->prepare("UPDATE footer_links SET link_text = ?, link_url = ?, link_type = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$link_text, $link_url, $link_type, $display_order, $link_id]);
        } else {
            // Insert new link
            $stmt = $pdo->prepare("INSERT INTO footer_links (link_text, link_url, link_type, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$link_text, $link_url, $link_type, $display_order]);
        }
        header("Location: footer-management.php");
        exit();
    }
}

// Handle footer link deletion
if (isset($_GET['delete_link'])) {
    $link_id = $_GET['delete_link'];
    $stmt = $pdo->prepare("DELETE FROM footer_links WHERE id = ?");
    $stmt->execute([$link_id]);
    header("Location: footer-management.php");
    exit();
}

// Handle help links form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['help_link'])) {
    $help_link_text = trim($_POST['help_link_text']);
    $help_link_url = trim($_POST['help_link_url']);
    $help_display_order = trim($_POST['help_display_order']);
    $help_link_id = $_POST['help_link_id'] ?? null;

    // Validate help link text and URL
    if (empty($help_link_text) || empty($help_link_url)) {
        $footer_errors[] = "Help link text and URL cannot be empty.";
    }

    // If no errors, insert/update help link
    if (empty($footer_errors)) {
        if ($help_link_id) {
            // Update existing help link
            $stmt = $pdo->prepare("UPDATE help_links SET link_text = ?, link_url = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$help_link_text, $help_link_url, $help_display_order, $help_link_id]);
        } else {
            // Insert new help link
            $stmt = $pdo->prepare("INSERT INTO help_links (link_text, link_url, display_order) VALUES (?, ?, ?)");
            $stmt->execute([$help_link_text, $help_link_url, $help_display_order]);
        }
        header("Location: footer-management.php");
        exit();
    }
}

// Handle help link deletion
if (isset($_GET['delete_help_link'])) {
    $help_link_id = $_GET['delete_help_link'];
    $stmt = $pdo->prepare("DELETE FROM help_links WHERE id = ?");
    $stmt->execute([$help_link_id]);
    header("Location: footer-management.php");
    exit();
}

// Pre-fill data for editing
$link_data = [];
if (isset($_GET['edit_link'])) {
    $link_id = $_GET['edit_link'];
    $stmt = $pdo->prepare("SELECT * FROM footer_links WHERE id = ?");
    $stmt->execute([$link_id]);
    $link_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

$help_link_data = [];
if (isset($_GET['edit_help_link'])) {
    $help_link_id = $_GET['edit_help_link'];
    $stmt = $pdo->prepare("SELECT * FROM help_links WHERE id = ?");
    $stmt->execute([$help_link_id]);
    $help_link_data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Footer Management</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <?php if (!empty($footer_errors)): ?>
                                <div class='alert alert-danger'>
                                    <?php foreach ($footer_errors as $error): ?>
                                        <p><?php echo htmlspecialchars($error); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <h4>Edit Footer Content</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="footer_content" value="1">
                                <div class="mb-3">
                                    <label class="form-label">Company Name *</label>
                                    <input type="text" class="form-control" name="company_name" value="<?php echo htmlspecialchars($footer_content['company_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description *</label>
                                    <textarea class="form-control" name="description" required><?php echo htmlspecialchars($footer_content['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($footer_content['address']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($footer_content['phone']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($footer_content['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Copyright Text</label>
                                    <input type="text" class="form-control" name="copyright_text" value="<?php echo htmlspecialchars($footer_content['copyright_text']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Designer Name *</label>
                                    <input type="text" class="form-control" name="designer_name" value="<?php echo htmlspecialchars($footer_content['designer_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Designer URL *</label>
                                    <input type="url" class="form-control" name="designer_url" value="<?php echo htmlspecialchars($footer_content['designer_url']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Footer Content</button>
                            </form>

                            <hr>

                            <h4>Manage Footer Links</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="footer_link" value="1">
                                <input type="hidden" name="link_id" value="<?php echo isset($link_data) ? $link_data['id'] : ''; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Link Text *</label>
                                    <input type="text" class="form-control" name="link_text" value="<?php echo isset($link_data) ? htmlspecialchars($link_data['link_text']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link URL *</label>
                                    <input type="url" class="form-control" name="link_url" value="<?php echo isset($link_data) ? htmlspecialchars($link_data['link_url']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link Type *</label>
                                    <select class="form-control" name="link_type" required>
                                        <option value="footer" <?php echo (isset($link_data) && $link_data['link_type'] === 'footer') ? 'selected' : ''; ?>>Footer</option>
                                        <option value="help" <?php echo (isset($link_data) && $link_data['link_type'] === 'help') ? 'selected' : ''; ?>>Help</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" class="form-control" name="display_order" value="<?php echo isset($link_data) ? htmlspecialchars($link_data['display_order']) : ''; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo isset($link_data) ? 'Update Link' : 'Add Link'; ?></button>
                            </form>

                            <hr>

                            <h4>Current Footer Links</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Link Text</th>
                                        <th>Link URL</th>
                                        <th>Type</th>
                                        <th>Display Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($footer_links as $link): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($link['link_text']); ?></td>
                                            <td><?php echo htmlspecialchars($link['link_url']); ?></td>
                                            <td><?php echo htmlspecialchars($link['link_type']); ?></td>
                                            <td><?php echo htmlspecialchars($link['display_order']); ?></td>
                                            <td>
                                                <a href="?edit_link=<?php echo $link['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="?delete_link=<?php echo $link['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this link?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <hr>

                            <h4>Manage Help Links</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="help_link" value="1">
                                <input type="hidden" name="help_link_id" value="<?php echo isset($help_link_data) ? $help_link_data['id'] : ''; ?>">
                                <div class="mb-3">
                                    <label class="form-label">Help Link Text *</label>
                                    <input type="text" class="form-control" name="help_link_text" value="<?php echo isset($help_link_data) ? htmlspecialchars($help_link_data['link_text']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Help Link URL *</label>
                                    <input type="url" class="form-control" name="help_link_url" value="<?php echo isset($help_link_data) ? htmlspecialchars($help_link_data['link_url']) : ''; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Help Display Order</label>
                                    <input type="number" class="form-control" name="help_display_order" value="<?php echo isset($help_link_data) ? htmlspecialchars($help_link_data['display_order']) : ''; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo isset($help_link_data) ? 'Update Help Link' : 'Add Help Link'; ?></button>
                            </form>

                            <hr>

                            <h4>Current Help Links</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Help Link Text</th>
                                        <th>Help Link URL</th>
                                        <th>Display Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($help_links as $help_link): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($help_link['link_text']); ?></td>
                                            <td><?php echo htmlspecialchars($help_link['link_url']); ?></td>
                                            <td><?php echo htmlspecialchars($help_link['display_order']); ?></td>
                                            <td>
                                                <a href="?edit_help_link=<?php echo $help_link['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="?delete_help_link=<?php echo $help_link['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this help link?');">Delete</a>
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
    </section>
</div>

<?php include 'layouts/footer.php'; ?>
