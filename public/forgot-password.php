<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");
// Include PHPMailer classes
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  // Get the user from the database
  $user = getUserByEmail($email);
  if ($user) {
    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
      // Disable debug output (set to 0)
      $mail->SMTPDebug = 0; // Set to 0 for no debug output

      // SMTP server configuration
      $mail->isSMTP(); // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com'; // SMTP server
      $mail->SMTPAuth = true; // Enable SMTP authentication
      $mail->Username = 'eagri.ct.ws@gmail.com'; // Gmail address
      $mail->Password = 'xnfkhjazsdjlsrsg'; // Gmail app password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption protocol
      $mail->Port = 587; // TCP port for Gmail

      // Sender and recipient settings
      $mail->setFrom('eagri.ct.ws@gmail.com', 'eAgri Auction'); // Sender email and name
      $mail->addAddress($email); // Recipient email from form

      // Email content
      $mail->isHTML(true); // Enable HTML format
      $mail->Subject = 'Password Reset Request'; // Email subject

      // Generate reset link dynamically
      $token = bin2hex(random_bytes(16));
      if (createPassResetToken($user['userId'], $token)) {
        $resetLink = "https://eagri.ct.ws/eAuction/public/reset-password.php?user=" . $user['userId'] . "&token=" . $token;
        $mail->Body = "
          <html>
          <head>
              <style>
                  body {
                      font-family: Arial, sans-serif;
                      background-color: #f4f4f4;
                      color: #333;
                      margin: 0;
                      padding: 0;
                  }
                  .container {
                      max-width: 600px;
                      margin: 50px auto;
                      background-color: #ffffff;
                      padding: 20px;
                      border-radius: 8px;
                      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                  }
                  .header {
                      text-align: center;
                      margin-bottom: 20px;
                  }
                  .header h1 {
                      font-size: 24px;
                      color: #333;
                  }
                  .content {
                      font-size: 16px;
                      line-height: 1.6;
                      color: #555;
                      margin-bottom: 20px;
                  }
                  .reset-link {
                      display: inline-block;
                      padding: 12px 24px;
                      background-color: #007bff;
                      color: #ffffff;
                      text-decoration: none;
                      border-radius: 4px;
                      font-size: 16px;
                      text-align: center;
                  }
                  .reset-link:hover {
                      background-color: #0056b3;
                  }
              </style>
          </head>
          <body>
              <div class='container py-5'>
                  <div class='header'>
                      <h1>Password Reset Request</h1>
                  </div>
                  <div class='content'>
                      <p>Hello,</p>
                      <p>We received a request to reset your password. Click the button below to reset your password:</p>
                      <a href=\"$resetLink\" class='reset-link'>Reset Password</a>
                      <p>If you didn't request a password reset, please ignore this email.</p>
                      <p>Thank you,</p>
                      <p>eAgri Auction</p>
                  </div>
              </div>
          </body>
          </html>
          ";

        // Send email
        if ($mail->send()) {
          echo '
            <p class="alert alert-success alert-dismissible fade show d-flex align-items-center"
               role="alert" data-bs-dismiss="alert"
               aria-label="Close"
               style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                  Email has been sent successfully.
              </p>
        ';

        } else {
          echo '
            <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
               role="alert" data-bs-dismiss="alert"
               aria-label="Close"
               style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                  Failed to send email. Error: ' . $mail->ErrorInfo .'
              </p>
        ';

        }
      } else {
        echo '
            <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
               role="alert" data-bs-dismiss="alert"
               aria-label="Close"
               style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                  Failed to create password reset token.
              </p>
        ';

      }
    } catch (Exception $e) {
      echo '
            <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
               role="alert" data-bs-dismiss="alert"
               aria-label="Close"
               style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                  Email could not be sent. Mailer Error: '.$mail->ErrorInfo.'</p>
        ';

    }
  } else {
    echo '
            <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
               role="alert" data-bs-dismiss="alert"
               aria-label="Close"
               style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                  No user found with that email address.
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
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Password Reset</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-lock"></i>&nbsp;
        Forgot Password
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" required class="form-control">
          </div>
          <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
<?
  include_once("./footer.php");
  ob_end_flush();
?>