<?php
session_start();
require_once '../functions/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php?error=login_required");
  exit;
}

// Get order data
$listing_id = $_POST['listing_id'];
$buyer_id = $_SESSION['user_id'];
$seller_id = $_POST['seller_id'];
$message = $_POST['message'];
$payment_method = $_POST['payment_method'];


// Insert new order
$stmt = $conn->prepare("INSERT INTO orders (listing_id, buyer_id, seller_id, message, payment_method) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiss", $listing_id, $buyer_id, $seller_id, $message, $payment_method);
$stmt->execute();

header("Location: my-orders.php?success=1");
exit;
?>