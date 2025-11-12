<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

class ProductController {
    private $db;
    private $product;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->product = new Product($this->db);
        $this->category = new Category($this->db);
    }

    // Listar todos os produtos (Admin)
    public function index() {
        $stmt = $this->product->readAll();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/products/index.php';
    }

    // Exibir formulário de criação
    public function create() {
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/products/create.php';
    }

    // Salvar novo produto
    public function store() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->price = $_POST['price'];
            $this->product->stock_quantity = $_POST['stock_quantity'];
            $this->product->category_id = $_POST['category_id'];
            
            // Upload de imagem
            if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $this->product->image_url = $this->uploadImage($_FILES['image']);
            }
            
            if($this->product->create()) {
                $_SESSION['success'] = 'Produto criado com sucesso!';
                header('Location: ' . BASE_URL . 'admin/products');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao criar produto.';
            }
        }
    }

    // Exibir formulário de edição
    public function edit($id) {
        $this->product->id = $id;
        $product_data = $this->product->readOne();
        
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/products/edit.php';
    }

    // Atualizar produto
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->id = $id;
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->price = $_POST['price'];
            $this->product->stock_quantity = $_POST['stock_quantity'];
            $this->product->category_id = $_POST['category_id'];
            
            // Upload de imagem (se houver)
            if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $this->product->image_url = $this->uploadImage($_FILES['image']);
            } else {
                // Manter imagem existente
                $current = $this->product->readOne();
                $this->product->image_url = $current['image_url'];
            }
            
            if($this->product->update()) {
                $_SESSION['success'] = 'Produto atualizado com sucesso!';
                header('Location: ' . BASE_URL . 'admin/products');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao atualizar produto.';
            }
        }
    }

    // Deletar produto
    public function delete($id) {
        $this->product->id = $id;
        
        if($this->product->delete()) {
            $_SESSION['success'] = 'Produto removido com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao remover produto.';
        }
        
        header('Location: ' . BASE_URL . 'admin/products');
        exit;
    }

    // Upload de imagem
    private function uploadImage($file) {
        $upload_dir = __DIR__ . '/../public/images/products/';
        
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if(move_uploaded_file($file['tmp_name'], $upload_path)) {
            return 'public/images/products/' . $new_filename;
        }
        
        return null;
    }

    // API - Retornar produtos em JSON
    public function api_list() {
        $stmt = $this->product->readAll();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($products);
    }
}