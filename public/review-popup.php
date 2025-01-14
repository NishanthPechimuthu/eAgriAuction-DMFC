<?php
ob_start(); // Start output buffering
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if not already started
}
include("header.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION["userId"];
    $reviewMessage = $_POST['reviewMessage'] ?? '';

    if (empty($reviewMessage)) {
        echo json_encode(["success" => false, "message" => "Review message is required."]);
        exit();
    }

    $response = addReview($userId, $reviewMessage);
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Review</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .floating-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
    }
    .floating-button button {
      border-radius: 50%;
      width: 60px;
      height: 60px;
      font-size: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>

<!-- Floating Button -->
<div class="floating-button">
  <button id="toggleReviewForm" class="btn btn-primary">
    <i class="bi bi-chat-left-quote-fill"></i>
  </button>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Submit Your Review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x"></i></button>
      </div>
      <div class="modal-body">
        <form id="reviewForm">
          <div class="mb-3">
            <textarea class="form-control" name="reviewMessage" rows="5" placeholder="Write your review..." required></textarea>
          </div>
          <button type="submit" class="btn btn-success w-100">Submit Review</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS (requires Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(document).ready(function () {
  // Toggle Review Modal
  const reviewButton = $('#toggleReviewForm');
  const reviewModalElement = $('#reviewModal');
  const reviewModal = new bootstrap.Modal(reviewModalElement);

  reviewButton.click(function () {
    if (reviewModalElement.hasClass('show')) {
      // If modal is already visible, hide it and change icon
      reviewModal.hide();
      reviewButton.html('<i class="bi bi-chat-left-quote-fill"></i>'); // Icon when closed
    } else {
      // If modal is hidden, show it and change icon
      reviewModal.show();
      reviewButton.html('<i class="bi bi-x-circle-fill"></i>'); // Icon when open (Close icon)
    }
  });

  // Submit Review
  $('#reviewForm').on('submit', function (e) {
    e.preventDefault();

    const formData = $(this).serialize();
    $.ajax({
      url: 'review-popup.php',
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          alert(response.message);
          $('#reviewForm')[0].reset();
          reviewModal.hide();
          reviewButton.html('<i class="bi bi-chat-left-quote-fill"></i>'); // Reset icon when modal is closed
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert('An error occurred while submitting your review.');
      }
    });
  });
});
</script>

</body>
</html>