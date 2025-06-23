<?php
session_start();
require_once "../functions/db.php";

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listing_id'])) {
  $user_id = $_SESSION['user_id'];
  $listing_id = (int) $_POST['listing_id']; // Cast to int for safety 

  // Add listing to saved_listings, ignore if already saved
  $stmt = $conn->prepare("INSERT IGNORE INTO saved_listings (user_id, listing_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $user_id, $listing_id);
  $stmt->execute();
}

header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect back
