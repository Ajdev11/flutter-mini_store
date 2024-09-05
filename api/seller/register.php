<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');

include_once('../../models/Sellers.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Seller = new Seller(); // Ensure this is initialized

    if (isset($_POST['name']) && $Seller->validate_params($_POST['name'])) {
        $Seller->name = $_POST['name'];
    } else {
        echo json_encode(['success' => 0, 'message' => 'Name is required']);
        die();
    }

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

    // File upload handling
    $seller_images_folder = '../../asset/seller_image/';
    if (!is_dir($seller_images_folder)) {
        mkdir($seller_images_folder);
    }
    if (isset($_FILES['image'])) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name']; // Corrected this line
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        // $new_file_name = $Seller->email . "_profile." . $extension;
        $new_file_name = $Seller->email . "_profile_" . "." . $extension;
        move_uploaded_file($file_tmp, $seller_images_folder . "/" . $new_file_name);
        $seller_images_folder = 'seller_image/' . $new_file_name;
    }


    if (isset($_POST['address']) && $Seller->validate_params($_POST['address'])) {
        $Seller->address = $_POST['address'];
    } else {
        echo json_encode(['success' => 0, 'message' => 'Address is required']);
        die();
    }

    if (isset($_POST['description']) && $Seller->validate_params($_POST['description'])) {
        $Seller->description = $_POST['description'];
    } else {
        echo json_encode(['success' => 0, 'message' => 'Description is required']);
        die();
    }

// checking mails here
    if($Seller->check_unique_email()){
        if ($id = $Seller->register_seller()) {
            echo json_encode(['success' => 1, 'message' => 'Seller Registered']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => 0, 'message' => 'Internal Server Error']);
        }
    }else{
        http_response_code(401);
        echo json_encode(['success' => 0, 'message' => 'Email already Exist']);
    }
    
} else {
    die(header('HTTP/1.1 405 Method Not Allowed'));
}
