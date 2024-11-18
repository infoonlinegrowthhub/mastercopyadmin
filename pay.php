<?php
session_start();
require 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['mobile'], $_POST['cust_name'], $_POST['email'])) {
    // Sanitize inputs
    $product_id = intval($_POST['product_id']);
    
    // Fetch product details from the database
    $stmt = $pdo->prepare("SELECT id, product_name, price FROM products WHERE id = :id AND status = 1");
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "<h5>Product not found or inactive.</h5>";
        exit;
    }

    // Generate unique identifiers
    $orderId = 'ORD_' . uniqid();
    $orderAmount = number_format($product['price'], 2, '.', '');
    $customer_id = uniqid();
    $customer_name = htmlspecialchars($_POST['cust_name']);
    $customer_email = htmlspecialchars($_POST['email']);
    $customer_phone = htmlspecialchars($_POST['mobile']);

    // Initialize cURL for the API request to Cashfree
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            "order_id" => $orderId,
            "order_amount" => $orderAmount,
            "order_currency" => CURRENCY,
            "customer_details" => array(
                "customer_id" => $customer_id,
                "customer_name" => $customer_name,
                "customer_email" => $customer_email,
                "customer_phone" => $customer_phone
            ),
            "order_meta" => array(
                "return_url" => BASE_URL . "success.php?order_id={$orderId}",
                "notify_url" => BASE_URL . "callback.php",
                "payment_methods" => "cc,dc,upi"
            )
        )),
        CURLOPT_HTTPHEADER => array(
            'X-Client-Secret: ' . SECRET_KEY,
            'X-Client-Id: ' . CLIENT_ID,
            'Content-Type: application/json',
            'Accept: application/json',
            'x-api-version: 2023-08-01'
        ),
    ));

    $response = curl_exec($curl);
    if ($response === false) {
        error_log('cURL Error: ' . curl_error($curl));
        echo "<h5>Error occurred while processing your payment. Please try again later.</h5>";
        exit;
    }
    curl_close($curl);

    $resData = json_decode($response);
    if (isset($resData->cf_order_id) && !empty($resData->cf_order_id)) {
        $cf_order_id = $resData->cf_order_id;
        $paymentSessionId = $resData->payment_session_id;

        // Insert payment details into the database
        $stmt = $pdo->prepare("INSERT INTO cashfree_payment (product_id, order_id, order_amount, customer_id, customer_name, customer_email, customer_phone, cf_order_id, payment_session_id, payment_status) VALUES (:product_id, :order_id, :order_amount, :customer_id, :customer_name, :customer_email, :customer_phone, :cf_order_id, :payment_session_id, 'pending')");
        $stmt->execute([
            ':product_id' => $product['id'],
            ':order_id' => $orderId,
            ':order_amount' => $orderAmount,
            ':customer_id' => $customer_id,
            ':customer_name' => $customer_name,
            ':customer_email' => $customer_email,
            ':customer_phone' => $customer_phone,
            ':cf_order_id' => $cf_order_id,
            ':payment_session_id' => $paymentSessionId
        ]);
    } else {
        echo "<h5>Payment request failed: " . htmlspecialchars($response) . "</h5>";
        exit;
    }

    // Include header and navbar
    include 'header.php';
    include 'navbar.php';
    ?>

    <div class="container my-5">
        <div class="infoBox">
            <h5>Confirm Your Details</h5>
            <table class="table">
                <tr><td><strong>Name</strong></td><td><?php echo htmlspecialchars($customer_name); ?></td></tr>
                <tr><td><strong>Email</strong></td><td><?php echo htmlspecialchars($customer_email); ?></td></tr>
                <tr><td><strong>Mobile No.</strong></td><td><?php echo htmlspecialchars($customer_phone); ?></td></tr>
                <tr><td><strong>Pay Amount</strong></td><td style="color:green;font-weight:bold;font-size:18px;"><?php echo "Rs. " . number_format($orderAmount, 2); ?></td></tr>
            </table>
            <div class="text-center">
                <button type="button" id="renderBtn" class="btn btn-success mt-3">Confirm & Pay</button>
            </div>
        </div>
    </div>

    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <script>
        const cashfree = Cashfree({
            mode: "<?php echo $payMode; ?>" // Update $payMode based on environment
        });

        document.getElementById("renderBtn").addEventListener("click", () => {
            cashfree.checkout({
                paymentSessionId: "<?php echo $paymentSessionId; ?>"
            });
        });
    </script>

    <?php include 'footer.php'; ?>

<?php
} else {
    echo "<h5>Invalid request</h5>"; 
}
?>
