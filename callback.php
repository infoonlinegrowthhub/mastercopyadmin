<?php
include 'config/config.php';

// Log the webhook request to a file for debugging
file_put_contents('webhook.log', file_get_contents('php://input') . "\n", FILE_APPEND);

// Read the incoming POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Ensure PDO connection is established
try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    file_put_contents('data.log', "Database connection error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
    exit;
}

if ($data) {
    // Extract order ID, payment status, and other necessary details
    $order_id = $data['data']['order']['order_id'] ?? null;
    $payment_time = $data['data']['payment']['payment_time'] ?? null;
    $payment_status = $data['data']['payment']['payment_status'] ?? null;

    // Check if order details are present
    $order_amount = $data['data']['order']['amount'] ?? null;
    $customer_id = $data['data']['order']['customer_id'] ?? null;
    $customer_name = $data['data']['order']['customer']['name'] ?? null;
    $customer_email = $data['data']['order']['customer']['email'] ?? null;
    $customer_phone = $data['data']['order']['customer']['phone'] ?? null;
    $cf_order_id = $data['data']['payment']['cf_order_id'] ?? null;
    $payment_session_id = $data['data']['payment']['payment_session_id'] ?? null;

    // Log missing order ID
    if (!$order_id) {
        file_put_contents('data.log', "Missing order ID in webhook data.\n", FILE_APPEND);
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing order ID']);
        exit;
    }

    // Prepare the database update query only if the payment is successful
    if ($payment_status === 'SUCCESS') {
        $stmt = $pdo->prepare("
            UPDATE cashfree_payment 
            SET 
                payment_status = :payment_status, 
                payment_time = :payment_time,
                order_amount = :order_amount,
                customer_id = :customer_id,
                customer_name = :customer_name,
                customer_email = :customer_email,
                customer_phone = :customer_phone,
                cf_order_id = :cf_order_id,
                payment_session_id = :payment_session_id
            WHERE 
                order_id = :order_id
        ");

        // Bind parameters to the prepared statement
        $stmt->bindParam(':payment_status', $payment_status);
        $stmt->bindParam(':payment_time', $payment_time);
        $stmt->bindParam(':order_amount', $order_amount);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':customer_email', $customer_email);
        $stmt->bindParam(':customer_phone', $customer_phone);
        $stmt->bindParam(':cf_order_id', $cf_order_id);
        $stmt->bindParam(':payment_session_id', $payment_session_id);
        $stmt->bindParam(':order_id', $order_id);

        // Execute the update query
        if ($stmt->execute()) {
            // Log successful update
            file_put_contents('data.log', "Updated order ID: $order_id with status: $payment_status\n", FILE_APPEND);
            http_response_code(200);
            echo json_encode(['status' => 'success']);
        } else {
            // Log the failure to update
            file_put_contents('data.log', "Failed to update order ID: $order_id. Error: " . implode(", ", $stmt->errorInfo()) . "\n", FILE_APPEND);
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update payment status']);
        }
    } else {
        // Log if payment is not successful
        file_put_contents('data.log', "Payment not successful for order ID: $order_id with status: $payment_status\n", FILE_APPEND);
        http_response_code(200); // Respond with success, but payment was not successful
        echo json_encode(['status' => 'error', 'message' => 'Payment not successful']);
    }
} else {
    // Invalid data
    http_response_code(400);
    echo json_encode(['status' => 'invalid data']);
}
?>
