<?php
session_start();
require_once "functions/db.php";

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=login_required");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user owns a store
$stmt = $conn->prepare("SELECT store_id, store_name, category, location FROM stores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$store = $result->fetch_assoc();

// Redirect if no store is found
if (!$store) {
    header("Location: join.php?error=no_store");
    exit();
}

// Fetch listings by user/store
$listings = $conn->prepare("SELECT listing_id, title, price, category, store_id FROM listings WHERE user_id = ?");
$listings->bind_param("i", $user_id);
$listings->execute();
$products = $listings->get_result();
?>

<?php include('includes/header.php'); ?>

<!-- Store Management Page -->
<div class="container py-5">
    <h2 class="mb-4">Manage Your Store</h2>

    <!-- Store Info -->
    <div class="mb-4">
        <h4><?= htmlspecialchars($store['store_name']) ?></h4>
        <p><strong>Category:</strong> <?= $store['category'] ?> | <strong>Location:</strong> <?= $store['location'] ?></p>
        <a href="add-product.php" class="btn btn-dark btn-sm">Add New Product</a>
    </div>

    <hr>

    <h5 class="mb-3">Your Products</h5>
    <!-- Show product listings -->
    <?php if ($products->num_rows > 0): ?>
        <div class="list-group">
            <?php while ($item = $products->fetch_assoc()): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($item['title']) ?></strong>
                        <span class="text-muted">(<?= $item['category'] ?>)</span>
                        <?php if (empty($item['store_id'])): ?>
                            <span class="badge bg-warning text-dark ms-2">Personal</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <span class="badge bg-secondary">R<?= number_format($item['price'], 2) ?></span>
                        <a href="add-product.php?edit=<?= $item['listing_id'] ?>" class="btn btn-sm btn-outline-dark">Edit</a>
                        <a href="add-product.php?delete=<?= $item['listing_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this listing?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">You haven’t added any products yet.</p>
    <?php endif; ?>

</div>

<?php include('includes/footer.php'); ?>