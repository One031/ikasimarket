<?php
require_once 'functions/db.php';
include 'includes/header.php';

// Get search query from URL
$search_query = trim($_GET['search']);

// Only execute search if a query is provided
if ($search_query )
?>

<!-- Search Results Section -->
<div class="container py-5">
    <h3>Search results for: <?= htmlspecialchars($search_query) ?></h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        // SQL statement to search listings by title or seller name
        $stmt = $conn->prepare("
  SELECT l.*, u.name AS seller 
  FROM listings l 
  JOIN users u ON l.user_id = u.user_id 
  WHERE l.title LIKE CONCAT('%', ?, '%') 
     OR u.name LIKE CONCAT('%', ?, '%')
");
        $stmt->bind_param("ss", $search_query, $search_query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <img src=" <?= (!empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'https://placeholder.pagebee.io/api/plain/300/200?text=Custom+Text') ?>" class="card-img-top " alt=" <?= htmlspecialchars($row['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="fw-bold">Price: R<?= number_format($row['price'], 2) ?></p>
                            <p class="text-muted">Seller: <?= htmlspecialchars($row['seller']) ?></p>
                            <a href="product.php?id=<?= $row['listing_id'] ?>" class="btn btn-sm btn-dark">View</a>
                        </div>
                    </div>
                </div>
            <?php endwhile;
        else: ?>
            <p class="text-muted">No results found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>