<?php
$page_title = 'Novo Produto';
include __DIR__ . '/../../layouts/admin-header.php';
?>

<div class="page-header">
    <h1><i class="fas fa-plus"></i> Cadastrar Novo Produto</h1>
    <a href="<?php echo BASE_URL; ?>admin/products" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="form-card">
    <form action="<?php echo BASE_URL; ?>admin/products/store" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Nome do Produto *</label>
                <input type="text" id="name" name="name" required placeholder="Ex: Notebook Dell Inspiron">
            </div>

            <div class="form-group">
                <label for="category_id">Categoria *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea id="description" name="description" rows="5"
                placeholder="Descrição detalhada do produto"></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price">Preço (R$) *</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required placeholder="0.00">
            </div>

            <div class="form-group">
                <label for="stock_quantity">Quantidade em Estoque *</label>
                <input type="number" id="stock_quantity" name="stock_quantity" min="0" required placeholder="0">
            </div>
        </div>

        <div class="form-group">
            <label for="image">Imagem do Produto</label>
            <input type="file" id="image" name="image" accept="image/*">
            <small class="form-text">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar Produto
            </button>
            <a href="<?php echo BASE_URL; ?>admin/products" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../layouts/admin-footer.php'; ?>