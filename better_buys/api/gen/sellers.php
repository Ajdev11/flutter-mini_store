<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept');

include_once('../../models/Sellers.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(array('success'=>1, 'seller'=>$Seller->api_sellers()));
} else {
    die(header('HTTP/1.1 405 Method Not Allowed'));
}
