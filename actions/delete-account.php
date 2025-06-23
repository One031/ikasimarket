<?php
session_start();
require_once "../functions/db.php";

if (!isset($_SESSION["user_id"])) exit;

$user_id = $_SESSION["user_id"];

// Delete related listings
$stmt = $conn->prepare("DELETE FROM listings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Delete related orders (buyer or seller)
$stmt = $conn->prepare("DELETE FROM orders WHERE buyer_id = ? OR seller_id = ?");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$stmt->close();

// Delete user account
$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// End session and redirect
session_destroy();
header("Location: index.php?account_deleted=1");
exit;
?>