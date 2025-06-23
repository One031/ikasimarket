<?php
require_once "functions/db.php";
include("includes/header.php");

// Check for product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container py-5'><p class='text-danger'>Invalid product ID.</p></div>";
    include("includes/footer.php");
    exit;
}



$listing_id = (int) $_GET['id'];

// Fetch product and seller info
$stmt = $conn->prepare("
    SELECT l.title, l.description, l.user_id, l.price, l.image_path, l.location, l.contact_phone, l.created_at, u.name AS seller_name
    FROM listings l
    JOIN users u ON l.user_id = u.user_id
    WHERE l.listing_id = ?
");
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container py-5'><p class='text-danger'>Product not found.</p></div>";
    include("includes/footer.php");
    exit;
}

$product = $result->fetch_assoc();

// Check if current user is the seller
$is_seller = $is_logged_in && $_SESSION['user_id'] === $product['user_id'];
?>

<!-- Product Details Section -->
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($product['image_path']) ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['title']) ?>">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3"><?= htmlspecialchars($product['title']) ?></h1>
            <h4 class="text-success mb-3">R<?= number_format($product['price'], 2) ?></h4>
            <p class="text-muted">Posted by <strong><?= htmlspecialchars($product['seller_name']) ?></strong></p>
            <p class="text-muted">Location: <?= htmlspecialchars($product['location']) ?></p>
            <p class="text-muted">Posted on: <?= date("F j, Y", strtotime($product['created_at'])) ?></p>
            <hr>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <!-- Show contact and booking buttons if viewer is not the seller -->
            <?php if (!$is_seller): ?>
                <a
                    href="<?= $is_logged_in ? 'chat.php?with=' . $product['user_id'] : '#' ?>"
                    class="btn btn-dark mt-3 contact-seller-btn"
                    <?= !$is_logged_in ? 'data-login-required="true"' : '' ?>>
                    Contact Seller
                </a>
                <a
                    href="<?= $is_logged_in ? 'booking.php?id=' . $listing_id : '#' ?>"
                    class="btn btn-outline-dark mt-3 book-now-btn"
                    <?= !$is_logged_in ? 'data-login-required="true"' : '' ?>>
                    Book / Order Now
                </a>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>