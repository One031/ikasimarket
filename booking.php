<?php
require_once 'functions/db.php';
include 'includes/header.php';

// Validate and retrieve product id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<p>Invalid product.</p>";
  include 'includes/footer.php'; exit;
}

$listing_id = $_GET['id'];

// Fetch product and seller info
$stmt = $conn->prepare("SELECT l.*, u.name AS seller FROM listings l JOIN users u ON l.user_id = u.user_id WHERE l.listing_id = ?");
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Error if product not found
if (!$product) {
  echo "<p>Product not found.</p>";
  include 'includes/footer.php'; exit;
}
?>
<!-- Booking Form -->
<div class="container py-5">
  <h3>Book: <?= htmlspecialchars($product['title']) ?></h3>

  <form action="actions/place-order.php" method="post">
    <input type="hidden" name="listing_id" value="<?= $product['listing_id'] ?>">
    <input type="hidden" name="seller_id" value="<?= $product['user_id'] ?>">

    <!-- Payment Method Selection -->
    <div class="mb-3">
      <label for="payment_method" class="form-label">Select Payment Method</label>
      <select name="payment_method" id="payment_method" class="form-select" required>
        <option value="cash">Cash</option>
        <option value="online">Online (Mock)</option>
      </select>
    </div>
    <!-- Hidden Online Payment Details -->
    <div id="online-payment" style="display:none;" class="mb-3">
      <label class="form-label">Card Details (Mock)</label>
      <input type="text" class="form-control mb-2" placeholder="Card Number">
      <input type="text" class="form-control mb-2" placeholder="Expiry Date">
      <input type="text" class="form-control" placeholder="CVV">
    </div>

    <div class="mb-3">
      <label for="message" class="form-label">Message / Notes</label>
      <textarea name="message" class="form-control" rows="4" placeholder="Add any instructions..."></textarea>
    </div>

    <button type="submit" class="btn btn-dark">Confirm Booking</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
