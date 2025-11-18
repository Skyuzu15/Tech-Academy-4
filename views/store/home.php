<?php 
$page_title = 'Início';
include __DIR__ . '/../layouts/header.php'; 
?>

<div class="container">
    <!-- Banner Principal -->
    <section class="hero-banner">
        <div class="hero-content">
            <h1>Bem-vindo ao <?php echo SITE_NAME; ?></h1>
            <p>Encontre os melhores produtos com os melhores preços!</p>
            <a href="#products" class="btn btn-primary">Ver Produtos</a>
        </div>
    </section>

    <!-- Categorias em Destaque -->
    <section class="categories-section">
        <h2 class="section-title">Categorias</h2>
        <div class="categories-grid">
            <?php foreach($categories as $category): ?>
                <a href="<?php echo BASE_URL; ?>category/<?php echo $category['id']; ?>" class="category-card">
                    <i class="fas fa-tag"></i>
                    <h3><?php echo $category['name']; ?></h3>
                    <p><?php echo $category['description']; ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Produtos -->
    <section class="products-section" id="products">
        <h2 class="section-title">Todos os Produtos</h2>
        
        <?php if(empty($products)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Nenhum produto disponível no momento.</p>
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
                            
                            <?php if($product['stock_quantity'] < 10): ?>
                                <span class="badge badge-warning">Últimas unidades!</span>
                            <?php endif; ?>
                        </a>

                        <div class="product-info">
                            <span class="product-category"><?php echo $product['category_name']; ?></span>
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
    </section>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>