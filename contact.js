// Scroll to Top Button Logic
const scrollBtn = document.getElementById("scrollTopBtn");
window.onscroll = function () {
  scrollBtn.style.display =
    document.documentElement.scrollTop > 300 ? "block" : "none";

  // Change header background on scroll
  const header = document.getElementById("main-header");
  header.style.backgroundColor =
    window.scrollY > 50 ? "#062f70" : "#0b3d91";
};

// Smooth scroll to top
scrollBtn.onclick = function () {
  window.scrollTo({ top: 0, behavior: "smooth" });
};

// Simple Form Validation Alert
document.getElementById("contactForm").addEventListener("submit", function (e) {
  e.preventDefault();
  alert("Thank you for your message! We will get back to you soon.");
  this.reset();
});
