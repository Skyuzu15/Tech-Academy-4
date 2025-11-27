<?php 
$page_title = 'Clientes';
include __DIR__ . '/../../layouts/admin-header.php'; 
?>

<div class="page-header">
    <h1><i class="fas fa-users"></i> Gerenciar Clientes</h1>
</div>

<div class="data-card">
    <?php if(empty($customers)): ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>Nenhum cliente cadastrado ainda.</p>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Cidade/Estado</th>
                    <th>Data de Cadastro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($customers as $customer): ?>
                    <tr>
                        <td>#<?php echo $customer['id']; ?></td>
                        <td><strong><?php echo $customer['name']; ?></strong></td>
                        <td><?php echo $customer['email']; ?></td>
                        <td><?php echo $customer['phone']; ?></td>
                        <td><?php echo $customer['city'] . '/' . $customer['state']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($customer['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/admin-footer.php'; ?>