<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");
isAuthenticated();
$users = getUserById($_SESSION["userId"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php include_once("../assets/link.html"); ?>
  <title>Profile</title>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="bi bi-person-circle"></i>&nbsp;
        Profile
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <img class="rounded-circle border border-2 border-dark" src="../images/profiles/<?=$users["userProfileImg"] ?>" width="40" height="40" alt="Profile Not Found" />
        <a class="text-dark" href="update-profile.php">
          <i class="bi bi-pencil-square"></i>
        </a>
      </div>
      <h1 class="fw-bold mt-3"><?=$users["userFirstName"]." ".$users["userLastName"] ?></h1>
      <p class="text-secondary">
        @<?=$users["userName"] ?>
      </p>
      <br />
    <h4>Profile Infomation</h4>
    <table>
      <tr>
        <td class="d-flex">
          <i class="bi bi-envelope"></i>
          <p class="ms-2 text-secondary">
            <?=$users["userEmail"] ?>
          </p>
        </td>
      </tr>
      <tr>
        <td class="mt-0 d-flex ">
          <i class="bi bi-phone"></i>
          <p class="ms-2 text-secondary">
            <?=$users["userPhone"] ?>
          </p>
        </td>
      </tr>
      <tr>
        <td class="d-flex">
          <i class="bi bi-geo-alt"></i>
          <p class="ms-2 text-secondary">
            <?=$users["userAddress"] ?>
          </p>
        </td>
      </tr>
    </table>
  </div>
</div>
</div>
</body>
</html>
<?
  include_once("./auction-chatbot.php");
  include_once("./footer.php");
  ob_end_flush();
?>