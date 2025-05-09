// document.addEventListener("DOMContentLoaded", () => {
//     const images = document.querySelectorAll(".gallery img");
  
//     images.forEach(img => {
//       img.addEventListener("click", () => {
//         const overlay = document.createElement("div");
//         overlay.className = "image-overlay";
//         overlay.innerHTML = `
//           <div class="popup">
//             <img src="${img.src}" alt="${img.alt}" />
//             <span class="close-btn">&times;</span>
//           </div>
//         `;
//         document.body.appendChild(overlay);
  
//         overlay.querySelector(".close-btn").addEventListener("click", () => {
//           overlay.remove();
//         });
//       });
//     });
//   });
  