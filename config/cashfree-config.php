<?php
require 'config.php'; // Include your database configuration

// Set the payment mode ('test' for sandbox, 'production' for live)
$payMode = 'production'; // Change to 'test' for sandbox mode

// Fetch configuration from the database based on the environment
try {
    $sql = "SELECT * FROM cashfree_config WHERE environment = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$payMode]);
    $config = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($config) {
        // Define Cashfree configuration based on the fetched settings
        define('CLIENT_ID', $config['app_id']);
        define('SECRET_KEY', $config['secret_key']);
        define('API_URL', $payMode === 'production' ? 'https://api.cashfree.com/pg/orders' : 'https://sandbox.cashfree.com/pg/orders');
        define('CURRENCY', 'INR'); // Define the currency constant
    } else {
        throw new Exception("Configuration not found for the selected environment.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
