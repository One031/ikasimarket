<?php
require_once "../functions/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST['listing_id'];

  // Prepare and execute delete query to prevent SQL injection
  $stmt = $conn->prepare("DELETE FROM listings WHERE listing_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: listings.php"); // Redirect back to listings page
exit;
?>
