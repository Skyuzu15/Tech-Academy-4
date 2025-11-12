<?php
class Customer {
    private $conn;
    private $table = 'customers';

    public $id;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $zipcode;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE (Registro)
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, email, password, phone, address, city, state, zipcode) 
                  VALUES (:name, :email, :password, :phone, :address, :city, :state, :zipcode)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash da senha
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':zipcode', $this->zipcode);
        
        return $stmt->execute();
    }

    // READ ALL
    public function readAll() {
        $query = "SELECT id, name, email, phone, city, state, created_at 
                  FROM " . $this->table . " 
                  WHERE active = 1 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->address = $row['address'];
            $this->city = $row['city'];
            $this->state = $row['state'];
            $this->zipcode = $row['zipcode'];
            $this->active = $row['active'];
        }
        
        return $row;
    }

    // UPDATE
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, 
                      email = :email,
                      phone = :phone,
                      address = :address,
                      city = :city,
                      state = :state,
                      zipcode = :zipcode
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':zipcode', $this->zipcode);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // DELETE (Soft Delete)
    public function delete() {
        $query = "UPDATE " . $this->table . " 
                  SET active = 0 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // LOGIN
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE email = :email AND active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($password, $row['password'])) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            return true;
        }
        
        return false;
    }

    // Verificar se email existe
    public function emailExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}