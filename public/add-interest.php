<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['userId'];
    $categories = $_POST['categoryId']; // This is an array of selected categories
    $type = $_POST['type'];
    $keywords = $_POST['keywords'];

    try {
        global $pdo;

        // Loop through each selected category and insert a separate record for each
        foreach ($categories as $categoryId) {
            $stmt = $pdo->prepare("INSERT INTO interests (interestUserId, interestCategoryId, interestProductType, interestKeywords) 
                                   VALUES (:userId, :categoryId, :type, :keywords)");
            $stmt->execute([
                ':userId' => $userId,
                ':categoryId' => $categoryId,
                ':type' => $type,
                ':keywords' => $keywords,
            ]);
        }

        $successMessage = "Interest(s) added successfully.";
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
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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
            <label for="categoryId" class="form-label">Select Categories</label>
            <select class="form-select select2" id="categoryId" name="categoryId[]" multiple="multiple" required>
                <?php 
                $categories = getCategories();
                foreach ($categories as $category): 
                ?>
                    <option value="<?= $category['categoryId']; ?>"><?= $category['categoryName']; ?></option>
                <?php endforeach; ?>
            </select>
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

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2 on the category select field
    $(document).ready(function() {
        $('#categoryId').select2({
            placeholder: "Select categories",
            allowClear: true
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
