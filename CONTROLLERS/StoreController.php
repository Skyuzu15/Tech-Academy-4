<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';

class StoreController {
    private $db;
    private $product;
    private $category;
    private $order;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->product = new Product($this->db);
        $this->category = new Category($this->db);
        $this->order = new Order($this->db);
    }

    // Página inicial da loja
    public function index() {
        // Buscar produtos
        $stmt = $this->product->readAll();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Buscar categorias
        $stmt_cat = $this->category->readAll();
        $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/store/home.php';
    }

    // Detalhes do produto
    public function productDetail($id) {
        $this->product->id = $id;
        $product_data = $this->product->readOne();
        
        if(!$product_data) {
            $_SESSION['error'] = 'Produto não encontrado.';
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Produtos relacionados (mesma categoria)
        $stmt = $this->product->readByCategory($product_data['category_id']);
        $related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/store/product-detail.php';
    }

    // Produtos por categoria
    public function category($id) {
        $this->category->id = $id;
        $category_data = $this->category->readOne();
        
        if(!$category_data) {
            $_SESSION['error'] = 'Categoria não encontrada.';
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Buscar produtos da categoria
        $stmt = $this->product->readByCategory($id);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Todas as categorias para menu
        $stmt_cat = $this->category->readAll();
        $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/store/category.php';
    }

    // Página de checkout
    public function checkout() {
        if(!isset($_SESSION['customer_id'])) {
            $_SESSION['error'] = 'Faça login para finalizar a compra.';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        if(empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Seu carrinho está vazio.';
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $cart_items = $_SESSION['cart'];
        $total = CartController::getTotal();
        
        require_once __DIR__ . '/../views/store/checkout.php';
    }

    // Processar pedido
    public function processOrder() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        if(!isset($_SESSION['customer_id'])) {
            $_SESSION['error'] = 'Faça login para finalizar a compra.';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        if(empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Seu carrinho está vazio.';
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Dados do pedido
        $customer_id = $_SESSION['customer_id'];
        $payment_method = $_POST['payment_method'];
        $shipping_address = $_POST['shipping_address'];
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
        
        // Calcular total
        $total = CartController::getTotal();
        
        // Criar pedido
        $this->order->customer_id = $customer_id;
        $this->order->total_amount = $total;
        $this->order->status = 'pending';
        $this->order->payment_method = $payment_method;
        $this->order->shipping_address = $shipping_address;
        $this->order->notes = $notes;
        
        if($this->order->create()) {
            // Adicionar itens ao pedido
            foreach($_SESSION['cart'] as $item) {
                $this->order->addItem(
                    $item['id'],
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                );
            }
            
            // Limpar carrinho
            $_SESSION['cart'] = [];
            
            $_SESSION['success'] = 'Pedido realizado com sucesso! Número do pedido: #' . $this->order->id;
            header('Location: ' . BASE_URL . 'order-success/' . $this->order->id);
            exit;
        } else {
            $_SESSION['error'] = 'Erro ao processar pedido. Tente novamente.';
            header('Location: ' . BASE_URL . 'checkout');
            exit;
        }
    }

    // Página de sucesso do pedido
    public function orderSuccess($order_id) {
        if(!isset($_SESSION['customer_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $this->order->id = $order_id;
        $order_data = $this->order->readOne();
        
        // Verificar se pedido pertence ao cliente
        if($order_data['customer_id'] != $_SESSION['customer_id']) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        // Buscar itens
        $stmt = $this->order->getItems();
        $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/store/order-success.php';
    }

    // Buscar produtos
    public function search() {
        $search_term = isset($_GET['q']) ? $_GET['q'] : '';
        
        if(empty($search_term)) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        $query = "SELECT p.*, c.name as category_name 
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.active = 1 
                  AND (p.name LIKE :search OR p.description LIKE :search)
                  ORDER BY p.name ASC";
        
        $stmt = $this->db->prepare($query);
        $search_param = "%{$search_term}%";
        $stmt->bindParam(':search', $search_param);
        $stmt->execute();
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Categorias para menu
        $stmt_cat = $this->category->readAll();
        $categories = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/store/search.php';
    }
}