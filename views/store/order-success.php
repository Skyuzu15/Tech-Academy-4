<?php 
$page_title = 'Pedido Realizado';
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container">
    <div class="order-success">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Pedido Realizado com Sucesso!</h1>
        <p class="order-number">Número do Pedido: <strong>#<?php echo $order_data['id']; ?></strong></p>
        
        <div class="order-info-box">
            <h3>Informações do Pedido</h3>
            
            <div class="info-row">
                <span class="label">Data:</span>
                <span class="value"><?php echo date('d/m/Y H:i', strtotime($order_data['created_at'])); ?></span>
            </div>
            
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="badge badge-warning">Aguardando Pagamento</span>
            </div>
            
            <div class="info-row">
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
            
            <div class="info-row">
                <span class="label">Total:</span>
                <span class="value total-value">R$ <?php echo number_format($order_data['total_amount'], 2, ',', '.'); ?></span>
            </div>
        </div>

        <div class="order-items-success">
            <h3>Itens do Pedido</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($order_items as $item): ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$ <?php echo number_format($item['unit_price'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="next-steps">
            <h3>Próximos Passos</h3>
            <ul>
                <li><i class="fas fa-envelope"></i> Você receberá um e-mail de confirmação</li>
                <li><i class="fas fa-box"></i> Seu pedido será processado em até 24 horas</li>
                <li><i class="fas fa-truck"></i> Acompanhe o status na área "Meus Pedidos"</li>
            </ul>
        </div>

        <div class="success-actions">
            <a href="<?php echo BASE_URL; ?>my-orders" class="btn btn-primary">
                <i class="fas fa-list"></i> Ver Meus Pedidos
            </a>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">
                <i class="fas fa-home"></i> Voltar para Início
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>