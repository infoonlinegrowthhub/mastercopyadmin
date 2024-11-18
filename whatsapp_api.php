<?php
// Include the database configuration
include 'config/config.php';

// Handle the request
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch the WhatsApp number
        $stmt = $pdo->prepare("SELECT * FROM whatsapp_settings WHERE id = 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
        break;

    case 'POST':
        // Update the WhatsApp number
        $data = json_decode(file_get_contents("php://input"), true);
        $whatsapp_number = $data['whatsapp_number'];

        $stmt = $pdo->prepare("UPDATE whatsapp_settings SET whatsapp_number = :whatsapp_number WHERE id = 1");
        $stmt->execute(['whatsapp_number' => $whatsapp_number]);

        echo json_encode(["message" => "WhatsApp number updated successfully."]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
?>
