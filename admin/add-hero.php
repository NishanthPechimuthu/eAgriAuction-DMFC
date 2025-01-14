<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");

// Call the authentication function
isAuthenticatedAsAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $heroTitle = htmlspecialchars(trim($_POST['heroTitle']));
    $heroMessage = htmlspecialchars(trim($_POST['heroMessage']));
    $heroContent = $_POST['heroContent']; // Raw HTML content
    $heroStatus = $_POST['heroStatus'];

    // Handle the cropped image data
    if (!empty($_POST['cropped_image'])) {
        $croppedImageData = $_POST['cropped_image'];
        $uploadDir = '../images/heroes/';

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique name for the image
        $uniqueName = 'hero_' . uniqid() . '.webp';
        $targetFile = $uploadDir . $uniqueName;

        // Decode base64 and save the image
        list(, $croppedImageData) = explode(',', $croppedImageData);
        $croppedImageData = base64_decode($croppedImageData);

        if (file_put_contents($targetFile, $croppedImageData)) {
            // If save successful, call function to add hero
            $result = addHero($heroTitle, $heroMessage, $heroContent, $uniqueName, $heroStatus);

            if ($result === "Hero added successfully!") {
                header("Location: manage-hero.php");
                exit();
            } else {
                echo '<p class="alert alert-danger">' . $result . '</p>';
            }
        } else {
            echo '<p class="alert alert-danger">Error: Failed to save the cropped image.</p>';
        }
    } else {
        echo '<p class="alert alert-danger">Error: No cropped image data received.</p>';
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
  <title>Add Hero</title>
  <?php include_once("../assets/link.html"); ?>
  <!-- Include Cropper.js -->
  <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.css" rel="stylesheet">
  <!-- Include TinyMCE -->
  <script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.2/tinymce.min.js"></script>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-user"></i>&nbsp; Add Hero
      </div>
      <div class="card-body">
        <form id="heroForm" action="add-hero.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="heroTitle" class="form-label">Title</label>
            <input type="text" id="heroTitle" name="heroTitle" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="heroMessage" class="form-label">Message</label>
            <input type="text" id="heroMessage" name="heroMessage" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="heroContent" class="form-label">Content</label>
            <textarea id="heroContent" name="heroContent" class="form-control"><?= htmlspecialchars($hero["heroContent"] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label for="heroStatus" class="form-label">Status</label>
            <select id="heroStatus" name="heroStatus" class="form-control" required>
              <option value="activate">Activate</option>
              <option value="deactivate">Deactivate</option>
              <option value="suspend">Suspend</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="productImage" class="form-label">Hero Image</label>
            <input type="file" id="productImage" name="productImage" accept="image/jpeg, image/png, image/webp" required class="form-control">
            <input type="hidden" name="cropped_image" id="croppedImage">
          </div>

          <!-- Preview of cropped image -->
          <div class="mb-3">
            <label for="imagePreview" class="form-label">Image Preview</label>
            <img id="imagePreview" class="img-fluid rounded-1 border border-2 border-dark" style="max-width: 100%; height: auto; display: none;">
          </div>

          <!-- Cropper Modal -->
          <div id="cropperModal" class="modal fade" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-body">
                  <img id="cropperImage" class="img-fluid rounded-1 border border-dark">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" id="cropButton" class="btn btn-primary">Crop</button>
                </div>
              </div>
            </div>
          </div>
          
          <div class="d-flex justify-content-between">
            <input type="submit" class="btn btn-primary" value="Add Hero">
            <input type="reset" class="btn btn-secondary" value="Clear">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.12/dist/cropper.min.js"></script>
  <script>
    // Initialize TinyMCE for rich text and source code mode
    tinymce.init({
      selector: '#heroContent',
      plugins: 'lists link image charmap preview code', // Add 'code' plugin
      toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code', // Add 'code' button
      menubar: false,
      height: 300, // Set editor height
    });

    document.addEventListener('DOMContentLoaded', function () {
      const productImageInput = document.getElementById('productImage');
      const cropperModal = document.getElementById('cropperModal');
      const cropperImage = document.getElementById('cropperImage');
      const cropButton = document.getElementById('cropButton');
      const croppedImageInput = document.getElementById('croppedImage');
      const imagePreview = document.getElementById('imagePreview');

      let cropper;
      let modal;

      // Handle image upload and cropping
      productImageInput.addEventListener('change', function () {
        const reader = new FileReader();
        reader.onload = function (e) {
          cropperImage.src = e.target.result;
          if (cropper) {
            cropper.destroy();
          }
          cropper = new Cropper(cropperImage, {
            aspectRatio: 16 / 9,
            viewMode: 2,
          });
          modal = new bootstrap.Modal(cropperModal);
          modal.show();
        };
        reader.readAsDataURL(this.files[0]);
      });

      // Handle cropping action
      cropButton.addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas({
          width: 800,
          height: 450,
        });
        croppedImageInput.value = canvas.toDataURL('image/webp');
        imagePreview.src = canvas.toDataURL('image/webp');
        imagePreview.style.display = 'block';

        modal.hide();
        cropper.destroy();
        cropper = null;
      });
    });
  </script>
</body>
</html>

<?php
include_once("./footer.php");
ob_end_flush();
?>