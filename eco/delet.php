<?php
require_once 'data.php';

// Check if 'id' is set and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $pdo->prepare('DELETE FROM cati WHERE id = ?');
    $success = $stmt->execute([$id]);

    if ($success) {
        header('Location: cataffiche.php'); // Redirect to the list page
        exit();
    } else {
        echo '<div class="alert alert-danger">Erreur lors de la suppression.</div>';
    }
} else {
    echo '<div class="alert alert-warning">ID invalide.</div>';
}
?>
