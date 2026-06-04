document.addEventListener("DOMContentLoaded", () => {
  // 1. Mobile Menu Toggle
  const menuToggle = document.querySelector(".mobile-menu-toggle");
  const mainNav = document.querySelector(".main-nav");

  if (menuToggle && mainNav) {
    menuToggle.addEventListener("click", () => {
      // Toggle the visibility class
      mainNav.classList.toggle("is-active");

      // A11y: Update aria-expanded for screen readers
      const isExpanded = menuToggle.getAttribute("aria-expanded") === "true";
      menuToggle.setAttribute("aria-expanded", !isExpanded);
    });
  }
});
