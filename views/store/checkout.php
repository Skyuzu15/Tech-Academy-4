<?php
$page_title = 'Finalizar Compra';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <h1 class="page-title">
        <i class="fas fa-check-circle"></i> Finalizar Compra
    </h1>

    <div class="checkout-container">
        <div class="checkout-form">
            <form action="<?php echo BASE_URL; ?>process-order" method="POST">
                <div class="form-section">
                    <h3><i class="fas fa-shipping-fast"></i> Endereço de Entrega</h3>

                    <div class="form-group">
                        <label for="shipping_address">Endereço Completo *</label>
                        <textarea name="shipping_address" id="shipping_address" rows="4" required
                            placeholder="Rua, Número, Complemento, Bairro, Cidade - Estado, CEP"></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-credit-card"></i> Forma de Pagamento</h3>

                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="credit_card" required>
                            <div class="payment-card">
                                <i class="fas fa-credit-card"></i>
                                <span>Cartão de Crédito</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="debit_card" required>
                            <div class="payment-card">
                                <i class="fas fa-credit-card"></i>
                                <span>Cartão de Débito</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="pix" required>
                            <div class="payment-card">
                                <i class="fas fa-qrcode"></i>
                                <span>PIX</span>
                            </div>
                        </label>

                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="boleto" required>
                            <div class="payment-card">
                                <i class="fas fa-barcode"></i>
                                <span>Boleto</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-comment"></i> Observações</h3>

                    <div class="form-group">
                        <label for="notes">Informações adicionais (opcional)</label>
                        <textarea name="notes" id="notes" rows="3"
                            placeholder="Alguma observação sobre seu pedido?"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-large btn-block">
                    <i class="fas fa-check"></i> Confirmar Pedido
                </button>
            </form>
        </div>

        <div class="checkout-summary">

            <h3>Resumo do Pedido</h3>

            <div class="order-items">
                <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">
                        <div class="item-image">
                            <?php if ($item['image_url']): ?>
                                <img src="<?php echo BASE_URL . $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                            <?php else: ?>
                                <div class="no-image-small">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="item-details">
                            <h4><?php echo $item['name']; ?></h4>
                            <p>Qtd: <?php echo $item['quantity']; ?> x R$
                                <?php echo number_format($item['price'], 2, ',', '.'); ?></p>
                        </div>
                        <div class="item-total">
                            R$ <?php echo number_format($item['price'] * $item['quantity'], 2, ',', '.'); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-totals">
                <div class="summary-line">
                    <span>Subtotal:</span>
                    <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                </div>

                <div class="summary-line">
                    <span>Frete:</span>
                    <span class="text-muted">Grátis</span>
                </div>

                <div class="summary-line summary-total">
                    <span>Total:</span>
                    <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                </div>
            </div>

            <div class="secure-checkout">
                <i class="fas fa-lock"></i>
                <span>Compra 100% segura</span>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>