<?php


$ds = DIRECTORY_SEPARATOR; //gives us special char that is used to separate between directory
$base_dir = realpath(dirname(__FILE__). $ds . '..') . $ds; //return directory of BEtter buys

require_once("{$base_dir}includes{$ds}Database.php");//Including database, It is same as require, but only one time it includes the external file.

class Product  {
    public $table = 'products';
    public $seller_id;
    public $name;
    public $image;
    public $price_per_kg;
    public $description;
    public $interaction_count;

    //validating if param exists or not
    public function __construct() { //creating constructor is complusary in 5.8

    }

    public function validate_param($value) {
        if(!empty($value)) return true;
        else return false;
    //return(!empty($value));
    }
    //storing product details
    public function add_product() {
        global $database;

        $this->seller_id = trim(htmlspecialchars(strip_tags($this->seller_id)));
        $this->name = trim(htmlspecialchars(strip_tags($this->name)));
        $this->image = trim(htmlspecialchars(strip_tags($this->image)));
        $this->price_per_kg = trim(htmlspecialchars(strip_tags($this->price_per_kg)));
        $this->description = trim(htmlspecialchars(strip_tags($this->description)));
        
        $sql = "INSERT INTO $this->table (seller_id, name, image, price_per_kg, description) VALUES (
            '" .$database->escape_value($this->seller_id). "',
            '" .$database->escape_value($this->name). "',
            '" .$database->escape_value($this->image). "',
            '" .$database->escape_value($this->price_per_kg). "',
            '" .$database->escape_value($this->description). "'
            )";

        $result = $database->query($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

      // method to return the list of products per seller
      public function get_products_per_seller()
      {
          global $database;
          
          $this->seller_id = trim(htmlspecialchars(strip_tags($this->seller_id)));
  
          $sql = "SELECT * FROM $this->table WHERE seller_id = '" .$database->escape_value($this->seller_id). "'";
  
          $result = $database->query($sql);
  
          return $database->fetch_array($result);
      }
       
}
$product = new Product();