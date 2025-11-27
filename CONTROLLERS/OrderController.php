<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Customer.php';

class OrderController {
    private $db;
    private $order;
    private $customer;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->order = new Order($this->db);
        $this->customer = new Customer($this->db);
    }

    // Listar todos os pedidos (Admin)
    public function index() {
        $stmt = $this->order->readAll();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/orders/index.php';
    }

    // Ver detalhes do pedido (Admin)
    public function show($id) {
        $this->order->id = $id;
        $order_data = $this->order->readOne();
        
        // Buscar itens do pedido
        $stmt = $this->order->getItems();
        $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/orders/show.php';
    }

    // Atualizar status do pedido
    public function updateStatus($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->order->id = $id;
            $this->order->status = $_POST['status'];
            
            if($this->order->updateStatus()) {
                $_SESSION['success'] = 'Status do pedido atualizado!';
            } else {
                $_SESSION['error'] = 'Erro ao atualizar status.';
            }
            
            header('Location: ' . BASE_URL . 'admin/orders/' . $id);
            exit;
        }
    }

    // Ver pedidos do cliente (Frontend)
    public function myOrders() {
        if(!isset($_SESSION['customer_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        $stmt = $this->order->readByCustomer($_SESSION['customer_id']);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/store/my-orders.php';
    }

    // Ver detalhes do pedido (Cliente)
    public function orderDetail($id) {
        if(!isset($_SESSION['customer_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        $this->order->id = $id;
        $order_data = $this->order->readOne();
        
        // Verificar se o pedido pertence ao cliente logado
        if($order_data['customer_id'] != $_SESSION['customer_id']) {
            $_SESSION['error'] = 'Acesso negado.';
            header('Location: ' . BASE_URL . 'my-orders');
            exit;
        }
        
        // Buscar itens
        $stmt = $this->order->getItems();
        $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/store/order-detail.php';
    }
}