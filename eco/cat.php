<?php
session_start();
// Check if user is logged in, if not redirect to login page
if(!isset($_SESSION['users'])) {
    header("Location: cont.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Category</title>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
    <h4>Add Category</h4>
    <?php
    if(isset($_POST['ajouter'])){
        $libelle = $_POST['libelle'];
        $description = $_POST['description'];
        
        if(!empty($libelle) && !empty($description)) {
            require_once 'data.php';
            
            $insert = $pdo->prepare('INSERT INTO cati (libelle,description) VALUES (?, ?)');
            $result = $insert->execute([$libelle, $description]);
            
            if($result) {
                echo '<div class="alert alert-success" role="alert">Category added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error adding category.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">All fields are required!</div>';
        }
    }
    ?>
    
    <form method="post">
        <label class="form-label">Libelle</label>
        <input type="text" class="form-control" name="libelle" required>
        
        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" required></textarea>
        
        <button type="submit" class="btn btn-primary mt-3" name="ajouter">Add Category</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>