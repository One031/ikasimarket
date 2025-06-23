<?php
session_start();
require_once "functions/db.php";
include("includes/header.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$my_id = $_SESSION['user_id'];

// Retrieve list of users that the current user has messaged or received messages from
$stmt = $conn->prepare("
  SELECT DISTINCT u.user_id, u.name
  FROM users u
  JOIN messages m ON (u.user_id = m.sender_id OR u.user_id = m.receiver_id)
  WHERE u.user_id != ? AND (m.sender_id = ? OR m.receiver_id = ?)
");
$stmt->bind_param("iii", $my_id, $my_id, $my_id);
$stmt->execute();
$users = $stmt->get_result();
?>

<!-- Diplay chat list -->
<div class="container py-5">
  <h4 class="mb-4">My Chats</h4>
  <ul class="list-group">
    <?php while ($user = $users->fetch_assoc()): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <?= htmlspecialchars($user['name']) ?>
        <a href="chat.php?with=<?= $user['user_id'] ?>" class="btn btn-sm btn-dark">Open Chat</a>
      </li>
    <?php endwhile; ?>
  </ul>
</div>

<?php include("includes/footer.php"); ?>
