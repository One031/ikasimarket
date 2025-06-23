<?php
require_once '../functions/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = (int)$_POST['order_id']; // Cast order_id to int
    $status = $_POST['status']; // New status value
    $pickup_location = trim($_POST['pickup_location']); // Pickup location
    $eta = trim($_POST['eta']); // Estimated time of arrival

    // Validate status against allowed values
    $allowed = ['Pending', 'In Progress', 'Ready', 'Completed'];
    if (!in_array($status, $allowed)) {
        die("Invalid status");
    }

    // Update order details
    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = ?, pickup_location = ?, eta = ? 
        WHERE order_id = ?
    ");
    $stmt->bind_param("sssi", $status, $pickup_location, $eta, $order_id);
    $stmt->execute();
}

header("Location: seller-orders.php?success=1");
exit;
?>