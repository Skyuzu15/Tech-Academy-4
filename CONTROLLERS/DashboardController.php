<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Order.php';

class DashboardController {
    private $db;
    private $product;
    private $category;
    private $customer;
    private $order;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->product = new Product($this->db);
        $this->category = new Category($this->db);
        $this->customer = new Customer($this->db);
        $this->order = new Order($this->db);
    }

    public function index() {
        // Verificar se admin está logado
        if(!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }

        // Buscar estatísticas
        $stats = $this->getStatistics();
        
        // Produtos mais vendidos
        $top_products = $this->getTopProducts();
        
        // Pedidos recentes
        $recent_orders = $this->getRecentOrders();
        
        // Produtos com baixo estoque
        $low_stock = $this->getLowStockProducts();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    private function getStatistics() {
        // Total de produtos
        $query_products = "SELECT COUNT(*) as total FROM products WHERE active = 1";
        $stmt = $this->db->query($query_products);
        $total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total de categorias
        $query_categories = "SELECT COUNT(*) as total FROM categories WHERE active = 1";
        $stmt = $this->db->query($query_categories);
        $total_categories = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total de clientes
        $query_customers = "SELECT COUNT(*) as total FROM customers WHERE active = 1";
        $stmt = $this->db->query($query_customers);
        $total_customers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Estatísticas de pedidos
        $order_stats = $this->order->getStats();

        return [
            'total_products' => $total_products,
            'total_categories' => $total_categories,
            'total_customers' => $total_customers,
            'total_orders' => $order_stats['total_orders'],
            'completed_orders' => $order_stats['completed_orders'],
            'pending_orders' => $order_stats['pending_orders'],
            'total_revenue' => $order_stats['total_revenue']
        ];
    }

    private function getTopProducts() {
        $query = "SELECT p.name, p.image_url, SUM(oi.quantity) as total_sold, SUM(oi.subtotal) as revenue
                  FROM order_items oi
                  JOIN products p ON oi.product_id = p.id
                  GROUP BY p.id, p.name, p.image_url
                  ORDER BY total_sold DESC
                  LIMIT 5";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getRecentOrders() {
        $query = "SELECT o.id, o.total_amount, o.status, o.created_at, c.name as customer_name
                  FROM orders o
                  JOIN customers c ON o.customer_id = c.id
                  ORDER BY o.created_at DESC
                  LIMIT 10";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getLowStockProducts() {
        $query = "SELECT id, name, stock_quantity, image_url
                  FROM products
                  WHERE active = 1 AND stock_quantity < 10
                  ORDER BY stock_quantity ASC
                  LIMIT 5";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // API para gráficos
    public function getSalesData() {
        $query = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as orders,
                    SUM(total_amount) as revenue
                  FROM orders
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  GROUP BY DATE(created_at)
                  ORDER BY date ASC";
        
        $stmt = $this->db->query($query);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}