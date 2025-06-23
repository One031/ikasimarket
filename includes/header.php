<!-- includes/header.php -->
<?php
//Start session if there isn't one already
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
//Database connection in functions folder
require_once "functions/db.php";
//Check if user has a store
$has_store = false;
$has_listings = false;

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  // Check if user has any stores
  $store_check = $conn->prepare("SELECT store_id FROM stores WHERE user_id = ?");
  $store_check->bind_param("i", $user_id);
  $store_check->execute();
  $store_check->store_result();
  $has_store = $store_check->num_rows > 0;
  $store_check->close();

  // Check if user has any listings
  $listings_check = $conn->prepare("SELECT listing_id FROM listings WHERE user_id = ?");
  $listings_check->bind_param("i", $user_id);
  $listings_check->execute();
  $listings_check->store_result();
  $has_listings = $listings_check->num_rows > 0;
  $listings_check->close();
}
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>iKasiMarket</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles.css" />
</head>

<body class="d-flex flex-column min-vh-100">
  <!-- Navbar -->
  <header class="p-3 text-bg-dark">
    <nav class="navbar navbar-expand-lg navbar-dark container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="assets/shopping-cart1.png" width="40" height="32" alt="Shopping cart icon" />
        <span class="fs-4 fw-bold">iKasiMarket</span>
      </a>

      <!-- Mobile toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-between" id="navbarMain">
        <!-- Search -->
        <form class="d-flex mx-auto my-2 my-lg-0 w-75 w-lg-50" role="search" method="GET" action="search.php">
          <input name="search" class="form-control form-control-dark text-bg-dark text-white placeholder-white" placeholder="Search for products, sellers..." aria-label="Search" required>
          <button type="submit" class="btn btn-warning"><i class="bi bi-search"></i></button>
        </form>

        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Authenticated user quick links -->
          <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
            <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
              <a href="../ikasimarket/admin/index.php" class="btn btn-sm btn-outline-warning">Admin</a>
            <?php endif; ?>
            <a href="saved.php" title="Saved" class="text-white"><i class="bi bi-heart fs-5"></i></a>
            <a href="chats.php" title="Chats" class="text-white"><i class="bi bi-chat-dots fs-5"></i></a>
            <?php if ($has_store || $has_listings): ?>
              <a href="my-store.php" class="text-white" title="<?= $has_store ? 'Manage Store' : 'Manage Listings' ?>">
                <i class="bi bi-shop fs-5"></i>
              </a>
            <?php endif; ?>
            <a href="post.php" class="text-white d-flex align-items-center gap-1">
              <i class="bi bi-plus-circle-fill"></i><span class="d-none d-md-inline">Post</span>
            </a>
            <!-- Dropdown menu -->
            <div class="dropdown">
              <a href="#" class="d-block text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile" width="32" height="32" class="rounded-circle">
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="account.php">Account</a></li>
                <li><a class="dropdown-item" href="my-orders.php">My Orders</a></li>
                <?php if ($has_store || $has_listings): ?>
                  <li><a class="dropdown-item" href="seller-orders.php">Manage Orders</a></li>
                <?php endif; ?>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="actions/logout.php">Log out</a></li>
              </ul>
            </div>
          </div>
        <?php else: ?>
          <!-- Login/Signup buttons -->
          <div class="text-end mt-3 mt-lg-0">
            <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#LoginModal">Login</button>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#SignupModal">Sign-up</button>
          </div>
        <?php endif; ?>
      </div>
    </nav>
  </header>