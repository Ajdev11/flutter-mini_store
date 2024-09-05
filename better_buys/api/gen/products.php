<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');

include_once('../../models/Product.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['seller_id']) && !empty(trim($_GET['seller_id']))) {
        $product->seller_id = trim($_GET['seller_id']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Seller ID is required']);
        die();
    }
    echo json_encode(array('success' => 1, 'products' => $product->get_product_per_seller()));
} else {
    die(header('HTTP/1.1 405 Method Not Allowed'));
}
