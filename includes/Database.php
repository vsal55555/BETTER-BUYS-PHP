<?php

define("servername", "localhost");
define("dbUsername", "root");
define("dbPassword", "0mnitr1x");
define("dbName", "better_buys");

class Database {

    private $connection;

    public function __construct() {
        $this->open_db_connection();
    }

    public function open_db_connection() {
     $this->connection = mysqli_connect(servername, dbUsername, dbPassword, dbName); 
    
    if(mysqli_connect_error()){
        die("Connection Failed : ".mysqli_connect_error());
    } 
    }
    //Excuting sql Query
    public function query($sql) {
        $result = $this->connection->query($sql);

        if (!$result) {
            die('Query Fails'. $sql);
        }
        return $result;
    }
    //fetching list of data from the SQL Query results
    public function fetch_array($result) {
        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row; //create empty array and add each row
            }
            return $result_array;
        }
    }

    //fetching single row of data from the sql query
    // Getting only 1 row
    public function fetch_row($result) {
     if($result->num_rows > 0) {
         return $result->fetch_assoc();
     }   
    }
    //check proper format of data
    public function escape_value($value) {
        return $this->connection->real_escape_string($value);
    }
    //closes the connection with sql
    public function close_connection() {
        $this->connection->close();
    }
}

$database = new Database();