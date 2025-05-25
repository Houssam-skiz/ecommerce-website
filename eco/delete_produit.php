<?php
require_once 'data.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the product
    $stmt = $pdo->prepare('DELETE FROM pro WHERE id = ?');
    $success = $stmt->execute([$id]);

    if ($success) {
        header('Location: produit.php?deleted=1'); // Redirect after delete
        exit();
    } else {
        echo '<div class="alert alert-danger">Erreur lors de la suppression.</div>';
    }
} else {
    echo '<div class="alert alert-warning">ID invalide.</div>';
}
?>
