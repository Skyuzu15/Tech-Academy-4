<?php 
$page_title = 'Pedidos';
include __DIR__ . '/../../layouts/admin-header.php'; 
?>

<div class="page-header">
    <h1><i class="fas fa-shopping-bag"></i> Gerenciar Pedidos</h1>
</div>

<div class="data-card">
    <?php if(empty($orders)): ?>
        <div class="empty-state">
            <i class="fas fa-shopping-bag"></i>
            <p>Nenhum pedido registrado ainda.</p>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>E-mail</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $order): ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo $order['customer_name']; ?></td>
                        <td><?php echo $order['customer_email']; ?></td>
                        <td>R$ <?php echo number_format($order['total_amount'], 2, ',', '.'); ?></td>
                        <td>
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
                                $class = $status_class[$order['status']] ?? 'badge-secondary';
                                $text = $status_text[$order['status']] ?? $order['status'];
                            ?>
                            <span class="badge <?php echo $class; ?>"><?php echo $text; ?></span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td class="table-actions">
                            <a href="<?php echo BASE_URL; ?>admin/orders/<?php echo $order['id']; ?>" 
                               class="btn btn-sm btn-info" title="Ver Detalhes">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/admin-footer.php'; ?>