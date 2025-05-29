<?php
// Start session and include config
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
?>

<!-- Ensure these are in your <head> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Responsive Bootstrap Navbar Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-light py-2 shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="main.php">
      <img src="images\finallogo.png" alt="Furrfect Pawshop Logo" style="height:48px; width:auto;" class="me-2">
      <span class="fw-bold" style="color:#a67b5b; font-size:1.3rem;">Furrfect Pawshop</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a href="main.php" class="nav-link px-3 fs-4">Home</a></li>
        <li class="nav-item"><a href="products.php" class="nav-link px-3 fs-4">Products</a></li>
        <li class="nav-item"><a href="services.php" class="nav-link px-3 fs-4">About</a></li>
        <li class="nav-item"><a href="contact.php" class="nav-link px-3 fs-4">Contact</a></li>
      </ul>

      <div class="d-flex align-items-center ms-lg-3 mt-3 mt-lg-0">
        <!-- Cart Button -->
        <a href="cart.php" class="btn btn-outline-secondary position-relative me-2 fs-4" id="cartBtn">
          🛒
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
            <?php
            echo (isset($_SESSION['cart']) && is_array($_SESSION['cart']))
                ? array_sum(array_column($_SESSION['cart'], 'quantity'))
                : 0;
            ?>
          </span>
        </a>

        <!-- User Profile or Login -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="dropdown">
            <button class="btn btn-outline-secondary ms-2 fs-4" type="button" id="profileDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-secondary ms-2 fs-4">
            <i class="fas fa-sign-in-alt"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- Ensure this is before </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
