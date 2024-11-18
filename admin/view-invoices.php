<?php include 'layouts/top.php'; ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>View Invoices</h1>
        </div>
        <div class="section-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Invoice Number</th>
                            <th>Date</th>
                            <th>Client Name</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection
                        try {
                            $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        } catch (PDOException $e) {
                            die("Connection failed: " . $e->getMessage());
                        }

                        // Handle deletion of an invoice
                        if (isset($_GET['delete_id'])) {
                            $delete_id = (int)$_GET['delete_id'];
                            $stmt = $pdo->prepare("DELETE FROM invoices WHERE id = :id");
                            $stmt->bindParam(':id', $delete_id);
                            if ($stmt->execute()) {
                                echo '<div class="alert alert-success">Invoice deleted successfully.</div>';
                                // Refresh the page after deletion
                                header("Location: view-invoices.php");
                                exit();
                            } else {
                                echo '<div class="alert alert-danger">Failed to delete invoice.</div>';
                            }
                        }

                        // Fetch invoices from the database
                        $stmt = $pdo->prepare("SELECT * FROM invoices");
                        $stmt->execute();
                        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($invoices):
                            foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                                    <td><?php echo htmlspecialchars($invoice['invoice_date']); ?></td>
                                    <td><?php echo htmlspecialchars($invoice['client_name']); ?></td>
                                    <td><?php echo number_format($invoice['total_amount'], 2); ?></td>
                                    <td>
                                        <a href="view-invoices.php?invoice_number=<?php echo urlencode($invoice['invoice_number']); ?>" class="btn btn-info">View</a>
                                        <a href="view-invoices.php?delete_id=<?php echo $invoice['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="5">No invoices found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Display invoice details if an invoice number is provided -->
            <?php if (isset($_GET['invoice_number'])): 
                $invoice_number = htmlspecialchars($_GET['invoice_number']);
                
                // Fetch the invoice details from the database
                try {
                    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE invoice_number = :invoice_number");
                    $stmt->bindParam(':invoice_number', $invoice_number);
                    $stmt->execute();
                    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Error fetching invoice: ' . $e->getMessage() . '</div>';
                }

                if ($invoice): ?>
                    <h2>Invoice Details</h2>
                    <table class="table">
                        <tr>
                            <th>Invoice Number:</th>
                            <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td><?php echo htmlspecialchars($invoice['invoice_date']); ?></td>
                        </tr>
                        <tr>
                            <th>Due Date:</th>
                            <td><?php echo htmlspecialchars($invoice['due_date']); ?></td>
                        </tr>
                        <tr>
                            <th>Client Name:</th>
                            <td><?php echo htmlspecialchars($invoice['client_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Client Address:</th>
                            <td><?php echo htmlspecialchars($invoice['client_address']); ?></td>
                        </tr>
                        <tr>
                            <th>Client Contact:</th>
                            <td><?php echo htmlspecialchars($invoice['client_contact']); ?></td>
                        </tr>
                        <tr>
                            <th>Item Name:</th>
                            <td><?php echo htmlspecialchars($invoice['item_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Item Price:</th>
                            <td><?php echo number_format($invoice['item_price'], 2); ?></td>
                        </tr>
                        <tr>
                            <th>Item Quantity:</th>
                            <td><?php echo htmlspecialchars($invoice['item_quantity']); ?></td>
                        </tr>
                        <tr>
                            <th>Subtotal:</th>
                            <td><?php echo number_format($invoice['item_subtotal'], 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total Amount:</th>
                            <td><?php echo number_format($invoice['total_amount'], 2); ?></td>
                        </tr>
                        <tr>
                            <th>Tax:</th>
                            <td><?php echo number_format($invoice['tax'], 2); ?></td>
                        </tr>
                        <tr>
                            <th>Payment Instructions:</th>
                            <td><?php echo nl2br(htmlspecialchars($invoice['payment_instructions'])); ?></td>
                        </tr>
                        <tr>
                            <th>Terms & Conditions:</th>
                            <td><?php echo nl2br(htmlspecialchars($invoice['terms_conditions'])); ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">Invoice not found.</div>
                <?php endif; 
            endif; ?>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>
