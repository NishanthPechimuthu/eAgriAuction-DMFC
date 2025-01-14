<?php
isAuthenticatedAsAdmin();
$userData = getUserRegistrationData();
$userData = array_reverse($userData); // Reversing to show most recent first
$recentUserData = array_slice($userData, 0, 6);
[$userLabels, $userDatasets] = prepareChartData($recentUserData, 'registrationMonth', ['userCount']);
?>

<div class="col-lg-6">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            User Registrations (Last 6 Months)
        </div>
        <div class="card-body"><canvas id="registrationChart" width="100%" height="50"></canvas></div>
        <div class="card-footer small text-muted">
            Updated on <?php echo getLastUpdateLabel(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        new Chart(document.getElementById("registrationChart").getContext("2d"), {
            type: "bar",
            data: {
                labels: <?php echo json_encode($userLabels); ?>,
                datasets: [
                    {
                        label: "User Registrations",
                        data: <?php echo json_encode($userDatasets['userCount']); ?>,
                        backgroundColor: "rgba(34, 139, 34, 0.8)",
                        borderColor: "rgba(34, 139, 34, 1)",
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        },
                    }],
                },
            },
        });
    });
</script>