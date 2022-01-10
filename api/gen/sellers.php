<?php

require_once "../../vendor/autoload.php";

header('Access-Control-Allow-Origin: *'); //controls what will be sent to client header('Access-Control-Allow-Origin: localhost:8080') only local can sent request
header('Content-type: application/json'); // content of response will be json
header('Access-Control-Allow-Method: GET'); //which type of request method will allow
header('Access-Control-Allow-Headers: Origin, Content-type, Accept'); //used to handle pre flight request

include_once '../../models/Seller.php';
//require 'Database.php';


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(array('success' => 1, 'sellers' => $seller->all_sellers()));
} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));//stops the excuetion of the flow
} 