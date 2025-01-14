<?php
isAuthenticatedAsAdmin();
// Pagination settings
$limit = 6;  // Number of auctions per page
$auctionPage = isset($_GET['auctionPage']) ? intval($_GET['auctionPage']) : 1;
$auctionOffset = ($auctionPage - 1) * $limit;

// Fetch auction data and auction count
$auctionDataPaginated = getAuctionData($auctionOffset, $limit);
$totalAuctions = getAuctionCount();
$auctionTotalPages = ceil($totalAuctions / $limit);

// Prepare chart data
[$auctionLabels, $auctionDatasets] = prepareChartData($auctionDataPaginated, 'auctionTitle', ['auctionStartPrice', 'highestBid'], true, 8);
?>
    <style>
        .pagination {
            margin: 0;
        }
        .pagination .page-item.active .page-link {
            background-color: #28a745;
            border-color: #28a745;
        }
        .pagination .page-link {
            color: #28a745;
        }
        .pagination .page-link:hover {
            background-color: #d4edda;
            border-color: #28a745;
        }
    </style>
<div class="col-lg-12">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-gavel me-1"></i>
            Auction Prices (Base vs. Highest)
        </div>
        <div class="card-body">
            <canvas id="auctionChart" width="100%" height="50"></canvas>
        </div>
        <div class="card-footer small text-muted">
            Updated on <?php echo getLastUpdateLabel(); ?>
        </div>
    </div>

    <!-- Pagination Controls -->
    <nav aria-label="Auction Chart Pagination">
        <ul class="pagination justify-content-center mb-4">
            <?php for ($i = 1; $i <= $auctionTotalPages; $i++) : ?>
                <li class="page-item <?php echo $i == $auctionPage ? 'active' : ''; ?>">
                    <a class="page-link <?php echo $i == $auctionPage ? 'text-white' : ''; ?>" href="?auctionPage=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Chart(document.getElementById("auctionChart").getContext("2d"), {
            type: "bar",
            data: {
                labels: <?php echo json_encode($auctionLabels); ?>,
                datasets: [
                    {
                        label: "Base Price",
                        data: <?php echo json_encode($auctionDatasets['auctionStartPrice']); ?>,
                        backgroundColor: "rgba(34, 139, 34, 0.8)",
                        borderColor: "rgba(34, 139, 34, 1)",
                        borderWidth: 2,
                    },
                    {
                        label: "Highest Bid",
                        data: <?php echo json_encode($auctionDatasets['highestBid']); ?>,
                        backgroundColor: "rgba(160, 82, 45, 0.8)",
                        borderColor: "rgba(160, 82, 45, 1)",
                        borderWidth: 2,
                    },
                ],
            },
        });
    });
</script>