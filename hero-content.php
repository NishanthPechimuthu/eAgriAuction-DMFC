<?php
  include("header.php");
  $id = base64_decode($_GET["id"]);
  $hero = getHeroById($id);
  $hero = $hero[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?=$hero["heroTitle"]?></title>
 <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php include("./assets/link.html"); ?>
    <link rel="stylesheet" href="./assets/css/home-style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>
<body>
<?php   include("navbar.php"); ?>
  <div class="container mt-5">
    <div class="content mt-5">
      <div class="row">
        <!-- Image Section with rounded corners -->
        <div class="col-md-6 center">
          <img src="./images/heroes/<?=$hero['heroImg']?>" class="img-fluid rounded-1" alt="Hero Image">
        </div>
        <!-- Content Section -->
        <div class="col-md-6 mb-1">
          <div class="m-2 card min-vh-100">
            <div class="card-header">
          <h2><?=$hero["heroTitle"]?></h2>
            </div>
  <div class="card-body">
    <?= $hero["heroContent"]; ?>
  </div>
</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<?php include("footer.php"); ?>