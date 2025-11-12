<?php
class Order {
    private $conn;
    private $table = 'orders';

    public $id;
    public $customer_id;
    public $total_amount;
    public $status;
    public $payment_method;
    public $shipping_address;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (customer_id, total_amount, status, payment_method, shipping_address, notes) 
                  VALUES (:customer_id, :total, :status, :payment, :address, :notes)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':customer_id', $this->customer_id);
        $stmt->bindParam(':total', $this->total_amount);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':payment', $this->payment_method);
        $stmt->bindParam(':address', $this->shipping_address);
        $stmt->bindParam(':notes', $this->notes);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    // READ ALL
    public function readAll() {
        $query = "SELECT o.*, c.name as customer_name, c.email as customer_email
                  FROM " . $this->table . " o
                  JOIN customers c ON o.customer_id = c.id
                  ORDER BY o.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $query = "SELECT o.*, c.name as customer_name, c.email as customer_email,
                         c.phone as customer_phone
                  FROM " . $this->table . " o
                  JOIN customers c ON o.customer_id = c.id
                  WHERE o.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // READ BY CUSTOMER
    public function readByCustomer($customer_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE customer_id = :customer_id 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->execute();
        
        return $stmt;
    }

    // UPDATE STATUS
    public function updateStatus() {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    // ADD ORDER ITEM
    public function addItem($product_id, $product_name, $quantity, $unit_price) {
        $query = "INSERT INTO order_items 
                  (order_id, product_id, product_name, quantity, unit_price, subtotal) 
                  VALUES (:order_id, :product_id, :product_name, :quantity, :price, :subtotal)";
        
        $stmt = $this->conn->prepare($query);
        
        $subtotal = $quantity * $unit_price;
        
        $stmt->bindParam(':order_id', $this->id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $unit_price);
        $stmt->bindParam(':subtotal', $subtotal);
        
        return $stmt->execute();
    }

    // GET ORDER ITEMS
    public function getItems() {
        $query = "SELECT oi.*, p.image_url
                  FROM order_items oi
                  LEFT JOIN products p ON oi.product_id = p.id
                  WHERE oi.order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $this->id);
        $stmt->execute();
        
        return $stmt;
    }

    // PROCESSAR PEDIDO USANDO PROCEDURE
    public function processOrder($customer_id, $payment_method, $shipping_address) {
        $query = "CALL process_order(:customer_id, :payment, :address)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':payment', $payment_method);
        $stmt->bindParam(':address', $shipping_address);
        
        if($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        
        return false;
    }

    // ESTATÍSTICAS
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN status = 'completed' THEN total_amount ELSE 0 END) as total_revenue
                  FROM " . $this->table;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}