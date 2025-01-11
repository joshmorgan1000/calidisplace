<?php
// index.php
require_once 'init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>California Fires Shelter Assistance</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Cali Fires Shelter</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <!-- Not logged in: show Login & Register -->
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php else: ?>
          <!-- Logged in: show Offer Home, Manage Listings, Logout -->
          <li class="nav-item"><a class="nav-link" href="offer_home.php">Offer Home</a></li>
          <li class="nav-item"><a class="nav-link" href="manage_listings.php">Manage Listings</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container my-5">
    <h1 class="text-center">California Fires Shelter Assistance</h1>
    <p class="text-center lead">
        Welcome! If you've been displaced by the fires in California, or if you want 
        to offer your home to those in need, explore the options below.
    </p>

    <div class="d-flex justify-content-center flex-wrap">
        <a href="find_home.php" class="btn btn-success m-2">Find a Home</a>
        <a href="request_home.php" class="btn btn-warning m-2">Request a Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="offer_home.php" class="btn btn-primary m-2">Offer a Home</a>
            <a href="manage_listings.php" class="btn btn-info m-2">Manage Listings</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-secondary m-2">Login to Offer a Home (Register Top Right)</a>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
</body>
</html>
