<?php 
$page_title = 'Dashboard';
include __DIR__ . '/../layouts/admin-header.php'; 
?>

<!-- Estatísticas -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['total_products']; ?></h3>
            <p>Produtos</p>
        </div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['total_orders']; ?></h3>
            <p>Pedidos</p>
        </div>
    </div>

    <div class="stat-card stat-info">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['total_customers']; ?></h3>
            <p>Clientes</p>
        </div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
            <h3>R$ <?php echo number_format($stats['total_revenue'], 2, ',', '.'); ?></h3>
            <p>Faturamento</p>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Pedidos Recentes -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-shopping-bag"></i> Pedidos Recentes</h3>
            <a href="<?php echo BASE_URL; ?>admin/orders" class="btn btn-sm btn-primary">Ver Todos</a>
        </div>
        <div class="card-body">
            <?php if(empty($recent_orders)): ?>
                <p class="text-muted">Nenhum pedido recente.</p>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recent_orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['customer_name']; ?></td>
                                <td>R$ <?php echo number_format($order['total_amount'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php
                                        $status_class = [
                                            'pending' => 'badge-warning',
                                            'processing' => 'badge-info',
                                            'completed' => 'badge-success',
                                            'cancelled' => 'badge-danger'
                                        ];
                                        $class = $status_class[$order['status']] ?? 'badge-secondary';
                                    ?>
                                    <span class="badge <?php echo $class; ?>"><?php echo $order['status']; ?></span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Produtos Mais Vendidos -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line"></i> Produtos Mais Vendidos</h3>
        </div>
        <div class="card-body">
            <?php if(empty($top_products)): ?>
                <p class="text-muted">Nenhuma venda registrada ainda.</p>
            <?php else: ?>
                <div class="top-products-list">
                    <?php foreach($top_products as $product): ?>
                        <div class="top-product-item">
                            <div class="product-image-small">
                                <?php if($product['image_url']): ?>
                                    <img src="<?php echo BASE_URL . $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                                <?php else: ?>
                                    <i class="fas fa-image"></i>
                                <?php endif; ?>
                            </div>
                            <div class="product-info-small">
                                <strong><?php echo $product['name']; ?></strong>
                                <span><?php echo $product['total_sold']; ?> vendidos</span>
                            </div>
                            <div class="product-revenue">
                                R$ <?php echo number_format($product['revenue'], 2, ',', '.'); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Produtos com Baixo Estoque -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Estoque Baixo</h3>
        </div>
        <div class="card-body">
            <?php if(empty($low_stock)): ?>
                <p class="text-success"><i class="fas fa-check"></i> Todos os produtos com estoque adequado!</p>
            <?php else: ?>
                <div class="low-stock-list">
                    <?php foreach($low_stock as $product): ?>
                        <div class="low-stock-item">
                            <div class="product-image-small">
                                <?php if($product['image_url']): ?>
                                    <img src="<?php echo BASE_URL . $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                                <?php else: ?>
                                    <i class="fas fa-image"></i>
                                <?php endif; ?>
                            </div>
                            <div class="product-info-small">
                                <strong><?php echo $product['name']; ?></strong>
                            </div>
                            <div class="stock-badge badge-danger">
                                <?php echo $product['stock_quantity']; ?> unid.
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>