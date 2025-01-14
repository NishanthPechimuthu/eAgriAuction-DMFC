<?php
ob_start();
session_start();
include("header.php");
include("navbar.php");

// Call the authentication function to ensure the user is logged in
isAuthenticated();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['momentImage']['name'])) {
        $uploadDir = '../images/moments/';

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique name for the image
        $imageName = 'moment_' . uniqid() . '.' . pathinfo($_FILES['momentImage']['name'], PATHINFO_EXTENSION);
        $targetFile = $uploadDir . $imageName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['momentImage']['tmp_name'], $targetFile)) {
            // Save the moment to the database
            $userId = $_SESSION['userId'];
            $result = addMoment($userId, $imageName);

            if ($result['success']) {
                header("Location: add-moments.php");
                exit();
            } else {
                echo '<p class="alert alert-danger">' . $result['message'] . '</p>';
            }
        } else {
            echo '<p class="alert alert-danger">Error: Failed to upload the image.</p>';
        }
    } else {
        echo '<p class="alert alert-danger">Error: No image uploaded.</p>';
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
  <title>Upload Moment Image</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-image"></i>&nbsp; Upload Moment Image
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="userId" value="<?= $_SESSION['userId']; ?>">
          <div class="mb-3">
            <label for="momentImage" class="form-label">Moment Image</label>
            <input type="file" id="momentImage" name="momentImage" accept="image/jpeg, image/png, image/webp" required class="form-control" onchange="previewImage(event)">
          </div>
          
          <!-- Image Preview -->
          <div class="mb-3">
            <img id="imagePreview" src="#" alt="Image Preview" class="img-fluid" style="display: none; max-width: 100%; height: auto;">
          </div>

          <div class="d-flex justify-content-between">
            <input type="submit" class="btn btn-primary" value="Upload Moment Image">
            <input type="reset" class="btn btn-secondary" value="Clear">
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // JavaScript function to preview the image before uploading
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
  </script>
</body>
</html>

<?php
include_once("./footer.php");
ob_end_flush();
?>