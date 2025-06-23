<?php
session_start();
require_once "../functions/db.php";

// Redirect if user not logged in 
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php?error=login_required");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["listing_id"])) {
  $user_id = $_SESSION["user_id"];
  $listing_id = (int) $_POST["listing_id"]; // Cast to int for safety


  // Delete saved listing for this user and listing
  $stmt = $conn->prepare("DELETE FROM saved_listings WHERE user_id = ? AND listing_id = ?");
  $stmt->bind_param("ii", $user_id, $listing_id);
  $stmt->execute();
}

header("Location: " . $_SERVER["HTTP_REFERER"]); // Redirect back
exit;
?>