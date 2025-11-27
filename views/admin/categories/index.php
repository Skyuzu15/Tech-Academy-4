<?php 
$page_title = 'Categorias';
include __DIR__ . '/../../layouts/admin-header.php'; 
?>

<div class="page-header">
    <h1><i class="fas fa-tags"></i> Gerenciar Categorias</h1>
    <a href="<?php echo BASE_URL; ?>admin/categories/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nova Categoria
    </a>
</div>

<div class="data-card">
    <?php if(empty($categories)): ?>
        <div class="empty-state">
            <i class="fas fa-tags"></i>
            <p>Nenhuma categoria cadastrada.</p>
            <a href="<?php echo BASE_URL; ?>admin/categories/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Cadastrar Primeira Categoria
            </a>
        </div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $category): ?>
                    <tr>
                        <td>#<?php echo $category['id']; ?></td>
                        <td><strong><?php echo $category['name']; ?></strong></td>
                        <td><?php echo substr($category['description'], 0, 50) . '...'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($category['created_at'])); ?></td>
                        <td class="table-actions">
                            <a href="<?php echo BASE_URL; ?>admin/categories/edit/<?php echo $category['id']; ?>" 
                               class="btn btn-sm btn-info" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?php echo BASE_URL; ?>admin/categories/delete/<?php echo $category['id']; ?>" 
                               class="btn btn-sm btn-danger" title="Excluir"
                               onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
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