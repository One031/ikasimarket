<?php
session_start();
require_once "../functions/db.php";

// Exit user is not logged in 
if (!isset($_SESSION["user_id"])) exit;

// Get and trim user input
$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);
$user_id = $_SESSION["user_id"];

// Update user details
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?");
$stmt->bind_param("sssi", $name, $email, $phone, $user_id);
$stmt->execute();

header("Location: account.php?updated=1");
exit;
