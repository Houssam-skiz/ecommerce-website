<?php
session_start();
if (!isset($_SESSION['users'])) {
    header("Location: cont.php");
    exit();
}
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }
        .product-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid var(--border-color);
        }
        .product-details {
            padding: 1.5rem;
        }
        .product-price {
            font-size: 1.25rem;
            color: var(--accent-color);
            font-weight: bold;
        }
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
        }
        .category-badge {
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--border-color);
        }
        .stat-number {
            font-size: 2rem;
            color: var(--accent-color);
            font-weight: bold;
        }
        .stat-label {
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <div class="header-actions">
        <h2>Product Management</h2>
        <a href="pro.php" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    <?php
    require_once 'data.php';
    
    // Get statistics
    $total_products = $pdo->query('SELECT COUNT(*) FROM pro')->fetchColumn();
    $total_categories = $pdo->query('SELECT COUNT(*) FROM cati')->fetchColumn();
    $avg_price = $pdo->query('SELECT AVG(prix) FROM pro')->fetchColumn();
    
    // Get products with categories
    $products = $pdo->query('SELECT p.*, c.libelle AS categorie 
                            FROM pro p 
                            JOIN cati c ON p.id_cat = c.id
                            ORDER BY p.date DESC')
                    ->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_products; ?></div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_categories; ?></div>
            <div class="stat-label">Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">$<?php echo number_format($avg_price, 2); ?></div>
            <div class="stat-label">Average Price</div>
        </div>
    </div>

    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <?php if ($product['discount'] > 0): ?>
                    <div class="discount-badge">-<?php echo $product['discount']; ?>%</div>
                <?php endif; ?>
                
                <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'; ?>" 
                     class="product-image" 
                     alt="<?php echo htmlspecialchars($product['libelle']); ?>">
                
                <div class="product-details">
                    <div class="category-badge">
                        <i class="fas fa-tag me-1"></i>
                        <?php echo htmlspecialchars($product['categorie']); ?>
                    </div>
                    
                    <h4><?php echo htmlspecialchars($product['libelle']); ?></h4>
                    
                    <div class="product-price">
                        $<?php echo number_format($product['prix'], 2); ?>
                    </div>
                    
                    <small class="text-muted">
                        Added: <?php echo date('M d, Y', strtotime($product['date'])); ?>
                    </small>

                    <div class="action-buttons">
                        <a href="edit_produit.php?id=<?php echo $product['id']; ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="delete_produit.php?id=<?php echo $product['id']; ?>" 
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this product?');">
                            <i class="fas fa-trash me-1"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
