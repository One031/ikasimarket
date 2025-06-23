<?php
require_once "functions/db.php";
include("includes/header.php");

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=login_required");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all saved listings for the user
$stmt = $conn->prepare("
  SELECT l.listing_id, l.title, l.price, l.payment_method, l.location, l.image_path, u.name AS seller
  FROM saved_listings s
  JOIN listings l ON s.listing_id = l.listing_id
  JOIN users u ON l.user_id = u.user_id
  WHERE s.user_id = ?
  ORDER BY s.saved_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Saved Products -->
<div class="container py-5">
    <h2 class="mb-4">Saved Products</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card shadow-sm card-hover h-100 w-100">
                        <img src=" <?= (!empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://placeholder.pagebee.io/api/plain/300/200?text=Custom+Text') ?>" class="card-img-top " alt=" <?= htmlspecialchars($row['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="fw-bold">Price: R<?= number_format($row['price'], 2) ?></p>
                            <p class="text-muted">Seller: <?= htmlspecialchars($row['seller']) ?></p>
                            <p class="text-muted mb-1"><i class="bi bi-credit-card-2-back-fill me-1">
                                </i> Payment: <?= ucfirst($row['payment_method']) ?></p>
                            <p class="text-muted mb-0"> <i class="bi bi-geo-alt-fill text-danger me-1">
                                </i> <?= htmlspecialchars($row['location']) ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="product.php?id=<?= $row['listing_id'] ?>" class="btn btn-sm btn-dark w-75">View Product</a>
                                <form method="post" action="actions/unsave.php">
                                    <input type="hidden" name="listing_id" value="<?= $row['listing_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-2" title="Remove from saved">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">You haven’t saved any products yet.</p>
        <?php endif; ?>

    </div>
</div>

<?php include("includes/footer.php"); ?>