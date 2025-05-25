<?php
// Make sure this is included only once
if(!defined('NAV_INCLUDED')) {
    define('NAV_INCLUDED', true);

    // Check if session has already been started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if the user is logged in
    $connect = isset($_SESSION['users']);

    // Include header
    include_once 'header.php';
?>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Ecommerce</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Ajouter Utilisateur</a>
        </li>
        
        <?php if ($connect): ?>
          <li class="nav-item">
            <a class="nav-link" href="cat.php">Ajouter Cat</a>
            <li class="nav-item">
            <a class="nav-link" href="cataffiche.php">list de Cat</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pro.php">Ajouter Pro</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="produit.php">list Pro</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="cont.php">Connexion</a>
          </li>
        <?php endif; ?>
        
      </ul>
    </div>
    <div class="theme-container">
      <button class="btn-theme-toggle" onclick="toggleTheme()">
        <i id="theme-icon" class="fas fa-moon"></i>
        <span class="d-none d-md-inline">Theme</span>
      </button>
    </div>
  </div>
</nav>
<?php } ?>