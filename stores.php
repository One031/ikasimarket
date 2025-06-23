<?php
require_once "functions/db.php";
include("includes/header.php");

// Fetch all stores and their owners
$query = "
  SELECT s.store_id, s.store_name, s.description, s.category, s.location, u.name AS owner_name
  FROM stores s
  JOIN users u ON s.user_id = u.user_id
  ORDER BY s.created_at DESC
";

$result = $conn->query($query);
?>

<!-- Page Content -->
<div class="container py-5">
  <h2 class="mb-4">Browse Stores</h2>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($store = $result->fetch_assoc()): ?>
        <div class="col">
          <div class="card shadow-sm card-hover h-100 w-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($store['store_name']) ?></h5>
              <p class="text-muted mb-1"><strong>Owner:</strong> <?= htmlspecialchars($store['owner_name']) ?></p>
              <p class="text-muted mb-1"><strong>Location:</strong> <?= htmlspecialchars($store['location']) ?></p>
              <p class="text-muted mb-2"><strong>Category:</strong> <?= htmlspecialchars($store['category']) ?></p>
              <p><?= nl2br(htmlspecialchars($store['description'])) ?></p>
              <a href="store.php?id=<?= $store['store_id'] ?>" class="btn btn-sm btn-dark w-100 mt-2">View Store</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-muted">No stores have been created yet.</p>
    <?php endif; ?>

  </div>
</div>

<?php include("includes/footer.php"); ?>
