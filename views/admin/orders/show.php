<?php 
$page_title = 'Detalhes do Pedido #' . $order_data['id'];
include __DIR__ . '/../../layouts/admin-header.php'; 
?>

<div class="page-header">
    <h1><i class="fas fa-shopping-bag"></i> Pedido #<?php echo $order_data['id']; ?></h1>
    <a href="<?php echo BASE_URL; ?>admin/orders" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="order-details-grid">
    <!-- Informações do Pedido -->
    <div class="order-info-card">
        <h3><i class="fas fa-info-circle"></i> Informações do Pedido</h3>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Data do Pedido:</span>
                <span class="value"><?php echo date('d/m/Y H:i', strtotime($order_data['created_at'])); ?></span>
            </div>

            <div class="info-item">
                <span class="label">Status:</span>
                <span class="value">
                    <?php
                        $status_class = [
                            'pending' => 'badge-warning',
                            'processing' => 'badge-info',
                            'completed' => 'badge-success',
                            'cancelled' => 'badge-danger'
                        ];
                        $status_text = [
                            'pending' => 'Pendente',
                            'processing' => 'Processando',
                            'completed' => 'Concluído',
                            'cancelled' => 'Cancelado'
                        ];
                        $class = $status_class[$order_data['status']] ?? 'badge-secondary';
                        $text = $status_text[$order_data['status']] ?? $order_data['status'];
                    ?>
                    <span class="badge <?php echo $class; ?>"><?php echo $text; ?></span>
                </span>
            </div>

            <div class="info-item">
                <span class="label">Forma de Pagamento:</span>
                <span class="value">
                    <?php 
                        $payment_methods = [
                            'credit_card' => 'Cartão de Crédito',
                            'debit_card' => 'Cartão de Débito',
                            'pix' => 'PIX',
                            'boleto' => 'Boleto Bancário'
                        ];
                        echo $payment_methods[$order_data['payment_method']] ?? $order_data['payment_method'];
                    ?>
                </span>
            </div>

            <div class="info-item">
                <span class="label">Total:</span>
                <span class="value total-highlight">R$ <?php echo number_format($order_data['total_amount'], 2, ',', '.'); ?></span>
            </div>
        </div>

        <!-- Atualizar Status -->
        <div class="status-update">
            <h4>Atualizar Status</h4>
            <form action="<?php echo BASE_URL; ?>admin/orders/update-status/<?php echo $order_data['id']; ?>" method="POST">
                <div class="form-inline">
                    <select name="status" class="form-control">
                        <option value="pending" <?php echo ($order_data['status'] == 'pending') ? 'selected' : ''; ?>>Pendente</option>
                        <option value="processing" <?php echo ($order_data['status'] == 'processing') ? 'selected' : ''; ?>>Processando</option>
                        <option value="completed" <?php echo ($order_data['status'] == 'completed') ? 'selected' : ''; ?>>Concluído</option>
                        <option value="cancelled" <?php echo ($order_data['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync"></i> Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Informações do Cliente -->
    <div class="customer-info-card">
        <h3><i class="fas fa-user"></i> Informações do Cliente</h3>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Nome:</span>
                <span class="value"><?php echo $order_data['customer_name']; ?></span>
            </div>

            <div class="info-item">
                <span class="label">E-mail:</span>
                <span class="value"><?php echo $order_data['customer_email']; ?></span>
            </div>

            <div class="info-item">
                <span class="label">Telefone:</span>
                <span class="value"><?php echo $order_data['customer_phone']; ?></span>
            </div>
        </div>

        <div class="shipping-address">
            <h4><i class="fas fa-shipping-fast"></i> Endereço de Entrega</h4>
            <p><?php echo nl2br($order_data['shipping_address']); ?></p>
        </div>

        <?php if($order_data['notes']): ?>
            <div class="order-notes">
                <h4><i class="fas fa-comment"></i> Observações</h4>
                <p><?php echo nl2br($order_data['notes']); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Itens do Pedido -->
<div class="order-items-card">
    <h3><i class="fas fa-box"></i> Itens do Pedido</h3>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($order_items as $item): ?>
                <tr>
                    <td>
                        <div class="product-info-inline">
                            <?php if($item['image_url']): ?>
                                <img src="<?php echo BASE_URL . $item['image_url']; ?>" 
                                     alt="<?php echo $item['product_name']; ?>" class="product-thumb">
                            <?php endif; ?>
                            <span><?php echo $item['product_name']; ?></span>
                        </div>
                    </td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>R$ <?php echo number_format($item['unit_price'], 2, ',', '.'); ?></td>
                    <td><strong>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total do Pedido:</strong></td>
                <td><strong class="total-highlight">R$ <?php echo number_format($order_data['total_amount'], 2, ',', '.'); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php include __DIR__ . '/../../layouts/admin-footer.php'; ?>