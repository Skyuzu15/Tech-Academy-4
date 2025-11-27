<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-login-page">
    <div class="admin-login-container">
        <div class="admin-login-box">
            <div class="login-header">
                <i class="fas fa-user-shield"></i>
                <h1>Admin Panel</h1>
                <p><?php echo SITE_NAME; ?></p>
            </div>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>admin/login-post" method="POST" class="admin-login-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Usuário
                    </label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Digite seu usuário" autofocus>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Senha
                    </label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Digite sua senha">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Entrar no Painel
                </button>
            </form>

            <div class="login-footer">
                <a href="<?php echo BASE_URL; ?>">
                    <i class="fas fa-arrow-left"></i> Voltar para o site
                </a>
            </div>
        </div>
    </div>
</body>
</html>