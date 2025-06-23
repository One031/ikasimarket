<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect to login if admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin | iKasiMarket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="../assets/styles.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
  <!-- Admin Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="index.php">iKasiMarket Admin</a>

    <!-- Navigation links -->
    <div class="ms-auto">
      <a href="index.php" class="text-white me-3 text-decoration-none">Dashboard</a>
      <a href="users.php" class="text-white me-3 text-decoration-none">Users</a>
      <a href="listings.php" class="text-white me-3 text-decoration-none">Listings</a>
      <a href="chats.php" class="text-white me-3 text-decoration-none">Chats</a>
      <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </nav>