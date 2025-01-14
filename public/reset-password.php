<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user_id and token are present in the GET parameters
if (isset($_GET['user']) && isset($_GET['token'])) {
  $user_id = $_GET['user'];
  $token = $_GET['token'];
} else {
  echo '
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Invalid reset link.
        </p>
      ';
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_POST['user_id'];
  $token = $_POST['token'];
  $new_password = $_POST["newpassword"];
  $con_password = $_POST["conpassword"];

  if (validateResetToken($user_id, $token)) {
    if ($new_password === $con_password) {
      // Hash the password and update in the database
      $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
      if (updateUserPassword($user_id, $hashed_password)) {
        updatePassResetToken($user_id, $token);
        echo '
        <p class="alert alert-success alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Your password has been successfully updated.
        </p>
      ';
      } else {
        echo '
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Failed to update password. Please try again.
        </p>
      ';
      }
    } else {
      echo '
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Passwords do not match.
        </p>
      ';
    }
  } else {
    echo '
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Invalid or expired reset link.
        </p>
      ';
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-key"></i>&nbsp;
        Reset Password
      </div>
      <div class="card-body">
        <?php if (validateResetToken($user_id, $token)): ?>
        <form action="reset-password.php?user=<?=$user_id ?>&token=<?=$token ?>" method="POST">
          <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
          <div class="mb-3">
            <label for="newpassword" class="form-label">New Password</label>
            <input type="password" id="newpassword" name="newpassword" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="conpassword" class="form-label">Confirm Password</label>
            <input type="password" id="conpassword" name="conpassword" required class="form-control">
          </div>
          <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
        <?php else : ?>
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
          role="alert" data-bs-dismiss="alert"
          aria-label="Close"
          style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Invalid or Expired Reset link.
        </p>
        <a href="forgot-password.php" class="btn btn-primary rounded rounded-pill w-100 fw-blod text-white mt-2">
          Resend Link
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
<?
  include_once("./footer.php");
  ob_end_flush();
?>