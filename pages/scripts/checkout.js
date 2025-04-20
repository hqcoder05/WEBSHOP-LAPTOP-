document.addEventListener("DOMContentLoaded", function () {
  // Xử lý hoạt ảnh khi chọn phương thức thanh toán
  const paymentMethods = document.querySelectorAll(
    'input[name="payment_method"]'
  );
  paymentMethods.forEach(function (method) {
    method.addEventListener("change", function () {
      document.querySelectorAll(".payment-method").forEach(function (pm) {
        pm.classList.remove("selected");
      });

      if (this.checked) {
        this.closest(".payment-method").classList.add("selected");
      }
    });

    // Đánh dấu phương thức thanh toán mặc định
    if (method.checked) {
      method.closest(".payment-method").classList.add("selected");
    }
  });

  // Validation Form
  const checkoutForm = document.getElementById("checkoutForm");
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function (e) {
      const requiredFields = [
        "fullname",
        "email",
        "phone",
        "address",
        "city",
        "district",
      ];
      let valid = true;

      requiredFields.forEach(function (field) {
        const input = document.getElementById(field);
        const errorMessage = input.nextElementSibling;

        if (input.value.trim() === "") {
          valid = false;
          input.classList.add("error");

          if (
            !errorMessage ||
            !errorMessage.classList.contains("error-message")
          ) {
            const error = document.createElement("div");
            error.className = "error-message";
            error.textContent = "Vui lòng nhập thông tin này";
            input.parentNode.insertBefore(error, input.nextSibling);
          }
        } else {
          input.classList.remove("error");
          if (
            errorMessage &&
            errorMessage.classList.contains("error-message")
          ) {
            errorMessage.remove();
          }
        }
      });

      if (!valid) {
        e.preventDefault();
      }
    });
  }
});
