<?php
include "includes/header.php";
require_once "../functions/db.php";

// Fetch all messages with sender and receiver names
$result = $conn->query("
  SELECT m.id, m.message, m.sent_at, u1.name AS sender, u2.name AS receiver
  FROM messages m
  JOIN users u1 ON m.sender_id = u1.user_id
  JOIN users u2 ON m.receiver_id = u2.user_id
  ORDER BY m.sent_at DESC
");
?>

<h3>Moderate Chat Messages</h3>

<!-- Messages Table -->
<table class="table table-bordered table-hover mt-3">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>From</th>
      <th>To</th>
      <th>Message</th>
      <th>Time</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['sender']) ?></td>
        <td><?= htmlspecialchars($row['receiver']) ?></td>
        <td><?= htmlspecialchars($row['message']) ?></td>
        <td><?= $row['sent_at'] ?></td>
        <td>
          <!-- Delete button with confirmation -->
          <form method="POST" action="actions/delete-message.php" onsubmit="return confirm('Delete this message?')">
            <input type="hidden" name="message_id" value="<?= $row['id'] ?>">
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include "includes/footer.php"; ?>
