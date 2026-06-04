document.addEventListener("DOMContentLoaded", () => {
  // Select all forms on the page so this script is reusable globally
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      // Find the submit button inside the form that is being submitted
      const submitButton = form.querySelector('button[type="submit"]');

      if (submitButton) {
        // Check if it's already disabled (Double-click protection)
        if (submitButton.disabled) {
          event.preventDefault();
          return;
        }

        // Disable the button to prevent further clicks
        submitButton.disabled = true;

        // Update accessibility state for screen readers
        submitButton.setAttribute("aria-disabled", "true");

        // Provide visual feedback (Store original text first, just in case)
        submitButton.dataset.originalText = submitButton.innerHTML;
        submitButton.innerHTML = 'Please wait... <span class="spinner"></span>';
      }
    });
  });
});
