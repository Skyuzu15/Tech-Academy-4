<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Product.php';

class CartController {
    private $db;
    private $product;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->product = new Product($this->db);
        
        // Inicializar carrinho na sessão
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Adicionar produto ao carrinho
    public function add() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            // Verificar se produto existe e tem estoque
            $this->product->id = $product_id;
            $product_data = $this->product->readOne();
            
            if(!$product_data) {
                $_SESSION['error'] = 'Produto não encontrado.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
            
            // Verificar estoque
            $stock_status = $this->product->checkStock($quantity);
            
            if($stock_status !== 'AVAILABLE') {
                $_SESSION['error'] = 'Produto sem estoque suficiente.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
            
            // Adicionar ao carrinho
            if(isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $product_data['id'],
                    'name' => $product_data['name'],
                    'price' => $product_data['price'],
                    'image_url' => $product_data['image_url'],
                    'quantity' => $quantity
                ];
            }
            
            $_SESSION['success'] = 'Produto adicionado ao carrinho!';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }
    }

    // Visualizar carrinho
    public function view() {
        $cart_items = $_SESSION['cart'];
        $total = $this->calculateTotal();
        
        require_once __DIR__ . '/../views/store/cart.php';
    }

    // Atualizar quantidade no carrinho
    public function update() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $quantity = (int)$_POST['quantity'];
            
            if($quantity <= 0) {
                unset($_SESSION['cart'][$product_id]);
            } else {
                // Verificar estoque
                $this->product->id = $product_id;
                $stock_status = $this->product->checkStock($quantity);
                
                if($stock_status === 'AVAILABLE') {
                    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
                } else {
                    $_SESSION['error'] = 'Estoque insuficiente.';
                }
            }
            
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }
    }

    // Remover item do carrinho
    public function remove($product_id) {
        if(isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success'] = 'Item removido do carrinho.';
        }
        
        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    // Limpar carrinho
    public function clear() {
        $_SESSION['cart'] = [];
        $_SESSION['success'] = 'Carrinho limpo.';
        
        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    // Calcular total do carrinho
    private function calculateTotal() {
        $total = 0;
        
        foreach($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    // Contar itens no carrinho
    public static function countItems() {
        if(!isset($_SESSION['cart'])) {
            return 0;
        }
        
        $count = 0;
        foreach($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }

    // Obter total (método estático para usar em qualquer lugar)
    public static function getTotal() {
        if(!isset($_SESSION['cart'])) {
            return 0;
        }
        
        $total = 0;
        foreach($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
}