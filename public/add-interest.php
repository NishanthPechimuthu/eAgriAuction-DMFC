<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $categoryId = $_POST['categoryId'];
    $type = $_POST['type'];
    $keywords = $_POST['keywords'];

    try {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO interests (interestUserId, interestCategoryId, interestProductType, interestKeywords) 
                               VALUES (:userId, :categoryId, :type, :keywords)");
        $stmt->execute([
            ':userId' => $userId,
            ':categoryId' => $categoryId,
            ':type' => $type,
            ':keywords' => $keywords,
        ]);
        $successMessage = "Interest added successfully.";
    } catch (PDOException $e) {
        $errorMessage = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Interest</title>
    <?php include_once("../assets/link.html"); ?>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Add Interest</h2>
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?= $successMessage; ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="hidden" class="form-control" id="userId" name="userId" value="<?=$_SESSION["userId"];?>" required>
        </div>
        <div class="mb-3">
            <label for="categoryId" class="form-label">Category ID</label>
            <input type="number" class="form-control" id="categoryId" name="categoryId" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Product Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="organic">Organic</option>
                <option value="hybrid">Hybrid</option>
                <option value="both">Both</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="keywords" class="form-label">Keywords</label>
            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Enter keywords (optional)">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>