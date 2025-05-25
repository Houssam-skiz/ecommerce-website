<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Liste des Catégories</title>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container">
    <h2 class="text-center my-4">Liste des Catégories</h2>
    <a href="cat.php" class="btn btn-primary">ajouter Catégories</a>

    <?php
    require_once 'data.php';
    $ex = $pdo->query('SELECT * FROM cati')->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Libelle</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ex as $ex1) { ?>
                <tr>
                    <td><?php echo $ex1['id']; ?></td>
                    <td><?php echo $ex1['libelle']; ?></td>
                    <td><?php echo $ex1['description']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($ex1['date'])); ?></td> <!-- Show only date -->
                    <td>
                    <a href="edit.php?id=<?php echo $ex1['id']; ?>" 
   class="btn btn-primary"
   onclick="return confirm('Voulez-vous modifier cette catégorie ?');">
   Modifier
</a>

<a href="delet.php?id=<?php echo $ex1['id']; ?>" 
   class="btn btn-danger"
   onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');">
   Supprimer
</a>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
