<?php

$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__) . $ds . '..' . $ds);

require_once($_SERVER['DOCUMENT_ROOT'] . '/better_buys/includes/Database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/better_buys/includes/Bcrypt.php');

class Seller
{
    private $table = 'sellers';
    public $id;
    public $name;
    public $email;
    public $password;
    public $image;
    public $address;
    public $description;

    public function __construct()
    {
        // Constructor logic (if any) can go here
    }

    public function validate_params($value)
    {
        return !empty($value);
    }

    // Check if the email is unique
    public function check_unique_email()
    {
        global $database;
        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $sql = "SELECT id FROM $this->table WHERE email = '" . $database->escape_value($this->email) . "'";
        $result = $database->query($sql);
        $user_id = $database->fetch_row($result);
        return empty($user_id);
    }

    public function register_seller()
    {
        global $database;

        // Sanitize properties
        $this->id = trim(htmlspecialchars(strip_tags($this->id)));
        $this->name = trim(htmlspecialchars(strip_tags($this->name)));
        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $this->password = trim(htmlspecialchars(strip_tags($this->password)));
        $this->image = trim(htmlspecialchars(strip_tags($this->image)));
        $this->address = trim(htmlspecialchars(strip_tags($this->address)));
        $this->description = trim(htmlspecialchars(strip_tags($this->description)));

        // Create SQL query
        $sql = "INSERT INTO $this->table (name, email, password, image, address, description) VALUES (
            '" . $database->escape_value($this->name) . "',
            '" . $database->escape_value($this->email) . "',
            '" . $database->escape_value(Bcrypt::hashPassword($this->password)) . "',
            '" . $database->escape_value($this->image) . "',
            '" . $database->escape_value($this->address) . "',
            '" . $database->escape_value($this->description) . "'
        )";

        // Execute query and return result
        $seller_saved = $database->query($sql);
        if ($seller_saved) {
            return $database->connection->insert_id;
        } else {
            return false;
        }
    }

    // Login function
    public function login()
    {
        global $database;
        $this->email = trim(htmlspecialchars(strip_tags($this->email)));
        $this->password = trim(htmlspecialchars(strip_tags($this->password)));
        $sql = "SELECT * FROM $this->table WHERE email = '" . $database->escape_value($this->email) . "'";
        $result = $database->query($sql);
        $seller = $database->fetch_row($result);
        if (empty($seller)) {
            return "Seller doesn't exist";
        } else {
            if (Bcrypt::checkPassword($this->password, $seller['password'])) {
                unset($seller['password']);
                return $seller;
            } else {
                return "Password doesn't match";
            }
        }
    }
    // meh=thods to fetch sellers api
    public function api_sellers(){
         global $database;

         $sql = " SELECT id, name, image, address FROM $this->table";
         $result = $database->query($sql);
         return $database->fetch_array($result);
    }
}

$Seller = new Seller();
