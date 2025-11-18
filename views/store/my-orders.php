<?php 
$page_title = 'Meus Pedidos';
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container">
    <h1 class="page-title">
        <i class="fas fa-shopping-bag"></i> Meus Pedidos
    </h1>

    <?php if(empty($orders)): ?>
        <div class="empty-state">
            <i class="fas fa-shopping-bag"></i>
            <h2>Você ainda não fez nenhum pedido</h2>
            <p>Comece a explorar nossos produtos!</p>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Começar a Comprar
            </a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-number">
                            <strong>Pedido #<?php echo $order['id']; ?></strong>
                            <span class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                        </div>
                        <div class="order-status">
                            <?php
                                $status_class = [
                                    'pending' => 'badge-warning',
                                    'processing' => 'badge-info',
                                    'completed' => 'badge-success',
                                    'cancelled' => 'badge-danger'
                                ];
                                
                                $status_text = [
                                    'pending' => 'Pendente',
                                    'processing' => 'Em Processamento',
                                    'completed' => 'Concluído',
                                    'cancelled' => 'Cancelado'
                                ];
                                
                                $class = $status_class[$order['status']] ?? 'badge-secondary';
                                $text = $status_text[$order['status']] ?? $order['status'];
                            ?>
                            <span class="badge <?php echo $class; ?>"><?php echo $text; ?></span>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="order-info">
                            <div class="info-item">
                                <i class="fas fa-credit-card"></i>
                                <span>
                                    <?php 
                                        $payment_methods = [
                                            'credit_card' => 'Cartão de Crédito',
                                            'debit_card' => 'Cartão de Débito',
                                            'pix' => 'PIX',
                                            'boleto' => 'Boleto'
                                        ];
                                        echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                                    ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span class="order-total">R$ <?php echo number_format($order['total_amount'], 2, ',', '.'); ?></span>
                            </div>
                        </div>

                        <a href="<?php echo BASE_URL; ?>my-orders/<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> Ver Detalhes
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>