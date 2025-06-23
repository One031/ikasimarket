<?php include('includes/header.php'); ?>
<!-- Hero Section -->
<section class="hero text-center py-5 bg-light">
  <div class="container">
    <h1 class="display-4 fw-bold">Your Kasi. Your Market.</h1>
    <p class="lead">Whether you're selling vetkoek, handmade crafts, or services — reach more people with iKasiMarket.</p>
    <a href="join.php" class="btn btn-dark btn-lg mt-3" <?= !$is_logged_in ? 'data-login-required="true"' : '' ?>>Join as Seller</a>
    <a href="stores.php" class="btn btn-light btn-lg mt-3 ms-2">View Stores</a>
  </div>
</section>

<!-- Featured products section -->

<section class="container my-5">
  <h2 class="mb-4 text-center">Featured Kasi Products</h2>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php
    require_once "functions/db.php";
    // Fetch latest listings and order in descending order
    $sql = "SELECT l.title, l.listing_id, l.price, l.location, l.image_path, l.payment_method, u.name AS seller 
        FROM listings l
        JOIN users u ON l.user_id = u.user_id
        ORDER BY l.created_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo '
    <div class="col">
      <div class="card shadow-sm h-100 w-100 card-hover position-relative">
      <!-- Product Image or placeholder -->
        <img src="' . (!empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://placeholder.pagebee.io/api/plain/300/200?text=') . '" class="card-img-top " alt="' . htmlspecialchars($row['title']) . '">
        <div class="card-body">
          <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
          <p class="fw-bold">Price: R' . number_format($row['price'], 2) . '</p>
          <p class="text-muted">Seller: ' . htmlspecialchars($row['seller']) . '</p>
          <p class="text-muted mb-1"><i class="bi bi-credit-card-2-back-fill me-1">
          </i> Payment: ' . ucfirst($row['payment_method']) . '</p>
          <p class="text-muted mb-0"> <i class="bi bi-geo-alt-fill text-danger me-1">
          </i>' . htmlspecialchars($row['location']) . '</p>
          <div class="d-flex justify-content-between">
            <a href="product.php?id=' . $row['listing_id'] . '" class="btn btn-sm btn-dark w-75">View Product</a>
            <!-- Save Button -->
            <form method="post" action="actions/save.php" class="ms-2" ' .
          (!$is_logged_in ? 'data-login-required="true"' : '') . '>
  <input type="hidden" name="listing_id" value="' . $row['listing_id'] . '">
  <button type="submit" class="btn btn-sm btn-outline-danger" title="Save listing">
    <i class="bi bi-heart"></i> 
  </button>
</form>
          </div>
        </div>
      </div>
    </div>';
      }
    } else {
      echo "<p>No products available right now.</p>";
    }
    ?>
  </div>
</section>

<?php include('includes/footer.php'); ?>