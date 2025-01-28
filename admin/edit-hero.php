<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");

// Call the authentication function
isAuthenticated();

// Fetch Hero Details
$heroId = $_GET['heroId'];
$hero = getHeroById($heroId)[0]; // Fetch hero details from the database

$validStatuses = ['activate', 'deactivate', 'suspend']; // Define allowed status values
$successMessage = ''; // To store success messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $heroTitle = htmlspecialchars(trim($_POST['heroTitle']));
    $heroMessage = htmlspecialchars(trim($_POST['heroMessage']));
    $heroContent = $_POST['heroContent']; // Raw HTML content
    $heroStatus = $_POST['heroStatus'];

    // Validate heroStatus
    if (!in_array($heroStatus, $validStatuses)) {
        echo '<p class="alert alert-danger">Invalid status value. Allowed values are "activate", "deactivate", or "suspend".</p>';
    } else {
        $croppedImageData = $_POST['cropped_image'] ?? null;
        $uploadDir = '../images/heroes/';
        $uniqueName = $hero["heroImg"]; // Default to existing image name

        if ($croppedImageData) {
            // Ensure the directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Decode base64 and save the image
            list(, $croppedImageData) = explode(',', $croppedImageData);
            $croppedImageData = base64_decode($croppedImageData);
            $uniqueName = 'hero_' . uniqid() . '.webp';
            $targetFile = $uploadDir . $uniqueName;

            if (!file_put_contents($targetFile, $croppedImageData)) {
                echo '<p class="alert alert-danger">Error: Failed to save the cropped image.</p>';
                $uniqueName = $hero["heroImg"]; // Revert to existing image name
            }
        }

        // Update the hero details in the database
        $result = updateHero($heroId, $heroTitle, $heroMessage, $heroContent, $heroStatus, $uniqueName, null);

        if ($result === "Hero updated successfully!") {
            $successMessage = "Hero updated successfully!";
            $hero = getHeroById($heroId)[0]; // Refresh hero details
        } else {
            echo '<p class="alert alert-danger">' . $result . '</p>';
        }
    }
}

ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Hero</title>
  <?php include_once("../assets/link.html"); ?>
  <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.2/tinymce.min.js"></script>
</head>
<body>
  <div class="container py-5">
    <?php if (!empty($successMessage)) : ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> <?= $successMessage ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-user"></i>&nbsp; Edit Hero
      </div>
      <div class="card-body">
        <form id="heroForm" action="edit-hero.php?heroId=<?= $hero["heroId"] ?>" method="POST">
          <div class="mb-3">
            <label for="heroTitle" class="form-label">Title</label>
            <input type="text" id="heroTitle" name="heroTitle" value="<?= htmlspecialchars($hero['heroTitle']) ?>" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="heroMessage" class="form-label">Message</label>
            <input type="text" id="heroMessage" name="heroMessage" value="<?= htmlspecialchars($hero['heroMessage']) ?>" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="heroContent" class="form-label">Content</label>
            <textarea id="heroContent" name="heroContent" class="form-control" style="height: 300px;"><?= htmlspecialchars($hero['heroContent']) ?></textarea>
          </div>
          <div class="mb-3">
            <label for="heroStatus" class="form-label">Status</label>
            <select id="heroStatus" name="heroStatus" class="form-control" required>
              <option value="activate" <?= $hero['heroStatus'] == 'activate' ? 'selected' : '' ?>>Activate</option>
              <option value="deactivate" <?= $hero['heroStatus'] == 'deactivate' ? 'selected' : '' ?>>Deactivate</option>
              <option value="suspend" <?= $hero['heroStatus'] == 'suspend' ? 'selected' : '' ?>>Suspend</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="productImage" class="form-label">Hero Image</label>
            <input type="file" id="productImage" name="productImage" accept="image/*" class="form-control">
            <input type="hidden" name="cropped_image" id="croppedImage">
          </div>
          <div class="mb-3">
            <label for="imagePreview" class="form-label">Image Preview</label>
            <img id="imagePreview" src="../images/heroes/<?= $hero['heroImg'] ?>" alt="Image Preview" class="img-fluid rounded-1 border border-2 border-dark" />
          </div>
          <div class="d-flex justify-content-between">
            <input type="submit" class="btn btn-primary" value="Update Hero">
            <input type="reset" class="btn btn-secondary" value="Clear">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>
  <script>
    // TinyMCE Initialization
    tinymce.init({
      selector: '#heroContent',
      plugins: 'lists link image code',
      toolbar: 'undo redo | bold italic | bullist numlist | code',
    });

    // Image Cropping Script
    const productImageInput = document.getElementById('productImage');
    const croppedImageInput = document.getElementById('croppedImage');
    const imagePreview = document.getElementById('imagePreview');
    let cropper;

    productImageInput.addEventListener('change', function () {
      const reader = new FileReader();
      reader.onload = function (e) {
        const cropperImage = document.getElementById('cropperImage');
        cropperImage.src = e.target.result;
        cropper = new Cropper(cropperImage, { aspectRatio: 16 / 9 });
        const modal = new bootstrap.Modal(cropperModal);
        modal.show();
      };
      reader.readAsDataURL(this.files[0]);
    });
  </script>
</body>
</html>

<?php
include_once("./footer.php");
ob_end_flush();
?>
