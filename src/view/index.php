<?php
require_once "../../vendor/autoload.php";

header("Access-Control-Allow-Origin: *"); //request any
header("Content-Type: application/json"); //sending json
header("Accept: application/json"); //accepting json

$method = $_SERVER['REQUEST_METHOD']; //stores which type of method carries request

$response = array(
    'status' => $method
);
echo json_encode($response);
?>