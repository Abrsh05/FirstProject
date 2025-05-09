// Show/hide scroll to top button
const scrollBtn = document.getElementById("scrollTopBtn");
window.onscroll = function () {
  if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
    scrollBtn.style.display = "block";
  } else {
    scrollBtn.style.display = "none";
  }

  // Optional: Change header background when scrolling
  const header = document.getElementById("main-header");
  if (window.scrollY > 50) {
    header.style.backgroundColor = "#06285f";
  } else {
    header.style.backgroundColor = "#0b3d91";
  }
};

// Smooth scroll to top
scrollBtn.onclick = function () {
  window.scrollTo({ top: 0, behavior: "smooth" });
};
