-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/11/2025 às 02:28
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ecommerce_db`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_bulk_products` (IN `p_category_id` INT, IN `p_quantity` INT, IN `p_base_price` DECIMAL(10,2))   BEGIN
	DECLARE i INT DEFAULT 1;
	DECLARE product_name VARCHAR(200);
	DECLARE product_price DECIMAL(10,2);
	DECLARE product_stock INT;

	WHILE i <= p_quantity DO
		SET product_name = CONCAT('Produto Teste', i);
		SET product_price = p_base_price + (RAND() * 100);
		SET product_stock = FLOOR(RAIN() * 100 + 10);
	
		INSERT INTO products (name, description, price, stock_quantity, category_id)
		VALUES (
			product_name,
			CONCAT('Descrição detalhada do ', product_name),
			ROUND(product_price, 2),
			product_stock,
			p_category_id
		);
		
		SET i = i + 1;
	END WHILE;
	
	SELECT CONCAT(p_quantity, ' produtos inseridos com sucesso!') AS message;
END

-- Como usar: CALL insert_bulk_products(1, 50, 100.00)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `process_order` (IN `p_customer_id` INT, IN `p_payment_method` VARCHAR(50), IN `p_shipping_address` TEXT)   BEGIN
    DECLARE v_order_id INT;
    DECLARE v_total DECIMAL(10,2) DEFAULT 0;
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_product_id INT;
    DECLARE v_quantity INT;
    DECLARE v_price DECIMAL(10,2);
    DECLARE v_subtotal DECIMAL(10,2);
    DECLARE v_product_name VARCHAR(200);
    
    DECLARE cur CURSOR FOR 
        SELECT ci.product_id, ci.quantity, p.price, p.name
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.customer_id = p_customer_id;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Criar pedido
    INSERT INTO orders (customer_id, total_amount, payment_method, shipping_address)
    VALUES (p_customer_id, 0, p_payment_method, p_shipping_address);
    
    SET v_order_id = LAST_INSERT_ID();
    
    -- Processar itens do carrinho
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO v_product_id, v_quantity, v_price, v_product_name;
        
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        SET v_subtotal = v_quantity * v_price;
        SET v_total = v_total + v_subtotal;
        
        -- Inserir item do pedido
        INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, subtotal)
        VALUES (v_order_id, v_product_id, v_product_name, v_quantity, v_price, v_subtotal);
    END LOOP;
    
    CLOSE cur;
    
    -- Atualizar total do pedido
    UPDATE orders SET total_amount = v_total WHERE id = v_order_id;
    
    -- Limpar carrinho
    DELETE FROM cart_items WHERE customer_id = p_customer_id;
    
    SELECT v_order_id AS order_id, v_total AS total_amount;
END$$

--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `calculate_cart_total` (`p_customer_id` INT) RETURNS DECIMAL(10,2) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE total DECIMAL(10,2);
    
    SELECT COALESCE(SUM(ci.quantity * p.price), 0)
    INTO total
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.customer_id = p_customer_id;
    
    RETURN total;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `check_stock_availability` (`p_product_id` INT, `p_quantity` INT) RETURNS VARCHAR(50) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci DETERMINISTIC READS SQL DATA BEGIN
    DECLARE current_stock INT;
    
    SELECT stock_quantity INTO current_stock
    FROM products
    WHERE id = p_product_id AND active = TRUE;
    
    IF current_stock IS NULL THEN
        RETURN 'PRODUCT_NOT_FOUND';
    ELSEIF current_stock >= p_quantity THEN
        RETURN 'AVAILABLE';
    ELSE
        RETURN 'INSUFFICIENT_STOCK';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `count_cart_items` (`p_session_id` VARCHAR(100)) RETURNS INT(11) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE item_count INT;
    
    SELECT COALESCE(SUM(quantity), 0)
    INTO item_count
    FROM cart_items
    WHERE session_id = p_session_id;
    
    RETURN item_count;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','manager') DEFAULT 'manager',
  `active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `active`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@ecommerce.com', 'Administrador', 'admin', 1, NULL, '2025-11-09 19:54:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cart_items`
--

INSERT INTO `cart_items` (`id`, `session_id`, `customer_id`, `product_id`, `quantity`, `created_at`) VALUES
(1, '1', 1, 1, 2, '2025-11-09 20:21:16'),
(2, '2', 1, 2, 3, '2025-11-09 20:21:34'),
(3, 'teste123', 1, 1, 2, '2025-11-09 23:39:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image_url`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Eletrônicos', 'Produtos eletrônicos e tecnologia', NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(2, 'Roupas', 'Vestuário e acessórios', NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(3, 'Livros', 'Livros e publicações', NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(4, 'Casa e Decoração', 'Itens para casa', NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(5, 'Esportes', 'Artigos esportivos', NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `phone`, `address`, `city`, `state`, `zipcode`, `active`, `created_at`, `updated_at`) VALUES
(1, 'João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '11999999999', 'Rua Teste, 123', 'São Paulo', 'SP', '01234-567', 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Acionadores `order_items`
--
DELIMITER $$
CREATE TRIGGER `update_stock_after_order` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
	UPDATE products
	SET stock_quantity = stock_quantuty - NEW.quantity
	WHERE id = NEW.product_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `image_url`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Notebook Dell', 'Notebook Dell Inspiron 15, Intel Core i5, 8GB RAM', 2999.00, 15, 1, NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(2, 'Mouse Logitech', 'Mouse sem fio Logitech MX Master', 299.00, 50, 1, NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(3, 'Camiseta Básica', 'Camiseta 100% algodão', 49.90, 100, 2, NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(4, 'Calça Jeans', 'Calça jeans masculina', 129.90, 80, 2, NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34'),
(5, 'Clean Code', 'Livro Clean Code - Robert Martin', 89.90, 30, 3, NULL, 1, '2025-11-09 19:54:34', '2025-11-09 19:54:34');

--
-- Acionadores `products`
--
DELIMITER $$
CREATE TRIGGER `audit_product_price_change` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
    IF OLD.price != NEW.price THEN
        INSERT INTO product_audit (
            product_id, 
            action, 
            old_price, 
            new_price, 
            changed_by
        )
        VALUES (
            NEW.id, 
            'PRICE_UPDATE', 
            OLD.price, 
            NEW.price, 
            USER()
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `audit_product_stock_change` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
	IF OLD.stock_quantity != NEW.stock_quantity THEN
		INSERT INTO product_audit (
			product_id,
			action,
			old_stock,
			new_stock,
			changed_by
		)
		VALUES (
			NEW.id,
			'STOCK_UPDATE',
			OLD.stock_quantity,
			NEW.stock_quantity,
			USER()
		);
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_aidt`
--

CREATE TABLE `product_aidt` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `active` varchar(50) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `new_price` decimal(10,2) DEFAULT NULL,
  `old_stock` int(11) DEFAULT NULL,
  `new_stock` int(11) DEFAULT NULL,
  `chenged_by` varchar(100) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Índices de tabela `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_customer` (`customer_id`);

--
-- Índices de tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`active`);

--
-- Índices de tabela `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_active` (`active`);

--
-- Índices de tabela `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_at`);

--
-- Índices de tabela `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Índices de tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_price` (`price`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_name` (`name`);

--
-- Índices de tabela `product_aidt`
--
ALTER TABLE `product_aidt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_changed_at` (`changed_at`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `product_aidt`
--
ALTER TABLE `product_aidt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Restrições para tabelas `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Restrições para tabelas `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
