<?php 
$page_title = 'Editar Categoria';
include __DIR__ . '/../../layouts/admin-header.php'; 
?>

<div class="page-header">
    <h1><i class="fas fa-edit"></i> Editar Categoria</h1>
    <a href="<?php echo BASE_URL; ?>admin/categories" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="form-card">
    <form action="<?php echo BASE_URL; ?>admin/categories/update/<?php echo $category_data['id']; ?>" method="POST">
        <div class="form-group">
            <label for="name">Nome da Categoria *</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo htmlspecialchars($category_data['name']); ?>">
        </div>

        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($category_data['description']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar Categoria
            </button>
            <a href="<?php echo BASE_URL; ?>admin/categories" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../layouts/admin-footer.php'; ?>