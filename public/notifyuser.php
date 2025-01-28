<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Path to log folder and JSON file
$logFolder = 'log';
$jsonFile = "{$logFolder}/log.json";

// Ensure log folder exists
if (!file_exists($logFolder)) {
    mkdir($logFolder, 0777, true);
}

// Initialize or load JSON data
$sentNotifications = [];
if (file_exists($jsonFile)) {
    $sentNotifications = json_decode(file_get_contents($jsonFile), true);
} else {
    file_put_contents($jsonFile, json_encode($sentNotifications));
}

try {
    // Get current date
    $currentDate = date('Y-m-d H:i:s');

    // SQL Query to fetch matching auctions
    $query = "
        SELECT i.interestUserId AS userId, 
               i.interestProductType AS type, 
               i.interestKeywords AS keywords, 
               u.userEmail, 
               c.categoryName, 
               a.auctionId, 
               a.auctionTitle, 
               a.auctionDescription, 
               a.auctionEndDate,
               a.auctionStartDate
        FROM interests i
        JOIN users u ON i.interestUserId = u.userId
        JOIN categories c ON i.interestCategoryId = c.categoryId
        JOIN auctions a ON a.auctionCategoryId = c.categoryId
        WHERE a.auctionStatus = 'activate'
          AND (i.interestProductType = a.auctionProductType OR i.interestProductType = 'both')
          AND (
              i.interestKeywords IS NULL 
              OR i.interestKeywords = '' 
              OR a.auctionTitle LIKE CONCAT('%', i.interestKeywords, '%')
          )
          AND a.auctionStartDate <= :currentDate
          AND a.auctionEndDate >= :currentDate
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['currentDate' => $currentDate]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group notifications by user
    $userNotifications = [];
    foreach ($notifications as $notify) {
        $userId = $notify['userId'];
        $auctionId = $notify['auctionId'];

        // Skip if this notification was already sent
        if (isset($sentNotifications[$userId]) && in_array($auctionId, $sentNotifications[$userId])) {
            continue;
        }

        // Group notifications for each user
        if (!isset($userNotifications[$userId])) {
            $userNotifications[$userId] = [
                'email' => $notify['userEmail'],
                'auctions' => []
            ];
        }

        $userNotifications[$userId]['auctions'][] = [
            'auctionId' => $auctionId,
            'categoryName' => $notify['categoryName'],
            'auctionTitle' => $notify['auctionTitle'],
            'auctionDescription' => $notify['auctionDescription'],
            'auctionEndDate' => $notify['auctionEndDate']
        ];

        // Add auction ID to the JSON file structure
        $sentNotifications[$userId][] = $auctionId;
    }

    $emailCount = 0; // Initialize email count

    // Send emails
    foreach ($userNotifications as $userId => $userData) {
        $mail = new PHPMailer(true);

        try {
            // SMTP server configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'eagri.ct.ws@gmail.com'; // Gmail address
            $mail->Password = 'xnfkhjazsdjlsrsg'; // Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient settings
            $mail->setFrom('eagri.ct.ws@gmail.com', 'notify-eAgri Auction');
            $mail->addAddress($userData['email']); // Recipient email

            // Prepare email content
            $auctionList = '';
            foreach ($userData['auctions'] as $auction) {
                $auctionList .= "
                    <div style='background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); padding: 15px; margin-bottom: 15px; border-radius: 8px; color: #fff;'>
                        <h3>{$auction['auctionTitle']}</h3>
                        <p><strong>Category:</strong> {$auction['categoryName']}</p>
                        <p><strong>Description:</strong> {$auction['auctionDescription']}</p>
                        <p><strong>Ends On:</strong> {$auction['auctionEndDate']}</p>
                        <a href='https://eagri.ct.ws/eAuction/public/bid.php?id={$auction['auctionId']}' style='color: #ffcc00; text-decoration: none;'>View Auction</a>
                    </div>";
            }

            $mail->isHTML(true);
            $mail->Subject = 'New Auctions Related to Your Interests';
            $mail->Body = "
                <html>
                <body>
                    <div style='font-family: Arial, sans-serif;'>
                        <h2 style='text-align: center; color: #4CAF50;'>New Auctions Matching Your Interests</h2>
                        {$auctionList}
                        <p style='text-align: center;'>Thank you for using eAgri Auction!</p>
                    </div>
                </body>
                </html>
            ";

            // Send email
            if ($mail->send()) {
                $emailCount++; // Increment email count
            }
        } catch (Exception $e) {
            echo "Mailer Error for User ID {$userId}: {$mail->ErrorInfo}<br>";
        }
    }

    // Save updated notifications to JSON file
    file_put_contents($jsonFile, json_encode($sentNotifications, JSON_PRETTY_PRINT));

    // Display the results
    echo "<div class='container mt-5'>";
    echo "<h2>Notification Summary</h2>";
    echo "<p>Total Emails Sent: <strong>{$emailCount}</strong></p>";
    echo "</div>";
} catch (Exception $e) {
    echo "Error: {$e->getMessage()}<br>";
}
?>