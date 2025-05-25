<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Connection</title>
</head>
<body>
<?php
session_start();
include 'nav.php';
?>

<div class="container">
<?php
   
   require_once 'data.php';

   if(isset($_POST['con'])){
       $login = $_POST['login'];
       $password = $_POST['password'];

       if (!empty($login) && !empty($password)) {
           $sql = $pdo->prepare('SELECT * FROM users WHERE login = ? AND password = ?');
           $sql->execute([$login, $password]);

           if($sql->rowCount() >= 1){
               $_SESSION['users'] = $sql->fetch();
               header('Location: admin.php');
               exit();
           } else {
               echo '<div class="alert alert-danger">Incorrect login or password!</div>';
           }
       } else {
           echo '<div class="alert alert-danger">All fields are required!</div>';
       }
   }
   ?>
    
    <h4>Connection</h4>
    <form method="post">
        <label class="form-label">Login</label>
        <input type="text" class="form-control" name="login" required>
        
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
        
        <button type="submit" class="btn btn-primary mt-3" name="con">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>