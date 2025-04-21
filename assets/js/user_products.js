// main.js

document.addEventListener("DOMContentLoaded", function () {
  // Gắn sự kiện khi click nút "Chi tiết"
  const detailButtons = document.querySelectorAll(".product-card .button a");

  detailButtons.forEach(function (button) {
    button.addEventListener("click", function (e) {
      // Ngăn không cho chuyển trang ngay (nếu muốn)
      // e.preventDefault();

      const productName =
        this.closest(".product-card").querySelector("h2").textContent;
      alert(`Bạn đã chọn xem chi tiết sản phẩm: ${productName}`);
    });
  });

  // Hiệu ứng hover cho thẻ sản phẩm
  const productCards = document.querySelectorAll(".product-card");

  productCards.forEach(function (card) {
    card.addEventListener("mouseenter", function () {
      card.style.boxShadow = "0 0 10px rgba(112, 56, 156, 0.6)";
      card.style.transform = "scale(1.02)";
      card.style.transition = "all 0.3s ease";
    });

    card.addEventListener("mouseleave", function () {
      card.style.boxShadow = "0 0 3px rgb(150, 150, 150)";
      card.style.transform = "scale(1)";
    });
  });
});
