<?php
isAuthenticatedAsAdmin();
$data = getBidData();
$bidData = array_reverse($data); // Reversing the data for recent to old
$recentBidData = array_slice($bidData, 0, 7);

// Prepare the labels as the last 7 days
$labels = [];
for ($i = 6; $i >= 0; $i--) {
    $labels[] = date('Y-m-d', strtotime("-$i days"));
}

// Prepare the bid data for chart
[$bidLabels, $bidDatasets] = prepareChartData($recentBidData, 'bidDate', ['maxBid', 'totalBid']);

$totalBidAmount = array_sum(array_column($recentBidData, 'totalBid')); // Sum of all total bids for last 7 days
$maxBidSum = array_sum(array_column($recentBidData, 'maxBid')); // Sum of max bids for last 7 days
?>

<div class="col-lg-6">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>
            Bids (Last 7 Days)
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
                        <td scope="col" class="fw-bold py-0" style="color:rgba(34, 139, 34, 1);">&#8377;&nbsp;&nbsp;<?= number_format($maxBidSum); ?></td> <!-- Remove padding for this row -->
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer small text-muted">
            Updated on <?php echo getLastUpdateLabel(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Chart(document.getElementById("bidChart").getContext("2d"), {
            type: "line",
            data: {
                labels: <?php echo json_encode($labels); ?>,  // Last 7 days as labels
                datasets: [
                    {
                        label: "Max Bid",  
                        data: <?php echo json_encode($bidDatasets['maxBid']); ?>,
                        borderColor: "rgba(34, 139, 34, 1)",
                        backgroundColor: "rgba(34, 139, 34, 0.8)",
                        borderWidth: 2,
                        fill: true,
                    },
                    {
                        label: "Total Bids",  
                        data: <?php echo json_encode($bidDatasets['totalBid']); ?>,
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