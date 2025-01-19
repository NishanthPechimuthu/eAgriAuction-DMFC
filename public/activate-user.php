<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// check if the email POST
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])){
  $email=$_POST["email"];
  $mail = new PHPMailer(true);
  $user=getUserByEmail($email);
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
      $mail->Subject = 'Account Activation Request'; // Email subject
  
      // Generate activation link dynamically
      $token = bin2hex(random_bytes(16));
      if (createUserActivateToken($user['userId'], $token)) {
          $activationLink = "https://eagri.ct.ws/eAuction/public/activate-user.php?user=" . $user['userId'] . "&token=" . $token;
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
                    .activation-link {
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #28a745;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 16px;
                        text-align: center;
                    }
                    .activation-link:hover {
                        background-color: #218838;
                    }
                </style>
            </head>
            <body>
                <div class='container py-5'>
                    <div class='header'>
                        <h1>Account Activation</h1>
                    </div>
                    <div class='content'>
                        <p>Hello,</p>
                        <p>Thank you for registering with eAgri Auction. Please click the button below to activate your account:</p>
                        <a href=\"$activationLink\" class='activation-link'>Activate Account</a>
                        <p>If you didn't register, please ignore this email.</p>
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
                      Activation email has been sent successfully.
                  </p>
            ';
          } else {
              echo '
                <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
                   role="alert" data-bs-dismiss="alert"
                   aria-label="Close"
                   style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                      Failed to send activation email. Error: ' . $mail->ErrorInfo . '
                  </p>
            ';
          }
      } else {
          echo '
              <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
                 role="alert" data-bs-dismiss="alert"
                 aria-label="Close"
                 style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                    Failed to create user activation token.
                </p>
          ';
      }
  } catch (Exception $e) {
      echo '
            <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
               role="alert" data-bs-dismiss="alert"
               aria-label="Close"
               style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                  Activation email could not be sent. Mailer Error: ' . $mail->ErrorInfo . '</p>
        ';
  }
}

// Check if the 'Activa' GET parameter is set
if (isset($_GET['user'])&&isset($_GET['token'])) {
  $user_id = $_GET['user'] ?? null;
  $token = $_GET['token'] ?? null;

  if ($user_id && $token) {
    if (validateUserActivate($user_id, $token)) {
      if (activateUser($user_id, $token)) {
        updateUserActivateToken($user_id, $token);
        echo '
          <div class="container py-5 text-center">
            <i class="fa fa-check-circle text-success" style="font-size: 5rem;"></i>
            <h2 class="text-success mt-3">Account Successfully Activated</h2>
            <p class="mt-3 text-justify">
              Your account has been successfully activated. You can now log in to your account and start using our services.
            </p>
            <a href="login.php" class="btn btn-primary mt-3">Go to Login</a>
          </div>
        ';
      } else {
        echo '
          <div class="container py-5 text-center">
            <i class="fa fa-times-circle text-danger" style="font-size: 5rem;"></i>
            <h2 class="text-danger mt-3">Activation Failed</h2>
            <p class="mt-3 text-justify">
              The activation link is invalid or expired. Please check your email for a valid link.
            </p>
          </div>
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
  } else {
    echo '
      <div class="container py-5 text-center">
        <i class="fa fa-exclamation-circle text-warning" style="font-size: 5rem;"></i>
        <h2 class="text-warning mt-3">Activation Error</h2>
        <p class="mt-3 text-justify">
          Missing activation parameters. Please check your email for the activation link.
        </p>
      </div>
    ';
  }
  exit(); // End script execution here
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Activation</title>
  <?php include_once("../assets/link.html"); ?>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .text-justify {
      text-align: justify;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white text-center">
        <i class="fa fa-envelope"></i>&nbsp;
        Account Activation
      </div>
      <div class="card-body text-center">
        <i class="fa fa-paper-plane text-primary" style="font-size: 5rem;"></i>
        <h2 class="mt-3">Activation Email Sent</h2>
        <p class="mt-3 text-justify">
          We have sent an activation email to your registered email address. Please check your inbox or spam folder to activate your account.
        </p>
        <p class="text-muted mt-2">
          After activation, you can log in to your account and start using our services.
        </p>
      </div>
    </div>
  </div>
</body>
</html>
<?
  include_once("./footer.php");
  ob_end_flush();
?>