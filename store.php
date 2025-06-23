<?php
require_once "functions/db.php";
include("includes/header.php");

// Validate store ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<div class='container py-5'><p class='text-danger'>Invalid store ID.</p></div>";
  include("includes/footer.php");
  exit;
}

$store_id = (int) $_GET['id'];

// Fetch store info and owner
$stmt = $conn->prepare("
  SELECT s.store_name, s.description, s.category, s.location, u.name AS owner
  FROM stores s
  JOIN users u ON s.user_id = u.user_id
  WHERE s.store_id = ?
");
$stmt->bind_param("i", $store_id);
$stmt->execute();
$store = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If store not found
if (!$store) {
  echo "<div class='container py-5'><p class='text-danger'>Store not found.</p></div>";
  include("includes/footer.php");
  exit;
}

// Fetch listings
$products_stmt = $conn->prepare("
  SELECT l.listing_id, l.title, l.price, l.image_path, l.payment_method
  FROM listings l
  WHERE l.store_id = ?
  ORDER BY l.created_at DESC
");
$products_stmt->bind_param("i", $store_id);
$products_stmt->execute();
$products = $products_stmt->get_result();
?>

<!-- Store Info -->
<div class="container py-5">
  <h2 class="mb-1"><?= htmlspecialchars($store['store_name']) ?></h2>
  <p class="text-muted mb-2">Owned by <?= htmlspecialchars($store['owner']) ?> | <?= htmlspecialchars($store['location']) ?> | Category: <?= htmlspecialchars($store['category']) ?></p>
  <p class="mb-4"><?= nl2br(htmlspecialchars($store['description'])) ?></p>

  <!-- Store Products -->
  <h4 class="mb-3">Products</h4>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php if ($products->num_rows > 0): ?>
      <?php while ($row = $products->fetch_assoc()): ?>
        <div class="col">
          <div class="card shadow-sm h-100 card-hover">
            <img src="<?= htmlspecialchars($row['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['title']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
              <p class="fw-bold">Price: R<?= number_format($row['price'], 2) ?></p>
              <p class="text-muted mb-1"><i class="bi bi-credit-card-2-back-fill me-1"></i>Payment: <?= ucfirst($row['payment_method']) ?></p>
              <a href="product.php?id=<?= $row['listing_id'] ?>" class="btn btn-sm btn-dark w-100 mt-2">View Product</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-muted">This store hasn't added any products yet.</p>
    <?php endif; ?>
  </div>
</div>

<?php include("includes/footer.php"); ?>
