document.addEventListener("DOMContentLoaded", () => {
  // Anti-Scraping Logic for Email and Phone
  const secureLinks = document.querySelectorAll(".secure-link");

  secureLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      // Get the prefix (mailto: or tel:)
      const action = this.getAttribute("data-action");

      // Get the Base64 encoded string and decode it natively
      const b64Data = this.getAttribute("data-b64");
      const decodedString = atob(b64Data);

      // Execute the system action
      window.location.href = action + decodedString;
    });
  });
});
