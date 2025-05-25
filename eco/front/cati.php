<?php  
include 'navfront.php';
require_once '../data.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid category ID.</div>";
    exit();
}

$id = $_GET['id'];

// Fetch the selected category
$stmt = $pdo->prepare('SELECT * FROM cati WHERE id = ?');
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    echo "<div class='alert alert-danger'>Category not found.</div>";
    exit();
}

// Fetch products under the selected category
$productStmt = $pdo->prepare('SELECT * FROM pro WHERE id_cat = ? ORDER BY date DESC');
$productStmt->execute([$id]);
$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

// Get category statistics
$statsStmt = $pdo->prepare('
    SELECT COUNT(*) as total,
           MIN(prix) as min_price,
           MAX(prix) as max_price,
           AVG(prix) as avg_price
    FROM pro WHERE id_cat = ?
');
$statsStmt->execute([$id]);
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['libelle']); ?> - Our Store</title>
    <link rel="stylesheet" href="../assets/css/front-styles.css">
</head>
<body>

<div class="hero-section">
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
    <div class="container">
        <a href="index.php" class="btn btn-outline-primary mb-4">
            <i class="fas fa-arrow-left me-2"></i>Back to Categories
        </a>
        <h1 class="display-4"><?php echo htmlspecialchars($category['libelle']); ?></h1>
        <?php if (!empty($category['description'])): ?>
            <p class="lead"><?php echo htmlspecialchars($category['description']); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <?php if ($stats['total'] > 0): ?>
        <div class="stats-bar animate-on-scroll">
            <div class="stat-item">
                <div class="stat-value">
                    <i class="fas fa-box me-2"></i>
                    <?php echo $stats['total']; ?>
                </div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">
                    <i class="fas fa-tag me-2"></i>
                    $<?php echo number_format($stats['min_price'], 2); ?>
                </div>
                <div class="stat-label">Min Price</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">
                    <i class="fas fa-tags me-2"></i>
                    $<?php echo number_format($stats['max_price'], 2); ?>
                </div>
                <div class="stat-label">Max Price</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">
                    <i class="fas fa-dollar-sign me-2"></i>
                    $<?php echo number_format($stats['avg_price'], 2); ?>
                </div>
                <div class="stat-label">Avg Price</div>
            </div>
        </div>

        <div class="filters animate-on-scroll">
            <h4>Sort & Filter</h4>
            <div class="sort-buttons">
                <button class="sort-button active" onclick="sortProducts('default')">Default</button>
                <button class="sort-button" onclick="sortProducts('price-low')">Price: Low to High</button>
                <button class="sort-button" onclick="sortProducts('price-high')">Price: High to Low</button>
                <button class="sort-button" onclick="sortProducts('newest')">Newest First</button>
            </div>
            <label>Price Range: $<span id="priceValue">0</span></label>
            <input type="range" class="price-slider form-range" 
                   min="0" 
                   max="<?php echo ceil($stats['max_price']); ?>" 
                   value="<?php echo ceil($stats['max_price']); ?>"
                   oninput="filterByPrice(this.value)">
        </div>

        <div class="product-grid">
            <?php foreach ($products as $index => $product): ?>
                <div class="product-card animate-on-scroll" 
                     data-price="<?php echo $product['prix']; ?>"
                     data-date="<?php echo strtotime($product['date']); ?>"
                     data-delay="<?php echo $index * 0.1; ?>">
                    <?php if ($product['discount'] > 0): ?>
                        <div class="discount-badge">-<?php echo $product['discount']; ?>%</div>
                    <?php endif; ?>
                    
                    <img src="<?php echo !empty($product['image']) ? '../' . $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" 
                         class="product-image" 
                         alt="<?php echo htmlspecialchars($product['libelle']); ?>">
                    
                    <div class="product-details">
                        <h3><?php echo htmlspecialchars($product['libelle']); ?></h3>
                        
                        <div class="product-price">
                            <?php
                            $final_price = $product['prix'] * (1 - $product['discount']/100);
                            if ($product['discount'] > 0):
                            ?>
                                <span class="text-muted text-decoration-line-through">
                                    $<?php echo number_format($product['prix'], 2); ?>
                                </span><br>
                            <?php endif; ?>
                            <span class="h4">$<?php echo number_format($final_price, 2); ?></span>
                        </div>

                        <div class="text-muted mb-3">
                            <small>Added <?php echo date('M d, Y', strtotime($product['date'])); ?></small>
                        </div>

                        <button class="btn btn-primary w-100" onclick="addToCart(this)">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state animate-on-scroll">
            <i class="fas fa-box-open fa-3x mb-3" style="color: var(--text-secondary);"></i>
            <h3>No Products Found</h3>
            <p class="text-muted">There are no products available in this category yet.</p>
            <a href="index.php" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i>Browse Other Categories
            </a>
        </div>
    <?php endif; ?>
</div>

<script src="../assets/js/animations.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply styles from data attributes
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

    // Initialize price slider value
    document.getElementById('priceValue').textContent = 
        document.querySelector('.price-slider').value;
});

function filterByPrice(maxPrice) {
    document.getElementById('priceValue').textContent = maxPrice;
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const price = parseFloat(product.dataset.price);
        if (price <= maxPrice) {
            product.style.display = 'block';
            product.classList.add('animate');
        } else {
            product.style.display = 'none';
            product.classList.remove('animate');
        }
    });
}

function sortProducts(method) {
    const products = Array.from(document.querySelectorAll('.product-card'));
    const container = document.querySelector('.product-grid');
    
    // Update active button
    document.querySelectorAll('.sort-button').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    products.sort((a, b) => {
        switch(method) {
            case 'price-low':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price-high':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'newest':
                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
            default:
                return 0;
        }
    });
    
    container.innerHTML = '';
    products.forEach((product, index) => {
        product.style.animationDelay = `${index * 0.1}s`;
        container.appendChild(product);
    });
}
</script>

</body>
</html>
