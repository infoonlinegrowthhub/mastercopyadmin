<?php
// Include the configuration file first to establish the database connection
include 'config/config.php';

// Database connection
try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Include header and navbar
include 'header.php';
include 'navbar.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Payment Status</h2>
    <div class="text-wrap">
        <div class="box-wrap shadow-sm p-4 rounded">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="summary-div">
                        <?php 
                        if (isset($_GET['order_id']) && $_GET['order_id'] != '') {
                            $order_id = $_GET['order_id'];

                            // Fetch order details from the database
                            $stmt = $pdo->prepare("
                                SELECT cp.*, p.download_file 
                                FROM cashfree_payment cp 
                                JOIN products p ON cp.product_id = p.id 
                                WHERE cp.order_id = :order_id
                            ");
                            $stmt->bindParam(':order_id', $order_id);
                            $stmt->execute();
                            $paymentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($paymentDetails) {
                                // Update the payment status if it's pending
                                if ($paymentDetails['payment_status'] === 'pending') {
                                    // Update the payment status to SUCCESS
                                    $updateStmt = $pdo->prepare("
                                        UPDATE cashfree_payment 
                                        SET payment_status = :payment_status, payment_time = NOW() 
                                        WHERE order_id = :order_id
                                    ");
                                    $payment_status = 'SUCCESS'; // Set payment status as SUCCESS

                                    // Bind parameters
                                    $updateStmt->bindParam(':payment_status', $payment_status);
                                    $updateStmt->bindParam(':order_id', $order_id);

                                    // Execute the update query
                                    if ($updateStmt->execute()) {
                                        // Log successful update
                                        file_put_contents('data.log', "Updated order ID: $order_id to SUCCESS\n", FILE_APPEND);
                                    } else {
                                        // Handle update failure
                                        file_put_contents('data.log', "Failed to update order ID: $order_id\n", FILE_APPEND);
                                    }
                                }

                                // Display the payment details in a table
                                ?>
                                <p class="text-center text-success" style="font-size: 18px;">
                                    Payment Successful!
                                </p>
                                <table class="table table-bordered mt-4">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Order ID</th>
                                            <td><?php echo htmlspecialchars($paymentDetails['order_id']); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Amount</th>
                                            <td>₹<?php echo number_format($paymentDetails['order_amount'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Payment Status</th>
                                            <td><?php echo htmlspecialchars($paymentDetails['payment_status']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="orderDetails">
                                    <p class="mt-3">Thank you for your payment!</p>
                                    <?php if (!empty($paymentDetails['download_file'])): ?>
                                        <!-- If the download_file contains a URL (Google Drive), link directly to it -->
                                        <a href="<?php echo htmlspecialchars($paymentDetails['download_file']); ?>" class="btn btn-info" target="_blank" download>Download Your Product</a>
                                    <?php else: ?>
                                        <p>No download link available for this product.</p>
                                    <?php endif; ?>
                                </div>
                                <?php
                            } else {
                                // Order ID not found in the database
                                ?>
                                <p class="text-center text-danger">
                                    Payment not found or is unsuccessful.
                                </p>
                                <?php
                            }
                        } else {
                            // No order ID provided
                            ?>
                            <p class="text-center text-danger">
                                Invalid request. No order ID provided.
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include the footer
include 'footer.php';
?>
