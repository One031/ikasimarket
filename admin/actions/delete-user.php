<?php
require_once "../functions/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST['user_id'];

  // Use prepared statement to prevent SQL injection
  $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: users.php"); // Redirect to users management page
exit;
?>
