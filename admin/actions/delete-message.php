<?php
require_once "../functions/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST['message_id'];

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: chats.php"); // Redirect back to chat moderation page
exit;
?>
