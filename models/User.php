<?php
class User {
    private $conn;
    private $table = 'admin_users';

    public $id;
    public $username;
    public $password;
    public $email;
    public $full_name;
    public $role;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (username, password, email, full_name, role) 
                  VALUES (:username, :password, :email, :full_name, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':role', $this->role);
        
        return $stmt->execute();
    }

    // LOGIN
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE username = :username AND active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($password, $row['password'])) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->email = $row['email'];
            $this->full_name = $row['full_name'];
            $this->role = $row['role'];
            
            // Atualizar último login
            $this->updateLastLogin();
            
            return true;
        }
        
        return false;
    }

    // Atualizar último login
    private function updateLastLogin() {
        $query = "UPDATE " . $this->table . " 
                  SET last_login = NOW() 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    // Verificar se username existe
    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}