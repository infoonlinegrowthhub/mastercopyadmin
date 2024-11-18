<?php include 'layouts/top.php'; ?>

<?php
// Define directories for uploads
$upload_dir = 'img/'; // Directory for product images

// Ensure the directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Initialize error message variable
$error_message = '';

// Function to handle file uploads with unique filenames
function handleFileUpload($file, $target_dir) {
    // Ensure file is uploaded
    if (!$file['tmp_name']) {
        return ['success' => false, 'message' => 'No file uploaded.'];
    }

    // Generate a unique filename using timestamp and a random string
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $unique_filename;

    // Check file size (5MB limit)
    if ($file['size'] > 5000000) {
        return ['success' => false, 'message' => "File is too large."];
    }

    // Check for valid file types (optional: restrict to certain formats)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => "Invalid file type."];
    }

    // Attempt to move the uploaded file
    if (!move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => false, 'message' => "Error uploading file."];
    }

    return ['success' => true, 'file_name' => $unique_filename]; // Return the unique filename
}

// Handle Create Operation
if (isset($_POST['form_create'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $live_preview_url = $_POST['live_preview_url'];
    $download_file = $_POST['download_file']; // Google Drive link
    
    // Upload the product image
    $uploadResult = handleFileUpload($_FILES['image'], $upload_dir);
    if ($uploadResult['success']) {
        // Insert product into the database
        $sql = "INSERT INTO products (product_name, description, price, image, live_preview_url, download_file, status) VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$product_name, $description, $price, $uploadResult['file_name'], $live_preview_url, $download_file])) {
            header("Location: products-management.php?success=" . urlencode("Product added successfully!"));
            exit();
        } else {
            $error_message = "Database error: " . $pdo->errorInfo()[2];
        }
    } else {
        $error_message = $uploadResult['message'];
    }
}

// Handle Update Operation
if (isset($_POST['form_update'])) {
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $live_preview_url = $_POST['live_preview_url'];
    $download_file = $_POST['download_file']; // Google Drive link
    
    // Prepare the update statement
    $sql = "UPDATE products SET product_name=?, description=?, price=?, live_preview_url=?, download_file=?";
    $params = [$product_name, $description, $price, $live_preview_url, $download_file];

    // Check if a new image is uploaded
    if ($_FILES['image']['name']) {
        $uploadResult = handleFileUpload($_FILES['image'], $upload_dir);
        if ($uploadResult['success']) {
            $params[] = $uploadResult['file_name']; // Use the original filename
            $sql .= ", image=?";
        } else {
            $error_message = $uploadResult['message'];
        }
    }

    // Complete the SQL statement
    $sql .= " WHERE id=?";
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        header("Location: products-management.php?success=" . urlencode("Product updated successfully!"));
        exit();
    } else {
        $error_message = "Database error: " . $pdo->errorInfo()[2];
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id])) {
        header("Location: products-management.php?success=" . urlencode("Product deleted successfully!"));
        exit();
    } else {
        $error_message = "Error: " . $pdo->errorInfo()[2];
    }
}

// Fetch Products for Display
$statement = $pdo->prepare("SELECT * FROM products");
$statement->execute();
$product_entries = $statement->fetchAll(PDO::FETCH_ASSOC);

// Edit Operation
$product_entry = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $statement = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $statement->execute([$id]);
    $product_entry = $statement->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Product Management</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (!empty($error_message)): ?>
                                <div class='alert alert-danger'><?php echo $error_message; ?></div>
                            <?php endif; ?>
                            <?php if (isset($_GET['success'])): ?>
                                <div class='alert alert-success'><?php echo htmlspecialchars($_GET['success']); ?></div>
                            <?php endif; ?>
                            <h4><?php echo isset($product_entry) ? 'Edit Product Entry' : 'Add Product Entry'; ?></h4>
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php if (isset($product_entry)): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product_entry['id']); ?>">
                                <?php endif; ?>
                                <div class="mb-4">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" class="form-control" name="product_name" value="<?php echo isset($product_entry) ? htmlspecialchars($product_entry['product_name']) : ''; ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Description *</label>
                                    <textarea class="form-control" name="description" required><?php echo isset($product_entry) ? htmlspecialchars($product_entry['description']) : ''; ?></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Price *</label>
                                    <input type="text" class="form-control" name="price" value="<?php echo isset($product_entry) ? htmlspecialchars($product_entry['price']) : ''; ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Image *</label>
                                    <input type="file" class="form-control" name="image" <?php echo isset($product_entry) ? '' : 'required'; ?>>
                                    <?php if (isset($product_entry)): ?>
                                        <p>Current Image:</p>
                                        <img src="<?php echo htmlspecialchars(BASE_URL . $upload_dir . $product_entry['image']); ?>" alt="Current Product Image" style="max-width: 200px;"/>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Live Preview URL</label>
                                    <input type="url" class="form-control" name="live_preview_url" value="<?php echo isset($product_entry) ? htmlspecialchars($product_entry['live_preview_url']) : ''; ?>">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Download File (Google Drive Link) *</label>
                                    <input type="url" class="form-control" name="download_file" 
                                           value="<?php echo isset($product_entry) ? htmlspecialchars($product_entry['download_file']) : ''; ?>" 
                                           placeholder="Enter Google Drive link" required>
                                    <?php if (isset($product_entry) && $product_entry['download_file']): ?>
                                        <p>Current Download File:</p>
                                        <a href="<?php echo htmlspecialchars($product_entry['download_file']); ?>" target="_blank">Download Current File</a>
                                    <?php endif; ?>
                                </div>
                                <button type="submit" name="<?php echo isset($product_entry) ? 'form_update' : 'form_create'; ?>" class="btn btn-primary">
                                    <?php echo isset($product_entry) ? 'Update Product' : 'Add Product'; ?>
                                </button>
                            </form>
                            <hr>
                            <h4>Products List</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($product_entries as $product): ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                                            <td><img src="<?php echo BASE_URL . 'admin/img/' . $product['image']; ?>" alt="Product Image" style="max-width: 100px;"></td>
                                            <td>
                                                <a href="products-management.php?edit=<?php echo $product['id']; ?>" class="btn btn-warning">Edit</a>
                                                <a href="products-management.php?delete=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
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
