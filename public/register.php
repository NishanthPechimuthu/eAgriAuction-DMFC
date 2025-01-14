<?php
ob_start();
session_start(); // Start the session
include './header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $conpassword = $_POST['conpassword'];
  if ($password == $conpassword) {
    $registrationResult = register($username, $email, $password);
  } else {
    echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Password and Confirm Password are not matched.
        </p>
    ';
  }

  if ($registrationResult === true) {
      // Create a hidden form and submit it via POST
      echo '<form id="redirectForm" action="activate-user.php" method="POST">
              <input type="hidden" name="email" value="' . htmlspecialchars($email) . '">
            </form>';
      
      echo '<script type="text/javascript">
              document.getElementById("redirectForm").submit();
            </script>';
  } else {
    $error = $registrationResult; // Capture the error message
  }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include_once("../assets/link.html"); ?>
  <link rel="stylesheet" href="../assets/style.css" type="text/css" media="all" />
<title>Register</title>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">

<div class="row border rounded-5 p-3 bg-white shadow box-area">

<div class="col-md-6 right-box">
<div class="row align-items-center">
<div class="header-text mb-4">
<h2>Welcome,you</h2>
<p>
We are happy to choose us.
</p>
</div>
<form method="post" accept-charset="utf-8">
<div class="input-group mb-3">
<input type="email" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email address">
</div>
<div class="input-group mb-3">
<input name="username" type="text" class="form-control form-control-lg bg-light fs-6" placeholder="User Name">
</div>
<div class="input-group mb-3">
<input name="password" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password">
</div>
<div class="input-group mb-1">
<input name="conpassword" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Conform Password">
</div>
<div class="input-group mb-5 d-flex justify-content-between">
</div>
<div class="input-group mb-3">
<input type="submit" value="Register"
class="text-white fw-bold btn btn-lg w-100 fs-6"
style="background: linear-gradient(45deg, rgba(1,255,202,1) 0%, rgba(204,255,0,1) 100%);">
</div>
</form>
<div class="row">
<small>Already have account? <a href="login.php">Log in</a></small>
</div>
</div>
</div>

<div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: rgb(1,255,202);background: linear-gradient(45deg, rgba(204,255,0,1) 0%, rgba(1,255,202,1) 100%);">
<div class="featured-image mb-3">
<img src="../images/logo/1.png" class="img-fluid" style="width: 250px;">
</div>
<p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 800;color:#000000;">
Be Verified
</p>
<small class="text-dark text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Make the auction genuine.</small>
</div>

</div>
</div>

</body>
</html>