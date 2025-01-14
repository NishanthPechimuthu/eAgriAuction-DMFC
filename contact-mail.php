<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("header.php");
// Include PHPMailer classes
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = htmlspecialchars($_POST["full_name"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '22ct19nishanth@gmail.com'; // Your email
        $mail->Password = 'aynqaraezfiltmwh'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email settings
        $mail->setFrom('22ct19nishanth@gmail.com', 'eAgriAuction Support');
        $mail->addAddress('nishanthpechimuthu@gmail.com'); // Your email to receive messages
        $mail->addReplyTo($email, $fullName);

        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission from $fullName";
        $mail->Body = "
            <h3>Contact Details</h3>
            <p><strong>Name:</strong> $fullName</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>$message</p>
        ";

        if ($mail->send()) {
            echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        text: 'Thank you for reaching out. We will get back to you shortly.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location = 'index.php';
                    });
                };
            </script>";
        } else {
            echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Send Message',
                        text: 'There was an error sending your message. Please try again later.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location = 'index.php';
                    });
                };
            </script>";
        }
    } catch (Exception $e) {
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Occurred',
                    text: 'Mailer Error: {$mail->ErrorInfo}',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location = 'index.php';
                });
            };
        </script>";
    }
}
?>