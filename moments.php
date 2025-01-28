<?php
// Database connection
include("header.php");
$query = "SELECT * FROM moments WHERE momentStatus = 'activate'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$moments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masonry Grid</title>
  <style>
    .layout-container {
      width: min(1000px, 100%);
      margin: 0 auto;
      columns: 3 300px;
      column-gap: 1em;
    }
    img {
      display: block;
      margin-bottom: 1em;
      width: 100%;
    }
  </style>
      <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php include("./assets/link.html"); ?>
    <link rel="stylesheet" href="./assets/css/home-style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>
<body>
	<?php include("navbar.php");?>

  <div class="container py-5 mt-auto">
    <div class="layout-container">
      <?php foreach ($moments as $moment): ?>
        <img src="./images/moments/<?= htmlspecialchars($moment['momentImg']) ?>" alt="">
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
<?php include("./footer.php"); ?>