<?php
ob_start(); // Start output buffering
session_start(); // Start the session
include("header.php");
include("navbar.php");
isAuthenticatedAsAdmin();
// Call the authentication function
// isAuthenticated();

// Handle form submission for adding categories
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize user inputs for category
  $categoryName = htmlspecialchars(trim($_POST['category_name']));

  // Handle the cropped image data for category
  if (!empty($_POST['category_image'])) {
    $categoryImageData = $_POST['category_image'];
    $uploadDir = '../images/categories/';

    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    // Generate a unique name for the category image
    $uniqueName = 'cat_' . uniqid() . '.webp';
    $targetFile = $uploadDir . $uniqueName;

    // Decode base64 and save the image
    list(, $categoryImageData) = explode(',', $categoryImageData);
    $categoryImageData = base64_decode($categoryImageData);

    if (file_put_contents($targetFile, $categoryImageData)) {
      // Add category details (requires addCategory() implementation)
      if (addCategory($categoryName, $uniqueName)) {
        echo '
        <p class="alert alert-success alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Category added successfully!
        </p>
    ';
      } else {
        echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Error: Failed to add category to the database.
        </p>
    ';
      }
    } else {
      echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Error: Failed to save the category image.
        </p>
    ';
    }
  } else {
    echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Error: No image data received.
        </p>
    ';
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Category</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-palette"></i>&nbsp;
        Add Auction
      </div>
      <div class="card-body">
        <form id="categoryForm" action="add-category.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="category_name" class="form-label">Category Name</label>
            <input type="text" id="category_name" name="category_name" required class="form-control">
          </div>
          <div class="mb-3">
            <label for="categoryImage" class="form-label">Category Image (512x512)</label>
            <input type="file" id="categoryImage" name="categoryImage" accept="image/jpeg, image/png, image/webp" required class="form-control">
            <input type="hidden" name="category_image" id="categoryCroppedImage"> <!-- Hidden field for cropped image data -->
          </div>

          <!-- Cropper Modal -->
          <div id="categoryCropperModal" class="modal fade" tabindex="-1" aria-labelledby="categoryCropperModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-body">
                  <img id="categoryCropperImage" class="img-fluid">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" id="categoryCropButton" class="btn btn-primary">Crop</button>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Add Category</button>
          <button type="clear" class="btn btn-secondary">Clear</button>
        </form>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const categoryImageInput = document.getElementById('categoryImage');
      const categoryCropperModal = document.getElementById('categoryCropperModal');
      const categoryCropperImage = document.getElementById('categoryCropperImage');
      const categoryCropButton = document.getElementById('categoryCropButton');
      const categoryCroppedImageInput = document.getElementById('categoryCroppedImage');

      let cropper;
      let modalInstance;

      // Show the Cropper Modal on image selection
      categoryImageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            categoryCropperImage.src = e.target.result;
            modalInstance = new bootstrap.Modal(categoryCropperModal); // Initialize the modal
            modalInstance.show();

            // Initialize Cropper.js
            cropper = new Cropper(categoryCropperImage, {
              aspectRatio: 1, // 1:1 for square images
              viewMode: 2, // Allows free movement of the crop box
              autoCropArea: 1, // 100% cropping area
              responsive: true,
              zoomable: true,
            });
          };
          reader.readAsDataURL(file);
        }
      });

      // Crop the image on button click
      categoryCropButton.addEventListener('click',
        function () {
          const canvas = cropper.getCroppedCanvas({
            width: 512,
            height: 512,
          });

          // Get the cropped image as a Data URL
          canvas.toBlob((blob) => {
            const reader = new FileReader();
            reader.onloadend = function () {
              categoryCroppedImageInput.value = reader.result; // Assign base64 data to hidden input
              modalInstance.hide(); // Close the modal
              cropper.destroy(); // Destroy the cropper instance
            };
            reader.readAsDataURL(blob);
          });
        });
    });
  </script>
</body>
</html>
<?
  include_once("./footer.php");
  ob_end_flush();
?>