<?php
ob_start();
session_start(); // Start the session
include("header.php");
include("navbar.php");

// Call the authentication function
isAuthenticated();

// Fetch active auctions
$auctions = getActiveAuctions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Auctions</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-balance-scale"></i>&nbsp;
        Ongoing Auctions
      </div>
      <div class="card-body">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
          hello
          <?php foreach ($auctions as $auction): ?>
          <div class="col mb-4">
            <div class="card shadow">
              <div class="position-relative">
                <!-- Container for the badges -->
                <div class="d-flex justify-content-between position-absolute top-0 start-0 w-100 p-2">
                  <!-- Badge for category -->
                  <span class="badge bg-info">
                    <i class="bi bi-tag"></i> <?= $categ = getCategoryById($auction["auctionCategoryId"]); ?>
                  </span>
                </div>
            
                <img class="shadow-sm card-img-top rounded-2" src="../images/products/<?=$auction['auctionProductImg'] ?>" alt="Product Image">
              </div>
              <div class="card-body">
                <table class="table table-sm table-borderless">
                  <tr>
                    <td colspan="4">
                      <h5 class="card-title text-primary mt-1">
                        <?php 
                          $title = htmlspecialchars($auction['auctionTitle']);
                          echo strlen($title) > 30 ? substr($title, 0, 30) . "..." : $title; 
                        ?>
                      </h5>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"><i class="fa fa-coins"></i>&nbsp;&nbsp;<b>&#8377;&nbsp;</b><?= htmlspecialchars($auction['auctionStartPrice']) ?></td>
                    <td colspan="2"><i class="fa fa-line-chart"></i>&nbsp;&nbsp;<b>&#8377;&nbsp;</b><?= (getHighestBid($auction["auctionId"]) === 0) ? 'not yet.' : getHighestBid($auction["auctionId"]);?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><i class="fa fa-balance-scale"></i>&nbsp;&nbsp;<?="<b>&nbsp;</b>" . htmlspecialchars($auction['auctionProductQuantity'])." ".htmlspecialchars($auction['auctionProductUnit']) ?></td>
                    <td colspan="2"><i class="fa fa-vial"></i>&nbsp;&nbsp;<?="&nbsp;" . htmlspecialchars($auction['auctionProductType'])?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><i class="fa fa-hourglass-start"></i>&nbsp;&nbsp;<?= htmlspecialchars($auction['auctionEndDate']) ?></td>
                  </tr>
                </table>
                <a href="bid.php?id=<?= $auction['auctionId'] ?>" class="btn btn-primary">Place Bid</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?
  include_once("./auction-chatbot.php");
  include_once("./footer.php");
  ob_end_flush();
?>