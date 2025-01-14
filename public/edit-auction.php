<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");

$auctionId = $_GET['auctionId'] ?? null;
if ($auctionId === null) {
  // Redirect or show an error if auctionId is not passed
  header("Location: manage-auction.php");
  exit();
}

$auction = getAuctionById($auctionId); // Function to fetch auction details by ID
$categories = getCategories(); // Function to fetch all categories
if (!$auction) {
  // If auction not found, redirect or show an error
  header("Location: manage-auction.php");
  exit();
}

// Call the authentication function
isAuthenticated();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize user inputs
  $title = htmlspecialchars(trim($_POST['title']));
  $start_price = filter_var($_POST['start_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $start_time = $_POST['start_time'];
  $end_date = $_POST['end_date'];
  $address = $_POST['address'];
  $category_id = $_POST['category']; // Use category ID, not category name
  $description = $_POST['description'];
  $status = $_POST["status"];
  $product_type = $_POST["product_type"];
  $product_quantity = $_POST["product_quantity"];
  $product_unit = $_POST["product_unit"];
  
  // Initialize image handling
  $image = null;
  
  if (!empty($_POST['cropped_image'])) {
    // Process the new cropped image
    $croppedImageData = $_POST['cropped_image'];
    $uploadDir = '../images/products/';
    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
    
    $oldImage = $auction['auctionProductImg']; // Assuming the old image filename is stored in auction['auctionProductImg']
    
    // If the old image exists, remove it before uploading the new image
    if (!empty($oldImage) && file_exists($uploadDir . $oldImage)) {
      unlink($uploadDir . $oldImage); // Remove the old image
    }

    // Generate a unique name for the new image
    $uniqueName = uniqid('auction_', true) . '.webp'; // Unique file name with a .webp extension
    $targetFile = $uploadDir . $uniqueName;

    // Decode base64 and save the image
    list(, $croppedImageData) = explode(',', $croppedImageData);
    $croppedImageData = base64_decode($croppedImageData);

    if (file_put_contents($targetFile, $croppedImageData)) {
      $image = $uniqueName; // Set the cropped image as the new image
    }
  }

  // If no cropped image, keep the old image or handle accordingly
  if (empty($image)) {
    $image = !empty($auction['auctionProductImg']) ? $auction['auctionProductImg'] : null;
  }

  // Call updateAuction function
  $result = updateAuction($auctionId, $title, $start_price, $start_time, $end_date, $category_id, $address, $description, $image, $status, $product_type, $product_quantity, $product_unit);

  if ($result) {
    echo '
    <p class="alert alert-success alert-dismissible fade show d-flex align-items-center"
       role="alert"  data-bs-dismiss="alert"
       aria-label="Close"
       style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
          Auction updated successfully
    </p>';
$auction = getAuctionById($auctionId);
  } else {
    echo '
    <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
       role="alert"  data-bs-dismiss="alert"
       aria-label="Close"
       style="white-space:nowrap; max-width: 100%; overflow-y: auto; ">
          Error: Failed to update the auction.
    </p>';
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
  <title>Edit Auction</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="bi bi-pencil-square"></i>&nbsp;
        Edit Auction
      </div>
      <div class="card-body">
        <form id="auctionForm" action="edit-auction.php?auctionId=<?= $auctionId ?>" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($auction['auctionTitle']) ?>" required class="form-control">
          </div>

          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select id="category" name="category" class="form-control" required>
              <option value="" disabled>Select Category</option>
              <?php foreach ($categories as $category): ?>
              <option value="<?= htmlspecialchars($category['categoryId']) ?>" <?=$auction['auctionCategoryId'] == $category['categoryId'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($category['categoryName']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="start_price" class="form-label">Starting Price</label>
            <input type="number" id="start_price" name="start_price" value="<?= htmlspecialchars($auction['auctionStartPrice']) ?>" required class="form-control">
          </div>

          <div class="mb-3">
            <label for="product_type" class="form-label">Product Type</label>
            <select id="product_type" name="product_type" class="form-control" required>
              <option value="" disabled>Select Type</option>
              <option value="organic" <?=$auction["auctionProductType"] == 'organic' ? 'selected' : '' ?>>
                ORGANIC
              </option>
              <option value="hybrid" <?=$auction["auctionProductType"] == 'hybrid' ? 'selected' : '' ?>>
                HYBRID
              </option>
            </select>
          </div>

          <div class="mb-3">
            <label for="product_quantity" class="form-label">Quantity</label>
            <input type="number" id="product_quantity" name="product_quantity" value="<?= htmlspecialchars($auction['auctionProductQuantity']) ?>" required class="form-control">
          </div>

          <div class="mb-3">
            <label for="product_unit" class="form-label">Product Unit</label>
            <select id="product_unit" name="product_unit" class="form-control" required>
              <option value="kg" <?=$auction["auctionProductUnit"] == 'kg' ? 'selected' : '' ?>>KG</option>
              <option value="ton" <?=$auction["auctionProductUnit"] == 'ton' ? 'selected' : '' ?>>TON</option>
              <option value="nos" <?=$auction["auctionProductUnit"] == 'nos' ? 'selected' : '' ?>>NOS</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="datetime-local" id="start_time" name="start_time" value="<?= date('Y-m-d\TH:i', strtotime($auction['auctionStartDate'])) ?>" required class="form-control">
          </div>

          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="datetime-local" id="end_date" name="end_date" value="<?= date('Y-m-d\TH:i', strtotime($auction['auctionEndDate'])) ?>" required class="form-control">
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($auction['auctionAddress']) ?>" required class="form-control">
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" required class="form-control"><?= htmlspecialchars($auction['auctionDescription']) ?></textarea>
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Auction Status</label>
            <select id="status" name="status" class="form-control" required>
              <option value="activate" <?=$auction['auctionStatus'] === 'activate' ? 'selected' : '' ?>>Activate</option>
              <option value="deactivate" <?=$auction['auctionStatus'] === 'deactivate' ? 'selected' : '' ?>>Deactivate</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="productImage" class="form-label">Product Image</label>
            <input type="file" name="productImage" class="form-control" id="productImage" accept="image/*">
          </div>

          <!-- Image preview section -->
          <div class="mb-3">
            <label for="imagePreview" class="form-label">Preview Image</label>
            <img id="imagePreview" src="<?= isset($auction['auctionProductImg']) ? '../images/products/' . $auction['auctionProductImg'] : '' ?>" alt="Image Preview" class="img-fluid rounded-1 border border-2 border-dark" />
        </div>

        <!-- Hidden input for cropped image -->
        <input type="hidden" name="cropped_image" id="cropped_image">

        <button type="submit" class="btn btn-primary">Update Auction</button>
      </form>
    </div>
  </div>
</div>

<!-- Modal for cropping image -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="imageToCrop" src="#" alt="Image to Crop" class="img-fluid">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="cropButton">Crop Image</button>
      </div>
    </div>
  </div>
</div>

<script>
  // Initialize the cropper.js once an image is selected
  let cropper;
  document.getElementById('productImage').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
      const image = document.getElementById('imageToCrop');
      image.src = e.target.result;

      // Open the modal when the image is loaded
      const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
      cropModal.show();

      // Initialize the cropper.js with the new image
      if (cropper) {
        cropper.destroy();
      }

      cropper = new Cropper(image, {
        aspectRatio: 1, // Square crop
        viewMode: 1,
        autoCropArea: 0.8
      });
    };

    if (file) {
      reader.readAsDataURL(file);
    }
  });

  // Handle the crop action
  document.getElementById('cropButton').addEventListener('click', function () {
    const canvas = cropper.getCroppedCanvas({
      width: 500, // You can adjust the width and height
      height: 500
    });

    // Get the data URL of the cropped image
    const croppedImage = canvas.toDataURL('image/webp');
    document.getElementById('cropped_image').value = croppedImage;

    // Update the image preview
    document.getElementById('imagePreview').src = croppedImage;

    // Close the modal
    const cropModal = bootstrap.Modal.getInstance(document.getElementById('cropModal'));
    cropModal.hide();
  });
</script>
</body>
</html>

<?php
include_once("./footer.php");
ob_end_flush();
?>