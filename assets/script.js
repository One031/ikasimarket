  // Handles elements that require login before proceeding
  document.addEventListener("DOMContentLoaded", function () {
    // Intercept links or buttons
    document.querySelectorAll('[data-login-required="true"]').forEach(el => {
      el.addEventListener("click", function (e) {
        e.preventDefault();
        const loginModal = new bootstrap.Modal(document.getElementById("LoginModal"));
        loginModal.show();
      });

      // Intercept form submission
      if (el.tagName === "FORM") {
        el.addEventListener("submit", function (e) {
          e.preventDefault();
          const loginModal = new bootstrap.Modal(document.getElementById("LoginModal"));
          loginModal.show();
        });
      }
    });
  });

  // Displays the online payment input section when toggled
  document.getElementById('payment_method').addEventListener('change', function () {
  document.getElementById('online-payment').style.display = 
    this.value === 'online' ? 'block' : 'none';
});
