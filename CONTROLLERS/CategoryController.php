<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    private $db;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->category = new Category($this->db);
    }

    // Listar todas as categorias
    public function index() {
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/admin/categories/index.php';
    }

    // Exibir formulário de criação
    public function create() {
        require_once __DIR__ . '/../views/admin/categories/create.php';
    }

    // Salvar nova categoria
    public function store() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];
            
            if($this->category->create()) {
                $_SESSION['success'] = 'Categoria criada com sucesso!';
                header('Location: ' . BASE_URL . 'admin/categories');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao criar categoria.';
            }
        }
    }

    // Exibir formulário de edição
    public function edit($id) {
        $this->category->id = $id;
        $category_data = $this->category->readOne();
        
        require_once __DIR__ . '/../views/admin/categories/edit.php';
    }

    // Atualizar categoria
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->id = $id;
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];
            
            if($this->category->update()) {
                $_SESSION['success'] = 'Categoria atualizada com sucesso!';
                header('Location: ' . BASE_URL . 'admin/categories');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao atualizar categoria.';
            }
        }
    }

    // Deletar categoria
    public function delete($id) {
        $this->category->id = $id;
        
        // Verificar se há produtos nesta categoria
        $count = $this->category->countProducts();
        
        if($count > 0) {
            $_SESSION['error'] = 'Não é possível remover categoria com produtos associados.';
        } else {
            if($this->category->delete()) {
                $_SESSION['success'] = 'Categoria removida com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao remover categoria.';
            }
        }
        
        header('Location: ' . BASE_URL . 'admin/categories');
        exit;
    }
}