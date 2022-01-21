<?php

require_once "../../vendor/autoload.php";

header('Access-Control-Allow-Origin: *'); //controls what will be sent to client header('Access-Control-Allow-Origin: localhost:8080') only local can sent request
header('Content-type: application/json'); // content of response will be json
header('Access-Control-Allow-Method: POST'); //which type of request method will allow
header('Access-Control-Allow-Headers: Origin, Content-type, Accept'); //used to handle pre flight request

include_once '../../models/Seller.php';
//require 'Database.php';


if($_SERVER['REQUEST_METHOD'] === 'POST') {

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
//reach only when request has both email and password
    $s = $seller->login();
    if(gettype($s) === 'array') {
    http_response_code(200);
    $character = 'eyJhbGciOiJIUzUxMiJ9.eyJqdGkiOiJVbmxpbWl0SldUIiwic3ViIjoiZS1kaGViYUB1bmxpb';//$token = bin2hex(random_bytes(16)); 
    echo json_encode(array('success' => 0, 'message' => 'Login Successful', 'seller' => $s,'token' => str_shuffle($character)));
    } else {
    http_response_code(401);
    echo json_encode(array('success' => 0, 'message' => 'Login UnSuccessful'));
    }

} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));//stops the excuetion of the flow
} 