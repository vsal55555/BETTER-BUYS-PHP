<?php

require_once "../../vendor/autoload.php";

header('Access-Control-Allow-Origin: *'); //controls what will be sent to client header('Access-Control-Allow-Origin: localhost:8080') only local can sent request
header('Content-type: application/json'); // content of response will be json
header('Access-Control-Allow-Method: GET'); //which type of request method will allow
header('Access-Control-Allow-Headers: Origin, Content-type, Accept'); //used to handle pre flight request

include_once '../../models/Seller.php';
$ds = DIRECTORY_SEPARATOR; //gives us special char that is used to separate between directory
$base_dir = realpath(dirname(__FILE__). $ds . '../..') . $ds; //return directory of BEtter buys

require_once("{$base_dir}includes{$ds}Database.php");
$token = "";

if($_SERVER['REQUEST_METHOD'] === 'GET') {
//get all the headers
foreach (getallheaders() as $name => $value) {
   // echo "$name: $value\n";
    if($name == "Authorization") {
        $token = $value;
    }
    
}
if (!empty($token)) {
    global $database;
    $table = 'api_token';
    $sql = "SELECT * FROM $table WHERE token = '". substr($token, 7) ."'"; //remove Bearer with space
    $result = $database->query($sql);
    if (mysqli_num_rows($result) > 0) {
       // echo "Token Avaiable";
        $checkRow = $database->fetch_row($result);
        if($checkRow['status'] == 1) {
            if($checkRow['hit_limit'] > $checkRow['hit_count']) {
            $sql = "UPDATE $table SET hit_count=hit_count+1 WHERE token = '". substr($token, 7) ."'";
            $result = $database->query($sql);
            echo(header('HTTP/1.1 200 OK'));
            echo json_encode(array('success' => 1, 'data' => "Found key in table"));
            } else {
            http_response_code(401);
            echo json_encode(array('success' => 0, 'data' => "API limit exceeded"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array('success' => 0, 'data' => "Token is expired"));
        }
    } else {
        http_response_code(401);
        echo json_encode(array('success' => 0, 'data' => "Invalid Token"));
    }
} else {
    http_response_code(401);
    echo json_encode(array('success' => 0, 'data' => "Access Denied"));
}

} else {
    die(header('HTTP/1.1 405 Request Method Not Allowed'));//stops the excuetion of the flow
} 