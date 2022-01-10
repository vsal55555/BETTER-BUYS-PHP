<?php

require_once "../../vendor/autoload.php";

header('Access-Control-Allow-Origin: *'); //controls what will be sent to client header('Access-Control-Allow-Origin: localhost:8080') only local can sent request
header('Content-type: application/json'); // content of response will be json
header('Access-Control-Allow-Method: POST'); //which type of request method will allow
header('Access-Control-Allow-Headers: Origin, Content-type, Accept'); //used to handle pre flight request

include_once '../../models/Product.php';
//require 'Database.php';


if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if($product->validate_param($_POST['seller_id'])) {
        $product->seller_id = $_POST['seller_id'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'Seller ID is required'));
        die();
    }

    if($product->validate_param($_POST['name'])) {
        $product->name = $_POST['name'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'name is required'));
        die();
    }

    // saving picture of product
    $product_images_folder = '../../assets/product_images/';

    if (!is_dir($product_images_folder)) {
        mkdir($product_images_folder, 0777);
    }

    if (isset($_FILES['image'])) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $tmp = explode('.', $file_name);
        $extension = end($tmp);
        
        $new_file_name = $product->seller_id . "_product_" . $product->name . "." . $extension;

        move_uploaded_file($file_tmp, $product_images_folder . "/" . $new_file_name);

        $product->image = 'product_images/' . $new_file_name;
    } else {
        echo json_encode(array('success' => 0, 'message' => 'Photo is required is required!'));
        die();
    }


    if($product->validate_param($_POST['price_per_kg'])) {
        $product->price_per_kg = $_POST['price_per_kg'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'price_per_kg is required'));
        die();
    }

    if($product->validate_param($_POST['description'])) {
        $product->description = $_POST['description'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'description is required'));
        die();
    }

    if ($product->add_product()) {
        echo json_encode(array('success' => 1, 'message' => 'Product successfully added'));
    } else {
        http_response_code(500);
        echo json_encode(array('success' => 0, 'message' => 'Internal Server Error'));
    }
} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));//stops the excuetion of the flow
} 