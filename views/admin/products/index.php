<?php 
$page_title = 'Produtos';
include __DIR__ . '/../../layouts/admin-header.php'; 
?>

<div class="page-header">
    <h1><i class="fas fa-box"></i> Gerenciar Produtos</h1>
    <a href="<?php echo BASE_URL; ?>admin/products/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Novo Produto
    </a>
</div>

<div class="data-card">
    <?php if(empty($products)): ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>Nenhum produto cadastrado.</p>
            <a href="<?php echo BASE_URL; ?>admin/products/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Cadastrar Primeiro Produto
            </a>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?>
                    <tr>
                        <td>#<?php echo $product['id']; ?></td>
                        <td>
                            <div class="table-image">
                                <?php if($product['image_url']): ?>
                                    <img src="<?php echo BASE_URL . $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                                <?php else: ?>
                                    <div class="no-image-table">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><strong><?php echo $product['name']; ?></strong></td>
                        <td><?php echo $product['category_name']; ?></td>
                        <td>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                        <td>
                            <?php if($product['stock_quantity'] < 10): ?>
                                <span class="badge badge-warning"><?php echo $product['stock_quantity']; ?></span>
                            <?php else: ?>
                                <span class="badge badge-success"><?php echo $product['stock_quantity']; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($product['active']): ?>
                                <span class="badge badge-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <a href="<?php echo BASE_URL; ?>admin/products/edit/<?php echo $product['id']; ?>" 
                               class="btn btn-sm btn-info" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>admin/products/delete/<?php echo $product['id']; ?>" 
                               class="btn btn-sm btn-danger" title="Excluir"
                               onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/admin-footer.php'; ?>