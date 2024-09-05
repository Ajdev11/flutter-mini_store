<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');

include_once('../../models/Sellers.php');

$Seller = new Seller(); // Initialize the $Seller object

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && $Seller->validate_params($_POST['email'])) {
        $Seller->email = $_POST['email'];
    } else {
        echo json_encode(['success' => 0, 'message' => 'Email is required']);
        die();
    }

    if (isset($_POST['password']) && $Seller->validate_params($_POST['password'])) {
        $Seller->password = $_POST['password'];
    } else {
        echo json_encode(['success' => 0, 'message' => 'Password is required']);
        die();
    }

    $s = $Seller->login();
    if (is_array($s)) {
        http_response_code(200);
        echo json_encode(['success' => 1, 'message' => 'Login successful!!', 'seller' => $s]);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(['success' => 0, 'message' => $s]);
    }
} else {
    die(header('HTTP/1.1 405 Method Not Allowed'));
}
