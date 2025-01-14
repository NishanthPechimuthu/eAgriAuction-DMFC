<?php
ob_start();
include("header.php");
include("navbar.php");

// Call the authentication function
isAuthenticated();
$user_id = $_SESSION["userId"];
$user = getUserById($user_id);

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $account_no = $_POST['account_no'];
  $image = $user['userProfileImg']; // Default to current image
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  // Handle image upload
  if (!empty($_POST['cropped_image'])) {
    $croppedImageData = $_POST['cropped_image'];
    $uploadDir = '../images/profiles/';

    // Ensure the directory exists
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    // Determine the target file name
    $isDefaultImage = empty($image) || $image === 'profile.webp';
    $targetFile = $isDefaultImage ? $uploadDir . 'img_' . uniqid() . '.webp' : $uploadDir . $image;

    // Decode base64 and save the image
    list(, $croppedImageData) = explode(',', $croppedImageData);
    $croppedImageData = base64_decode($croppedImageData);

    if (file_put_contents($targetFile, $croppedImageData)) {
      // Set the new file name for the database
      $image = basename($targetFile);
    } else {
      echo '
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                Failed to save the cropped image.
        </p>
    ';
    }
  }

  // Update profile details
  if (updateUserProfile($user_id, $fname, $lname, $account_no, $image, $phone, $address)) {
    echo '
        <p class="alert alert-success alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                Profile updated successfully!
        </p>
    ';
    $user = getUserById($user_id); // Refresh user data
  } else {
    echo '
        <p class="alert alert-danger alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
                Failed to update profile.
        </p>
    ';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Update Profile</title>
  <?php include_once("../assets/link.html"); ?>
</head>
<body>
  <div class="container py-5">
    <div class="card mb-4">
      <div class="card-header">
        <i class="fa fa-user-pen"></i>&nbsp;
        Update Profile
      </div>
      <div class="card-body">
        <form action="update-profile.php" method="POST">
          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['userName']) ?>" disabled class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label" for="fname">First Name</label>
            <input type="text" name="fname" id="fname" value="<?= htmlspecialchars($user['userFirstName']) ?>" required class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label" for="lname">Last Name</label>
            <input type="text" name="lname" id="lname" value="<?= htmlspecialchars($user['userLastName']) ?>" required class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label" for="email">E-Mail</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['userEmail']??null) ?>" disabled class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label" for="account_no">Account No</label>
            <input 
                type="text" 
                name="account_no" 
                id="account_no" 
                value="<?= htmlspecialchars($user['userAccountNo']) ?>" 
                required 
                class="form-control">
            <div id="accountFeedback" class="text-danger mt-1"></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="phone">Phone</label>
            <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($user['userPhone']??null) ?>" required class="form-control" placeholder="+91 xxx-xxx-xxxx">
          </div>
          <div class="mb-3">
            <label class="form-label" for="address">Address</label>
            <input type="text" name="address" id="address" value="<?= htmlspecialchars($user['userAddress']) ?>" required class="form-control" placeholder="Sub Dist, Dist, State">
          </div>
          <div class="mb-3">
            <label class="form-label" for="role">Role</label>
            <input type="text" name="role" id="role" value="<?= htmlspecialchars($user['userRole']) ?>" disabled class="form-control">
          </div>
          <div class="mb-3">
            <label for="profileImage" class="form-label">Profile Image</label>
            <input type="file" id="profileImage" name="profileImage" accept="image/jpeg, image/png, image/webp" class="form-control">
            <input type="hidden" name="cropped_image" id="croppedImage">
          </div>
          <div class="mb-3">
            <label for="imagePreview" class="form-label">Image Preview</label>
            <img id="imagePreview" class="img-fluid" style="max-width: 100%; height: auto;"
            src="<?= (isset($user['userProfileImg']) && file_exists('../images/profiles/' . htmlspecialchars($user['userProfileImg'])))
            ? '../images/profiles/' . htmlspecialchars($user['userProfileImg'])
            : '../images/profiles/profile.jpg' ?>"
            alt="Profile Image Preview">
          </div>
          <div id="cropperModal" class="modal fade" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-body">
                  <div class="d-flex justify-content-center">
                    <img id="cropperImage" src="" alt="Cropper Image" class="img-fluid">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="cropButton">Crop</button>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100">Update Profile</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
  <script>
    const productImageInput = document.getElementById("profileImage");
    const croppedImageInput = document.getElementById("croppedImage");
    const cropperModal = new bootstrap.Modal(document.getElementById("cropperModal"));
    const cropperImage = document.getElementById("cropperImage");
    const imagePreview = document.getElementById("imagePreview");
    const cropButton = document.getElementById("cropButton");
    let cropper;

    productImageInput.addEventListener("change", function (event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          cropperImage.src = e.target.result;
          cropperModal.show();

          if (cropper) {
            cropper.destroy();
          }
          cropper = new Cropper(cropperImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
          });
        };
        reader.readAsDataURL(file);
      }
    });

    cropButton.addEventListener("click", function () {
      if (cropper) {
        const croppedCanvas = cropper.getCroppedCanvas({
          width: 500,
          height: 500,
        });
        croppedCanvas.toBlob(function (blob) {
          const reader = new FileReader();
          reader.onloadend = function () {
            croppedImageInput.value = reader.result;
            imagePreview.src = reader.result;
            cropperModal.hide();
          };
          reader.readAsDataURL(blob);
        }, "image/webp");
      }
    });
  </script>
  <script>
    const accountInput = document.getElementById("account_no");
    const feedbackDiv = document.getElementById("accountFeedback");
  
    accountInput.addEventListener("input", function () {
      const accountNumber = accountInput.value;
  
      if (accountNumber.length < 9 || accountNumber.length > 18) {
        feedbackDiv.textContent = "Account number must be between 9 to 18 characters.";
      } else {
        // Call AJAX to check for additional validation (optional)
        feedbackDiv.textContent = ""; // Clear feedback
      }
    });
  
    // Optional: AJAX example for additional backend validation
    accountInput.addEventListener("blur", function () {
      const accountNumber = accountInput.value;
      if (accountNumber.length >= 9 && accountNumber.length <= 18) {
        fetch('validate-account.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ account_no: accountNumber })
        })
          .then(response => response.json())
          .then(data => {
            if (!data.valid) {
              feedbackDiv.textContent = data.message || "Invalid account number.";
            } else {
              feedbackDiv.textContent = ""; // Clear feedback
            }
          })
          .catch(error => {
            console.error("Error:", error);
          });
      }
    });
  </script>
</body>
</html>
<?
  include_once("./footer.php");
  ob_end_flush();
?>