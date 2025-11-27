<?php 
$page_title = 'Busca: ' . htmlspecialchars($search_term);
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container">
    <div class="search-results-header">
        <h1>
            <i class="fas fa-search"></i> 
            Resultados para: "<?php echo htmlspecialchars($search_term); ?>"
        </h1>
        <p><?php echo count($products); ?> produto(s) encontrado(s)</p>
    </div>

    <?php if(empty($products)): ?>
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h2>Nenhum produto encontrado</h2>
            <p>Tente buscar com outras palavras-chave</p>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                <i class="fas fa-home"></i> Voltar para Home
            </a>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <a href="<?php echo BASE_URL; ?>product/<?php echo $product['id']; ?>" class="product-image">
                        <?php if($product['image_url']): ?>
                            <img src="<?php echo BASE_URL . $product['image_url']; ?>" 
                                 alt="<?php echo $product['name']; ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </a>

                    <div class="product-info">
                        <span class="product-category"><?php echo $product['category_name']; ?></span>
                        <h3 class="product-name">
                            <a href="<?php echo BASE_URL; ?>product/<?php echo $product['id']; ?>">
                                <?php echo $product['name']; ?>
                            </a>
                        </h3>
                        <div class="product-footer">
                            <span class="product-price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                            
                            <?php if($product['stock_quantity'] > 0): ?>
                                <form action="<?php echo BASE_URL; ?>cart/add" method="POST" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-cart-plus"></i> Adicionar
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="badge badge-danger">Sem estoque</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>