<!-- includes/footer.php -->
<footer class="mt-auto bg-dark text-white text-center p-3">
  <p class="mb-0">© <?= date('Y'); ?> iKasiMarket. All rights reserved.</p>
</footer>


<!-- Error handling for login and signup -->
<?php
$showLoginError = isset($_GET['error']) && $_GET['error'] == 'login';
$showSignupError = isset($_GET['error']) && $_GET['error'] == 'signup';
?>

<!-- Login Modal -->
<div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="LoginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/login.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="LoginModalLabel">Login to iKasiMarket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <?php if ($showLoginError): ?>
        <div class="alert alert-danger">Invalid login credentials.</div>
      <?php endif; ?>
      <div class="modal-body">
        <div class="mb-3">
          <label for="loginEmail" class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="loginEmail" required>
        </div>
        <div class="mb-3">
          <label for="loginPassword" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="loginPassword" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-dark w-100">Login</button>
      </div>
    </form>
  </div>
</div>

<!-- Sign-up Modal -->
<div class="modal fade" id="SignupModal" tabindex="-1" aria-labelledby="SignupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="actions/signup.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="SignupModalLabel">Join iKasiMarket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <?php if ($showSignupError): ?>
        <div class="alert alert-danger">Signup Error.</div>
      <?php endif; ?>
      <div class="modal-body">
        <div class="mb-3">
          <label for="signupName" class="form-label">Name</label>
          <input type="text" name="name" class="form-control" id="signupName" required>
        </div>
        <div class="mb-3">
          <label for="signupEmail" class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="signupEmail" required>
        </div>
        <div class="mb-3">
          <label for="signupPhone" class="form-label">Phone number</label>
          <input type="text" name="phone" class="form-control" id="signupPhone" required>
        </div>
        <div class="mb-3">
          <label for="signupPassword" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="signupPassword" required
            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
            title="Password must be at least 8 characters long, include uppercase, lowercase, a number, and a special character.">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-dark w-100">Sign Up</button>
      </div>
    </form>
  </div>
</div>
<!-- Bootstrap & Custom Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/script.js"></script>
</body>

</html>