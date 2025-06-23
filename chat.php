<?php
session_start();
require_once "functions/db.php";
include("includes/header.php");

// Redirect if not logged in or the user chatting with is not specified
if (!isset($_SESSION['user_id']) || !isset($_GET['with'])) {
  header("Location: index.php");
  exit;
}

$my_id = $_SESSION['user_id'];
$with_id = (int) $_GET['with'];

// Get user chatting with name
$user_stmt = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
$user_stmt->bind_param("i", $with_id);
$user_stmt->execute();
$user_stmt->bind_result($with_name);
$user_stmt->fetch();
$user_stmt->close();

// Handle sending a message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
  $msg = trim($_POST['message']);
  $insert = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
  $insert->bind_param("iis", $my_id, $with_id, $msg);
  $insert->execute();
}

// Fetch conversation
$chat_stmt = $conn->prepare("
  SELECT sender_id, message, sent_at 
  FROM messages 
  WHERE (sender_id = ? AND receiver_id = ?) 
     OR (sender_id = ? AND receiver_id = ?)
  ORDER BY sent_at
");
$chat_stmt->bind_param("iiii", $my_id, $with_id, $with_id, $my_id);
$chat_stmt->execute();
$messages = $chat_stmt->get_result();
?>

<!-- Chat user interface -->
<div class="container py-5">
  <h4 class="mb-4">Chat with <?= htmlspecialchars($with_name) ?></h4>

  <div class="border p-3 mb-3 bg-light" style="height: 300px; overflow-y: auto;">
    <?php while ($msg = $messages->fetch_assoc()): ?>
      <div class="mb-2 <?= $msg['sender_id'] == $my_id ? 'text-end' : 'text-start' ?>">
        <span class="badge bg-<?= $msg['sender_id'] == $my_id ? 'primary' : 'secondary' ?>">
          <?= htmlspecialchars($msg['message']) ?>
        </span><br>
        <small class="text-muted"><?= date('H:i M j', strtotime($msg['sent_at'])) ?></small>
      </div>
    <?php endwhile; ?>
  </div>

  <form method="post" class="d-flex">
    <input type="text" name="message" class="form-control me-2" placeholder="Type a message..." required>
    <button class="btn btn-dark">Send</button>
  </form>
</div>

<?php include("includes/footer.php"); ?>
