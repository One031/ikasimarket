<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php?error=login_required");
    exit();
}
require_once "functions/db.php";

$user_id = $_SESSION["user_id"];
$error = null;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $category = $_POST["category"];
    $location = trim($_POST["location"]);
    $phone = trim($_POST["contact_phone"]);
    $price = $_POST["price"];
    $payment_method = $_POST["payment_method"];

    // Handle optional image upload
    $image_path = null;
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $filename = uniqid() . "-" . basename($_FILES["image"]["name"]);
        $target = "uploads/" . $filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $image_path = $target;
        }
    }

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO listings (user_id, title, description, category, location, contact_phone, price, image_path, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $user_id, $title, $description, $category, $location, $phone, $price, $image_path, $payment_method);

    if ($stmt->execute()) {
        header("Location: account.php?post=success");
        exit();
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<?php include('includes/header.php'); ?>

<!-- Product Post Form -->
<div class="container py-5">
    <h2 class="mb-4">Post a New Product</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
                <option value="">Select category</option>
                <option value="Food">Food</option>
                <option value="Clothing">Clothing</option>
                <option value="Services">Services</option>
                <option value="Household">Household</option>
                <option value="Other">Other</option>
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

        <div class="mb-3">
            <label class="form-label">Price (R)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
        </div>

        <label for="payment_method" class="form-label">Accepted Payment Method</label>
        <select name="payment_method" id="payment_method" class="form-select" required>
            <option value="cash">Cash</option>
            <option value="online">Online</option>
            <option value="both">Both</option>
        </select>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-dark w-100">Post</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>