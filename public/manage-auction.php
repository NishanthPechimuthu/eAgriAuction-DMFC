<?php
ob_start(); // Start output buffering

include("header.php");
include("navbar.php");

// Call the authentication function
isAuthenticated();

// Retrieve user ID from the session
$user_id = $_SESSION['userId']; // Assuming 'user_id' is stored in the session
$auctions = getUsersAuctions();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['delete'])) {
    deleteAuction($_POST['auction_id']);
    header("Location: manage-auction.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <?php include_once("../assets/link.html"); ?>
    <link href="../assets/css/table-styles.css" rel="stylesheet" />
    <style>
        td {
            height: 50px;
            line-height: 50px;
        }
        td, th {
            min-width: 100px;
            max-width: 140px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            overflow: auto;
            padding: 10px;
        }
        @media (max-width: 768px) {
            td{
                height: 40px;
                line-height: 40px;
            }
            th, td {
                font-size: 12px;
                padding: 5px;
            }
            td img {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-cogs me-1"></i> Manage Auctions
            </div>
            <div class="card-body">
                <table id="auctionsTable">
                    <thead>
                        <tr>
                            <th>S/No</th>
                            <th>Title</th>
                            <th>Profile</th>
                            <th>Base Price (₹)</th>
                            <th>High Price (₹)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>S/No</th>
                            <th>Title</th>
                            <th>Profile</th>
                            <th>Base Price (₹)</th>
                            <th>High Price (₹)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($auctions as $auction): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= htmlspecialchars($auction['auctionTitle']) ?></td>
                                <td>
                                    <img src="../images/products/<?= htmlspecialchars($auction['auctionProductImg']) ?>" alt="Product Image" class="rounded-1 border border-dark" width="50" height="50">
                                </td>
                                <td><?= htmlspecialchars($auction['auctionStartPrice']) ?></td>
                                <td><?= htmlspecialchars(getHighestBid($auction['auctionId'])) ?></td>
                                <td><?= (new DateTime($auction['auctionStartDate']))->format('d/m/Y') ?></td>
                                <td><?= (new DateTime($auction['auctionEndDate']))->format('d/m/Y') ?></td>
                                <td>
                                    <p class="badge rounded-pill <?= $auction['auctionStatus'] === 'activate' ? 'bg-success text-white' : ($auction['auctionStatus'] === 'deactivate' ? 'bg-warning text-dark' : 'bg-danger text-white') ?> m-0">
                                        <?= htmlspecialchars($auction['auctionStatus']) ?>
                                    </p>
                                </td>
                                <td>
                                    <a href="bid.php?id=<?= $auction['auctionId'] ?>" class="btn btn-info btn-sm fw-bold text-white align-items-center">View</a>
                                </td>
                                <td>
                                    <a href="edit-auction.php?auctionId=<?= htmlspecialchars($auction['auctionId']) ?>" class="btn btn-warning btn-sm fw-bold text-dark">Edit</a>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="auction_id" value="<?= htmlspecialchars($auction['auctionId']) ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm fw-bold text-white" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>
    window.addEventListener('DOMContentLoaded', event => {
        const datatablesSimple = document.getElementById('auctionsTable');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple);
        }
    });
</script>
</body>
</html>

<?php
include_once("./auction-chatbot.php");
include_once("./footer.php");
ob_end_flush();
?>