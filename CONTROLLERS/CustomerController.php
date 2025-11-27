<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Customer.php';

class CustomerController {
    private $db;
    private $customer;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->customer = new Customer($this->db);
    }

    // Listar todos os clientes (Admin)
    public function index() {
        // Verificar se é admin
        if(!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }

        $stmt = $this->customer->readAll();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/customers/index.php';
    }

    // Ver detalhes do cliente
    public function show($id) {
        if(!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }

        $this->customer->id = $id;
        $customer_data = $this->customer->readOne();
        
        if(!$customer_data) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            header('Location: ' . BASE_URL . 'admin/customers');
            exit;
        }
        
        // Buscar pedidos do cliente
        $query = "SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':customer_id', $id);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/customers/show.php';
    }
}