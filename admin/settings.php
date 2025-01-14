<?php 
ob_start();
session_start();
include("header.php");
include("navbar.php");
// isAuthenticated();
isAuthenticatedAsAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php include_once("../assets/link.html"); ?>
  <title>Settings</title>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="bi bi-person-circle"></i>&nbsp;
        Setting
      </div>
      <div class="card-body">
        <table>
          <tr>
            <td class="d-flex ">
              <i class="bi bi-lock"></i>
              <a href="change-password.php" class="ms-2 text-secondary">Change Password</a>
            </td>
          </tr>
          <tr>
            <td class="d-flex ">
              <i class="bi bi-person"></i>
              <a href="update-profile.php" class="ms-2 text-secondary">Update Profile</a>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
<?
  include_once("./footer.php");
  ob_end_flush();
?>