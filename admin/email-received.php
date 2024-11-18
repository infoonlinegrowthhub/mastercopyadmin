<?php 
include 'layouts/top.php'; 

// Set the number of rows per page
$rows_per_page = 10; 

// Get the current page from URL, default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting row for the SQL query
$start_from = ($page - 1) * $rows_per_page;

// Handle deletion if a POST request is made
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare and execute the delete statement
    $stmt = $pdo->prepare("DELETE FROM contact_submissions WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect to the same page with a success message
        header('Location: email-received.php?message=Email deleted successfully');
        exit();
    } else {
        // Redirect to the same page with an error message
        header('Location: email-received.php?message=Failed to delete email');
        exit();
    }
}
?>

<!-- Page Header Start -->
<div class="container-fluid page-header mb-5">
    <div class="container text-center">
        <h1 class="display-4 text-white">Emails Received</h1>
    </div>
</div>
<!-- Page Header End -->

<!-- Email List Start -->
<div class="container-xxl py-5">
    <div class="container">
        <h2 class="mb-4">List of Received Emails</h2>

        <?php
        // Display success or error message
        if (isset($_GET['message'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_GET['message']) . '</div>';
        }
        ?>

        <!-- Table Start -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Project</th>
                        <th>Message</th>
                        <th>Date Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch emails from the database with pagination
                    $stmt = $pdo->prepare("SELECT * FROM contact_submissions ORDER BY submitted_at DESC LIMIT :start, :rows_per_page");
                    $stmt->bindParam(':start', $start_from, PDO::PARAM_INT);
                    $stmt->bindParam(':rows_per_page', $rows_per_page, PDO::PARAM_INT);
                    $stmt->execute();
                    $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($emails)) {
                        echo '<tr><td colspan="7" class="text-center">No emails found.</td></tr>';
                    } else {
                        $counter = $start_from + 1;
                        foreach ($emails as $email) {
                            echo "<tr>
                                    <td>{$counter}</td>
                                    <td>{$email['name']}</td>
                                    <td>{$email['email']}</td>
                                    <td>{$email['project']}</td>
                                    <td>" . nl2br(htmlspecialchars($email['message'])) . "</td>
                                    <td>{$email['submitted_at']}</td>
                                    <td>
                                        <form action='' method='POST' style='display:inline;' class='d-inline'>
                                            <input type='hidden' name='id' value='{$email['id']}'>
                                            <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this email?\");'>Delete</button>
                                        </form>
                                    </td>
                                  </tr>";
                            $counter++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Table End -->

        <!-- Pagination Start -->
        <div class="pagination-wrapper text-center">
            <?php
            // Get total number of rows in the database
            $stmt = $pdo->query("SELECT COUNT(*) FROM contact_submissions");
            $total_rows = $stmt->fetchColumn();

            // Calculate total number of pages
            $total_pages = ceil($total_rows / $rows_per_page);

            // Display pagination links
            if ($total_pages > 1) {
                echo '<nav aria-label="Page navigation example">';
                echo '<ul class="pagination justify-content-center">';

                // Previous button
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="email-received.php?page=' . ($page - 1) . '">Previous</a></li>';
                }

                // Page number buttons
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active_class = ($i == $page) ? 'active' : '';
                    echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="email-received.php?page=' . $i . '">' . $i . '</a></li>';
                }

                // Next button
                if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="email-received.php?page=' . ($page + 1) . '">Next</a></li>';
                }

                echo '</ul>';
                echo '</nav>';
            }
            ?>
        </div>
        <!-- Pagination End -->
    </div>
</div>
<!-- Email List End -->

<?php include 'layouts/footer.php'; ?>
