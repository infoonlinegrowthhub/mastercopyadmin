<?php include 'layouts/top.php'; ?>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all users from the database
$statement = $pdo->prepare("SELECT * FROM users");
$statement->execute();
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_statement = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $delete_statement->execute([$delete_id]);
    $success_message = 'User deleted successfully!';
}

// Fetch user for editing
$edit_user = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_statement = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $edit_statement->execute([$edit_id]);
    $edit_user = $edit_statement->fetch(PDO::FETCH_ASSOC);
}

// Handle user update
if (isset($_POST['form_update'])) {
    try {
        if (empty($_POST['user_id']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['role'])) {
            throw new Exception("All fields are required.");
        }

        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        // Update user in the database
        $update_statement = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $update_statement->execute([$username, $email, $role, $user_id]);

        $success_message = 'User updated successfully!';
        // Refresh the users list after updating
        $statement = $pdo->prepare("SELECT * FROM users");
        $statement->execute();
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>User Management</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>$error_message</div>";
                            }
                            if (isset($success_message)) {
                                echo "<div class='alert alert-success'>$success_message</div>";
                            }
                            ?>
                            <!-- User Table -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($users)): ?>
                                        <tr>
                                            <td colspan="6">No users found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                                            <td>
                                                <a href="?edit_id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
                                                <a href="?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <!-- User Edit Form -->
                            <?php if ($edit_user): ?>
                                <form method="post" action="">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($edit_user['id']); ?>">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($edit_user['username']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($edit_user['email']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <input type="text" id="role" name="role" class="form-control" value="<?php echo htmlspecialchars($edit_user['role']); ?>" required>
                                    </div>
                                    <button type="submit" name="form_update" class="btn btn-primary">Update User</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>
