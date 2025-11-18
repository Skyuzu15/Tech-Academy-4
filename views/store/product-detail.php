<?php 
$page_title = $product_data['name'];
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container">
    <div class="breadcrumb">
        <a href="<?php echo BASE_URL; ?>">Início</a>
        <span>/</span>
        <a href="<?php echo BASE_URL; ?>category/<?php echo $product_data['category_id']; ?>">
            <?php echo $product_data['category_name']; ?>
        </a>
        <span>/</span>
        <span><?php echo $product_data['name']; ?></span>
    </div>

    <div class="product-detail">
        <div class="product-detail-image">
            <?php if($product_data['image_url']): ?>
                <img src="<?php echo BASE_URL . $product_data['image_url']; ?>" 
                     alt="<?php echo $product_data['name']; ?>">
            <?php else: ?>
                <div class="no-image-large">
                    <i class="fas fa-image"></i>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-detail-info">
            <span class="product-category">
                <i class="fas fa-tag"></i> <?php echo $product_data['category_name']; ?>
            </span>
            
            <h1><?php echo $product_data['name']; ?></h1>
            
            <div class="product-price-large">
                R$ <?php echo number_format($product_data['price'], 2, ',', '.'); ?>
            </div>

            <div class="product-stock">
                <?php if($product_data['stock_quantity'] > 0): ?>
                    <span class="stock-available">
                        <i class="fas fa-check-circle"></i> 
                        <?php echo $product_data['stock_quantity']; ?> unidades disponíveis
                    </span>
                <?php else: ?>
                    <span class="stock-unavailable">
                        <i class="fas fa-times-circle"></i> Produto indisponível
                    </span>
                <?php endif; ?>
            </div>

            <div class="product-description-full">
                <h3>Descrição do Produto</h3>
                <p><?php echo nl2br($product_data['description']); ?></p>
            </div>

            <?php if($product_data['stock_quantity'] > 0): ?>
                <form action="<?php echo BASE_URL; ?>cart/add" method="POST" class="add-to-cart-form-detail">
                    <input type="hidden" name="product_id" value="<?php echo $product_data['id']; ?>">
                    
                    <div class="quantity-selector">
                        <label for="quantity">Quantidade:</label>
                        <input type="number" name="quantity" id="quantity" value="1" 
                               min="1" max="<?php echo $product_data['stock_quantity']; ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Produtos Relacionados -->
    <?php if(!empty($related_products) && count($related_products) > 1): ?>
        <section class="related-products">
            <h2 class="section-title">Produtos Relacionados</h2>
            <div class="products-grid">
                <?php foreach($related_products as $product): ?>
                    <?php if($product['id'] != $product_data['id']): ?>
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
                                <h3 class="product-name">
                                    <a href="<?php echo BASE_URL; ?>product/<?php echo $product['id']; ?>">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h3>
                                <div class="product-footer">
                                    <span class="product-price">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>