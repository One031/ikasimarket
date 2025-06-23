<?php
session_start();
require_once "functions/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=login_required");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user has a store
$stmt = $conn->prepare("SELECT store_id FROM stores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (!$store_id) {
    header("Location: join.php?error=no_store");
    exit();
}

// Delete product
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $del_stmt = $conn->prepare("DELETE FROM listings WHERE listing_id = ? AND user_id = ?");
    $del_stmt->bind_param("ii", $delete_id, $user_id);
    $del_stmt->execute();
    $del_stmt->close();
    header("Location: my-store.php?delete=success");
    exit();
}

// Add or update product
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $category = $_POST["category"];
    $price = $_POST["price"];
    $image_path = null;

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $filename = uniqid() . "-" . basename($_FILES["image"]["name"]);
        $target = "uploads/" . $filename;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
        $image_path = $target;
    }

    if (isset($_POST['edit_id'])) {
        $edit_id = intval($_POST['edit_id']);
        $stmt = $conn->prepare("UPDATE listings SET title=?, description=?, category=?, price=?, image_path=? WHERE listing_id=? AND user_id=?");
        $stmt->bind_param("sssdsii", $title, $description, $category, $price, $image_path, $edit_id, $user_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO listings (user_id, title, description, category, price, image_path, store_id) VALUES (?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("isssdss", $user_id, $title, $description, $category, $price, $image_path, $store_id);
    }

    if ($stmt->execute()) {
        header("Location: my-store.php?add=success");
        exit();
    } else {
        $error = "Failed to save product.";
    }
}

include('includes/header.php');
?>

<div class="container py-5">
    <h2 class="mb-4">Add or Edit Product</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <!-- Product Form -->
    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <?php if (isset($_GET['edit'])): ?>
            <?php
            $edit_id = intval($_GET['edit']);
            $edit_query = $conn->prepare("SELECT title, description, category, price FROM listings WHERE listing_id = ? AND user_id = ?");
            $edit_query->bind_param("ii", $edit_id, $user_id);
            $edit_query->execute();
            $edit_query->bind_result($etitle, $edesc, $ecat, $eprice);
            $edit_query->fetch();
            ?>
            <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= $etitle ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= $ecat ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (R)</label>
            <input type="number" name="price" class="form-control" value="<?= $eprice ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required><?= $edesc ?? '' ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-dark w-100">
            <?= isset($edit_id) ? 'Update Product' : 'Add Product' ?>
        </button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
