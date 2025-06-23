<?php
include "includes/header.php";
require_once "../functions/db.php";

// Fetch all users
$result = $conn->query("SELECT user_id, name, email, phone FROM users ORDER BY user_id DESC");
?>

<h3>Manage Users</h3>

<!-- Users Table -->
<table class="table table-bordered table-hover mt-3">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($user = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $user['user_id'] ?></td>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['phone']) ?></td>
        <td>
          <!-- Delete button with confirmation -->
          <form method="POST" action="actions/delete-user.php" onsubmit="return confirm('Delete this user?')">
            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include "includes/footer.php"; ?>
