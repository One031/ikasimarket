<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php?error=login_required");
    exit();
}

require_once "functions/db.php";

$user_id = $_SESSION["user_id"];
$query = $conn->prepare("SELECT name, email, phone FROM users WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($name, $email, $phone);
$query->fetch();
$query->close();
?>

<?php include('includes/header.php'); ?>

<div class="container py-5">
  <h2 class="mb-4">My Account</h2>

  <!-- Update Profile -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title">Edit Profile</h5>
      <form action="actions/update-profile.php" method="post">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($name) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
        </div>
        <button type="submit" class="btn btn-dark">Update Profile</button>
      </form>
    </div>
  </div>

  <!-- Change Password -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title">Change Password</h5>
      <form action="actions/change-password.php" method="post">
        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="new_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-dark">Change Password</button>
      </form>
    </div>
  </div>

  <!-- Delete Account -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title text-danger">Delete Account</h5>
      <form action="actions/delete-account.php" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
        <button type="submit" class="btn btn-danger">Delete My Account</button>
      </form>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
