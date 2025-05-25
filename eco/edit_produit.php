<?php
session_start();
require_once 'data.php';

// Check if 'id' is set in the URL and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch product details
    $stmt = $pdo->prepare('SELECT * FROM pro WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // If product not found, show error
    if (!$product) {
        echo '<div class="alert alert-danger">Produit non trouvé.</div>';
        exit();
    }
} else {
    echo '<div class="alert alert-warning">ID invalide.</div>';
    exit();
}

// Fetch categories for dropdown
$categories = $pdo->query('SELECT * FROM cati')->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libelle = trim($_POST['libelle']);
    $prix = trim($_POST['prix']);
    $discount = trim($_POST['discount']);
    $categorie = trim($_POST['categorie']);

    if (!empty($libelle) && !empty($prix) && !empty($categorie)) {
        try {
            $updateStmt = $pdo->prepare('UPDATE pro SET libelle = ?, prix = ?, discount = ?, id_cat = ? WHERE id = ?');
            $success = $updateStmt->execute([$libelle, $prix, $discount, $categorie, $id]);

            if ($success) {
                header('Location: produit_affiche.php?success=1'); // Redirect after update
                exit();
            } else {
                echo '<div class="alert alert-danger">Erreur lors de la modification.</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Erreur: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-warning">Tous les champs sont requis.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modifier Produit</title>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <h2 class="text-center my-4">Modifier Produit</h2>

    <form method="post">
        <label class="form-label">Libelle</label>
        <input type="text" class="form-control" name="libelle" value="<?php echo htmlspecialchars($product['libelle']); ?>" required>

        <label class="form-label">Prix</label>
        <input type="number" class="form-control" name="prix" value="<?php echo htmlspecialchars($product['prix']); ?>" required>

        <label class="form-label">Discount</label>
        <input type="number" class="form-control" name="discount" value="<?php echo htmlspecialchars($product['discount']); ?>" required>

        <label class="form-label">Categorie</label>
        <select name="categorie" class="form-select" required>
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['id']; ?>" 
                    <?php echo ($category['id'] == $product['id_cat']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['libelle']); ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" class="btn btn-primary mt-3">Modifier</button>
        <a href="produit_affiche.php" class="btn btn-secondary mt-3">Annuler</a>
    </form>
</div>

</body>
</html>
