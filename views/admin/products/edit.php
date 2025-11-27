<?php 
$page_title = 'Editar Produto';
include __DIR__ . '/../../layouts/admin-header.php'; 
?>

<div class="page-header">
    <h1><i class="fas fa-edit"></i> Editar Produto</h1>
    <a href="<?php echo BASE_URL; ?>admin/products" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="form-card">
    <form action="<?php echo BASE_URL; ?>admin/products/update/<?php echo $product_data['id']; ?>" 
          method="POST" enctype="multipart/form-data">
        
        <div class="form-row">
            <div class="form-group">
                <label for="name">Nome do Produto *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($product_data['name']); ?>">
            </div>

            <div class="form-group">
                <label for="category_id">Categoria *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Selecione uma categoria</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"
                                <?php echo ($category['id'] == $product_data['category_id']) ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($product_data['description']); ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Preço (R$) *</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required 
                       value="<?php echo $product_data['price']; ?>">
            </div>

            <div class="form-group">
                <label for="stock_quantity">Quantidade em Estoque *</label>
                <input type="number" id="stock_quantity" name="stock_quantity" min="0" required 
                       value="<?php echo $product_data['stock_quantity']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Imagem Atual</label>
            <div class="current-image">
                <?php if($product_data['image_url']): ?>
                    <img src="<?php echo BASE_URL . $product_data['image_url']; ?>" 
                         alt="<?php echo $product_data['name']; ?>" style="max-width: 200px;">
                <?php else: ?>
                    <p class="text-muted">Nenhuma imagem cadastrada</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="image">Nova Imagem (deixe em branco para manter a atual)</label>
            <input type="file" id="image" name="image" accept="image/*">
            <small class="form-text">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar Produto
            </button>
            <a href="<?php echo BASE_URL; ?>admin/products" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../layouts/admin-footer.php'; ?>