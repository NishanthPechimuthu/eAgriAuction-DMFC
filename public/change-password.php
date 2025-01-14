<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");
isAuthenticated();
$user_id = $_SESSION["userId"];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old_password = $_POST["oldpassword"];
  $new_password = $_POST["newpassword"];
  $con_password = $_POST["conpassword"];
  $oldPass = getUserPassword($user_id);
  if (password_verify($old_password, $oldPass)) {
    if ($new_password == $con_password) {
      $new_password = password_hash($new_password, PASSWORD_BCRYPT);
      if (updateUserPassword($user_id, $new_password)) {
        echo '
        <p class="alert alert-success alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Your Password was changed.
        </p>
    ';
      } else {
        echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Your Password was not changed.
        </p>
    ';
      }
    } else {
      echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
              Your password and confirm password are mismatched.
        </p>
    ';
    }
  } else {
    echo '
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
            Your password was incorrect. mismatched.
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
  <title>Change Password</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-lock"></i>&nbsp;
        Change Password
      </div>
      <div class="card-body">
        <form id="changePasswordForm" action="change-password.php" method="POST">
          <div class="mb-3 position-relative">
            <label for="oldpassword" class="form-label">Old Password</label>
            <input type="password" id="oldpassword" name="oldpassword" required class="form-control">
            <span class="toggle-password position-absolute" onclick="togglePassword('oldpassword', this)" style="right: 10px; top: 38px; cursor: pointer;">
              <i class="fas fa-eye"></i> <!-- Plain eye icon for hidden password -->
            </span>
          </div>
          <div class="mb-3 position-relative">
            <label for="newpassword" class="form-label">New Password</label>
            <input type="password" id="newpassword" name="newpassword" required class="form-control">
            <span class="toggle-password position-absolute" onclick="togglePassword('newpassword', this)" style="right: 10px; top: 38px; cursor: pointer;">
              <i class="fas fa-eye"></i> <!-- Plain eye icon for hidden password -->
            </span>
          </div>
          <div class="mb-3 position-relative">
            <label for="conpassword" class="form-label">Confirm Password</label>
            <input type="password" id="conpassword" name="conpassword" required class="form-control">
            <span class="toggle-password position-absolute" onclick="togglePassword('conpassword', this)" style="right: 10px; top: 38px; cursor: pointer;">
              <i class="fas fa-eye"></i> <!-- Plain eye icon for hidden password -->
            </span>
          </div>
          <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function togglePassword(fieldId, toggleElement) {
      const passwordField = document.getElementById(fieldId);
      const icon = toggleElement.querySelector('i');

      if (passwordField.type === "password") {
        passwordField.type = "text";
        // Update the icon to eye-slash
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        // Change input type to password to hide the password
        passwordField.type = "password";
        // Update the icon to eye
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>
<?php
  include_once("./footer.php");
  ob_end_flush();
?>