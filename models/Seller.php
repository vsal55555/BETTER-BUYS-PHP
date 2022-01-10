<?php


$ds = DIRECTORY_SEPARATOR; //gives us special char that is used to separate between directory
$base_dir = realpath(dirname(__FILE__). $ds . '..') . $ds; //return directory of BEtter buys

require_once("{$base_dir}includes{$ds}Database.php");//Including database, It is same as require, but only one time it includes the external file.
require_once("{$base_dir}includes{$ds}Bcrypt.php");//including Bcrypt

class Seller {
    private $table = 'sellers';
    
    public $id;
    public $name;
    public $email;
    public $password;
    public $image;
    public $address;
    public $description;

    //validating if param exists or not
    public function __construct() { //creating constructor is complusary in 5.8

    }

    public function validate_param($value) {
        if(!empty($value)) return true;
        else return false;
        //return(!empty($value));
    }

    //to check if email is unique or not
    public function check_unique_email() {
        global $database;
        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $sql = "SELECT id FROM $this->table WHERE email = '" .$database->escape_value($this->email). "'";
        $result = $database->query($sql);
        $user_id = $database->fetch_row($result);

        return empty($user_id);

    } 

    //saving new data to database
    public function register_seller() {
        global $database;
        //trims removes white spaces
        //refining all our data members
        $this->name = trim(htmlspecialchars(strip_tags($this->name)));//htmlspecialchars converts <>/ into html entities
        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $this->password = trim(htmlspecialchars(strip_tags($this->password)));
        $this->image = trim(htmlspecialchars(strip_tags($this->image)));
        $this->address = trim(htmlspecialchars(strip_tags($this->address)));
        $this->description = trim(htmlspecialchars(strip_tags($this->description)));

        //preparing queries
        $sql = "INSERT INTO $this->table (name, email, password, image, address, description) VALUES (
            '" .$database->escape_value($this->name). "',
            '" .$database->escape_value($this->email). "',
            '" .$database->escape_value(Bcrypt::hashPassword($this->password)). "',
            '" .$database->escape_value($this->image). "',
            '" .$database->escape_value($this->address). "',
            '" .$database->escape_value($this->description). "'
        )";
        //executing queries
        $seller_saved = $database->query($sql);
        if($seller_saved) return true;//$database->last_insert_id();
        else false;
    }
    public function login() {
        global $database;
        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $this->password = trim(htmlspecialchars(strip_tags($this->password)));
        
        $sql = "SELECT * FROM $this->table WHERE email = '" .$database->escape_value($this->email). "'";
        $result = $database->query($sql);
        $seller = $database->fetch_row($result);//fetches first row of the result

        if(empty($seller)) return "Seller doesnot exists"; //return string 
        else {
            if(Bcrypt::checkPassword($this->password, $seller['password'])) {
                unset($seller['password']);
                return $seller; //return this is array
            } else {
                return "Password doen't match";
            }
        }
    }

    //method to return the list of seller
    public function all_sellers() {
        global $database;

        $sql = "SELECT id, name, image, address FROM $this->table";
        $result = $database->query($sql);

        return $database->fetch_array($result);//fetch all column and return as array
    }
}
$seller = new Seller();

