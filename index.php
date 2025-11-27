<?php
// Iniciar configurações
require_once 'config/config.php';

// Pegar a URL
$request = $_SERVER['REQUEST_URI'];
$base_path = '/ecommerce-mvc/'; // ⚠️ AJUSTAR CONFORME SEU CAMINHO
$request = str_replace($base_path, '', $request);
$request = strtok($request, '?'); // Remover query string

// Remover barra final
$request = rtrim($request, '/');

// Roteamento
switch ($request) {
    // ROTAS DA LOJA (FRONTEND)
    case '':
    case 'home':
        require_once 'controllers/StoreController.php';
        $controller = new StoreController();
        $controller->index();
        break;

    case (preg_match('/^product\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/StoreController.php';
        $controller = new StoreController();
        $controller->productDetail($matches[1]);
        break;

    case (preg_match('/^category\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/StoreController.php';
        $controller = new StoreController();
        $controller->category($matches[1]);
        break;

    case 'search':
        require_once 'controllers/StoreController.php';
        $controller = new StoreController();
        $controller->search();
        break;

    // ROTAS DE AUTENTICAÇÃO (CLIENTE)
    case 'login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->customerLogin();
        break;

    case 'login-post':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->customerLoginPost();
        break;

    case 'register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->customerRegister();
        break;

    case 'register-post':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->customerRegisterPost();
        break;

    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->customerLogout();
        break;

    // ROTAS DO CARRINHO
    case 'cart':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->view();
        break;

    case 'cart/add':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->add();
        break;

    case 'cart/update':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->update();
        break;

    case (preg_match('/^cart\/remove\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->remove($matches[1]);
        break;

    case 'cart/clear':
        require_once 'controllers/CartController.php';
        $controller = new CartController();
        $controller->clear();
        break;

    // ROTAS DE CHECKOUT E PEDIDOS (CLIENTE)
    case 'checkout':
        require_once 'controllers/StoreController.php';
        $controller = new StoreController();
        $controller->checkout();
        break;

    case 'process-order':
        require_once 'controllers/StoreController.php';
        $controller = new StoreController();
        $controller->processOrder();
        break;

    case (preg_match('/^order-success\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/StoreController.php';
        $controller = new StoreController();
        $controller->orderSuccess($matches[1]);
        break;

    case 'my-orders':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->myOrders();
        break;

    case (preg_match('/^my-orders\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->orderDetail($matches[1]);
        break;

    // ROTAS ADMIN - LOGIN
    case 'admin/login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->adminLogin();
        break;

    case 'admin/login-post':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->adminLoginPost();
        break;

    case 'admin/logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->adminLogout();
        break;

    // ROTAS ADMIN - DASHBOARD
    case 'admin':
    case 'admin/dashboard':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    // ROTAS ADMIN - PRODUTOS
    case 'admin/products':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->index();
        break;

    case 'admin/products/create':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->create();
        break;

    case 'admin/products/store':
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->store();
        break;

    case (preg_match('/^admin\/products\/edit\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^admin\/products\/update\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->update($matches[1]);
        break;

    case (preg_match('/^admin\/products\/delete\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/ProductController.php';
        $controller = new ProductController();
        $controller->delete($matches[1]);
        break;

    // ROTAS ADMIN - CATEGORIAS
    case 'admin/categories':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->index();
        break;

    case 'admin/categories/create':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->create();
        break;

    case 'admin/categories/store':
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->store();
        break;

    case (preg_match('/^admin\/categories\/edit\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->edit($matches[1]);
        break;

    case (preg_match('/^admin\/categories\/update\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->update($matches[1]);
        break;

    case (preg_match('/^admin\/categories\/delete\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->delete($matches[1]);
        break;

    // ROTAS ADMIN - PEDIDOS
    case 'admin/orders':
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->index();
        break;

    case (preg_match('/^admin\/orders\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->show($matches[1]);
        break;

    case (preg_match('/^admin\/orders\/update-status\/(\d+)$/', $request, $matches) ? true : false):
        require_once 'controllers/OrderController.php';
        $controller = new OrderController();
        $controller->updateStatus($matches[1]);
        break;

    // ROTAS ADMIN - CLIENTES
    case 'admin/customers':
        require_once 'controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->index();
        break;

    // 404 - PÁGINA NÃO ENCONTRADA
    default:
        http_response_code(404);
        echo "<!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>404 - Página não encontrada</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    margin: 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-align: center;
                }
                .container {
                    padding: 40px;
                }
                h1 {
                    font-size: 6rem;
                    margin: 0;
                }
                h2 {
                    font-size: 2rem;
                    margin: 20px 0;
                }
                p {
                    font-size: 1.2rem;
                    margin-bottom: 30px;
                }
                a {
                    display: inline-block;
                    padding: 15px 30px;
                    background: white;
                    color: #667eea;
                    text-decoration: none;
                    border-radius: 8px;
                    font-weight: 600;
                    transition: transform 0.3s;
                    }
                a:hover {
                    transform: translateY(-3px);
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>404</h1>
                <h2>Página não encontrada</h2>
                <p>A página que você procura não existe.</p>
                <a href='" . BASE_URL . "'>Voltar para Home</a>
            </div>
        </body>
        </html>";
    break;
}