<?php
session_start();
if (!isset($_SESSION['users'])) {
    header("Location: cont.php");
    exit();
}
include 'header.php';
require_once 'data.php';

// Create uploads directory if it doesn't exist
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Fetch categories
$ex = $pdo->prepare('SELECT * FROM cati');
$ex->execute();
$categories = $ex->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        .product-form {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }
        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-top: 1rem;
        }
        .image-preview {
            max-width: 200px;
            margin-top: 1rem;
            border-radius: 8px;
            display: none;
        }
        .custom-file-upload {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            margin: 1rem 0;
            transition: all 0.3s ease;
        }
        .custom-file-upload:hover {
            border-color: var(--accent-color);
            background: var(--bg-secondary);
        }
        .upload-icon {
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
    <div class="product-form">
        <h2 class="mb-4">Add New Product</h2>
        
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" name="libelle" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Price</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" step="0.01" name="prix" min="0" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Discount (%)</label>
                <input type="number" class="form-control" name="discount" min="0" max="100" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="categorie" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>">
                            <?= htmlspecialchars($category['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <label class="custom-file-upload d-block">
                    <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;" required>
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <div>Click or drag image here</div>
                </label>
                <img id="imagePreview" class="image-preview">
            </div>
            
            <button type="submit" class="btn btn-primary mt-3" name="ajouter">
                <i class="fas fa-plus-circle me-2"></i>Add Product
            </button>
        </form>
    </div>
</div>

<?php 
if (isset($_POST['ajouter'])) {
    $libelle = $_POST['libelle'];
    $prix = $_POST['prix'];
    $discount = $_POST['discount'];
    $categorie = $_POST['categorie'];
    $dat = date('Y-m-d');

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = 'uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_path = $upload_path;
            }
        }
    }

    if (!empty($libelle) && !empty($prix) && !empty($categorie) && !empty($image_path)) {
        try {
            $x = $pdo->prepare('INSERT INTO pro (libelle, prix, discount, id_cat, date, image) VALUES (?, ?, ?, ?, ?, ?)');
            $x->execute([$libelle, $prix, $discount, $categorie, $dat, $image_path]);
            echo '<div class="alert alert-success">Product added successfully!</div>'; 
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error adding product!</div>';
        }
    } else {
        echo '<div class="alert alert-danger">All fields including image are required!</div>';
    }
}
?>

<script>
// Image preview functionality
document.getElementById('imageInput').onchange = function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
};
</script>

</body>
</html>
