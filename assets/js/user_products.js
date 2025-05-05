document.addEventListener("DOMContentLoaded", function () {
    const addToCartButtons = document.querySelectorAll(".add-to-cart");

    addToCartButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const productId = this.getAttribute("data-product-id");

            fetch("../../includes/logic/cart_functions.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `action=add&id=${productId}&quantity=1`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Đã thêm sản phẩm vào giỏ hàng!");
                        // Bạn có thể cho chuyển hướng luôn:
                        // window.location.href = "checkout.php";
                    } else {
                        alert("Lỗi khi thêm vào giỏ hàng.");
                    }
                });
        });
    });
});
