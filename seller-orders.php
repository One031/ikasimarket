<?php
require_once 'functions/db.php';
include 'includes/header.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=login_required");
    exit;
}

// Check for update success message
$showSuccess = isset($_GET['success']) && $_GET['success'] == 1;

$seller_id = $_SESSION['user_id'];

// Fetch all orders where current user is the seller
$stmt = $conn->prepare("
    SELECT o.*, l.title, l.image_path, u.name AS buyer_name
    FROM orders o
    JOIN listings l ON o.listing_id = l.listing_id
    JOIN users u ON o.buyer_id = u.user_id
    WHERE o.seller_id = ?
    ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Orders list -->
<div class="container py-5">
    <h2 class="mb-4">Orders Received</h2>

    <!-- Success message -->
    <?php if ($showSuccess): ?>
        <div class="alert alert-success">Order Updated!</div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($row['image_path'] ?? 'assets/img/placeholder.jpg') ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($row['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="mb-1">Buyer: <?= htmlspecialchars($row['buyer_name']) ?></p>
                            <p class="mb-1">Payment: <?= ucfirst($row['payment_method']) ?></p>
                            <p class="mb-1">Ordered: <?= date("F j, Y", strtotime($row['created_at'])) ?></p>
                            <?php if (!empty($row['message'])): ?>
                                <p class="small text-muted">Message: <?= nl2br(htmlspecialchars($row['message'])) ?></p>
                            <?php endif; ?>
                            
                            <!-- Update form -->
                            <form action="actions/update-status.php" method="post" class="mt-3">
                                <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">

                                <div class="mb-2">
                                    <label class="form-label">Pickup Location</label>
                                    <input type="text" name="pickup_location" class="form-control form-control-sm"
                                        value="<?= htmlspecialchars($row['pickup_location']) ?>">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">ETA</label>
                                    <input type="text" name="eta" class="form-control form-control-sm"
                                        value="<?= htmlspecialchars($row['eta']) ?>">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Order Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option <?= $row['status'] == 'Ready' ? 'selected' : '' ?>>Ready</option>
                                        <option <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-sm btn-dark">Update Order</button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">No orders have been placed for your listings yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>