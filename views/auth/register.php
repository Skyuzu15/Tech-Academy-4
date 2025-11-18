<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <h1><?php echo SITE_NAME; ?></h1>
                <h2>Criar Conta</h2>
            </div>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>register-post" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Nome Completo
                    </label>
                    <input type="text" id="name" name="name" required 
                           placeholder="Seu nome completo">
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> E-mail
                    </label>
                    <input type="email" id="email" name="email" required 
                           placeholder="seu@email.com">
                </div>

                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Telefone
                    </label>
                    <input type="text" id="phone" name="phone" required 
                           placeholder="(00) 00000-0000">
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Senha
                    </label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Mínimo 6 caracteres" minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Confirmar Senha
                    </label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           placeholder="Digite a senha novamente">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Cadastrar
                </button>
            </form>

            <div class="auth-footer">
                <p>Já tem uma conta? <a href="<?php echo BASE_URL; ?>login">Fazer Login</a></p>
                <p><a href="<?php echo BASE_URL; ?>">Voltar para loja</a></p>
            </div>
        </div>
    </div>

    <script>
        // Validar senhas iguais
        document.querySelector('.auth-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if(password !== confirm) {
                e.preventDefault();
                alert('As senhas não coincidem!');
            }
        });
    </script>
</body>
</html>