<?php

require_once "../../vendor/autoload.php";

header('Access-Control-Allow-Origin: *'); //controls what will be sent to client header('Access-Control-Allow-Origin: localhost:8080') only local can sent request
header('Content-type: application/json'); // content of response will be json
header('Access-Control-Allow-Method: GET'); //which type of request method will allow
header('Access-Control-Allow-Headers: Origin, Content-type, Accept'); //used to handle pre flight request

include_once '../../models/Product.php';
//require 'Database.php';


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if($product->validate_param($_GET['seller_id'])) {
        $product->seller_id = $_GET['seller_id'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'Seller_id is required'));
        die();
    }
    //reach here is success
    echo json_encode(array('success' => 1, 'products' => $product->get_products_per_seller()));
} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));//stops the excuetion of the flow
} 