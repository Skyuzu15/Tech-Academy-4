<?php
class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $name;
    public $description;
    public $image_url;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, description, image_url) 
                  VALUES (:name, :description, :image)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image_url);
        
        return $stmt->execute();
    }

    // READ ALL
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE active = 1 
                  ORDER BY name ASC";
        
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
            $this->description = $row['description'];
            $this->image_url = $row['image_url'];
            $this->active = $row['active'];
        }
        
        return $row;
    }

    // UPDATE
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, 
                      description = :description,
                      image_url = :image
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image_url);
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

    // Contar produtos por categoria
    public function countProducts() {
        $query = "SELECT COUNT(*) as total 
                  FROM products 
                  WHERE category_id = :id AND active = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}