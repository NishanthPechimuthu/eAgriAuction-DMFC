<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include_once("./assets/link.html"); ?>
</head>
<body class="bg-light">

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-2 py-0 fixed-top">
    <a class="navbar-brand" href="index.php">
      <img width="54px" height="54px" src="./images/logo/android-chrome-192x192.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="./index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./index.php#about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./index.php#contact">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./moments.php">Moments</a>
        </li>
      </ul>
      <!-- Align Login and Register buttons to the right -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link btn btn-primary text-white m-1 fw-bold" href="./public/login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-warning text-dark m-1 fw-bold" href="./public/register.php">Register</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Include Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>