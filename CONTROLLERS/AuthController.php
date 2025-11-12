<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Customer.php';

class AuthController {
    private $db;
    private $user;
    private $customer;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
        $this->customer = new Customer($this->db);
    }

    // Exibir formulário de login admin
    public function adminLogin() {
        if(isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin/dashboard');
            exit;
        }
        
        require_once __DIR__ . '/../views/admin/login.php';
    }

    // Processar login admin
    public function adminLoginPost() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            if($this->user->login($username, $password)) {
                $_SESSION['admin_id'] = $this->user->id;
                $_SESSION['admin_username'] = $this->user->username;
                $_SESSION['admin_role'] = $this->user->role;
                
                header('Location: ' . BASE_URL . 'admin/dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Usuário ou senha inválidos.';
                header('Location: ' . BASE_URL . 'admin/login');
                exit;
            }
        }
    }

    // Logout admin
    public function adminLogout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_username']);
        unset($_SESSION['admin_role']);
        
        header('Location: ' . BASE_URL . 'admin/login');
        exit;
    }

    // Exibir formulário de login cliente
    public function customerLogin() {
        if(isset($_SESSION['customer_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Processar login cliente
    public function customerLoginPost() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            if($this->customer->login($email, $password)) {
                $_SESSION['customer_id'] = $this->customer->id;
                $_SESSION['customer_name'] = $this->customer->name;
                $_SESSION['customer_email'] = $this->customer->email;
                
                header('Location: ' . BASE_URL);
                exit;
            } else {
                $_SESSION['error'] = 'Email ou senha inválidos.';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
        }
    }

    // Exibir formulário de registro
    public function customerRegister() {
        if(isset($_SESSION['customer_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Processar registro
    public function customerRegisterPost() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->customer->name = $_POST['name'];
            $this->customer->email = $_POST['email'];
            $this->customer->password = $_POST['password'];
            $this->customer->phone = $_POST['phone'];
            
            // Verificar se email já existe
            if($this->customer->emailExists()) {
                $_SESSION['error'] = 'Email já cadastrado.';
                header('Location: ' . BASE_URL . 'register');
                exit;
            }
            
            if($this->customer->create()) {
                $_SESSION['success'] = 'Cadastro realizado com sucesso! Faça login.';
                header('Location: ' . BASE_URL . 'login');
                exit;
            } else {
                $_SESSION['error'] = 'Erro ao realizar cadastro.';
                header('Location: ' . BASE_URL . 'register');
                exit;
            }
        }
    }

    // Logout cliente
    public function customerLogout() {
        unset($_SESSION['customer_id']);
        unset($_SESSION['customer_name']);
        unset($_SESSION['customer_email']);
        
        header('Location: ' . BASE_URL);
        exit;
    }
}