<?php 
$page_title = $category_data['name'];
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container">
    <div class="category-header">
        <h1>
            <i class="fas fa-tag"></i> 
            <?php echo $category_data['name']; ?>
        </h1>
        <?php if($category_data['description']): ?>
            <p><?php echo $category_data['description']; ?></p>
        <?php endif; ?>
        <p class="product-count"><?php echo count($products); ?> produto(s) nesta categoria</p>
    </div>

    <?php if(empty($products)): ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h2>Nenhum produto nesta categoria ainda</h2>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                <i class="fas fa-home"></i> Ver Todas as Categorias
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
                        
                        <?php if($product['stock_quantity'] < 10 && $product['stock_quantity'] > 0): ?>
                            <span class="badge badge-warning">Últimas unidades!</span>
                        <?php endif; ?>
                    </a>

                    <div class="product-info">
                        <h3 class="product-name">
                            <a href="<?php echo BASE_URL; ?>product/<?php echo $product['id']; ?>">
                                <?php echo $product['name']; ?>
                            </a>
                        </h3>
                        <p class="product-description">
                            <?php echo substr($product['description'], 0, 80) . '...'; ?>
                        </p>
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