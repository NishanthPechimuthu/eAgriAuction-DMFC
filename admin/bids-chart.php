<?php
isAuthenticatedAsAdmin();
// Fetch current page and calculate offset
$auctionPage = isset($_GET['auctionPage']) ? max(1, (int)$_GET['auctionPage']) : 1;
$itemsPerPage = 7; // Number of days per page
$offset = ($auctionPage - 1) * $itemsPerPage;

// Get paginated data and total page count
list($data, $auctionTotalPages) = getPaginatedBidData($itemsPerPage, $offset);
$bidData = array_reverse($data);

// Prepare the labels for the selected 7 days
$labels = array_column($bidData, 'bidDate');

// Prepare the bid data for chart
[$bidLabels, $bidDatasets] = prepareChartData($bidData, 'bidDate', ['maxBid', 'totalBid']);

$totalBidAmount = array_sum(array_column($bidData, 'totalBid')); // Sum of all total bids for the selected page
$maxBidSum = array_sum(array_column($bidData, 'maxBid')); // Sum of max bids for the selected page
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
            <i class="fas fa-chart-bar me-1"></i>
            Bids (7 Days)
        </div>
        <div class="card-body">
            <canvas id="bidChart" width="100%" height="50"></canvas>
            <table class="table table-bordered mt-3 py-0">
                <thead>
                    <tr>
                        <th scope="col" class="py-1">Metric</th> <!-- Optional: Adjust padding for header cells -->
                        <th scope="col" class="py-1">Amount</th> <!-- Optional: Adjust padding for header cells -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="col" class="py-0">Total Bid Amount (7 days)</td> <!-- Remove padding for this row -->
                        <td scope="col" class="fw-bold py-0" style="color:rgba(160, 82, 45, 1);">&#8377;&nbsp;&nbsp;<?php echo number_format($totalBidAmount); ?></td> <!-- Remove padding for this row -->
                    </tr>
                    <tr>
                        <td scope="col" class="py-0">Sum of Max Bids (7 days)</td> <!-- Remove padding for this row -->
                        <td scope="col" class="fw-bold py-0" style="color:rgba(34, 139, 34, 1);">&#8377;&nbsp;&nbsp;<?php echo number_format($maxBidSum); ?></td> <!-- Remove padding for this row -->
                    </tr>
                </tbody>
            </table>
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
        // Reverse the labels and datasets for proper order (latest on the left)
        const reversedLabels = <?php echo json_encode(array_reverse($labels)); ?>;
        const reversedMaxBidData = <?php echo json_encode(array_reverse($bidDatasets['maxBid'])); ?>;
        const reversedTotalBidData = <?php echo json_encode(array_reverse($bidDatasets['totalBid'])); ?>;

        new Chart(document.getElementById("bidChart").getContext("2d"), {
            type: "line",
            data: {
                labels: reversedLabels, // Reversed labels for latest date on the left
                datasets: [
                    {
                        label: "Max Bid",  
                        data: reversedMaxBidData, // Reversed data for Max Bid
                        borderColor: "rgba(34, 139, 34, 1)",
                        backgroundColor: "rgba(34, 139, 34, 0.8)",
                        borderWidth: 2,
                        fill: true,
                    },
                    {
                        label: "Total Bids",  
                        data: reversedTotalBidData, // Reversed data for Total Bids
                        borderColor: "rgba(160, 82, 45, 1)",
                        backgroundColor: "rgba(160, 82, 45, 0.8)",
                        borderWidth: 2,
                        fill: true,
                    },
                ],
            },
        });
    });
</script>