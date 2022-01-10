<?php

require_once "../../vendor/autoload.php";

header('Access-Control-Allow-Origin: *'); //controls what will be sent to client header('Access-Control-Allow-Origin: localhost:8080') only local can sent request
header('Content-type: application/json'); // content of response will be json
header('Access-Control-Allow-Method: POST'); //which type of request method will allow
header('Access-Control-Allow-Headers: Origin, Content-type, Accept'); //used to handle pre flight request

include_once '../../models/Seller.php';
//require 'Database.php';


if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if($seller->validate_param($_POST['name'])) {
        $seller->name = $_POST['name'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'Name is required'));
        die();
    }

    if($seller->validate_param($_POST['email'])) {
        $seller->email = $_POST['email'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'Email is required'));
        die();
    }

    if($seller->validate_param($_POST['password'])) {
        $seller->password = $_POST['password'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'Password is required'));
        die();
    }
    //saving image of seller
    $seller_images_folder = '../../assets/seller_images/';

    if(!is_dir($seller_images_folder)) {
        mkdir($seller_images_folder);
    }
    if(isset($_FILES['images'])) {
       $file_name = $_FILES['image']['name']; //saving the images
       $file_tmp = $_FILES['image']['tmp_name'];
       $extension = end(explode('.', $file_name));

       $new_file_name = $seller->email . "_profile" . "." . $extension;
       move_uploaded_file($file_tmp, $seller_images_folder . "/" . $new_file_name);

       $seller->image = 'seller_images/' . $new_file_name; //address and save to db
    }

    if($seller->validate_param($_POST['address'])) {
        $seller->address = $_POST['address'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'address is required'));
        die();
    }

    if($seller->validate_param($_POST['description'])) {
        $seller->description = $_POST['description'];
    } else {
        echo json_encode(array('success' => 0, 'message' => 'Description is required'));
        die();
    }

    if ($seller->check_unique_email()){
        if($id = $seller->register_seller()) {
            echo json_encode(array('success' => 1, 'message' => 'Seller Registered'));
        } else {
            http_response_code(500);
            echo json_encode(array('success' => 0, 'message' => 'Internal Server Error')); 
        }
    } else {
        http_response_code(401);
        echo json_encode(array('success' => 0, 'message' => 'Email Already Registered'));
    }

} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));//stops the excuetion of the flow
} 

