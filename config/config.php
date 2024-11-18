<?php
// Database configuration settings
$dbhost = 'localhost';
$dbname = 'mastercopyadmin';
$dbuser = 'root';
$dbpass = '';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    error_log("Connection error: " . $exception->getMessage());
    echo "We are experiencing technical difficulties. Please try again later.";
    exit;
}

// Define base URL constants if not already defined
if (!defined("BASE_URL")) {
    define("BASE_URL", "http://localhost/mastercopyadmin/");
}

if (!defined("ADMIN_URL")) {
    define("ADMIN_URL", BASE_URL . "admin/");
}

// SMTP configuration constants
if (!defined("SMTP_HOST")) {
    define("SMTP_HOST", "smtp.hostinger.com");
}

if (!defined("SMTP_PORT")) {
    define("SMTP_PORT", 465);
}

if (!defined("SMTP_USERNAME")) {
    define("SMTP_USERNAME", "info@onlinegrowthhub.in");
}

if (!defined("SMTP_PASSWORD")) {
    define("SMTP_PASSWORD", "DDsingh@1999");
}

if (!defined("SMTP_ENCRYPTION")) {
    define("SMTP_ENCRYPTION", "ssl");
}

if (!defined("SMTP_FROM")) {
    define("SMTP_FROM", "info@onlinegrowthhub.in");
}

// Check if PDO and PDO MySQL extensions are enabled
if (!extension_loaded('pdo') || !extension_loaded('pdo_mysql')) {
    die("PDO or PDO MySQL extension is not enabled. Please enable it in your php.ini file.");
}

// Include Cashfree configuration
include_once 'cashfree-config.php';
?>
