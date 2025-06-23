<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php?error=login_required");
    exit();
}

require_once "functions/db.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $store_name = trim($_POST["store_name"]);
    $description = trim($_POST["description"]);
    $category = $_POST["category"];
    $location = trim($_POST["location"]);
    $phone = trim($_POST["contact_phone"]);
    $user_id = $_SESSION["user_id"];

    // Insert store details into db
    $stmt = $conn->prepare("INSERT INTO stores (user_id, store_name, description, category, location, contact_phone) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $store_name, $description, $category, $location, $phone);

    if ($stmt->execute()) {
        header("Location: my-store.php");
        exit();
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>



<?php include('includes/header.php'); ?>

<!-- Seller Registration Form -->
<div class="container py-5">
    <h2 class="mb-4">Join as a Seller</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="join.php" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Store Name</label>
            <input type="text" name="store_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <option value="">Select category</option>
                <option value="Food">Food</option>
                <option value="Clothing">Clothing</option>
                <option value="Crafts">Crafts</option>
                <option value="Household">Household</option>
                <option value="Services">Services</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Phone</label>
            <input type="text" name="contact_phone" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-dark w-100">Create My Store</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>