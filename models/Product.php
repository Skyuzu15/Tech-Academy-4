<?php
class Product {
    private $conn;
    private $table = 'products';

    public $id;
    public $name;
    public $description;
    public $price;
    public $stock_quantity;
    public $category_id;
    public $image_url;
    public $active;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, description, price, stock_quantity, category_id, image_url) 
                  VALUES (:name, :description, :price, :stock, :category, :image)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':stock', $this->stock_quantity);
        $stmt->bindParam(':category', $this->category_id);
        $stmt->bindParam(':image', $this->image_url);
        
        return $stmt->execute();
    }

    // READ ALL
    public function readAll() {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.active = 1
                  ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->stock_quantity = $row['stock_quantity'];
            $this->category_id = $row['category_id'];
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
                      price = :price,
                      stock_quantity = :stock,
                      category_id = :category,
                      image_url = :image
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':stock', $this->stock_quantity);
        $stmt->bindParam(':category', $this->category_id);
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

    // BUSCAR POR CATEGORIA
    public function readByCategory($category_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE category_id = :category_id AND active = 1
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        
        return $stmt;
    }

    // VERIFICAR ESTOQUE
    public function checkStock($quantity) {
        $query = "SELECT check_stock_availability(:id, :quantity) as status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['status'];
    }
}