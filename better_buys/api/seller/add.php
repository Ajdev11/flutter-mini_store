<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');

include_once('../../models/Product.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = new Product();

    if (isset($_POST['seller_id']) && !empty(trim($_POST['seller_id']))) {
        $product->seller_id = trim($_POST['seller_id']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Seller ID is required']);
        die();
    }

    if (isset($_POST['name']) && !empty(trim($_POST['name']))) {
        $product->name = trim($_POST['name']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Name is required']);
        die();
    }

    // saving picture of products
    $product_images_folder = '../../asset/product_image/';
    if (!is_dir($product_images_folder)) {
        mkdir($product_images_folder);
    }
    if (isset($_FILES['image'])) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name']; // Corrected this line
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = $product->seller_id . "_product_." . "." . $product->name . $extension;
        move_uploaded_file($file_tmp, $product_images_folder . "/" . $new_file_name);
        $product_images_folder = 'product_image/' . $new_file_name;
    } else {
        echo json_encode(['success' => 0, 'message' => 'Photo is required']);
        die();
    }




    if (isset($_POST['price_per_kg']) && !empty(trim($_POST['price_per_kg']))) {
        $product->price_per_kg = trim($_POST['price_per_kg']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Price per KG is required']);
        die();
    }


    if (isset($_POST['description']) && !empty(trim($_POST['description']))) {
        $product->description = trim($_POST['description']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Description is required']);
        die();
    }

    // checking mails here

    if ($product->add_products()) {
        echo json_encode(['success' => 1, 'message' => 'Product is saved']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => 0, 'message' => 'Internal Server Error']);
    }
} else {
    die(header('HTTP/1.1 405 Method Not Allowed'));
}
