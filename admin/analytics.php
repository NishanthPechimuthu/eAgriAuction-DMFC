<?php
session_start();
ob_start();

include "header.php";
include "navbar.php";
isAuthenticatedAsAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Agri-Themed Charts</title>
    <?php include("../assets/link.html"); ?>
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
</head>
<body>
    <div class="container">
        <h1 class="text-center">Agri-Themed Charts</h1>

        <!-- Include each chart -->
        <?php include "bid-chart.php"; ?>
        <?php include "registration-chart.php"; ?>
        <?php include "auction-chart.php"; ?>
        <?php include "user-status-chart.php"; ?>
    </div>

</body>
</html>