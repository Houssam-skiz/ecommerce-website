<?php include_once '../header.php'; ?>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <i class="fas fa-store me-2"></i>
      <span>Ecommerce</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fas fa-th-large me-1"></i>
            Categories
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#featured">
            <i class="fas fa-star me-1"></i>
            Featured
          </a>
        </li>
      </ul>

      <div class="d-flex align-items-center gap-3">
        <!-- Add Cart Icon -->
        <div id="cart-icon">
          <button class="btn btn-outline-primary position-relative">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              0
            </span>
          </button>
        </div>

        <div class="theme-container">
          <button class="btn-theme-toggle" onclick="toggleTheme()">
            <i id="theme-icon" class="fas fa-moon"></i>
            <span class="d-none d-md-inline">Theme</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</nav>

<style>
.navbar {
    backdrop-filter: blur(10px);
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
}

.navbar-brand {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--accent-color) !important;
}

.nav-link {
    position: relative;
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: var(--accent-color);
    transition: width 0.3s ease;
}

.nav-link:hover::after {
    width: 100%;
}

/* Cart Icon Styles */
#cart-icon {
    position: relative;
}

#cart-icon button {
    transition: all 0.3s ease;
}

#cart-icon button:hover {
    transform: translateY(-2px);
}

.badge {
    transition: all 0.3s ease;
}

#cart-icon:hover .badge {
    transform: scale(1.2);
}

@media (max-width: 768px) {
    .navbar-collapse {
        background: var(--bg-primary);
        padding: 1rem;
        border-radius: 10px;
        margin-top: 1rem;
    }
}
</style>
