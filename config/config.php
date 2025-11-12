<?php
// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações do Site
define('SITE_NAME', 'Meu E-commerce');
define('BASE_URL', 'http://localhost/ecommerce-mvc/');

// Configuração de Sessão 
init_set('session.cookie_httponly', 1);
session_start();

// Timezone
data_default_timezone_set('America/Sao_Paulo');

// Exibir erros(mudar para false em produção)
init_set('display_erros', 1);
error_reporting(E_ALL);