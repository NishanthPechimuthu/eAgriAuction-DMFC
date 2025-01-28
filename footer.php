<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("./assets/link.html"); ?>
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
    }
    footer {
      margin-top: auto;
    }
  </style>
</head>
<body>
  <div class="d-flex flex-column flex-grow-1">
    <!-- Content goes here -->
  </div>

  <!-- Footer -->
  <footer class="container-fluid bg-dark text-white pt-5 pb-4 px-5">
    <div class="text-left text-md-left">
      <div class="row justify-content-start">
        <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold text-success">e-Agri Auction</h5>
          <p>E-Agri Auction is an online platform that connects farmers directly with wholesalers through e-auctions. This helps farmers get better prices for their produce by bypassing middlemen, while wholesalers can easily access fresh, quality products.</p>
        </div>
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold text-success">Products</h5>
          <p><a href="./public/auctions.php" class="text-white" style="text-decoration: none;">Auctions</a></p>
          <p><a href="./public/add-auction.php" class="text-white" style="text-decoration: none;">Add Auctions</a></p>
          <p><a href="./public/manage-auction.php" class="text-white" style="text-decoration: none;">Manage Auction</a></p>
        </div>
        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold text-success">Useful links</h5>
          <p><a href="./public/profile.php" class="text-white" style="text-decoration: none;">Your Account</a></p>
          <p><a href="./public/revisited-auctions.php" class="text-white" style="text-decoration: none;">Your Participated Auctions</a></p>
          <p><a href="#" class="text-white" style="text-decoration: none;">Help</a></p>
        </div>
        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold text-success">Contact</h5>
          <p><i class="fas fa-home mr-3"></i>&nbsp;&nbsp;<a class="text-decoration-none text-white"  href="https://www.google.com/maps/dir/?api=1&destination=M6H6%2B5F9%2C+Somavarapatti%2C+Tamil+Nadu">Tiruppur, Tamil Nadu, India - 642205</a></p>
          <p><i class="fas fa-envelope mr-3"></i>&nbsp;&nbsp;<a class="text-decoration-none text-white" href="mailto:eagri.ct.ws@gmail.com">eagri.ct.ws@gmail.com</a></p>
          <p><i class="fas fa-phone mr-3"></i>&nbsp;&nbsp;<a class="text-decoration-none text-white" href="tel:+918015864344">+91 8015864344</a></p>
        </div>
      </div>
      <hr class="mb-4">
      <div class="row justify-content-start align-items-center">
        <div class="col-md-7 col-lg-8">
          <p>Copyright &copy; 2024 - 2025 All rights reserved by:
            <a href="mailto:eagri.ct.ws@gmail.com" style="text-decoration: none;">
              <strong class="text-success">eAgri[NSCBG]</strong>
            </a>
          </p>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>