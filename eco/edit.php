<?php
session_start();
require_once 'data.php';

// Check if 'id' is set in the URL and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch category details
    $stmt = $pdo->prepare('SELECT * FROM cati WHERE id = ?');
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    // If category not found, show error
    if (!$category) {
        echo '<div class="alert alert-danger">Catégorie non trouvée.</div>';
        exit();
    }
} else {
    echo '<div class="alert alert-warning">ID invalide.</div>';
    exit();
}

// Handle form submission (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libelle = trim($_POST['libelle']);
    $description = trim($_POST['description']);

    if (!empty($libelle) && !empty($description)) {
        try {
            $updateStmt = $pdo->prepare('UPDATE cati SET libelle = ?, description = ? WHERE id = ?');
            $success = $updateStmt->execute([$libelle, $description, $id]);

            if ($success) {
                header('Location: cataffiche.php?success=1'); // Redirect after update
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
    <title>Modifier Catégorie</title>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <h2 class="text-center my-4">Modifier Catégorie</h2>

    <form method="post">
        <label class="form-label">Libelle</label>
        <input type="text" class="form-control" name="libelle" value="<?php echo htmlspecialchars($category['libelle']); ?>" required>

        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" required><?php echo htmlspecialchars($category['description']); ?></textarea>

        <button type="submit" class="btn btn-primary mt-3">Modifier</button>
        <a href="cataffiche.php" class="btn btn-secondary mt-3">Annuler</a>
    </form>
</div>

</body>
</html>
