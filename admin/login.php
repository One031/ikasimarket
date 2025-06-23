<?php
session_start();
require_once "../functions/db.php";

// Handle login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT admin_id, password FROM admins WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  // If user exists
  if ($stmt->num_rows === 1) {
    $stmt->bind_result($admin_id, $hash);
    $stmt->fetch();

    if (password_verify($password, $hash)) {
      $_SESSION['admin_id'] = $admin_id;
      header("Location: index.php");
      exit;
    }
  }

  $error = "Invalid credentials.";
}
?>

<!-- Login page html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login | iKasiMarket</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
  <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
    <h4 class="mb-3">Admin Login</h4>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Login form -->
    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-dark w-100">Login</button>
    </form>
  </div>
</body>
</html>
