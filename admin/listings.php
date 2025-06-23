<?php
include "includes/header.php";
require_once "../functions/db.php";

// Fetch all listings
$result = $conn->query("SELECT l.listing_id, l.title, u.name AS seller FROM listings l JOIN users u ON l.user_id = u.user_id ORDER BY l.listing_id DESC");
?>

<h3>Manage Listings</h3>

<!-- Listings Table -->
<table class="table table-bordered table-hover mt-3">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Seller</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['listing_id'] ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['seller']) ?></td>
        <td>
          <!-- Delete button with confirmation -->
          <form method="POST" action="actions/delete-listing.php" onsubmit="return confirm('Delete this listing?')">
            <input type="hidden" name="listing_id" value="<?= $row['listing_id'] ?>">
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include "includes/footer.php"; ?>
