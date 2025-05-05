// <!-- JavaScript ở cuối trang để cải thiện performance -->

document.addEventListener("DOMContentLoaded", function () {
  // Lấy các phần tử cần thiết
  const productCheckboxes = document.querySelectorAll(".product-checkbox");
  const selectAllCheckbox = document.getElementById("selectAll");
  const selectAllBtn = document.getElementById("selectAllBtn");
  const cartTotalElement = document.getElementById("cartTotal");
  const quantityInputs = document.querySelectorAll(".quantity-input");
  const checkoutSelectedBtn = document.getElementById("checkoutSelected");
  const removeButtons = document.querySelectorAll(".remove-btn");

  // Hàm cập nhật tổng số tiền dựa trên các sản phẩm đã chọn
  function updateCartTotal() {
    let total = 0;
    const selectedProducts = [];

    productCheckboxes.forEach(function (checkbox) {
      if (checkbox.checked) {
        const row = checkbox.closest(".product-row");
        const productId = row.dataset.id;
        const subtotalElement = row.querySelector(".product-subtotal");
        const subtotal = parseInt(subtotalElement.getAttribute("data-value"));

        total += subtotal;
        selectedProducts.push(productId);
      }
    });

    cartTotalElement.innerHTML =
      "<strong>" + numberWithCommas(total) + " VNĐ</strong>";

    checkoutSelectedBtn.textContent =
      "Thanh toán đơn hàng" +
      (selectedProducts.length > 0
        ? " (" + selectedProducts.length + " sản phẩm)"
        : "");

    if (selectedProducts.length > 0) {
      checkoutSelectedBtn.disabled = false;
      checkoutSelectedBtn.classList.remove("disabled");
    } else {
      checkoutSelectedBtn.disabled = true;
      checkoutSelectedBtn.classList.add("disabled");
    }

    const allChecked =
      document.querySelectorAll(".product-checkbox").length > 0 &&
      Array.from(document.querySelectorAll(".product-checkbox")).every(
        (cb) => cb.checked
      );
    selectAllCheckbox.checked = allChecked;
    selectAllBtn.textContent = allChecked ? "Bỏ chọn tất cả" : "Chọn tất cả";

    return selectedProducts;
  }

  // Định dạng số tiền với dấu phẩy
  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  // Tính toán lại thành tiền cho một sản phẩm
  function updateProductSubtotal(quantityInput) {
    const row = quantityInput.closest(".product-row");
    const priceText = row.querySelector(".product-price").textContent;
    const price = parseInt(priceText.replace(/[^\d]/g, ""));
    const quantity = parseInt(quantityInput.value) || 1;
    const subtotal = price * quantity;

    const subtotalElement = row.querySelector(".product-subtotal");
    subtotalElement.textContent = numberWithCommas(subtotal) + " VNĐ";
    subtotalElement.setAttribute("data-value", subtotal);

    if (row.querySelector(".product-checkbox").checked) {
      updateCartTotal();
    }
  }

  // Xử lý khi thay đổi số lượng
  quantityInputs.forEach(function (input) {
    input.addEventListener("change", function () {
      if (this.value < 1) this.value = 1; // Đảm bảo số lượng không nhỏ hơn 1
      updateProductSubtotal(this);
      // Tự động submit form để cập nhật session
      const form = document.getElementById("cartForm");
      const hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.name = "update_cart";
      hiddenInput.value = "true";
      form.appendChild(hiddenInput);
      form.submit();
    });
  });

  // Xử lý khi chọn/bỏ chọn một sản phẩm
  productCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      const row = this.closest(".product-row");
      if (this.checked) {
        row.classList.add("selected");
      } else {
        row.classList.remove("selected");
      }
      updateCartTotal();
    });
  });

  // Xử lý nút "Chọn tất cả" trong thead
  selectAllCheckbox.addEventListener("change", function () {
    const isChecked = this.checked;

    productCheckboxes.forEach(function (checkbox) {
      checkbox.checked = isChecked;
      const row = checkbox.closest(".product-row");
      if (isChecked) {
        row.classList.add("selected");
      } else {
        row.classList.remove("selected");
      }
    });

    updateCartTotal();
  });

  // Xử lý nút "Chọn tất cả" ở dưới
  selectAllBtn.addEventListener("click", function () {
    const allChecked = Array.from(productCheckboxes).every((cb) => cb.checked);
    const newState = !allChecked;

    productCheckboxes.forEach(function (checkbox) {
      checkbox.checked = newState;
      const row = checkbox.closest(".product-row");
      if (newState) {
        row.classList.add("selected");
      } else {
        row.classList.remove("selected");
      }
    });

    selectAllCheckbox.checked = newState;
    updateCartTotal();
  });

  // Xử lý nút "Thanh toán đơn hàng"
  checkoutSelectedBtn.addEventListener("click", function (e) {
    e.preventDefault();

    const selectedProducts = updateCartTotal();

    if (selectedProducts.length === 0) {
      alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán");
      return;
    }

    const form = document.getElementById("cartForm");
    form.action = "checkout.php";

    // Tạo input ẩn để gửi cờ checkout
    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "checkout_selected";
    hiddenInput.value = "true";
    form.appendChild(hiddenInput);

    // Gửi danh sách sản phẩm đã chọn và số lượng
    selectedProducts.forEach(function (productId) {
      const quantityInput = document.querySelector(
        `input[name="quantity[${productId}]"]`
      );
      const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
      const quantityHiddenInput = document.createElement("input");
      quantityHiddenInput.type = "hidden";
      quantityHiddenInput.name = `quantity[${productId}]`;
      quantityHiddenInput.value = quantity;
      form.appendChild(quantityHiddenInput);

      const selectedInput = document.createElement("input");
      selectedInput.type = "hidden";
      selectedInput.name = "selected_products[]";
      selectedInput.value = productId;
      form.appendChild(selectedInput);
    });

    form.submit();
  });

  // Xử lý nút xóa sản phẩm không cần reload trang
  removeButtons.forEach(function (button) {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      const productId = this.getAttribute("data-id");
      const row = this.closest(".product-row");

      row.style.opacity = 0;

      setTimeout(function () {
        row.remove();

        if (document.querySelectorAll(".product-row").length === 0) {
          document.getElementById("content").innerHTML = `
                  <div class="empty-cart">
                    <p>Giỏ hàng của bạn đang trống.</p>
                    <a href="applications.php" class="continue-shopping">Tiếp tục mua sắm</a>
                  </div>
                `;
        } else {
          updateCartTotal();
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "cart.php?remove=" + productId, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send();

        const cartCount = document.querySelector(".cart-count");
        if (cartCount) {
          const currentCount = parseInt(cartCount.textContent);
          cartCount.textContent = Math.max(0, currentCount - 1);
        }
      }, 300);
    });
  });

  // Cập nhật tổng tiền ban đầu
  updateCartTotal();
});
