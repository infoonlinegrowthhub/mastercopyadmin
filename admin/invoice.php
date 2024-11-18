<?php include 'layouts/top.php'; ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Create or View Invoice</h1>
        </div>
        <div class="section-body">

            <?php
            // Automatically generate the next invoice number
            $invoice_number = '';
            try {
                $stmt = $pdo->query("SELECT MAX(invoice_number) AS last_invoice FROM invoices");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $last_invoice_number = $result['last_invoice'] ?? 'INV0000';
                
                // Increment the last invoice number
                $last_number = (int) substr($last_invoice_number, 3); // Assuming the format INV0000
                $new_invoice_number = 'INV' . str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger">Error fetching last invoice number: ' . $e->getMessage() . '</div>';
            }

            // Check if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve form data
                $invoice_date = htmlspecialchars($_POST['invoice_date']);
                $due_date = htmlspecialchars($_POST['due_date']);
                $client_id = htmlspecialchars($_POST['client_id']); // Use client_id instead of name
                
                // Fetch the client details
                $stmt = $pdo->prepare("SELECT name, address, contact FROM clients WHERE id = :id");
                $stmt->bindParam(':id', $client_id);
                $stmt->execute();
                $client = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($client) {
                    $client_name = $client['name'];
                    $client_address = $client['address'];
                    $client_contact = $client['contact'];
                } else {
                    echo '<div class="alert alert-danger">Client not found.</div>';
                    exit;
                }
                
                $item_name = htmlspecialchars($_POST['item_name']);
                $item_price = htmlspecialchars($_POST['item_price']);
                $item_quantity = htmlspecialchars($_POST['item_quantity']);
                $item_subtotal = $item_price * $item_quantity; // Calculate subtotal
                $total_amount = htmlspecialchars($_POST['total_amount']);
                $tax = htmlspecialchars($_POST['tax']);
                $payment_instructions = htmlspecialchars($_POST['payment_instructions']);
                $terms_conditions = htmlspecialchars($_POST['terms_conditions']);

                // Insert the invoice data into the database
                try {
                    $stmt = $pdo->prepare("INSERT INTO invoices (invoice_number, invoice_date, due_date, client_name, client_address, client_contact, item_name, item_price, item_quantity, item_subtotal, total_amount, tax, payment_instructions, terms_conditions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$new_invoice_number, $invoice_date, $due_date, $client_name, $client_address, $client_contact, $item_name, $item_price, $item_quantity, $item_subtotal, $total_amount, $tax, $payment_instructions, $terms_conditions]);

                    // Redirect to the same page to view the generated invoice
                    header("Location: invoice.php?invoice_number=$new_invoice_number");
                    exit;

                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Error saving invoice: ' . $e->getMessage() . '</div>';
                }
            }

            // Check if an invoice number is provided to display the invoice
            if (isset($_GET['invoice_number'])) {
                // Fetch the invoice data from the database
                $invoice_number = htmlspecialchars($_GET['invoice_number']);
                try {
                    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE invoice_number = :invoice_number");
                    $stmt->bindParam(':invoice_number', $invoice_number);
                    $stmt->execute();
                    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$invoice) {
                        echo '<div class="alert alert-danger">Invoice not found.</div>';
                    } else {
                        // Extract invoice details
                        $invoice_date = $invoice['invoice_date'];
                        $due_date = $invoice['due_date'];
                        $client_name = $invoice['client_name'];
                        $client_address = $invoice['client_address'];
                        $client_contact = $invoice['client_contact'];
                        $item_name = $invoice['item_name'];
                        $item_price = $invoice['item_price'];
                        $item_quantity = $invoice['item_quantity'];
                        $item_subtotal = $invoice['item_subtotal'];
                        $total_amount = $invoice['total_amount'];
                        $tax = $invoice['tax'];
                        $total_due = $total_amount + $tax;
                        $payment_instructions = $invoice['payment_instructions'];
                        $terms_conditions = $invoice['terms_conditions'];
                        ?>

<div class="invoice">
    <!-- Invoice Header -->
    <div class="invoice-header d-flex justify-content-between align-items-center" style="margin-bottom: 0;">
        <h1 class="invoice-heading" style="margin-bottom: 0;">Invoice</h1>
        <div class="invoice-details text-right" style="margin-bottom: 0;">
            <h6 class="font-weight-bold" style="margin-bottom: 0;">Invoice Number:</h6>
            <p style="margin: 0;"><?php echo $invoice_number; ?></p>
            <p style="margin: 0;"><strong>Invoice Date:</strong> <?php echo $invoice_date; ?></p>
            <p style="margin: 0;"><strong>Due Date:</strong> <?php echo $due_date; ?></p>
        </div>
    </div>
    <hr class="invoice-divider">

    <div class="row invoice-details">
        <div class="col-12">
            <table class="table">
                <tr>
                    <td class="w-50">
                        <div class="client-details">
                            <strong>Invoice To:</strong><br>
                            <?php echo $client_name; ?><br>
                            <?php echo nl2br($client_address); ?><br>
                            <?php echo $client_contact; ?>
                        </div>
                    </td>
                    <td class="w-50 text-right">
                    <div class="company-details d-flex align-items-center justify-content-end">
                        <img src="img/logo.png" alt="Company Logo" class="invoice-logo" style="width: 100px; height: auto; margin-right: 15px;">
                        <div class="text-right">
                            <strong>Online Growth Hub</strong><br>
                            Madhuban Rd, Belthara, Uttar Pradesh 221715<br>
                            <strong>Phone:</strong> 9032666855 <br>
                            <strong>Email:</strong> info@onlinegrowthhub.in <br>
                            <strong>Website:</strong> <a href="https://onlinegrowthhub.in" target="_blank">onlinegrowthhub.in</a>
                        </div>
                    </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="order-summary mt-0">
        <h4 class="section-title">Invoice Summary</h4>
        <hr class="invoice-divider">
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><?php echo $item_name; ?></td>
                        <td class="text-center"><?php echo number_format($item_price, 2); ?></td>
                        <td class="text-center"><?php echo $item_quantity; ?></td>
                        <td class="text-right"><?php echo number_format($item_subtotal, 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="text-right"><?php echo number_format($total_amount, 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Tax:</strong></td>
                        <td class="text-right"><?php echo number_format($tax, 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Total Due:</strong></td>
                        <td class="text-right"><strong><?php echo number_format($total_due, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Advertisement Section -->
    <div class="advertisement-section mt-3 mb-3" style="border: 1px dashed #ccc; padding: 8px; text-align: center;">
        <h5 style="margin: 0; color: #007bff;">Online Growth Hub – Your Growth Partner</h5>
        <p style="margin: 5px 0;">Trusted by 95% of clients for E-Commerce, NGO & WordPress Websites, PHP Development, Software & Portal Development, Digital Marketing, and Graphic Design.</p>
        <p style="margin: 5px 0;">Let’s work together to elevate your business with tailored, high-quality solutions.</p>
    </div>



    <hr class="invoice-divider">

    <!-- Payment Info Section -->
    <div class="payment-info mt-0">
        <h5><strong>Payment Terms:</strong></h5>
        <p>Payment is due within 30 days of receipt of this invoice. Please make payments to the account details provided below.</p>
        
        <h5><strong>Bank Details:</strong></h5>
        <ul class="bank-details">
            <li><strong>Account Name:</strong> Dheeraj Singh</li>
            <li><strong>Account Number:</strong> 5555 0104 0537 21</li>
            <li><strong>Bank Name:</strong> Epifi Federal Neo Banking</li>
            <li><strong>IFSC Code:</strong> EFBB0018</li>
            <li><strong>UPI Number:</strong> 9032666855 (Accepts all UPI payments)</li>
        </ul>
    </div>


    <div class="text-md-right mt-4 no-print">
        <a href="javascript:window.print();" class="btn btn-warning btn-icon icon-left">
            <i class="fas fa-print"></i> Print
        </a>
    </div>
</div>

<style>
    @media print {
        body {
            margin: 0;
            padding: 0;
            font-size: 12px; /* Adjust font size for print */
        }
        .invoice {
            width: 100%;
            margin: 0;
            padding: 0;
            page-break-inside: avoid; /* Prevent page break inside the invoice */
        }
        .row {
            margin: 0; /* Remove row margin */
        }
        .no-print {
            display: none; /* Hide non-print elements */
        }
        /* Add borders to the tables only in print */
        .table {
            border: 1px solid #dee2e6; /* Add border to the table */
        }
        .table td, .table th {
            border: 1px solid #dee2e6; /* Add border to table cells */
        }
    }

    .invoice {
        margin: 20px; /* Add margins for better spacing */
        padding: 15px; /* Provide some inner padding for the invoice */
        border: 1px solid #dee2e6; /* Optional: Add border around the invoice */
        border-radius: 5px; /* Optional: Rounded corners for the invoice */
        background-color: #ffffff; /* Optional: White background for contrast */
    }
    .invoice-header {
        margin-bottom: 15px; /* Add space below header */
    }
    .company-details {
        display: flex; /* Enable flex layout */
        align-items: center; /* Align items vertically */
        margin-top: 10px; /* Optional: Add margin for better spacing */
    }
    .invoice-logo {
        margin-right: 10px; /* Space between the logo and text */
    }
    .thead-dark th {
        background-color: #343a40;
        color: white; /* Ensure the header text is white for visibility */
    }
    .advertisement-section {
        background-color: #f8f9fa; /* Light grey background for contrast */
        color: #333;
        font-size: 14px;
    }
</style>















<?php
}
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Error fetching invoice: ' . $e->getMessage() . '</div>';
}
} else {
    // Display the form to create a new invoice
    ?>

    <form method="POST">
        <div class="form-group">
            <label for="client_id">Select Client</label>
            <select name="client_id" id="client_id" class="form-control">
                <option value="">Select a client</option>
                <?php
                // Fetch clients from the database
                $stmt = $pdo->query("SELECT id, name FROM clients");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="invoice_date">Invoice Date</label>
            <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" name="item_name" id="item_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="item_price">Item Price</label>
            <input type="number" name="item_price" id="item_price" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="item_quantity">Item Quantity</label>
            <input type="number" name="item_quantity" id="item_quantity" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tax">Tax Amount</label>
            <input type="number" name="tax" id="tax" class="form-control" step="0.01">
        </div>

        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="payment_instructions">Payment Instructions</label>
            <textarea name="payment_instructions" id="payment_instructions" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="terms_conditions">Terms and Conditions</label>
            <textarea name="terms_conditions" id="terms_conditions" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Create Invoice</button>
        </div>
    </form>

<?php
}
?>

</div>
</section>
</div>

<?php include 'layouts/footer.php'; ?>
