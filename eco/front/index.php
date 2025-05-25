<?php 
include 'navfront.php';
require_once '../data.php';

// Fetch categories with product counts
$categories = $pdo->query('
    SELECT c.*, COUNT(p.id) as product_count 
    FROM cati c 
    LEFT JOIN pro p ON c.id = p.id_cat 
    GROUP BY c.id
')->fetchAll(PDO::FETCH_ASSOC);

// Get featured products (latest with images)
$featured_products = $pdo->query('
    SELECT p.*, c.libelle as category_name 
    FROM pro p 
    JOIN cati c ON p.id_cat = c.id 
    ORDER BY p.date DESC 
    LIMIT 4
')->fetchAll(PDO::FETCH_ASSOC);

// Get random category icons
$icons = ['shopping-bag', 'tshirt', 'mobile', 'laptop', 'headphones', 'camera', 'watch', 'shoe-prints'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Store</title>
    <link rel="stylesheet" href="../assets/css/front-styles.css">
</head>
<body>

<section class="hero-section">
    <div class="hero-shapes">
        <?php for($i = 0; $i < 5; $i++): 
            $width = rand(50, 200);
            $left = rand(0, 100);
            $top = rand(0, 100);
            $delay = $i * 0.5;
        ?>
            <div class="hero-shape" data-width="<?php echo $width; ?>" 
                 data-left="<?php echo $left; ?>" 
                 data-top="<?php echo $top; ?>"
                 data-delay="<?php echo $delay; ?>"></div>
        <?php endfor; ?>
    </div>
    <div class="hero-content container">
        <h1 class="display-4 mb-4 animate-on-scroll">Welcome to Our Store</h1>
        <p class="lead mb-4 animate-on-scroll">Discover our amazing collection of products</p>
        <div class="search-container animate-on-scroll">
            <input type="text" class="search-input" placeholder="Search products..." 
                   onkeyup="filterProducts(this.value)">
        </div>
    </div>
</section>

<div class="container">
    <section id="categories">
        <h2 class="section-title animate-on-scroll">Shop by Category</h2>
        <div class="category-grid">
            <?php foreach ($categories as $index => $category): ?>
                <a href="cati.php?id=<?php echo $category['id']; ?>" class="text-decoration-none">
                    <div class="category-card animate-on-scroll" data-delay="<?php echo $index * 0.1; ?>">
                        <span class="category-count"><?php echo $category['product_count']; ?> items</span>
                        <div class="category-icon">
                            <i class="fas fa-<?php echo $icons[array_rand($icons)]; ?>"></i>
                        </div>
                        <h3 class="h5 mb-3"><?php echo htmlspecialchars($category['libelle']); ?></h3>
                        <span class="btn btn-sm btn-outline-primary">View Products</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php if (!empty($featured_products)): ?>
        <section id="featured" class="featured-section">
            <h2 class="section-title animate-on-scroll">Featured Products</h2>
            <div class="featured-grid">
                <?php foreach ($featured_products as $index => $product): ?>
                    <div class="product-card animate-on-scroll" 
                         data-category="<?php echo htmlspecialchars($product['category_name']); ?>"
                         data-delay="<?php echo $index * 0.15; ?>">
                        <?php if ($product['discount'] > 0): ?>
                            <div class="discount-badge">-<?php echo $product['discount']; ?>%</div>
                        <?php endif; ?>
                        <img src="<?php echo !empty($product['image']) ? '../' . $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" 
                             class="product-image" 
                             alt="<?php echo htmlspecialchars($product['libelle']); ?>">
                        <div class="product-details">
                            <small class="text-muted"><?php echo htmlspecialchars($product['category_name']); ?></small>
                            <h4 class="mb-2"><?php echo htmlspecialchars($product['libelle']); ?></h4>
                            <div class="product-price">
                                <?php
                                $final_price = $product['prix'] * (1 - $product['discount']/100);
                                if ($product['discount'] > 0):
                                ?>
                                    <small class="text-muted text-decoration-line-through">
                                        $<?php echo number_format($product['prix'], 2); ?>
                                    </small>
                                <?php endif; ?>
                                $<?php echo number_format($final_price, 2); ?>
                            </div>
                            <button class="btn btn-primary w-100 mt-3" onclick="addToCart(this)">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<script src="../assets/js/animations.js"></script>
<script>
// Apply styles from data attributes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.hero-shape').forEach(shape => {
        shape.style.width = shape.dataset.width + 'px';
        shape.style.height = shape.dataset.width + 'px';
        shape.style.left = shape.dataset.left + '%';
        shape.style.top = shape.dataset.top + '%';
        shape.style.animationDelay = shape.dataset.delay + 's';
    });

    document.querySelectorAll('[data-delay]').forEach(element => {
        element.style.animationDelay = element.dataset.delay + 's';
    });
});

function filterProducts(query) {
    query = query.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const title = product.querySelector('h4').textContent.toLowerCase();
        const category = product.dataset.category.toLowerCase();
        
        if (title.includes(query) || category.includes(query)) {
            product.style.display = 'block';
            product.classList.add('animate');
        } else {
            product.style.display = 'none';
            product.classList.remove('animate');
        }
    });
}
</script>

</body>
</html>
