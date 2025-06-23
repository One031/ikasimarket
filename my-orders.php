<?php
require_once 'functions/db.php';
include 'includes/header.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=login_required");
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Optional success alert
$showSuccess = isset($_GET['success']) && $_GET['success'] == 1;

// Fetch buyer's orders
$stmt = $conn->prepare("
    SELECT o.*, l.title, l.image_path, u.name AS seller_name
    FROM orders o
    JOIN listings l ON o.listing_id = l.listing_id
    JOIN users u ON o.seller_id = u.user_id
    WHERE o.buyer_id = ?
    ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Orders Page -->
<div class="container py-5">
    <h2 class="mb-4">My Orders</h2>

    <!-- Display success message if redirected after placing an order -->
    <?php if ($showSuccess): ?>
        <div class="alert alert-success">Your booking was placed successfully!</div>
    <?php endif; ?>
    
    <!-- Show list of orders if available -->
    <?php if ($result->num_rows > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($row['image_path'] ?? 'assets/img/placeholder.jpg') ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($row['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="mb-1">Seller: <?= htmlspecialchars($row['seller_name']) ?></p>
                            <p class="mb-1">Payment: <?= ucfirst($row['payment_method']) ?></p>
                            <p class="mb-1"><strong>Pickup Location:</strong> <?= htmlspecialchars($row['pickup_location']) ?: 'Waiting for seller' ?></p>
                            <p class="mb-1"><strong>ETA:</strong> <?= htmlspecialchars($row['eta']) ?: 'Waiting for seller' ?></p>
                            <p class="mb-1">Status: <strong><?= $row['status'] ?></strong></p>
                            <p class="text-muted">Ordered: <?= date("F j, Y", strtotime($row['created_at'])) ?></p>
                            <?php if (!empty($row['message'])): ?>
                                <p class="small text-muted">Note: <?= nl2br(htmlspecialchars($row['message'])) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">You haven’t placed any orders yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>