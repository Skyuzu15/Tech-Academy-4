<?php 
$page_title = 'Carrinho de Compras';
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container">
    <h1 class="page-title">
        <i class="fas fa-shopping-cart"></i> Carrinho de Compras
    </h1>

    <?php if(empty($cart_items)): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h2>Seu carrinho está vazio</h2>
            <p>Adicione produtos para continuar comprando</p>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Continuar Comprando
            </a>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <div class="cart-items">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_items as $item): ?>
                            <tr>
                                <td class="cart-product">
                                    <div class="product-cart-info">
                                        <?php if($item['image_url']): ?>
                                            <img src="<?php echo BASE_URL . $item['image_url']; ?>" 
                                                 alt="<?php echo $item['name']; ?>">
                                        <?php else: ?>
                                            <div class="cart-no-image">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                        <span><?php echo $item['name']; ?></span>
                                    </div>
                                </td>
                                <td class="cart-price">
                                    R$ <?php echo number_format($item['price'], 2, ',', '.'); ?>
                                </td>
                                <td class="cart-quantity">
                                    <form action="<?php echo BASE_URL; ?>cart/update" method="POST" class="update-quantity-form">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                               min="1" class="quantity-input">
                                        <button type="submit" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="cart-subtotal">
                                    R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?>
                                </td>
                                <td class="cart-actions">
                                    <a href="<?php echo BASE_URL; ?>cart/remove/<?php echo $item['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Deseja remover este item?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-actions-footer">
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Continuar Comprando
                    </a>
                    <a href="<?php echo BASE_URL; ?>cart/clear" class="btn btn-danger"
                       onclick="return confirm('Deseja limpar o carrinho?')">
                        <i class="fas fa-trash-alt"></i> Limpar Carrinho
                    </a>
                </div>
            </div>

            <div class="cart-summary">
                <h3>Resumo do Pedido</h3>
                
                <div class="summary-line">
                    <span>Subtotal:</span>
                    <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                </div>

                <div class="summary-line">
                    <span>Frete:</span>
                    <span>A calcular</span>
                </div>

                <div class="summary-line summary-total">
                    <span>Total:</span>
                    <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                </div>

                <?php if(isset($_SESSION['customer_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>checkout" class="btn btn-primary btn-block">
                        <i class="fas fa-check"></i> Finalizar Compra
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>login" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Fazer Login para Continuar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>