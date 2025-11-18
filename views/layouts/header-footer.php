<?php
require_once __DIR__ . '/../../controllers/CartController.php';
$cart_count = CartController::countItems();
$cart_total = CartController::getTotal();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Mensagens de Feedback -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>">
                        <h1><?php echo SITE_NAME; ?></h1>
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="search-bar">
                    <form action="<?php echo BASE_URL; ?>search" method="GET">
                        <input type="text" name="q" placeholder="Buscar produtos..."
                            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <!-- User Menu -->
                <div class="header-actions">
                    <!-- Carrinho -->
                    <a href="<?php echo BASE_URL; ?>cart" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cart_count > 0): ?>
                            <span class="cart-badge"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                        <span class="cart-total">R$ <?php echo number_format($cart_total, 2, ',', '.'); ?></span>
                    </a>

                    <!-- User -->
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        <div class="user-menu">
                            <button class="user-btn">
                                <i class="fas fa-user"></i>
                                <?php echo $_SESSION['customer_name']; ?>
                            </button>
                            <div class="user-dropdown">
                                <a href="<?php echo BASE_URL; ?>my-orders">Meus Pedidos</a>
                                <a href="<?php echo BASE_URL; ?>logout">Sair</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>login" class="btn-login">
                            <i class="fas fa-sign-in-alt"></i> Entrar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="<?php echo BASE_URL; ?>">Início</a></li>
                <?php
                // Buscar categorias
                require_once __DIR__ . '/../../config/Database.php';
                require_once __DIR__ . '/../../models/Category.php';
                $db = (new Database())->connect();
                $category = new Category($db);
                $stmt = $category->readAll();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $cat):
                    ?>
                    <li><a href="<?php echo BASE_URL; ?>category/<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content"></main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo SITE_NAME; ?></h3>
                    <p>Sua loja online de confiança.</p>
                </div>

                <div class="footer-section">
                    <h4>Links Rápidos</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Início</a></li>
                        <li><a href="<?php echo BASE_URL; ?>cart">Carrinho</a></li>
                        <?php if (isset($_SESSION['customer_id'])): ?>
                            <li><a href="<?php echo BASE_URL; ?>my-orders">Meus Pedidos</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Contato</h4>
                    <ul>
                        <li><i class="fas fa-envelope"></i> contato@ecommerce.com</li>
                        <li><i class="fas fa-phone"></i> (11) 9999-9999</li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Redes Sociais</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos os direitos reservados.</p>
                <p>Desenvolvido com MVC em PHP</p>
            </div>
        </div>
    </footer>

    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
</body>

</html>