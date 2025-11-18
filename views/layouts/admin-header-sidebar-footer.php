<?php
// Verificar se está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . 'admin/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-body">
    <!-- Mensagens de Feedback -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button class="close-alert">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button class="close-alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="admin-container">
        <!-- Sidebar -->
        <?php include __DIR__ . '/admin-sidebar.php'; ?>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Bar -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h2>
                </div>

                <div class="header-right">
                    <a href="<?php echo BASE_URL; ?>" class="btn-view-site" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Ver Site
                    </a>

                    <div class="admin-user">
                        <span><i class="fas fa-user-shield"></i> <?php echo $_SESSION['admin_username']; ?></span>
                        <a href="<?php echo BASE_URL; ?>admin/logout" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="admin-content">

                <aside class="admin-sidebar" id="adminSidebar">
                    <div class="sidebar-header">
                        <h2><i class="fas fa-store"></i> Admin Panel</h2>
                    </div>

                    <nav class="sidebar-nav">
                        <ul>
                            <li
                                class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/dashboard') !== false || $_SERVER['REQUEST_URI'] == BASE_URL . 'admin') ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>admin/dashboard">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li
                                class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/products') !== false) ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>admin/products">
                                    <i class="fas fa-box"></i>
                                    <span>Produtos</span>
                                </a>
                            </li>

                            <li
                                class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/categories') !== false) ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>admin/categories">
                                    <i class="fas fa-tags"></i>
                                    <span>Categorias</span>
                                </a>
                            </li>

                            <li
                                class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/orders') !== false) ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>admin/orders">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>Pedidos</span>
                                </a>
                            </li>

                            <li
                                class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'admin/customers') !== false) ? 'active' : ''; ?>">
                                <a href="<?php echo BASE_URL; ?>admin/customers">
                                    <i class="fas fa-users"></i>
                                    <span>Clientes</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </aside>

            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>public/js/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>