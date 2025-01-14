<?php
isAuthenticatedAsAdmin();
$userStatusData = getUserStatusData();
$userStatusLabels = [];
$userStatusCounts = [];

foreach ($userStatusData as $status) {
    $userStatusLabels[] = ucfirst($status['userStatus']); // Capitalize the first letter
    $userStatusCounts[] = $status['statusCount'];
}
?>

<div class="col-lg-6">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-pie me-1"></i>
            User Status Distribution
        </div>
        <div class="card-body">
            <canvas id="userStatusChart" width="100%" height="50"></canvas>
        </div>
        <div class="card-footer small text-muted">
            Updated on <?php echo getLastUpdateLabel(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Chart(document.getElementById("userStatusChart").getContext("2d"), {
            type: "pie",
            data: {
                labels: <?php echo json_encode($userStatusLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($userStatusCounts); ?>,
                    backgroundColor: [
                        "rgba(34, 139, 34, 0.8)",   // Green for 'activate'
                        "rgba(160,82,45,0.8)", // Brown for 'deactivate'
                        "rgb(106,9,255,0.8)"     // Violate for 'suspend'
                    ],
                    borderColor: [
                        "rgba(34, 139, 34, 1)",
                        "rgba(160, 82, 45, 1)",
                        "rgb(106,9,255,1)"
                    ],
                    borderWidth: 2,
                }]
            },
        });
    });
</script>