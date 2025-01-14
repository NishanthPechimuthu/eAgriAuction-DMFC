<?php
ob_start(); // Start output buffering

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection setup (replace with your actual connection details)
include("header.php");

// If the request is POST, handle the chatbot query
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Force JSON response header
    header('Content-Type: application/json; charset=utf-8');

    $query = $_POST['query'] ?? '';

    // Check if query is empty
    if (empty($query)) {
        echo json_encode(["success" => false, "message" => "Please enter a search query."]);
        exit();
    }

    try {
        $response = getAuctionResults($query);
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
    exit();
}

// Function to get auction results based on the query
function getAuctionResults($query) {
    global $pdo;

    // Check if $pdo is initialized
    if (!isset($pdo)) {
        throw new Exception("Database connection is not established.");
    }

    // Sanitize input
    $query = trim($query);

    // First, search for auctions with a direct match or partial match
    $stmt = $pdo->prepare("
        SELECT * 
        FROM auctions 
        WHERE (auctionTitle LIKE :query OR auctionDescription LIKE :query)
          AND auctionEndDate >= NOW() 
          AND auctionStartDate <= NOW()
        ORDER BY auctionTitle ASC 
        LIMIT 5
    ");
    $stmt->execute(['query' => "%$query%"]);
    $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($auctions) > 0) {
        return ["success" => true, "auctions" => $auctions];
    } 

    // Fallback: Search for auctions using SOUNDEX (approximate match)
    $stmt = $pdo->prepare("
        SELECT * 
        FROM auctions 
        WHERE SOUNDEX(auctionTitle) = SOUNDEX(:query)
          AND auctionEndDate >= NOW() 
        LIMIT 5
    ");
    $stmt->execute(['query' => $query]);
    $similarAuctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($similarAuctions) > 0) {
        return [
            "success" => true,
            "message" => "No exact matches found, but here are some similar results.",
            "auctions" => $similarAuctions
        ];
    }

    return ["success" => false, "message" => "No auctions found for your query."];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Chatbot</title>
    <?php include("../assets/link.html"); ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        .floating-button button {
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .chatbot-container {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 350px;
            height: 500px;
            border: 1px solid #ccc;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: none;
            flex-direction: column;
            z-index: 1000;
        }
        .chatbot-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .chatbot-messages {
            padding: 10px;
            flex-grow: 1;
            overflow-y: auto;
            font-size: 14px;
            max-height: 400px;
        }
        .chatbot-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
        }
        .chatbot-input input {
            flex-grow: 1;
            border-radius: 4px;
        }
        .chat-message {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .chat-message img {
            height: 32px;
            width: 32px;
            border-radius: 50%;
            margin-right: 10px;
            border: 1px solid #000000;
        }
        .chat-message.bot img {
            margin-left: 5px;
        }
        .chat-message.bot {
            justify-content: flex-start;
            text-align: left;
        }
        .chat-message.user {
            justify-content: flex-end;
            text-align: right;
        }
        .chat-message.bot div {
            background-color: #d4f8d4;
            color: black;
            border-radius: 15px;
            padding: 10px;
            max-width: 70%;
            display: inline-block;
        }
        .chat-message.user div {
            background-color: #d0e9ff;
            color: black;
            border-radius: 15px;
            padding: 10px;
            max-width: 70%;
            display: inline-block;
        }
        /* Adjusted card styles for the chatbot */
        .card-custom {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }
        .card-custom .card-body {
            padding: 10px;
            font-size: 12px;
        }
        .card-custom .card-title {
            font-size: 14px;
            color: #007bff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card-custom img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .card-custom .badge {
            font-size: 12px;
        }
    </style>
</head>
<body>

<!-- Chatbot Floating Button -->
<div id="chatbot-toggle" class="floating-button">
    <button class="btn btn-primary">
        <i class="bi bi-robot"></i>
    </button>
</div>

<!-- Chatbot Container -->
<div id="chatbot-container" class="chatbot-container">
    <div class="chatbot-header">
        Vendor Chatbot
    </div>
    <div id="chatbot-messages" class="chatbot-messages">
        <div class="chat-message bot">
            <img src="../images/profiles/bot.webp" alt="blk">
            <div><strong>blk:</strong> How can I help you today?</div>
        </div>
    </div>
    <div class="chatbot-input">
        <input type="text" id="user-query" class="form-control" placeholder="Ask about an auction..." />
        <button id="send-query" class="btn btn-primary ms-2">Send</button>
    </div>
</div>

<!-- Bootstrap JS (requires Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    const chatbotToggle = $('#chatbot-toggle');
    const chatbotContainer = $('#chatbot-container');
    const chatbotMessages = $('#chatbot-messages');
    const sendQueryButton = $('#send-query');
    const userQueryInput = $('#user-query');

    // Toggle chatbot visibility
    chatbotToggle.on('click', function () {
        chatbotContainer.toggle();
    });

    // Send user query to the chatbot
    sendQueryButton.on('click', function () {
        const query = userQueryInput.val().trim();

        if (!query) {
            chatbotMessages.append(`
                <div class="chat-message bot">
                    <img src="../images/profiles/bot.webp" alt="blk">
                    <div><strong>blk:</strong> Please enter a valid query.</div>
                </div>
            `);
            chatbotMessages.scrollTop(chatbotMessages[0].scrollHeight);
            return;
        }

        chatbotMessages.append(`
            <div class="chat-message user">
                <img src="../images/profiles/<?php echo $_SESSION["userProfileImg"] ?? "profile.webp";?>" alt="User">
                <div><strong>You:</strong> ${query}</div>
            </div>
        `);
        userQueryInput.val('');

        $.ajax({
            url: 'auction-chatbot.php', // This file handles the request
            type: 'POST',
            data: { query: query },
            dataType: 'json',
          success: function (response) {
    if (response.success) {
        if (response.auctions && response.auctions.length > 0) {
response.auctions.forEach(function (auction) {
    chatbotMessages.append(`
        <div class="card card-custom">
            <div class="position-relative">
                <div class="d-flex justify-content-between position-absolute top-0 start-0 w-100 p-2">
      
                </div>
                <img class="shadow-sm card-img-top rounded-2" src="../images/products/${auction.auctionProductImg}" alt="Product Image">
            </div>
            <div class="card-body">
                <h5 class="card-title text-primary mt-1">${auction.auctionTitle}</h5>
                <p><b><i class="fas fa-rupee-sign"></i> </b>${auction.auctionStartPrice}</p>
                <p><b><i class="fa fa-balance-scale"></i> </b> ${auction.auctionProductQuantity} ${auction.auctionProductUnit}</p>
                <a href="bid.php?id=${auction.auctionId}" class="btn btn-primary">Place Bid</a>
            </div>
        </div>
    `);
});
        } else {
            chatbotMessages.append(`
                <div class="chat-message bot">
                    <img src="../images/profiles/bot.webp" alt="blk">
                    <div><strong>blk:</strong> ${response.message || "No auctions found."}</div>
                </div>
            `);
        }
    } else {
        chatbotMessages.append(`
            <div class="chat-message bot">
                <img src="../images/profiles/bot.webp" alt="blk">
                <div><strong>blk:</strong> ${response.message}</div>
            </div>
        `);
    }

    chatbotMessages.scrollTop(chatbotMessages[0].scrollHeight);
},
            error: function (xhr, status, error) {
                chatbotMessages.append(`
                    <div class="chat-message bot">
                        <img src="../images/profiles/bot.webp" alt="blk">
                        <div><strong>blk:</strong> Something went wrong. Please try again.</div>
                    </div>
                `);
                console.error('Error:', error);
                chatbotMessages.scrollTop(chatbotMessages[0].scrollHeight);
            }
        });
    });

    // Send the query when the "Enter" key is pressed
    userQueryInput.on('keypress', function (e) {
        if (e.which === 13) {
            sendQueryButton.click();
        }
    });
});
</script>

</body>
</html>