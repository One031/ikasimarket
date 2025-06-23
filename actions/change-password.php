<?php
session_start();
require_once "../functions/db.php";

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) exit;

$user_id = $_SESSION["user_id"];
$current = $_POST["current_password"];
$new = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

// Get current hashed password
$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

// Verify current password
if (!password_verify($current, $hash)) {
    die("Incorrect current password.");
}

// Update password with new hash
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
$stmt->bind_param("si", $new, $user_id);
$stmt->execute();

header("Location: account.php?password_changed=1");
exit;
