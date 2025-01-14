<?php
session_start(); // Start the session

// Check if user_id session is set
if (isset($_SESSION["userId"]) && $_SESSION["userId"] != NULL) {
  header("Location: auctions.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Auction Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="text-center">
      <h1 class="display-4 fw-bold">Welcome to Online Auction</h1>
      <p class="mt-3">
        Bid on your favorite items and win exciting deals.
      </p>
      <div class="mt-4">
        <a href="register.php" class="text-primary">Register</a> |
        <a href="login.php" class="text-primary">Login</a>
      </div>
    </div>
  </div>
</body>
</html>