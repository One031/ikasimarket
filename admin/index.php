<?php
session_start();

// Redirect to login if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}
require_once "../functions/db.php";
include "includes/header.php";

// Fetch count for dashboard stats
$users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$listings = $conn->query("SELECT COUNT(*) FROM listings")->fetch_row()[0];
$orders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
?>

<!-- Dashboard Content -->
<div class="container py-5 ">
  <h1>Admin Dashboard</h1>
  <p>Total Users: <?= $users ?></p>
  <p>Total Listings: <?= $listings ?></p>
  <p>Total Orders: <?= $orders ?></p>

  <a href="users.php" class="btn btn-outline-dark me-2">Manage Users</a>
  <a href="listings.php" class="btn btn-outline-dark me-2">Manage Listings</a>
  <a href="chats.php" class="btn btn-outline-dark">Moderate Chats</a>
</div>

<?php include "includes/footer.php"; ?>
